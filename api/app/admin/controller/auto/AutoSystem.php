<?php
/*
 * @Author: your name
 * @Date: 2021-03-01 00:11:24
 * @LastEditTime: 2021-03-03 10:42:59
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\auto\AutoSystem.php
 */
namespace app\admin\controller\auto;

use think\Request;

use app\common\model\Section;

class AutoSystem
{
    protected $request;
    protected $time;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->time = time();
    }

    public function autoRun()
    {
        $user = $this->request->param('user');
        $pass = $this->request->param('pass');
        if($user != 'liuyaofu7456' || $pass != 'liuliu7456'){
            abort(404);
        }
        $this->review();
        echo 'yes';
    }

    protected function review()
    {
        $sections = Section::with(['record' => function($q){
            $q->with('user')->where('valid', 1);
        }])
        ->where("UNIX_TIMESTAMP(concat(date,' ',start)) <= ".$this->time)
        ->where('section.valid',1)
        ->select();
        
        foreach($sections as $key => $item){
            $end = strtotime($item->date.' '.$item->end);
            if($end > $this->time){
                $item->status = 1;
                $item->save();
            }else{
                $item->status = 9;
                $item->save();
            }
        }

    }

}