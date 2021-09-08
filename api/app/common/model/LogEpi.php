<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-03-01 13:26:46
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\model\User.php
 */
declare (strict_types = 1);

namespace app\common\model;
use think\model\concern\SoftDelete;

use think\Model;
use think\facade\Db;
/**
 * @mixin \think\Model
 */
class LogEpi extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    protected $autoWriteTimestamp = true;
    protected $table = 'log_epi';
    protected $pk = 'lid';
    protected $schema = [
        'lid' => 'int',
        'uid' => 'int',
        'date' => 'date',
        'islocal' => 'bit',
        'istour' => 'bit',
        'istouchlocal' => 'bit',
        'istouch' => 'bit',
        'istouchrecovery' => 'bit',
        'isill' => 'bit',
        'iswilling' => 'bit',
        'ishightemp' => 'bit',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'lid' => 'integer',
        'uid' => 'integer',
        'islocal' => 'integer',
        'istour' => 'integer',
        'istouchlocal' => 'integer',
        'istouch' => 'integer',
        'istouchrecovery' => 'integer',
        'isill' => 'integer',
        'iswilling' => 'integer',
        'ishightemp' => 'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}
