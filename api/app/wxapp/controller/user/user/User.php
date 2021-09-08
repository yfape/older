<?php
/*
 * @Author: your name
 * @Date: 2021-02-22 10:22:39
 * @LastEditTime: 2021-02-22 10:27:47
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\user\user\User.php
 */
namespace app\wxapp\controller\user;

use app\wxapp\controller\user\UserCommon;
use app\common\model\User as UserModel;

class User extends UserCommon
{   
    /**
     * @获取用户信息: 
     * @param {*}
     * @return {*}
     */
    public function get(){
        return json($this->user);
    }
}