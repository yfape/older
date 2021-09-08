<?php
/*
 * @Author: your name
 * @Date: 2021-02-08 15:02:32
 * @LastEditTime: 2021-02-18 21:27:13
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\admin\controller\user\User.php
 */
namespace app\admin\controller\user;

use think\Request;
use think\facade\Db;
use app\common\model\User as UserModel;
use app\common\model\Team as TeamModel;

class User
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @获取用户列表: 
     * @param {*} $field
     * @param {*} $value
     * @param {*} $limit
     * @param {*} $page
     * @return {*}
     */
    public function list($field, $value, $limit, $page)
    {
        $users = [];
        try{
            $users = UserModel::where($field, $value)->order('update_time','desc')->paginate([
                'list_rows'=> $limit,
                'page' => $page,
            ]);
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json($users);
    }

    /**
     * @单项更新: 
     * @param {*}
     * @return {*}
     */
    public function put($uid, $field, $value=null)
    {
        try{
            $user = UserModel::find($uid);
            $user[$field] = $value;
            $user->save();
        } catch(\Exception $e){
            abort(540, $e->getMessage());
        }
        return json(['msg' => '更新成功']);
    }
}