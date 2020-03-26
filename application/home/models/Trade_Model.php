<?php

/**
 * 名称:活动模型
 * 担当:
 */
class Trade_Model extends CI_Model {

    /**
     * __construct
     */
    public function __construct() {

        parent::__construct();

        $this->load->model('Conf_Model', 'conf');

        $this->load->model('Bind_Model', 'bind');
    }

    /**
     * 根据id获取活动信息
     */
    public function get_trade_info($trade_id, $rewrite = false)
    {
        $user_id = intval($this->session->userdata('user_id'));
        $trade_info = $this->db->get_where("rqf_trade_info", ['id' => $trade_id, 'user_id' => $user_id])->row();

        return $trade_info;
    }

    /**
     * 获取运单编号
     */
    public function get_express_sn($order_sn, $pay_sn)
    {
        $params['name'] = '1186635123894779905';
        $params['secret'] = '9d1e72df358554d56df7332159de7094';
        $params['orderInfo'] = json_encode([$pay_sn]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://139.198.176.41:8112/mtop/api?api=mtop.order.open.api.getOrderInfoApiService&v=1.0");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        $res = curl_exec($curl);
        curl_close($curl);
        $this->load->helper('common_helper');
        $rtndata = json_decode($res, true);
        if ($rtndata['code'] == '1001') {
            $express_sn = $rtndata['result']['orderList'][0]['yundan_id'];
            // 更新表中运单号
            $suffix = order_suffix($order_sn);
            $this->write_db = $this->load->database('write', true);
            $this->write_db->update('rqf_trade_order_'. $suffix, ['express_sn' => $express_sn], ['order_sn' => $order_sn]);
            return $express_sn;
        }
        return 0;
    }

    /**
     * 根据活动id获取活动商品信息
     */
    public function get_trade_item($trade_id) {

        $trade_item = $this->db->get_where("rqf_trade_item", ['trade_id' => $trade_id])->row();
        if ($trade_item)
        {
            if ($trade_item->price == "0.00") {
                $trade_item->price = '';
            } else {
                $trade_item->price *= 1;
            }
        }
        return $trade_item;
    }

    /**
     * 获取关键词信息
     */
    public function get_trade_search($trade_id)
    {
        $pc_taobao = [];
        $pc_tmall = [];
        $app = [];
        $hc = [];
        $empty_arr = [
            'search_img' => '',
            'search_img2' => '',
            'kwd' => '',
            'classify1' => '',
            'classify2' => '',
            'classify3' => '',
            'classify4' => '',
            'class_kwd1' => '',
            'class_kwd2' => '',
            'class_kwd3' => '',
            'class_kwd4' => '',
            'low_price' => '',
            'high_price' => '',
            'area' => '全国',
            'discount' => '',
            'order_way' => '',
            'goods_cate' => '',
            'num' => 0,
            'surplus_num' => 0,
            'discount_arr' => []
        ];

        // 1:pc淘宝  2:pc天猫  3:手机淘宝  4:手机京东  5:淘宝会场
        $res = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();
        foreach ($res as $v) {
            $v->discount_arr = explode(',', $v->discount);
            if ($v->low_price == "0.00") {
                $v->low_price = '';
            } else {
                $v->low_price *= 1;
            }
            if ($v->high_price == "0.00") {
                $v->high_price = '';
            } else {
                $v->high_price *= 1;
            }
            if ($v->plat_id == '1') {
                $pc_taobao[] = $v;
            } elseif ($v->plat_id == '2') {
                $pc_tmall[] = $v;
            } elseif (in_array($v->plat_id, ['3', '4', '14'])) {
                $app[] = $v;
            } elseif ($v->plat_id == '5') {
                $hc[] = $v;
            }
        }

        if (empty($pc_taobao)) {
            $pc_taobao[] = (object)$empty_arr;
        }
        if (empty($pc_tmall)) {
            $pc_tmall[] = (object)$empty_arr;
        }
        if (empty($app)) {
            $app[] = (object)$empty_arr;
        }
        if (empty($hc)) {
            $hc[] = (object)$empty_arr;
        }

        return ['pc_taobao' => $pc_taobao, 'pc_tmall' => $pc_tmall, 'app' => $app, 'hc' => $hc];
    }

    public function get_trade_scan($trade_id)
    {
        $pc_taobao = [];
        $pc_tmall = [];
        $app = [];
        $hc = [];
        $empty_arr = [
            'goods_url' => '',
            'goods_name' => '',
            'shop_name' => '',
            'price' => '',
            'search_img' => '',
            'search_img2' => '',
            'kwd' => '',
            'low_price' => '',
            'high_price' => '',
            'area' => '全国',
            'discount' => '',
            'order_way' => '',
            'goods_cate' => '',
            'discount_arr' => []
        ];

        // 1:pc淘宝  2:pc天猫  3:手机淘宝  4:手机京东  5:淘宝会场
        $res = $this->db->get_where('rqf_trade_scan', ['trade_id' => $trade_id])->result();
        foreach ($res as $v) {
            $v->discount_arr = explode(',', $v->discount);
            if ($v->low_price == "0.00") {
                $v->low_price = '';
            } else {
                $v->low_price *= 1;
            }
            if ($v->high_price == "0.00") {
                $v->high_price = '';
            } else {
                $v->high_price *= 1;
            }
            if ($v->plat_id == '1') {
                $pc_taobao[] = $v;
            } elseif ($v->plat_id == '2') {
                $pc_tmall[] = $v;
            } elseif (in_array($v->plat_id, ['3', '4', '14'])) {
                $app[] = $v;
            } elseif ($v->plat_id == '5') {
                $hc[] = $v;
            }
        }


        if (empty($app)) {
            $app[] = (object)$empty_arr;
        }


        return ['pc_taobao' => $pc_taobao, 'pc_tmall' => $pc_tmall, 'app' => $app, 'hc' => $hc];
    }
    /**
     * 获取链接中的id
     */
    public function get_item_id($url, $plat_id = '1')
    {
        $result = '';
        if ($plat_id == '4') {
            // 京东链接
            $url_data = pathinfo($url);
            if (isset($url_data['basename']) && $url_data['basename']) {
                $result = substr($url_data['basename'], 0, strpos($url_data['basename'], '.html'));
            }
        } elseif ($plat_id == '14') {
            // 拼多多链接
            $url_info = parse_url($url);
            $query = $url_info['query'];
            $vals = explode('&', $query);
            $res = array();
            foreach ($vals as $item) {
                $temp = explode('=', $item);
                if (isset($temp[1])) {
                    $res[$temp[0]] = $temp[1];
                }
            }
            // https://mobile.yangkeduo.com/goods2.html?goods_id=2915674786
            if (array_key_exists('goods_id', $res)) {
                $result = $res['goods_id'];
            }
        } else {
            // 淘宝、天猫链接
            $url_info = parse_url($url);
            $query = $url_info['query'];
            $vals = explode('&', $query);
            $res = array();
            foreach ($vals as $item) {
                $temp = explode('=', $item);
                if (isset($temp[1])) {
                    $res[$temp[0]] = $temp[1];
                }
            }

            if (array_key_exists('id', $res)) {
                $result = $res['id'];
            }
        }

        return $result;
    }

    /**
     * 获取活动选择属性
     */
    public function trade_select($trade_info) {

        $trade_select = [];

        // 平台名称
        $plat_list = $this->conf->plat_list();

        $trade_select['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];

        // 店铺名称
        $bind_shop = $this->db->get_where('rqf_bind_shop', ['id'=>$trade_info->shop_id])->row();

        $trade_select['shop_name'] = $bind_shop->shop_name;
        // 默认圆通快递
        //$trade_select['shipping_type'] = $bind_shop->shipping_type;
        $trade_select['shipping_type'] = 'yto';
        // 活动类型
        $trade_type_list = $this->conf->trade_type_list($trade_info->plat_id);

        $trade_select['type_name'] = $trade_type_list[$trade_info->trade_type]['type_name'];

        // 总单数
        $trade_select['total_num'] = $trade_info->total_num;

        return $trade_select;
    }

    /**
     * 获取子活动相关数量
     */
    public function order_cnts($trade_info)
    {
        $ongoing = 0;           // 进行中
        $wait_send = 0;         // 待发货
        $wait_refund = 0;       // 待返款
        $finished = 0;          // 已完成
        $not_started = 0;       // 未接单
        $not_pay = 0;           // 未付款

        if ($trade_info->trade_type == '10') {
            
            $sql = "select traffic_status from rqf_traffic_record_union where trade_id = ? and traffic_status <= 7";
            $res = $this->db->query($sql, [intval($trade_info->id)])->result();
            foreach ($res as $v) {
                switch ($v->traffic_status) {
                    case '0':
                    case '1':
                    case '2':
                    case '3':
                        $ongoing++;
                        break;
                    case '7':
                        $finished++;
                        break;
                }
            }

            $sql = 'select apply_num, finish_num from `rqf_trade_info` where id = ? ';
            $res = $this->db->query($sql, [intval($trade_info->id)])->row();
            $finished = $res->finish_num;
            $not_pay = $res->apply_num;

            $not_started = $trade_info->total_num - $finished - $ongoing;
            $not_started = max($not_started, 0);
            return (object)[
                'ongoing' => $ongoing,
                'wait_send' => $wait_send,
                'wait_refund' => $wait_refund,
                'finished' => $finished,
                'not_started' => $not_started,
                'not_pay' => $not_pay
            ];

        } else {

            $sql = "select order_status from rqf_trade_order_union where trade_id = ? and order_status <= 7";
            $res = $this->db->query($sql, [intval($trade_info->id)])->result();
            foreach ($res as $v) {
                switch ($v->order_status) {
                    case '7':
                        $finished++;
                        break;
                    case '6':
                        $wait_refund++;
                        break;
                    case '4':
                        $wait_refund++;
                        break;
                    case '2':
                        $wait_send++;
                        break;
                    case '0':
                        $not_pay++;
                        break;
                }
                if ($v->order_status != '7') {
                    $ongoing++;
                }
            }

            $not_started = $trade_info->total_num - $finished - $ongoing;
            $not_started = max($not_started, 0);
            return (object)[
                'ongoing' => $ongoing,
                'wait_send' => $wait_send,
                'wait_refund' => $wait_refund,
                'finished' => $finished,
                'not_started' => $not_started,
                'not_pay' => $not_pay
            ];
        }

    }


    /**
     * 获取取消返还资金信息
     */
    public function cancel_refund($trade_info)
    {
        $order_cnts = $this->order_cnts($trade_info);
        $refund = ['surplus_num' => 0, 'deposit' => 0, 'point' => 0];
        if ($trade_info->trade_status == '1') {
            $refund = [
                'surplus_num' => $trade_info->total_num,
                'deposit' => $trade_info->trade_deposit,
                'point' => $trade_info->trade_point
            ];
        } elseif (in_array($trade_info->trade_status, ['2', '6'])) {
            if (in_array($trade_info->trade_type, ['111', '112', '113', '114', '211', '212', '213', '214'])) {
                $order_cnts->not_started = bcadd($trade_info->pc_num, $trade_info->phone_num, 2);
            }
            // 如果没有用户接单
            if ($trade_info->total_num == $order_cnts->not_started) {
                $trade_service = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
                $trade_point = bcadd($trade_info->order_fee_point, $trade_info->order_dis_point, 4);
                $service_point = 0;
                foreach ($trade_service as $v) {
                    if (in_array($v->service_name, ['first_check', 'set_time', 'set_over_time'])) {
                        continue;
                    }
                    $service_point = bcadd($service_point, $v->pay_point, 4);
                }

                $trade_point = bcadd($trade_point, $service_point, 4);
                $refund = [
                    'surplus_num' => $trade_info->total_num,
                    'deposit' => $trade_info->trade_deposit,
                    'point' => $trade_point
                ];
            } else {
                $deposit_unit_price = bcdiv($trade_info->trade_deposit, $trade_info->total_num, 4);
                $trade_service = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
                $trade_scans = $this->db->get_where('rqf_trade_scan', ['trade_id' => $trade_info->id])->result_array();
                $point_unit_price = 0;
                $point_unit_price = bcadd($point_unit_price, $trade_info->total_fee, 4);
                if ($trade_info->is_phone) {
                    $point_unit_price = bcadd($point_unit_price, ORDER_DIS_PRICE, 4);
                }
                $average_refund_list = ['plat_refund', 'bus_refund', 'set_traffic', 'add_reward', 'kwd_eval', 'setting_eval', 'add_reward_ext', 'set_shipping', 'shopping_end', 'area_limit', 'sex_limit', 'reputation_limit', 'taoqi_limit', 'setting_picture', 'traffic_list', 'newhand', 'super_scan', 'safe_control', 'extend_cycle'];
                foreach ($trade_service as $v) {
                    if (in_array($v->service_name, $average_refund_list)) {
                        if (strpos($v->service_name, 'super_scan') !== false) {
                            if (count($trade_scans) > 0) {
                                $single_price = bcmul($v->price, count($trade_scans), 2);
                                $point_unit_price = bcadd($point_unit_price, $single_price, 4);
                            }
                        } else {
                            $point_unit_price = bcadd($point_unit_price, $v->price, 4);
                        }
                    }
                }

                $refund = [
                    'surplus_num' => $order_cnts->not_started,
                    'deposit' => bcmul($deposit_unit_price, $order_cnts->not_started, 2),
                    'point' => bcmul($point_unit_price, $order_cnts->not_started, 2)
                ];
            }
        }

        return (object)$refund;
    }
}
