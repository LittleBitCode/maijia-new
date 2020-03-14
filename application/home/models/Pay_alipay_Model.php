<?php
/**
 * 支付宝流水账号充值
 *
 */
class Pay_alipay_Model extends CI_Model{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * 校验流水账号是否已经使用
     * @return true 已经使用 false 未使用
     *
    */
    public function check_pay_sn($sn){
        $check_sql_0 = "select 1 from rqf_buy_user_point_0 where pay_sn = ?";
        $check_sql_1 = "select 1 from rqf_buy_user_point_1 where pay_sn = ?";
        $check_sql_2 = "select 1 from rqf_buy_user_point_2 where pay_sn = ?";
        $check_sql_3 = "select 1 from rqf_buy_user_point_3 where pay_sn = ?";
        $check_sql_4 = "select 1 from rqf_buy_user_point_4 where pay_sn = ?";
        $check_sql_5 = "select 1 from rqf_buy_user_point_5 where pay_sn = ?";
        $check_sql_6 = "select 1 from rqf_buy_user_point_6 where pay_sn = ?";
        $check_sql_7 = "select 1 from rqf_buy_user_point_7 where pay_sn = ?";
        $check_sql_8 = "select 1 from rqf_buy_user_point_8 where pay_sn = ?";
        $check_sql_9 = "select 1 from rqf_buy_user_point_9 where pay_sn = ?";
        $check_sql_bus_deposit = "select 1 from rqf_bus_user_deposit where pay_sn = ?";
        $check_sql_bus_point = "select 1 from rqf_bus_user_point where pay_sn = ?";
        $this->write_db = $this->load->database('write', true);//使用主库避免主从延迟情况

        $check_info_0 = $this->write_db->query($check_sql_0,array($sn))->row();
        $check_info_1 = $this->write_db->query($check_sql_1,array($sn))->row();
        $check_info_2 = $this->write_db->query($check_sql_2,array($sn))->row();
        $check_info_3 = $this->write_db->query($check_sql_3,array($sn))->row();
        $check_info_4 = $this->write_db->query($check_sql_4,array($sn))->row();
        $check_info_5 = $this->write_db->query($check_sql_5,array($sn))->row();
        $check_info_6 = $this->write_db->query($check_sql_6,array($sn))->row();
        $check_info_7 = $this->write_db->query($check_sql_7,array($sn))->row();
        $check_info_8 = $this->write_db->query($check_sql_8,array($sn))->row();
        $check_info_9 = $this->write_db->query($check_sql_9,array($sn))->row();

        $check_bus_deposit_info = $this->write_db->query($check_sql_bus_deposit,array($sn))->row();
        $check_bus_point_info = $this->write_db->query($check_sql_bus_point,array($sn))->row();

        $this->write_db->close();

        if($check_info_0 || $check_info_1 || $check_info_2 || $check_info_3 || $check_info_4 || $check_info_5 || $check_info_6 || $check_info_7 || $check_info_8 || $check_info_9 || $check_bus_deposit_info || $check_bus_point_info) {

            return true;
        }

        return false;

    }
}