<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:活动详情控制器
 * 担当:
 */
class Detail extends Ext_Controller {

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
     * 主活动详情
     */
    public function trade()
    {
        $data = $this->data;
        $trade_id = intval($this->uri->segment(3));
        $trade_info = $this->trade->get_trade_info($trade_id);
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_id);
        $data['trade_search'] = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();
        $data['trade_scans'] = $this->db->get_where('rqf_trade_scan', ['trade_id' => $trade_id])->result();
        $data['trade_service'] = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_id])->result();
        $data['bind_shop'] = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $trade_type_name_list = $this->conf->trade_type_name_list();
        $data['trade_type_text'] = $trade_type_name_list[$trade_info->trade_type];
        $data['order_cnts'] = $this->trade->order_cnts($trade_info);
        if ($trade_info->trade_type == '10') {       //如果类型为流量订单
            $this->load->model('Traffic_Model', 'traffic');
            $cancel_refund = $this->traffic->cancel_refund($trade_info);
        } else {
            $cancel_refund = $this->trade->cancel_refund($trade_info);
        }
        $data['cancel_refund'] = $cancel_refund;
        if ('10' == $trade_info->trade_type) {
            $this->load->model('Traffic_Model', 'traffic');
            $data['traffic_list'] = $this->traffic->get_traffic_total_bussiness($trade_id);
        } else {
            // 押金(含保证金)/单
            $goods_val = bcmul($trade_info->price, (1 + TRADE_PAYMENT_PERCENT), 2);
            $goods_val = bcmul($goods_val, $trade_info->buy_num, 2);
            $data['goods_val'] = $goods_val;
            // 押金小计
            $deposit_subtotal = bcadd($goods_val, $trade_info->post_fee, 2);
            $data['deposit_subtotal'] = $deposit_subtotal;
        }

        // 任务要求
        $task_requirements = unserialize($data['trade_item']->task_requirements);
        unset($data['trade_item']->task_requirements);
        if (empty($task_requirements)) {
            $task_requirements = ['is_post' => 0, 'chat' => 1, 'coupon' => 0, 'coupon_link' => '', 'credit' => 0];
        }
        $data['task_requirements'] = $task_requirements;

        // 金币小计
        $point_subtotal = $trade_info->total_fee;
        if ($trade_info->is_phone) {
            $point_subtotal = bcadd($point_subtotal, ORDER_DIS_PRICE, 2);
        }
        $data['point_subtotal'] = $point_subtotal;

        $this->load->view('detail/trade', $data);
    }

    /**
     * 子活动详情
     */
    public function order()
    {
        $data = $this->data;
        $order_sn = trim($this->uri->segment(3));
        $suffix = order_suffix($order_sn);
        $trade_order = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn' => $order_sn, 'bus_user_id' => intval($this->session->userdata('user_id'))])->row();
        if (empty($trade_order)) {
            redirect('center');
            return;
        }

        $data['trade_type_list'] = $this->conf->trade_type_name_list();
        $data['trade_order'] = $trade_order;
        $data['order_info'] = $this->db->get_where("rqf_order_info_{$suffix}", ['order_sn' => $order_sn])->row();
        $trade_info = $this->trade->get_trade_info($trade_order->trade_id);
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_order->trade_id);
        $data['bind_account'] = $this->db->get_where('rqf_bind_account', ['id' => $trade_order->account_id])->row();
        $buy_user_info = $this->db->get_where('rqf_users', ['id' => $trade_order->user_id])->row();
        $data['buy_nickname'] = $buy_user_info->nickname;
        $data['bind_shop'] = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['setting_img'] = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id), 'order_sn' => $order_sn])->row();
        $data['setting_eval'] = $this->db->get_where('rqf_setting_eval', ['trade_id' => intval($trade_info->id), 'order_sn' => $order_sn])->row();
        if (in_array($trade_info->trade_type, ['1', '2', '3', '6'])) {
            $trade_search = $this->db->get_where('rqf_trade_search', ['id' => intval($trade_order->search_id)])->row();
            $data['kwds'] = $trade_search->kwd;
        }


        //关键词展示白名单
        $user_id = $this->session->userdata('user_id');
        $kwdimg_whitelist=['284322'];//关键词展示白名单 用户 userid
        $data["kwdimg_show"]=false;
        if (in_array($user_id,$kwdimg_whitelist)){
            $data["kwdimg_show"]=true;
        }
        $this->load->view('detail/order', $data);
    }
}
