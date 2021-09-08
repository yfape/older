<?php
/*
 * @Author: your name
 * @Date: 2021-02-18 23:32:53
 * @LastEditTime: 2021-03-04 11:36:19
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\section\InsertSections.php
 */
namespace app\admin\controller\section;

use think\Request;
use think\facade\Db;
use app\common\model\Team as TeamModel;
use app\common\model\Section as SectionModel;

class insertSections
{
    protected $request;
    protected $team;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index($tid)
    {
        $dates = $this->request->post('date');
        $dates = $this->getDates($dates['from'], $dates['to']);
        $this->getTeam($tid);
        foreach($dates as $key => $value){
            $this->inserts($value);
        }
        return json(['msg' => '上线场次成功']);
    }

    protected function getDates($start, $end)
    {
        $arr = [];
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        while ($dt_start<=$dt_end){
            $arr[] = date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $arr;
    }

    protected function getTeam($tid)
    {
        $this->team = TeamModel::find($tid);
    }

    protected function inserts($date)
    {
        $sections = SectionModel::where(['tid' => $this->team->tid, 'date' => $date])->select();
        count($sections) > 0 && abort(540, '当天已存在场次');
        $week = date("w", strtotime($date));
        $temps = $this->team->content[$week];

        if($temps == [] || !$temps){
            return;
        }

        foreach($temps as $k => $v){
            SectionModel::create([
                'tid' => $this->team->tid,
                'date' => $date,
                'start' => $v['start'],
                'end' => $v['end'],
                'addr' => $v['addr'],
                'count' => $v['count'],
                'sur' => $v['count'],
                'tip' => $v['tip'],
                'valid' => 1
            ]);
        }
    }
}