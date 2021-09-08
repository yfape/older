<?php
/*
 * @Author: your name
 * @Date: 2021-01-31 10:30:06
 * @LastEditTime: 2021-03-03 11:17:32
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\common.php
 */

namespace app\wxapp\controller\user;

use think\Request;
use app\wxapp\model\User;

class UserCommon
{
    protected $request;
    protected $user;
    protected $time;

    public function __construct(Request $request)
    {
        $this->time = time();
        $this->request = $request;
        $user = User::with(['team','singleteam'])->where('openid', $this->request->openid)->findOrEmpty(); 
        $user->isEmpty() && abort(400);
        $user->valid || abort(550);
        $this->user = $user;
    }


    protected function checkTeam(){
        $this->user->tid || abort(220, '用户未报名');
    }
}