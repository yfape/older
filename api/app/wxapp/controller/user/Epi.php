<?php
/*
 * @Author: your name
 * @Date: 2021-02-23 11:18:05
 * @LastEditTime: 2021-03-03 01:16:24
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\wxapp\controller\user\Log.php
 */
namespace app\wxapp\controller\user;

use app\wxapp\controller\user\UserCommon;
use app\wxapp\model\LogEpi as LogEpiModel;

class Epi extends UserCommon
{
    public function isEpi()
    {
    	return json(['isepi' => 1]);
    	
        $dates = LogEpiModel::where('uid', $this->user->uid)
        ->where('date >= date_sub(CURDATE(),interval 7 day)')
        ->column('date');

        $sysdates = $this->getDates();
        $nodates = array_diff($sysdates, $dates);


        if(count($nodates)>0){
            return json([
                'isepi' => 0,
                'nodates' => $nodates
            ]);
        }else{
            return json(['isepi' => 1]);
        }
    }

    public function save()
    {
        $data = $this->request->post();
        $data['uid'] = $this->user->uid;
        LogEpiModel::create($data);
        return json(['msg' => '提交成功']);
    }

    protected function getDates()
    {
        $arr = [];
        $dt_start = strtotime('-7 day', time());
        $dt_end = time();
        while ($dt_start<=$dt_end){
            $arr[] = date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $arr;
    }
}