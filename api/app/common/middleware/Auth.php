<?php
/*
 * @Author: your name
 * @Date: 2021-01-26 10:32:13
 * @LastEditTime: 2021-01-26 15:50:37
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\middleware\Auth.php
 */
declare (strict_types = 1);

namespace app\common\middleware;

class Auth
{   
    private $request;
    private $token;
    private $openid;
    private $sessionkey;

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {   
        if(in_array($request->url(), config('middleware.except'))){
            return $next($request);
        }

        $this->request = $request;
        $this->loadToken() || abort(400);
        $this->vertifyIdentity() || abort(401);
        $request->openid = $this->openid;
        $request->sessionkey = $this->sessionkey;
        return $next($request);
    }

    /**
     * @获取token: 
     * @param {*}
     * @return {*}
     */
    protected function loadToken(){
        $this->token = $this->request->header('Authorization');
        return $this->token ? true : false;
    }

    /**
     * @验证token并解析openid和sessionkey: 
     * @param {*}
     * @return {*}
     */
    protected function vertifyIdentity(){
        $res = app('jwt')->verToken($this->token, config('app.jwt.salt'), config('app.jwt.jsclass'));
        if($res){
            $this->openid = $res->openid;
            $this->sessionkey = $res->sessionkey;
        }
        return ($res && $this->openid && $this->sessionkey) ? true : false;
    }
}
