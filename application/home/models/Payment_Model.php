<?php
/**
 * 支付模型
 * @author hu
 */

class Payment_Model extends CI_Model {
    
    /**
     * weibo 钱包退款银行列表
     * **/
    public function get_weibo_bank_list() {
        return array(
                'ABC' => '中国农业银行',
                'BOC' => '中国银行',
                'CMB' => '招商银行',
                'COMM' => '交通银行',
                'ICBC' => '中国工商银行',
                'SPDB' => '上海浦东发展银行',
                'SZPAB' => '平安银行',
                'CCB' => '中国建设银行',
                'CMBC' => '中国民生银行',
                'CITIC' => '中信银行',
                'CEB' => '中国光大银行',
                'CIB' => '兴业银行',
                'GDB' => '广东发展银行',
                'HXB' => '华夏银行'
            );
    }

    /**
     * 支付方式
     * **/
    function get_pay_type_list() {
        return array('bank'=>'银行卡', 'alipay'=>'支付宝');
    }


    
    
}