<?php
/*
 * @Author: your name
 * @Date: 2021-01-22 22:25:21
 * @LastEditTime: 2021-01-26 00:53:37
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\Request.php
 */
namespace app;

// 应用请求对象类
class Request extends \think\Request
{
    protected $filter = ['htmlspecialchars'];
}
