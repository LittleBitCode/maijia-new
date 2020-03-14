<?php

/**
 * 名称:生成唯一单号模型
 * 担当:
 */
class Uniq_Model extends CI_Model
{

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 生成唯一活动号
     */
    public function create_trade_sn()
    {
        do {
            $trade_sn = 'T' . $this->uniq_sn(true);
            $check_row = $this->db->get_where('rqf_trade_info', ['trade_sn' => $trade_sn])->row();
        } while ($check_row);

        return $trade_sn;
    }

    /**
     * 生成唯一支付单号
     */
    public function create_pay_sn($prefix)
    {
        do {
            $pay_sn = $prefix . $this->uniq_sn();
            $check_row = $this->db->get_where('rqf_pay_log', ['pay_sn' => $pay_sn])->row();
        } while ($check_row);

        return $pay_sn;
    }

    /**
     * 生成唯一提现单号
     */
    public function create_withdrawal_sn($prefix)
    {
        do {
            $withdrawal_sn = $prefix . $this->uniq_sn();
            $check_row = $this->db->get_where('rqf_user_withdrawal', ['withdrawal_sn' => $withdrawal_sn])->row();
        } while ($check_row);

        return $withdrawal_sn;
    }

    /** 支付宝提交生成随机数 */
    public function create_alipay_random()
    {
        do {
            $random = strval(rand(100000, 999999));
            $check_row = $this->db->query('select 1 from rqf_alipay_pay_recode where title = ?', [$random])->row();
        } while ($check_row);

        return $random;
    }

    /**
     * 生成唯一单号
     */
    private function uniq_sn($b = false)
    {
        if ($b) {
            return strtoupper(dechex(date('m')) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99)));
        } else {
            return strtoupper(date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99)));
        }
    }
}
