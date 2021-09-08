<?php
/*
 * @Author: your name
 * @Date: 2021-03-03 10:19:33
 * @LastEditTime: 2021-03-03 11:29:29
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\user\Team.php
 */
namespace app\wxapp\controller\user;

use think\facade\Db;
use app\wxapp\controller\user\UserCommon;
use app\wxapp\model\Team as TeamModel;
use app\wxapp\model\User as UserModel;
use app\wxapp\model\LogTeam;

class Team extends UserCommon
{
    /**
     * @班队列表: 
     * @param {*} $category
     * @return {*}
     */
    public function listWithCategory($category)
    {
        $teams = TeamModel::where('category', $category)->select();
        return json($teams);
    }

    /**
     * @加入班队: 
     * @param {*} $tid
     * @return {*}
     */
    public function joinTeam($tid)
    {
        $team = TeamModel::findOrEmpty($tid);
        $team->isEmpty() && abort(400, '不存在此班队');
        $team->category == 1 && $this->user->tid && abort(20003, '只能报一个日常班');
        $team->category == 2 && $this->user->t2id && abort(20003, '只能报一个教学班');
        $this->user->single && $this->user->single != $team->tid && abort(20004, '单科生只能选报指定班');
        $team->open || abort(20005, '此班队报名已关闭');
        $team->sur > 0 || abort(20006, '班队已报满');

        Db::startTrans();
        try{
            //用户修改tid或t2id
            if($team->category == 1){
                $this->user->tid = $team->tid;
            }else{
                $this->user->t2id = $team->tid; 
            }
            $this->user->save();
            //班队修改sur
            //班队保留名额
            $team->sur = $team->sur - 1;
            if($team->remain > 0 && $team->sur <= $team->remain - $team->remained){
            	$team->sur = 0;
            }
            $team->save();
            
            //添加记录
            LogTeam::create(['uid' => $this->user->uid, 'tid' => $team->tid]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '报名成功']);
    }

    /**
     * @获取用户班队: 
     * @param {*}
     * @return {*}
     */
    public function getUserTeams()
    {
        $team = TeamModel::find($this->user->tid);
        $team2 = TeamModel::find($this->user->t2id);
        return json([
            'team' => $team,
            'team2' => $team2
        ]);
    }
}