<?php
/*
 * @Author: yfso
 * @Date: 2021-02-04 15:24:17
 * @LastEditTime: 2021-03-03 22:21:48
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\user\section\JoinSection.php
 */
namespace app\wxapp\controller\user\section;

use think\facade\Db;
use app\wxapp\model\Section as SectionModel;
use app\wxapp\model\Record as RecordModel;
use app\wxapp\model\User as UserModel;

class JoinSection {

    protected $user;
    protected $section;
    protected $time;
    private $forbidtext;

    public function __construct(UserModel $user, $sid)
    {
        $this->user = $user;
        $this->section = SectionModel::findOrEmpty($sid);
        $this->section->isEmpty() && abort(400, '不存在班队'); 
        $this->time = time();
    }

    public function index()
    {
        $this->userIsNoForbid() || abort(230, '抱歉,'. $this->forbidtext .'内您无法进行预约');
        $this->sectionHasSur() || abort(232, '本场活动人数已满');
        $this->sectionNotStart() || abort(233, '本场活动已开始');
        $this->sectionValid() || abort(234, '本场活动已取消');
        $this->compareSectionWithTid() || abort(400);
        $this->userNoSectionToday() || abort(231, '您当日已有预约');
        $this->userIsNoForbidByArrive() || abort(230, '抱歉，您30天已内缺席超过3次，无法预约活动');
        $this->joinTheSection() || abort(540);
        return true;
    }

    /**
     * @用户是否因取消订单或缺席被封禁: 
     * @param {*}
     * @return {*}
     */
    protected function userIsNoForbid()
    {
        $this->time = time();
        $text = '';
        if(!$this->user->forbid || ($this->time >= (int)$this->user->forbid)){
            return true;
        }
        $sub = $this->user->forbid - $this->time;
        if($sub < 60){
            $text = $sub . "秒";
        }else if($sub < 3600){
            $text = ceil($sub / 60) . "分";
        }else if($sub < 86400){
            $text = ceil($sub / 3600) . "小时";
        }else{
            $text = ceil($sub / 86400) . "日";
        }
        $this->forbidtext = $text; 
        return false;
    }

    /**
     * @section是否有余位: 
     * @param {*}
     * @return {*}
     */
    protected function sectionHasSur()
    {
        return $this->section->sur > 0;
    }

    /**
     * @活动未开始: 
     * @param {*}
     * @return {*}
     */
    protected function sectionNotStart()
    {
        return strtotime($this->section->date.' '.$this->section->start) > $this->time;
    }

    /**
     * @本场活动已取消: 
     * @param {*}
     * @return {*}
     */
    protected function sectionValid()
    {
        return $this->section->valid;
    }

    /**
     * @比对section是否属于用户班队: 
     * @param {*}
     * @return {*}
     */
    protected function compareSectionWithTid()
    {
        return ($this->section->tid == $this->user->tid) || ($this->section->tid == $this->user->t2id);
    }

    /**
     * @用户当日是否存在预约: 
     * @param {*}
     * @return {*}
     */
    protected function userNoSectionToday()
    {
        $record = RecordModel::hasWhere('section', [
            'date' => $this->section->date
        ])
        ->where([
            'record.valid' => 1,
            'uid' => $this->user->uid
        ])->findOrEmpty();
        return $record->isEmpty() ? true : false;
    }

    /**
     * @执行预约操作: 
     * @param {*}
     * @return {*}
     */
    protected function joinTheSection()
    {
        Db::startTrans();
        try{
            RecordModel::create([
                'uid' => $this->user->uid,
                'sid' => $this->section->sid,
                'arrive' => 0,
                'valid' => 1
            ]);
            $this->section->sur = $this->section->sur - 1;
            $this->section->save();
            Db::commit();
        } catch (\Exception $e){
            Db::rollback();
            return false;
        }
        return true;
    }

    /**
     * @判断是否缺席超过三次: 
     * @param {*}
     * @return {*}
     */
    protected function userIsNoForbidByArrive()
    {
        $noArriveSections = SectionModel::hasWhere('record', ['uid' => $this->user->uid,'valid'=>1,'arrive'=>0])
        ->whereRaw('TO_DAYS(date) >= TO_DAYS(date_sub(NOW(), interval 1 MONTH)) and UNIX_TIMESTAMP(concat(date," ",end)) < UNIX_TIMESTAMP(now())')
        ->order('date', 'desc')
        ->select();
        if(count($noArriveSections) >= 3){
            $lastSection = $noArriveSections[0];
            $this->user->forbid = strtotime($lastSection->date.' '.$lastSection->end) + 86400*14;
            $this->user->save();
            return false;
        }else{
            return true;
        }
    }
}