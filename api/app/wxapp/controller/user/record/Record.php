<?php
/*
 * @Author: your name
 * @Date: 2021-02-19 09:57:15
 * @LastEditTime: 2021-02-27 23:55:17
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\user\record\Record.php
 */
namespace app\wxapp\controller\user\record;

use think\Request;
use app\wxapp\controller\user\UserCommon;
use app\common\model\Record as RecordModel;



class Record extends UserCommon
{
    public function list($page)
    {
        $data = RecordModel::with(['section' => function($q){
            $q->with(['team'])->visible(['sid','date','start','end','addr','count','sur','tip','status','valid','statustext'
            ,'team.tid','team.name','team.logo','team.valid']);
        }])
        ->hidden(['create_time'])
        ->where([
            'uid' => $this->user->uid,
            // 'complete' => 0,
            // 'valid' => 1
        ])
        ->order(['update_time' => 'desc'])
        ->select();
        return $data;
    }

    /**
     * @获取未开始的场次: 
     * @param {*}
     * @return {*}
     */
    public function uncomplete()
    {

    }
}