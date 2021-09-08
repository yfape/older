<?php
/*
 * @Author: your name
 * @Date: 2021-01-26 10:32:13
 * @LastEditTime: 2021-02-07 14:52:47
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\middleware\Auth.php
 */
declare (strict_types = 1);

namespace app\common\middleware;

class HttpOptionsDeal
{   
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {   
        if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){ 
            abort(200);
        }
        return $next($request);
    }

}
