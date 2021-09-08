<?php
/*
 * @Author: your name
 * @Date: 2021-02-06 23:57:05
 * @LastEditTime: 2021-02-19 00:36:07
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\admin\controller\team\Team.php
 */
namespace app\admin\controller\team;

use think\Request;
use think\facade\Db;

use app\common\model\Team as TeamModel;
use app\common\model\Record as RecordModel;
use app\common\model\Section as SectionModel;
use app\common\model\User as UserModel;

class Team
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @获取单个信息: 
     * @param {*} $tid
     * @return {*}
     */
    public function get($tid)
    {
        $team = TeamModel::find($tid);
        return json($team);
    }

    /**
     * @获取班队列表: 
     * @param {*}
     * @return {*}
     */
    public function list()
    {
        $limit = $this->request->param('limit');
        $page = $this->request->param('page');
        $data = TeamModel::where(true)->hidden(['delete_time', 'update_time', 'create_time'])->paginate([
            'list_rows'=> $limit,
            'page' => $page,
        ]);
        return json($data);
    }

    /**
     * @校正各班人数: 
     * @param {*}
     * @return {*}
     */
    public function sync()
    {
        try{
            $teams = TeamModel::where(true)->select();
            foreach($teams as $key => $item){
                $num = Db::table('user')->where('tid', $item->tid)->count();
                $item->sur = $item->sum - $item->remain - $num;
                $item->save();
            }
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json(['msg' => '数据校准成功']);
    }

    /**
     * @修改班队部分信息: 
     * @param {*}
     * @return {*}
     */
    public function put()
    {
        $field = $this->request->param('field');
        $tid = $this->request->param('tid');
        $value = $this->request->param('value');

        $team = TeamModel::find($tid);
        if($field == 'open'){
            $team->open = $team->open ? 0 : 1;
        }else if($field == 'valid'){
            $team->valid = $team->valid ? 0 : 1;
            if(!$team->valid){
                $team->open = 0;

                //取消本班队所有未完成场次
                $sections = SectionModel::where([
                    'status' => 0,
                    'tid' => $tid
                ])->update(['valid' => 0, 'update_time' => time()]);

            }
        }else{
            $team[$field] = $value;
        }
        $team->save();
        return json(['msg' => '更新成功']);
    }

    /**
     * @新增班队: 
     * @param {*}
     * @return {*}
     */
    public function insert()
    {
        try{
            $team = new TeamModel;
            $team->save($this->request->except(['update_time', 'create_time', 'delete_time'], 'post'));
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json(['tid' => $team->tid,'msg' => '新增成功']);
    }

    /**
     * @删除team: 
     * @param {*} $tid
     * @return {*}
     */
    public function delete($tid)
    {
        Db::startTrans();
        try{
            RecordModel::destroy(function($q) use($tid){
                $q->hasWhere('section', ['tid' => $tid]);
            });
            SectionModel::destroy(function($q) use($tid){
                $q->where('tid', $tid);
            });
            UserModel::where('tid', $tid)->update(['tid' => null]);
            TeamModel::destroy($tid);
            Db::commit();
        } catch (\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '删除成功']);
    }

    /**
     * @修改班队数据: 
     * @param {*} $tid
     * @return {*}
     */
    public function update($tid)
    {
        try{
            TeamModel::update($this->request->except(['update_time', 'create_time', 'delete_time', 'valid'], 'post'), ['tid' => $tid]);
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json(['tid' => $tid,'msg' => '更新成功']);
    }

    /**
     * @获取上线最后一天: 
     * @param {*} $sid
     * @return {*}
     */
    public function lastdate($tid)
    {
        $section = SectionModel::where('tid', $tid)->order(['date' => 'desc'])->findOrEmpty();
        return json($section->isEmpty() ? date('Y/m/d') : $section->date);
    }
}