<?php
/*
 * @Author: your name
 * @Date: 2021-02-07 09:21:04
 * @LastEditTime: 2021-02-20 09:56:08
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \api\extend\wx\WXservice.php
 */
namespace wx;

class WXservice
{
    private $appid;
    private $appsecret;
    private $openid;
    private $sessionkey;

    public function __construct($appid, $appsecret)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }

    /**
     * @设置openid和sessionkey: 
     * @param {*} $openid
     * @param {*} $sessionkey
     * @return {*}
     */
    public function setOpenidSessionkey($openid, $sessionkey){
        $this->openid = $openid;
        $this->sessionkey = $sessionkey;
        return $this;
    }

    /**
     * @获取用户手机号: 
     * @param {*} $encryptedData
     * @param {*} $iv
     * @return {*}
     */
    public function getPhone($encryptedData, $iv){
        $errCode = $this->decryptData($encryptedData, $iv, $data );
        if($errCode === 0){
            $data = json_decode($data);
            return $data->purePhoneNumber ? $data->purePhoneNumber : ''; 
        }else{
            return false;
        }
    }
    
    public function decryptData( $encryptedData, $iv, &$data )
	{
		if (strlen($this->sessionkey) != 24) {
			return -41001;
		}
		$aesKey=base64_decode($this->sessionkey);

        
		if (strlen($iv) != 24) {
			return -41002;
		}
		$aesIV=base64_decode($iv);

		$aesCipher=base64_decode($encryptedData);

		$result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

		$dataObj=json_decode( $result );
		if( $dataObj  == NULL )
		{
			return -41003;
		}
		if( $dataObj->watermark->appid != $this->appid )
		{
			return -41003;
		}
		$data = $result;
		return 0;
	}
}