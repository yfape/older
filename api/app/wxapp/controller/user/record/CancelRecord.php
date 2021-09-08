<?php
/*
 * @Author: your name
 * @Date: 2021-02-04 14:53:19
 * @LastEditTime: 2021-03-03 17:09:03
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\section\section.php
 */
namespace app\wxapp\controller\user\record;

use think\facade\Db;
use app\wxapp\model\Record as RecordModel;
use app\wxapp\model\Section as SectionModel;
use app\wxapp\model\User as UserModel;

class CancelRecord{

    private $section;
    private $record;
    protected $user;
    private $time;
    protected $surnum;

    public function __construct(UserModel $user, $rid)
    {
        $this->user = $user;
        $this->record = RecordModel::findOrEmpty($rid);
        $this->record->isEmpty() && abort(400, '不存在预约');
        $this->section = SectionModel::findOrEmpty($this->record->sid);
        $this->section->isEmpty() && abort(400, '不存在场次');
        $this->time = time();
    }

    public function index(){
        $this->sectionNotStart() || abort(233, '本场活动已开始');
        $this->sectionValid() || abort(234, '本场活动已取消');
        $this->userIsSubScribe() || abort(400);
        $this->sectionIsNotToday() || abort(240, '当日活动不能取消');
        $this->cancelTheSubscribe() || abort(540);
        $this->incCancelNum();
        $this->setForbid();
        if($this->surnum > 0){
            return "取消成功，今日您还能取消{$this->surnum}次";
        }else{
            return "取消成功，今日您已无法预约";
        }
        
    }

    /**
     * @活动未开始: 
     * @param {*}
     * @return {*}
     */
    protected function sectionNotStart(){
        return strtotime($this->section->date.' '.$this->section->start) > $this->time;
    }

    /**
     * @本场活动已取消: 
     * @param {*}
     * @return {*}
     */
    protected function sectionValid(){
        return $this->section->valid;
    }

    /**
     * @用户是否预约本场: 
     * @param {*}
     * @return {*}
     */
    protected function userIsSubScribe(){
        return $this->record->uid == $this->user->uid;
    }

    /**
     * @活动不在当日: 
     * @param {*}
     * @return {*}
     */
    protected function sectionIsNotToday(){
        return strtotime($this->section->date) > strtotime(date('Ymd'));
    }

    /**
     * @取消预约:
     * @param {*}
     * @return {*}
     */
    protected function cancelTheSubscribe(){
        Db::startTrans();
        try{
            $this->record->valid = 0;
            $this->record->save();
            $this->section->sur = $this->section->sur + 1;
            $this->section->save();
            Db::commit();
        } catch (\Exception $e){
            Db::rollback();
            return false;
        }
        return true;
    }

    /**
     * @增加取消次数: 
     * @param {*}
     * @return {*}
     */
    protected function incCancelNum(){
        $num = Db::table('record')->where([
            'uid' => $this->record->uid,
            'valid' => 0            
        ])->where('to_days(FROM_UNIXTIME(update_time)) = to_days(now())')->count();
        $sub = config('app.tactic.cancelnum') - $num;
        $this->surnum = $sub;
    }

    /**
     * @设置用户封禁: 
     * @param {*}
     * @return {*}
     */
    protected function setForbid(){
        if($this->surnum > 0){
            return true;
        }
        if(($this->user->forbid) && ($this->user->forbid - $this->time >= config('app.tactic.cancelforbidtime'))){
            return true;
        }
        $this->user->forbid = $this->time + config('app.tactic.cancelforbidtime');
        $this->user->save();
        return true;
    }
}