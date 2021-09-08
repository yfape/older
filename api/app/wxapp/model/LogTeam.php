<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-03-03 01:17:09
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
class LogTeam extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    protected $autoWriteTimestamp = true;
    protected $table = 'log_team';
    protected $pk = 'lid';
    protected $schema = [
        'lid' => 'int',
        'uid' => 'int',
        'tid' => 'int',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'lid' => 'integer',
        'uid' => 'integer',
        'tid' => 'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function team(){
        return $this->belongsTo(Team::class, 'tid', 'tid');
    }
}
