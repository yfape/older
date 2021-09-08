<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-02-27 19:55:46
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
class LogSign extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    protected $autoWriteTimestamp = true;
    protected $table = 'log_sign';
    protected $pk = 'lid';
    protected $schema = [
        'lid' => 'int',
        'uid' => 'int',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'lid' => 'integer',
        'uid' => 'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}
