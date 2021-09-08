<?php
/*
 * @Author: your name
 * @Date: 2021-03-03 14:34:02
 * @LastEditTime: 2021-03-03 17:13:27
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\user\Section.php
 */
namespace app\wxapp\controller\user;

use app\wxapp\controller\user\UserCommon;
use app\wxapp\model\Section as SectionModel;

class Section extends UserCommon
{
    public function listWithDate($date)
    {
        $sections = SectionModel::with(['team','record'=>function($q){
            $q->where(['uid' => $this->user->uid, 'valid' => 1]);
        }])
        ->whereOr([
            [
                ['date','=',$date],['tid','=',$this->user->tid]
            ],
            [
                ['date','=',$date],['tid','=',$this->user->t2id]
            ]
        ])
        ->append(['status', 'statustext'])
        ->order('sid','asc')
        ->select();
        return json($sections);
    }

    /**
     * @预约: 
     * @param {*}
     * @return {*}
     */
    public function joinSection($sid)
    {
        $js = new \app\wxapp\controller\user\section\JoinSection($this->user, $sid);
        $res = $js->index();
        return $res ? json(['msg' => '预约成功']) : json(['msg' => '预约失败']);
    }
}