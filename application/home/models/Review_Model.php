<?php

/**
 * 名称:审核模型
 * 担当:
 */
class Review_Model extends CI_Model
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Conf_Model', 'conf');
    }

    /**
     * 获取待确认平台件数
     */
    public function review_plat_cnts()
    {
        $plat_list = $this->conf->plat_list();
        $user_id = intval($this->session->userdata('user_id'));
        $sql = "select plat_id from rqf_trade_order_union where bus_user_id = ? and order_status in (2,4,6)";
        $res = $this->db->query($sql, [$user_id])->result();
        foreach ($res as $v) {
            $plat_list[$v->plat_id]['cnt'] += 1;
        }

        foreach ($plat_list as $k => $v) {
            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }

        return $plat_list;
    }

    /**
     * 商家个人中心头部提示信息
     */
    public function get_refund_header()
    {
        $user_id = intval($this->session->userdata('user_id'));
        $sql = "select count(idx) cnt,min(confirm_time) ctime from rqf_trade_order_union where bus_user_id = ? and order_status in (4,6)";
        $row = $this->db->query($sql, [$user_id])->row();
        $show = 0;
        $date = '';
        $cnt = '';
        if ($row && $row->cnt != 0) {
            $show = 1;
            $date = date("Y-m-d H:i:s", $row->ctime);
            $cnt = $row->cnt;
        }

        return ['show' => $show, 'date' => $date, 'cnt' => $cnt];
    }


    /** 查询订单各状态对应的数量值 **/
    public function get_order_status_nums()
    {
        $user_id = intval($this->session->userdata('user_id'));
        $plat_list = $this->conf->plat_list();
        $sql = 'select plat_id, order_status, trade_type from rqf_trade_order_union where bus_user_id = ? and (order_status < 7 or (order_status > 7 and first_start_time >= ?))';
        $query_list = $this->db->query($sql, [$user_id, strtotime('-2 day')])->result();
        $result = [];
        foreach ($query_list as $item) {
            if (!array_key_exists($item->plat_id, $result)) {
                $result[$item->plat_id] = $plat_list[$item->plat_id];
                $result[$item->plat_id]['status_0'] = 0;
                $result[$item->plat_id]['status_1'] = 0;
                $result[$item->plat_id]['status_2'] = 0;
                $result[$item->plat_id]['status_3'] = 0;
                $result[$item->plat_id]['status_4'] = 0;
                $result[$item->plat_id]['status_5'] = 0;
                $result[$item->plat_id]['status_99'] = 0;
                $result[$item->plat_id]['traffic_1'] = 0;
                $result[$item->plat_id]['refunds'] = 0;
            }
            switch ($item->order_status) {
                case '0':
                    // 已接手 待下单
                    $result[$item->plat_id]['status_0'] += 1;
                    break;
                case '1':
                    // 已下单待打印快递单
                    $result[$item->plat_id]['status_1'] += 1;
                    break;
                case '2':
                    // 打印快递单、待发货
                    $result[$item->plat_id]['status_2'] += 1;
                    break;
                case '3':
                    // 已发货
                    if ($item->trade_type != '115') {
                        $result[$item->plat_id]['status_3'] += 1;
                    }
                    break;
                case '4':
                case '6':
                    // 待确认返款
                    if ($item->trade_type == '115') {
                        $result[$item->plat_id]['refunds'] += 1;
                    } else {
                        $result[$item->plat_id]['status_4'] += 1;
                    }
                    break;
                case '5':
                    // 待确认返款
                    $result[$item->plat_id]['status_5'] += 1;
                    break;
                default:
                    $result[$item->plat_id]['status_99'] += 1;
                    break;
            }
        }

        // 浏览订单
//        $sql = 'select plat_id, count(*) cnts from rqf_traffic_record_union where bus_user_id = ? and traffic_status = 1 group by plat_id ';
//        $query_traffic_list = $this->db->query($sql, [$user_id])->result();
//        foreach ($query_traffic_list as $item) {
//            $result[$item->plat_id]['traffic_1'] = intval($item->cnts);
//        }
        return $result;
    }

    /**
     * 获取待平台返款平台件数
     */
    public function plat_refund_plat_cnts()
    {
        $plat_list = $this->conf->plat_list();
        $user_id = intval($this->session->userdata('user_id'));
        $sql = "select plat_id from rqf_trade_order_union where bus_user_id = ? and order_status in (4,6) and plat_refund = 1";
        $res = $this->db->query($sql, [$user_id])->result();
        foreach ($res as $v) {
            $plat_list[$v->plat_id]['cnt'] += 1;
        }

        foreach ($plat_list as $k => $v) {
            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }

        return $plat_list;
    }

    /**
     * 获取交易编号的重复性
     * @param trade_serial_no 交易编号
     * @return bool true 已存在 false不存在
     */
    public function check_serial_no($trade_serial_no)
    {
        $sql_0 = "select 1 from rqf_order_info_0 where trade_serial_no = ?";
        $sql_1 = "select 1 from rqf_order_info_1 where trade_serial_no = ?";
        $sql_2 = "select 1 from rqf_order_info_2 where trade_serial_no = ?";
        $sql_3 = "select 1 from rqf_order_info_3 where trade_serial_no = ?";
        $sql_4 = "select 1 from rqf_order_info_4 where trade_serial_no = ?";
        $sql_5 = "select 1 from rqf_order_info_5 where trade_serial_no = ?";
        $sql_6 = "select 1 from rqf_order_info_6 where trade_serial_no = ?";
        $sql_7 = "select 1 from rqf_order_info_7 where trade_serial_no = ?";
        $sql_8 = "select 1 from rqf_order_info_8 where trade_serial_no = ?";
        $sql_9 = "select 1 from rqf_order_info_9 where trade_serial_no = ?";

        $check_0 = $this->db->query($sql_0, $trade_serial_no)->row();
        $check_1 = $this->db->query($sql_1, $trade_serial_no)->row();
        $check_2 = $this->db->query($sql_2, $trade_serial_no)->row();
        $check_3 = $this->db->query($sql_3, $trade_serial_no)->row();
        $check_4 = $this->db->query($sql_4, $trade_serial_no)->row();
        $check_5 = $this->db->query($sql_5, $trade_serial_no)->row();
        $check_6 = $this->db->query($sql_6, $trade_serial_no)->row();
        $check_7 = $this->db->query($sql_7, $trade_serial_no)->row();
        $check_8 = $this->db->query($sql_8, $trade_serial_no)->row();
        $check_9 = $this->db->query($sql_9, $trade_serial_no)->row();

        if ($check_0 || $check_1 || $check_2 || $check_3 || $check_4 || $check_5 || $check_6 || $check_7 || $check_8 || $check_9) {
            return true;
        }
        return false;
    }

    /**
     * 获取商家返款的活动信息（根据订单编号）
     *
     */
    public function get_bus_refund_info($order_sn_arr = array())
    {

        $sql = "select order_sn,trade_id,pay_sn,account_id,plat_id,shop_id,user_id from rqf_trade_order_union where order_sn in ?";
        $res = $this->db->query($sql, array($order_sn_arr))->result();
        if (!$res) {
            return array();
        }
        foreach ($res as $k => $v) {
            $suffix = order_suffix($v->order_sn);
            //商品信息
            $trade_item = $this->db->query("select goods_name from rqf_trade_item where trade_id = ?", array($v->trade_id))->row();
            $res[$k]->goods_name = $trade_item->goods_name;
            //买号信息
            $bind_info = $this->db->query("select account_name from rqf_bind_account where id = ?", array($v->account_id))->row();
            $res[$k]->account_name = $bind_info->account_name;
            //返款信息
            $withdrawal_info = $this->db->query("select alipay_account,tenpay_account,true_name from rqf_buy_account where user_id = ?", array($v->user_id))->row();
            if (in_array($v->plat_id, array(1, 2))) {
                //财付通
                $res[$k]->refund_type = '财付通';
                $res[$k]->refund_num = $withdrawal_info->tenpay_account;
            } else {
                //支付宝
                $res[$k]->refund_type = '支付宝';
                $res[$k]->refund_num = $withdrawal_info->alipay_account;
            }

            $res[$k]->refund_person = $withdrawal_info->true_name;
            //主活动信息
            $trade_info = $this->db->query("select price,buy_num from rqf_trade_info where id = ?", array($v->trade_id))->row();
            $res[$k]->refund_money = floatval($trade_info->price * $trade_info->buy_num);
            //店铺信息
            $shop_info = $this->db->query("select shop_name from rqf_bind_shop where id = ?", array($v->shop_id))->row();
            $res[$k]->shop_name = $shop_info->shop_name;
        }
        return $res;

    }
}
