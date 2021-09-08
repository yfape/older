<?php
/*
 * @Author: 刘尧夫
 * @Date: 2021-01-23 00:36:59
 * @LastEditTime: 2021-01-23 01:03:46
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\extend\Correspond\HttpCor.php
 */

namespace Correspond;

class HttpCor 
{
    
    private $ch;
    private $headers = [
        "Content-type:application/json;",
        "Accept:application/json"
    ];

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch,CURLOPT_HTTPHEADER,$this->headers);
    }

    /**
     * @set argument 'url' to curl 
     * @param {*} $url
     * @return self
     */
    public function setUrl($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        return $this;
    }

    /**
     * @set argument 'headers' to curl: 
     * @param {*} $headers
     * @return self
     */
    public function setHeaders($headers)
    {
        curl_setopt($this->ch,CURLOPT_HTTPHEADER,$headers);
        return $this;
    }

    /**
     * @set argument 'SSL' to curl: 
     * @param {*} $pem
     * @return self
     */
    public function setSSL($pem)
    {
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($this->ch, CURLOPT_CAINFO, $pem);
        return $this;
    }

    /**
     * @emit a GET request 
     * @param {*}
     * @return {*}
     */
    public function get()
    {
        return $this->receiveData();
    }

    /**
     * @emit a POST request : 
     * @param {*} $data
     * @return {*}
     */
    public function post($data = null)
    {
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        return $this->receiveData();
    }

    /**
     * @agent exec: 
     * @param {*} $isjson
     * @return {*}
     */
    protected function receiveData($isjson = true)
    {
        $res = curl_exec($this->ch);
        curl_close($this->ch);
        return $isjson ? json_decode($res, true) : $res;
    }
}