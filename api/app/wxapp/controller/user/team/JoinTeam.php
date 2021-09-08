<?php
/*
 * @Author: yfso
 * @Date: 2021-02-04 11:11:31
 * @LastEditTime: 2021-02-25 09:55:24
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\team\JoinTeam.php
 */
namespace app\wxapp\controller\user\team;

use think\Request;
use think\facade\Db;

use app\wxapp\controller\user\team\Team;
use app\common\model\Team as TeamModel;
use app\common\model\User as UserModel;

class JoinTeam extends Team{

    private $tid;
    private $team;

    public function index(){
        $this->loadTid() || abort(400);
        $this->userIsNoTeam() || abort(221, '您已报名，无法重复报名');
        $this->compareSingleWithTid() || abort(222, '抱歉，您只能选报'.$this->user->singleteam->name);
        $this->loadTeamModel() || abort(400);
        $this->teamIsOpen() || abort(223, "抱歉，{$this->team->name}报名已关闭");
        $this->teamhasPlaces() || abort(224, "抱歉，{$this->team->name}已报满");
        $this->joinTheTeam() || abort(540);
        return json(['msg' => '报名成功']);
    }

    /**
     * @加载tid: 
     * @param {*}
     * @return {*}
     */
    protected function loadTid(){
        $this->tid = $this->request->param('tid/d');
        return $this->tid ? true : false;
    }
    /**
     * @判断用户是否已报名: 
     * @param {*}
     * @return {*}
     */
    protected function userIsNoTeam(){
        return !$this->user->tid ? true : false;
    }

    /**
     * @比对单科生报名是否合法: 
     * @param {*}
     * @return {*}
     */
    protected function compareSingleWithTid(){
        return ($this->user->single && ($this->user->single != $this->tid)) ? false : true;
    }
                
    /**
     * @获取目标班队对象: 
     * @param {*}
     * @return {*}
     */
    protected function loadTeamModel(){
        $this->team = $this->get($this->tid);
        return $this->team ? true : false;
    }

    /**
     * @班队是否开放: 
     * @param {*}
     * @return {*}
     */
    protected function teamIsOpen(){
        return $this->team->open ? true : false;
    }

    /**
     * @班队有名额: 
     * @param {*}
     * @return {*}
     */
    protected function teamhasPlaces(){
        return $this->team->sur > 0;
    }

    /**
     * @加入班队: 
     * @param {*}
     * @return {*}
     */
    protected function joinTheTeam(){
        $this->user->save(['tid' => $this->tid]);
        return json(['msg' => '报名成功']);
    }
}