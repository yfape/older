<?php
/*
 * @Author: your name
 * @Date: 2021-02-06 22:55:22
 * @LastEditTime: 2021-02-06 23:02:13
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\admin\controller\services\Page.php
 */
namespace app\admin\controller\services;

use think\Request;

class Page
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function home()
    {
        return json($this->request->mid);
    }
}