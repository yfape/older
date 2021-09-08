<?php
/*
 * @Author: your name
 * @Date: 2021-03-08 22:55:04
 * @LastEditTime: 2021-03-09 02:00:55
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\outPut.php
 */
namespace app\admin\controller;

use PHPExcel;
use PHPExcel_IOFactory;

use app\common\model\Team as TeamModel;
use app\common\model\User as UserModel;

class Output
{
    public function teams()
    {
        $teams = TeamModel::where(true)->field('name as 姓名,time as 时间, addr as 地点, sum as 报名名额, sur as 剩余名额, remain as 预留人数, open as 报名状态')->select()->toArray();
        $res = $this->out('班队列表', $teams);
        return $res;
    }

    public function teamusers($tid)
    {
        $team = TeamModel::find($tid);
        $users = UserModel::where("tid=$tid or t2id=$tid")->with(['team','team2','single'])
        ->select();
        
        $resarr = [];
        for($i=0;$i<count($users);$i++){
            $resarr[$i] = [
                '卡号' => $users[$i]['incode'].' ',
                '姓名' => $users[$i]['name'],
                '电话' => $users[$i]['phone'].' ',
                '日常班' => $users[$i]['teamname'],
                '教学班' => $users[$i]['team2name'],
                '单科' => $users[$i]['singlename'],
            ];
        }

        $res = $this->out($team->name.'班队学员表', $resarr);
        return $res;
    }

    public function users()
    {
        $users = UserModel::with(['team','team2','single'])->select();
        $resarr = [];
        for($i=0;$i<count($users);$i++){
            $resarr[$i] = [
                '卡号' => $users[$i]['incode'].' ',
                '姓名' => $users[$i]['name'],
                '电话' => $users[$i]['phone'].' ',
                '日常班' => $users[$i]['teamname'],
                '教学班' => $users[$i]['team2name'],
                '单科' => $users[$i]['singlename'],
            ];
        }
        $res = $this->out('全体用户表', $resarr);
        return $res;
    }

    protected function out($title, $data)
    {
        $abcarr = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q'];
        $headers = array_keys($data[0]);

        $objExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objActSheet = $objExcel->getActiveSheet(0);
        $objActSheet->setTitle($title); //设置excel的标题
        $objActSheet->setCellValue('A1', $title)->mergeCells('A1:'.$abcarr[count($headers)-1].'1');
        foreach($headers as $key => $value){
            $objActSheet->setCellValue($abcarr[$key].'2', $headers[$key]);
        }        
        for($i=0; $i<count($data); $i++){
            $row = $data[$i];
            $j = 0;
            foreach($row as $key => $value){
                $objExcel->getActiveSheet()->setCellValue($abcarr[$j].($i+3), $value);
                $j++;
            }
        }

        $objExcel->setActiveSheetIndex(0);        
        $time = time();
        $filename = $title.'.xlsx';
        $filedir = config('filesystem.disks.public.root').'/output';
        mkdirs($filedir);
        $fileaddr = $filedir.'/'.$filename;
        $fileaddr = str_replace("\\",'/',$fileaddr);
        if(file_exists($fileaddr)){
            unlink($fileaddr);
        }
        
        $objWriter->save($fileaddr);
        $webdomain = 'http://'.env('LOCALHOST');
        return json($webdomain.'/storage/output/'.$filename);
    }
}