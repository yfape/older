<?php
/*
 * @Author: your name
 * @Date: 2021-02-18 13:07:29
 * @LastEditTime: 2021-03-08 00:43:15
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\section\Section.php
 */
namespace app\admin\controller;

use think\Request;
use think\facade\Db;

use app\common\model\Section as SectionModel;
use app\common\model\Record as RecordModel;

class Section
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function listByDate($tid, $date)
    {
        if(!$date){
            $date = date('Ymd');
        }
        $sections = SectionModel::where([
            'tid' => $tid,
            'date' => $date
        ])->append(['status', 'statustext'])->order('sid', 'asc')->select();
        return json($sections);
    }

    public function changeValid($sid)
    {
        $section = SectionModel::where(true)->append(['status', 'statustext'])->find($sid);
        $section->valid = $section->valid ? 0 : 1;
        $section->tip = $this->request->post('tip');
        $section->save();
        return json(['msg' => '场次启用成功']);
    }

    public function updateCountAndTip($sid)
    {
        $section = SectionModel::where('sid', $sid)->append(['status'])->with('team')->find();
        $data = $this->request->only(['count', 'tip']);
        $data['count'] <= $section->team->sum || abort(400, '单场人数不能大于班队总报名人数');
        $section->status != 9 || abort(400, '本场活动已完结无法修改');
        $numInSection = RecordModel::where([
            'sid' => $sid,
            'valid' => 1
        ])->count();
        $section->sur = $data['count'] - $numInSection;
        $section->sur || abort(400, '活动人数必须大于已预约人数');
        $section->save($data);
        return json(['msg' => '更新成功']);
    }
    public function delete($sid)
    {
        Db::startTrans();
        try{
            SectionModel::destroy($sid);
            RecordModel::destroy(function($q)use($sid){
                $q->where('sid', $sid);
            });
            Db::commit();
        } catch(\Exception $e){
            Db::rollback();
            abort(540, $e->getMessage());
        }
        return json(['msg' => '删除成功']);
    }
    
    
}