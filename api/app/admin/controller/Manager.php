<?php
namespace app\admin\controller;

use think\Request;
use app\common\model\Manager as ManagerModel;

class Manager
{
	protected $request;
	protected $manager;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->manager = ManagerModel::where('openid', $this->request->openid)->findOrEmpty();
		$this->manager->isEmpty() && abort(400, '不存在此账号');
    }
	
	public function getSelf()
	{
		return json($this->manager);
	}
	
	public function reviewPass()
	{
		$oldpass = $this->request->post('oldpass');
		$newpass = $this->request->post('newpass');
		
		($oldpass && $newpass) || abort(400);
		
		if(openssl_encrypt($oldpass, 'AES-256-ECB', config('app.login.salt')) != $this->manager->pass){
			abort(400, '旧密码错误');
		}
		
		$this->manager->pass = openssl_encrypt($newpass, 'AES-256-ECB', config('app.login.salt'));
		$this->manager->save();
		return json(['msg' => '修改密码成功']);
		
	}
	
	public function list()
	{
		$managers = ManagerModel::where(true)->select();
		return json($managers);
	}
	
	public function get($mid)
	{
		$manager = ManagerModel::find($mid);
		return json($manager);
	}
	
	public function resetPass($mid)
	{
		$manager = ManagerModel::find($mid);
		$manager->pass = openssl_encrypt('123', 'AES-256-ECB', config('app.login.salt'));
		$manager->save();
		return json(['msg' => '重置密码成功']);
	}
	
	public function delete($mid)
	{
		$manager = ManagerModel::find($mid);
		$manager->force()->delete();
		return json(['msg' => '删除成功']);
	}
	
	public function insert()
	{
		$manager = new ManagerModel;
		$data = $this->request->only(['phone', 'name', 'isadmin', 'user', 'valid']);
		$data['pass'] = openssl_encrypt('123', 'AES-256-ECB', config('app.login.salt'));
		$manager->save($data);
		return json(['msg' => '删除成功']);
	}
	
	public function update($mid)
	{
		$manager = ManagerModel::find($mid);
		$data = $this->request->only(['phone', 'name', 'isadmin', 'valid']);
		$data['valid'] = (int)$data['valid'];
		$data['isadmin'] = (int)$data['isadmin'];
		$manager->save($data);
		return json(['msg' => '更新成功']);
	}
}