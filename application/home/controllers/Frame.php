<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:Frame控制器
 * 担当:
 */
class Frame extends Ext_Controller {

    /**
     * __construct
     */
    public function __construct () {

        parent::__construct();

        $this->load->model('Trade_Model', 'trade');

        $this->load->model('Conf_Model', 'conf');

        $this->load->model('Bind_Model', 'bind');
    }

    /**
     * 活动列表frame
     */
    public function trade_list_frame()
    {
        $data = $this->data;
        $t = intval($this->uri->segment(3));
        if (!in_array($t, [1, 2, 3, 4, 5])) {
            $t = 1;
        }

        $data['t'] = $t;
        $user_id = intval($this->session->userdata('user_id'));
        // 绑定平台
        $plat_list = $this->bind->bind_shop_cnt_list($user_id);
        foreach ($plat_list as $k => $v) {
            if ($v['cnt'] == 0) {
                unset($plat_list[$k]);
            }
        }
        $data['plat_list'] = $plat_list;
        // 绑定店铺
        $shop_list = $this->bind->all_shop_list($user_id);
        $data['shop_list'] = $shop_list;
        // 任务类型
        $trade_type_name_list = $this->conf->trade_type_name_list();
        $data['trade_type_name_list'] = $trade_type_name_list;

        $sql = "select * from rqf_trade_info where user_id = ? and is_show = 1";
        $res = $this->db->query($sql, [$user_id])->result();
        $trade_cnts = ['all' => 0, 'ongoing' => 0, 'finished' => 0, 'unpayed' => 0, 'unchecked' => 0];
        $trade_cnts['all'] = count($res);
        foreach ($res as $v) {
            if (in_array($v->trade_status, ['2','4'])) {
                if ($v->apply_num < $v->total_num) {
                    $trade_cnts['ongoing']++;
                } else {
                    $trade_cnts['finished']++;
                }
            } elseif ($v->trade_status == '0') {
                $trade_cnts['unpayed']++;
            } elseif ($v->trade_status == '5') {
                $trade_cnts['unchecked']++;
            }
        }
        $data['trade_cnts'] = $trade_cnts;

        // 筛选条件

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
        // 活动类型
        $trade_type = $this->input->get('trade_type');
        $trade_types = array_keys($trade_type_name_list);
        if (!in_array($trade_type, $trade_types)) {
            $trade_type = '';
        }
        $data['trade_type'] = $trade_type;
        // 终端
        $terminal = $this->input->get('terminal');
        if (!in_array($terminal, ['1', '2'])) {
            $terminal = '';
        }
        $data['terminal'] = $terminal;
        // sub status
        $sub_status = $this->input->get('sub_status');
        if (!in_array($sub_status, ['1', '2', '3', '4', '5'])) {
            $sub_status = '';
        }
        $data['sub_status'] = $sub_status;

        // 评价类型
        $eval_type = $this->input->get('eval_type');
        if (!in_array($eval_type, ['1', '2', '3', '4', '5','6'])) {
            $eval_type = '';
        }
        $data['eval_type'] = $eval_type;

        // 高级搜索
        $key = $this->input->get('key');
        if (!in_array($key, ['1', '2', '3', '4', '5','6'])) {
            $key = '';
        }
        $data['key'] = $key;
        $val = $this->input->get('val');
        if (empty($val)) {
            $val = '';
        }
        $data['val'] = $val;
        // 报名活动起止时间
        $time_type = $this->input->get('ttime');
        $st = $this->input->get('st');
        $et = $this->input->get('et');
        if (empty($st)) {
            $st = '';
        }
        if (empty($et)) {
            $et = '';
        }
        $data['ttime'] = $time_type;
        $data['st'] = $st;
        $data['et'] = $et;

        // 分页信息
        $page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;

        $per_page = 5;

        $offset = ($page - 1) * $per_page;

        $where = '';

        if ($t == 4) {
            $where .= ' and trade_status = 0';              // 待付款活动单
        } elseif ($t == 2) {
            $where = ' and trade_status in(2,4) and apply_num < total_num';
        } elseif ($t == 3) {
            $where = ' and trade_status = 2 and apply_num >= total_num';
        } elseif ($t == 5) {
            $where .= ' and trade_status = 5';              // 审核不通过
        }
        if ($plat_id) {
            $where .= sprintf(" and plat_id = %s", $plat_id);
        }
        if ($shop_id) {
            $where .= sprintf(" and shop_id = %s", $shop_id);
        }
        if ($trade_type) {
            $where .= sprintf(" and trade_type = %s", $trade_type);
        }
        if ($terminal == '1') {
            $where .= sprintf(" and is_pc = 1");
        } elseif ($terminal == '2') {
            $where .= sprintf(" and is_phone = 1");
        }
        if ($sub_status) {
            if ($sub_status == '1') {
                $where = ' and pc_num + phone_num > 0';
            } elseif ($sub_status == '2') {
                $sql = "select trade_id from rqf_trade_order_union where bus_user_id = ? and order_status < 7";
                $res = $this->db->query($sql, [$user_id])->result();
                $ids = [];
                foreach ($res as $v) {
                    $ids[] = $v->trade_id;
                }
                $ids = array_unique($ids);
                if ($ids) {
                    $idstr = implode(',', $ids);
                } else {
                    $idstr = '0';
                }

                $where .= " and id in ({$idstr})";
            } elseif ($sub_status == '3') {
                $sql = "select trade_id from rqf_trade_order_union where bus_user_id = ? and order_status = 2";
                $res = $this->db->query($sql, [$user_id])->result();
                $ids = [];
                foreach ($res as $v) {
                    $ids[] = $v->trade_id;
                }
                $ids = array_unique($ids);
                if ($ids) {
                    $idstr = implode(',', $ids);
                } else {
                    $idstr = '0';
                }

                $where .= " and id in ({$idstr})";
            } elseif ($sub_status == '4') {
                $sql = "select trade_id from rqf_trade_order_union where bus_user_id = ? and order_status in (4,6)";
                $res = $this->db->query($sql, [$user_id])->result();
                $ids = [];
                foreach ($res as $v) {
                    $ids[] = $v->trade_id;
                }
                $ids = array_unique($ids);
                if ($ids) {
                    $idstr = implode(',', $ids);
                } else {
                    $idstr = '0';
                }

                $where .= " and id in ({$idstr})";
            } elseif ($sub_status == '5') {
                $sql = "select trade_id from rqf_trade_order_union where bus_user_id = ? and order_status = 7";
                $res = $this->db->query($sql, [$user_id])->result();
                $ids = [];
                foreach ($res as $v) {
                    $ids[] = $v->trade_id;
                }
                $ids = array_unique($ids);
                if ($ids) {
                    $idstr = implode(',', $ids);
                } else {
                    $idstr = '0';
                }

                $where .= " and id in ({$idstr})";
            }
        }
        //评价类型筛选条件
        if ($eval_type)
        {
            //传递值-1为类型对应值
            $eval_type_temp=(int)$eval_type-1;
            $where.= sprintf("  and eval_type=%s",$eval_type_temp);
        }

        if ($val) {
            $val = trim($val);
            $val = str_replace(' ', '', $val);
            if ($key == '1') {
                $where .= sprintf(" and trade_sn = '%s'", $val);
            } elseif ($key == '2') {
                $where .= sprintf(" and trade_sn = '%s'", substr($val, 0, 16));
            } elseif ($key == '3') {
                $trade_sn = '';
                $row = $this->db->get_where('rqf_trade_order_union', ['pay_sn' => $val])->row();
                if ($row) {
                    $trade_sn = $row->trade_sn;
                }
                $where .= sprintf(" and trade_sn = '%s'", $trade_sn);
            } elseif ($key == '4') {
                $ids = '0';
                $bind_account = $this->db->get_where('rqf_bind_account', ['account_name' => $val])->row();
                if ($bind_account) {
                    $suffix = suffix($bind_account->user_id);
                    $res = $this->db->get_where("rqf_trade_order_{$suffix}", ['bus_user_id' => $user_id, 'account_id' => $bind_account->id])->result();
                    foreach ($res as $v) {
                        $ids .= ",{$v->trade_id}";
                    }
                }

                $where .= " and id in ({$ids})";
            } elseif ($key == '5') {
                $ids = '0';
                $trade_item_res = $this->db->query("select trade_id from rqf_trade_item where goods_name like '%{$val}%'")->result();
                foreach ($trade_item_res as $v) {
                    $ids .= ",{$v->trade_id}";
                }

                $where .= " and id in ({$ids})";
            }elseif($key == '6'){ //根据运单号筛选
                $trade_sn = '';
                $row = $this->db->get_where('rqf_trade_order_union', ['express_sn' => $val])->row();
                if ($row) {
                    $trade_sn = $row->trade_sn;
                }
                $where .= sprintf(" and trade_sn = '%s'", $trade_sn);
            }
        }
        // 检查时间处理
        if ($st) {
            if ($time_type == '1'){
                $where .= " and created_time >= " . strtotime("{$st} 00:00:00");
            } else {
                $where .= " and pay_time >= " . strtotime("{$st} 00:00:00");
            }
        }

        if ($et) {
            if ($time_type == '1'){
                $where .= " and created_time <= " . strtotime("{$et} 23:59:59");
            } else {
                $where .= " and pay_time <= " . strtotime("{$et} 23:59:59");
            }
        }

        $trade_status_list = $this->conf->trade_status_list();

        $sql = "select * from rqf_trade_info where user_id = {$user_id} and is_show = 1 {$where} order by id desc limit {$offset},{$per_page}";
        $res = $this->db->query($sql)->result();
        $this->load->model('Traffic_Model', 'traffic');
        foreach ($res as $k => $v) {
            if ($v->apply_num >= $v->total_num) $v->trade_status = 3;
            $res[$k]->status_text = $trade_status_list[$v->trade_status];
            $res[$k]->trade_item = $this->trade->get_trade_item($v->id);
            $res[$k]->bind_shop = $this->db->get_where('rqf_bind_shop', ['id' => $v->shop_id])->row();
            if ($v->trade_type == '10') {
                $res[$k]->order_cnts = $this->traffic->traffic_order_cnts($v->id, $v->total_num);
            } else {
                $res[$k]->order_cnts = $this->trade->order_cnts($v);
            }
            if ($v->trade_status == '5'){
                $res[$k]->trade_uncheck = $this->db->get_where('rqf_trade_uncheck', ['trade_id' => $v->id])->result();
            } else {
                $res[$k]->trade_uncheck = '';
            }
        }
        $data['res'] = $res;

        $cnt_sql = "select count(1) cnt from rqf_trade_info where user_id = {$user_id} and is_show = 1 {$where}";
        $cnt_row = $this->db->query($cnt_sql)->row();

        // 分页
        $this->load->library('pagination');
        $config['base_url'] = "/frame/trade_list_frame/{$t}?plat_id=$plat_id&shop_id=$shop_id&trade_type=$trade_type&terminal=$terminal&sub_status=$sub_status&eval_type=$eval_type&key=$key&val=$val&st=$st&et=$et";
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

        $this->load->view('frame/trade_list_frame', $data);
    }

