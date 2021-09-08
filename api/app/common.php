<?php
/*
 * @Author: your name
 * @Date: 2021-02-07 01:08:49
 * @LastEditTime: 2021-03-09 00:18:28
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \api\app\common.php
 */
/*
 * @Author: your name
 * @Date: 2021-02-07 01:08:49
 * @LastEditTime: 2021-02-08 01:21:55
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \api\app\common.php
 */
// 应用公共文件
function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) {
        return true;
    }
    if (!mkdirs(dirname($dir), $mode)) {
        return false;
    }
    return @mkdir($dir, $mode);
}
//可逆加密
function encrypt($data, $key) { 
    $prep_code = serialize($data); 
    $block = mcrypt_get_block_size('des', 'ecb'); 
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) { 
    $prep_code .= str_repeat(chr($pad), $pad); 
    } 
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB); 
    return base64_encode($encrypt); 
} 

//可逆解密
function decrypt($str, $key) { 
    $str = base64_decode($str); 
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB); 
    $block = mcrypt_get_block_size('des', 'ecb'); 
    $pad = ord($str[($len = strlen($str)) - 1]); 
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) { 
    $str = substr($str, 0, strlen($str) - $pad); 
    } 
    return unserialize($str); 
} 

function imgOutFormat($img){
	if(!$img){
		return;
	}
	if(strstr($img,'http') === false){
		return 'http://'.env('LOCALHOST').config('filesystem.disks.public.url').'/'.$img;
	}else{
		return $img;
	}
} 

function imgInFormat($img){
	if(strstr($img,env('LOCALHOST')) === false){
		return $img;
	}
	$arr = explode('/', $img);
	$arr = array_slice($arr,3);

	return '/'.implode('/', $arr);
}