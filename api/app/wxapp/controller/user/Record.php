<?php
/*
 * @Author: your name
 * @Date: 2021-01-31 10:25:16
 * @LastEditTime: 2021-03-03 17:25:29
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\PersonInfo.php
 */
namespace app\wxapp\controller\user;

use app\wxapp\controller\user\UserCommon;

use app\wxapp\model\User;
use app\wxapp\model\Record as RecordModel;
use app\wxapp\model\LogSign;

class Record extends UserCommon
{

    /**
     * @获取未完成的record: 
     * @param {*}
     * @return {*}
     */
    public function uncompletes()
    {
        $records = RecordModel::with(['section' => function($q){
            $q->append(['status', 'statustext'])->with('team');  
        }])
        ->hasWhere('section', function($q){
          $q->whereRaw('unix_timestamp(concat(date," ",end)) > unix_timestamp(now())');
        })
        ->where([
            'uid' => $this->user->uid,
            'record.valid' => 1
        ])
        ->order([
            'section.date' => 'asc',
            'section.sid' => 'asc'
        ])
        ->select();
        return json($records);
    }

    /**
     * @取消record: 
     * @param {*} $rid
     * @return {*}
     */
    public function cancelRecord($rid)
    {
        $rd = new \app\wxapp\controller\user\record\CancelRecord($this->user, $rid);
        $res = $rd->index();
        return json(['msg' => $res]);
    }

    /**
     * @获取历史纪录: 
     * @param {*} $page
     * @return {*}
     */
    public function history($page)
    {
        $records = RecordModel::with(['section' => function($q){
            $q->append(['status', 'statustext'])->with('team');  
        }])
        ->where([
            'uid' => $this->user->uid
        ])
        ->order('update_time', 'desc')
        ->paginate([
            'list_rows' => 10,
            'page' => $page
        ]);
        return json($records->all());
    }

}