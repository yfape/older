<?php
/*
 * @Author: your name
 * @Date: 2021-02-23 11:18:05
 * @LastEditTime: 2021-02-23 13:52:44
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\user\Log.php
 */
namespace app\wxapp\controller\user;

use app\wxapp\controller\user\UserCommon;
use app\common\model\LogTeam as LogTeamModel;

class Log extends UserCommon
{
    /**
     * @获取最后一次报名信息: 
     * @param {*}
     * @return {*}
     */
    public function teamlast(){
        $log = LogTeamModel::with('team')->where(['uid' => $this->user->uid])->order('create_time', 'desc')->find();
        return json($log);
    }
}