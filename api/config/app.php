<?php
/*
 * @Author: your name
 * @Date: 2021-01-22 22:25:21
 * @LastEditTime: 2021-03-09 01:07:19
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\config\app.php
 */
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用地址
    'app_host'         => env('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'wxapp',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => false,

    //应用映射
    'app_map' => [
        'admin' => 'admin',
        'wxapp' => 'wxapp'
    ],
    //域名绑定
    'domain_bind' => [
        'admin.***.com' => 'admin',
        '10.100.80.204' => 'wxapp',
        '192.168.2.3' => 'wxapp',
        
        'oer.***.com' => 'wxapp',
        'oeradmin.***.com' => 'admin',
    ],
    'login' => [
        'salt' => 'sdfsdfds34234@##1223sdf'
    ],
    //微信号APPID、APPSECRET
    'wx' => [
        'AppID' => '************************',
        'AppSecret' => '*******************************',
        'code2Session' => 'https://api.weixin.qq.com/sns/jscode2session'
    ],
    //密钥信息
    'jwt' => [
        'expire' => 36000,
        'salt' => 'sdfsdfds34234@##1223sdf',
        'jsclass' => ['HS256'],
        'aud' => 'oer.***.com',
        'iss' => 'oer.***.com',
    ],
    //系统策略
    'tactic' => [
        'identity' => 3,
        'cancelnum' => 3,
        'cancelforbidtime' => 86400
    ],
    'webdomain' => 'http://'.$_ENV['LOCALHOST']
];
