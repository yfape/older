<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-03-07 20:59:48
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\model\User.php
 */
declare (strict_types = 1);

namespace app\common\model;
use think\model\concern\SoftDelete;
use app\common\model\Section;

use think\Model;
use think\facade\Db;
/**
 * @mixin \think\Model
 */
class Record extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    protected $autoWriteTimestamp = true;
    protected $table = 'record';
    protected $pk = 'rid';
    protected $schema = [
        'rid' => 'int',
        'uid' => 'int',
        'sid' => 'int',
        'arrive' => 'int',
        'valid' => 'bit',
        'statustext' => 'string',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'rid' => 'integer',
        'uid' => 'integer',
        'sid' => 'integer',
        'arrive' => 'integer',
        'valid' => 'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function section(){
        return $this->belongsTo(Section::class, 'sid', 'sid');
    }

    public function getStatustextAttr($value, $data){
        if(!$data['valid']){
            return '取消预约';
        }
        if($data['arrive']){
            return '到场';
        }else{
            return '缺席';
        }
    }
}