    /**
     * 子活动列表frame
     */
    public function order_list_frame() {

        $data = $this->data;
        $trade_id = intval($this->uri->segment(3));
        $data['trade_id'] = $trade_id;
        $user_id = $this->session->userdata('user_id');
        $data['status_type_list'] = [
            '1'=>'所有状态',
            '2'=>'已接手,待付款',
            '3'=>'快递单打印中',
            '4'=>'已付款,待发货',
            '5'=>'快递单已打印,待收货',
            '6'=>'已收货,待退款',
            '7'=>'已操作返款,待买手确认',
            '8'=>'已完成'
        ];

        $data['status_text_list'] = [
            '0'=>'已接手,待付款',
            '1'=>'快递单打印中',
            '2'=>'已付款,待发货',
            '3'=>'快递单已打印,待收货',
            '4'=>'已收货,待退款',
            '5'=>'已操作返款,待买手确认',
            '6'=>'已收货,待退款',
            '7'=>'已完成'
        ];
        $data['shipping_type_list'] = $this->conf->get_shipping_type_list();
        $where = '';
        $status_type = intval($this->input->get('status_type'));
        if (!in_array($status_type, [1,2,3,4,5,6,7,8])) {
            $status_type = 1;
        }

        $data['status_type'] = $status_type;
        switch ($status_type) {
            case 1:
                $where = " and o.order_status <= 7";
                break;
            case 2:
                $where = " and o.order_status = 0";
                break;
            case 3:
                $where = " and o.order_status = 1";
                break;
            case 4:
                $where = " and o.order_status = 2";
                break;
            case 5:
                $where = " and o.order_status = 3";
                break;
            case 6:
                $where = " and o.order_status in (4,6)";
                break;
            case 7:
                $where = " and o.order_status = 5";
                break;
            case 8:
                $where = " and o.order_status = 7";
                break;
        }
        $sql = "select o.id, o.channel, o.order_sn, o.express_sn, o.express_type, o.order_money, o.order_status, o.pay_sn, b.account_name, s.kwd
                  from rqf_trade_order_union o 
                    left join rqf_trade_search s on o.search_id = s.id 
                    left join rqf_bind_account b on o.account_id = b.id 
                 where o.bus_user_id = ? and o.trade_id = ? and o.order_status <= 7 ". $where ;
        $data['res'] = $this->db->query($sql, [$user_id, $trade_id])->result();

        $this->load->view('frame/order_list_frame', $data);
    }

