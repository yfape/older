<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-03-03 16:32:38
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\model\User.php
 */
declare (strict_types = 1);

namespace app\wxapp\model;
use app\common\model\Team;
use think\model\concern\SoftDelete;

use think\Model;
use think\facade\Db;

/**
 * @mixin \think\Model
 */
class Section extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = true;
    protected $table = 'section';
    protected $pk = 'sid';
    protected $schema = [
        'sid' => 'int',
        'tid' => 'int',
        'date' => 'date',
        'start' => 'string',
        'end' => 'string',
        'addr' => 'string',
        'count' => 'int',
        'sur' => 'int',
        'tip' => 'string',
        'valid' => 'bit',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'sid' => 'integer',
        'tid' => 'integer',
        'date' => 'datetime:Y/m/d',
        'count' => 'integer',
        'sur' => 'integer',
        'valid' => 'integer'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'tid', 'tid');
    }

    public function record()
    {
        return $this->hasMany(Record::class, 'sid', 'sid');
    }

    public function getStatustextAttr($value, $data)
    {
        if(!$data['valid']){
            return '场次取消';
        }
        $status = $this->getStatusAttr($value, $data);
        $res = '';
        switch($status){
            case 0: $res = '未开始'; break;
            case 1: $res = '进行中'; break;
            case 9: $res = '已完结'; break;
            default: $res = '场次异常'; break;
        }
        return $res;
    }

    public function getStatusAttr($value, $data)
    {
        $time = time();
        $starttime = strtotime($data['date'].' '.$data['start']);
        $endtime = strtotime($data['date'].' '.$data['end']);

        $newstatus = 0;
        if($time >= $endtime){
            $newstatus = 9;
        }else if($time >= $starttime){
            $newstatus = 1;
        }else{
            $newstatus = 0;
        }
        return $newstatus;
    }

    /**
     * @新增前: 
     * 1、开始时间必须小于结束时间
     * 2、人数必须大于0
     * @param {*} $model
     * @return {*}
     */
    public static function onBeforeInsert($model)
    {
        abort(540, '不能新增');
    }
}
