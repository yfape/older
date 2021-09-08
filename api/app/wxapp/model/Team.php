<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-03-03 11:24:50
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\model\User.php
 */
declare (strict_types = 1);

namespace app\wxapp\model;
use think\model\concern\SoftDelete;

use think\Model;
use think\facade\Db;
/**
 * @mixin \think\Model
 */
class Team extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = true;
    protected $table = 'team';
    protected $pk = 'tid';
    protected $schema = [
        'tid' => 'int',
        'name' => 'string',
        'content' => 'string',
        'time' => 'string',
        'addr' => 'string',
        'sum' => 'int',
        'count' => 'int',
        'sur' => 'int',
        'remain' => 'int',
        'valid' => 'bit',
        'statustext' => 'string',
        'open' => 'bit',
        'category' => 'int',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'tid' => 'integer',
        'content' => 'json',
        'sum' => 'integer',
        'count' => 'integer',
        'sur' => 'integer',
        'remain' => 'integer',
        'valid' => 'integer',
        'open' => 'integer',
        'category' => 'integer'
    ];

    public function section()
    {
        return $this->hasMany(Section::class, 'tid', 'tid');
    }

    public function logteam()
    {
        return $this->hasMany(LogTeam::class, 'tid', 'tid');
    }

    public function getStatustextAttr($value, $data){
        if(!$data['valid']){
            return '关闭';
        }
        if(!$data['open']){
            return '报名关闭';
        }
        return $data['sur'] <=0 ? '已满' : '开放';
    }

    public static function onBeforeInsert($model)
    {
        abort(540, '不能新增');
    }
}
