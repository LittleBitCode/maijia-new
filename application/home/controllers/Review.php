<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:商家审核控制器
 * 担当:
 */
class Review extends Ext_Controller {

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Trade_Model', 'trade');
        $this->load->model('Conf_Model', 'conf');
        $this->load->model('Bind_Model', 'bind');
    }

    /**
     * 待处理订单
     */
    public function order_list()
    {
        $data = $this->data;
        $t = intval($this->uri->segment(3));
        $t = in_array($t, [1, 2, 3, 4, 5, 6]) ? $t : 1;
        $user_id = intval($this->session->userdata('user_id'));
        // 平台
        $plat_list = $this->bind->bind_shop_cnt_list($user_id);
        foreach ($plat_list as $k => $v) {
            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }
        $data['plat_list'] = $plat_list;
        $start_time = trim($this->input->get('st'));
        $end_time = trim($this->input->get('et'));
        if (empty($start_time)) {
            $start_time = date('Y-m-d', strtotime('-2 day'));
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d', strtotime('+1 day'));
        }
        // 店铺
        $shop_list = $this->bind->all_shop_list($user_id);
        $data['shop_list'] = $shop_list;
        $sql = 'select order_status from rqf_trade_order_union where bus_user_id = ? and trade_type not in (115,215) and (order_status in (0, 1, 2, 3) or (order_status > 7 and first_start_time >= ? and first_start_time < ?))';
        $res = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time)])->result();
        $order_cnts = ['all' => 0, 'wait_pay' => 0, 'wait_print' => 0, 'wait_send' => 0, 'send_out' => 0, 'time_out' => 0];
        $order_cnts['all'] = count($res);
        foreach ($res as $v) {
            switch ($v->order_status) {
                case '0':
                    //已接手，待下单
                    $order_cnts['wait_pay']++;
                    break;
                case '1':
                    //已下单，待打印快递单
                    $order_cnts['wait_print']++;
                    break;
                case '2':
                    //已下单，待商家发货
                    $order_cnts['wait_send']++;
                    break;
                case '3':
                    //已发货
                    $order_cnts['send_out']++;
                    break;
                default:
                    //已取消
                    $order_cnts['time_out']++;
            }
        }
        $data['order_cnts'] = $order_cnts;
        $order_status_list = $this->conf->order_status_list();
        $plat_id = $this->input->get('plat_id');
        $plat_ids = array_keys($plat_list);
        if (!in_array($plat_id, $plat_ids)) {
            $plat_id = '';
        }
        $data['plat_id'] = $plat_id;
        $shop_id = $this->input->get('shop_id');
        $shop_ids = array_keys($shop_list);
        if (!in_array($shop_id, $shop_ids)) {
            $shop_id = '';
        }
        $data['shop_id'] = $shop_id;

        $key = trim($this->input->get('key'));
        if (!in_array($key, ['1', '2'])) {
            $key = '';
        }

        $data['key'] = $key;
        $val = $this->input->get('val');
        if (empty($val)) {
            $val = '';
        }
        $data['val'] = $val;

        // 分页信息
        $page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        if ($t == 1) {
            $where = 'and order_status in (0,1,2,3)';
        } elseif ($t == 2) {
            $where = 'and order_status = 0';
        } elseif ($t == 3) {
            $where = 'and order_status = 1';
        } elseif ($t == 4) {
            $where = 'and order_status = 2';
        } elseif ($t == 5) {
            $where = 'and order_status = 3';
        } elseif ($t == 6) {
            $where = 'and order_status > 7 and first_start_time >= '. strtotime($start_time). ' and first_start_time < '. strtotime($end_time);
        }
        $where .= ' and trade_type not in (115,215)';
        if ($plat_id) {
            $where .= sprintf(" and plat_id = %s", $plat_id);
        }

        if ($shop_id) {
            $where .= sprintf(" and shop_id = %s", $shop_id);
        }
        if ($val) {
            if ($key == '1') {
                $where .= sprintf(" and pay_sn = '%s'", $val);
            }

            if ($key == '2') {
                $bind_account_res = $this->db->get_where('rqf_bind_account', ['account_name' => $val])->result();
                $account_ids = "0";
                foreach ($bind_account_res as $v) {
                    $account_ids .= ",{$v->id}";
                }
                $where .= " and account_id in ({$account_ids})";
            }
        }

        $orderby = " ";
        // 支付时间处理
        if (in_array($t, [3, 4, 5])) {
            $orderby .= ' order by pay_time desc ';
        }
        // 接手时间处理
        if (in_array($t, [1, 2])) {
            $orderby .= ' order by first_start_time desc ';
        }

        $sql = "select * from rqf_trade_order_union where bus_user_id = {$user_id} {$where} {$orderby} limit {$offset}, {$per_page}";
        $res = $this->db->query($sql)->result();

        $trade_type_name_list = $this->conf->trade_type_name_list();

        foreach ($res as $k => $v) {
            $res[$k]->trade_item = $this->trade->get_trade_item($v->trade_id);
            $tmp_trade_search = $this->db->get_where('rqf_trade_search', ['id' => $v->search_id])->row();
            if ($tmp_trade_search) {
                $res[$k]->trade_search = $tmp_trade_search;
            } else {
                $res[$k]->trade_search = [];
            }
            $trade_service = $this->db->get_where('rqf_trade_service', ['trade_id' => $v->trade_id, 'service_name' => 'set_shipping'])->row();
            if ($trade_service) {
                $res[$k]->service_shipping = $trade_service->param;
            } else {
                $res[$k]->service_shipping = '';
            }
            $res[$k]->bind_account = $this->db->get_where('rqf_bind_account', ['id' => $v->account_id])->row();
            $res[$k]->status_text = $order_status_list[$v->order_status];
            $res[$k]->bind_shop = $this->db->get_where('rqf_bind_shop', ['id' => $v->shop_id])->row();
            $res[$k]->type_name = $trade_type_name_list[$v->trade_type];
            if ($v->express_type == 'yto' && empty($v->express_sn)) {
                $express_sn = $this->trade->get_express_sn($v->order_sn, $v->pay_sn);
                if (!empty($express_sn)) {
                    $res[$k]->express_sn = $express_sn;
                }
            }
        }

        $data['res'] = $res;
        $cnt_sql = "select count(1) cnt from rqf_trade_order_union where bus_user_id = {$user_id} {$where}";
        $cnt_row = $this->db->query($cnt_sql)->row();

        // 分页
        $this->load->library('pagination');
        $config['base_url'] = "/review/order_list/". $t;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';
        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['t'] = $t;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['shiping_type_list'] = $this->conf->get_shipping_type_list();
        $this->load->view('review/order_list', $data);
    }


    /**
     * 待处理订单
     */
    public function refund_order_list()
    {
        $data = $this->data;
        $t = intval($this->uri->segment(3));
        $t = in_array($t, [1, 2, 3, 4, 6]) ? $t : 1;
        $user_id = intval($this->session->userdata('user_id'));
        // 平台
        $plat_list = $this->bind->bind_shop_cnt_list($user_id);
        foreach ($plat_list as $k => $v) {
            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }
        $data['plat_list'] = $plat_list;
        $start_time = trim($this->input->get('st'));
        $end_time = trim($this->input->get('et'));
        if (empty($start_time)) {
            $start_time = date('Y-m-d', strtotime('-2 day'));
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d', strtotime('+1 day'));
        }
        // 店铺
        $shop_list = $this->bind->all_shop_list($user_id);
        $data['shop_list'] = $shop_list;
        $sql = 'select order_status, count(*) cnts from rqf_trade_order_union where bus_user_id = ? and trade_type in (115,215) and (order_status <= 7 or (order_status > 7 and first_start_time >= ? and first_start_time < ?)) group by order_status';
        $res = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time)])->result();
        $order_cnts = ['all' => 0, 'wait_pay' => 0, 'wait_print' => 0, 'wait_send' => 0, 'time_out' => 0];
        foreach ($res as $item) {
            switch ($item->order_status) {
                case '0':
                    $order_cnts['wait_pay'] += intval($item->cnts);
                    break;
                case '1':
                    $order_cnts['wait_print'] += intval($item->cnts);
                    break;
                case '4':
                    $order_cnts['wait_send'] += intval($item->cnts);
                    break;
                case '97':
                case '98':
                case '99':
                    $order_cnts['time_out'] += intval($item->cnts);
                    break;
            }
            $order_cnts['all'] += intval($item->cnts);
        }
        $data['order_cnts'] = $order_cnts;
        $order_status_list = ['0' => '已接手，待下单', '1' => '已下单，待审核', '3' => '已审核、待淘宝申请退款', '4' => '已申请退款、待淘宝确认', '5' => '淘宝已确认退款', '6' => '驳回', '7' => '已完成', '97' => '超时取消', '98' => '已取消', '99' => '已放弃'];
        $plat_id = $this->input->get('plat_id');
        $plat_ids = array_keys($plat_list);
        if (!in_array($plat_id, $plat_ids)) {
            $plat_id = '';
        }
        $data['plat_id'] = $plat_id;
        $shop_id = $this->input->get('shop_id');
        $shop_ids = array_keys($shop_list);
        if (!in_array($shop_id, $shop_ids)) {
            $shop_id = '';
        }
        $data['shop_id'] = $shop_id;

        $key = trim($this->input->get('key'));
        if (!in_array($key, ['1', '2'])) {
            $key = '';
        }

        $data['key'] = $key;
        $val = $this->input->get('val');
        if (empty($val)) {
            $val = '';
        }
        $data['val'] = $val;

        // 分页信息
        $page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        if ($t == 1) {
            $where = 'and order_status <= 7';
        } elseif ($t == 2) {
            $where = 'and order_status = 0';
        } elseif ($t == 3) {
            $where = 'and order_status = 1';
        } elseif ($t == 4) {
            $where = 'and order_status = 4';
        } elseif ($t == 6) {
            $where = 'and order_status > 7 and first_start_time >= '. strtotime($start_time). ' and first_start_time < '. strtotime($end_time);
        }
        $where .= ' and trade_type in (115,215)';
        if ($plat_id) {
            $where .= sprintf(" and plat_id = %s", $plat_id);
        }

        if ($shop_id) {
            $where .= sprintf(" and shop_id = %s", $shop_id);
        }
        if ($val) {
            if ($key == '1') {
                $where .= sprintf(" and pay_sn = '%s'", $val);
            }

            if ($key == '2') {
                $bind_account_res = $this->db->get_where('rqf_bind_account', ['account_name' => $val])->result();
                $account_ids = "0";
                foreach ($bind_account_res as $v) {
                    $account_ids .= ",{$v->id}";
                }
                $where .= " and account_id in ({$account_ids})";
            }
        }

        $orderby = " ";
        // 支付时间处理
        if (in_array($t, [3, 4, 5])) {
            $orderby .= ' order by pay_time desc ';
        }
        // 接手时间处理
        if (in_array($t, [1, 2])) {
            $orderby .= ' order by first_start_time desc ';
        }

        $sql = "select * from rqf_trade_order_union where bus_user_id = {$user_id} {$where} {$orderby} limit {$offset}, {$per_page}";
        $res = $this->db->query($sql)->result();

        $trade_type_name_list = $this->conf->trade_type_name_list();
        foreach ($res as $k => $v) {
            $res[$k]->trade_item = $this->trade->get_trade_item($v->trade_id);
            $tmp_trade_search = $this->db->get_where('rqf_trade_search', ['id' => $v->search_id])->row();
            if ($tmp_trade_search) {
                $res[$k]->trade_search = $tmp_trade_search;
            } else {
                $res[$k]->trade_search = [];
            }
            $trade_service = $this->db->get_where('rqf_trade_service', ['trade_id' => $v->trade_id, 'service_name' => 'set_shipping'])->row();
            if ($trade_service) {
                $res[$k]->service_shipping = $trade_service->param;
            } else {
                $res[$k]->service_shipping = '';
            }
            $res[$k]->bind_account = $this->db->get_where('rqf_bind_account', ['id' => $v->account_id])->row();
            $res[$k]->status_text = $order_status_list[$v->order_status];
            $res[$k]->bind_shop = $this->db->get_where('rqf_bind_shop', ['id' => $v->shop_id])->row();
            $res[$k]->type_name = $trade_type_name_list[$v->trade_type];
        }

        $data['res'] = $res;
        $cnt_sql = "select count(1) cnt from rqf_trade_order_union where bus_user_id = {$user_id} {$where}";
        $cnt_sql = "select count(1) cnt from rqf_trade_order_union where bus_user_id > 0 {$where}";
        $cnt_row = $this->db->query($cnt_sql)->row();

        // 分页
        $this->load->library('pagination');
        $config['base_url'] = "/review/refund_order_list/". $t;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';
        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['t'] = $t;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['shiping_type_list'] = $this->conf->get_shipping_type_list();
        $this->load->view('review/refund_order_list', $data);
    }

    /**
     * 待处理订单
     */
    public function traffic_list()
    {
        $data = $this->data;
        $t = intval($this->uri->segment(3));
        $t = in_array($t, [1, 2, 3, 4, 6]) ? $t : 1;
        $user_id = intval($this->session->userdata('user_id'));
        // 平台
        $plat_list = $this->bind->bind_shop_cnt_list($user_id);
        foreach ($plat_list as $k => $v) {
            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }
        $data['plat_list'] = $plat_list;
        $start_time = trim($this->input->get('st'));
        $end_time = trim($this->input->get('et'));
        //是否是查询操作
        $is_query=1;
        if (empty($start_time)) {
            $start_time = date('Y-m-d', strtotime('-2 day'));
            $is_query=0;
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d', strtotime('+1 day'));
            $is_query=0;
        }

        // 店铺
        $shop_list = $this->bind->all_shop_list($user_id);
        $data['shop_list'] = $shop_list;

        $sql = 'select traffic_status from rqf_traffic_record_union where bus_user_id = ? ';
        $res = $this->db->query($sql,$user_id)->result();
        $order_cnts = ['all' => 0, 'status_0' => 0, 'status_1' => 0, 'status_2' => 0, 'status_3' => 0, 'status_9' => 0];
        foreach ($res as $v) {
            switch ($v->traffic_status) {
                case '0':
                    $order_cnts['status_0']++;
                    break;
                case '1':
                    $order_cnts['status_1']++;
                    break;
                case '2':
                    $order_cnts['status_2']++;
                    break;
                case '3':
                    $order_cnts['status_3']++;
                    break;
//                case '7':
//                    break;
                default:
                    $order_cnts['status_9']++;
            }
        }

        $order_cnts['all'] = count($res);

        $data['order_cnts'] = $order_cnts;
        $this->load->model('Traffic_Model', 'traffic');
        $order_status_list = $this->traffic->get_traffic_status();
        $plat_id = $this->input->get('plat_id');
        $plat_ids = array_keys($plat_list);
        if (!in_array($plat_id, $plat_ids)) {
            $plat_id = '';
        }
        $data['plat_id'] = $plat_id;
        $shop_id = $this->input->get('shop_id');
        $shop_ids = array_keys($shop_list);
        if (!in_array($shop_id, $shop_ids)) {
            $shop_id = '';
        }
        $data['shop_id'] = $shop_id;

        $key = trim($this->input->get('key'));
        if (!in_array($key, ['1', '2', '3'])) {
            $key = '';
        }

        $data['key'] = $key;
        $val = $this->input->get('val');
        if (empty($val)) {
            $val = '';
        }
        $data['val'] = $val;

        // 分页信息
        $page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
        $per_page = 5;
        $offset = ($page - 1) * $per_page;
        if ($t == 1) {
            $where = 'and traffic_status in (0, 1, 2, 3, 7 ,97, 98, 99) ';
        } elseif ($t == 2) {
            $where = 'and traffic_status = 1 ';
        } elseif ($t == 3) {
            $where = 'and traffic_status = 2 ';
        } elseif ($t == 4) {
            $where = 'and traffic_status = 3 ';
        }elseif ($t==6)
        {
            $where = 'and traffic_status in (97,98,99) ';
        }

        if ($is_query)
        {
            $where .= ' and add_time >= '. strtotime($start_time). ' and add_time < '. strtotime($end_time);
        }


        if ($plat_id) {
            $where .= sprintf(" and plat_id = %s", $plat_id);
        }

        if ($shop_id) {
            $where .= sprintf(" and shop_id = %s", $shop_id);
        }
        if ($val) {
            //订单号查询
            if ($key == '1') {
                $order_id = '0';
                $row = $this->db->get_where('rqf_trade_order_union', ['pay_sn' => $val])->row();
                if ($row)
                {
                    $order_id = $row->id;
                }
                $where .= sprintf(" and order_id = '%s'  ", $order_id);
            }

            if ($key == '2') {
                $bind_account_res = $this->db->get_where('rqf_bind_account', ['account_name' => $val])->result();
                $account_ids = "0";
                foreach ($bind_account_res as $v) {
                    $account_ids .= ",{$v->id}";
                }
                $where .= " and account_id in ({$account_ids})";
            }

            if ($key == '3') {
                $where .= sprintf(" and trade_sn = '%s'", $val);
            }
        }

        $orderby = "  ";
        // 接手时间处理
        if (in_array($t, [1, 2])) {
            $orderby .= ' order by add_time desc ';
        }
        // 支付时间处理
        else  {
            $orderby .= ' order by confirm_time desc ';
        }

        $sql = "select * from rqf_traffic_record_union where bus_user_id = {$user_id} {$where} {$orderby} limit {$offset}, {$per_page}";
        $res = $this->db->query($sql)->result();
        foreach ($res as $k => $v) {
            $res[$k]->trade_item = $this->trade->get_trade_item($v->trade_id);
            $tmp_trade_search = $this->db->get_where('rqf_trade_search', ['id' => $v->search_id])->row();
            if ($tmp_trade_search) {
                $res[$k]->trade_search = $tmp_trade_search;
            } else {
                $res[$k]->trade_search = [];
            }

            $res[$k]->bind_account = $this->db->get_where('rqf_bind_account', ['id' => $v->account_id])->row();
            $res[$k]->status_text = $order_status_list[$v->traffic_status];
            $res[$k]->bind_shop = $this->db->get_where('rqf_bind_shop', ['id' => $v->shop_id])->row();
            // 订单截图
            if ($v->traffic_status > 0 && $v->traffic_status < 99) {
                $suffix = suffix($v->user_id);
                $query_item = $this->db->get_where('rqf_traffic_info_'. $suffix, ['trade_id' => intval($v->trade_id), 'order_id' => intval($v->order_id)])->row();
                $res[$k]->img_list = [
                    'key_words_img' => $query_item->key_words_img, 'goods_top_img' => $query_item->goods_top_img,
                    'goods_bottom_img' => $query_item->goods_bottom_img, 'user_info_img' => $query_item->user_info_img,
                    'collect_goods_img' => $query_item->collect_goods_img, 'collect_shop_img' => $query_item->collect_shop_img,
                    'shop_cart_img' => $query_item->shop_cart_img, 'goods_eval_img' => $query_item->goods_eval_img, 'coupon_img' => $query_item->coupon_img,
                    'compare_goods_img1' => $query_item->compare_goods_img1,'compare_goods_img2' => $query_item->compare_goods_img2,'compare_goods_img3' => $query_item->compare_goods_img3,
                    'like_goods_img' => $query_item->like_goods_img
                ];
            } else {
                $res[$k]->img_list = [];
            }
            // 审核不通过原因
            if ($v->traffic_status == '3') {
                $sql = "select comments from rqf_traffic_action_{$suffix} where order_id = ? and order_status = 3 order by add_time desc ";
                $query_item = $this->db->query($sql, [intval($v->order_id)])->row();
                $res[$k]->unchecked_reason = implode("<br/>", json_decode($query_item->comments, true));
            } else {
                $res[$k]->unchecked_reason = '';
            }
        }

        $data['res'] = $res;
        $cnt_sql = "select count(1) cnt from rqf_traffic_record_union where bus_user_id = {$user_id} {$where}";
        $cnt_row = $this->db->query($cnt_sql)->row();

        $base_url_time='';
        if ($is_query)
        {
            $base_url_time="&st={$start_time}&et={$end_time}";
        }

        // 分页
        $this->load->library('pagination');
        $config['base_url'] = "/review/traffic_list/{$t}?key={$key}&val={$val}{$base_url_time}";
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';
        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['t'] = $t;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['shiping_type_list'] = $this->conf->get_shipping_type_list();
        $this->load->view('review/traffic_list', $data);
    }

    /**
     * 发货
     */
    public function send_out()
    {
        $order_sn = trim($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $suffix = order_suffix($order_sn);
        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn' => $order_sn])->row();
        $referer = $this->input->server('HTTP_REFERER');
        if (empty($referer)) {
            $referer = "center";
        }

        if (empty($trade_order)) {
            redirect($referer);
            return;
        }
        if ($trade_order->bus_user_id != $user_id) {
            redirect($referer);
            return;
        }
        if ($trade_order->order_status != '2') {
            redirect($referer);
        }

        $t = time();
        $this->write_db = $this->load->database('write', true);
        $sql = "update rqf_trade_order_{$suffix} set order_status = 3, send_time = {$t}
                where bus_user_id = {$user_id} and order_status = 2 and order_sn = '{$order_sn}'";
        $this->write_db->query($sql);
        if ($this->write_db->affected_rows()) {
            $order_action_ins = [
                'order_id' => $trade_order->id,
                'order_sn' => $trade_order->order_sn,
                'order_status' => 3,
                'order_note' => '商家操作发货',
                'add_time' => time(),
                'created_user' => $this->session->userdata('nickname'),
                'comments' => ''
            ];
            $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
        }
        $this->write_db->close();

        redirect($referer);
    }

    /**
     * 批量操作发货
     */
    public function batch_send_out() {

        $this->write_db = $this->load->database('write', true);

        $user_id = $this->session->userdata('user_id');

        $trade_orders = $this->write_db->get_where("rqf_trade_order_union", ['order_status'=>2,'bus_user_id'=>$user_id])->result();

        if(!$trade_orders) {
            return json_encode(array('error'=>1,'msg'=>'无可操作发货的订单'));
        }

        foreach ($trade_orders as $k => $v) {

            $suffix = order_suffix($v->order_sn);

            $t = time();
            $sql = "update rqf_trade_order_{$suffix} set order_status = 3, send_time = {$t}
                    where bus_user_id = {$user_id} and order_status = 2 and order_sn = '{$v->order_sn}'";
            $this->write_db->query($sql);

            if ($this->write_db->affected_rows()) {

                $order_action_ins = [
                    'order_id'=>$v->id,
                    'order_sn'=>$v->order_sn,
                    'order_status'=>3,
                    'order_note'=>'商家操作发货',
                    'add_time'=>time(),
                    'created_user'=>$this->session->userdata('nickname'),
                    'comments'=>''
                ];

                $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
            }

        }

        $this->write_db->close(); 

        echo json_encode(array('error'=>0,'msg'=>'操作成功'));
    }

    /**
     * 待返款订单
     */
    public function refund_list() {

        $data = $this->data;

        $t = intval($this->uri->segment(3));

        if (!in_array($t, [1,3,4])) {
            $t = 1;
        }

        $data['t'] = $t;

        $user_id = $this->session->userdata('user_id');
       //user_id //商家用户ID
        $sql = "select order_status,plat_refund from rqf_trade_order_union where bus_user_id = {$user_id} and order_status in (4,5,6)";

        $res = $this->db->query($sql)->result();

        $order_cnts = [
            'plat_refund'=>0,
            'bus_refund'=>0,
            'reject'=>0,
            'refunded'=>0
        ];

        foreach ($res as $v) {

            switch ($v->order_status) {
                case '4':
                    if ($v->plat_refund) {
                        $order_cnts['plat_refund']++;
                    } else {
                        $order_cnts['bus_refund']++;
                    }
                    break;
                case '5':
                    $order_cnts['refunded']++;
                    break;
                case '6':
                    $order_cnts['reject']++;
                    break;
            }
        }

        $data['order_cnts'] = (object)$order_cnts;
        //商家绑定店铺列表
        $plat_list = $this->bind->bind_shop_cnt_list($user_id);

        foreach ($plat_list as $k=>$v) {

            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }

        $data['plat_list'] = $plat_list;

        $shop_list = $this->bind->all_shop_list($user_id);

        $data['shop_list'] = $shop_list;

        // 平台id
        $plat_id = $this->input->get('plat_id');

        $plat_ids = array_keys($plat_list);

        if (!in_array($plat_id, $plat_ids)) {
            $plat_id = '';
        }

        $data['plat_id'] = $plat_id;

        // 店铺id
        $shop_id = $this->input->get('shop_id');

        $shop_ids = array_keys($shop_list);

        if (!in_array($shop_id, $shop_ids)) {
            $shop_id = '';
        }

        $data['shop_id'] = $shop_id;

        // key
        $key = trim($this->input->get('key'));

        if (!in_array($key, ['1','2'])) {
            $key = '';
        }

        $data['key'] = $key;

        // value
        $val = $this->input->get('val');

        if (empty($val)) {
            $val = '';
        }

        $data['val'] = $val;

        // 分页信息
        $page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;

        $per_page = 10;

        $offset = ($page - 1) * $per_page;

        if ($t == 1) 
            $where = 'and order_status = 4 and plat_refund = 1';
        elseif ($t == 2)
            $where = 'and order_status = 4 and plat_refund = 0';
        elseif ($t == 3)
            $where = 'and order_status = 6';
        elseif ($t == 4)
            $where = 'and order_status = 5';
        if ($plat_id) {
            $where .= sprintf(" and plat_id = %s", $plat_id);
        }
       //商家店铺id 存在
        if ($shop_id) {
            $where .= sprintf(" and shop_id = %s", $shop_id);
        }

        if ($val) {
            if ($key == '1') {
                $where .= sprintf(" and pay_sn = '%s'", $val);
            }

            if ($key == '2') {
                $bind_account_res = $this->db->get_where('rqf_bind_account', ['account_name'=>$val])->result();
                $account_ids = "0";
                foreach ($bind_account_res as $v) {
                    $account_ids .= ",{$v->id}";
                }

                $where .= " and account_id in ({$account_ids})";
            }
        }

        if ($this->input->get('export') !== null) {

            $sql = "select * from rqf_trade_order_union where bus_user_id = {$user_id} {$where}";
            $res = $this->db->query($sql)->result();

            $str_data = "活动编号,店铺名称,商品名称,买号,订单号,支付金额,订单状态\r\n";

            foreach ($res as $v) {
                $bind_shop = $this->db->get_where('rqf_bind_shop', ['id'=>$v->shop_id])->row();
                $trade_item = $this->trade->get_trade_item($v->trade_id);
                $buy_account = $this->db->get_where('rqf_buy_account', ['user_id'=>$v->user_id])->row();

                $str_data .= $v->order_sn . "\t,";
                $str_data .= $bind_shop->shop_name . "\t,";
                $str_data .= $trade_item->goods_name . "\t,";
                $str_data .= $buy_account->account_name . "\t,";
                $str_data .= $v->pay_sn . "\t,";
                $str_data .= $v->order_money . "\t,";
                $str_data .= ['4'=>'待返款','5'=>'已返款','6'=>'买手驳回'][$v->order_status] . "\t,\r\n";
                print_r($str_data);
            }

            if ($t == 1) {
                $export_name = "待返款订单";
            } elseif ($t == 3) {
                $export_name = "买手驳回订单";
            } else {
                $export_name = "已返款订单";
            }

            $this->load->helper('download');
            force_download("{$export_name}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
            exit();
        }

        $sql = "select * from rqf_trade_order_union where bus_user_id = {$user_id} {$where} limit {$offset},{$per_page}";
        $res = $this->db->query($sql)->result();
        $trade_type_name_list = $this->conf->trade_type_name_list();

        foreach ($res as $k=>$v) {

            $res[$k]->trade_item = $this->trade->get_trade_item($v->trade_id);

            $tmp_suffix = suffix($v->user_id);

            $res[$k]->order_info = $this->db->get_where("rqf_order_info_{$tmp_suffix}", ['order_id'=>$v->id])->row();

            $res[$k]->bind_account = $this->db->get_where('rqf_bind_account', ['id'=>$v->account_id])->row();

            $res[$k]->bind_shop = $this->db->get_where('rqf_bind_shop', ['id'=>$v->shop_id])->row();

            $res[$k]->buy_account = $this->db->get_where('rqf_buy_account', ['user_id'=>$v->user_id])->row();

            $res[$k]->type_name = $trade_type_name_list[$v->trade_type];

            $res[$k]->trade_type = $v->trade_type;

            //如果为待返款订单查询订单评论类型
            $res[$k]->eval_details=new stdClass();
            if ($t==1){
                $sql="SELECT eval_type from rqf_trade_info where trade_sn='{$v->trade_sn}' and user_id='{$v->bus_user_id}'";
                $type= $this->db->query($sql)->row();
                if ($type!=null) {
                  $eval_type= $type->eval_type;
                  $eval_type_list= $this->conf->get_eval_type_list();
                  $eval_type_name=  $eval_type_list[$eval_type];

                  $res[$k]->eval_details->eval_type_name=$eval_type_name;
                  $res[$k]->eval_details->eval_type=$eval_type;
                }
            }
        }
        $data['res'] = $res;
        $cnt_sql = "select count(1) cnt from rqf_trade_order_union where bus_user_id = {$user_id} {$where}";
        $cnt_row = $this->db->query($cnt_sql)->row();

        // 分页
        $this->load->library('pagination');

        $config['base_url'] = "/review/refund_list/{$t}/?plat_id=".$plat_id."&shop_id=".$shop_id;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';

        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();


        $this->load->view('review/refund_list', $data);
    }

    /**
     * 查看评论详情
     */
    public function get_eval_details(){
        $eval_type = trim($this->input->post('eval_type'));
        $order_sn = trim($this->input->post('order_no'));
        $trade_id = trim($this->input->post('tid'));
        $res=new  stdClass();

        if (!$eval_type && !$order_sn && !$trade_id){
            $res->code="1";
            $res->msg="参数有误";
            exit(json_encode($res));
        }
        $setting_eval=new stdClass();
        $setting_img=new stdClass();
        $eval_type_list= $this->conf->get_eval_type_list();
        $eval_type_name=  $eval_type_list[$eval_type];
        $param="";
        if (in_array($eval_type, [0, 2, 3])) {
            if ($eval_type==2){

                $sql="SELECT  param  from rqf_trade_service where trade_id= ? AND service_name='kwd_eval' ";
                $trade_service= $this->db->query($sql,[$trade_id])->row();
                if ($trade_service)
                {
                    $param=$trade_service->param;
                }
                if ($param)
                {
                    $setting_eval->content=json_decode($param);
                }
            }
            elseif ($eval_type==3){
                $setting_eval = $this->db->get_where('rqf_setting_eval', ['order_sn' => $order_sn])->row();
            }
            if (!$setting_eval)
            {
                $res->code="0";
                $res->msg="没有获取到数据";
                exit(json_encode($res));
            }
            $res->setting_eval=$setting_eval;
        }
        elseif (in_array($eval_type, [4, 5])){
            $setting_img = $this->db->get_where('rqf_setting_img', ['order_sn' => $order_sn])->row();

            if (!$setting_img)
            {
                $res->code="1";
                $res->msg="没有获取到数据";
                exit(json_encode($res));
            }
            $res->setting_img=$setting_img;
        }
        $res->eval_type_name=$eval_type_name;
        $res->eval_type=$eval_type;
        $res->code="0";
        $res->msg="ok";
        echo json_encode($res);
    }

    /**
     * 驳回评价截图
     */
    public function reject_evalImg(){
        $order_sn = trim($this->input->post('order_no'));
        $radio_val = trim($this->input->post('radio_val'));
        $reason = trim($this->input->post('reason'));
        $suffix = order_suffix($order_sn);
        if (empty($order_sn)||empty($radio_val)){
            exit(json_encode(['code' => 1, 'msg' => '参数不正确']));
        }
        if ($radio_val=="3"){
            if (empty($reason)){
                exit(json_encode(['code' => 1, 'msg' => '请填写驳回原因']));
            }
        }

        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$order_sn])->row();
        if (empty($trade_order)) {
            exit(json_encode(['code' => 1, 'msg' => '没有查询到订单信息']));
        }
        // 操作日志
        $order_action_ins = [
            'order_id'=>$trade_order->id,
            'order_sn'=>$trade_order->order_sn,
            'order_status'=>4,
            'order_note'=>'商家驳回评价截图',
            'add_time'=>time(),
            'created_user'=>$this->session->userdata('nickname'),
            'comments'=>$reason
        ];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update("rqf_trade_order_{$suffix}",["order_status"=>3],["trade_id"=>$trade_order->trade_id]);
        $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 平台返款
     */
    public function plat_refund() {
        $order_sn = trim($this->input->post('order_no'));
        if (!$order_sn) {
            exit(json_encode(['code' => 1, 'msg' => '参数有误']));
        }
        $user_id = $this->session->userdata('user_id');

        $suffix = order_suffix($order_sn);

        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$order_sn])->row();

        $referer = $this->input->server('HTTP_REFERER');

        if (empty($referer)) {
            $referer = "center";
        }

        if (empty($trade_order)) {
            exit(json_encode(['code' => 1, 'msg' => '没有查询到订单信息']));
        }

        if ($trade_order->bus_user_id != $user_id) {
            exit(json_encode(['code' => 1, 'msg' => 'fails']));
        }

        if ($trade_order->order_status != '4') {
            exit(json_encode(['code' => 1, 'msg' => '不正确的订单状态']));
        }

        $this->write_db = $this->load->database('write', true);

        $t = time();

        $sql = "update rqf_trade_order_{$suffix} set order_status = 5, confirm_time = {$t}
                where bus_user_id = {$user_id} and order_status = 4 and order_sn = '{$order_sn}'";

        $this->write_db->query($sql);

        if ($this->write_db->affected_rows()) {

            $order_action_ins = [
                'order_id'=>$trade_order->id,
                'order_sn'=>$trade_order->order_sn,
                'order_status'=>5,
                'order_note'=>'商家平台返款',
                'add_time'=>time(),
                'created_user'=>$this->session->userdata('nickname'),
                'comments'=>''
            ];

            $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
        }else {
            exit(json_encode(['code' => 1, 'msg' => '操作失败']));
        }

        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 买手驳回再返款
     */
    public function reject_refund() {

        $order_sn = trim($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $suffix = order_suffix($order_sn);

        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$order_sn])->row();

        $referer = $this->input->server('HTTP_REFERER');

        if (empty($referer)) {
            $referer = "center";
        }

        if (empty($trade_order)) {
            redirect($referer);
            return;
        }

        if ($trade_order->bus_user_id != $user_id) {
            redirect($referer);
            return;
        }

        if ($trade_order->order_status != '6') {
            redirect($referer);
        }

        $this->write_db = $this->load->database('write', true);

        $t = time();

        $sql = "update rqf_trade_order_{$suffix} set order_status = 5, confirm_time = {$t}
                where bus_user_id = {$user_id} and order_status = 6 and order_sn = '{$order_sn}'";

        $this->write_db->query($sql);

        if ($this->write_db->affected_rows()) {

            $order_action_ins = [
                'order_id'=>$trade_order->id,
                'order_sn'=>$trade_order->order_sn,
                'order_status'=>5,
                'order_note'=>'商家平台返款',
                'add_time'=>time(),
                'created_user'=>$this->session->userdata('nickname'),
                'comments'=>''
            ];

            $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
        }

        $this->write_db->close();

        redirect($referer);
    }

    /**
     * 商家返款
     */
    public function bus_refund() {

        $order_sn = trim($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $suffix = order_suffix($order_sn);

        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$order_sn])->row();

        $referer = $this->input->server('HTTP_REFERER');

        if (empty($referer)) {
            $referer = "center";
        }

        if (empty($trade_order)) {
            redirect($referer);
            return;
        }

        if ($trade_order->bus_user_id != $user_id) {
            redirect($referer);
            return;
        }

        if ($trade_order->order_status != '4') {
            redirect($referer);
        }

        $trade_serial_no = trim($this->uri->segment(4));

        //验证交易编号的重复情况

        $this->load->model('Review_Model','review');
        $check_info = $this->review->check_serial_no($trade_serial_no);

        if($check_info){
            redirect($referer);
        }

        $this->write_db = $this->load->database('write', true);

        $t = time();

        $sql = "update rqf_trade_order_{$suffix} set order_status = 5, confirm_time = {$t}
                where bus_user_id = {$user_id} and order_status = 4 and order_sn = '{$order_sn}'";

        $this->write_db->query($sql);

        if ($this->write_db->affected_rows()) {

            $order_info_upd = ['trade_serial_no'=>$trade_serial_no];

            $order_info_key = ['order_sn'=>$order_sn];

            $this->write_db->update("rqf_order_info_{$suffix}", $order_info_upd, $order_info_key);

            $order_action_ins = [
                'order_id'=>$trade_order->id,
                'order_sn'=>$trade_order->order_sn,
                'order_status'=>5,
                'order_note'=>'商家确认返款',
                'add_time'=>time(),
                'created_user'=>$this->session->userdata('nickname'),
                'comments'=>''
            ];

            $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
        }

        $this->write_db->close();

        redirect($referer);
    }

    /**
     * 修改订单金额
     */
    public function update_order_money() {

        $order_sn = trim($this->input->post('order_sn'));

        $user_id = $this->session->userdata('user_id');

        $suffix = order_suffix($order_sn);

        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$order_sn])->row();

        $referer = $this->input->server('HTTP_REFERER');

        if (empty($referer)) {
            $referer = "center";
        }

        if (empty($trade_order)) {
            redirect($referer);
            return;
        }

        if ($trade_order->bus_user_id != $user_id) {
            redirect($referer);
            return;
        }

        if (!in_array($trade_order->order_status, ['4','6','1','2'])) {
            redirect($referer);
        }

        $order_money = floatval($this->input->post('order_money'));
        $money_upmessage = $this->input->post('money_upmessage');

        $this->write_db = $this->load->database('write', true);

        $sql = "update rqf_trade_order_{$suffix} set order_money = {$order_money}, bus_modified = 1
                where bus_user_id = {$user_id} and order_status in (4,6,1,2) and order_sn = '{$order_sn}'";

        $this->write_db->query($sql);

        if ($this->write_db->affected_rows()) {

            $order_action_ins = [
                'order_id'=>$trade_order->id,
                'order_sn'=>$trade_order->order_sn,
                'order_status'=>$trade_order->order_status,
                'order_note'=>'商家修改订单金额',
                'add_time'=>time(),
                'created_user'=>$this->session->userdata('nickname'),
                'comments'=>'订单金额:'.$trade_order->order_money.'->'.$order_money." 原因：" . $money_upmessage
            ];

            $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);

        }

        $this->write_db->close();

        redirect($referer);
    }

    /**
     * 批量平台返款
     */
    public function batch_plat_refund() {

        $order_sns = $this->input->post('order_sns');

        $user_id = $this->session->userdata('user_id');

        $this->write_db = $this->load->database('write', true);

        $t = time();

        foreach ($order_sns as $v) {

            $suffix = order_suffix($v);

            $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$v])->row();

            if (empty($trade_order)) {
                continue;
            }

            if ($trade_order->bus_user_id != $user_id) {
                continue;
            }

            if ($trade_order->order_status != '4') {
                continue;
            }

            $sql = "update rqf_trade_order_{$suffix} set order_status = 5, confirm_time = {$t}
                    where bus_user_id = {$user_id} and order_status = 4 and order_sn = '{$v}'";

            $this->write_db->query($sql);

            if ($this->write_db->affected_rows()) {

                $order_action_ins = [
                    'order_id'=>$trade_order->id,
                    'order_sn'=>$trade_order->order_sn,
                    'order_status'=>5,
                    'order_note'=>'商家平台返款',
                    'add_time'=>time(),
                    'created_user'=>$this->session->userdata('nickname'),
                    'comments'=>''
                ];

                $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
            }
        }

        $this->write_db->close();

        $referer = $this->input->server('HTTP_REFERER');

        redirect($referer);
    }

    /**
     * 批量商家返款
     */
    public function batch_bus_refund() {
        $order_params = $this->input->post('order_params');

        $user_id = $this->session->userdata('user_id');

        $this->write_db = $this->load->database('write', true);

        $t = time();

        foreach ($order_params as $v) {

            $params = explode(',', $v);

            $suffix = order_suffix($params[0]);

            $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$params[0]])->row();

            if (empty($trade_order)) {
                continue;
            }

            if ($trade_order->bus_user_id != $user_id) {
                continue;
            }

            if ($trade_order->order_status != '4') {
                continue;
            }

            $sql = "update rqf_trade_order_{$suffix} set order_status = 5, confirm_time = {$t}
                    where bus_user_id = {$user_id} and order_status = 4 and order_sn = '{$params[0]}'";

            $this->write_db->query($sql);

            if ($this->write_db->affected_rows()) {

                $order_info_upd = ['trade_serial_no'=>$params[1]];

                $order_info_key = ['order_sn'=>$params[0]];

                $this->write_db->update("rqf_order_info_{$suffix}", $order_info_upd, $order_info_key);

                $order_action_ins = [
                    'order_id'=>$trade_order->id,
                    'order_sn'=>$trade_order->order_sn,
                    'order_status'=>5,
                    'order_note'=>'商家确认返款',
                    'add_time'=>time(),
                    'created_user'=>$this->session->userdata('nickname'),
                    'comments'=>''
                ];

                $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
            }
        }

        $this->write_db->close();

        $referer = $this->input->server('HTTP_REFERER');

        redirect($referer);
    }
    /**
     * 批量导出商家返款活动信息
     */
    public function upload_bus_plat_order(){
        $res = $this->input->post('order_sn_arr');
        $order_sn_arr = explode(',',$res);
        $this->load->model('Review_Model','review');

        error_reporting(E_ALL);
        if(!$order_sn_arr || !is_array($order_sn_arr)){
            exit(json_encode(array('code'=>0,'msg'=>'请选择需要返款的订单信息')));
        }
        $order_sn_list=array();
        foreach ($order_sn_arr as $k=>$v){
            $order_sn_list[] = $v;
        }

        $bus_refund_info = $this->review->get_bus_refund_info($order_sn_list);
        $str_data = "商品名,子活动编号,店铺名称,买号名称,付款订单号,收款人,退款方式,退款账号,退款金额\r\n";


        foreach ($bus_refund_info as $v) {

            // 商品名
            $str_data .= $v->goods_name . "\t,";
            // 子活动编号
            $str_data .= $v->order_sn . "\t,";
            // 店铺名称
            $str_data .= $v->shop_name . "\t,";
            // 买号名称
            $str_data .= $v->account_name . "\t,";
            // 付款订单号
            $str_data .= $v->pay_sn . "\t,";
            // 收款人
            $str_data .= $v->refund_person . "\t,";
            // 退款方式
            $str_data .= $v->refund_type . "\t,";
            // 退款账号
            $str_data .= $v->refund_num . "\t,";

            // 退款金额
            $str_data .= $v->refund_money . "\r\n";
        }

        $this->load->helper('download');

        $d = date('Ymd');

        $download_name = "待退款订单信息一览";


        force_download("{$download_name}-{$d}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
        return;
    }

    /** 确认淘宝已退款 */
    public function confirm_taobao_refund()
    {
        $order_sn = trim($this->uri->segment(3));
        $user_id = intval($this->session->userdata('user_id'));
        $suffix = order_suffix($order_sn);
        $sql = "select * from `rqf_trade_order_{$suffix}` where `order_sn` = ? and `bus_user_id` = ? and `trade_type` in (115,215) and `order_status` in (4, 6)";
        $trade_order = $this->db->query($sql, [$order_sn, $user_id])->row();
        $referer = $this->input->server('HTTP_REFERER');
        if (empty($referer)) {
            $referer = "center";
        }

        if (empty($trade_order)) {
            redirect($referer);
            return;
        }
        if ($trade_order->bus_user_id != $user_id) {
            redirect($referer);
            return;
        }

        $this->write_db = $this->load->database('write', true);
        $sql = "update rqf_trade_order_{$suffix} set order_status = 5, refund_time = ? where bus_user_id = ? and order_status in (4, 6) and order_sn = ? ";
        $this->write_db->query($sql, [time(), $user_id, $order_sn]);
        if ($this->write_db->affected_rows()) {
            $order_action_ins = [
                'order_id' => $trade_order->id,
                'order_sn' => $trade_order->order_sn,
                'order_status' => 5,
                'order_note' => '商家确认淘宝已退款',
                'add_time' => time(),
                'created_user' => $this->session->userdata('nickname'),
                'comments' => ''
            ];
            $this->write_db->insert("rqf_order_action_{$suffix}", $order_action_ins);
        }
        $this->write_db->close();

        redirect($referer);
    }
}
