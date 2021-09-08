<?php
/*
 * @Author: your name
 * @Date: 2021-02-28 03:09:47
 * @LastEditTime: 2021-03-04 11:03:08
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\manager\Info.php
 */
namespace app\wxapp\controller\manager;

use think\Request;
use app\wxapp\model\Team as TeamModel;
use app\wxapp\model\Section as SectionModel;
use app\wxapp\model\Record as RecordModel;
use app\wxapp\model\User as UserModel;

class Info
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;    
    }

    /**
     * @获取正常班队: 
     * @param {*}
     * @return {*}
     */
    public function getTeams($date)
    {
        $teams = TeamModel::hasWhere('section', ['date' => $date, 'valid' => 1])
        ->with(['section' => function($q)use($date){
            $q->where([
                'date' => $date,
                'valid' => 1
            ]);
        }])
        ->where('team.valid', 1)
        ->select();
        return json($teams);
    }

    /**
     * @获取section: 
     * @param {*} $sid
     * @return {*}
     */
    public function getSection($sid)
    {
        $section = SectionModel::with(['team', 'record' => function($q)use($sid){
            $q->with('user')->where(['sid' => $sid, 'valid' => 1]);
        }])->where('section.sid', $sid)->append(['status'])->findOrEmpty();
        return json($section);
    }

    /**
     * @record单项修改: 
     * @param {*} $rid
     * @param {*} $field
     * @param {*} $value
     * @return {*}
     */
    public function setRecord($rid, $field, $value)
    {
        $record = RecordModel::with('section')->find($rid);
        if($field == 'arrive'){
            $record->section->status == 9 && abort(431, '本场此已完结，无法签到');
        }

        $record[$field] = $value;
        $record->save();
        return json(['msg' => '更新成功']);
    }

    /**
     * @获取record: 
     * @param {*} $uid
     * @param {*} $rid
     * @return {*}
     */
    public function getSectionUserRecord($sid, $uid, $rid)
    {
        $record = RecordModel::where([
            'sid' => $sid,
            'uid' => $uid,
            'rid' => $rid,
            'valid' => 1
        ])
        ->with('user')->findOrEmpty();
        return json($record);
    }

    public function quickarrive($sid, $uid, $rid)
    {
        $record = RecordModel::with('section')->where([
            'sid' => $sid,
            'uid' => $uid,
            'rid' => $rid,
            'valid' => 1
        ])->findOrEmpty();
        if($record->isEmpty()){
            abort(231, '此用户未预约该场活动');
        }
        if($record->arrive){
            abort(232, '用户已签到');
        }
        if($record->section->status == 9){
            abort(431, '本场此已完结，无法签到');
        }
        $record->arrive = 1;
        $record->save();
        return json(['msg' => '签到成功']);
    }

    /**
     * @获取用户: 
     * @param {*} $uid
     * @return {*}
     */
    public function getUser($uid)
    {
        $user = UserModel::with(['team','team2'])->where('uid',$uid)->findOrEmpty();
        return json($user);
    }
}