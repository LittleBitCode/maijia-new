<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alipay_recharge_model
 *
 */
class Alipay_recharge_model extends CI_Model {
    //put your code here

    /**
     * 发送post请求
     * @param type $gateway
     * @param type $post_data
     * @return type
     */
    private function _sendPost($gateway, $post_data){
        $ch = curl_init($gateway);
        curl_setopt($ch,CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /**
     * 通过sn获取，使用状态
     */
    public function get_sn_info($sn){
        $time = time();
        $sign = $this->createsign($sn, $time);

        $post_data = array(
            'sn'=>$sn,
            'time'=>$time,
            'sign'=>$sign
            );
        $gateway = 'http://106.75.29.239/alipay_tradeno/get_info_by_sn';
        $output = $this->_sendPost($gateway, $post_data);
        $output = $this->decrypt($output);
        $output = base64_decode($output);
        $output = json_decode($output);
        return $output;
    }

    /**
     * 更新充值状态
     */
    public function set_sn_not_valid($sn){
        $time = time();
        $sign = $this->createsign($sn, $time);

        $post_data = array(
            'sn'=>$sn,
            'time'=>$time,
            'sign'=>$sign
            );
        $gateway = 'http://106.75.29.239/alipay_tradeno/update_status';
        $output = $this->_sendPost($gateway, $post_data);
        $output = $this->decrypt($output);
        $output = base64_decode($output);
        $output = json_decode($output);
        return $output;
    }

    /**
     * 生成sign
     * @param type $sn
     * @param type $time
     * @return type
     */
    private function createsign($sn,$time){
        /**
         *$time = time();
         *$salt = "你的盐值";  //项目与接口方保持一致的盐值,不要轻易改动。
         *$key = "你的key";   //项目与接口方的key不要轻易改动。
         *
         */
        $project = array('key'=>'lkj&&^%$^add',  'salt'=>'renqifu_&&#ss');
        $salt = $project['salt'];
        $key = $project['key'];

        $sign = md5($sn.$salt.$key.$time);
        return $sign;
        /**
         * 注意：
         * 使用demo
         * 根据sn获取签名
         * post到 该网管
         * post字段
         * time = 上述$time值
         * sign = 上述$sign值
         * sn = 上述$sn值
         */
    }









    /**
     * 加密解密数据秘钥
     * @var type
     */
    private $_decryptpassport__encrypt_key = "01job_function_authored_by_ctm1688";

    /**
     * 加密数据
     * @param type $txt
     * @return type
     */
    public function encrypt($txt)
    {
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for($i = 0;$i < strlen($txt); $i++)
        {
           $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
           $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        return base64_encode($this -> passport_key($tmp));
    }

    /**
     * 解密数据
     * @param type $txt
     * @return type
     */
    public function decrypt($txt)
    {
        $txt = $this -> passport_key(base64_decode($txt));
        $tmp = '';
        for($i = 0;$i < strlen($txt); $i++) {
           $md5 = $txt[$i];
           $tmp .= $txt[++$i] ^ $md5;
        }
        return $tmp;
    }

    /**
     * 加密数据
     * @param type $txt
     * @return type
     */
    private function passport_key($txt)
    {
        $encrypt_key = md5($this ->_decryptpassport__encrypt_key);
        $ctr = 0;
        $tmp = '';
        for($i = 0; $i < strlen($txt); $i++)
        {
           $ctr = $ctr == strlen($this ->_decryptpassport__encrypt_key) ? 0 : $ctr;
           $tmp .= $txt[$i] ^ $this ->_decryptpassport__encrypt_key[$ctr++];
        }
        return $tmp;
    }
}
