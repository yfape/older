<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 10:03:06
 * @LastEditTime: 2021-01-26 15:33:45
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\extend\jwt\Myjwt.php
 */
namespace jwt;

use Firebase\JWT\JWT;
/*
生成及验证token
 */
class Myjwt{

	/**
	 * 创建token
	 * @param  [arr] $data [description]
	 * @param  [int] $exp  [description]
	 * @param  [str] $aud  [description]
	 * @param  [str] $iss  [description]
	 * @param  [str] $key  [description]
	 * @return [str] token [description]
	 */
	public function getToken($data,$exp = 0,$aud,$iss,$key){
		$time = time();
		$token = [
			'iss' => $iss,
			'aud' => $aud,
			'iat' => $time,
			'nbf' => $time,
			'exp' => $time+$exp,
			'data' => $data,
		];
		return JWT::encode($token,$key);
	}
	
	/**
	 * 验证token
	 * @param  [str] $jwt     [description]
	 * @param  [str] $key     [description]
	 * @param  [arr] $jmclass [description]
	 * @return [arr]          [description]
	 */
	public function verToken($jwt,$key,$jmclass = [])
	{
		try {
	    	JWT::$leeway = 60;//当前时间减去60，把时间留点余地
	       	$decoded = JWT::decode($jwt, $key, $jmclass);//HS256方式，这里要和签发的时候对应
	       	$arr = (array)$decoded;
	       	// $arr = array_merge($arr,array('status'=>'1111'));
	       	return isset($arr['data']) ? $arr['data'] : false;
	    }catch(\Exception $e) {  //其他错误
	    	return false;
	    }
	}
	
}