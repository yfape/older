<?php
namespace app\admin\controller;

use think\Request;

class AdminCommon
{
    protected $request;
    protected $time;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->time = time();
    }
}