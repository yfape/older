<?php
/*
 * @Author: your name
 * @Date: 2021-02-08 15:02:32
 * @LastEditTime: 2021-03-08 22:49:35
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\user\User.php
 */
namespace app\admin\controller;

use app\common\model\LogTeam;
use think\Request;
use think\facade\Db;
use app\common\model\User as UserModel;
use app\common\model\Team as TeamModel;
use app\common\model\Section as SectionModel;
use app\common\model\Record as RecordModel;

class User
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get($uid)
    {
        try{
            $user = UserModel::where('uid', $uid)->with(['team', 'team2', 'single'])->find();
            return json($user);
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        
    }

    /**
     * @获取用户列表: 
     * @param {*} $field
     * @param {*} $value
     * @param {*} $limit
     * @param {*} $page
     * @return {*}
     */
    public function list($limit, $page)
    {
        $valid = $this->request->param('valid/d');
        $validsen = $valid ? 'valid='.$valid : true;
        $search = $this->request->param('search/s');
        $search = strlen($search) > 0 ? [['incode|name|phone', 'like', '%'.$search.'%']] : true;
        $users = UserModel::where($validsen)->where($search)->with(['team', 'team2', 'single'])->order('create_time','desc')->paginate([
            'list_rows'=> $limit,
            'page' => $page,
        ]);
        return json($users);
    }

    /**
     * @单项更新: 
     * @param {*}
     * @return {*}
     */
    public function put($uid, $field, $value=null)
    {
        try{
            $user = UserModel::find($uid);
            $user[$field] = $value;
            $user->save();
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json(['msg' => '更新成功']);
    }

    public function search($search)
    {
        $users = UserModel::where([
            ['incode|name|phone', 'like', "%$search%"],
            ['valid', '=', 1]
        ])->with(['team', 'team2', 'single'])->select();
        return json($users);
    }

    public function changeTeam($uid, $tid)
    {
        Db::startTrans();
        try{
            $team = TeamModel::find($tid);
            
            if($team->remain<=0 || $team->remained >= $team->remain){
            	$team->sur > 0 || abort(400, '班队已满');
            	$team->sur -= 1;
            }
            $team->remained += 1;
            $team->save();

            $user = UserModel::find($uid);
            if($user->single && $user->single != $team->tid){
                abort(400, '单科生无法报名其他班队');
            }
            if($team->category == 1){
                $user->tid && abort(400, '此用户已报日常班，请先将其移出');
                $user->tid = $tid;
                $user->save();
            }else if($team->category == 2){
                $user->t2id && abort(400, '此用户已报教学班，请先将其移出');
                $user->t2id = $tid;
                $user->save();
            }else{
                abort(400, '班队信息异常');
            }
            
            LogTeam::create([
                'uid' => $uid,
                'tid' => $tid
            ]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '加入班队成功']);
    }

    public function insert()
    {
        $data = $this->request->only(['incode', 'name', 'phone', 'single']);
        $data['valid'] = 1;
        try{
            UserModel::create($data);
        }catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json(['msg' => '新增成功']);
    }

    public function delete($uid)
    {
        $user = UserModel::find($uid);
        Db::startTrans();
        try{
            TeamModel::where('tid', $user->tid)->whereOr('tid', $user->t2id)->inc('sur')->update(); 
            $rids = RecordModel::where('uid', $uid)->column('rid');
            SectionModel::hasWhere('record', function($q)use($rids){
                $q->where('rid', 'in', $rids);
            })->inc('section.sur')->update();
            RecordModel::destroy(function($q)use($rids){
            	$q->where('rid','in',$rids);
            });
            \app\common\model\LogTeam::destroy(function($q)use($user){
                $q->where('uid', $user->uid);
            });
            \app\common\model\LogSign::destroy(function($q)use($user){
                $q->where('uid', $user->uid);
            });
            \app\common\model\LogEpi::destroy(function($q)use($user){
                $q->where('uid', $user->uid);
            });
            $user->force()->delete();
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '删除成功']);
    }
    
    public function update($uid)
    {
    	$user = UserModel::find($uid);
    	$data = $this->request->only(['name', 'phone', 'single']);
    	$data['single'] = $data['single'] ? $data['single'] : null;
    	$user->save($data);
    	return json(['msg' => '保存成功']);
    }
}