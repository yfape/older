<?php
/*
 * @Author: your name
 * @Date: 2021-03-04 09:27:26
 * @LastEditTime: 2021-03-08 15:49:42
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\Team.php
 */
namespace app\admin\controller;

use think\facade\Db;
use app\common\model\Team as TeamModel;
use app\common\model\Record as RecordModel;
use app\common\model\Section as SectionModel;
use app\common\model\User as UserModel;

class Team extends AdminCommon
{

    public function list($limit, $page)
    {
        $category = $this->request->param('category/d');
        $category = $category ? [['category', '=', $category]] : true;

        $data = TeamModel::where($category)->append(['statustext'])->paginate([
            'list_rows'=> $limit,
            'page' => $page,
        ]);
        return json($data);
    }

    public function get($tid)
    {
        $team = TeamModel::where(true)->append(['statustext'])->find($tid);
        return json($team);
    }

    public function put($tid, $field, $value=null)
    {
        $team = TeamModel::find($tid);
        $team[$field] = $value;
        $team->save();
        return json(['msg' => '更新成功']);
    }

    public function insert()
    {
        $team = new TeamModel;
        $data = $this->request->except(['update_time', 'create_time', 'delete_time'], 'post');
        $data['count'] = isset($data['count']) ? $data['count'] : 0;
        $data['remain'] = isset($data['remain']) ? $data['remain'] : 0;
        $data['sum'] >= $data['count'] || abort(400, '每场人数必须小于总报名人数');
        $data['sum'] > $data['remain'] || abort(400, '预留人数必须小于总报名人数');
        $data['sur'] = $data['sum'] >= 2 ? $data['sum'] : ($data['sum'] - $data['remain']);
        $data['valid'] = 1;
        $data['remained'] = 0;
        $team->save($data);
        return json(['msg' => '新增成功']);
    }

    public function delete($tid)
    {
        Db::startTrans();
        try{
            $sids = SectionModel::where('tid', $tid)->column('sid');
            RecordModel::destroy(function($q)use($sids){
                $q->where('sid', 'in', $sids);
            });
            SectionModel::destroy($sids);
            TeamModel::destroy($tid);
            UserModel::where('tid', $tid)->update(['tid' => null]);
            UserModel::where('t2id', $tid)->update(['t2id' => null]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '删除成功']);
    }

    public function update($tid)
    {
        $team = TeamModel::find($tid);
        $data = $this->request->except(['update_time', 'create_time', 'delete_time','sur'], 'post');
        $data['count'] = isset($data['count']) ? $data['count'] : 0;
        $data['remain'] = isset($data['remain']) ? $data['remain'] : 0;
        $data['valid'] = 1;
        $data['sum'] >= $data['count'] || abort(400, '每场人数不能大于总报名人数');
        $data['sum'] > $data['remain'] || abort(400, '预留人数必须小于总报名人数');
        $numInTeam = UserModel::whereOr([
            [
                ['valid','=',1],['tid','=',$tid]
            ],
            [
                ['valid','=',1],['t2id','=',$tid]
            ]
        ])->count();
        $data['sum'] >= $numInTeam + $data['remain'] || abort(400, '总人数必须大于当前已报名人数+预留人数');
        
        $data['sur'] = $data['sum'] - $numInTeam - $data['remain'] + ($team->remained>$data['remain']?$data['remain']:$team->remained);
        
        $team->save($data);
        return json(['tid' => $tid,'msg' => '更新成功']);
    }

    public function lastdate($tid)
    {
        $section = SectionModel::where('tid', $tid)->order(['date' => 'desc'])->findOrEmpty();
        return json($section->isEmpty() ? date('Y/m/d') : $section->date);
    }

    public function changeOpen($tid)
    {
        $team = TeamModel::find($tid);
        $team->open = $team->open ? 0 : 1;
        $team->save();
        return json(['msg' => '报名'.($team->open?'开放':'关闭').'成功']);
    }

    public function openAll($open)
    {
        TeamModel::where('valid', 1)->update(['open' => (int)$open]);
        return json(['msg' => '所有班队'.($open?'开放':'关闭').'成功']);
    }

    public function users($tid, $limit, $page)
    {
        $users = UserModel::where('tid|t2id', $tid)->order('update_time','desc')->paginate([
            'list_rows'=> $limit,
            'page' => $page,
        ]);
        return json($users);
    }

    public function removeUser($tid, $uid)
    {
        Db::startTrans();
        try{
        	$team = TeamModel::find($tid);
        	
        	if($team->remain<=0 || $team->remained>$team->remain){
        		$team->sur = $team->sur + 1;
        	}
        	
        	$team->remained = $team->remained - 1;
        	$team->remained = $team->remained > 0 ? $team->remained : 0;

        	$team->save();
        	
        	
            $user = UserModel::find($uid);
            if($user->tid == $tid){
                $user->tid = null;
                $user->save();
            }else if($user->t2id == $tid){
                $user->t2id = null;
                $user->save();
            }else{
                abort(400, '用户不在此班队中');
            }
            $rids =RecordModel::hasWhere('section', function($q)use($tid){
                $q->whereRaw('unix_timestamp(concat(date," ",start)) > unix_timestamp(now()) and tid='.$tid);
            })->where('uid', $uid)->column('rid');
            // return json($rids);
            SectionModel::hasWhere('record', function($q)use($rids){
                $q->where('rid', 'in', $rids);
            })->inc('section.sur')->update();
            RecordModel::destroy(function($q)use($rids){
            	$q->where('rid', 'in', $rids);
            });
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '移除成功']);
    }
}