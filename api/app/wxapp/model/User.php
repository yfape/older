<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 14:57:19
 * @LastEditTime: 2021-03-04 09:13:47
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\common\model\User.php
 */
declare (strict_types = 1);

namespace app\wxapp\model;
use think\model\concern\SoftDelete;
use think\Model;
use app\common\model\Team;
use think\facade\Db;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    protected $autoWriteTimestamp = true;
    protected $table = 'user';
    protected $pk = 'uid';
    protected $schema = [
        'uid' => 'int',
        'openid' => 'string',
        'incode' => 'string',
        'name' => 'string',
        'phone' => 'string',
        'tid' => 'int',
        't2id' => 'int',
        'single' => 'int',
        'valid' => 'bit',
        'forbid' => 'int',
        'statustext' => 'string',
        'delete_time' => 'int',
        'create_time' => 'int',
        'update_time' => 'int',
    ];
    protected $type = [
        'uid' => 'integer',
        'phone' => 'integer',
        'tid' => 'integer',
        't2id' => 'integer',
        'single' => 'integer',
        'valid' => 'integer',
        'forbid' => 'integer'
    ];

    /**
     * @12345678901 => 123****8901
     * @param {*} $value
     * @return {*}
     */
    public function getSectionPhoneAttr($value, $data)
    {
        if(!$data['phone']){
            return null;
        }
        return str_replace(substr($data['phone'].'', 3, 4), '****', $data['phone']);
    }

    public function getStatustextAttr($value, $data)
    {
        if(!$data['valid']){
            return '限制登陆';
        }
        if($data['forbid'] > time()){
            return '限制预约';
        }
        return '正常';
    }

    public function record()
    {
        return $this->hasMany(Record::class, 'uid', 'uid');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'tid', 'tid')->bind([
            'teamname' => 'name'
        ]);
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 't2id', 'tid')->bind([
            'team2name' => 'name'
        ]);
    }

    public function singleteam()
    {
        return $this->belongsTo(Team::class, 'single', 'tid')->bind([
            'singleteam' => 'name'
        ]);
    }

    public static function onBeforeInsert($model)
    {
        abort(540, '不能新增');
    }

    public static function onBeforeUpdate($model)
    {
        
    }

    public static function onBeforeDelete($model)
    {
        abort(540, '不能删除');
    }
}
