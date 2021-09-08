<?php
/*
 * @Author: 刘尧夫
 * @Date: 2021-01-22 15:45:35
 * @LastEditTime: 2021-03-04 09:25:03
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\web\controller\login.php
 */
declare (strict_types = 1);

namespace app\wxapp\controller\common;

use think\facade\Request;
use Correspond\HttpCor;
use app\wxapp\model\User;
use app\wxapp\model\Manager;

class Login 
{
    private $code;
    private $appid;
    private $appsecret;
    private $code2Session;
    private $openid;
    private $sessionkey;
    private $identity;
    private $token;
    private $pass;
    
    public function __construct()
    {   
        $this->code2Session = config('app.wx.code2Session');
        $this->appid = config('app.wx.AppID');
        $this->appsecret = config('app.wx.AppSecret');
        $this->code2Session = $this->code2Session . "?appid=$this->appid&secret=$this->appsecret";
    }

    /**
     * @常规登陆入口: 
     * @param {*}
     * @return {*}
     */
    public function index()
    {
        $this->code = Request::param('code');
        $this->code || abort(400);
        $this->getOpenId() || abort(420);
        $this->createToken() || abort(520);
        $this->verifyIdentity() || abort(550, '您已被禁止登陆');
        return json([
            'token' => $this->token,
            'pass' => $this->pass,
            'identity' => $this->identity
        ]);
    }

    /**
     * @携带code请求wx，获取openid: 
     * @param {*}
     * @return true or false
     */
    protected function getOpenId()
    {
        $this->code2Session = $this->code2Session . "&js_code=$this->code&grant_type=authorization_code";
        $httpcor = new HttpCor();
        $res = $httpcor->setUrl($this->code2Session)->get();
        if(!isset($res['openid'])){
            return false;
        }
        $this->openid = isset($res['openid']) ? $res['openid'] : false;
        $this->sessionkey = isset($res['session_key']) ? $res['session_key'] : false;
        return true;
    }

    /**
     * @创建token，包含openid、sessionkey 
     * @param {*}
     * @return bool
     */
    protected function createToken()
    {
        $config = config('app.jwt');
        $data = [
            'openid' => $this->openid,
            'sessionkey' => $this->sessionkey
        ];
        $token = app('jwt')->getToken($data, $config['expire'], $config['aud'], $config['iss'], $config['salt']);
        if(!$token){
            return false;
        }
        $this->token = $token;
        return true;
    }

    /**
     * @通过openid确认登陆用户身份，并设置pass 
     * @param {*}
     * @return true or false
     */
    protected function verifyIdentity()
    {
        $user = User::with(['team', 'team2', 'singleteam'])->where('openid', $this->openid)->findOrEmpty();
        if($user->isEmpty()){
            $manager = Manager::where('openid', $this->openid)->findOrEmpty();
            if($manager->isEmpty()){
                $this->pass = 0;
            }else{
                if(!$manager->valid){
                    return false;
                }
                $this->identity = $manager;
                $this->pass = 2;
            }
        }else{
            if(!$user->valid){
                return false;
            }
            $this->identity = $user;
            $this->pass = 1;
        }
        return true;
    }
}
