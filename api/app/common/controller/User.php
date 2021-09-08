<?php
/*
 * @Author: your name
 * @Date: 2021-02-08 15:55:19
 * @LastEditTime: 2021-02-08 17:25:35
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\common\controller\User.php
 */
namespace app\common\controller;

use think\facade\Db;
use think\facade\Request;

use app\common\model\Team as TeamModel;
use app\common\model\Record as RecordModel;
use app\common\model\Section as SectionModel;
use app\common\model\User as UserModel;

class User
{
    public function exitTeam($uid){
        Db::startTrans();
        try{
            UserModel::update(['tid' => null, 'uid' => $uid]);
            
            //查询用户出未完成的record
            RecordModel::destroy(function($q)use($uid){
                $q->where([
                    'complete' => 0,
                    'valid' => 1
                ]);
            });



            Db::commit();
        } catch(\Exception $e){
            Db::rollback();
        }
    }
}