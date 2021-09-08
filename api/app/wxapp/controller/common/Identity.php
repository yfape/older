<?php
/*
 * @Author: your name
 * @Date: 2021-01-26 22:17:05
 * @LastEditTime: 2021-03-08 11:24:35
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\common\Identity.php
 */
/*
 * @Author: your name
 * @Date: 2021-01-26 22:17:05
 * @LastEditTime: 2021-01-27 00:40:53
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\common\Identity.php
 */
/*
 * @Author: your name
 * @Date: 2021-01-26 16:58:19
 * @LastEditTime: 2021-01-26 22:34:40
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\controller\common\Identity.php
 */
namespace app\wxapp\controller\common;

use think\Request;
use wx\WXservice;
use app\wxapp\model\User;
use app\wxapp\model\Manager;

class Identity
{
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $num = app('rhash')->setTable('lg_id_fo')->setKey($this->request->openid)->get('n');
        if($num >= config('app.tactic.identity')){
            abort(423, '抱歉，您尝试验证次数太多，请稍后重试');
        }
    }

    /**
     * @通过用户手机号确定用户身份: 
     * @param {*}
     * @return {*}
     */
    public function byPhone()
    {
        $encryptedData = urldecode($this->request->post('encryptedData'));
        $iv = $this->request->post('iv');
        $wx = new WXservice(config('app.wx.AppID'), config('app.wx.AppSecret'));
        $phone = $wx->setOpenidSessionkey($this->request->openid, $this->request->sessionkey)->getPhone($encryptedData, $iv);
        $phone || abort(422, '无法获取您的手机号码，请联系管理员');
        $user = User::where('phone', $phone)->findOrEmpty();
        if(!$user->isEmpty()){
            $out = [
                'name' => $user->name,
                'phone' => $user->phone,
                'incode' => $user->incode
            ];
            return json([
                'pass' => 9,
                'identity' => $out,
                'identitykey' => $this->createIdentitykey($out)
            ]);
        }
        $manager = Manager::where('phone', $phone)->findOrEmpty();
        if(!$manager->isEmpty()){
            $out = [
                'name' => $manager->name,
                'phone' => $manager->phone,
            ];
            return json([
                'pass' => 9,
                'identity' => $out,
                'identitykey' => $this->createIdentitykey($out),
            ]);
        }
        return json([
            'pass' => 0,
            'identity' => [
                'phone' => $phone
            ]
        ]);
    }

    /**
     * @通过用户输入的incode等信息确定身份: 
     * @param {*}
     * @return {*}
     */
    public function byIncode()
    {
        $phone = $this->request->post('phone');
        $name = $this->request->post('name');
        $incode = $this->request->post('incode');
        ($phone && $name && $incode) || abort(400);
        $user = User::where('incode', $incode)->findOrEmpty();
        if($user->isEmpty() || ($user->name != $name)){
            $errnum = $this->setRedisForbid();
            $sn = config('app.tactic.identity') - $errnum;
            $sen = $sn >0 ? "信息不匹配，您还有" . $sn . "次尝试机会" : "抱歉，您尝试验证次数太多，请稍后重试";
            abort(424, $sen);
        }
        $out = [
            'name' => $user->name,
            'phone' => $phone,
            'incode' => $user->incode
        ];
        return json([
            'pass' => 9,
            'identity' => $out,
            'identitykey' => $this->createIdentitykey($out)
        ]);
    }

    /** 
     * @用户确认身份: 
     * @param {*}
     * @return {*}
     */
    public function affirmIdentity()
    {
        $phone = $this->request->post('phone');
        $name = $this->request->post('name');
        // $incode = $this->request->post('incode');
        $identitykey = $this->request->post('identitykey');

        $res = app('jwt')->verToken($identitykey, config('app.jwt.salt'), config('app.jwt.jsclass'));
        $res || abort(400);
        $t_name = $res->name;
        $t_phone = $res->phone;
        (($t_name == $name) && ($t_phone == $phone)) || abort(400, '您的身份信息异常');
        
        $pass = null;
        $user = null;
        $incode = property_exists($res, 'incode') ? $res->incode : false;
        if($incode){
        	$tuser = User::where("phone=$phone and incode!=$incode")->findOrEmpty();
        	if(!$tuser->isEmpty()){
        		$tuser->openid = null;
        		$tuser->phone = null;
        		$tuser->save();
        	}
        	
        	$user = User::where('incode', $incode)->findOrEmpty();
        	$user->isEmpty() && abort(400, '未匹配的您的身份信息');
	        $user->openid = $this->request->openid;
            $user->phone = $phone;
            $user->save();
            $pass = 1;
        }else{
        	$user = Manager::where('phone', $phone)->findOrEmpty();
            $user->isEmpty() && abort(400, '未匹配的您的身份信息');
            $user->openid = $this->request->openid;
            $user->phone = $phone;
            $user->save();
            $pass = 2;
        }
        return json([
            'pass' => $pass,
            'identity' => $user
        ]);
    }

    /**
     * @description: 
     * @param {*}
     * @return {*}
     */
    protected function createIdentitykey($data){
        $config = config('app.jwt');
        $token = app('jwt')->getToken($data, $config['expire'], $config['aud'], $config['iss'], $config['salt']);
        return $token;
    }


    /**
     * @当用户验证错误标记至redis-forbid: 
     * @param {*}
     * @return {*}
     */
    protected function setRedisForbid(): int{
        $num = app('rhash')->setTable('lg_id_fo')->setKey($this->request->openid)->get('n');
        $num = $num ? (int)$num : 0;
        app('rhash')->setTable('lg_id_fo')->setKey($this->request->openid)->set(['n' => ++$num], '', true)->setExpire(3600);
        return $num;
    }
}