    /**
     * 最近报名活动列表
     */
    public function recent_list_frame() {

        $user_id = $this->session->userdata('user_id');

        $shop_id = intval($this->uri->segment(3));

        $bind_shop = $this->db->get_where('rqf_bind_shop', ['id'=>$shop_id, 'user_id'=>$user_id])->row();

        if (empty($bind_shop)) {
            $bind_shop = (object)['shop_name'=>''];
        }

        $data['bind_shop'] = $bind_shop;

        $t = strtotime('-30 day');

        $sql = "select * from rqf_trade_info 
                where user_id = {$user_id}
                and shop_id = {$shop_id}
                and trade_status = 2
                and created_time > {$t}
                order by id desc";

        $res = $this->db->query($sql)->result();

        foreach ($res as $k=>$v) {
            $res[$k]->trade_item = $this->db->get_where('rqf_trade_item', ['trade_id'=>$v->id])->row();
        }

        $data['res'] = $res;

        $this->load->view('frame/recent_list_frame', $data);
    }


    /** 浏览子订单列表 **/
    public function traffic_list_frame()
    {
        $data = $this->data;
        $trade_id = intval($this->uri->segment(3));
        $data['trade_id'] = $trade_id;
        $user_id = $this->session->userdata('user_id');
        $data['status_type_list'] = [
            '1' => '所有状态',
            '2' => '已接手,待付款',
            '3' => '快递单打印中',
            '4' => '已付款,待发货',
            '5' => '快递单已打印,待收货',
            '6' => '已收货,待退款',
            '7' => '已操作返款,待买手确认',
            '8' => '已完成'
        ];

        $data['status_text_list'] = [
            '0' => '已接手,待付款',
            '1' => '快递单打印中',
            '2' => '已付款,待发货',
            '3' => '快递单已打印,待收货',
            '4' => '已收货,待退款',
            '5' => '已操作返款,待买手确认',
            '6' => '已收货,待退款',
            '7' => '已完成'
        ];
        $data['shipping_type_list'] = $this->conf->get_shipping_type_list();
        $where = '';
        $status_type = intval($this->input->get('status_type'));
        if (!in_array($status_type, [1, 2, 3, 4, 5, 6, 7, 8])) {
            $status_type = 1;
        }

        $data['status_type'] = $status_type;
        switch ($status_type) {
            case 1:
                $where = " and o.order_status <= 7";
                break;
            case 2:
                $where = " and o.order_status = 0";
                break;
            case 3:
                $where = " and o.order_status = 1";
                break;
            case 4:
                $where = " and o.order_status = 2";
                break;
            case 5:
                $where = " and o.order_status = 3";
                break;
            case 6:
                $where = " and o.order_status in (4,6)";
                break;
            case 7:
                $where = " and o.order_status = 5";
                break;
            case 8:
                $where = " and o.order_status = 7";
                break;
        }
        $sql = "select o.id, o.channel, o.order_sn, o.express_sn, o.express_type, o.order_money, o.order_status, o.pay_sn, b.account_name, s.kwd
                  from rqf_trade_order_union o 
                    left join rqf_trade_search s on o.search_id = s.id 
                    left join rqf_bind_account b on o.account_id = b.id 
                 where o.bus_user_id = ? and o.trade_id = ? and o.order_status <= 7 " . $where;
        $data['res'] = $this->db->query($sql, [$user_id, $trade_id])->result();

        $this->load->view('frame/traffic_list_frame', $data);
    }
}
