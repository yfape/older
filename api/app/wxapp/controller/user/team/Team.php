<?php
/*
 * @Author: your name
 * @Date: 2021-02-03 22:44:29
 * @LastEditTime: 2021-02-04 11:20:59
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\Team.php
 */
namespace app\wxapp\controller\user\team;

use think\Request;
use app\wxapp\controller\user\UserCommon;
use app\common\model\Team as TeamModel;
use think\facade\Db;

class Team extends UserCommon{

    protected function get($tid){
        return TeamModel::find($tid);
    }

    public function list(){
        $teams = TeamModel::where(true)->hidden(['remain','create_time','update_time'])->select();
        return $teams;
    }

}