<?php
/*
 * @Author: your name
 * @Date: 2021-02-06 21:55:55
 * @LastEditTime: 2021-02-06 23:10:38
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\admin\controller\services\Login.php
 */
namespace app\admin\controller\services;

use think\Request;
use app\common\model\Manager as ManagerModel;

class Login
{
    protected $request;
    protected $user;
    protected $pass;
    protected $manager;
    private $token;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function index()
    {
        $this->loadData() || abort(400);
        $this->loadManager() || abort(420, '用户名或密码错误');
        $this->isValid() || abort(220, '用户已被禁用');
        $this->createToken() || abort(520, '密钥创建失败');
        return json(['token' => $this->token]);
    }

    /**
     * @装载user pass: 
     * @param {*}
     * @return {*}
     */
    protected function loadData()
    {
        $this->user = $this->request->param('user/s');
        $this->pass = $this->request->param('pass/s');
        return $this->user && $this->pass;
    }

    /**
     * @查询用户: 
     * @param {*}
     * @return {*}
     */
    protected function loadManager()
    {
        $this->manager = ManagerModel::where([
            'user' => $this->user,
            'pass' => openssl_encrypt($this->pass, 'AES-256-ECB', config('app.login.salt'))
        ])->find();
        return $this->manager ? true : false;
    }

    /**
     * @用户未被禁用: 
     * @param {*}
     * @return {*}
     */
    protected function isValid()
    {
        return $this->manager->valid;
    }

    /**
     * @创建token: 
     * @param {*}
     * @return {*}
     */
    protected function createToken()
    {
        $config = config('app.jwt');
        $data = [
            'mid' => $this->manager->mid
        ];
        $token = app('jwt')->getToken($data, $config['expire'], $config['aud'], $config['iss'], $config['salt']);
        if(!$token){
            return false;
        }
        $this->token = $token;
        return true;
    }
}
