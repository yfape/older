<?php
namespace app\admin\controller\services;

use think\facade\Request;
use think\facade\Filesystem;

class Upload {

	public function index(){
		$files = Request::file();
		try {
	        validate(['image'=>'filesize:12|fileExt:jpg,gif,png,jpeg,docx,doc,pdf|image:jpg,png,gif,jpeg'])->check($files);
	        $savename = [];
	        foreach($files as $file) {
	            $thisfile = \think\facade\Filesystem::putFile( '/logo', $file);
	            $thisfile = str_replace('\\','/',$thisfile);
	            $savename[] = imgOutFormat($thisfile);
	        }
	        return json($savename);
	    } catch (\Exception $e) {
	        abort(400, $e->getMessage());
	    }
	}

}