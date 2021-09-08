<?php
/*
 * @Author: your name
 * @Date: 2021-02-04 14:53:19
 * @LastEditTime: 2021-03-03 10:49:36
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\section\section.php
 */
namespace app\wxapp\controller\user\section;

use app\wxapp\controller\user\UserCommon;
use app\common\model\Section as SectionModel;

class Section extends UserCommon{

    public function list(){
        $date = $this->request->param('date');
        $tid = $this->user->tid;
        $sections = SectionModel::with(['team','record' => function($q){
            $q->where(['uid' => $this->user->uid, 'valid' => 1]);
        }])
        ->where([
            'tid' => $tid,
            'date' => $date
        ])
        ->hidden(['create_time','update_time'])
        ->order('sid','asc')->select();
        return json($sections);
    }

    public function uncomplete()
    {
        $sections = SectionModel::with(['team','record' => function($q){
        	$q->where(['uid' => $this->user->uid, 
            'valid' => 1]);
        }])
        ->hasWhere('record',[
            'uid' => $this->user->uid, 
            'valid' => 1
        ])
        ->where('status','<',9)       
        ->order(['date'=>'asc','sid'=>'asc'])
        ->select();
        return json($sections);
    }

    protected function get(){
        $sid = $this->request->param('sid/d');
        $section = SectionModel::where('sid', $sid)->hidden(['create_time', 'update_time'])->find();
        return $section;
    }

    public function history($page)
    {
        $sections = SectionModel::hasWhere('record',[
            'uid' => $this->user->uid
        ])
        ->with(['team','record' => function($q){
        	$q->where(['uid' => $this->user->uid]);
    	}])
        ->order(['date'=>'desc','sid'=>'desc'])
        ->paginate([
            'list_rows' => 10,
            'page' => $page
        ]);
        return json($sections->all());
    }

}