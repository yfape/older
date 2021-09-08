<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-02-08 11:44:34
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\model\User.php
 */
declare (strict_types = 1);

namespace app\wxapp\model;
use think\model\concern\SoftDelete;

use think\Model;

/**
 * @mixin \think\Model
 */
class Manager extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $table = 'manager';
    protected $pk = 'mid';
    protected $schema = [
        'mid' => 'int',
        'openid' => 'string',
        'phone' => 'string',
        'name' => 'string',
        'isadmin' => 'int',
        'user' => 'string',
        'pass' => 'string',
        'valid' => 'bit',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int'
    ];
    protected $type = [
        'uid' => 'integer',
        'isadmin' => 'integer',
        'valid' => 'integer'
    ];
    
    /**
     * @12345678901 => 123****8901
     * @param {*} $value
     * @return {*}
     */
    public function getSectionPhoneAttr($value, $data){
        if(!$data['phone']){
            return null;
        }
        return str_replace(substr($data['phone'].'', 3, 4), '****', $data['phone']);
    }
}
