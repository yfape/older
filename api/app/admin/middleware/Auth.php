<?php
/*
 * @Author: your name
 * @Date: 2021-01-26 10:32:13
 * @LastEditTime: 2021-02-07 14:48:08
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\middleware\Auth.php
 */
declare (strict_types = 1);

namespace app\admin\middleware;

class Auth
{   
    private $request;
    private $token;
    private $mid;

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $url = $request->url();
        $urlarr = explode('?', $url);
        $url = $urlarr[0];
        if(in_array($url, config('middleware.except'))){
            return $next($request);
        }
        $this->request = $request;
        $this->loadToken() || abort(400);
        $this->vertifyIdentity() || abort(401);
        $request->mid = $this->mid;
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
            $this->mid = $res->mid;
        }
        return ($res && $this->mid) ? true : false;
    }
}
