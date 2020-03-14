<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 名称:活动控制器
 * 担当:
 */
class Trade extends Ext_Controller
{

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->is_vip()) {
            redirect('group/pay_group');
            return;
        }

        $this->load->model('Conf_Model', 'conf');
        $this->load->model('Fee_Model', 'fee');
        $this->load->model('User_Model', 'user');
        $this->load->model('Uniq_Model', 'uniq');
        $this->load->model('Bind_Model', 'bind');
        $this->load->model('Trade_Model', 'trade');
        $this->load->model('Base64_Model', 'base64');
        $this->load->helper('qiniu_helper');
    }

    /**
     * 活动调度
     */
    public function step()
    {
        if ($this->uri->segment(3) === null) {
            $this->init();
            return;
        }
        $trade_id = intval($this->uri->segment(3));
        $plat_id = intval($this->input->post('plat_id'));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->db->get_where('rqf_trade_info', ['id' => $trade_id, 'user_id' => $user_id])->row();
        if (empty($trade_info)) {
            redirect('center');
            return;
        }
        if ($plat_id > 0 && $trade_info->plat_id != $plat_id) {
            $trade_info->plat_id = $plat_id;
            // 切换tab 更换默认的店铺选择
            $shop_list = $this->bind->bind_shop_list($user_id, $plat_id);
            if ($shop_list && count($shop_list) > 0) {
                $trade_info->shop_id = $shop_list[0]->id;
            }
        }

        // 未支付,已支付,审核不通过可进入流程
        if (!in_array($trade_info->trade_status, ['0', '1', '5'])) {
            redirect('center');
            return;
        }

        switch ($trade_info->trade_step) {
            // 活动第一步
            case '1':
                $this->step1($trade_info);
                break;
            default:
                if (in_array($trade_info->trade_type, ['1', '6', '7'])) {
                    // 文字好评
                    $this->char_eval_step($trade_info);
                } elseif (in_array($trade_info->trade_type, ['2', '114', '214'])) {
                    // 回访订单
                    $this->return_visit_step($trade_info);
                } elseif ($trade_info->trade_type == '3') {
                    // 图文好评
                    $this->pic_eval_step($trade_info);
                } elseif ($trade_info->trade_type == '4') {
                    // 聚划算
                    $this->jhs_step($trade_info);
                } elseif ($trade_info->trade_type == '5') {
                    // 淘抢购
                    $this->tqg_step($trade_info);
                } elseif (in_array($trade_info->trade_type, ['111', '112', '113', '211', '212', '213'])) {
                    // 双十一
                    $this->d11_step($trade_info);
                } elseif ($trade_info->trade_type == '10') {
                    // 流量订单
                    $this->traffic_step($trade_info);
                } elseif ($trade_info->trade_type == '90') {
                    // 超级搜索任务
                    $this->super_char_eval_step($trade_info);
                } elseif (in_array($trade_info->trade_type, ['115', '215'])) {
                    // 退款任务
                    $this->refund_step($trade_info);
                } elseif ($trade_info->trade_type == '140') {
                    // 拼多多搜索任务
                    $this->pdd_char_eval_step($trade_info);
                }
                break;
        }
    }

    /**
     * 上一步
     */
    public function prev()
    {
        if ($this->uri->segment(3) === null) {
            redirect('center');
            return;
        }

        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        switch ($trade_info->trade_step) {
            case '2':
                $trade_info_upd = ['trade_step' => 1];
                $key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 2];
                break;
            case '3':
                $trade_info_upd = ['trade_step' => 2];
                $key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 3];
                break;
            case '4':
                if ($trade_info->trade_type == '10') {
                    $trade_info_upd = ['trade_step' => 2];
                } else {
                    $trade_info_upd = ['trade_step' => 3];
                }
                $key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 4];
                break;
            case '5':
                $trade_info_upd = ['trade_step' => 4];
                $key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 5];
                break;
            case '6':
                $trade_info_upd = ['trade_step' => 5];
                $key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 6];
                break;
            case '7':
                $trade_info_upd = ['trade_step' => 6];
                $key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 7];
                break;
        }

        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->close();
        redirect('trade/step/' . $trade_id);
    }

    /**
     * 双十一活动调度
     */
    private function d11_step($trade_info)
    {

        // var_dump($trade_info);die;

        switch ($trade_info->trade_step) {
            case '2':
                $this->d11_step2($trade_info);
                break;
            case '3':
                $this->char_eval_step3($trade_info);
                break;
            case '4':
                $this->char_eval_step4($trade_info);
                break;
            case '5':
                $this->char_eval_step5($trade_info);
                break;
            case '6':
                $this->char_eval_step6($trade_info);
                break;
        }
    }

    /**
     * 双十一活动第二步
     */
    private function d11_step2($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['app_search'] = $trade_search['app'];
        $data['hc_search'] = $trade_search['hc'];
        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];

        // 会场信息
        $trade_double11 = $this->db->get_where('rqf_trade_double11', ['trade_id' => $trade_info->id])->row();

        if (empty($trade_double11)) {
            $trade_double11 = (object)[
                'source' => 1,
                'front_money' => '',
                'final_payment' => '',
                'activity_url' => '',
                'activity_img' => '',
                'goods_img' => '',
                'activity_cate' => ''
            ];
        }

        $data['trade_double11'] = $trade_double11;

        $this->load->view('trade/d11_step2', $data);
    }

    /**
     * 双十一第二步提交(1)
     */
    public function d11_step2_1_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $trade_info = $this->trade->get_trade_info($trade_id);

        $old_trade_search = $this->trade->get_trade_search($trade_id);

        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }

        $this->write_db = $this->load->database('write', true);

        $goods_name = trim($this->input->post('goods_name'));

        $goods_url = trim($this->input->post('goods_url'));

        $price = floatval($this->input->post('price'));

        $show_price = $this->input->post('show_price');

        $buy_num = intval($this->input->post('buy_num'));

        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);

        $front_money = floatval($this->input->post('front_money'));

        $final_payment = floatval($this->input->post('final_payment'));

        if ($goods_name == '') {
            echo json_encode(['code' => 2, 'msg' => '请输入商品名称']);
            return;
        }

        if ($goods_url == '') {
            echo json_encode(['code' => 3, 'msg' => '请输入商品链接']);
            return;
        }

        if (empty($price)) {
            echo json_encode(['code' => 5, 'msg' => '请输入商品价格']);
            return;
        }

        if (in_array($trade_info->trade_type, ['111', '112', '211', '212']) && empty($front_money)) {
            echo json_encode(['code' => 4, 'msg' => '请输入定金']);
            return;
        }

        if ((in_array($trade_info->trade_type, ['112', '212'])) && empty($final_payment)) {
            echo json_encode(['code' => 6, 'msg' => '请输入尾款']);
            return;
        }

        if (in_array($trade_info->trade_type, ['111', '112', '211', '212'])) {
            $price = bcadd($front_money, $final_payment, 2);
        } else {
            if (empty($price)) {
                echo json_encode(['code' => 5, 'msg' => '请输入商品价格']);
                return;
            }
        }

        if ($buy_num < 1) {
            echo json_encode(['code' => 7, 'msg' => '请输入购买件数']);
            return;
        }

        $activity_url = '';
        $activity_cate = '';
        $activity_img = '';
        $goods_img = '';
        $source = '';

        // 手机淘宝关键词进店
        $phone_check = trim($this->input->post('phone_check'));

        if ($phone_check) {

            $app_kwd = $this->input->post('app_kwd');

            $app_low_price = $this->input->post('app_low_price');

            $app_high_price = $this->input->post('app_high_price');

            $app_discount_text = $this->input->post('app_discount_text');

            $app_area = $this->input->post('app_area');

            $goods_cate = $this->input->post('goods_cate');

            $app_order_way = $this->input->post('app_order_way');

            $color = $this->input->post('app_guige_color');
            $size = $this->input->post('app_guige_size');

            $app_img1_base64 = $this->input->post('app_img1_base64');

            $app_img2_base64 = $this->input->post('app_img2_base64');

            // 关键词验证
            foreach ($app_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 3,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => $app_order_way
            ];

            if ($app_img1_base64) {
                // 判断是否是自动抓取的图片
                if (filter_var($app_img1_base64, FILTER_VALIDATE_URL)) {
                    $curl_img = qiniu_upload_binary($app_img1_base64);
                    $tmp_info['search_img'] = CDN_URL . $curl_img;
                } else {
                    $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                    $tmp_info['search_img'] = CDN_URL . $app_img1;
                    qiniu_upload(ltrim($app_img1, '/'));
                }
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                // $old_app_search_0 = $old_trade_search['app'][0];
                // $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            $goods_img = $tmp_info['search_img'];

            $old_app_search = $old_trade_search['app'];

            foreach ($app_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['low_price'] = $app_low_price[$k];
                $tmp_info['high_price'] = $app_high_price[$k];
                $tmp_info['discount'] = rtrim($app_discount_text[$k], ',');
                $tmp_info['area'] = $app_area[$k];
                $tmp_info['goods_cate'] = $goods_cate[$k];
                $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
                // $tmp_info['color'] = $color;
                // $tmp_info['size'] = $size;
                $trade_search[] = $tmp_info;
            }

            // var_dump($trade_search);die;

            $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);

            $this->write_db->insert_batch('rqf_trade_search', $trade_search);

            $source = 1;
        }

        // 双十一会场方式进店
        $hc_check = trim($this->input->post('hc_check'));

        if ($hc_check) {

            $activity_url = trim($this->input->post('activity_url'));

            if ($activity_url == '') {
                echo json_encode(['code' => 1, 'msg' => '请输入会场链接']);
                return;
            }

            $activity_cate = trim($this->input->post('activity_cate'));

            $activity_img_base64 = $this->input->post('activity_img_base64');

            if ($activity_img_base64) {
                $activity_img = $this->base64->to_img($activity_img_base64, UPLOAD_TRADE_INFO_DIR);
                qiniu_upload(ltrim($activity_img, '/'));
                $activity_img = CDN_URL . $activity_img;
            } else {
                $activity_row = $this->db->get_where('rqf_trade_double11', ['trade_id' => $trade_id])->row();

                $activity_img = '';

                if ($activity_row) {
                    $activity_img = $activity_row->activity_img;
                }
            }

            $trade_search = [];

            $goods_img = '';

            $hc_kwd = $this->input->post('hc_kwd');

            $goods_img_hc_base64 = $this->input->post('goods_img_hc_base64');

            // 关键词验证
            foreach ($hc_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 5
            ];

            if ($goods_img_hc_base64) {
                $goods_img = $this->base64->to_img($goods_img_hc_base64, UPLOAD_TRADE_INFO_DIR);
                qiniu_upload(ltrim($goods_img, '/'));
                $goods_img = CDN_URL . $goods_img;
            } else {
                $activity_row = $this->db->get_where('rqf_trade_double11', ['trade_id' => $trade_id])->row();

                $goods_img = '';

                if ($activity_row) {
                    $goods_img = $activity_row->goods_img;
                }
            }

            $old_hc_search = $old_trade_search['hc'];

            foreach ($hc_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['num'] = isset($old_hc_search[$k]) ? $old_hc_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_hc_search[$k]) ? $old_hc_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }

            $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);

            $this->write_db->insert_batch('rqf_trade_search', $trade_search);

            $source = 2;
        }

        if (in_array($trade_info->trade_type, ['111', '211'])) {
            $order_fee_obj = $this->fee->order_fee_obj(1, $front_money * $buy_num);
        } else {
            $order_fee_obj = $this->fee->order_fee_obj(1, $price * $buy_num);
        }
//
//        if (in_array($trade_info->trade_type, ['112', '212'])) {
//            $order_fee_obj->total_fee = $order_fee_obj->total_fee + 4;
//            $order_fee_obj->base_reward = $order_fee_obj->base_reward + 2;
//        }
        $order_fee_obj->total_fee = $order_fee_obj->total_fee - 2;
        $order_fee_obj->base_reward = $order_fee_obj->base_reward - 1;


        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }

        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();

        if ($trade_info->trade_type == '111') {
            $final_time = strtotime('2019-11-11 00:00:00');
        } else if ($trade_info->trade_type == '211') {
            $final_time = strtotime('2019-12-12 00:00:00');
        } else {
            $final_time = time();
        }
        $trade_double11 = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_info->trade_sn,
            'type' => 1,
            'source' => $source,
            'front_money' => $front_money,
            'final_payment' => $final_payment,
            'activity_url' => $activity_url,
            'activity_img' => $activity_img,
            'goods_img' => $goods_img,
            'activity_cate' => $activity_cate,
            'final_time' => $final_time
        ];

        $this->write_db->delete('rqf_trade_double11', ['trade_id' => $trade_id]);

        $this->write_db->insert('rqf_trade_double11', $trade_double11);

        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => 0,
            'is_phone' => 1,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];

        $trade_info_key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num
        ];

        $trade_item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);

        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 双十一第二步提交(2)
     */
    public function d11_step2_2_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $is_post = intval($this->input->post('is_post'));

        if ($is_post) {
            $post_fee = 0;
        } else {
            $post_fee = POST_FEE;
        }

        $trade_info_upd = [
            'is_post' => $is_post,
            'post_fee' => $post_fee
        ];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $trade_item_upd = [
            'is_post' => $is_post
        ];

        $item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);

        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);

        $this->write_db->close();

        echo 0;
    }

    /**
     * 双十一第二步提交(3)
     */
    public function d11_step2_3_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info_upd = ['trade_step' => 3];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);

        $this->write_db->close();

        echo 0;
    }

    /** 文字好评活动调度 **/
    private function char_eval_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->char_eval_step2($trade_info);
                break;
            case '3':
                $this->char_eval_step3($trade_info);
                break;
            case '4':
                $this->char_eval_step4($trade_info);
                break;
            case '5':
                $this->char_eval_step5($trade_info);
                break;
            case '6':
                $this->char_eval_step6($trade_info);
                break;
        }
    }

    /**
     * 超级搜索活动调度
     */
    private function super_char_eval_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->super_char_eval_step2($trade_info);
                break;
            case '3':
                $this->super_char_eval_step3($trade_info);
                break;
            case '4':
                $this->super_char_eval_step4($trade_info);
                break;
            case '5':
                $this->super_char_eval_step5($trade_info);
                break;
            case '6':
                $this->super_char_eval_step6($trade_info);
                break;
            case '7':
                $this->super_char_eval_step7($trade_info);
                break;
        }
    }

    /*** 回访订单活动调度 **/
    private function return_visit_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->return_visit_step2($trade_info);
                break;
            case '3':
                $this->return_visit_step3($trade_info);
                break;
            case '4':
                $this->return_visit_step4($trade_info);
                break;
            case '5':
                $this->return_visit_step5($trade_info);
                break;
            case '6':
                $this->return_visit_step6($trade_info);
                break;
        }
    }

    /*** 图文好评活动调度 **/
    private function pic_eval_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->pic_eval_step2($trade_info);
                break;
            case '3':
                $this->pic_eval_step3($trade_info);
                break;
            case '4':
                $this->pic_eval_step4($trade_info);
                break;
            case '5':
                $this->pic_eval_step5($trade_info);
                break;
            case '6':
                $this->pic_eval_step6($trade_info);
                break;
        }
    }

    /** 聚划算 */
    private function jhs_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->jhs_step2($trade_info);
                break;
            case '3':
                $this->char_eval_step3($trade_info);
                break;
            case '4':
                $this->char_eval_step4($trade_info);
                break;
            case '5':
                $this->char_eval_step5($trade_info);
                break;
            case '6':
                $this->char_eval_step6($trade_info);
                break;
        }
    }

    /** 淘抢购 */
    private function tqg_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->tqg_step2($trade_info);
                break;
            case '3':
                $this->char_eval_step3($trade_info);
                break;
            case '4':
                $this->char_eval_step4($trade_info);
                break;
            case '5':
                $this->char_eval_step5($trade_info);
                break;
            case '6':
                $this->char_eval_step6($trade_info);
                break;
        }
    }

    /** 流量发布流程 **/
    private function traffic_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->traffic_step2($trade_info);
                break;
            case '4':
                $this->traffic_step4($trade_info);
                break;
            case '5':
                $this->traffic_step5($trade_info);
                break;
            case '6':
                $this->char_eval_step6($trade_info);
                break;
        }
    }

    /*** 双十一退款订单 **/
    private function refund_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->refund_step2($trade_info);
                break;
            case '3':
                $this->refund_step3($trade_info);
                break;
            case '4':
                $this->refund_step4($trade_info);
                break;
            case '5':
                $this->refund_step5($trade_info);
                break;
            case '6':
                $this->refund_step6($trade_info);
                break;
        }
    }

    /** 拼多多搜索活动调度 **/
    private function pdd_char_eval_step($trade_info)
    {
        switch ($trade_info->trade_step) {
            case '2':
                $this->pdd_char_eval_step2($trade_info);
                break;
            case '3':
                $this->char_eval_step3($trade_info);
                break;
            case '4':
                $this->char_eval_step4($trade_info);
                break;
            case '5':
                $this->char_eval_step5($trade_info);
                break;
            case '6':
                $this->char_eval_step6($trade_info);
                break;
        }
    }

    /**
     * 活动初始化
     */
    private function init()
    {
        $this->write_db = $this->load->database('write', true);
        $user_id = $this->session->userdata('user_id');
        $trade_sn = $this->uniq->create_trade_sn();
        $bind_shop = $this->bind->default_bind_shop($user_id);
        if (empty($bind_shop)) {
            redirect('center/bind');
            return;
        } else {
            $plat_id = $bind_shop->plat_id;
            $shop_id = $bind_shop->id;
            $no_print = $bind_shop->no_print;
        }

        $trade_info = [
            'trade_sn' => $trade_sn,
            'user_id' => $user_id,
            'plat_id' => $plat_id,
            'shop_id' => $shop_id,
            'trade_step' => 1,
            'trade_type' => 1,
            'is_post' => 1,
            'total_num' => 10,
            'created_time' => time(),
            'no_print' => $no_print
        ];

        $this->write_db->insert('rqf_trade_info', $trade_info);
        $trade_id = $this->write_db->insert_id();
        $trade_item = ['trade_id' => $trade_id, 'trade_sn' => $trade_sn, 'buy_num' => 1, 'is_post' => 1];
        $this->write_db->insert('rqf_trade_item', $trade_item);

        // 默认增值服务
        $trade_service = [
            ['trade_id' => $trade_id, 'trade_sn' => $trade_sn, 'service_name' => 'plat_refund', 'comments' => '快速返款'],
            ['trade_id' => $trade_id, 'trade_sn' => $trade_sn, 'service_name' => 'first_check', 'comments' => '优先审核'],
            ['trade_id' => $trade_id, 'trade_sn' => $trade_sn, 'service_name' => 'traffic_list', 'comments' => '人气权重优化'],
            ['trade_id' => $trade_id, 'trade_sn' => $trade_sn, 'service_name' => 'free_eval', 'comments' => '自由好评']
        ];

        $result = $this->write_db->insert_batch('rqf_trade_service', $trade_service);
        if ($result > 0) {
            $param = serialize(['normal_price' => 4, 'collect_goods' => 2]);
            $result = $this->write_db->query('update rqf_trade_service set param = ? where trade_id = ? and service_name = ?', [$param, $trade_id, 'traffic_list']);
        }
        $trade_action = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_sn,
            'trade_status' => 0,
            'trade_note' => '开始报名活动',
            'add_time' => time(),
            'created_user' => $this->session->userdata('nickname'),
            'comments' => ''
        ];
        $this->write_db->insert('rqf_trade_action', $trade_action);

        redirect('trade/step/' . $trade_id);
    }

    /**
     * 获取订单侠订购授权状态
     */
    public function ddx_auth()
    {
        $show_ww = $this->input->post('shop_ww');
        $user_id = $this->session->userdata('user_id');

        if (empty($show_ww)) {
            exit(json_encode(['code' => 5, 'msg' => '参数错误！']));
        }
        $row = $this->db->get_where('rqf_bind_shop', ['user_id' => $user_id, 'shop_ww' => $show_ww])->row();
        if (empty($row)) {
            exit(json_encode(['code' => 5, 'msg' => '未找到该店铺信息！']));
        }

        $this->write_db = $this->load->database('write', true);
        $auth_info2 = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 2])->row();
        if (empty($auth_info2)) {
            $auth_info = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 1])->row();
            if (empty($auth_info)) {
                $result = $this->ddx($show_ww);
                if ($result['code'] == 0) {
                    $insert_array['shop_ww'] = $show_ww;
                    $insert_array['auth_type'] = 1;
                    $insert_array['is_order'] = $result['is_order'];
                    $insert_array['expires_time'] = strtotime($result['expires_time']);
                    $insert_array['deadline'] = strtotime($result['deadline']);
                    $this->write_db->insert('rqf_shop_auth_info', $insert_array);
                }
                exit(json_encode(['code' => $result['code'], 'msg' => $result['msg']]));
            } else {
                if ($auth_info->expires_time > time()) {
                    exit(json_encode(['code' => 0, 'msg' => 'ok']));
                } else {
                    exit(json_encode(['code' => 3, 'msg' => '授权过期，需要重新授权']));
                }
            }
        } else {
            if ($auth_info2->expires_time > time()) {
                exit(json_encode(['code' => 0, 'msg' => 'ok']));
            } else {
                exit(json_encode(['code' => 3, 'msg' => '授权过期，需要重新授权']));
            }
        }
    }

    private function ddx($show_ww)
    {
        $this->load->helper('curl_helper');
        $url = 'http://api.tbk.dingdanxia.com/shop/auth_info';
        $param['apikey'] = 'lNvC0W2qFV8OFbhSsT2IBOZ9u10ZjsuY';
        $param['seller_nick'] = $show_ww;
        $result = curl_post($url, $param);
        if ($result) {
            $result = json_decode($result, true);
            if ($result['code'] == '-1') {
                return ['code' => 1, 'msg' => '未购买，未授权'];
            }
            if ($result['code'] == '200') {
                $data = $result['data'];
                if ($data['is_order'] == 'false') {
                    return ['code' => 2, 'msg' => '订购过期，需要重新订购'];
                }
                $auth_time = strtotime($data['expires_time']);
                if ($auth_time < time()) {
                    return ['code' => 3, 'msg' => '授权过期，需要重新授权'];
                }
            }
            return ['code' => 0, 'msg' => 'ok', 'is_order' => $data['is_order'], 'expires_time' => $data['expires_time'], 'deadline' => $data['deadline']];
        } else {
            return ['code' => 4, 'msg' => '接口请求失败'];
        }
    }

    /**
     * 公用活动第一步
     * **/
    private function step1($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['bind_shop_cnt_list'] = $this->bind->bind_shop_cnt_list($user_id);
        foreach ($data['bind_shop_cnt_list'] as $plat_id => $item) {
            if ($item['cnt'] > 0) {
                $data['bind_shop_cnt_list'][$plat_id]['bind_shop_list'] = $this->bind->bind_shop_list($user_id, $plat_id);
            } else {
                $data['bind_shop_cnt_list'][$plat_id]['bind_shop_list'] = false;
            }
        }
        $data['trade_type_list'] = $this->conf->trade_type_list($trade_info->plat_id);
        // 记录弹出提示淘客框
        $redis_key = 'COLSE_TAOKE_TIPS_' . $user_id;
        $redis_home_tips = $this->cache->redis->get($redis_key);
        if (!$redis_home_tips) {
            $expire_times = strtotime(date('Y-m-d', strtotime('+1 day'))) - time();
            $this->cache->redis->save($redis_key, 1, $expire_times);
        }
        $data['show_tips'] = $redis_home_tips;

        $this->load->view('trade/step1', $data);
    }

    /**
     * 公用活动第一步提交
     */
    public function step1_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $plat_id = intval($this->input->post('plat_id'));

        $shop_id = intval($this->input->post('shop_id'));

        $trade_type = intval($this->input->post('trade_type'));

        $bind_shop = $this->db->get_where('rqf_bind_shop', ['id' => $shop_id])->row();

        if (empty($plat_id)) {
            echo 2;
            return;
        }

        if (empty($shop_id)) {
            echo 3;
            return;
        }

        if (empty($trade_type)) {
            echo 4;
            return;
        }

        $no_print = ($bind_shop->shipping_type == 'self') ? $bind_shop->no_print : 0;
        $trade_info = [
            'plat_id' => $plat_id,
            'shop_id' => $shop_id,
            'trade_type' => $trade_type,
            'trade_step' => 2,
            'no_print' => $no_print,
        ];

        if ($trade_type == 3) {
            $trade_info['total_num'] = 1;
        }

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id,
            'trade_step' => 1
        ];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info, $key);

        $this->write_db->close();

        echo 0;
    }

    /**
     * 文字好评第二步
     */
    private function char_eval_step2($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['taobao_search'] = $trade_search['pc_taobao'];
        $data['tmall_search'] = $trade_search['pc_tmall'];
        $data['app_search'] = $trade_search['app'];
        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        // 任务要求
        $task_requirements = unserialize($data['trade_item']->task_requirements);
        unset($data['trade_item']->task_requirements);
        if (empty($task_requirements)) {
            $task_requirements = ['is_post' => 0, 'chat' => 1, 'coupon' => 0, 'coupon_link' => '', 'credit' => 0];
        }
        $data['task_requirements'] = $task_requirements;

        $this->load->view('trade/char_eval_step2', $data);
    }

    /**
     * 文字好评第二步提交(1)
     */
    public function char_eval_step2_1_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        $old_trade_search = $this->trade->get_trade_search($trade_id);
        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }

        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $price = floatval($this->input->post('price'));
        $show_price = $this->input->post('show_price');
        $buy_num = intval($this->input->post('buy_num'));
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        if ($goods_name == '') {
            echo json_encode(['code' => 2, 'msg' => '请输入商品名称']);
            return;
        }

        if ($goods_url == '') {
            echo json_encode(['code' => 3, 'msg' => '请输入商品链接']);
            return;
        }
        if (empty($item_id)) {
            echo json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']);
            return;
        }
        if (empty($price)) {
            echo json_encode(['code' => 4, 'msg' => '请输入商品价格']);
            return;
        }

        if ($buy_num < 1) {
            echo json_encode(['code' => 5, 'msg' => '请输入购买件数']);
            return;
        }

        $trade_search = [];

        $goods_img = '';

        $pc_taobao = trim($this->input->post('pc_taobao'));
        if ($pc_taobao) {
            $tb_kwd = $this->input->post('tb_kwd');
            $tb_classify1 = $this->input->post('tb_classify1');
            $tb_classify2 = $this->input->post('tb_classify2');
            $tb_classify3 = $this->input->post('tb_classify3');
            $tb_classify4 = $this->input->post('tb_classify4');
            $tb_low_price = $this->input->post('tb_low_price');
            $tb_high_price = $this->input->post('tb_high_price');
            $tb_area = $this->input->post('tb_area');
            $tb_img_base64 = $this->input->post('tb_img_base64');

            // 关键词验证
            foreach ($tb_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 1,
                'low_price' => $tb_low_price,
                'high_price' => $tb_high_price,
                'area' => $tb_area
            ];

            if ($tb_img_base64) {
                $tb_img = $this->base64->to_img($tb_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tb_img;
                qiniu_upload(ltrim($tb_img, '/'));
            } else {
                $old_taobao_search_0 = $old_trade_search['pc_taobao'][0];
                $tmp_info['search_img'] = $old_taobao_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_taobao_search = $old_trade_search['pc_taobao'];

            foreach ($tb_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tb_classify1[$k];
                $tmp_info['classify2'] = $tb_classify2[$k];
                $tmp_info['classify3'] = $tb_classify3[$k];
                $tmp_info['classify4'] = $tb_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $pc_tmall = $this->input->post('pc_tmall');
        if ($pc_tmall) {
            $tm_kwd = $this->input->post('tm_kwd');
            $tm_classify1 = $this->input->post('tm_classify1');
            $tm_classify2 = $this->input->post('tm_classify2');
            $tm_classify3 = $this->input->post('tm_classify3');
            $tm_classify4 = $this->input->post('tm_classify4');
            $tm_low_price = $this->input->post('tm_low_price');
            $tm_high_price = $this->input->post('tm_high_price');
            $tm_area = $this->input->post('tm_area');
            $tm_img_base64 = $this->input->post('tm_img_base64');

            // 关键词验证
            foreach ($tm_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '天猫关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 2,
                'low_price' => $tm_low_price,
                'high_price' => $tm_high_price,
                'area' => $tm_area
            ];

            if ($tm_img_base64) {
                $tm_img = $this->base64->to_img($tm_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tm_img;
                qiniu_upload(ltrim($tm_img, '/'));
            } else {
                $old_tmall_search_0 = $old_trade_search['pc_tmall'][0];
                $tmp_info['search_img'] = $old_tmall_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_tmall_search = $old_trade_search['pc_tmall'];

            foreach ($tm_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tm_classify1[$k];
                $tmp_info['classify2'] = $tm_classify2[$k];
                $tmp_info['classify3'] = $tm_classify3[$k];
                $tmp_info['classify4'] = $tm_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $phone_taobao = trim($this->input->post('phone_taobao'));
        if ($phone_taobao) {
            $app_kwd = $this->input->post('app_kwd');
            $app_low_price = $this->input->post('app_low_price');
            $app_high_price = $this->input->post('app_high_price');
            $app_discount_text = $this->input->post('app_discount_text');
            $app_area = $this->input->post('app_area');
            $goods_cate = $this->input->post('goods_cate');
            $app_order_way = $this->input->post('app_order_way');
            $app_img1_base64 = $this->input->post('app_img1_base64');
            $app_img2_base64 = $this->input->post('app_img2_base64');
            $search_plat = ($trade_info->plat_id == '4') ? 4 : 3;           // 手机淘宝、与手机京东共文件，按平台区分
            if ($trade_info->trade_type == '7') {
                $app_kwd = ['--'];
            }

            // 关键词验证
            foreach ($app_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => $search_plat,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => is_null($app_order_way) ? '' : $app_order_way
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            $goods_img = $tmp_info['search_img'];
            $old_app_search = $old_trade_search['app'];
            foreach ($app_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['low_price'] = isset($app_low_price[$k]) ? $app_low_price[$k] : '';
                $tmp_info['high_price'] = isset($app_high_price[$k]) ? $app_high_price[$k] : '';
                $tmp_info['discount'] = isset($app_discount_text[$k]) ? rtrim($app_discount_text[$k], ',') : '';
                $tmp_info['area'] = isset($app_area[$k]) ? $app_area[$k] : '';
                $tmp_info['goods_cate'] = isset($goods_cate[$k]) ? $goods_cate[$k] : '';
                $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        if ($pc_taobao && $pc_tmall) {
            $is_pc = 3;
        } elseif ($pc_taobao && !$pc_tmall) {
            $is_pc = 1;
        } elseif (!$pc_taobao && $pc_tmall) {
            $is_pc = 2;
        } else {
            $is_pc = 0;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        $order_fee_obj = $this->fee->order_fee_obj($trade_info->trade_type, $price * $buy_num);
        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }

        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => $is_pc,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];
        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id];

        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];
        $trade_item_key = ['trade_id' => $trade_id];
        // data update
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);
        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_search', $trade_search);
        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 文字好评第二步提交(2)
     */
    public function char_eval_step2_2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }
        // 传入参数
        $is_post = intval($this->input->post('is_post'));
        $chat = intval($this->input->post('chat'));
        $coupon = intval($this->input->post('coupon'));
        $coupon_link = trim($this->input->post('coupon_link'));
        $credit = intval($this->input->post('credit'));
        $post_fee = $is_post ? 0 : POST_FEE;
        $trade_info_upd = ['is_post' => $is_post, 'post_fee' => $post_fee];
        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $task_requirements_str = serialize(['is_post' => $is_post, 'chat' => $chat, 'coupon' => $coupon, 'coupon_link' => $coupon_link, 'credit' => $credit]);      // 活动下单要求
        $trade_item_upd = ['is_post' => $is_post, 'task_requirements' => $task_requirements_str];
        $item_key = ['trade_id' => $trade_id];
        // 数据库更新
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->close();

        echo 0;
    }

    /**
     * 文字好评第二步提交(3)
     */
    public function char_eval_step2_3_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info_upd = ['trade_step' => 3];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);

        $this->write_db->close();

        echo 0;
    }

    /**
     * 文字好评第三步
     */
    private function char_eval_step3($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $trade_nums = ['1', '3', '5', '10', '20', '100', '250'];
        $data['is_custom'] = !in_array($trade_info->total_num, $trade_nums);
        $data['custom_val'] = in_array($trade_info->total_num, $trade_nums) ? 1 : $trade_info->total_num;
        // 订单搜索词
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_info->id])->result();

        if (count($trade_search) == 1) {
            $trade_search[0]->num = $trade_info->total_num;
        }
        $data['trade_search'] = $trade_search;
        $data['plat_names'] = ['1' => '淘宝', '2' => '天猫', '3' => '手机淘宝', '4' => '手机京东', '5' => '会场', '14' => '拼多多'];

        $this->load->view('trade/char_eval_step3', $data);
    }

    /**
     * 文字好评第三步提交
     */
    public function char_eval_step3_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info = $this->trade->get_trade_info($trade_id);
        $total_num = intval($this->input->post('total_num'));
        $total_num_custom = intval($this->input->post('total_num_custom'));
        $nums = $this->input->post('nums');
        $order_prompt = $this->input->post('order_prompt');
        if ($total_num == 0) {
            $total_num = $total_num_custom;
        }
        if ($total_num == 0) {
            echo 2;
            return;
        }
        // 京东链接直拍 没有关键字检索、聚划算、淘抢购
        if (in_array($trade_info->trade_type, ['4', '5', '7'])) {
            $nums[] = $total_num;
        }

        $pc_num = $trade_info->is_pc ? $total_num : 0;
        $phone_num = $trade_info->is_phone ? $total_num : 0;
        // 活动费用
        $order_fee_point = bcmul($trade_info->total_fee, $total_num, 2);
        // 手机端订单分布
        $order_dis_point = $trade_info->is_phone ? bcmul($total_num, ORDER_DIS_PRICE, 2) : 0;
        // 手机端赏金
        $phone_reward = $trade_info->is_phone ? PHONE_REWARD : 0;
        // 每单商品价值
        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 2);
        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 2);
        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 2);
        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 2);
        // 活动押金
        $trade_deposit = bcmul($deposit_subtotal, $total_num, 2);
        // 活动保证金
        $trade_payment = bcmul($payment, $total_num, 2);
        // 活动邮费
        $trade_post_fee = bcmul($trade_info->post_fee, $total_num, 2);
        // 赠送流量单（浏览+加购）
        $award_num = $this->award_num($total_num);
        $trade_info_upd = [
            'total_num' => $total_num,
            'award_num' => $award_num,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'phone_reward' => $phone_reward,
            'order_fee_point' => $order_fee_point,
            'order_dis_point' => $order_dis_point,
            'trade_payment' => $trade_payment,
            'trade_post_fee' => $trade_post_fee,
            'trade_deposit' => $trade_deposit,
            'trade_step' => 4
        ];

        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $trade_item_upd = ['order_prompt' => $order_prompt];
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();
        if (count($nums) != count($trade_search)) {
            echo 3;
            return;
        }

        $nums_sum = 0;
        $trade_search_upd = [];
        foreach ($nums as $k => $v) {
            $nums_sum += $v;
            $trade_search_upd[] = ['id' => $trade_search[$k]->id, 'num' => $v, 'surplus_num' => $v];
        }

        if ($nums_sum != $total_num) {
            echo 3;
            return;
        }

        $item_key = ['trade_id' => $trade_id];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->update_batch('rqf_trade_search', $trade_search_upd, 'id');
        $this->write_db->close();

        echo 0;
    }

    /**
     * 文字好评第四步
     */
    private function char_eval_step4($trade_info)
    {
        $data = $this->data;
        $user_id = intval($this->session->userdata('user_id'));
        $data['trade_info'] = $trade_info;
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $data['interval_list'] = $this->conf->interval_list();
        // 活动单数超过10单，删除自定义好评及评价内容
        $this->write_db = $this->load->database('write', true);
        if ($trade_info->total_num > 10) {
            $result = $this->write_db->query('DELETE FROM `rqf_trade_service` WHERE `trade_id` = ? AND `service_name` in ?', [intval($trade_info->id), ['setting_eval', 'setting_picture']]);
            $this->write_db->delete('rqf_setting_eval', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        } elseif ($trade_info->total_num > 5) {
            // 活动单数超过5单，删除图文好评及评价内容
            $this->write_db->delete('rqf_trade_service', ['trade_id' => intval($trade_info->id), 'service_name' => 'setting_picture']);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        }
        $this->write_db->close();
        // 增值服务
        $res = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
        $trade_service = [];
        foreach ($res as $v) {
            $trade_service[$v->service_name] = $v;
        }

        // 平台返款
        // $data['has_plat_refund'] = (isset($trade_service['plat_refund']) || (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800));
        $data['has_plat_refund'] = true;
        // $data['plat_refund_disabled'] = (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800);
        $data['plat_refund_disabled'] = true;           // 强制平台返款
        $data['plat_refund_percent'] = fun_plat_refund_percent(bcmul($trade_info->price, $trade_info->buy_num, 4));        // 获取任务订单退款手续费
        // 商家返款
        $data['has_bus_refund'] = isset($trade_service['bus_refund']);
        // 提升完成活动速度
        $data['add_speed_val'] = isset($trade_service['add_speed']) ? $trade_service['add_speed']->price : 0;
        // 加赏活动佣金
        $data['has_add_reward'] = isset($trade_service['add_reward']);
        $data['add_reward_val'] = isset($trade_service['add_reward']) ? intval($trade_service['add_reward']->price) : 3;
        // 优先审单
        $data['has_first_check'] = isset($trade_service['first_check']);
        // 千人千面设置 地域限制
        $data['area_limit'] = isset($trade_service['area_limit']);
        $data['area_limit_list'] = isset($trade_service['area_limit']) ? explode(',', $trade_service['area_limit']->param) : [];
        // 千人千面设置 性别选择
        $data['sex_limit'] = isset($trade_service['sex_limit']);
        $data['sex_limit_val'] = isset($trade_service['sex_limit']) ? $trade_service['sex_limit']->param : '0';
        // 指定平台新注册买手接单
        $data['has_newhand'] = isset($trade_service['newhand']) ? $trade_service['newhand']->param : '';
        // 仅限钻级别的买号可接此活动
        $data['reputation_limit'] = isset($trade_service['reputation_limit']) ? 2 : 1;
        // 仅限淘气值1000以上买号可接此活动
        $data['taoqi_limit'] = isset($trade_service['taoqi_limit']) ? 2 : 1;
        // 定时发布
        $data['has_set_time'] = isset($trade_service['set_time']);
        $data['set_time_val'] = isset($trade_service['set_time']) ? $trade_service['set_time']->param : '';
        // 定时结束任务
        $data['has_set_over_time'] = isset($trade_service['set_over_time']);
        $data['set_over_time_val'] = isset($trade_service['set_over_time']) ? $trade_service['set_over_time']->param : '';
        // 分时发布
        $data['custom_time_price'] = isset($trade_service['custom_time_price']) ? json_decode($trade_service['custom_time_price']->param, true) : [];
        $data['set_time_pre_val'] = intval($trade_info->start_time) > 0 ? date('Y-m-d', $trade_info->start_time) : date('Y-m-d');
        // 分时单数
        $interval_nums = [1, 2, 5, 10, 20, 50];
        foreach ($interval_nums as $k => $v) {
            if ($v >= $trade_info->total_num && ($v != 1)) {
                unset($interval_nums[$k]);
            }
        }

        $data['interval_nums'] = $interval_nums;
        // 间隔发布
        /**
         * $data['has_set_interval'] = (isset($trade_service['set_interval'])) || ($trade_info->total_num >= 20);
         * if ($trade_info->total_num == 1) {
         * $data['has_set_interval'] = false;
         * }
         * $data['set_interval_disabled'] = (($trade_info->total_num >= 20) || ($trade_info->total_num == 1));
         * */
        $data['set_interval_disabled'] = false;
        $data['has_set_interval'] = isset($trade_service['set_interval']);

        if (isset($trade_service['set_interval'])) {
            $params = explode('|', $trade_service['set_interval']->param);
            $set_interval_val = $params[0];
            $interval_num_val = $params[1];
        } else {
            $set_interval_val = '10m';
            $interval_num_val = '1';
        }
        $data['set_interval_val'] = $set_interval_val;
        $data['interval_num_val'] = $interval_num_val;

        if (in_array($trade_info->trade_type, ['111', '211'])) {
            // 包裹重量
            $data['set_weight_val'] = '0';
            // 快递选择
            $data['set_shipping'] = 'self';
        } else {
            // 包裹重量
            $data['set_weight_val'] = isset($trade_service['set_weight']) ? $trade_service['set_weight']->param : '2';
            if ($trade_info->plat_id == 4 || $trade_info->plat_id == 14) {
                $data['set_shipping'] = 'sto';
            } else {
                // 快递选择
                $data['set_shipping'] = isset($trade_service['set_shipping']) ? $trade_service['set_shipping']->param : $data['trade_select']['shipping_type'];
            }
        }
        // 默认好评
        $data['has_default_eval'] = isset($trade_service['default_eval']);
        // 自由好评
        $data['has_free_eval'] = isset($trade_service['free_eval']);
        // 关键词好评
        $data['has_kwd_eval'] = isset($trade_service['kwd_eval']);
        // 关键词列表
        $data['kwds'] = isset($trade_service['kwd_eval']) ? json_decode($trade_service['kwd_eval']->param, true) : ['', '', ''];
        // 自定义好评
        $data['has_setting_eval'] = isset($trade_service['setting_eval']);
        // 自定义好评内容
        $eval_contents = [];
        if ($trade_info->total_num <= 10) {
            $setting_eval_res = $this->db->get_where('rqf_setting_eval', ['trade_id' => intval($trade_info->id)])->result();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $eval_contents[] = '';
            }
            if ($setting_eval_res) {
                foreach ($setting_eval_res as $k => $v) {
                    if ($k >= $trade_info->total_num) {
                        continue;
                    }

                    $eval_contents[$k] = $v->content;
                }
            }
            $data['setting_eval_disabled'] = false;
        } else {
            $data['setting_eval_disabled'] = true;
        }
        $data['eval_contents'] = $eval_contents;
        // 图文好评
        $data['has_setting_picture'] = (isset($trade_service['setting_picture']) && $trade_info->trade_type != '140') ? true : false;
        $txt_images_list = [];
        if ($trade_info->total_num <= 5 && $trade_info->trade_type != '140') {
            $trade_item_res = $this->db->get_where('rqf_trade_item', ['trade_id' => intval($trade_info->id)])->row();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $txt_images_list[] = ['color' => $trade_item_res->color, 'size' => $trade_item_res->size, 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => ''];
            }

            if ($data['has_setting_picture']) {
                $txt_image_res = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id)])->result();
                if ($txt_image_res) {
                    foreach ($txt_image_res as $k => $v) {
                        if ($k >= $trade_info->total_num) {
                            continue;
                        }
                        $txt_images_list[$k] = ['color' => $v->color, 'size' => $v->size, 'img1' => $v->img1, 'img2' => $v->img2, 'img3' => $v->img3, 'img4' => $v->img4, 'img5' => $v->img5, 'content' => $v->content];
                    }
                }
            }
        }
        $data['txt_image_list'] = $txt_images_list;

        // 视频评价
        $data['has_setting_video'] = (isset($trade_service['setting_video']) && $trade_info->trade_type != '140') ? true : false;
        $video_image_list = [];
        if ($trade_info->total_num <= 5 && $trade_info->trade_type != '140') {
            $trade_item_res = $this->db->get_where('rqf_trade_item', ['trade_id' => intval($trade_info->id)])->row();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $video_image_list[] = ['color' => $trade_item_res->color, 'size' => $trade_item_res->size, 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => '', 'video' => ''];
            }

            if ($data['has_setting_video']) {
                $video_image_res = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id)])->result();
                if ($video_image_res) {
                    foreach ($video_image_res as $k => $v) {
                        if ($k >= $trade_info->total_num) {
                            continue;
                        }
                        $video_image_list[$k] = ['color' => $v->color, 'size' => $v->size, 'img1' => $v->img1, 'img2' => $v->img2, 'img3' => $v->img3, 'img4' => $v->img4, 'img5' => $v->img5, 'content' => $v->content, 'video' => $v->video];
                    }
                }
            }
        }
        $data['video_image_list'] = $video_image_list;

        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 4);
        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 4);
        $data['payment'] = $payment;
        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 4);
        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 4);
        $data['deposit_subtotal'] = $deposit_subtotal;
        // 金币小计
        $point_subtotal = $trade_info->total_fee;
        if ($trade_info->is_phone) {
            $point_subtotal = bcadd($point_subtotal, ORDER_DIS_PRICE, 4);
        }
        $data['point_subtotal'] = $point_subtotal;
        // 快递类型
        if (in_array($trade_info->trade_type, ['111', '211'])) {
            $list = [
                'self' => ['name' => '自发快递赠送小礼品', 'price' => 0, 'default' => 1, 'is_show' => 1],
            ];
            $data['shipping_type_list'] = $list;
        } else {
            if ($trade_info->plat_id == 4 || $trade_info->plat_id == 14) {
                $list = [
                    'sto' => ['name' => '申通快递', 'price' => 3, 'default' => 1, 'is_show' => 1],
                    'yunda' => ['name' => '韵达快递', 'price' => 3, 'default' => 0, 'is_show' => 0],
                    'zto' => ['name' => '中通快递', 'price' => 3, 'default' => 0, 'is_show' => 1],
                    'self' => ['name' => '自发快递赠送小礼品', 'price' => 0, 'default' => 0, 'is_show' => 1],
                ];
                $data['shipping_type_list'] = $list;
            } else {
                $data['shipping_type_list'] = $this->conf->get_shipping_type_list();
            }
        }
        // 人气权重

        $this->load->model('Traffic_Model', 'traffic');
        if (in_array($trade_info->trade_type, ['4', '5']) || in_array($trade_info->plat_id, ['4', '14'])) {
            $service_list = [];
        } else {
            if (isset($trade_service['traffic_list'])) {
                $service_list = unserialize($trade_service['traffic_list']->param);
            } else {
                if (count($trade_service) <= 0) {
                    $service_list = ['normal_price' => 4, 'collect_goods' => 2];   // 默认勾选浏览商品、收藏商品
                } else {
                    $service_list = [];
                }
            }
        }
        $data['traffic_arr'] = $this->traffic->normal_task_traffic_show($trade_info->total_num, $service_list, time());

        // 增值服务优惠折扣
        $discount_list = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        foreach ($discount_list as $item) {
            $data['discount'][$item->service_name] = intval($item->discount);
        }

        $this->load->view('trade/char_eval_step4', $data);
    }

    /**
     * 文字好评第四步提交
     */
    public function char_eval_step4_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }
        $this->write_db = $this->load->database('write', true);
        $trade_info = $this->trade->get_trade_info($trade_id);
        $trade_service = [];
        $service_point = 0;
        // 增值服务优惠折扣
        $discount_query = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        $discount_list = [];
        foreach ($discount_query as $item) {
            $discount_list[$item->service_name] = intval($item->discount);
        }
        // 快速返款
        $plat_refund = $this->input->post('plat_refund');
        if ($plat_refund || (bcmul($trade_info->price, $trade_info->buy_num, 4) <= 800)) {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, fun_plat_refund_percent($tmp_price), 4);
            if (array_key_exists('plat_refund', $discount_list) && $discount_list['plat_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['plat_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'plat_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '快速返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 1;
        } else {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, BUS_REFUND_PERCENT, 4);
            if (array_key_exists('bus_refund', $discount_list) && $discount_list['bus_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['bus_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'bus_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '商家返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 0;
        }

        // 提升完成活动速度
        $add_speed = intval($this->input->post('add_speed'));
        if ($add_speed) {
            if (!in_array($add_speed, [10, 20, 30])) {
                $add_speed = 10;
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_speed',
                'price' => $add_speed,
                'num' => 1,
                'pay_point' => $add_speed,
                'param' => '',
                'comments' => '提升完成活动速度'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_speed = 0;
        }

        // 加赏活动佣金
        $add_reward = $this->input->post('add_reward');
        $add_reward_point = intval($this->input->post('add_reward_point'));
        if ($add_reward) {
            $add_reward_point = max($add_reward_point, 2);
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward',
                'price' => $add_reward_point,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($add_reward_point, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '加赏活动佣金'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_reward_point = 0;
        }

        // 优先审核
        $first_check = $this->input->post('first_check');
        if ($first_check) {
            $first_check_val = 1;
            $tmp_price = FIRST_CHECK_PRICE;
            if (array_key_exists('first_check', $discount_list) && $discount_list['first_check'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['first_check'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_check',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => '',
                'comments' => '优先审核'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $first_check_val = 0;
        }

        // 定时发布
        $set_time = $this->input->post('set_time');
        $set_time_val = $this->input->post('set_time_val');
        if ($set_time && $set_time_val) {
            if (strtotime($set_time_val) <= time()) {
                exit(json_encode(['error' => 1, 'message' => '设置的定时发布时间应大于当前时间']));
            }
            $tmp_price = 3;
            if (array_key_exists('set_time', $discount_list) && $discount_list['set_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_time_val,
                'comments' => '定时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $start_time = strtotime($set_time_val);
        } else {
            $start_time = 0;
        }
        // 定时结束任务
        $set_over_time = $this->input->post('set_over_time');
        $set_over_time_val = $this->input->post('set_over_time_val');
        if ($set_over_time && $set_over_time_val) {
            $compare_time = ($set_time && $set_time_val) ? strtotime($set_time_val) : time();
            if (strtotime($set_over_time_val) <= $compare_time + 3600) {
                exit(json_encode(['error' => 1, 'message' => '结束时间、与活动时间至少错开一个小时']));
            }
            $tmp_price = 2;
            if (array_key_exists('set_over_time', $discount_list) && $discount_list['set_over_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_over_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_over_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_over_time_val,
                'comments' => '定时结束'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 活动分时发布、与间隔发布 目前暂定为互斥关系 二选一
        $interval_param = '';
        $custom_time_price = $this->input->post('custom_time_price');       // 分时发布
        $set_interval = $this->input->post('set_interval');                 // 间隔发布
        if ($custom_time_price && $set_interval) {
            exit(json_encode(['error' => 1, 'message' => '分时发布、与间隔发布暂不可以同时发布 ，请确认']));
        } elseif ($custom_time_price) {
            // 分时发布
            $total_nums = 0;
            $custom_time_price_list = [];
            // 查看分时发布开始时间
            $set_time_pre_val = empty(trim($this->input->post('set_time_pre_val'))) ? strtotime('+1 hour') : strtotime(trim($this->input->post('set_time_pre_val')));
            $reference_hour = date('H', $set_time_pre_val);
            foreach ($custom_time_price as $item) {
                if (intval($item['nums']) <= 0) continue;
                if (intval($item['hour']) < $reference_hour) {
                    exit(json_encode(['error' => 1, 'message' => '分时发布点应大于当前时间、或定时发布的时间，请确认']));
                }
                $total_nums += intval($item['nums']);
                $custom_time_price_list[$item['hour']] = $item['nums'];
            }
            if ($total_nums != $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '总活动单数与时间点单数累加值应一致，请确认']));
            }
            $tmp_price = CUSTOM_TIME_PRICE;
            if (array_key_exists('custom_time_price', $discount_list) && $discount_list['custom_time_price'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['custom_time_price'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'custom_time_price',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => json_encode($custom_time_price_list),
                'comments' => '分时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $pc_num = 0;
            $phone_num = 0;
            $start_time = $set_time_pre_val;
        } elseif ($set_interval) {
            // 间隔发布
            $set_interval_val = $this->input->post('set_interval_val');
            $interval_num = $this->input->post('interval_num');
            $interval_list = $this->conf->interval_list();
            $interval_list_keys = array_keys($interval_list);
            if (!in_array($set_interval_val, $interval_list_keys)) {
                $set_interval_val = $interval_list_keys[0];
            }
            $tmp_price = SET_INTERVAL_PRICE;
            if (array_key_exists('set_interval', $discount_list) && $discount_list['set_interval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_interval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_interval',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => "{$set_interval_val}|{$interval_num}",
                'comments' => '间隔发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $interval_param = "{$set_interval_val}|{$interval_num}";
            $pc_num = 0;
            $phone_num = 0;
        } else {
            $pc_num = $trade_info->is_pc ? $trade_info->total_num : 0;
            $phone_num = $trade_info->is_phone ? $trade_info->total_num : 0;
        }

        // 选择包裹配送方式
        $no_print = $trade_info->no_print;
        $shipping = $this->input->post('shipping');
        if (is_null($shipping) || empty(trim($shipping))) {
            exit(json_encode(['error' => 1, 'message' => '请选择预备配送的快递类型']));
        }
        $shipping_info = $this->conf->get_shipping_type_list($shipping);
        if ($shipping_info && $shipping_info['name']) {
            //快递折扣
            if (array_key_exists('set_shipping', $discount_list) && $discount_list['set_shipping'] < 100) {
                $shipping_info['price'] = round(bcmul($shipping_info['price'], $discount_list['set_shipping'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_shipping',
                'price' => $shipping_info['price'],
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($shipping_info['price'], $trade_info->total_num, 4),
                'param' => $shipping,
                'comments' => $shipping_info['name'] . '配送'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            if ($shipping == 'self') {
                $no_print = (intval($trade_info->no_print) <= 0) ? 2 : intval($trade_info->no_print);
            } else {
                $no_print = 0;
            }
        }

        // 自定义包裹重量(必选)
        $set_weight_val = $this->input->post('set_weight_val');
        $tmp_info = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_info->trade_sn,
            'service_name' => 'set_weight',
            'price' => SET_WEIGHT_PRICE,
            'num' => 1,
            'pay_point' => SET_WEIGHT_PRICE,
            'param' => $set_weight_val,
            'comments' => '自定义包裹重量'
        ];
        $trade_service[] = $tmp_info;
        $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);

        // 更新商品记录商品重量
        $this->write_db->update('rqf_trade_item', ['weight' => $set_weight_val], ['trade_id' => $trade_id]);

        // 延长买家购物周期
        $extend_cycle = intval($this->input->post('extend_cycle'));
        if ($extend_cycle && in_array($extend_cycle, [2, 3])) {
            if ($extend_cycle == 2) {
                $tmp_price = EXTEND_CYCLE1_PRICE;
            } else {
                $tmp_price = EXTEND_CYCLE2_PRICE;
            }
            if (array_key_exists('extend_cycle', $discount_list) && $discount_list['extend_cycle'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['extend_cycle'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'extend_cycle',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $extend_cycle,
                'comments' => '延长买家购物周期'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $extend_cycle = 0;
        }

        // 限制买号重复进店下单
        $shopping_end = intval($this->input->post('shopping_end'));
        if ($shopping_end) {
            $tmp_price = SHOPPING_END_BOX;
            if (array_key_exists('shopping_end', $discount_list) && $discount_list['shopping_end'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['shopping_end'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'shopping_end',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $shopping_end,
                'comments' => '限制买号重复进店下单'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 指定平台新注册买手接单
        $newhand = intval($this->input->post('newhand'));
        if ($newhand) {
            if ($newhand == 1) {
                $tmp_price = 1;
                $comments = '指定平台1个月内新注册下单';
            } elseif ($newhand == 2) {
                $tmp_price = 2;
                $comments = '指定平台15天内新注册下单';
            } else {
                $tmp_price = 3;
                $comments = '指定平台7天内新注册下单';
            }
            if (array_key_exists('newhand', $discount_list) && $discount_list['newhand'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['newhand'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'newhand',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $newhand,
                'comments' => $comments
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 地域限制
        $area_limit = $this->input->post('area_limit');
        if ($area_limit == '1') {
            $area_limit_city = $this->input->post('area_limit_city');
            $tmp_price = AREA_LIMIT;
            if (array_key_exists('area_limit', $discount_list) && $discount_list['area_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['area_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'area_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => implode(',', $area_limit_city),
                'comments' => '千人千面－地域限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 性别限制
        $sex_limit = $this->input->post('sex_limit');
        if ($sex_limit == '1') {
            $sex_limit_val = $this->input->post('sex_limit_val');
            $tmp_price = SEX_LIMIT;
            if (array_key_exists('sex_limit', $discount_list) && $discount_list['sex_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['sex_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'sex_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $sex_limit_val,
                'comments' => '千人千面－性别限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 钻级别的买号
        $reputation_limit = $this->input->post('reputation_limit');
        if ($reputation_limit == '1') {
            $tmp_price = REPUTATION_LIMIT;
            if (array_key_exists('reputation_limit', $discount_list) && $discount_list['reputation_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['reputation_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'reputation_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 5,           // 标记5以上就是钻级
                'comments' => '千人千面－钻级别的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 淘气值1000的买号
        $taoqi_limit = $this->input->post('taoqi_limit');
        if ($taoqi_limit == '1') {
            $tmp_price = TAOQI_LIMIT;
            if (array_key_exists('taoqi_limit', $discount_list) && $discount_list['taoqi_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['taoqi_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'taoqi_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 1000,
                'comments' => '千人千面－淘气值1000的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 人气权重
        $traffic_list = $this->input->post('traffic_list');
        if (count($traffic_list) > 0) {
            $tmp_price = 0;
            $this->load->model('Traffic_Model', 'traffic');
            $traffic_arr = $this->traffic->get_traffic_list(time());
            $data_arr = [];
            foreach ($traffic_list as $item) {
                if (intval($item['num']) > 0) {
                    $tmp_price += floatval($traffic_arr[$item['name']]['price'] * $item['num']);
                    $data_arr[$item['name']] = $item['num'];
                }
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'traffic_list',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => serialize($data_arr),
                'comments' => '人气权重优化'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 评价类型
        $eval_type = 0;
        // 默认好评
        $default_eval = $this->input->post('default_eval');
        if ($default_eval) {
            $eval_type = 1;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'default_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '默认好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 自由好评
        $free_eval = $this->input->post('free_eval');
        if ($free_eval) {
            $eval_type = 0;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'free_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自由好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 关键词好评
        $kwd_eval = $this->input->post('kwd_eval');
        $kwds = $this->input->post('kwds');
        if ($kwd_eval && $kwds) {
            $eval_type = 2;
            foreach ($kwds as $k => $v) {
                if ($v == '') unset($kwds[$k]);
            }
            if (count($kwds) != 3) {
                exit(json_encode(['error' => 1, 'message' => '关键词好评填写不正确']));
            }
            $tmp_price = KWD_EVAL_PRICE;
            if (array_key_exists('kwd_eval', $discount_list) && $discount_list['kwd_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['kwd_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'kwd_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => json_encode($kwds),
                'comments' => '关键词好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 自定义好评
        $setting_eval = $this->input->post('setting_eval');
        $eval_contents = $this->input->post('eval_contents');
        $this->write_db->delete('rqf_setting_eval', ['trade_id' => $trade_id]);
        if ($setting_eval && $eval_contents) {
            $eval_type = 3;
            foreach ($eval_contents as $k => $v) {
                if ($v == '') unset($eval_contents[$k]);
            }

            if (count($eval_contents) < $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '自定义好评与指定的单数不匹配']));
            }
            $setting_eval = [];
            foreach ($eval_contents as $v) {
                $setting_eval[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'content' => $v
                ];
            }

            $tmp_price = SETTING_EVAL_PRICE;
            if (array_key_exists('setting_eval', $discount_list) && $discount_list['setting_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自定义好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 图文好评
        $pic_rewards = 0;
        $this->write_db->delete('rqf_setting_img', ['trade_id' => $trade_id]);
        $setting_picture = $this->input->post('setting_picture');
        if ($setting_picture) {
            $eval_type = 4;
            $setting_pic_color = $this->input->post('setting_pic_color');
            $setting_pic_size = $this->input->post('setting_pic_size');
            $setting_pic_list = $this->input->post('setting_pic_list');
            $setting_pic_content = $this->input->post('setting_pic_content');
            if (count($setting_pic_list) != intval($trade_info->total_num) || count($setting_pic_content) != intval($trade_info->total_num)) {
                exit(json_encode(['error' => 1, 'message' => '图文好评上传的图片、评论，与指定的单数不匹配']));
            }
            // 记录评价内容
            $setting_pic_recode = [];
            foreach ($setting_pic_list as $key => $pic_list) {
                $setting_pic_recode[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'color' => $setting_pic_color[$key],
                    'size' => $setting_pic_size[$key],
                    'img1' => isset($pic_list[0]) ? trim($pic_list[0]) : '',
                    'img2' => isset($pic_list[1]) ? trim($pic_list[1]) : '',
                    'img3' => isset($pic_list[2]) ? trim($pic_list[2]) : '',
                    'img4' => isset($pic_list[3]) ? trim($pic_list[3]) : '',
                    'img5' => isset($pic_list[4]) ? trim($pic_list[4]) : '',
                    'video' => '',
                    'content' => $setting_pic_content[$key]
                ];
            }
            // 记录增值服务
            $tmp_price = 4;
            $pic_rewards = 2;       // 用户分成
            if (array_key_exists('setting_picture', $discount_list) && $discount_list['setting_picture'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_picture'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_picture',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '图文好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 视频评价
        $setting_video = $this->input->post('setting_video');
        if ($setting_video) {
            $eval_type = 5;
            $setting_video_list = $this->input->post('setting_video_list');
            $setting_video_color = $this->input->post('setting_video_color');
            $setting_video_size = $this->input->post('setting_video_size');
            $setting_video_pic_list = $this->input->post('setting_video_pic_list');
            $setting_video_content = $this->input->post('setting_video_content');
            if (count($setting_video_list) != intval($trade_info->total_num)) {
                exit(json_encode(['error' => 1, 'message' => '视频评价上传的视频段数，与指定的单数不匹配']));
            }
            // 记录评价内容
            $setting_pic_recode = [];
            foreach ($setting_video_list as $key => $video) {
                $setting_pic_recode[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'color' => $setting_video_color[$key],
                    'size' => $setting_video_size[$key],
                    'img1' => isset($setting_video_pic_list[$key][0]) ? trim($setting_video_pic_list[$key][0]) : '',
                    'img2' => isset($setting_video_pic_list[$key][1]) ? trim($setting_video_pic_list[$key][1]) : '',
                    'img3' => isset($setting_video_pic_list[$key][2]) ? trim($setting_video_pic_list[$key][2]) : '',
                    'img4' => isset($setting_video_pic_list[$key][3]) ? trim($setting_video_pic_list[$key][3]) : '',
                    'img5' => isset($setting_video_pic_list[$key][4]) ? trim($setting_video_pic_list[$key][4]) : '',
                    'video' => $video,
                    'content' => $setting_video_content[$key]
                ];
            }
            // 记录增值服务
            $tmp_price = 6;
            $pic_rewards = 3;       // 用户分成
            if (array_key_exists('setting_video', $discount_list) && $discount_list['setting_video'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_video'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_video',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '视频评价'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        $trade_point = bcadd($trade_info->order_fee_point, $trade_info->order_dis_point, 4);
        $trade_point = bcadd($trade_point, $service_point, 4);
        $trade_info_upd = [
            'add_reward' => bcmul($add_reward_point, ADD_REWARD_POINT_PERCENT, 4),
            'pic_reward' => $pic_rewards,
            'recommend_weight' => $add_speed,
            'extend_cycle' => $extend_cycle,
            'first_check' => $first_check_val,
            'service_point' => $service_point,
            'trade_point' => $trade_point,
            'trade_step' => 5,
            'plat_refund' => $plat_refund_val,
            'start_time' => $start_time,
            'interval' => $interval_param,
            'eval_type' => $eval_type,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'no_print' => $no_print
        ];

        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 4];
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        if ($this->write_db->affected_rows()) {
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_id]);
            $this->write_db->insert_batch('rqf_trade_service', $trade_service);
            if ($setting_eval && $eval_contents) {
                $this->write_db->insert_batch('rqf_setting_eval', $setting_eval);
            }
            if (($setting_picture || $setting_video) && $setting_pic_recode) {
                $this->write_db->insert_batch('rqf_setting_img', $setting_pic_recode);
            }
        }
        $this->write_db->close();

        exit(json_encode(['error' => 0]));
    }

    /**
     * 文字好评第五步
     */
    private function char_eval_step5($trade_info)
    {

        $data = $this->data;

        $user_id = $this->session->userdata('user_id');

        $data['trade_info'] = $trade_info;

        $data['user_info'] = $this->user->get_user_info($user_id);

        // $data['trade_select'] = $this->trade->get_trade_select($trade_info);

        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['shop_ww'] = $shop_info->shop_ww;

        $this->load->view('trade/char_eval_step5', $data);
    }

    /**
     * 文字好评第五步提交
     */
    public function char_eval_step5_submit()
    {
        $this->write_db = $this->load->database('write', true);
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
        $trade_info = $this->trade->get_trade_info($trade_id);
        if (!in_array($trade_info->trade_status, ['0', '5'])) {
            redirect('center');
            return;
        }

        if (in_array($trade_info->plat_id, [1, 2])) {
            $shop_info_sql = "select * from rqf_bind_shop where id = {$trade_info->shop_id}";
            $shop_info = $this->write_db->query($shop_info_sql)->row();
            if (empty($shop_info)) {
                redirect('center');
                return;
            }
            $show_ww = $shop_info->shop_ww;
            $auth_info2 = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 2])->row();
            if (empty($auth_info2)) {
                $auth_info = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 1])->row();
                if (empty($auth_info)) {
                    $result = $this->ddx($show_ww);
                    if ($result['code'] == 0) {
                        $insert_array['shop_ww'] = $show_ww;
                        $insert_array['auth_type'] = 1;
                        $insert_array['is_order'] = $result['is_order'];
                        $insert_array['expires_time'] = strtotime($result['expires_time']);
                        $insert_array['deadline'] = strtotime($result['deadline']);
                        $this->write_db->insert('rqf_shop_auth_info', $insert_array);
                    } else {
                        error_back($result['msg']);
                        return;
                    }
                } else {
                    if ($auth_info->expires_time < time()) {
                        error_back('授权过期，需要重新授权');
                        return;
                    }
                }
            } else {
                if ($auth_info2->expires_time < time()) {
                    error_back('授权过期，需要重新授权');
                    return;
                }
            }
        }

        // 使用押金
        // $has_deposit = $this->input->post('has_deposit');
        $has_deposit = true;

        // 使用金币
        // $has_point = $this->input->post('has_point');
        $has_point = true;

        // 账户押金
        $user_deposit = 0;

        if ($has_deposit)
            $user_deposit = $user_info->user_deposit;

        // 账户金币
        $user_point = 0;

        if ($has_point)
            $user_point = $user_info->user_point;

        // 活动押金
        $trade_deposit = $trade_info->trade_deposit;

        // 活动金币
        $trade_point = $trade_info->trade_point;

        // 金币支付
        $pay_point = 0;

        // 押金支付
        $pay_deposit = 0;

        // 押金转金币
        $deposit_to_point = 0;

        // 第三方支付
        $pay_third = 0;
        if (bccomp($trade_point, $user_point, 2) > 0) {
            error_back('账户金币不足!');
            return;
        } else {
            $pay_point = $trade_point;
        }

        $trade_deposit = bcadd($trade_deposit, $deposit_to_point, 2);
        if (bccomp($trade_deposit, $user_deposit, 2) > 0) {
            error_back('账户押金不足!');
            return;
        } else {
            $pay_deposit = $trade_deposit;
        }

        if ($pay_third == 0) {
            $sql = "update rqf_trade_info
                      set trade_step = 6, trade_status = 1, pay_point = {$pay_point}, pay_deposit = {$pay_deposit}, pay_third = {$pay_third}, pay_time = ?
                    where id = {$trade_id} and user_id = {$user_id} and trade_step = 5 and trade_status in (0,5)";
            $this->write_db->query($sql, [time()]);
            if ($this->write_db->affected_rows()) {
                // 押金转金币
                if ($deposit_to_point > 0) {
                    $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
                    $user_deposit = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 200,
                        'score_nums' => '-' . $deposit_to_point,
                        'last_score' => bcsub($user_info->user_deposit, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_deposit,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                    $user_point = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 100,
                        'score_nums' => '+' . $deposit_to_point,
                        'last_score' => bcadd($user_info->user_point, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_point,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];

                    $this->write_db->insert('rqf_bus_user_point', $user_point);

                    $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$deposit_to_point, $deposit_to_point, $user_id]);
                }

                $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

                // 冻结押金
                $user_deposit = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_deposit,
                    'last_score' => bcsub($user_info->user_deposit, $trade_info->trade_deposit, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_deposit,
                    'last_frozen_score' => bcadd($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];
                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                // 冻结金币
                $user_point = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_point,
                    'last_score' => bcsub($user_info->user_point, $trade_info->trade_point, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_point,
                    'last_frozen_score' => bcadd($user_info->frozen_point, $trade_info->trade_point, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];
                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $sql = 'update rqf_users
                        set user_deposit = user_deposit - ?,
                            frozen_deposit = frozen_deposit + ?,
                            user_point = user_point - ?,
                            frozen_point = frozen_point + ?
                            where id = ?';
                $this->write_db->query($sql, [$trade_info->trade_deposit, $trade_info->trade_deposit, $trade_info->trade_point, $trade_info->trade_point, $user_id]);

                // 操作日志
                $action_info = [
                    'trade_id' => $trade_info->id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 1,
                    'trade_note' => '活动已支付',
                    'add_time' => time(),
                    'created_user' => $this->session->userdata('nickname'),
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $action_info);
            }

            redirect('trade/step/' . $trade_id);
            return;
        }
    }

    /**
     * 文字好评第六步
     */
    private function char_eval_step6($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $this->load->view('trade/char_eval_step6', $data);
    }

    /**
     * 超级搜索任务第二步
     */
    private function super_char_eval_step2($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 浏览任务
        $trade_scan = $this->trade->get_trade_scan($trade_info->id);
        $data['app_scans'] = $trade_scan['app'];


        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        $this->load->view('trade/super_char_eval_step2', $data);
    }

    public function super_char_eval_step2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);

        $num = intval($this->input->post('num'));

        $goods_name = $this->input->post('goods_name');
        $shop_name = $this->input->post('shop_name');
        $goods_url = $this->input->post('goods_url');
        $price = $this->input->post('price');
        $kwds = $this->input->post('kwd');
        $low_price = $this->input->post('low_price');
        $high_price = $this->input->post('high_price');
        $discount_text = $this->input->post('discount_text');
        $area = $this->input->post('area');
        $goods_cate = $this->input->post('goods_cate');
        $order_way = $this->input->post('order_way');
        $img1_base64 = $this->input->post('img1_base64');
        $img2_base64 = $this->input->post('img2_base64');
        $img3_base64 = $this->input->post('img3_base64');
        $img4_base64 = $this->input->post('img4_base64');
        $img5_base64 = $this->input->post('img5_base64');
        $img6_base64 = $this->input->post('img6_base64');
        $img7_base64 = $this->input->post('img7_base64');
        $img8_base64 = $this->input->post('img8_base64');
        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }
        $old_trade_scan = $this->trade->get_trade_scan($trade_id);
        $scans = [];
        for ($i = 0; $i < $num; $i++) {
            $name = $goods_name[$i];
            $current_shop_name = $shop_name[$i];
            $url = $goods_url[$i];
            $kwd = $kwds[$i];
            if ($name == '') {
                echo json_encode(['code' => 2, 'msg' => '请输入第' . ($i + 1) . '商品名称']);
                return;
            }

            if ($current_shop_name == '') {
                echo json_encode(['code' => 2, 'msg' => '请输入第' . ($i + 1) . '店铺名称']);
                return;
            }

            if ($url == '') {
                echo json_encode(['code' => 3, 'msg' => '请输入第' . ($i + 1) . '商品链接']);
                return;
            }

            if (empty($kwd)) {
                echo json_encode(['code' => 4, 'msg' => '第' . ($i + 1) . '个手机淘宝关键词不能为空']);
                return;
            }
            $item_id = $this->trade->get_item_id($url, $trade_info->plat_id);
            if (empty($item_id)) {
                echo json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']);
                return;
            }
            $scans[$i]['trade_id'] = $trade_id;
            $scans[$i]['trade_sn'] = $trade_info->trade_sn;
            $scans[$i]['plat_id'] = 3;
            $scans[$i]['goods_url'] = $url;
            $scans[$i]['goods_name'] = $name;
            $scans[$i]['shop_name'] = $current_shop_name;
            $scans[$i]['price'] = $price[$i];
            $image_result = [];
            switch ($i) {
                case 0:
                    $image_result = $this->get_search_images($img1_base64, $img2_base64, $old_trade_scan, $i);
                    break;
                case 1:
                    $image_result = $this->get_search_images($img3_base64, $img4_base64, $old_trade_scan, $i);
                    break;
                case 2:
                    $image_result = $this->get_search_images($img5_base64, $img6_base64, $old_trade_scan, $i);
                    break;
                case 3:
                    $image_result = $this->get_search_images($img7_base64, $img8_base64, $old_trade_scan, $i);
                    break;
            }
            $scans[$i]['search_img'] = $image_result[0];
            $scans[$i]['search_img2'] = $image_result[1];
            $scans[$i]['item_id'] = $item_id;
            $scans[$i]['kwd'] = $kwd;
            $scans[$i]['low_price'] = $low_price[$i];
            $scans[$i]['high_price'] = $high_price[$i];
            $scans[$i]['area'] = $area[$i];
            $scans[$i]['discount'] = $discount_text[$i] != '' ? rtrim($discount_text[$i], ',') : '';
            $scans[$i]['order_way'] = $order_way[$i];
            $scans[$i]['goods_cate'] = $goods_cate[$i];
        }

        $trade_info_upd = ['trade_step' => 3];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->delete('rqf_trade_scan', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_scan', $scans);
        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    private function get_search_images($img1_base64, $img2_base64, $old_trade_scan, $i)
    {
        if ($img1_base64) {
            $app_img1 = $this->base64->to_img($img1_base64, UPLOAD_TRADE_INFO_DIR);
            $search_img1 = CDN_URL . $app_img1;
            qiniu_upload(ltrim($app_img1, '/'));
        } else {
            $old_app_scan_1 = $old_trade_scan['app'][$i];
            $search_img1 = $old_app_scan_1->search_img;
        }

        if ($img2_base64) {
            $app_img2 = $this->base64->to_img($img2_base64, UPLOAD_TRADE_INFO_DIR);
            $search_img2 = CDN_URL . $app_img2;
            qiniu_upload(ltrim($app_img2, '/'));
        } else {
            $old_app_scan_1 = $old_trade_scan['app'][$i];
            $search_img2 = $old_app_scan_1->search_img2;
        }
        return [$search_img1, $search_img2];
    }

    /**
     * 超级搜索任务第三步
     */
    private function super_char_eval_step3($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['taobao_search'] = $trade_search['pc_taobao'];
        $data['tmall_search'] = $trade_search['pc_tmall'];
        $data['app_search'] = $trade_search['app'];
        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        // 任务要求
        $task_requirements = unserialize($data['trade_item']->task_requirements);
        unset($data['trade_item']->task_requirements);
        if (empty($task_requirements)) {
            $task_requirements = ['is_post' => 0, 'chat' => 1, 'coupon' => 0, 'coupon_link' => '', 'credit' => 0];
        }
        $data['task_requirements'] = $task_requirements;

        $this->load->view('trade/super_char_eval_step3', $data);
    }

    /**
     * 超级搜索任务第三步提交(1)
     */
    public function super_char_eval_step3_1_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        $old_trade_search = $this->trade->get_trade_search($trade_id);
        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }

        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $price = floatval($this->input->post('price'));
        $show_price = $this->input->post('show_price');
        $buy_num = intval($this->input->post('buy_num'));
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        if ($goods_name == '') {
            echo json_encode(['code' => 2, 'msg' => '请输入商品名称']);
            return;
        }

        if ($goods_url == '') {
            echo json_encode(['code' => 3, 'msg' => '请输入商品链接']);
            return;
        }
        if (empty($item_id)) {
            echo json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']);
            return;
        }
        if (empty($price)) {
            echo json_encode(['code' => 4, 'msg' => '请输入商品价格']);
            return;
        }

        if ($buy_num < 1) {
            echo json_encode(['code' => 5, 'msg' => '请输入购买件数']);
            return;
        }

        $trade_search = [];

        $goods_img = '';

        $pc_taobao = trim($this->input->post('pc_taobao'));
        if ($pc_taobao) {
            $tb_kwd = $this->input->post('tb_kwd');
            $tb_classify1 = $this->input->post('tb_classify1');
            $tb_classify2 = $this->input->post('tb_classify2');
            $tb_classify3 = $this->input->post('tb_classify3');
            $tb_classify4 = $this->input->post('tb_classify4');
            $tb_low_price = $this->input->post('tb_low_price');
            $tb_high_price = $this->input->post('tb_high_price');
            $tb_area = $this->input->post('tb_area');
            $tb_img_base64 = $this->input->post('tb_img_base64');

            // 关键词验证
            foreach ($tb_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 1,
                'low_price' => $tb_low_price,
                'high_price' => $tb_high_price,
                'area' => $tb_area
            ];

            if ($tb_img_base64) {
                $tb_img = $this->base64->to_img($tb_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tb_img;
                qiniu_upload(ltrim($tb_img, '/'));
            } else {
                $old_taobao_search_0 = $old_trade_search['pc_taobao'][0];
                $tmp_info['search_img'] = $old_taobao_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_taobao_search = $old_trade_search['pc_taobao'];

            foreach ($tb_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tb_classify1[$k];
                $tmp_info['classify2'] = $tb_classify2[$k];
                $tmp_info['classify3'] = $tb_classify3[$k];
                $tmp_info['classify4'] = $tb_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $pc_tmall = $this->input->post('pc_tmall');
        if ($pc_tmall) {
            $tm_kwd = $this->input->post('tm_kwd');
            $tm_classify1 = $this->input->post('tm_classify1');
            $tm_classify2 = $this->input->post('tm_classify2');
            $tm_classify3 = $this->input->post('tm_classify3');
            $tm_classify4 = $this->input->post('tm_classify4');
            $tm_low_price = $this->input->post('tm_low_price');
            $tm_high_price = $this->input->post('tm_high_price');
            $tm_area = $this->input->post('tm_area');
            $tm_img_base64 = $this->input->post('tm_img_base64');

            // 关键词验证
            foreach ($tm_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '天猫关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 2,
                'low_price' => $tm_low_price,
                'high_price' => $tm_high_price,
                'area' => $tm_area
            ];

            if ($tm_img_base64) {
                $tm_img = $this->base64->to_img($tm_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tm_img;
                qiniu_upload(ltrim($tm_img, '/'));
            } else {
                $old_tmall_search_0 = $old_trade_search['pc_tmall'][0];
                $tmp_info['search_img'] = $old_tmall_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_tmall_search = $old_trade_search['pc_tmall'];

            foreach ($tm_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tm_classify1[$k];
                $tmp_info['classify2'] = $tm_classify2[$k];
                $tmp_info['classify3'] = $tm_classify3[$k];
                $tmp_info['classify4'] = $tm_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $phone_taobao = trim($this->input->post('phone_taobao'));
        if ($phone_taobao) {
            $app_kwd = $this->input->post('app_kwd');
            $app_low_price = $this->input->post('app_low_price');
            $app_high_price = $this->input->post('app_high_price');
            $app_discount_text = $this->input->post('app_discount_text');
            $app_area = $this->input->post('app_area');
            $goods_cate = $this->input->post('goods_cate');
            $app_order_way = $this->input->post('app_order_way');
            $app_img1_base64 = $this->input->post('app_img1_base64');
            $app_img2_base64 = $this->input->post('app_img2_base64');
            $search_plat = ($trade_info->plat_id == '4') ? 4 : 3;           // 手机淘宝、与手机京东共文件，按平台区分
            if ($trade_info->trade_type == '7') {
                $app_kwd = ['--'];
            }

            // 关键词验证
            foreach ($app_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => $search_plat,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => is_null($app_order_way) ? '' : $app_order_way
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            $goods_img = $tmp_info['search_img'];
            $old_app_search = $old_trade_search['app'];
            foreach ($app_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['low_price'] = isset($app_low_price[$k]) ? $app_low_price[$k] : '';
                $tmp_info['high_price'] = isset($app_high_price[$k]) ? $app_high_price[$k] : '';
                $tmp_info['discount'] = isset($app_discount_text[$k]) ? rtrim($app_discount_text[$k], ',') : '';
                $tmp_info['area'] = isset($app_area[$k]) ? $app_area[$k] : '';
                $tmp_info['goods_cate'] = isset($goods_cate[$k]) ? $goods_cate[$k] : '';
                $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        if ($pc_taobao && $pc_tmall) {
            $is_pc = 3;
        } elseif ($pc_taobao && !$pc_tmall) {
            $is_pc = 1;
        } elseif (!$pc_taobao && $pc_tmall) {
            $is_pc = 2;
        } else {
            $is_pc = 0;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        $order_fee_obj = $this->fee->order_fee_obj($trade_info->trade_type, $price * $buy_num);
        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }

        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => $is_pc,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];
        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id];

        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];
        $trade_item_key = ['trade_id' => $trade_id];
        // data update
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);
        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_search', $trade_search);
        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 超级搜索任务第三步提交(2)
     */
    public function super_char_eval_step3_2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }
        // 传入参数
        $is_post = intval($this->input->post('is_post'));
        $chat = intval($this->input->post('chat'));
        $coupon = intval($this->input->post('coupon'));
        $coupon_link = trim($this->input->post('coupon_link'));
        $credit = intval($this->input->post('credit'));
        $post_fee = $is_post ? 0 : POST_FEE;
        $trade_info_upd = ['is_post' => $is_post, 'post_fee' => $post_fee];
        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $task_requirements_str = serialize(['is_post' => $is_post, 'chat' => $chat, 'coupon' => $coupon, 'coupon_link' => $coupon_link, 'credit' => $credit]);      // 活动下单要求
        $trade_item_upd = ['is_post' => $is_post, 'task_requirements' => $task_requirements_str];
        $item_key = ['trade_id' => $trade_id];
        // 数据库更新
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->close();

        echo 0;
    }

    /**
     * 超级搜索任务第三步提交(3)
     */
    public function super_char_eval_step3_3_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info_upd = ['trade_step' => 4];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);

        $this->write_db->close();

        echo 0;
    }

    /**
     * 超级搜索任务第四步
     */
    private function super_char_eval_step4($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $trade_scan = $this->trade->get_trade_scan(intval($trade_info->id));
        $data['app_scans'] = $trade_scan['app'];

        $trade_nums = ['1', '3', '5', '10', '20', '100', '250'];
        $data['is_custom'] = !in_array($trade_info->total_num, $trade_nums);
        $data['custom_val'] = in_array($trade_info->total_num, $trade_nums) ? 1 : $trade_info->total_num;
        // 订单搜索词
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_info->id])->result();

        if (count($trade_search) == 1) {
            $trade_search[0]->num = $trade_info->total_num;
        }
        $data['trade_search'] = $trade_search;
        $data['plat_names'] = ['1' => '淘宝', '2' => '天猫', '3' => '手机淘宝', '4' => '手机京东', '5' => '会场', '14' => '拼多多'];

        $this->load->view('trade/super_char_eval_step4', $data);
    }

    /**
     * 超级搜索任务第四步提交
     */
    public function super_char_eval_step4_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info = $this->trade->get_trade_info($trade_id);
        $total_num = intval($this->input->post('total_num'));
        $total_num_custom = intval($this->input->post('total_num_custom'));
        $nums = $this->input->post('nums');
        $order_prompt = $this->input->post('order_prompt');
        if ($total_num == 0) {
            $total_num = $total_num_custom;
        }
        if ($total_num == 0) {
            echo 2;
            return;
        }
        // 京东链接直拍 没有关键字检索、聚划算、淘抢购
        if (in_array($trade_info->trade_type, ['4', '5', '7'])) {
            $nums[] = $total_num;
        }

        $pc_num = $trade_info->is_pc ? $total_num : 0;
        $phone_num = $trade_info->is_phone ? $total_num : 0;
        // 活动费用
        $order_fee_point = bcmul($trade_info->total_fee, $total_num, 2);
        // 手机端订单分布
        $order_dis_point = $trade_info->is_phone ? bcmul($total_num, ORDER_DIS_PRICE, 2) : 0;
        // 手机端赏金
        $phone_reward = $trade_info->is_phone ? PHONE_REWARD : 0;
        // 每单商品价值
        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 2);
        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 2);
        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 2);
        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 2);
        // 活动押金
        $trade_deposit = bcmul($deposit_subtotal, $total_num, 2);
        // 活动保证金
        $trade_payment = bcmul($payment, $total_num, 2);
        // 活动邮费
        $trade_post_fee = bcmul($trade_info->post_fee, $total_num, 2);
        // 赠送流量单（浏览+加购）
        $award_num = $this->award_num($total_num);
        $trade_info_upd = [
            'total_num' => $total_num,
            'award_num' => $award_num,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'phone_reward' => $phone_reward,
            'order_fee_point' => $order_fee_point,
            'order_dis_point' => $order_dis_point,
            'trade_payment' => $trade_payment,
            'trade_post_fee' => $trade_post_fee,
            'trade_deposit' => $trade_deposit,
            'trade_step' => 5
        ];

        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $trade_item_upd = ['order_prompt' => $order_prompt];
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();
        if (count($nums) != count($trade_search)) {
            echo 3;
            return;
        }

        $nums_sum = 0;
        $trade_search_upd = [];
        foreach ($nums as $k => $v) {
            $nums_sum += $v;
            $trade_search_upd[] = ['id' => $trade_search[$k]->id, 'num' => $v, 'surplus_num' => $v];
        }

        if ($nums_sum != $total_num) {
            echo 3;
            return;
        }

        $item_key = ['trade_id' => $trade_id];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->update_batch('rqf_trade_search', $trade_search_upd, 'id');
        $this->write_db->close();

        echo 0;
    }

    /**
     * 超级搜索任务第五步
     */
    private function super_char_eval_step5($trade_info)
    {
        $data = $this->data;
        $user_id = intval($this->session->userdata('user_id'));
        $data['trade_info'] = $trade_info;
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $data['interval_list'] = $this->conf->interval_list();
        // 活动单数超过10单，删除自定义好评及评价内容
        $this->write_db = $this->load->database('write', true);
        if ($trade_info->total_num > 10) {
            $result = $this->write_db->query('DELETE FROM `rqf_trade_service` WHERE `trade_id` = ? AND `service_name` in ?', [intval($trade_info->id), ['setting_eval', 'setting_picture']]);
            $this->write_db->delete('rqf_setting_eval', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        } elseif ($trade_info->total_num > 5) {
            // 活动单数超过5单，删除图文好评及评价内容
            $this->write_db->delete('rqf_trade_service', ['trade_id' => intval($trade_info->id), 'service_name' => 'setting_picture']);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        }
        $this->write_db->close();
        // 增值服务
        $res = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
        $trade_service = [];
        foreach ($res as $v) {
            $trade_service[$v->service_name] = $v;
        }

        // 平台返款
        // $data['has_plat_refund'] = (isset($trade_service['plat_refund']) || (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800));
        $data['has_plat_refund'] = true;
        // $data['plat_refund_disabled'] = (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800);
        $data['plat_refund_disabled'] = true;           // 强制平台返款
        $data['plat_refund_percent'] = fun_plat_refund_percent(bcmul($trade_info->price, $trade_info->buy_num, 4));        // 获取任务订单退款手续费
        // 商家返款
        $data['has_bus_refund'] = isset($trade_service['bus_refund']);
        // 提升完成活动速度
        $data['add_speed_val'] = isset($trade_service['add_speed']) ? $trade_service['add_speed']->price : 0;
        // 加赏活动佣金
        $data['has_add_reward'] = isset($trade_service['add_reward']);
        $data['add_reward_val'] = isset($trade_service['add_reward']) ? intval($trade_service['add_reward']->price) : 3;
        // 优先审单
        $data['has_first_check'] = isset($trade_service['first_check']);
        // 千人千面设置 地域限制
        $data['area_limit'] = isset($trade_service['area_limit']);
        $data['area_limit_list'] = isset($trade_service['area_limit']) ? explode(',', $trade_service['area_limit']->param) : [];
        // 千人千面设置 性别选择
        $data['sex_limit'] = isset($trade_service['sex_limit']);
        $data['sex_limit_val'] = isset($trade_service['sex_limit']) ? $trade_service['sex_limit']->param : '0';
        // 指定平台新注册买手接单
        $data['has_newhand'] = isset($trade_service['newhand']) ? $trade_service['newhand']->param : '';
        // 仅限钻级别的买号可接此活动
        $data['reputation_limit'] = isset($trade_service['reputation_limit']) ? 2 : 1;
        // 仅限淘气值1000以上买号可接此活动
        $data['taoqi_limit'] = isset($trade_service['taoqi_limit']) ? 2 : 1;
        // 定时发布
        $data['has_set_time'] = isset($trade_service['set_time']);
        $data['set_time_val'] = isset($trade_service['set_time']) ? $trade_service['set_time']->param : '';
        // 定时结束任务
        $data['has_set_over_time'] = isset($trade_service['set_over_time']);
        $data['set_over_time_val'] = isset($trade_service['set_over_time']) ? $trade_service['set_over_time']->param : '';
        // 分时发布
        $data['custom_time_price'] = isset($trade_service['custom_time_price']) ? json_decode($trade_service['custom_time_price']->param, true) : [];
        $data['set_time_pre_val'] = intval($trade_info->start_time) > 0 ? date('Y-m-d', $trade_info->start_time) : date('Y-m-d');
        // 分时单数
        $interval_nums = [1, 2, 5, 10, 20, 50];
        foreach ($interval_nums as $k => $v) {
            if ($v >= $trade_info->total_num && ($v != 1)) {
                unset($interval_nums[$k]);
            }
        }

        $data['interval_nums'] = $interval_nums;
        // 间隔发布
        /**
         * $data['has_set_interval'] = (isset($trade_service['set_interval'])) || ($trade_info->total_num >= 20);
         * if ($trade_info->total_num == 1) {
         * $data['has_set_interval'] = false;
         * }
         * $data['set_interval_disabled'] = (($trade_info->total_num >= 20) || ($trade_info->total_num == 1));
         * */
        $data['set_interval_disabled'] = false;
        $data['has_set_interval'] = isset($trade_service['set_interval']);

        if (isset($trade_service['set_interval'])) {
            $params = explode('|', $trade_service['set_interval']->param);
            $set_interval_val = $params[0];
            $interval_num_val = $params[1];
        } else {
            $set_interval_val = '10m';
            $interval_num_val = '1';
        }
        $data['set_interval_val'] = $set_interval_val;
        $data['interval_num_val'] = $interval_num_val;

        // 包裹重量
        $data['set_weight_val'] = isset($trade_service['set_weight']) ? $trade_service['set_weight']->param : '2';
        // 快递选择
        $data['set_shipping'] = isset($trade_service['set_shipping']) ? $trade_service['set_shipping']->param : $data['trade_select']['shipping_type'];
        // 默认好评
        $data['has_default_eval'] = isset($trade_service['default_eval']);
        // 自由好评
        $data['has_free_eval'] = isset($trade_service['free_eval']);
        // 关键词好评
        $data['has_kwd_eval'] = isset($trade_service['kwd_eval']);
        // 关键词列表
        $data['kwds'] = isset($trade_service['kwd_eval']) ? json_decode($trade_service['kwd_eval']->param, true) : ['', '', ''];
        // 自定义好评
        $data['has_setting_eval'] = isset($trade_service['setting_eval']);
        // 自定义好评内容
        $eval_contents = [];
        if ($trade_info->total_num <= 10) {
            $setting_eval_res = $this->db->get_where('rqf_setting_eval', ['trade_id' => intval($trade_info->id)])->result();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $eval_contents[] = '';
            }
            if ($setting_eval_res) {
                foreach ($setting_eval_res as $k => $v) {
                    if ($k >= $trade_info->total_num) {
                        continue;
                    }

                    $eval_contents[$k] = $v->content;
                }
            }
            $data['setting_eval_disabled'] = false;
        } else {
            $data['setting_eval_disabled'] = true;
        }
        $data['eval_contents'] = $eval_contents;
        // 图文好评
        $data['has_setting_picture'] = (isset($trade_service['setting_picture']) && $trade_info->trade_type != '140') ? true : false;
        $txt_images_list = [];
        if ($trade_info->total_num <= 5 && $trade_info->trade_type != '140') {
            $trade_item_res = $this->db->get_where('rqf_trade_item', ['trade_id' => intval($trade_info->id)])->row();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $txt_images_list[] = ['color' => $trade_item_res->color, 'size' => $trade_item_res->size, 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => ''];
            }

            if ($data['has_setting_picture']) {
                $txt_image_res = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id)])->result();
                if ($txt_image_res) {
                    foreach ($txt_image_res as $k => $v) {
                        if ($k >= $trade_info->total_num) {
                            continue;
                        }
                        $txt_images_list[$k] = ['color' => $v->color, 'size' => $v->size, 'img1' => $v->img1, 'img2' => $v->img2, 'img3' => $v->img3, 'img4' => $v->img4, 'img5' => $v->img5, 'content' => $v->content];
                    }
                }
            }
        }
        $data['txt_image_list'] = $txt_images_list;

        // 视频评价
        $data['has_setting_video'] = (isset($trade_service['setting_video']) && $trade_info->trade_type != '140') ? true : false;
        $video_image_list = [];
        if ($trade_info->total_num <= 5 && $trade_info->trade_type != '140') {
            $trade_item_res = $this->db->get_where('rqf_trade_item', ['trade_id' => intval($trade_info->id)])->row();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $video_image_list[] = ['color' => $trade_item_res->color, 'size' => $trade_item_res->size, 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => '', 'video' => ''];
            }

            if ($data['has_setting_video']) {
                $video_image_res = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id)])->result();
                if ($video_image_res) {
                    foreach ($video_image_res as $k => $v) {
                        if ($k >= $trade_info->total_num) {
                            continue;
                        }
                        $video_image_list[$k] = ['color' => $v->color, 'size' => $v->size, 'img1' => $v->img1, 'img2' => $v->img2, 'img3' => $v->img3, 'img4' => $v->img4, 'img5' => $v->img5, 'content' => $v->content, 'video' => $v->video];
                    }
                }
            }
        }
        $data['video_image_list'] = $video_image_list;

        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 4);
        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 4);
        $data['payment'] = $payment;
        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 4);
        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 4);
        $data['deposit_subtotal'] = $deposit_subtotal;
        // 金币小计
        $point_subtotal = $trade_info->total_fee;
        if ($trade_info->is_phone) {
            $point_subtotal = bcadd($point_subtotal, ORDER_DIS_PRICE, 4);
        }
        $data['point_subtotal'] = $point_subtotal;
        // 快递类型
        $data['shipping_type_list'] = $this->conf->get_shipping_type_list();
        // 人气权重

        $this->load->model('Traffic_Model', 'traffic');
        if (in_array($trade_info->trade_type, ['4', '5']) || in_array($trade_info->plat_id, ['4', '14'])) {
            $service_list = [];
        } else {
            if (isset($trade_service['traffic_list'])) {
                $service_list = unserialize($trade_service['traffic_list']->param);
            } else {
                if (count($trade_service) <= 0) {
                    $service_list = ['normal_price' => 4, 'collect_goods' => 2];   // 默认勾选浏览商品、收藏商品
                } else {
                    $service_list = [];
                }
            }
        }
        $data['traffic_arr'] = $this->traffic->normal_task_traffic_show($trade_info->total_num, $service_list, time());
        $trade_scan = $this->trade->get_trade_scan(intval($trade_info->id));
        $data['app_scans'] = $trade_scan['app'];
        // 增值服务优惠折扣
        $discount_list = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        foreach ($discount_list as $item) {
            $data['discount'][$item->service_name] = intval($item->discount);
        }

        $this->load->view('trade/super_char_eval_step5', $data);
    }

    /**
     * 超级搜索任务第五步提交
     */
    public function super_char_eval_step5_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }
        $this->write_db = $this->load->database('write', true);
        $trade_info = $this->trade->get_trade_info($trade_id);
        $get_trade_scan = $this->trade->get_trade_scan($trade_id);
        $trade_scans = $get_trade_scan['app'];
        // 活动押金
        $service_point = 0;
        $trade_service = [];
        if (count($trade_scans) > 0) {
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'super_scan',
                'price' => SUPER_SCAN_PRICE,
                'num' => bcmul(count($trade_scans), $trade_info->total_num, 2),
                'pay_point' => round(bcmul(bcmul(SUPER_SCAN_PRICE, count($trade_scans), 4), $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '超级浏览任务'
            ];
            $trade_service[] = $tmp_info;
            // 增加活动押金
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 增值服务优惠折扣
        $discount_query = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        $discount_list = [];
        foreach ($discount_query as $item) {
            $discount_list[$item->service_name] = intval($item->discount);
        }
        // 快速返款
        $plat_refund = $this->input->post('plat_refund');
        if ($plat_refund || (bcmul($trade_info->price, $trade_info->buy_num, 4) <= 800)) {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, fun_plat_refund_percent($tmp_price), 4);
            if (array_key_exists('plat_refund', $discount_list) && $discount_list['plat_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['plat_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'plat_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '快速返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 1;
        } else {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, BUS_REFUND_PERCENT, 4);
            if (array_key_exists('bus_refund', $discount_list) && $discount_list['bus_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['bus_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'bus_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '商家返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 0;
        }

        // 提升完成活动速度
        $add_speed = intval($this->input->post('add_speed'));
        if ($add_speed) {
            if (!in_array($add_speed, [10, 20, 30])) {
                $add_speed = 10;
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_speed',
                'price' => $add_speed,
                'num' => 1,
                'pay_point' => $add_speed,
                'param' => '',
                'comments' => '提升完成活动速度'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_speed = 0;
        }

        // 加赏活动佣金
        $add_reward = $this->input->post('add_reward');
        $add_reward_point = intval($this->input->post('add_reward_point'));
        if ($add_reward) {
            $add_reward_point = max($add_reward_point, 2);
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward',
                'price' => $add_reward_point,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($add_reward_point, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '加赏活动佣金'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_reward_point = 0;
        }

        // 优先审核
        $first_check = $this->input->post('first_check');
        if ($first_check) {
            $first_check_val = 1;
            $tmp_price = FIRST_CHECK_PRICE;
            if (array_key_exists('first_check', $discount_list) && $discount_list['first_check'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['first_check'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_check',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => '',
                'comments' => '优先审核'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $first_check_val = 0;
        }

        // 定时发布
        $set_time = $this->input->post('set_time');
        $set_time_val = $this->input->post('set_time_val');
        if ($set_time && $set_time_val) {
            if (strtotime($set_time_val) <= time()) {
                exit(json_encode(['error' => 1, 'message' => '设置的定时发布时间应大于当前时间']));
            }
            $tmp_price = 3;
            if (array_key_exists('set_time', $discount_list) && $discount_list['set_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_time_val,
                'comments' => '定时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $start_time = strtotime($set_time_val);
        } else {
            $start_time = 0;
        }
        // 定时结束任务
        $set_over_time = $this->input->post('set_over_time');
        $set_over_time_val = $this->input->post('set_over_time_val');
        if ($set_over_time && $set_over_time_val) {
            $compare_time = ($set_time && $set_time_val) ? strtotime($set_time_val) : time();
            if (strtotime($set_over_time_val) <= $compare_time + 3600) {
                exit(json_encode(['error' => 1, 'message' => '结束时间、与活动时间至少错开一个小时']));
            }
            $tmp_price = 2;
            if (array_key_exists('set_over_time', $discount_list) && $discount_list['set_over_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_over_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_over_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_over_time_val,
                'comments' => '定时结束'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 活动分时发布、与间隔发布 目前暂定为互斥关系 二选一
        $interval_param = '';
        $custom_time_price = $this->input->post('custom_time_price');       // 分时发布
        $set_interval = $this->input->post('set_interval');                 // 间隔发布
        if ($custom_time_price && $set_interval) {
            exit(json_encode(['error' => 1, 'message' => '分时发布、与间隔发布暂不可以同时发布 ，请确认']));
        } elseif ($custom_time_price) {
            // 分时发布
            $total_nums = 0;
            $custom_time_price_list = [];
            // 查看分时发布开始时间
            $set_time_pre_val = empty(trim($this->input->post('set_time_pre_val'))) ? strtotime('+1 hour') : strtotime(trim($this->input->post('set_time_pre_val')));
            $reference_hour = date('H', $set_time_pre_val);
            foreach ($custom_time_price as $item) {
                if (intval($item['nums']) <= 0) continue;
                if (intval($item['hour']) < $reference_hour) {
                    exit(json_encode(['error' => 1, 'message' => '分时发布点应大于当前时间、或定时发布的时间，请确认']));
                }
                $total_nums += intval($item['nums']);
                $custom_time_price_list[$item['hour']] = $item['nums'];
            }
            if ($total_nums != $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '总活动单数与时间点单数累加值应一致，请确认']));
            }
            $tmp_price = CUSTOM_TIME_PRICE;
            if (array_key_exists('custom_time_price', $discount_list) && $discount_list['custom_time_price'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['custom_time_price'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'custom_time_price',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => json_encode($custom_time_price_list),
                'comments' => '分时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $pc_num = 0;
            $phone_num = 0;
            $start_time = $set_time_pre_val;
        } elseif ($set_interval) {
            // 间隔发布
            $set_interval_val = $this->input->post('set_interval_val');
            $interval_num = $this->input->post('interval_num');
            $interval_list = $this->conf->interval_list();
            $interval_list_keys = array_keys($interval_list);
            if (!in_array($set_interval_val, $interval_list_keys)) {
                $set_interval_val = $interval_list_keys[0];
            }
            $tmp_price = SET_INTERVAL_PRICE;
            if (array_key_exists('set_interval', $discount_list) && $discount_list['set_interval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_interval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_interval',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => "{$set_interval_val}|{$interval_num}",
                'comments' => '间隔发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $interval_param = "{$set_interval_val}|{$interval_num}";
            $pc_num = 0;
            $phone_num = 0;
        } else {
            $pc_num = $trade_info->is_pc ? $trade_info->total_num : 0;
            $phone_num = $trade_info->is_phone ? $trade_info->total_num : 0;
        }

        // 选择包裹配送方式
        $no_print = $trade_info->no_print;
        $shipping = $this->input->post('shipping');
        if (is_null($shipping) || empty(trim($shipping))) {
            exit(json_encode(['error' => 1, 'message' => '请选择预备配送的快递类型']));
        }
        $shipping_info = $this->conf->get_shipping_type_list($shipping);
        if ($shipping_info && $shipping_info['name']) {
            //快递折扣
            if (array_key_exists('set_shipping', $discount_list) && $discount_list['set_shipping'] < 100) {
                $shipping_info['price'] = round(bcmul($shipping_info['price'], $discount_list['set_shipping'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_shipping',
                'price' => $shipping_info['price'],
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($shipping_info['price'], $trade_info->total_num, 4),
                'param' => $shipping,
                'comments' => $shipping_info['name'] . '配送'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            if ($shipping == 'self') {
                $no_print = (intval($trade_info->no_print) <= 0) ? 2 : intval($trade_info->no_print);
            } else {
                $no_print = 0;
            }
        }

        // 自定义包裹重量(必选)
        $set_weight_val = $this->input->post('set_weight_val');
        $tmp_info = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_info->trade_sn,
            'service_name' => 'set_weight',
            'price' => SET_WEIGHT_PRICE,
            'num' => 1,
            'pay_point' => SET_WEIGHT_PRICE,
            'param' => $set_weight_val,
            'comments' => '自定义包裹重量'
        ];
        $trade_service[] = $tmp_info;
        $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);

        // 更新商品记录商品重量
        $this->write_db->update('rqf_trade_item', ['weight' => $set_weight_val], ['trade_id' => $trade_id]);

        // 延长买家购物周期
        $extend_cycle = intval($this->input->post('extend_cycle'));
        if ($extend_cycle && in_array($extend_cycle, [2, 3])) {
            if ($extend_cycle == 2) {
                $tmp_price = EXTEND_CYCLE1_PRICE;
            } else {
                $tmp_price = EXTEND_CYCLE2_PRICE;
            }
            if (array_key_exists('extend_cycle', $discount_list) && $discount_list['extend_cycle'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['extend_cycle'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'extend_cycle',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $extend_cycle,
                'comments' => '延长买家购物周期'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $extend_cycle = 0;
        }

        // 限制买号重复进店下单
        $shopping_end = intval($this->input->post('shopping_end'));
        if ($shopping_end) {
            $tmp_price = SHOPPING_END_BOX;
            if (array_key_exists('shopping_end', $discount_list) && $discount_list['shopping_end'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['shopping_end'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'shopping_end',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $shopping_end,
                'comments' => '限制买号重复进店下单'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 指定平台新注册买手接单
        $newhand = intval($this->input->post('newhand'));
        if ($newhand) {
            if ($newhand == 1) {
                $tmp_price = 1;
                $comments = '指定平台1个月内新注册下单';
            } elseif ($newhand == 2) {
                $tmp_price = 2;
                $comments = '指定平台15天内新注册下单';
            } else {
                $tmp_price = 3;
                $comments = '指定平台7天内新注册下单';
            }
            if (array_key_exists('newhand', $discount_list) && $discount_list['newhand'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['newhand'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'newhand',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $newhand,
                'comments' => $comments
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 地域限制
        $area_limit = $this->input->post('area_limit');
        if ($area_limit == '1') {
            $area_limit_city = $this->input->post('area_limit_city');
            $tmp_price = AREA_LIMIT;
            if (array_key_exists('area_limit', $discount_list) && $discount_list['area_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['area_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'area_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => implode(',', $area_limit_city),
                'comments' => '千人千面－地域限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 性别限制
        $sex_limit = $this->input->post('sex_limit');
        if ($sex_limit == '1') {
            $sex_limit_val = $this->input->post('sex_limit_val');
            $tmp_price = SEX_LIMIT;
            if (array_key_exists('sex_limit', $discount_list) && $discount_list['sex_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['sex_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'sex_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $sex_limit_val,
                'comments' => '千人千面－性别限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 钻级别的买号
        $reputation_limit = $this->input->post('reputation_limit');
        if ($reputation_limit == '1') {
            $tmp_price = REPUTATION_LIMIT;
            if (array_key_exists('reputation_limit', $discount_list) && $discount_list['reputation_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['reputation_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'reputation_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 5,           // 标记5以上就是钻级
                'comments' => '千人千面－钻级别的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 淘气值1000的买号
        $taoqi_limit = $this->input->post('taoqi_limit');
        if ($taoqi_limit == '1') {
            $tmp_price = TAOQI_LIMIT;
            if (array_key_exists('taoqi_limit', $discount_list) && $discount_list['taoqi_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['taoqi_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'taoqi_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 1000,
                'comments' => '千人千面－淘气值1000的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 人气权重
        $traffic_list = $this->input->post('traffic_list');
        if (count($traffic_list) > 0) {
            $tmp_price = 0;
            $this->load->model('Traffic_Model', 'traffic');
            $traffic_arr = $this->traffic->get_traffic_list(time());
            $data_arr = [];
            foreach ($traffic_list as $item) {
                if (intval($item['num']) > 0) {
                    $tmp_price += floatval($traffic_arr[$item['name']]['price'] * $item['num']);
                    $data_arr[$item['name']] = $item['num'];
                }
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'traffic_list',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => serialize($data_arr),
                'comments' => '人气权重优化'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 评价类型
        $eval_type = 0;
        // 默认好评
        $default_eval = $this->input->post('default_eval');
        if ($default_eval) {
            $eval_type = 1;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'default_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '默认好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 自由好评
        $free_eval = $this->input->post('free_eval');
        if ($free_eval) {
            $eval_type = 0;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'free_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自由好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 关键词好评
        $kwd_eval = $this->input->post('kwd_eval');
        $kwds = $this->input->post('kwds');
        if ($kwd_eval && $kwds) {
            $eval_type = 2;
            foreach ($kwds as $k => $v) {
                if ($v == '') unset($kwds[$k]);
            }
            if (count($kwds) != 3) {
                exit(json_encode(['error' => 1, 'message' => '关键词好评填写不正确']));
            }
            $tmp_price = KWD_EVAL_PRICE;
            if (array_key_exists('kwd_eval', $discount_list) && $discount_list['kwd_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['kwd_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'kwd_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => json_encode($kwds),
                'comments' => '关键词好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 自定义好评
        $setting_eval = $this->input->post('setting_eval');
        $eval_contents = $this->input->post('eval_contents');
        $this->write_db->delete('rqf_setting_eval', ['trade_id' => $trade_id]);
        if ($setting_eval && $eval_contents) {
            $eval_type = 3;
            foreach ($eval_contents as $k => $v) {
                if ($v == '') unset($eval_contents[$k]);
            }

            if (count($eval_contents) < $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '自定义好评与指定的单数不匹配']));
            }
            $setting_eval = [];
            foreach ($eval_contents as $v) {
                $setting_eval[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'content' => $v
                ];
            }

            $tmp_price = SETTING_EVAL_PRICE;
            if (array_key_exists('setting_eval', $discount_list) && $discount_list['setting_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自定义好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 图文好评
        $pic_rewards = 0;
        $this->write_db->delete('rqf_setting_img', ['trade_id' => $trade_id]);
        $setting_picture = $this->input->post('setting_picture');
        if ($setting_picture) {
            $eval_type = 4;
            $setting_pic_color = $this->input->post('setting_pic_color');
            $setting_pic_size = $this->input->post('setting_pic_size');
            $setting_pic_list = $this->input->post('setting_pic_list');
            $setting_pic_content = $this->input->post('setting_pic_content');
            if (count($setting_pic_list) != intval($trade_info->total_num) || count($setting_pic_content) != intval($trade_info->total_num)) {
                exit(json_encode(['error' => 1, 'message' => '图文好评上传的图片、评论，与指定的单数不匹配']));
            }
            // 记录评价内容
            $setting_pic_recode = [];
            foreach ($setting_pic_list as $key => $pic_list) {
                $setting_pic_recode[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'color' => $setting_pic_color[$key],
                    'size' => $setting_pic_size[$key],
                    'img1' => isset($pic_list[0]) ? trim($pic_list[0]) : '',
                    'img2' => isset($pic_list[1]) ? trim($pic_list[1]) : '',
                    'img3' => isset($pic_list[2]) ? trim($pic_list[2]) : '',
                    'img4' => isset($pic_list[3]) ? trim($pic_list[3]) : '',
                    'img5' => isset($pic_list[4]) ? trim($pic_list[4]) : '',
                    'video' => '',
                    'content' => $setting_pic_content[$key]
                ];
            }
            // 记录增值服务
            $tmp_price = 4;
            $pic_rewards = 2;       // 用户分成
            if (array_key_exists('setting_picture', $discount_list) && $discount_list['setting_picture'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_picture'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_picture',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '图文好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 视频评价
        $setting_video = $this->input->post('setting_video');
        if ($setting_video) {
            $eval_type = 5;
            $setting_video_list = $this->input->post('setting_video_list');
            $setting_video_color = $this->input->post('setting_video_color');
            $setting_video_size = $this->input->post('setting_video_size');
            $setting_video_pic_list = $this->input->post('setting_video_pic_list');
            $setting_video_content = $this->input->post('setting_video_content');
            if (count($setting_video_list) != intval($trade_info->total_num)) {
                exit(json_encode(['error' => 1, 'message' => '视频评价上传的视频段数，与指定的单数不匹配']));
            }
            // 记录评价内容
            $setting_pic_recode = [];
            foreach ($setting_video_list as $key => $video) {
                $setting_pic_recode[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'color' => $setting_video_color[$key],
                    'size' => $setting_video_size[$key],
                    'img1' => isset($setting_video_pic_list[$key][0]) ? trim($setting_video_pic_list[$key][0]) : '',
                    'img2' => isset($setting_video_pic_list[$key][1]) ? trim($setting_video_pic_list[$key][1]) : '',
                    'img3' => isset($setting_video_pic_list[$key][2]) ? trim($setting_video_pic_list[$key][2]) : '',
                    'img4' => isset($setting_video_pic_list[$key][3]) ? trim($setting_video_pic_list[$key][3]) : '',
                    'img5' => isset($setting_video_pic_list[$key][4]) ? trim($setting_video_pic_list[$key][4]) : '',
                    'video' => $video,
                    'content' => $setting_video_content[$key]
                ];
            }
            // 记录增值服务
            $tmp_price = 6;
            $pic_rewards = 3;       // 用户分成
            if (array_key_exists('setting_video', $discount_list) && $discount_list['setting_video'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_video'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_video',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '视频评价'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        $trade_point = bcadd($trade_info->order_fee_point, $trade_info->order_dis_point, 4);
        $trade_point = bcadd($trade_point, $service_point, 4);
        // 超级浏览任务佣金
        $scan_reward = bcmul(count($trade_scans), PHONE_SCAN_REWARD, 4);
        $trade_info_upd = [
            'add_reward' => bcadd(bcmul($add_reward_point, ADD_REWARD_POINT_PERCENT, 4), $scan_reward, 4),
            'pic_reward' => $pic_rewards,
            'recommend_weight' => $add_speed,
            'extend_cycle' => $extend_cycle,
            'first_check' => $first_check_val,
            'service_point' => $service_point,
            'trade_point' => $trade_point,
            'trade_step' => 6,
            'plat_refund' => $plat_refund_val,
            'start_time' => $start_time,
            'interval' => $interval_param,
            'eval_type' => $eval_type,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'no_print' => $no_print
        ];

        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 5];
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        if ($this->write_db->affected_rows()) {
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_id]);
            $this->write_db->insert_batch('rqf_trade_service', $trade_service);
            if ($setting_eval && $eval_contents) {
                $this->write_db->insert_batch('rqf_setting_eval', $setting_eval);
            }
            if (($setting_picture || $setting_video) && $setting_pic_recode) {
                $this->write_db->insert_batch('rqf_setting_img', $setting_pic_recode);
            }
        }
        $this->write_db->close();

        exit(json_encode(['error' => 0]));
    }

    /**
     * 超级搜索任务第六步
     */
    private function super_char_eval_step6($trade_info)
    {

        $data = $this->data;

        $user_id = $this->session->userdata('user_id');

        $data['trade_info'] = $trade_info;

        $data['user_info'] = $this->user->get_user_info($user_id);

        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['shop_ww'] = $shop_info->shop_ww;

        // $data['trade_select'] = $this->trade->get_trade_select($trade_info);

        $this->load->view('trade/super_char_eval_step6', $data);
    }

    /**
     * 超级搜索任务第六步提交
     */
    public function super_char_eval_step6_submit()
    {
        $this->write_db = $this->load->database('write', true);
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
        $trade_info = $this->trade->get_trade_info($trade_id);
        if (!in_array($trade_info->trade_status, ['0', '5'])) {
            redirect('center');
            return;
        }

        if (in_array($trade_info->plat_id, [1, 2])) {
            $shop_info_sql = "select * from rqf_bind_shop where id = {$trade_info->shop_id}";
            $shop_info = $this->write_db->query($shop_info_sql)->row();
            if (empty($shop_info)) {
                redirect('center');
                return;
            }
            $show_ww = $shop_info->shop_ww;
            $auth_info2 = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 2])->row();
            if (empty($auth_info2)) {
                $auth_info = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 1])->row();
                if (empty($auth_info)) {
                    $result = $this->ddx($show_ww);
                    if ($result['code'] == 0) {
                        $insert_array['shop_ww'] = $show_ww;
                        $insert_array['auth_type'] = 1;
                        $insert_array['is_order'] = $result['is_order'];
                        $insert_array['expires_time'] = strtotime($result['expires_time']);
                        $insert_array['deadline'] = strtotime($result['deadline']);
                        $this->write_db->insert('rqf_shop_auth_info', $insert_array);
                    } else {
                        error_back($result['msg']);
                        return;
                    }
                } else {
                    if ($auth_info->expires_time < time()) {
                        error_back('授权过期，需要重新授权');
                        return;
                    }
                }
            } else {
                if ($auth_info2->expires_time < time()) {
                    error_back('授权过期，需要重新授权');
                    return;
                }
            }
        }

        // 使用押金
        // $has_deposit = $this->input->post('has_deposit');
        $has_deposit = true;

        // 使用金币
        // $has_point = $this->input->post('has_point');
        $has_point = true;

        // 账户押金
        $user_deposit = 0;

        if ($has_deposit)
            $user_deposit = $user_info->user_deposit;

        // 账户金币
        $user_point = 0;

        if ($has_point)
            $user_point = $user_info->user_point;

        // 活动押金
        $trade_deposit = $trade_info->trade_deposit;

        // 活动金币
        $trade_point = $trade_info->trade_point;

        // 金币支付
        $pay_point = 0;

        // 押金支付
        $pay_deposit = 0;

        // 押金转金币
        $deposit_to_point = 0;

        // 第三方支付
        $pay_third = 0;
        if (bccomp($trade_point, $user_point, 2) > 0) {
            error_back('账户金币不足!');
            return;
        } else {
            $pay_point = $trade_point;
        }

        $trade_deposit = bcadd($trade_deposit, $deposit_to_point, 2);
        if (bccomp($trade_deposit, $user_deposit, 2) > 0) {
            error_back('账户押金不足!');
            return;
        } else {
            $pay_deposit = $trade_deposit;
        }

        if ($pay_third == 0) {
            $sql = "update rqf_trade_info
                      set trade_step = 7, trade_status = 1, pay_point = {$pay_point}, pay_deposit = {$pay_deposit}, pay_third = {$pay_third}, pay_time = ?
                    where id = {$trade_id} and user_id = {$user_id} and trade_step = 6 and trade_status in (0,5)";
            $this->write_db->query($sql, [time()]);
            if ($this->write_db->affected_rows()) {
                // 押金转金币
                if ($deposit_to_point > 0) {
                    $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
                    $user_deposit = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 200,
                        'score_nums' => '-' . $deposit_to_point,
                        'last_score' => bcsub($user_info->user_deposit, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_deposit,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                    $user_point = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 100,
                        'score_nums' => '+' . $deposit_to_point,
                        'last_score' => bcadd($user_info->user_point, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_point,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];

                    $this->write_db->insert('rqf_bus_user_point', $user_point);

                    $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$deposit_to_point, $deposit_to_point, $user_id]);
                }

                $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

                // 冻结押金
                $user_deposit = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_deposit,
                    'last_score' => bcsub($user_info->user_deposit, $trade_info->trade_deposit, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_deposit,
                    'last_frozen_score' => bcadd($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];
                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                // 冻结金币
                $user_point = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_point,
                    'last_score' => bcsub($user_info->user_point, $trade_info->trade_point, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_point,
                    'last_frozen_score' => bcadd($user_info->frozen_point, $trade_info->trade_point, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];
                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $sql = 'update rqf_users
                        set user_deposit = user_deposit - ?,
                            frozen_deposit = frozen_deposit + ?,
                            user_point = user_point - ?,
                            frozen_point = frozen_point + ?
                            where id = ?';
                $this->write_db->query($sql, [$trade_info->trade_deposit, $trade_info->trade_deposit, $trade_info->trade_point, $trade_info->trade_point, $user_id]);

                // 操作日志
                $action_info = [
                    'trade_id' => $trade_info->id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 1,
                    'trade_note' => '活动已支付',
                    'add_time' => time(),
                    'created_user' => $this->session->userdata('nickname'),
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $action_info);
            }

            redirect('trade/step/' . $trade_id);
            return;
        }
    }

    /**
     * 超级搜索任务第七步
     */
    private function super_char_eval_step7($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $this->load->view('trade/super_char_eval_step7', $data);
    }


    /**------------------------------------回访订单------------------------------------**/

    /**
     * 回访订单第二步
     */
    private function return_visit_step2($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['taobao_search'] = $trade_search['pc_taobao'];
        $data['tmall_search'] = $trade_search['pc_tmall'];
        $data['app_search'] = $trade_search['app'];

        // 任务要求
        $task_requirements = unserialize($data['trade_item']->task_requirements);
        unset($data['trade_item']->task_requirements);
        if (empty($task_requirements)) {
            $task_requirements = ['is_post' => 0, 'chat' => 1, 'coupon' => 0, 'coupon_link' => '', 'credit' => 0];
        }
        $data['task_requirements'] = $task_requirements;

        $this->load->view('trade/return_visit_step2', $data);
    }

    /**
     * 回访订单第二步提交(1)
     */
    public function return_visit_step2_1_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $trade_info = $this->trade->get_trade_info($trade_id);

        $old_trade_search = $this->trade->get_trade_search($trade_id);

        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }

        $goods_name = trim($this->input->post('goods_name'));

        $goods_url = trim($this->input->post('goods_url'));

        $price = floatval($this->input->post('price'));

        $show_price = $this->input->post('show_price');

        $buy_num = intval($this->input->post('buy_num'));

        $color = trim($this->input->post('color'));

        $size = trim($this->input->post('size'));

        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);

        if ($goods_name == '') {
            echo json_encode(['code' => 2, 'msg' => '请输入商品名称']);
            return;
        }

        if ($goods_url == '') {
            echo json_encode(['code' => 3, 'msg' => '请输入商品链接']);
            return;
        }
        if (empty($item_id)) {
            echo json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']);
            return;
        }
        if (empty($price)) {
            echo json_encode(['code' => 4, 'msg' => '请输入商品价格']);
            return;
        }

        if ($buy_num < 1) {
            echo json_encode(['code' => 5, 'msg' => '请输入购买件数']);
            return;
        }

        $trade_search = [];

        $goods_img = '';

        $pc_taobao = trim($this->input->post('pc_taobao'));

        if ($pc_taobao) {

            $tb_kwd = $this->input->post('tb_kwd');

            $tb_classify1 = $this->input->post('tb_classify1');
            $tb_classify2 = $this->input->post('tb_classify2');
            $tb_classify3 = $this->input->post('tb_classify3');
            $tb_classify4 = $this->input->post('tb_classify4');

            $tb_low_price = $this->input->post('tb_low_price');
            $tb_high_price = $this->input->post('tb_high_price');
            $tb_area = $this->input->post('tb_area');

            $tb_img_base64 = $this->input->post('tb_img_base64');

            // 关键词验证
            foreach ($tb_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 1,
                'low_price' => $tb_low_price,
                'high_price' => $tb_high_price,
                'area' => $tb_area
            ];

            if ($tb_img_base64) {
                $tb_img = $this->base64->to_img($tb_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tb_img;
                qiniu_upload(ltrim($tb_img, '/'));
            } else {
                $old_taobao_search_0 = $old_trade_search['pc_taobao'][0];
                $tmp_info['search_img'] = $old_taobao_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_taobao_search = $old_trade_search['pc_taobao'];

            foreach ($tb_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tb_classify1[$k];
                $tmp_info['classify2'] = $tb_classify2[$k];
                $tmp_info['classify3'] = $tb_classify3[$k];
                $tmp_info['classify4'] = $tb_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $pc_tmall = $this->input->post('pc_tmall');

        if ($pc_tmall) {

            $tm_kwd = $this->input->post('tm_kwd');

            $tm_classify1 = $this->input->post('tm_classify1');
            $tm_classify2 = $this->input->post('tm_classify2');
            $tm_classify3 = $this->input->post('tm_classify3');
            $tm_classify4 = $this->input->post('tm_classify4');

            $tm_low_price = $this->input->post('tm_low_price');
            $tm_high_price = $this->input->post('tm_high_price');
            $tm_area = $this->input->post('tm_area');

            $tm_img_base64 = $this->input->post('tm_img_base64');

            // 关键词验证
            foreach ($tm_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '天猫关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 2,
                'low_price' => $tm_low_price,
                'high_price' => $tm_high_price,
                'area' => $tm_area
            ];

            if ($tm_img_base64) {
                $tm_img = $this->base64->to_img($tm_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tm_img;
                qiniu_upload(ltrim($tm_img, '/'));
            } else {
                $old_tmall_search_0 = $old_trade_search['pc_tmall'][0];
                $tmp_info['search_img'] = $old_tmall_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_tmall_search = $old_trade_search['pc_tmall'];

            foreach ($tm_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tm_classify1[$k];
                $tmp_info['classify2'] = $tm_classify2[$k];
                $tmp_info['classify3'] = $tm_classify3[$k];
                $tmp_info['classify4'] = $tm_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $phone_taobao = trim($this->input->post('phone_taobao'));

        if ($phone_taobao) {

            // var_dump($_POST);die;

            $app_kwd = $this->input->post('app_kwd');

            $app_low_price = $this->input->post('app_low_price');

            $app_high_price = $this->input->post('app_high_price');

            $app_discount_text = $this->input->post('app_discount_text');

            $app_area = $this->input->post('app_area');

            $goods_cate = $this->input->post('goods_cate');

            $app_order_way = $this->input->post('app_order_way');

            $app_img1_base64 = $this->input->post('app_img1_base64');

            $app_img2_base64 = $this->input->post('app_img2_base64');

            // 关键词验证
            foreach ($app_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 3,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => $app_order_way
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            $goods_img = $tmp_info['search_img'];
            $old_app_search = $old_trade_search['app'];
            foreach ($app_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['low_price'] = isset($app_low_price[$k]) ? $app_low_price[$k] : '';
                $tmp_info['high_price'] = isset($app_high_price[$k]) ? $app_high_price[$k] : '';
                $tmp_info['discount'] = isset($app_discount_text[$k]) ? rtrim($app_discount_text[$k], ',') : '';
                $tmp_info['area'] = isset($app_area[$k]) ? $app_area[$k] : '';
                $tmp_info['goods_cate'] = isset($goods_cate[$k]) ? $goods_cate[$k] : '';
                $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        if ($pc_taobao && $pc_tmall) {
            $is_pc = 3;
        } elseif ($pc_taobao && !$pc_tmall) {
            $is_pc = 1;
        } elseif (!$pc_taobao && $pc_tmall) {
            $is_pc = 2;
        } else {
            $is_pc = 0;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        if (in_array($trade_info->trade_type, ['114', '214'])) {
            $order_fee_obj = $this->fee->order_fee_obj('1', $price * $buy_num);
            $order_fee_obj->total_fee = $order_fee_obj->total_fee + 4;
            $order_fee_obj->base_reward = $order_fee_obj->base_reward + 2;
        } else {
            $order_fee_obj = $this->fee->order_fee_obj('2', $price * $buy_num);
        }
        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }

        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => $is_pc,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];

        $trade_info_key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];

        $trade_item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);

        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);

        $this->write_db->insert_batch('rqf_trade_search', $trade_search);

        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 回访订单第二步提交(2)
     */
    public function return_visit_step2_2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $is_post = intval($this->input->post('is_post'));
        $chat = intval($this->input->post('chat'));
        $coupon = intval($this->input->post('coupon'));
        $coupon_link = trim($this->input->post('coupon_link'));
        $credit = intval($this->input->post('credit'));
        $post_fee = $is_post ? 0 : POST_FEE;

        $trade_info_upd = ['is_post' => $is_post, 'post_fee' => $post_fee];
        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $task_requirements_str = serialize(['is_post' => $is_post, 'chat' => $chat, 'coupon' => $coupon, 'coupon_link' => $coupon_link, 'credit' => $credit]);      // 活动下单要求
        $trade_item_upd = ['is_post' => $is_post, 'task_requirements' => $task_requirements_str];
        $item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->close();

        echo 0;
    }

    /**
     * 回访订单第二步提交(3)
     */
    public function return_visit_step2_3_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info_upd = ['trade_step' => 3];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);

        $this->write_db->close();

        echo 0;
    }

    /**
     * 回访订单第三步
     */
    private function return_visit_step3($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $trade_nums = ['1', '3', '5', '10', '20', '100', '250'];
        $data['is_custom'] = !in_array($trade_info->total_num, $trade_nums);
        $data['custom_val'] = in_array($trade_info->total_num, $trade_nums) ? 1 : $trade_info->total_num;
        // 订单搜索词
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_info->id])->result();
        if (count($trade_search) == 1) {
            $trade_search[0]->num = $trade_info->total_num;
        }
        $data['trade_search'] = $trade_search;
        $data['plat_names'] = ['1' => '淘宝', '2' => '天猫', '3' => '手机淘宝'];

        $this->load->view('trade/return_visit_step3', $data);
    }

    /**
     * 回访订单第三步提交
     */
    public function return_visit_step3_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info = $this->trade->get_trade_info($trade_id);

        $total_num = intval($this->input->post('total_num'));

        $total_num_custom = intval($this->input->post('total_num_custom'));

        $nums = $this->input->post('nums');

        $order_prompt = $this->input->post('order_prompt');

        if ($total_num == 0) {
            $total_num = $total_num_custom;
        }

        if ($total_num == 0) {
            echo 2;
            return;
        }

        $pc_num = $trade_info->is_pc ? $total_num : 0;

        $phone_num = $trade_info->is_phone ? $total_num : 0;

        // 活动费用
        $order_fee_point = bcmul($trade_info->total_fee, $total_num, 2);

        // 手机端订单分布
        $order_dis_point = $trade_info->is_phone ? bcmul($total_num, ORDER_DIS_PRICE, 2) : 0;

        // 手机端赏金
        $phone_reward = $trade_info->is_phone ? PHONE_REWARD : 0;

        // 每单商品价值
        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 2);

        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 2);

        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 2);

        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 2);

        // 活动押金
        $trade_deposit = bcmul($deposit_subtotal, $total_num, 2);

        // 活动保证金
        $trade_payment = bcmul($payment, $total_num, 2);

        // 活动邮费
        $trade_post_fee = bcmul($trade_info->post_fee, $total_num, 2);

        // 赠送流量单（浏览+加购）
        $award_num = $this->award_num($total_num);

        $trade_info_upd = [
            'total_num' => $total_num,
            'award_num' => $award_num,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'phone_reward' => $phone_reward,
            'order_fee_point' => $order_fee_point,
            'order_dis_point' => $order_dis_point,
            'trade_payment' => $trade_payment,
            'trade_post_fee' => $trade_post_fee,
            'trade_deposit' => $trade_deposit,
            'trade_step' => 4
        ];

        $key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $trade_item_upd = [
            'order_prompt' => $order_prompt
        ];

        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();

        if (count($nums) != count($trade_search)) {
            echo 3;
            return;
        }

        $nums_sum = 0;

        $trade_search_upd = [];

        foreach ($nums as $k => $v) {

            $nums_sum += $v;

            $trade_search_upd[] = ['id' => $trade_search[$k]->id, 'num' => $v, 'surplus_num' => $v];
        }

        if ($nums_sum != $total_num) {
            echo 3;
            return;
        }

        $item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);

        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);

        $this->write_db->update_batch('rqf_trade_search', $trade_search_upd, 'id');

        $this->write_db->close();

        echo 0;
    }

    /**
     * 回访订单第四步
     */
    private function return_visit_step4($trade_info)
    {
        $data = $this->data;
        $user_id = intval($this->session->userdata('user_id'));
        $data['trade_info'] = $trade_info;
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $data['interval_list'] = $this->conf->interval_list();
        // 活动单数超过10单，删除自定义好评及评价内容
        $this->write_db = $this->load->database('write', true);
        if ($trade_info->total_num > 10) {
            $result = $this->write_db->query('DELETE FROM `rqf_trade_service` WHERE `trade_id` = ? AND `service_name` in ?', [intval($trade_info->id), ['setting_eval', 'setting_picture']]);
            $this->write_db->delete('rqf_setting_eval', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        } elseif ($trade_info->total_num > 5) {
            // 活动单数超过5单，删除图文好评及评价内容
            $this->write_db->delete('rqf_trade_service', ['trade_id' => intval($trade_info->id), 'service_name' => 'setting_picture']);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        }
        $this->write_db->close();
        // 增值服务
        $res = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
        $trade_service = [];
        foreach ($res as $v) {
            $trade_service[$v->service_name] = $v;
        }

        // 平台返款
        // $data['has_plat_refund'] = (isset($trade_service['plat_refund']) || (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800));
        $data['has_plat_refund'] = true;
        // $data['plat_refund_disabled'] = (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800);
        $data['plat_refund_disabled'] = true;           // 强制平台返款
        $data['plat_refund_percent'] = fun_plat_refund_percent(bcmul($trade_info->price, $trade_info->buy_num, 4));        // 获取任务订单退款手续费
        // 商家返款
        $data['has_bus_refund'] = isset($trade_service['bus_refund']);
        // 提升完成活动速度
        $data['add_speed_val'] = isset($trade_service['add_speed']) ? $trade_service['add_speed']->price : 0;
        // 加赏活动佣金
        $data['has_add_reward'] = isset($trade_service['add_reward']);
        $data['add_reward_val'] = isset($trade_service['add_reward']) ? intval($trade_service['add_reward']->price) : 3;
        // 优先审单
        $data['has_first_check'] = isset($trade_service['first_check']);
        // 千人千面设置 地域限制
        $data['area_limit'] = isset($trade_service['area_limit']);
        $data['area_limit_list'] = isset($trade_service['area_limit']) ? explode(',', $trade_service['area_limit']->param) : [];
        // 指定平台新注册买手接单
        $data['has_newhand'] = isset($trade_service['newhand']) ? $trade_service['newhand']->param : '';
        // 千人千面设置 性别选择
        $data['sex_limit'] = isset($trade_service['sex_limit']);
        $data['sex_limit_val'] = isset($trade_service['sex_limit']) ? $trade_service['sex_limit']->param : '0';
        // 仅限钻级别的买号可接此活动
        $data['reputation_limit'] = isset($trade_service['reputation_limit']) ? 2 : 1;
        // 仅限淘气值1000以上买号可接此活动
        $data['taoqi_limit'] = isset($trade_service['taoqi_limit']) ? 2 : 1;
        // 定时发布
        $data['has_set_time'] = isset($trade_service['set_time']);
        $data['set_time_val'] = isset($trade_service['set_time']) ? $trade_service['set_time']->param : '';
        // 定时结束任务
        $data['has_set_over_time'] = isset($trade_service['set_over_time']);
        $data['set_over_time_val'] = isset($trade_service['set_over_time']) ? $trade_service['set_over_time']->param : '';
        // 分时发布
        $data['custom_time_price'] = isset($trade_service['custom_time_price']) ? json_decode($trade_service['custom_time_price']->param, true) : [];
        $data['set_time_pre_val'] = intval($trade_info->start_time) > 0 ? date('Y-m-d', $trade_info->start_time) : date('Y-m-d');
        // 分时单数
        $interval_nums = [1, 2, 5, 10, 20, 50];
        foreach ($interval_nums as $k => $v) {
            if ($v >= $trade_info->total_num && ($v != 1)) {
                unset($interval_nums[$k]);
            }
        }
        $data['interval_nums'] = $interval_nums;

        // 间隔发布
        /**
         * $data['has_set_interval'] = (isset($trade_service['set_interval'])) || ($trade_info->total_num >= 20);
         * if ($trade_info->total_num == 1) {
         * $data['has_set_interval'] = false;
         * }
         * $data['set_interval_disabled'] = (($trade_info->total_num >= 20) || ($trade_info->total_num == 1));
         * */
        $data['set_interval_disabled'] = false;
        $data['has_set_interval'] = isset($trade_service['set_interval']);
        if (isset($trade_service['set_interval'])) {
            $params = explode('|', $trade_service['set_interval']->param);
            $set_interval_val = $params[0];
            $interval_num_val = $params[1];
        } else {
            $set_interval_val = '10m';
            $interval_num_val = '1';
        }
        $data['set_interval_val'] = $set_interval_val;
        $data['interval_num_val'] = $interval_num_val;

        // 包裹重量
        $data['set_weight_val'] = isset($trade_service['set_weight']) ? $trade_service['set_weight']->param : '2';
        // 快递选择
        $data['set_shipping'] = isset($trade_service['set_shipping']) ? $trade_service['set_shipping']->param : $data['trade_select']['shipping_type'];
        // 默认好评
        $data['has_default_eval'] = isset($trade_service['default_eval']);
        // 自由好评
        $data['has_free_eval'] = isset($trade_service['free_eval']);
        // 关键词好评
        $data['has_kwd_eval'] = isset($trade_service['kwd_eval']);
        // 关键词列表
        $data['kwds'] = isset($trade_service['kwd_eval']) ? json_decode($trade_service['kwd_eval']->param, true) : ['', '', ''];
        // 自定义好评
        $data['has_setting_eval'] = isset($trade_service['setting_eval']);
        // 自定义好评内容
        $eval_contents = [];
        if ($trade_info->total_num <= 10) {
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $eval_contents[] = '';
            }
            $setting_eval_res = $this->db->get_where('rqf_setting_eval', ['trade_id' => intval($trade_info->id)])->result();
            foreach ($setting_eval_res as $k => $v) {
                if ($k >= $trade_info->total_num) {
                    continue;
                }
                $eval_contents[$k] = $v->content;
            }

            $data['setting_eval_disabled'] = false;
        } else {
            $data['setting_eval_disabled'] = true;
        }
        $data['eval_contents'] = $eval_contents;
        // 图文好评
        $data['has_setting_picture'] = isset($trade_service['setting_picture']);
        // 图文好评设置
        $txt_images_list = [];
        if ($trade_info->total_num <= 5) {
            $trade_item_res = $this->db->get_where('rqf_trade_item', ['trade_id' => intval($trade_info->id)])->row();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $txt_images_list[] = ['color' => $trade_item_res->color, 'size' => $trade_item_res->size, 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => ''];
            }
            if ($data['has_setting_picture']) {
                $txt_image_res = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id)])->result();
                foreach ($txt_image_res as $k => $v) {
                    if ($k >= $trade_info->total_num) {
                        continue;
                    }

                    $txt_images_list[$k] = ['color' => $v->color, 'size' => $v->size, 'img1' => $v->img1, 'img2' => $v->img2, 'img3' => $v->img3, 'img4' => $v->img4, 'img5' => $v->img5, 'content' => $v->content];
                }
            }
        } else {
            $data['txt_image_disabled'] = true;
        }
        $data['txt_image_list'] = $txt_images_list;

        // 视频评价
        $data['has_setting_video'] = isset($trade_service['setting_video']);
        $video_image_list = [];
        if ($trade_info->total_num <= 5) {
            $trade_item_res = $this->db->get_where('rqf_trade_item', ['trade_id' => intval($trade_info->id)])->row();
            for ($i = 0; $i < $trade_info->total_num; $i++) {
                $video_image_list[] = ['color' => $trade_item_res->color, 'size' => $trade_item_res->size, 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => '', 'video' => ''];
            }

            if ($data['has_setting_video']) {
                $video_image_res = $this->db->get_where('rqf_setting_img', ['trade_id' => intval($trade_info->id)])->result();
                if ($video_image_res) {
                    foreach ($video_image_res as $k => $v) {
                        if ($k >= $trade_info->total_num) {
                            continue;
                        }
                        $video_image_list[$k] = ['color' => $v->color, 'size' => $v->size, 'img1' => $v->img1, 'img2' => $v->img2, 'img3' => $v->img3, 'img4' => $v->img4, 'img5' => $v->img5, 'content' => $v->content, 'video' => $v->video];
                    }
                }
            }
        }
        $data['video_image_list'] = $video_image_list;

        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 4);
        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 4);

        $data['payment'] = $payment;

        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 4);

        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 4);

        $data['deposit_subtotal'] = $deposit_subtotal;

        // 金币小计
        $point_subtotal = $trade_info->total_fee;

        if ($trade_info->is_phone) {
            $point_subtotal = bcadd($point_subtotal, ORDER_DIS_PRICE, 4);
        }

        $data['point_subtotal'] = $point_subtotal;
        // 快递类型
        $data['shipping_type_list'] = $this->conf->get_shipping_type_list();
        // 人气权重
        $this->load->model('Traffic_Model', 'traffic');
        if (in_array($trade_info->trade_type, ['4', '5']) || in_array($trade_info->plat_id, ['4', '14'])) {
            $service_list = [];
        } else {
            if (isset($trade_service['traffic_list'])) {
                $service_list = unserialize($trade_service['traffic_list']->param);
            } else {
                if (count($trade_service) <= 0) {
                    $service_list = ['normal_price' => 4, 'collect_goods' => 2];   // 默认勾选浏览商品、收藏商品
                } else {
                    $service_list = [];
                }
            }
        }
        $data['traffic_arr'] = $this->traffic->normal_task_traffic_show($trade_info->total_num, $service_list, time());

        // 增值服务优惠折扣
        $discount_list = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        foreach ($discount_list as $item) {
            $data['discount'][$item->service_name] = intval($item->discount);
        }

        $this->load->view('trade/return_visit_step4', $data);
    }

    /**
     * 回访订单第四步提交
     */
    public function return_visit_step4_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }

        $this->write_db = $this->load->database('write', true);
        $trade_info = $this->trade->get_trade_info($trade_id);
        $trade_service = [];
        $service_point = 0;
        // 增值服务优惠折扣
        $discount_query = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        $discount_list = [];
        foreach ($discount_query as $item) {
            $discount_list[$item->service_name] = intval($item->discount);
        }
        // 快速返款
        $plat_refund = $this->input->post('plat_refund');
        if ($plat_refund || (bcmul($trade_info->price, $trade_info->buy_num, 4) <= 800)) {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, fun_plat_refund_percent($tmp_price), 4);
            if (array_key_exists('plat_refund', $discount_list) && $discount_list['plat_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['plat_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'plat_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '快速返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 1;
        } else {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, BUS_REFUND_PERCENT, 4);
            if (array_key_exists('bus_refund', $discount_list) && $discount_list['bus_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['bus_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'bus_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '商家返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 0;
        }

        // 提升完成活动速度
        $add_speed = intval($this->input->post('add_speed'));
        if ($add_speed) {
            if (!in_array($add_speed, [10, 20, 30])) {
                $add_speed = 10;
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_speed',
                'price' => $add_speed,
                'num' => 1,
                'pay_point' => $add_speed,
                'param' => '',
                'comments' => '提升完成活动速度'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_speed = 0;
        }

        // 加赏活动佣金
        $add_reward = $this->input->post('add_reward');
        $add_reward_point = intval($this->input->post('add_reward_point'));
        if ($add_reward) {
            $add_reward_point = max($add_reward_point, 4);
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward',
                'price' => $add_reward_point,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($add_reward_point, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '加赏活动佣金'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_reward_point = 0;
        }

        // 优先审核
        $first_check = $this->input->post('first_check');
        if ($first_check) {
            $first_check_val = 1;
            $tmp_price = FIRST_CHECK_PRICE;
            if (array_key_exists('first_check', $discount_list) && $discount_list['first_check'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['first_check'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_check',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => '',
                'comments' => '优先审核'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $first_check_val = 0;
        }

        // 定时发布
        $set_time = $this->input->post('set_time');
        $set_time_val = $this->input->post('set_time_val');
        if ($set_time && $set_time_val) {
            if (strtotime($set_time_val) <= time()) {
                exit(json_encode(['error' => 1, 'message' => '设置的定时发布时间应大于当前时间']));
            }
            $tmp_price = 3;
            if (array_key_exists('set_time', $discount_list) && $discount_list['set_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_time_val,
                'comments' => '定时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $start_time = strtotime($set_time_val);
        } else {
            $start_time = 0;
        }
        // 定时结束任务
        $set_over_time = $this->input->post('set_over_time');
        $set_over_time_val = $this->input->post('set_over_time_val');
        if ($set_over_time && $set_over_time_val) {
            $compare_time = ($set_time && $set_time_val) ? strtotime($set_time_val) : time();
            if (strtotime($set_over_time_val) <= $compare_time + 3600) {
                exit(json_encode(['error' => 1, 'message' => '结束时间、与活动时间至少错开一个小时']));
            }
            $tmp_price = 2;
            if (array_key_exists('set_over_time', $discount_list) && $discount_list['set_over_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_over_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_over_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_over_time_val,
                'comments' => '定时结束'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 活动分时发布、与间隔发布 目前暂定为互斥关系 二选一
        $interval_param = '';
        $custom_time_price = $this->input->post('custom_time_price');       // 分时发布
        $set_interval = $this->input->post('set_interval');                 // 间隔发布
        if ($custom_time_price && $set_interval) {
            exit(json_encode(['error' => 1, 'message' => '分时发布、与间隔发布暂不可以同时发布 ，请确认']));
        } elseif ($custom_time_price) {
            // 分时发布
            $total_nums = 0;
            $custom_time_price_list = [];
            // 查看分时发布开始时间
            $set_time_pre_val = empty(trim($this->input->post('set_time_pre_val'))) ? strtotime('+1 hour') : strtotime(trim($this->input->post('set_time_pre_val')));
            $reference_hour = date('H', $set_time_pre_val);
            foreach ($custom_time_price as $item) {
                if (intval($item['nums']) <= 0) continue;
                if (intval($item['hour']) < $reference_hour) {
                    exit(json_encode(['error' => 1, 'message' => '分时发布点应大于当前时间、或定时发布的时间，请确认']));
                }
                $total_nums += intval($item['nums']);
                $custom_time_price_list[$item['hour']] = $item['nums'];
            }
            if ($total_nums != $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '总活动单数与时间点单数累加值应一致，请确认']));
            }
            $tmp_price = CUSTOM_TIME_PRICE;
            if (array_key_exists('custom_time_price', $discount_list) && $discount_list['custom_time_price'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['custom_time_price'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'custom_time_price',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => json_encode($custom_time_price_list),
                'comments' => '分时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $pc_num = 0;
            $phone_num = 0;
            $start_time = $set_time_pre_val;
        } elseif ($set_interval) {
            // 间隔发布
            $set_interval_val = $this->input->post('set_interval_val');
            $interval_num = $this->input->post('interval_num');
            $interval_list = $this->conf->interval_list();
            $interval_list_keys = array_keys($interval_list);
            if (!in_array($set_interval_val, $interval_list_keys)) {
                $set_interval_val = $interval_list_keys[0];
            }
            $tmp_price = SET_INTERVAL_PRICE;
            if (array_key_exists('set_interval', $discount_list) && $discount_list['set_interval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_interval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_interval',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => "{$set_interval_val}|{$interval_num}",
                'comments' => '间隔发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $interval_param = "{$set_interval_val}|{$interval_num}";
            $pc_num = 0;
            $phone_num = 0;
        } else {
            $pc_num = $trade_info->is_pc ? $trade_info->total_num : 0;
            $phone_num = $trade_info->is_phone ? $trade_info->total_num : 0;
        }

        // 选择包裹配送方式
        $no_print = $trade_info->no_print;
        $shipping = $this->input->post('shipping');
        if (is_null($shipping) || empty(trim($shipping))) {
            exit(json_encode(['error' => 1, 'message' => '请选择预备配送的快递类型']));
        }
        $shipping_info = $this->conf->get_shipping_type_list($shipping);
        if ($shipping_info && $shipping_info['name']) {
            //快递折扣
            if (array_key_exists('set_shipping', $discount_list) && $discount_list['set_shipping'] < 100) {
                $shipping_info['price'] = round(bcmul($shipping_info['price'], $discount_list['set_shipping'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_shipping',
                'price' => $shipping_info['price'],
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($shipping_info['price'], $trade_info->total_num, 4),
                'param' => $shipping,
                'comments' => $shipping_info['name'] . '配送'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            if ($shipping == 'self') {
                $no_print = (intval($trade_info->no_print) <= 0) ? 2 : intval($trade_info->no_print);
            } else {
                $no_print = 0;
            }
        }

        // 自定义包裹重量(必选)
        $set_weight_val = $this->input->post('set_weight_val');
        $tmp_info = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_info->trade_sn,
            'service_name' => 'set_weight',
            'price' => SET_WEIGHT_PRICE,
            'num' => 1,
            'pay_point' => SET_WEIGHT_PRICE,
            'param' => $set_weight_val,
            'comments' => '自定义包裹重量'
        ];

        $trade_service[] = $tmp_info;
        $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        $this->write_db->update('rqf_trade_item', ['weight' => $set_weight_val], ['trade_id' => $trade_id]);

        // 延长买家购物周期
        $extend_cycle = intval($this->input->post('extend_cycle'));
        if ($extend_cycle && in_array($extend_cycle, [2, 3])) {
            if ($extend_cycle == 2) {
                $tmp_price = EXTEND_CYCLE1_PRICE;
            } else {
                $tmp_price = EXTEND_CYCLE2_PRICE;
            }
            if (array_key_exists('extend_cycle', $discount_list) && $discount_list['extend_cycle'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['extend_cycle'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'extend_cycle',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $extend_cycle,
                'comments' => '延长买家购物周期'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $extend_cycle = 0;
        }

        // 限制买号重复进店下单
        $shopping_end = intval($this->input->post('shopping_end'));
        if ($shopping_end) {
            $tmp_price = SHOPPING_END_BOX;
            if (array_key_exists('shopping_end', $discount_list) && $discount_list['shopping_end'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['shopping_end'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'shopping_end',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $shopping_end,
                'comments' => '限制买号重复进店下单'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 指定平台新注册买手接单
        $newhand = intval($this->input->post('newhand'));
        if ($newhand) {
            if ($newhand == 1) {
                $tmp_price = 1;
                $comments = '指定平台1个月内新注册下单';
            } elseif ($newhand == 2) {
                $tmp_price = 2;
                $comments = '指定平台15天内新注册下单';
            } else {
                $tmp_price = 3;
                $comments = '指定平台7天内新注册下单';
            }
            if (array_key_exists('newhand', $discount_list) && $discount_list['newhand'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['newhand'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'newhand',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $newhand,
                'comments' => $comments
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 地域限制
        $area_limit = $this->input->post('area_limit');
        if ($area_limit == '1') {
            $area_limit_city = $this->input->post('area_limit_city');
            $tmp_price = AREA_LIMIT;
            if (array_key_exists('area_limit', $discount_list) && $discount_list['area_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['area_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'area_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => implode(',', $area_limit_city),
                'comments' => '千人千面－地域限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 性别限制
        $sex_limit = $this->input->post('sex_limit');
        if ($sex_limit == '1') {
            $sex_limit_val = $this->input->post('sex_limit_val');
            $tmp_price = SEX_LIMIT;
            if (array_key_exists('sex_limit', $discount_list) && $discount_list['sex_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['sex_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'sex_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $sex_limit_val,
                'comments' => '千人千面－性别限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 钻级别的买号
        $reputation_limit = $this->input->post('reputation_limit');
        if ($reputation_limit == '1') {
            $tmp_price = REPUTATION_LIMIT;
            if (array_key_exists('reputation_limit', $discount_list) && $discount_list['reputation_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['reputation_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'reputation_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 5,           // 标记5以上就是钻级
                'comments' => '千人千面－钻级别的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 淘气值1000的买号
        $taoqi_limit = $this->input->post('taoqi_limit');
        if ($taoqi_limit == '1') {
            $tmp_price = TAOQI_LIMIT;
            if (array_key_exists('taoqi_limit', $discount_list) && $discount_list['taoqi_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['taoqi_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'taoqi_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 1000,
                'comments' => '千人千面－淘气值1000的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 人气权重
        $traffic_list = $this->input->post('traffic_list');
        if (count($traffic_list) > 0) {
            $tmp_price = 0;
            $this->load->model('Traffic_Model', 'traffic');
            $traffic_arr = $this->traffic->get_traffic_list(time());
            $data_arr = [];
            foreach ($traffic_list as $item) {
                if (intval($item['num']) > 0) {
                    $tmp_price += floatval($traffic_arr[$item['name']]['price'] * $item['num']);
                    $data_arr[$item['name']] = $item['num'];
                }
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'traffic_list',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => serialize($data_arr),
                'comments' => '人气权重优化'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 评价类型
        $eval_type = 0;
        // 默认好评
        $default_eval = $this->input->post('default_eval');
        if ($default_eval) {
            $eval_type = 1;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'default_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '默认好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 自由好评
        $free_eval = $this->input->post('free_eval');
        if ($free_eval) {
            $eval_type = 0;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'free_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自由好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 关键词好评
        $kwd_eval = $this->input->post('kwd_eval');
        $kwds = $this->input->post('kwds');
        if ($kwd_eval && $kwds) {
            $eval_type = 2;
            foreach ($kwds as $k => $v) {
                if ($v == '') unset($kwds[$k]);
            }
            if (count($kwds) != 3) {
                exit(json_encode(['error' => 1, 'message' => '关键词好评填写不正确']));
            }
            $tmp_price = KWD_EVAL_PRICE;
            if (array_key_exists('kwd_eval', $discount_list) && $discount_list['kwd_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['kwd_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'kwd_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => json_encode($kwds),
                'comments' => '关键词好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 自定义好评
        $setting_eval = $this->input->post('setting_eval');
        $eval_contents = $this->input->post('eval_contents');
        $this->write_db->delete('rqf_setting_eval', ['trade_id' => $trade_id]);
        if ($setting_eval && $eval_contents) {
            $eval_type = 3;
            foreach ($eval_contents as $k => $v) {
                if ($v == '') unset($eval_contents[$k]);
            }
            if (count($eval_contents) < $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '自定义好评与指定的单数不匹配']));
            }
            $setting_eval = [];
            foreach ($eval_contents as $v) {
                $setting_eval[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'content' => $v
                ];
            }

            $tmp_price = SETTING_EVAL_PRICE;
            if (array_key_exists('setting_eval', $discount_list) && $discount_list['setting_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自定义好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 图文好评
        $pic_rewards = 0;
        $this->write_db->delete('rqf_setting_img', ['trade_id' => $trade_id]);
        $setting_picture = $this->input->post('setting_picture');
        if ($setting_picture) {
            $eval_type = 4;
            $setting_pic_color = $this->input->post('setting_pic_color');
            $setting_pic_size = $this->input->post('setting_pic_size');
            $setting_pic_list = $this->input->post('setting_pic_list');
            $setting_pic_content = $this->input->post('setting_pic_content');
            if (count($setting_pic_list) != intval($trade_info->total_num) || count($setting_pic_content) != intval($trade_info->total_num)) {
                exit(json_encode(['error' => 1, 'message' => '图文好评上传的图片、评论，与指定的单数不匹配']));
            }
            // 记录评价内容
            $setting_pic_recode = [];
            foreach ($setting_pic_list as $key => $pic_list) {
                $setting_pic_recode[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'color' => $setting_pic_color[$key],
                    'size' => $setting_pic_size[$key],
                    'img1' => isset($pic_list[0]) ? trim($pic_list[0]) : '',
                    'img2' => isset($pic_list[1]) ? trim($pic_list[1]) : '',
                    'img3' => isset($pic_list[2]) ? trim($pic_list[2]) : '',
                    'img4' => isset($pic_list[3]) ? trim($pic_list[3]) : '',
                    'img5' => isset($pic_list[4]) ? trim($pic_list[4]) : '',
                    'video' => '',
                    'content' => $setting_pic_content[$key]
                ];
            }
            // 记录增值服务
            $tmp_price = 4;
            $pic_rewards = 2;       // 用户分成
            if (array_key_exists('setting_picture', $discount_list) && $discount_list['setting_picture'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_picture'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_picture',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '图文好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 视频评价
        $setting_video = $this->input->post('setting_video');
        if ($setting_video) {
            $eval_type = 5;
            $setting_video_list = $this->input->post('setting_video_list');
            $setting_video_color = $this->input->post('setting_video_color');
            $setting_video_size = $this->input->post('setting_video_size');
            $setting_video_pic_list = $this->input->post('setting_video_pic_list');
            $setting_video_content = $this->input->post('setting_video_content');
            if (count($setting_video_list) != intval($trade_info->total_num)) {
                exit(json_encode(['error' => 1, 'message' => '视频评价上传的视频段数，与指定的单数不匹配']));
            }
            // 记录评价内容
            $setting_pic_recode = [];
            foreach ($setting_video_list as $key => $video) {
                $setting_pic_recode[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'color' => $setting_video_color[$key],
                    'size' => $setting_video_size[$key],
                    'img1' => isset($setting_video_pic_list[$key][0]) ? trim($setting_video_pic_list[$key][0]) : '',
                    'img2' => isset($setting_video_pic_list[$key][1]) ? trim($setting_video_pic_list[$key][1]) : '',
                    'img3' => isset($setting_video_pic_list[$key][2]) ? trim($setting_video_pic_list[$key][2]) : '',
                    'img4' => isset($setting_video_pic_list[$key][3]) ? trim($setting_video_pic_list[$key][3]) : '',
                    'img5' => isset($setting_video_pic_list[$key][4]) ? trim($setting_video_pic_list[$key][4]) : '',
                    'video' => $video,
                    'content' => $setting_video_content[$key]
                ];
            }
            // 记录增值服务
            $tmp_price = 6;
            $pic_rewards = 3;       // 用户分成
            if (array_key_exists('setting_video', $discount_list) && $discount_list['setting_video'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_video'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_video',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '视频评价'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        $trade_point = bcadd($trade_info->order_fee_point, $trade_info->order_dis_point, 4);
        $trade_point = bcadd($trade_point, $service_point, 4);
        $trade_info_upd = [
            'add_reward' => bcmul($add_reward_point, ADD_REWARD_POINT_PERCENT, 4),
            'pic_reward' => $pic_rewards,
            'recommend_weight' => $add_speed,
            'extend_cycle' => $extend_cycle,
            'first_check' => $first_check_val,
            'service_point' => $service_point,
            'trade_point' => $trade_point,
            'trade_step' => 5,
            'plat_refund' => $plat_refund_val,
            'start_time' => $start_time,
            'interval' => $interval_param,
            'eval_type' => $eval_type,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'no_print' => $no_print
        ];

        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 4];
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        if ($this->write_db->affected_rows()) {
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_id]);
            $this->write_db->insert_batch('rqf_trade_service', $trade_service);
            if ($setting_eval && $eval_contents) {
                $this->write_db->insert_batch('rqf_setting_eval', $setting_eval);
            }
            if (($setting_picture || $setting_video) && $setting_pic_recode) {
                $this->write_db->insert_batch('rqf_setting_img', $setting_pic_recode);
            }
        }
        $this->write_db->close();

        exit(json_encode(['error' => 0]));
    }

    /**
     * 回访订单第五步
     */
    private function return_visit_step5($trade_info)
    {

        $data = $this->data;

        $user_id = $this->session->userdata('user_id');

        $data['trade_info'] = $trade_info;

        $data['user_info'] = $this->user->get_user_info($user_id);

        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['shop_ww'] = $shop_info->shop_ww;

        // $data['trade_select'] = $this->trade->get_trade_select($trade_info);

        $this->load->view('trade/return_visit_step5', $data);
    }

    /**
     * 回访订单第五步提交
     */
    public function return_visit_step5_submit()
    {

        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');

        $this->write_db = $this->load->database('write', true);
        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
        $trade_info = $this->trade->get_trade_info($trade_id);
        if (!in_array($trade_info->trade_status, ['0', '5'])) {
            redirect('center');
            return;
        }

        if (in_array($trade_info->plat_id, [1, 2])) {
            $shop_info_sql = "select * from rqf_bind_shop where id = {$trade_info->shop_id}";
            $shop_info = $this->write_db->query($shop_info_sql)->row();
            if (empty($shop_info)) {
                redirect('center');
                return;
            }
            $show_ww = $shop_info->shop_ww;
            $auth_info2 = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 2])->row();
            if (empty($auth_info2)) {
                $auth_info = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 1])->row();
                if (empty($auth_info)) {
                    $result = $this->ddx($show_ww);
                    if ($result['code'] == 0) {
                        $insert_array['shop_ww'] = $show_ww;
                        $insert_array['auth_type'] = 1;
                        $insert_array['is_order'] = $result['is_order'];
                        $insert_array['expires_time'] = strtotime($result['expires_time']);
                        $insert_array['deadline'] = strtotime($result['deadline']);
                        $this->write_db->insert('rqf_shop_auth_info', $insert_array);
                    } else {
                        error_back($result['msg']);
                        return;
                    }
                } else {
                    if ($auth_info->expires_time < time()) {
                        error_back('授权过期，需要重新授权');
                        return;
                    }
                }
            } else {
                if ($auth_info2->expires_time < time()) {
                    error_back('授权过期，需要重新授权');
                    return;
                }
            }
        }

        // 使用押金
        $has_deposit = true;

        // 使用金币
        $has_point = true;

        // 账户押金
        $user_deposit = 0;

        if ($has_deposit)
            $user_deposit = $user_info->user_deposit;

        // 账户金币
        $user_point = 0;

        if ($has_point)
            $user_point = $user_info->user_point;

        // 活动押金
        $trade_deposit = $trade_info->trade_deposit;

        // 活动金币
        $trade_point = $trade_info->trade_point;

        // 金币支付
        $pay_point = 0;

        // 押金支付
        $pay_deposit = 0;

        // 押金转金币
        $deposit_to_point = 0;

        // 第三方支付
        $pay_third = 0;

        if (bccomp($trade_point, $user_point, 2) > 0) {

            // $pay_point = $user_point;

            // $deposit_to_point = bcsub($trade_point, $user_point, 2);

            error_back('账户金币不足');
            return;
        } else {

            $pay_point = $trade_point;
        }

        $trade_deposit = bcadd($trade_deposit, $deposit_to_point, 2);

        if (bccomp($trade_deposit, $user_deposit, 2) > 0) {

            // $pay_deposit = $user_deposit;

            // $pay_third = bcsub($trade_deposit, $user_deposit, 2);

            error_back('账户押金不足');
            return;
        } else {

            $pay_deposit = $trade_deposit;
        }

        if ($pay_third == 0) {
            $sql = "update rqf_trade_info
                      set trade_step = 6, trade_status = 1, pay_point = {$pay_point}, pay_deposit = {$pay_deposit}, pay_third = {$pay_third}, pay_time = ?
                    where id = {$trade_id} and user_id = {$user_id} and trade_step = 5 and trade_status in (0,5)";
            $this->write_db->query($sql, [time()]);
            if ($this->write_db->affected_rows()) {
                // 押金转金币
                if ($deposit_to_point > 0) {
                    $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
                    $user_deposit = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 200,
                        'score_nums' => '-' . $deposit_to_point,
                        'last_score' => bcsub($user_info->user_deposit, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_deposit,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                    $user_point = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 100,
                        'score_nums' => '+' . $deposit_to_point,
                        'last_score' => bcadd($user_info->user_point, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_point,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_point', $user_point);
                    $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$deposit_to_point, $deposit_to_point, $user_id]);
                }

                $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

                // 冻结押金

                $user_deposit = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_deposit,
                    'last_score' => bcsub($user_info->user_deposit, $trade_info->trade_deposit, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_deposit,
                    'last_frozen_score' => bcadd($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                // 冻结金币

                $user_point = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_point,
                    'last_score' => bcsub($user_info->user_point, $trade_info->trade_point, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_point,
                    'last_frozen_score' => bcadd($user_info->frozen_point, $trade_info->trade_point, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $sql = 'update rqf_users set user_deposit = user_deposit - ?, frozen_deposit = frozen_deposit + ?, user_point = user_point - ?, frozen_point = frozen_point + ? where id = ?';
                $this->write_db->query($sql, [$trade_info->trade_deposit, $trade_info->trade_deposit, $trade_info->trade_point, $trade_info->trade_point, $user_id]);

                // 操作日志
                $action_info = [
                    'trade_id' => $trade_info->id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 1,
                    'trade_note' => '活动已支付',
                    'add_time' => time(),
                    'created_user' => $this->session->userdata('nickname'),
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $action_info);
            }

            redirect('trade/step/' . $trade_id);
            return;
        }
    }

    /**
     * 回访订单第六步
     */
    private function return_visit_step6($trade_info)
    {

        $data = $this->data;

        $data['trade_info'] = $trade_info;

        $this->load->view('trade/return_visit_step6', $data);
    }


    /**------------------------------------图文好评------------------------------------**/

    /**
     * 图文好评第二步
     */
    private function pic_eval_step2($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);

        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['taobao_search'] = $trade_search['pc_taobao'];
        $data['tmall_search'] = $trade_search['pc_tmall'];
        $data['app_search'] = $trade_search['app'];

        // 任务要求
        $task_requirements = unserialize($data['trade_item']->task_requirements);
        unset($data['trade_item']->task_requirements);
        if (empty($task_requirements)) {
            $task_requirements = ['is_post' => 0, 'chat' => 1, 'coupon' => 0, 'coupon_link' => '', 'credit' => 0];
        }
        $data['task_requirements'] = $task_requirements;

        $this->load->view('trade/pic_eval_step2', $data);
    }

    /**
     * 图文好评第二步提交(1)
     */
    public function pic_eval_step2_1_submit()
    {

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $trade_info = $this->trade->get_trade_info($trade_id);

        $old_trade_search = $this->trade->get_trade_search($trade_id);

        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }

        $goods_name = trim($this->input->post('goods_name'));

        $goods_url = trim($this->input->post('goods_url'));

        $price = floatval($this->input->post('price'));

        $show_price = $this->input->post('show_price');

        $buy_num = intval($this->input->post('buy_num'));

        $color = trim($this->input->post('color'));

        $size = trim($this->input->post('size'));

        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);

        if ($goods_name == '') {
            echo json_encode(['code' => 2, 'msg' => '请输入商品名称']);
            return;
        }

        if ($goods_url == '') {
            echo json_encode(['code' => 3, 'msg' => '请输入商品链接']);
            return;
        }
        if (empty($item_id)) {
            echo json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']);
            return;
        }
        if (empty($price)) {
            echo json_encode(['code' => 4, 'msg' => '请输入商品价格']);
            return;
        }

        if ($buy_num < 1) {
            echo json_encode(['code' => 5, 'msg' => '请输入购买件数']);
            return;
        }

        $trade_search = [];

        $goods_img = '';

        $pc_taobao = trim($this->input->post('pc_taobao'));

        if ($pc_taobao) {

            $tb_kwd = $this->input->post('tb_kwd');

            $tb_classify1 = $this->input->post('tb_classify1');
            $tb_classify2 = $this->input->post('tb_classify2');
            $tb_classify3 = $this->input->post('tb_classify3');
            $tb_classify4 = $this->input->post('tb_classify4');

            $tb_low_price = $this->input->post('tb_low_price');
            $tb_high_price = $this->input->post('tb_high_price');
            $tb_area = $this->input->post('tb_area');

            $tb_img_base64 = $this->input->post('tb_img_base64');

            // 关键词验证
            foreach ($tb_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 1,
                'low_price' => $tb_low_price,
                'high_price' => $tb_high_price,
                'area' => $tb_area
            ];

            if ($tb_img_base64) {
                $tb_img = $this->base64->to_img($tb_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tb_img;
                qiniu_upload(ltrim($tb_img, '/'));
            } else {
                $old_taobao_search_0 = $old_trade_search['pc_taobao'][0];
                $tmp_info['search_img'] = $old_taobao_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];
            $old_taobao_search = $old_trade_search['pc_taobao'];
            foreach ($tb_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = isset($tb_classify1[$k]) ? $tb_classify1[$k] : '';
                $tmp_info['classify2'] = isset($tb_classify2[$k]) ? $tb_classify2[$k] : '';
                $tmp_info['classify3'] = isset($tb_classify3[$k]) ? $tb_classify3[$k] : '';
                $tmp_info['classify4'] = isset($tb_classify4[$k]) ? $tb_classify4[$k] : '';
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $pc_tmall = $this->input->post('pc_tmall');
        if ($pc_tmall) {
            $tm_kwd = $this->input->post('tm_kwd');
            $tm_classify1 = $this->input->post('tm_classify1');
            $tm_classify2 = $this->input->post('tm_classify2');
            $tm_classify3 = $this->input->post('tm_classify3');
            $tm_classify4 = $this->input->post('tm_classify4');
            $tm_low_price = $this->input->post('tm_low_price');
            $tm_high_price = $this->input->post('tm_high_price');
            $tm_area = $this->input->post('tm_area');
            $tm_img_base64 = $this->input->post('tm_img_base64');

            // 关键词验证
            foreach ($tm_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '天猫关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 2,
                'low_price' => $tm_low_price,
                'high_price' => $tm_high_price,
                'area' => $tm_area
            ];

            if ($tm_img_base64) {
                $tm_img = $this->base64->to_img($tm_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tm_img;
                qiniu_upload(ltrim($tm_img, '/'));
            } else {
                $old_tmall_search_0 = $old_trade_search['pc_tmall'][0];
                $tmp_info['search_img'] = $old_tmall_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_tmall_search = $old_trade_search['pc_tmall'];

            foreach ($tm_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tm_classify1[$k];
                $tmp_info['classify2'] = $tm_classify2[$k];
                $tmp_info['classify3'] = $tm_classify3[$k];
                $tmp_info['classify4'] = $tm_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $phone_taobao = trim($this->input->post('phone_taobao'));

        if ($phone_taobao) {

            // var_dump($_POST);die;

            $app_kwd = $this->input->post('app_kwd');

            $app_low_price = $this->input->post('app_low_price');

            $app_high_price = $this->input->post('app_high_price');

            $app_discount_text = $this->input->post('app_discount_text');

            $app_area = $this->input->post('app_area');

            $goods_cate = $this->input->post('goods_cate');

            $app_order_way = $this->input->post('app_order_way');

            $app_img1_base64 = $this->input->post('app_img1_base64');

            $app_img2_base64 = $this->input->post('app_img2_base64');

            // 关键词验证
            foreach ($app_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 3,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => $app_order_way
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            $goods_img = $tmp_info['search_img'];

            $old_app_search = $old_trade_search['app'];

            foreach ($app_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['low_price'] = $app_low_price[$k];
                $tmp_info['high_price'] = $app_high_price[$k];
                $tmp_info['discount'] = rtrim($app_discount_text[$k], ',');
                $tmp_info['area'] = $app_area[$k];
                $tmp_info['goods_cate'] = $goods_cate[$k];
                $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        if ($pc_taobao && $pc_tmall) {
            $is_pc = 3;
        } elseif ($pc_taobao && !$pc_tmall) {
            $is_pc = 1;
        } elseif (!$pc_taobao && $pc_tmall) {
            $is_pc = 2;
        } else {
            $is_pc = 0;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        $order_fee_obj = $this->fee->order_fee_obj($trade_info->trade_type, $price * $buy_num);

        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }

        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => $is_pc,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];

        $trade_info_key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];

        $trade_item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);

        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);

        $this->write_db->insert_batch('rqf_trade_search', $trade_search);

        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 图文好评第二步提交(2)
     */
    public function pic_eval_step2_2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }
        // 传入参数
        $is_post = intval($this->input->post('is_post'));
        $chat = intval($this->input->post('chat'));
        $coupon = intval($this->input->post('coupon'));
        $coupon_link = trim($this->input->post('coupon_link'));
        $credit = intval($this->input->post('credit'));
        $post_fee = $is_post ? 0 : POST_FEE;

        $trade_info_upd = ['is_post' => $is_post, 'post_fee' => $post_fee];
        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $task_requirements_str = serialize(['is_post' => $is_post, 'chat' => $chat, 'coupon' => $coupon, 'coupon_link' => $coupon_link, 'credit' => $credit]);      // 活动下单要求
        $trade_item_upd = ['is_post' => $is_post, 'task_requirements' => $task_requirements_str];
        $item_key = ['trade_id' => $trade_id];
        // Data update
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->close();

        echo 0;
    }

    /**
     * 图文好评第二步提交(3)
     */
    public function pic_eval_step2_3_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info_upd = ['trade_step' => 3];
        $key = ['id' => $trade_id, 'user_id' => $user_id];

        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->close();

        echo 0;
    }

    /**
     * 图文好评第三步
     */
    private function pic_eval_step3($trade_info)
    {

        $data = $this->data;

        $user_id = $this->session->userdata('user_id');

        $data['trade_info'] = $trade_info;

        $trade_item = $this->trade->get_trade_item($trade_info->id);

        $data['no_editable'] = ($trade_item->size || $trade_item->color);

        $data['trade_item'] = $trade_item;

        $data['trade_select'] = $this->trade->trade_select($trade_info);

        $setting_img = $this->db->get_where('rqf_setting_img', ['trade_id' => $trade_info->id])->result();

        //如果没有查询到数据，初始化返回结构体
        if (empty($setting_img)) {
            $setting_img = [(object)['color' => '', 'size' => '', 'img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => '', 'content' => '']];
        }
        $data['setting_img'] = $setting_img;
        $this->load->view('trade/pic_eval_step3', $data);
    }

    /**
     * 图文好评第三步提交
     */
    public function pic_eval_step3_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info = $this->trade->get_trade_info($trade_id);

        $color = $this->input->post('color');

        $size = $this->input->post('size');

        $imgs = $this->input->post('imgs');

        $contents = $this->input->post('contents');

        $order_prompt = $this->input->post('order_prompt');

        $setting_img = [];

        foreach ($color as $k => $v) {

            $tmp_imgs = explode(',', $imgs[$k]);

            $tmp_img1 = '';
            $tmp_img2 = '';
            $tmp_img3 = '';
            $tmp_img4 = '';
            $tmp_img5 = '';

            if (isset($tmp_imgs[0])) $tmp_img1 = $tmp_imgs[0];
            if (isset($tmp_imgs[1])) $tmp_img2 = $tmp_imgs[1];
            if (isset($tmp_imgs[2])) $tmp_img3 = $tmp_imgs[2];
            if (isset($tmp_imgs[3])) $tmp_img4 = $tmp_imgs[3];
            if (isset($tmp_imgs[4])) $tmp_img5 = $tmp_imgs[4];

            $setting_img[] = [
                'trade_id' => $trade_info->id,
                'trade_sn' => $trade_info->trade_sn,
                'color' => $v,
                'size' => $size[$k],
                'img1' => $tmp_img1,
                'img2' => $tmp_img2,
                'img3' => $tmp_img3,
                'img4' => $tmp_img4,
                'img5' => $tmp_img5,
                'video' => '',
                'content' => $contents[$k],
            ];
        }

        $total_num = count($color);

        if ($total_num == 0) {
            echo 2;
            return;
        }

        $pc_num = $trade_info->is_pc ? $total_num : 0;

        $phone_num = $trade_info->is_phone ? $total_num : 0;

        // 活动费用
        $order_fee_point = bcmul($trade_info->total_fee, $total_num, 2);

        // 手机端订单分布
        $order_dis_point = $trade_info->is_phone ? bcmul($total_num, ORDER_DIS_PRICE, 2) : 0;

        // 手机端赏金
        $phone_reward = $trade_info->is_phone ? PHONE_REWARD : 0;

        // 每单商品价值
        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 2);

        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 2);

        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 2);

        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 2);

        // 活动押金
        $trade_deposit = bcmul($deposit_subtotal, $total_num, 2);

        // 活动保证金
        $trade_payment = bcmul($payment, $total_num, 2);

        // 活动邮费
        $trade_post_fee = bcmul($trade_info->post_fee, $total_num, 2);

        // 赠送流量单（浏览+加购）
        $award_num = $this->award_num($total_num);

        $trade_info_upd = [
            'total_num' => $total_num,
            'award_num' => $award_num,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'phone_reward' => $phone_reward,
            'order_fee_point' => $order_fee_point,
            'order_dis_point' => $order_dis_point,
            'trade_payment' => $trade_payment,
            'trade_post_fee' => $trade_post_fee,
            'trade_deposit' => $trade_deposit,
            'trade_step' => 4
        ];

        $trade_info_key = [
            'id' => $trade_id,
            'user_id' => $user_id,
            'trade_step' => 3
        ];

        $trade_item_upd = [
            'order_prompt' => $order_prompt
        ];

        $trade_item_key = ['trade_id' => $trade_id];

        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();

        $nums = $this->allot_nums($total_num, count($trade_search));

        $trade_search_upd = [];

        foreach ($nums as $k => $v) {
            $trade_search_upd[] = ['id' => $trade_search[$k]->id, 'num' => $v, 'surplus_num' => $v];
        }

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        if ($this->write_db->affected_rows()) {

            $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);

            $this->write_db->update_batch('rqf_trade_search', $trade_search_upd, 'id');

            $this->write_db->delete('rqf_setting_img', ['trade_id' => $trade_id]);

            $this->write_db->insert_batch('rqf_setting_img', $setting_img);
        }

        $this->write_db->close();

        echo 0;
    }

    /**
     * 分配单数
     */
    private function allot_nums($sum, $cnt)
    {

        $arr = [];

        for ($i = 0; $i < $cnt; $i++)
            $arr[] = 0;

        for ($i = 0; $i < $sum; $i++) {

            $idx = $i % $cnt;

            $arr[$idx]++;
        }

        return $arr;
    }

    /**
     * 图文好评第四步
     */
    private function pic_eval_step4($trade_info)
    {
        $data = $this->data;
        $user_id = intval($this->session->userdata('user_id'));
        $data['trade_info'] = $trade_info;
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $data['interval_list'] = $this->conf->interval_list();
        // 活动单数超过10单，删除自定义好评及评价内容
        if ($trade_info->total_num > 10) {
            $this->write_db = $this->load->database('write', true);
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_info->id, 'service_name' => 'setting_eval']);
            $this->write_db->delete('rqf_setting_eval', ['trade_id' => $trade_info->id]);
            $this->write_db->close();
        }

        $res = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
        $trade_service = [];
        foreach ($res as $v) {
            $trade_service[$v->service_name] = $v;
        }

        // 平台返款
        // $data['has_plat_refund'] = (isset($trade_service['plat_refund']) || (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800));
        $data['has_plat_refund'] = true;
        // $data['plat_refund_disabled'] = (bcmul($trade_info->price,$trade_info->buy_num,4) <= 800);
        $data['plat_refund_disabled'] = true;           // 强制平台返款
        $data['plat_refund_percent'] = fun_plat_refund_percent(bcmul($trade_info->price, $trade_info->buy_num, 4));        // 获取任务订单退款手续费
        // 商家返款
        $data['has_bus_refund'] = isset($trade_service['bus_refund']);
        // 提升完成活动速度
        $data['add_speed_val'] = isset($trade_service['add_speed']) ? $trade_service['add_speed']->price : 0;
        // 加赏活动佣金
        $data['has_add_reward'] = isset($trade_service['add_reward']);
        $data['add_reward_val'] = isset($trade_service['add_reward']) ? intval($trade_service['add_reward']->price) : 3;
        // 优先审单
        $data['has_first_check'] = isset($trade_service['first_check']);
        // 千人千面设置 地域限制
        $data['area_limit'] = isset($trade_service['area_limit']);
        $data['area_limit_list'] = isset($trade_service['area_limit']) ? explode(',', $trade_service['area_limit']->param) : [];
        // 千人千面设置 性别选择
        $data['sex_limit'] = isset($trade_service['sex_limit']);
        $data['sex_limit_val'] = isset($trade_service['sex_limit']) ? $trade_service['sex_limit']->param : '0';
        // 指定平台新注册买手接单
        $data['has_newhand'] = isset($trade_service['newhand']) ? $trade_service['newhand']->param : '';
        // 仅限钻级别的买号可接此活动
        $data['reputation_limit'] = isset($trade_service['reputation_limit']) ? 2 : 1;
        // 仅限淘气值1000以上买号可接此活动
        $data['taoqi_limit'] = isset($trade_service['taoqi_limit']) ? 2 : 1;
        // 定时发布
        $data['has_set_time'] = isset($trade_service['set_time']);
        $data['set_time_val'] = isset($trade_service['set_time']) ? $trade_service['set_time']->param : '';
        // 定时结束任务
        $data['has_set_over_time'] = isset($trade_service['set_over_time']);
        $data['set_over_time_val'] = isset($trade_service['set_over_time']) ? $trade_service['set_over_time']->param : '';
        // 分时发布
        $data['custom_time_price'] = isset($trade_service['custom_time_price']) ? json_decode($trade_service['custom_time_price']->param, true) : [];
        $data['set_time_pre_val'] = intval($trade_info->start_time) > 0 ? date('Y-m-d', $trade_info->start_time) : date('Y-m-d');
        // 分时单数
        $interval_nums = [1, 2, 5, 10, 20, 50];
        foreach ($interval_nums as $k => $v) {
            if ($v >= $trade_info->total_num && ($v != 1)) {
                unset($interval_nums[$k]);
            }
        }
        $data['interval_nums'] = $interval_nums;

        // 间隔发布
        /**
         * $data['has_set_interval'] = (isset($trade_service['set_interval'])) || ($trade_info->total_num >= 20);
         * if ($trade_info->total_num == 1) {
         * $data['has_set_interval'] = false;
         * }
         * $data['set_interval_disabled'] = (($trade_info->total_num >= 20) || ($trade_info->total_num == 1));
         * */
        $data['set_interval_disabled'] = false;
        $data['has_set_interval'] = isset($trade_service['set_interval']);
        if (isset($trade_service['set_interval'])) {
            $params = explode('|', $trade_service['set_interval']->param);
            $set_interval_val = $params[0];
            $interval_num_val = $params[1];
        } else {
            $set_interval_val = '10m';
            $interval_num_val = '1';
        }
        $data['set_interval_val'] = $set_interval_val;
        $data['interval_num_val'] = $interval_num_val;

        // 包裹重量
        $data['set_weight_val'] = isset($trade_service['set_weight']) ? $trade_service['set_weight']->param : '2';
        // 快递选择
        $data['set_shipping'] = isset($trade_service['set_shipping']) ? $trade_service['set_shipping']->param : $data['trade_select']['shipping_type'];
        // 默认好评
        $data['has_default_eval'] = isset($trade_service['default_eval']);
        // 自由好评
        $data['has_free_eval'] = isset($trade_service['free_eval']);
        // 关键词好评
        $data['has_kwd_eval'] = isset($trade_service['kwd_eval']);
        // 关键词列表
        $data['kwds'] = isset($trade_service['kwd_eval']) ? json_decode($trade_service['kwd_eval']->param, true) : ['', '', ''];
        // 自定义好评
        $data['has_setting_eval'] = isset($trade_service['setting_eval']);
        // 自定义好评内容
        $eval_contents = [];
        $setting_eval_res = $this->db->get_where('rqf_setting_eval', ['trade_id' => $trade_info->id])->result();
        for ($i = 0; $i < $trade_info->total_num; $i++) {
            $eval_contents[] = '';
        }

        if ($setting_eval_res) {
            foreach ($setting_eval_res as $k => $v) {
                if ($k >= $trade_info->total_num) {
                    continue;
                }
                $eval_contents[$k] = $v->content;
            }
        }
        $data['eval_contents'] = $eval_contents;
        $data['setting_eval_disabled'] = ($trade_info->total_num > 10);

        $goods_val = bcmul($trade_info->price, $trade_info->buy_num, 4);
        // 活动保证金/单
        $payment = bcmul($goods_val, TRADE_PAYMENT_PERCENT, 4);
        $data['payment'] = $payment;
        // 押金小计
        $deposit_subtotal = bcadd($goods_val, $payment, 4);
        $deposit_subtotal = bcadd($deposit_subtotal, $trade_info->post_fee, 4);
        $data['deposit_subtotal'] = $deposit_subtotal;
        // 金币小计
        $point_subtotal = $trade_info->total_fee;
        if ($trade_info->is_phone) {
            $point_subtotal = bcadd($point_subtotal, ORDER_DIS_PRICE, 4);
        }

        $data['point_subtotal'] = $point_subtotal;
        // 快递类型
        $data['shipping_type_list'] = $this->conf->get_shipping_type_list();
        // 人气权重
        $this->load->model('Traffic_Model', 'traffic');
        if (in_array($trade_info->trade_type, ['4', '5']) || in_array($trade_info->plat_id, ['4', '14'])) {
            $service_list = [];
        } else {
            if (isset($trade_service['traffic_list'])) {
                $service_list = unserialize($trade_service['traffic_list']->param);
            } else {
                if (count($trade_service) <= 0) {
                    $service_list = ['normal_price' => 4, 'collect_goods' => 2];   // 默认勾选浏览商品、收藏商品
                } else {
                    $service_list = [];
                }
            }
        }
        $data['traffic_arr'] = $this->traffic->normal_task_traffic_show($trade_info->total_num, $service_list, time());

        // 增值服务优惠折扣
        $discount_list = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        foreach ($discount_list as $item) {
            $data['discount'][$item->service_name] = intval($item->discount);
        }

        $this->load->view('trade/pic_eval_step4', $data);
    }

    /**
     * 图文好评第四步提交
     */
    public function pic_eval_step4_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }

        $this->write_db = $this->load->database('write', true);
        $trade_info = $this->trade->get_trade_info($trade_id);
        $trade_service = [];
        $service_point = 0;
        // 增值服务优惠折扣
        $discount_query = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        $discount_list = [];
        foreach ($discount_query as $item) {
            $discount_list[$item->service_name] = intval($item->discount);
        }
        // 快速返款
        $plat_refund = $this->input->post('plat_refund');
        if ($plat_refund || (bcmul($trade_info->price, $trade_info->buy_num, 4) <= 800)) {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, fun_plat_refund_percent($tmp_price), 4);
            if (array_key_exists('plat_refund', $discount_list) && $discount_list['plat_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['plat_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'plat_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '快速返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 1;
        } else {
            $tmp_price = bcmul($trade_info->price, $trade_info->buy_num, 4);
            $tmp_price = bcmul($tmp_price, BUS_REFUND_PERCENT, 4);
            if (array_key_exists('bus_refund', $discount_list) && $discount_list['bus_refund'] < 100) {
                $tmp_price = bcmul($tmp_price, $discount_list['bus_refund'] / 100, 4);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'bus_refund',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => round(bcmul($tmp_price, $trade_info->total_num, 4), 2),
                'param' => '',
                'comments' => '商家返款'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $plat_refund_val = 0;
        }

        // 提升完成活动速度
        $add_speed = intval($this->input->post('add_speed'));
        if ($add_speed) {
            if (!in_array($add_speed, [10, 20, 30])) {
                $add_speed = 10;
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_speed',
                'price' => $add_speed,
                'num' => 1,
                'pay_point' => $add_speed,
                'param' => '',
                'comments' => '提升完成活动速度'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_speed = 0;
        }

        // 加赏活动佣金
        $add_reward = $this->input->post('add_reward');
        $add_reward_point = intval($this->input->post('add_reward_point'));
        if ($add_reward) {
            $add_reward_point = max($add_reward_point, 2);
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward',
                'price' => $add_reward_point,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($add_reward_point, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '加赏活动佣金'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_reward_point = 0;
        }

        // 优先审核
        $first_check = $this->input->post('first_check');
        if ($first_check) {
            $first_check_val = 1;
            $tmp_price = FIRST_CHECK_PRICE;
            if (array_key_exists('first_check', $discount_list) && $discount_list['first_check'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['first_check'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_check',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => '',
                'comments' => '优先审核'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $first_check_val = 0;
        }

        // 定时发布
        $set_time = $this->input->post('set_time');
        $set_time_val = $this->input->post('set_time_val');
        if ($set_time && $set_time_val) {
            if (strtotime($set_time_val) <= time()) {
                exit(json_encode(['error' => 1, 'message' => '设置的定时发布时间应大于当前时间']));
            }
            $tmp_price = 3;
            if (array_key_exists('set_time', $discount_list) && $discount_list['set_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_time_val,
                'comments' => '定时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $start_time = strtotime($set_time_val);
        } else {
            $start_time = 0;
        }
        // 定时结束任务
        $set_over_time = $this->input->post('set_over_time');
        $set_over_time_val = $this->input->post('set_over_time_val');
        if ($set_over_time && $set_over_time_val) {
            $compare_time = ($set_time && $set_time_val) ? strtotime($set_time_val) : time();
            if (strtotime($set_over_time_val) <= $compare_time + 3600) {
                exit(json_encode(['error' => 1, 'message' => '结束时间、与活动时间至少错开一个小时']));
            }
            $tmp_price = 2;
            if (array_key_exists('set_over_time', $discount_list) && $discount_list['set_over_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_over_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_over_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_over_time_val,
                'comments' => '定时结束'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 活动分时发布、与间隔发布 目前暂定为互斥关系 二选一
        $interval_param = '';
        $custom_time_price = $this->input->post('custom_time_price');       // 分时发布
        $set_interval = $this->input->post('set_interval');                 // 间隔发布
        if ($custom_time_price && $set_interval) {
            exit(json_encode(['error' => 1, 'message' => '分时发布、与间隔发布暂不可以同时发布 ，请确认']));
        } elseif ($custom_time_price) {
            // 分时发布
            $total_nums = 0;
            $custom_time_price_list = [];
            // 查看分时发布开始时间
            $set_time_pre_val = empty(trim($this->input->post('set_time_pre_val'))) ? strtotime('+1 hour') : strtotime(trim($this->input->post('set_time_pre_val')));
            $reference_hour = date('H', $set_time_pre_val);
            foreach ($custom_time_price as $item) {
                if (intval($item['nums']) <= 0) continue;
                if (intval($item['hour']) < $reference_hour) {
                    exit(json_encode(['error' => 1, 'message' => '分时发布点应大于当前时间、或定时发布的时间，请确认']));
                }
                $total_nums += intval($item['nums']);
                $custom_time_price_list[$item['hour']] = $item['nums'];
            }
            if ($total_nums != $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '总活动单数与时间点单数累加值应一致，请确认']));
            }
            $tmp_price = CUSTOM_TIME_PRICE;
            if (array_key_exists('custom_time_price', $discount_list) && $discount_list['custom_time_price'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['custom_time_price'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'custom_time_price',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => json_encode($custom_time_price_list),
                'comments' => '分时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $pc_num = 0;
            $phone_num = 0;
            $start_time = $set_time_pre_val;
        } elseif ($set_interval) {
            // 间隔发布
            $set_interval_val = $this->input->post('set_interval_val');
            $interval_num = $this->input->post('interval_num');
            $interval_list = $this->conf->interval_list();
            $interval_list_keys = array_keys($interval_list);
            if (!in_array($set_interval_val, $interval_list_keys)) {
                $set_interval_val = $interval_list_keys[0];
            }

            $tmp_price = SET_INTERVAL_PRICE;
            if (array_key_exists('set_interval', $discount_list) && $discount_list['set_interval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_interval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_interval',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => "{$set_interval_val}|{$interval_num}",
                'comments' => '间隔发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $interval_param = "{$set_interval_val}|{$interval_num}";
            $pc_num = 0;
            $phone_num = 0;
        } else {
            $pc_num = $trade_info->is_pc ? $trade_info->total_num : 0;
            $phone_num = $trade_info->is_phone ? $trade_info->total_num : 0;
        }

        // 选择包裹配送方式
        $no_print = $trade_info->no_print;
        $shipping = $this->input->post('shipping');
        if (is_null($shipping) || empty(trim($shipping))) {
            exit(json_encode(['error' => 1, 'message' => '请选择预备配送的快递类型']));
        }
        $shipping_info = $this->conf->get_shipping_type_list($shipping);
        if ($shipping_info && $shipping_info['name']) {
            //快递折扣
            if (array_key_exists('set_shipping', $discount_list) && $discount_list['set_shipping'] < 100) {
                $shipping_info['price'] = round(bcmul($shipping_info['price'], $discount_list['set_shipping'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_shipping',
                'price' => $shipping_info['price'],
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($shipping_info['price'], $trade_info->total_num, 4),
                'param' => $shipping,
                'comments' => $shipping_info['name'] . '配送'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            if ($shipping == 'self') {
                $no_print = (intval($trade_info->no_print) <= 0) ? 2 : intval($trade_info->no_print);
            } else {
                $no_print = 0;
            }
        }

        // 自定义包裹重量(必选)
        $set_weight_val = $this->input->post('set_weight_val');
        $tmp_info = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_info->trade_sn,
            'service_name' => 'set_weight',
            'price' => SET_WEIGHT_PRICE,
            'num' => 1,
            'pay_point' => SET_WEIGHT_PRICE,
            'param' => $set_weight_val,
            'comments' => '自定义包裹重量'
        ];

        $trade_service[] = $tmp_info;
        $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        $this->write_db->update('rqf_trade_item', ['weight' => $set_weight_val], ['trade_id' => $trade_id]);

        // 延长买家购物周期
        $extend_cycle = intval($this->input->post('extend_cycle'));
        if ($extend_cycle && in_array($extend_cycle, [2, 3])) {
            if ($extend_cycle == 2) {
                $tmp_price = EXTEND_CYCLE1_PRICE;
            } else {
                $tmp_price = EXTEND_CYCLE2_PRICE;
            }
            if (array_key_exists('extend_cycle', $discount_list) && $discount_list['extend_cycle'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['extend_cycle'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'extend_cycle',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $extend_cycle,
                'comments' => '延长买家购物周期'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $extend_cycle = 0;
        }

        // 限制买号重复进店下单
        $shopping_end = intval($this->input->post('shopping_end'));
        if ($shopping_end) {
            $tmp_price = SHOPPING_END_BOX;
            if (array_key_exists('shopping_end', $discount_list) && $discount_list['shopping_end'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['shopping_end'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'shopping_end',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $shopping_end,
                'comments' => '限制买号重复进店下单'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 指定平台新注册买手接单
        $newhand = intval($this->input->post('newhand'));
        if ($newhand) {
            if ($newhand == 1) {
                $tmp_price = 1;
                $comments = '指定平台1个月内新注册下单';
            } elseif ($newhand == 2) {
                $tmp_price = 2;
                $comments = '指定平台15天内新注册下单';
            } else {
                $tmp_price = 3;
                $comments = '指定平台7天内新注册下单';
            }
            if (array_key_exists('newhand', $discount_list) && $discount_list['newhand'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['newhand'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'newhand',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $newhand,
                'comments' => $comments
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 地域限制
        $area_limit = $this->input->post('area_limit');
        if ($area_limit == '1') {
            $area_limit_city = $this->input->post('area_limit_city');
            $tmp_price = AREA_LIMIT;
            if (array_key_exists('area_limit', $discount_list) && $discount_list['area_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['area_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'area_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => implode(',', $area_limit_city),
                'comments' => '千人千面－地域限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 性别限制
        $sex_limit = $this->input->post('sex_limit');
        if ($sex_limit == '1') {
            $sex_limit_val = $this->input->post('sex_limit_val');
            $tmp_price = SEX_LIMIT;
            if (array_key_exists('sex_limit', $discount_list) && $discount_list['sex_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['sex_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'sex_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $sex_limit_val,
                'comments' => '千人千面－性别限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 钻级别的买号
        $reputation_limit = $this->input->post('reputation_limit');
        if ($reputation_limit == '1') {
            $tmp_price = REPUTATION_LIMIT;
            if (array_key_exists('reputation_limit', $discount_list) && $discount_list['reputation_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['reputation_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'reputation_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 5,           // 标记5以上就是钻级
                'comments' => '千人千面－钻级别的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 淘气值1000的买号
        $taoqi_limit = $this->input->post('taoqi_limit');
        if ($taoqi_limit == '1') {
            $tmp_price = TAOQI_LIMIT;
            if (array_key_exists('taoqi_limit', $discount_list) && $discount_list['taoqi_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['taoqi_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'taoqi_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 1000,
                'comments' => '千人千面－淘气值1000的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 人气权重
        $traffic_list = $this->input->post('traffic_list');
        if (count($traffic_list) > 0) {
            $tmp_price = 0;
            $this->load->model('Traffic_Model', 'traffic');
            $traffic_arr = $this->traffic->get_traffic_list(time());
            $data_arr = [];
            foreach ($traffic_list as $item) {
                if (intval($item['num']) > 0) {
                    $tmp_price += floatval($traffic_arr[$item['name']]['price'] * $item['num']);
                    $data_arr[$item['name']] = $item['num'];
                }
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'traffic_list',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => serialize($data_arr),
                'comments' => '人气权重优化'
            ];
            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 评价类型
        $eval_type = 0;
        // 默认好评
        $default_eval = $this->input->post('default_eval');
        if ($default_eval) {
            $eval_type = 1;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'default_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '默认好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 自由好评
        $free_eval = $this->input->post('free_eval');
        if ($free_eval) {
            $eval_type = 0;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'free_eval',
                'price' => DEFAULT_EVAL_PRICE,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul(DEFAULT_EVAL_PRICE, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自由好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 关键词好评
        $kwd_eval = $this->input->post('kwd_eval');
        $kwds = $this->input->post('kwds');
        if ($kwd_eval && $kwds) {
            $eval_type = 2;
            foreach ($kwds as $k => $v) {
                if ($v == '') unset($kwds[$k]);
            }
            if (count($kwds) != 3) {
                exit(json_encode(['error' => 1, 'message' => '关键词好评填写不正确']));
            }
            $tmp_price = KWD_EVAL_PRICE;
            if (array_key_exists('kwd_eval', $discount_list) && $discount_list['kwd_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['kwd_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'kwd_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => json_encode($kwds),
                'comments' => '关键词好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 自定义好评
        $setting_eval = $this->input->post('setting_eval');
        $eval_contents = $this->input->post('eval_contents');
        $this->write_db->delete('rqf_setting_eval', ['trade_id' => $trade_id]);
        if ($setting_eval && $eval_contents) {
            $eval_type = 3;
            foreach ($eval_contents as $k => $v) {
                if ($v == '') unset($eval_contents[$k]);
            }

            if (count($eval_contents) < $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '自定义好评与指定的单数不匹配']));
            }
            $setting_eval = [];
            foreach ($eval_contents as $v) {
                $setting_eval[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'content' => $v
                ];
            }

            $tmp_price = SETTING_EVAL_PRICE;
            if (array_key_exists('setting_eval', $discount_list) && $discount_list['setting_eval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['setting_eval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'setting_eval',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '自定义好评'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        $trade_point = bcadd($trade_info->order_fee_point, $trade_info->order_dis_point, 4);
        $trade_point = bcadd($trade_point, $service_point, 4);
        $trade_info_upd = [
            'add_reward' => bcmul($add_reward_point, ADD_REWARD_POINT_PERCENT, 4),
            'pic_reward' => 0,
            'recommend_weight' => $add_speed,
            'extend_cycle' => $extend_cycle,
            'first_check' => $first_check_val,
            'service_point' => $service_point,
            'trade_point' => $trade_point,
            'trade_step' => 5,
            'plat_refund' => $plat_refund_val,
            'start_time' => $start_time,
            'interval' => $interval_param,
            'eval_type' => $eval_type,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'no_print' => $no_print
        ];

        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 4];
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        if ($this->write_db->affected_rows()) {
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_id]);
            $this->write_db->insert_batch('rqf_trade_service', $trade_service);
            if ($setting_eval && $eval_contents) {
                $this->write_db->insert_batch('rqf_setting_eval', $setting_eval);
            }
        }
        $this->write_db->close();

        exit(json_encode(['error' => 0]));
    }

    /*** 图文好评第五步 **/
    private function pic_eval_step5($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['user_info'] = $this->user->get_user_info($user_id);
        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['shop_ww'] = $shop_info->shop_ww;
        $this->load->view('trade/pic_eval_step5', $data);
    }

    /**
     * 图文好评第五步提交
     */
    public function pic_eval_step5_submit()
    {

        // var_dump($_POST);die;

        $this->write_db = $this->load->database('write', true);

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

        $trade_info = $this->trade->get_trade_info($trade_id);

        if (!in_array($trade_info->trade_status, ['0', '5'])) {
            redirect('center');
            return;
        }

        if (in_array($trade_info->plat_id, [1, 2])) {
            $shop_info_sql = "select * from rqf_bind_shop where id = {$trade_info->shop_id}";
            $shop_info = $this->write_db->query($shop_info_sql)->row();
            if (empty($shop_info)) {
                redirect('center');
                return;
            }
            $show_ww = $shop_info->shop_ww;
            $auth_info2 = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 2])->row();
            if (empty($auth_info2)) {
                $auth_info = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 1])->row();
                if (empty($auth_info)) {
                    $result = $this->ddx($show_ww);
                    if ($result['code'] == 0) {
                        $insert_array['shop_ww'] = $show_ww;
                        $insert_array['auth_type'] = 1;
                        $insert_array['is_order'] = $result['is_order'];
                        $insert_array['expires_time'] = strtotime($result['expires_time']);
                        $insert_array['deadline'] = strtotime($result['deadline']);
                        $this->write_db->insert('rqf_shop_auth_info', $insert_array);
                    } else {
                        error_back($result['msg']);
                        return;
                    }
                } else {
                    if ($auth_info->expires_time < time()) {
                        error_back('授权过期，需要重新授权');
                        return;
                    }
                }
            } else {
                if ($auth_info2->expires_time < time()) {
                    error_back('授权过期，需要重新授权');
                    return;
                }
            }
        }

        // 使用押金
        $has_deposit = true;

        // 使用金币
        $has_point = true;

        // 账户押金
        $user_deposit = 0;

        if ($has_deposit)
            $user_deposit = $user_info->user_deposit;

        // 账户金币
        $user_point = 0;

        if ($has_point)
            $user_point = $user_info->user_point;

        // 活动押金
        $trade_deposit = $trade_info->trade_deposit;

        // 活动金币
        $trade_point = $trade_info->trade_point;

        // 金币支付
        $pay_point = 0;

        // 押金支付
        $pay_deposit = 0;

        // 押金转金币
        $deposit_to_point = 0;

        // 第三方支付
        $pay_third = 0;

        if (bccomp($trade_point, $user_point, 2) > 0) {

            // $pay_point = $user_point;

            // $deposit_to_point = bcsub($trade_point, $user_point, 2);

            error_back('账户金币不足');
            return;
        } else {

            $pay_point = $trade_point;
        }

        $trade_deposit = bcadd($trade_deposit, $deposit_to_point, 2);

        if (bccomp($trade_deposit, $user_deposit, 2) > 0) {

            // $pay_deposit = $user_deposit;

            // $pay_third = bcsub($trade_deposit, $user_deposit, 2);

            error_back('账户押金不足');
            return;
        } else {

            $pay_deposit = $trade_deposit;
        }

        if ($pay_third == 0) {
            $sql = "update rqf_trade_info
                      set trade_step = 6, trade_status = 1, pay_point = {$pay_point}, pay_deposit = {$pay_deposit}, pay_third = {$pay_third}, pay_time = ?
                    where id = {$trade_id} and user_id = {$user_id} and trade_step = 5 and trade_status in (0,5)";
            $this->write_db->query($sql, [time()]);
            if ($this->write_db->affected_rows()) {
                // 押金转金币
                if ($deposit_to_point > 0) {
                    $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
                    $user_deposit = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 200,
                        'score_nums' => '-' . $deposit_to_point,
                        'last_score' => bcsub($user_info->user_deposit, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_deposit,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];

                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                    $user_point = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 100,
                        'score_nums' => '+' . $deposit_to_point,
                        'last_score' => bcadd($user_info->user_point, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_point,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];

                    $this->write_db->insert('rqf_bus_user_point', $user_point);

                    $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$deposit_to_point, $deposit_to_point, $user_id]);
                }

                $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

                // 冻结押金

                $user_deposit = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_deposit,
                    'last_score' => bcsub($user_info->user_deposit, $trade_info->trade_deposit, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_deposit,
                    'last_frozen_score' => bcadd($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                // 冻结金币

                $user_point = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_point,
                    'last_score' => bcsub($user_info->user_point, $trade_info->trade_point, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_point,
                    'last_frozen_score' => bcadd($user_info->frozen_point, $trade_info->trade_point, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $sql = 'update rqf_users
                        set user_deposit = user_deposit - ?,
                            frozen_deposit = frozen_deposit + ?,
                            user_point = user_point - ?,
                            frozen_point = frozen_point + ?
                            where id = ?';

                $this->write_db->query($sql, [$trade_info->trade_deposit, $trade_info->trade_deposit, $trade_info->trade_point, $trade_info->trade_point, $user_id]);

                // 操作日志
                $action_info = [
                    'trade_id' => $trade_info->id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 1,
                    'trade_note' => '活动已支付',
                    'add_time' => time(),
                    'created_user' => $this->session->userdata('nickname'),
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $action_info);
            }

            redirect('trade/step/' . $trade_id);
            return;
        }
    }

    /**
     * 图文好评第六步
     */
    private function pic_eval_step6($trade_info)
    {

        $data = $this->data;

        $data['trade_info'] = $trade_info;

        $this->load->view('trade/pic_eval_step6', $data);
    }

    /** 聚划算第二步 **/
    private function jhs_step2($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        if (empty($trade_search['app'][0]->kwd)) {
            $trade_search['app'][0]->kwd = 'jhs_search';
        }
        $data['app_search'] = $trade_search['app'];
        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        $this->load->view('trade/jhs_step2', $data);
    }


    /** 淘抢购第二步 */
    private function tqg_step2($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['taobao_search'] = $trade_search['pc_taobao'];
        $data['tmall_search'] = $trade_search['pc_tmall'];
        $data['app_search'] = $trade_search['app'];
        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        $this->load->view('trade/tqg_step2', $data);
    }

    /** 淘抢购 第二步 商品信息提交 **/
    public function tqg_step2_1_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        $old_trade_search = $this->trade->get_trade_search($trade_id);
        if (empty($user_id)) {
            exit(json_encode(['code' => 1, 'msg' => '用户未登录']));
        }

        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $price = floatval($this->input->post('price'));
        $show_price = $this->input->post('show_price');
        $buy_num = intval($this->input->post('buy_num'));
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        if ($goods_name == '') {
            exit(json_encode(['code' => 2, 'msg' => '请输入商品名称']));
        }

        if ($goods_url == '') {
            exit(json_encode(['code' => 3, 'msg' => '请输入商品链接']));
        }
        if (empty($item_id)) {
            exit(json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']));
        }
        if ($price <= 0) {
            exit(json_encode(['code' => 4, 'msg' => '请输入商品价格']));
        }
        if ($buy_num < 1) {
            exit(json_encode(['code' => 5, 'msg' => '请输入购买件数']));
        }

        $trade_search = [];
        $goods_img = '';
        $phone_taobao = trim($this->input->post('phone_taobao'));
        if ($phone_taobao) {
            $goods_cate = $this->input->post('goods_cate');
            $app_img1_base64 = $this->input->post('app_img1_base64');
            $app_img2_base64 = $this->input->post('app_img2_base64');
            $search_plat = ($trade_info->plat_id == '4') ? 4 : 3;           // 手机淘宝、与手机京东共文件，按平台区分
            // 淘抢购相差时间
            $tqg_time = $this->input->post('tqg_time');                     // 淘抢购开场时间
            $start_time = $this->input->post('start_time');                 // 活动开始时间
            $end_time = $this->input->post('end_time');                     // 活动结束时间

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => $search_plat,
                'classify1' => strtotime($tqg_time),
                'classify2' => strtotime($start_time),
                'classify3' => strtotime($end_time),
                'classify4' => '',
                'order_way' => '',
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            // search_img => 淘抢购主图  search_img2 => 商品主图
            $goods_img = $tmp_info['search_img2'];

            $tmp_info['kwd'] = $this->input->post('type');      // 淘抢购  即将售罄  围观抢  爆款返场  品牌抢购
            $tmp_info['low_price'] = '';
            $tmp_info['high_price'] = '';
            $tmp_info['discount'] = '';
            $tmp_info['area'] = '';
            $tmp_info['goods_cate'] = $goods_cate;
            $tmp_info['num'] = $buy_num;
            $tmp_info['surplus_num'] = 0;
            $trade_search[] = $tmp_info;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        $order_fee_obj = $this->fee->order_fee_obj($trade_info->trade_type, $price * $buy_num);
        if (empty($order_fee_obj)) {
            exit(json_encode(['code' => 7, 'msg' => '系统错误']));
        }
        // Update table trade_info
        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => 0,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];
        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id];
        // Update table trade_item
        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];
        $trade_item_key = ['trade_id' => $trade_id];
        // Begin to execute SQL
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);
        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_search', $trade_search);
        $this->write_db->close();

        exit(json_encode(['code' => 0, 'msg' => 'ok']));
    }

    /** 聚划算 第二步 商品信息提交 **/
    public function jhs_step2_1_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        $old_trade_search = $this->trade->get_trade_search($trade_id);
        if (empty($user_id)) {
            exit(json_encode(['code' => 1, 'msg' => '用户未登录']));
        }

        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $price = floatval($this->input->post('price'));
        $show_price = $this->input->post('show_price');
        $buy_num = intval($this->input->post('buy_num'));
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $active_type = $this->input->post('active_type');
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        // 数据验证
        if ($goods_name == '') {
            exit(json_encode(['code' => 2, 'msg' => '请输入商品名称']));
        }
        if ($goods_url == '') {
            exit(json_encode(['code' => 3, 'msg' => '请输入商品链接']));
        }
        if (empty($item_id)) {
            exit(json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']));
        }
        if ($price <= 0) {
            exit(json_encode(['code' => 4, 'msg' => '请输入商品价格']));
        }
        if ($buy_num < 1) {
            exit(json_encode(['code' => 5, 'msg' => '请输入购买件数']));
        }
        if (!in_array($active_type, ['jhs_link', 'jhs_search'])) {
            exit(json_encode(['code' => 5, 'msg' => '请选择使用"手机聚划算——链接直拍“搜索框"查找商品、或者使用"手机淘宝——聚划算"查找商品']));
        }

        $trade_search = [];
        $goods_img = '';
        $phone_taobao = trim($this->input->post('phone_taobao'));
        if ($phone_taobao) {
            $goods_cate = $this->input->post('goods_cate');
            $app_img1_base64 = $this->input->post('app_img1_base64');
            $app_img2_base64 = $this->input->post('app_img2_base64');
            $search_plat = ($trade_info->plat_id == '4') ? 4 : 3;           // 手机淘宝、与手机京东共文件，按平台区分

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => $search_plat,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => $this->input->post('type')      // 淘抢购  即将售罄  围观抢  爆款返场  品牌抢购
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            // search_img => 淘抢购主图  search_img2 => 商品主图
            $goods_img = $tmp_info['search_img2'];
            $tmp_info['kwd'] = $active_type;       // 手机聚划算——链接直拍 => jhs_link；手机淘宝——聚划算 => jhs_search
            $tmp_info['low_price'] = '';
            $tmp_info['high_price'] = '';
            $tmp_info['discount'] = '';
            $tmp_info['area'] = '';
            $tmp_info['goods_cate'] = $goods_cate ? $goods_cate : '';
            $tmp_info['num'] = $buy_num;
            $tmp_info['surplus_num'] = 0;
            $trade_search[] = $tmp_info;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        $order_fee_obj = $this->fee->order_fee_obj($trade_info->trade_type, $price * $buy_num);
        if (empty($order_fee_obj)) {
            exit(json_encode(['code' => 7, 'msg' => '系统错误']));
        }
        // Update table trade_info
        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => 0,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];
        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id];
        // Update table trade_item
        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];
        $trade_item_key = ['trade_id' => $trade_id];
        // Begin to execute SQL
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);
        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_search', $trade_search);
        $this->write_db->close();

        exit(json_encode(['code' => 0, 'msg' => 'ok']));
    }

    /** 流程任务单第二步 */
    private function traffic_step2($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['app_search'] = $trade_search['app'];
        // 关键词分布
        $this->load->model('Traffic_Model', 'traffic');
        $traffic_list = $this->traffic->get_traffic_distribution($trade_info->id);
        $data['traffic_list'] = [];
        $exec_days = 0;
        $start_time = 0;
        if ($traffic_list) {
            foreach ($traffic_list as $item) {
                if ($start_time <= 0) {
                    $start_time = $item->start_time;
                }
                $data['traffic_list'][$item->search_kwd][] = $item->total_traffics;
                $exec_days += 1;
            }
            $traffic_item = $traffic_list[0];
            $data['rate_list'] = json_decode($traffic_item->traffic_dist, true);
        } else {
            $data['traffic_list'][''] = ['', '', '', '', '', ''];
            $data['rate_list'] = ['collect_goods' => '0.3', 'add_to_cart' => '0.3', 'collect_shop' => '0.4', 'get_coupon' => '0.3', 'item_evaluate' => '0.4', 'like_goods' => '0.4', 'compare_goods' => '0.9'];
        }

        $data['exec_days'] = ($exec_days == 0) ? 6 : ($exec_days / count($data['traffic_list']));
        $data['start_time'] = ($start_time == 0) ? date('Y-m-d') : date("Y-m-d", $start_time);
        $data['start_mins'] = ($start_time == 0) ? date('H:i', time() + 1800) : date("H:i", $start_time);
        // 各选项费用
        $traffic_type_list = $this->traffic->get_traffic_list(time());
        $data['normal_price'] = $traffic_type_list['normal_price']['price'];
        unset($traffic_type_list['normal_price']);
        $data['traffic_type_list'] = $traffic_type_list;

        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        $this->load->view('trade/traffic_step2', $data);
    }

    /** 流程任务单第三步 **/
    private function traffic_step4($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $data['trade_select'] = $this->trade->trade_select($trade_info);

        // 增值服务
        $res = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
        $trade_service = [];
        foreach ($res as $v) {
            $trade_service[$v->service_name] = $v;
        }
        // 访客入店时间分布
        $data['has_type_custom'] = isset($trade_service['dist_type_custom']);
        $data['has_type_curve'] = isset($trade_service['dist_type_curve']);
        $data['has_type_random'] = isset($trade_service['dist_type_random']);
        if ($data['has_type_custom']) {
            $data['custom_list'] = json_decode($trade_service['dist_type_custom']->param, true);
        } else {
            $data['custom_list'] = [];
        }
        // 加赏活动佣金
        $data['has_add_reward'] = isset($trade_service['add_reward']);
        $data['add_reward_val'] = isset($trade_service['add_reward']) ? floatval($trade_service['add_reward']->price) : 0.2;
        // 优先审单
        $data['has_first_check'] = isset($trade_service['first_check']);
        // 千人千面设置 地域限制
        $data['has_area_limit'] = isset($trade_service['area_limit']);
        $data['area_limit_list'] = isset($trade_service['area_limit']) ? explode(',', $trade_service['area_limit']->param) : [];
        // 千人千面设置 性别选择
        $data['has_sex_limit'] = isset($trade_service['sex_limit']);
        $data['sex_limit_val'] = isset($trade_service['sex_limit']) ? $trade_service['sex_limit']->param : '1';
        // 优先执行
        $data['has_first_exec'] = isset($trade_service['first_exec']);
        $data['has_disposable'] = isset($trade_service['disposable']);
        // 任务单分布
        $this->load->model('Traffic_Model', 'traffic');
        $data['traffic_list'] = $this->traffic->get_traffic_total($trade_info->id);
        $this->load->view('trade/traffic_step4', $data);
    }

    /** 流程任务单第四步 **/
    private function traffic_step5($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['user_info'] = $this->user->get_user_info($user_id);

        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['shop_ww'] = $shop_info->shop_ww;

        $this->load->view('trade/traffic_step5', $data);
    }

    /** 流量商品信息提交 */
    public function traffic_step2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            exit(json_encode(['code' => 1, 'msg' => '用户未登录']));
        }

        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $show_price = $this->input->post('show_price');
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $cart_nums = $this->input->post('cart_nums');
        $order_prompt = $this->input->post('order_prompt');
        if ($goods_name == '') {
            exit(json_encode(['code' => 2, 'msg' => '请输入商品名称']));
        }
        if ($goods_url == '') {
            exit(json_encode(['code' => 3, 'msg' => '请输入商品链接']));
        }
        // 手机端任务
        $phone_taobao = trim($this->input->post('phone_taobao'));
        if (!$phone_taobao) {
            exit(json_encode(['code' => 10, 'msg' => '非法参数']));
        }
        $app_kwd = $this->input->post('app_kwd');
        $app_low_price = $this->input->post('app_low_price');
        $app_high_price = $this->input->post('app_high_price');
        $app_discount_text = $this->input->post('app_discount_text');
        $app_area = $this->input->post('app_area');
        $goods_cate = $this->input->post('goods_cate');
        $app_order_way = $this->input->post('app_order_way');
        $app_img1_base64 = trim($this->input->post('app_img1_base64'));
        $app_img2_base64 = trim($this->input->post('app_img2_base64'));
        $start_time = $this->input->post('start_time');
        $days_list = $this->input->post('days_list');
        $rate_list = $this->input->post('rate_list');
        $rate_list_map = [];
        foreach ($rate_list as $item) {
            $rate_list_map[$item['type']] = floatval($item['rate']);
        }
        // 关键词验证
        foreach ($app_kwd as $key_words) {
            if (empty(trim($key_words))) {
                exit(json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']));
            }
        }
        if (empty($app_img1_base64)) {
            exit(json_encode(['code' => 7, 'msg' => '请上传搜索商品主图']));
        }
        if (strtotime($start_time) < time()) {
            exit(json_encode(['code' => 7, 'msg' => '任务开始时间小于了当前系统时间，请确认']));
        }
        // 任务单记录
        $trade_info = $this->trade->get_trade_info($trade_id);
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        if (empty($item_id)) {
            exit(json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']));
        }
        // 搜索关键词
        $trade_search = [];
        foreach ($app_kwd as $key => $item) {
            $trade_search[] = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => ($trade_info->plat_id == '4') ? 4 : 3,           // 手机淘宝、与手机京东共文件，按平台区分
                'search_img' => $app_img1_base64,
                'search_img2' => $app_img2_base64,
                'kwd' => $item,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'low_price' => $app_low_price[$key],
                'high_price' => $app_high_price[$key],
                'area' => $app_area[$key],
                'discount' => $app_discount_text[$key],
                'order_way' => $app_order_way[$key],
                'goods_cate' => $goods_cate[$key],
                'num' => 0,
                'surplus_num' => array_sum($days_list[$key])
            ];
        }
        // 浏览分布分配
        $trade_traffic = [];
        $total_nums = 0;
        foreach ($days_list as $key => $item_list) {
            $current_start_time = strtotime($start_time);
            foreach ($item_list as $item) {
                $plat_id = ($trade_info->plat_id == '2') ? '1' : $trade_info->plat_id;
                $tmp_arr = [
                    'plat_id' => intval($plat_id),
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 0,
                    'search_id' => 0,
                    'search_kwd' => $app_kwd[$key],
                    'total_traffics' => intval($item),
                    'traffic_dist' => json_encode($rate_list_map),
                    'start_time' => $current_start_time,
                    'collect_goods' => isset($rate_list_map['collect_goods']) ? round(floatval($rate_list_map['collect_goods']) * intval($item)) : 0,
                    'add_to_cart' => isset($rate_list_map['add_to_cart']) ? round(floatval($rate_list_map['add_to_cart']) * intval($item)) : 0,
                    'collect_shop' => isset($rate_list_map['collect_shop']) ? round(floatval($rate_list_map['collect_shop']) * intval($item)) : 0,
                    'get_coupon' => isset($rate_list_map['get_coupon']) ? round(floatval($rate_list_map['get_coupon']) * intval($item)) : 0,
                    'item_evaluate' => isset($rate_list_map['item_evaluate']) ? round(floatval($rate_list_map['item_evaluate']) * intval($item)) : 0,
                    'compare_goods' => isset($rate_list_map['compare_goods']) ? round(floatval($rate_list_map['compare_goods']) * intval($item)) : 0,
                    'surplus_num' => intval($item),
                    'like_goods' => isset($rate_list_map['like_goods']) ? round(floatval($rate_list_map['like_goods']) * intval($item)) : 0
                ];
                $trade_traffic[] = $tmp_arr;
                $current_start_time = strtotime(date('Y-m-d', $current_start_time + 86400)) + 60;
                $total_nums += intval($item);
            }
        }
        // 任务单信息
        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => 0,
            'buy_num' => 0,
            'total_num' => $total_nums,
            // 每2单赠送1单
            'award_num' => intval((intval($total_nums) / 2)),
            'is_pc' => 0,
            'is_phone' => 1,
            'total_fee' => 0.8,
            'base_reward' => 0.5,
            'snatch_gold' => 0,
            'is_show' => 1,
            'trade_step' => 4
        ];
        // 商品信息
        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $app_img1_base64,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => 0,
            'show_price' => $show_price,
            'buy_num' => (min($cart_nums) >= 1) ? min($cart_nums) : 1,
            'color' => $color,
            'size' => $size,
            'order_prompt' => $order_prompt,
            'weight' => (max($cart_nums) >= 1) ? max($cart_nums) : 1,
        ];

        // 更新数据
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, ['id' => $trade_id, 'user_id' => $user_id]);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, ['trade_id' => $trade_id]);
        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_search', $trade_search);
        $this->write_db->delete('rqf_trade_traffic', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_traffic', $trade_traffic);
        $res = $this->write_db->query('update rqf_trade_traffic t, rqf_trade_search s set t.search_id = s.id where t.trade_id = s.trade_id and t.search_kwd = s.kwd ');
        $this->write_db->close();

        exit(json_encode(['code' => 0, 'msg' => 'ok']));
    }

    /** 流量提交增值服务 */
    public function traffic_step4_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = intval($this->session->userdata('user_id'));
        if (empty($user_id)) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }

        $trade_info = $this->trade->get_trade_info($trade_id);
        $trade_service = [];
        $service_point = 0;
        // 访客入店时间分布
        $dist_type = $this->input->post('dist_type');
        if ($dist_type) {
            if ('custom' == $dist_type) {
                $dist_time_list = $this->input->post('dist_time_list');
                if (array_sum($dist_time_list) != 100) {
                    exit(json_encode(['error' => 1, 'message' => '访客入店时间分布-自定义时间段分布占比合计不等于100%，请确认']));
                }
                $tmp_info = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'service_name' => 'dist_type_custom',
                    'price' => 6,
                    'num' => 1,
                    'pay_point' => 6,
                    'param' => json_encode($dist_time_list),
                    'comments' => '访客入店时间分布-自定义'
                ];
                $trade_service[] = $tmp_info;
                $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            } elseif ('curve' == $dist_type) {
                $tmp_info = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'service_name' => 'dist_type_curve',
                    'price' => 6,
                    'num' => 1,
                    'pay_point' => 6,
                    'param' => '',
                    'comments' => '访客入店时间分布-网购用户习惯曲线分布'
                ];
                $trade_service[] = $tmp_info;
                $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            } elseif ('random' == $dist_type) {
                $tmp_info = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'service_name' => 'dist_type_random',
                    'price' => 3,
                    'num' => 1,
                    'pay_point' => 3,
                    'param' => '',
                    'comments' => '访客入店时间分布-随机分布'
                ];
                $trade_service[] = $tmp_info;
                $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            }
        }

        // 性别选择
        $sex_limit = $this->input->post('sex_limit');
        if ($sex_limit == '1') {
            $sex_limit_val = $this->input->post('sex_limit_val');
            $tmp_price = 0.3;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'sex_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $sex_limit_val,
                'comments' => '访客类型-性别限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 地域限制
        $area_limit = $this->input->post('area_limit');
        if ($area_limit == '1') {
            $area_limit_city = $this->input->post('area_limit_city');
            $tmp_price = 0.3;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'area_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => implode(',', $area_limit_city),
                'comments' => '访客类型-地域限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 优先审单
        $first_check = $this->input->post('first_check');
        if ($first_check) {
            $first_check_val = 1;
            $tmp_price = FIRST_CHECK_PRICE;
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_check',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => '',
                'comments' => '优先审核'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $first_check_val = 0;
        }
        // 加赏活动佣金
        $add_reward = $this->input->post('add_reward');
        $add_reward_point = floatval($this->input->post('add_reward_point'));
        if ($add_reward) {
            $add_reward_point = max($add_reward_point, 0.2);
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward',
                'price' => $add_reward_point,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($add_reward_point, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '加赏活动佣金'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_reward_point = 0;
        }
        // 优先执行
        $first_exec = intval($this->input->post('first_exec'));
        if ($first_exec) {
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_exec',
                'price' => 10,
                'num' => 1,
                'pay_point' => 10,
                'param' => '',
                'comments' => '优先执行'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        $disposable = intval($this->input->post('disposable'));
        if ($disposable) {
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'disposable',
                'price' => 3,
                'num' => 1,
                'pay_point' => 3,
                'param' => '',
                'comments' => '一次性投放'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 合计计算佣金
        $order_fee_point = 0;
        $this->load->model('Traffic_Model', 'traffic');
        $traffic_list = $this->traffic->get_traffic_total($trade_id);
        foreach ($traffic_list as $item) {
            $order_fee_point += floatval($item['price'] * $item['cnts']);
        }
        $trade_point = bcadd($order_fee_point, $trade_info->order_dis_point, 4);
        $trade_point = bcadd($trade_point, $service_point, 4);
        $trade_info_upd = [
            'add_reward' => bcmul($add_reward_point, ADD_REWARD_POINT_PERCENT, 4),
            'pic_reward' => 0,
            'recommend_weight' => 0,
            'extend_cycle' => 0,
            'first_check' => $first_check_val,
            'service_point' => $service_point,
            'order_fee_point' => $order_fee_point,
            'trade_point' => $trade_point,
            'trade_step' => 5,
            'plat_refund' => 0,
            'eval_type' => 0,
            'pc_num' => 0,
            'phone_num' => 0,
            'no_print' => 0
        ];

        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 4];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        if ($this->write_db->affected_rows()) {
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_id]);
            $this->write_db->insert_batch('rqf_trade_service', $trade_service);
        }
        $this->write_db->close();

        exit(json_encode(['error' => 0]));
    }

    /** * 退款订单 － 第二步 **/
    private function refund_step2($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['taobao_search'] = $trade_search['pc_taobao'];
        $data['tmall_search'] = $trade_search['pc_tmall'];
        $data['app_search'] = $trade_search['app'];
        $plat_list = $this->conf->plat_list();
        $data['plat_name'] = $plat_list[$trade_info->plat_id]['pname'];
        if ($data['plat_name'] == '天猫') {
            $data['plat_name'] = '淘宝';
        }

        $this->load->view('trade/refund_step2', $data);
    }

    /** * 退款订单 － 第二步 商品数据提交 **/
    public function refund_step2_1_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        $old_trade_search = $this->trade->get_trade_search($trade_id);
        if (empty($user_id)) {
            echo json_encode(['code' => 1, 'msg' => '用户未登录']);
            return;
        }

        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $price = floatval($this->input->post('price'));
        $show_price = $this->input->post('show_price');
        $buy_num = intval($this->input->post('buy_num'));
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        if ($goods_name == '') {
            echo json_encode(['code' => 2, 'msg' => '请输入商品名称']);
            return;
        }

        if ($goods_url == '') {
            echo json_encode(['code' => 3, 'msg' => '请输入商品链接']);
            return;
        }
        if (empty($item_id)) {
            echo json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']);
            return;
        }
        if (empty($price)) {
            echo json_encode(['code' => 4, 'msg' => '请输入商品价格']);
            return;
        }

        if ($buy_num < 1) {
            echo json_encode(['code' => 5, 'msg' => '请输入购买件数']);
            return;
        }

        $trade_search = [];

        $goods_img = '';

        $pc_taobao = trim($this->input->post('pc_taobao'));
        if ($pc_taobao) {
            $tb_kwd = $this->input->post('tb_kwd');
            $tb_classify1 = $this->input->post('tb_classify1');
            $tb_classify2 = $this->input->post('tb_classify2');
            $tb_classify3 = $this->input->post('tb_classify3');
            $tb_classify4 = $this->input->post('tb_classify4');
            $tb_low_price = $this->input->post('tb_low_price');
            $tb_high_price = $this->input->post('tb_high_price');
            $tb_area = $this->input->post('tb_area');
            $tb_img_base64 = $this->input->post('tb_img_base64');

            // 关键词验证
            foreach ($tb_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 1,
                'low_price' => $tb_low_price,
                'high_price' => $tb_high_price,
                'area' => $tb_area
            ];

            if ($tb_img_base64) {
                $tb_img = $this->base64->to_img($tb_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tb_img;
                qiniu_upload(ltrim($tb_img, '/'));
            } else {
                $old_taobao_search_0 = $old_trade_search['pc_taobao'][0];
                $tmp_info['search_img'] = $old_taobao_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_taobao_search = $old_trade_search['pc_taobao'];

            foreach ($tb_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tb_classify1[$k];
                $tmp_info['classify2'] = $tb_classify2[$k];
                $tmp_info['classify3'] = $tb_classify3[$k];
                $tmp_info['classify4'] = $tb_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_taobao_search[$k]) ? $old_taobao_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $pc_tmall = $this->input->post('pc_tmall');
        if ($pc_tmall) {
            $tm_kwd = $this->input->post('tm_kwd');
            $tm_classify1 = $this->input->post('tm_classify1');
            $tm_classify2 = $this->input->post('tm_classify2');
            $tm_classify3 = $this->input->post('tm_classify3');
            $tm_classify4 = $this->input->post('tm_classify4');
            $tm_low_price = $this->input->post('tm_low_price');
            $tm_high_price = $this->input->post('tm_high_price');
            $tm_area = $this->input->post('tm_area');
            $tm_img_base64 = $this->input->post('tm_img_base64');

            // 关键词验证
            foreach ($tm_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '天猫关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => 2,
                'low_price' => $tm_low_price,
                'high_price' => $tm_high_price,
                'area' => $tm_area
            ];

            if ($tm_img_base64) {
                $tm_img = $this->base64->to_img($tm_img_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $tm_img;
                qiniu_upload(ltrim($tm_img, '/'));
            } else {
                $old_tmall_search_0 = $old_trade_search['pc_tmall'][0];
                $tmp_info['search_img'] = $old_tmall_search_0->search_img;
            }

            $goods_img = $tmp_info['search_img'];

            $old_tmall_search = $old_trade_search['pc_tmall'];

            foreach ($tm_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['classify1'] = $tm_classify1[$k];
                $tmp_info['classify2'] = $tm_classify2[$k];
                $tmp_info['classify3'] = $tm_classify3[$k];
                $tmp_info['classify4'] = $tm_classify4[$k];
                $tmp_info['discount'] = '';
                $tmp_info['order_way'] = '';
                $tmp_info['num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_tmall_search[$k]) ? $old_tmall_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        $phone_taobao = trim($this->input->post('phone_taobao'));
        if ($phone_taobao) {
            $app_kwd = $this->input->post('app_kwd');
            $app_low_price = $this->input->post('app_low_price');
            $app_high_price = $this->input->post('app_high_price');
            $app_discount_text = $this->input->post('app_discount_text');
            $app_area = $this->input->post('app_area');
            $goods_cate = $this->input->post('goods_cate');
            $app_order_way = $this->input->post('app_order_way');
            $app_img1_base64 = $this->input->post('app_img1_base64');
            $app_img2_base64 = $this->input->post('app_img2_base64');
            $search_plat = ($trade_info->plat_id == '4') ? 4 : 3;           // 手机淘宝、与手机京东共文件，按平台区分
            if ($trade_info->trade_type == '7') {
                $app_kwd = ['--'];
            }

            // 关键词验证
            foreach ($app_kwd as $v) {
                if ($v == '') {
                    echo json_encode(['code' => 6, 'msg' => '手机淘宝关键词不能为空']);
                    return;
                }
            }

            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'plat_id' => $search_plat,
                'classify1' => '',
                'classify2' => '',
                'classify3' => '',
                'classify4' => '',
                'order_way' => is_null($app_order_way) ? '' : $app_order_way
            ];

            if ($app_img1_base64) {
                $app_img1 = $this->base64->to_img($app_img1_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img'] = CDN_URL . $app_img1;
                qiniu_upload(ltrim($app_img1, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img'] = $old_app_search_0->search_img;
            }

            if ($app_img2_base64) {
                $app_img2 = $this->base64->to_img($app_img2_base64, UPLOAD_TRADE_INFO_DIR);
                $tmp_info['search_img2'] = CDN_URL . $app_img2;
                qiniu_upload(ltrim($app_img2, '/'));
            } else {
                $old_app_search_0 = $old_trade_search['app'][0];
                $tmp_info['search_img2'] = $old_app_search_0->search_img2;
            }

            $goods_img = $tmp_info['search_img'];
            $old_app_search = $old_trade_search['app'];
            foreach ($app_kwd as $k => $v) {
                $tmp_info['kwd'] = $v;
                $tmp_info['low_price'] = isset($app_low_price[$k]) ? $app_low_price[$k] : '';
                $tmp_info['high_price'] = isset($app_high_price[$k]) ? $app_high_price[$k] : '';
                $tmp_info['discount'] = isset($app_discount_text[$k]) ? rtrim($app_discount_text[$k], ',') : '';
                $tmp_info['area'] = isset($app_area[$k]) ? $app_area[$k] : '';
                $tmp_info['goods_cate'] = isset($goods_cate[$k]) ? $goods_cate[$k] : '';
                $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
                $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
                $trade_search[] = $tmp_info;
            }
        }

        if ($pc_taobao && $pc_tmall) {
            $is_pc = 3;
        } elseif ($pc_taobao && !$pc_tmall) {
            $is_pc = 1;
        } elseif (!$pc_taobao && $pc_tmall) {
            $is_pc = 2;
        } else {
            $is_pc = 0;
        }

        $is_phone = $phone_taobao ? 1 : 0;
        $order_fee_obj = $this->fee->order_fee_obj('1', $price * $buy_num);     // 计价暂时同文字搜索订单
        if (in_array($trade_info->trade_type, ['115', '215'])) {
            $order_fee_obj->total_fee = $order_fee_obj->total_fee - 1;
            $order_fee_obj->base_reward = $order_fee_obj->base_reward - 1;
        }
        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }

        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => $is_pc,
            'is_phone' => $is_phone,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1
        ];

        $trade_info_key = [
            'id' => $trade_id,
            'user_id' => $user_id
        ];

        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size
        ];

        $trade_item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);

        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);

        $this->write_db->insert_batch('rqf_trade_search', $trade_search);

        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /** * 退款订单 － 第二步 商品快递信息提交 **/
    public function refund_step2_2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $is_post = intval($this->input->post('is_post'));
        if ($is_post) {
            $post_fee = 0;
        } else {
            $post_fee = POST_FEE;
        }

        $trade_info_upd = ['is_post' => $is_post, 'post_fee' => $post_fee];
        $key = ['id' => $trade_id, 'user_id' => $user_id];

        $trade_item_upd = ['is_post' => $is_post];
        $item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->close();

        echo 0;
    }

    /** * 退款订单 － 第二步 提交 **/
    public function refund_step2_3_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info_upd = ['trade_step' => 3];
        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->close();
        echo 0;
    }

    /** * 退款订单 － 第三步 **/
    private function refund_step3($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $trade_nums = ['1', '3', '5', '10', '20', '100', '250'];
        $data['is_custom'] = !in_array($trade_info->total_num, $trade_nums);
        $data['custom_val'] = in_array($trade_info->total_num, $trade_nums) ? 1 : $trade_info->total_num;
        // 订单搜索词
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_info->id])->result();
        if (count($trade_search) == 1) {
            $trade_search[0]->num = $trade_info->total_num;
        }
        $data['trade_search'] = $trade_search;
        $data['plat_names'] = ['1' => '淘宝', '2' => '天猫', '3' => '手机淘宝', '4' => '手机京东', '5' => '会场'];

        $this->load->view('trade/refund_step3', $data);
    }

    /** * 退款订单 － 第三步 提交 **/
    public function refund_step3_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            echo 1;
            return;
        }

        $trade_info = $this->trade->get_trade_info($trade_id);
        $total_num = intval($this->input->post('total_num'));
        $total_num_custom = intval($this->input->post('total_num_custom'));
        $nums = $this->input->post('nums');
        $order_prompt = $this->input->post('order_prompt');
        if ($total_num == 0) {
            $total_num = $total_num_custom;
        }
        if ($total_num == 0) {
            echo 2;
            return;
        }

        $pc_num = $trade_info->is_pc ? $total_num : 0;
        $phone_num = $trade_info->is_phone ? $total_num : 0;
        // 活动费用
        $order_fee_point = bcmul($trade_info->total_fee, $total_num, 2);
        // 手机端订单分布
        $order_dis_point = $trade_info->is_phone ? bcmul($total_num, ORDER_DIS_PRICE, 2) : 0;
        // 手机端赏金
        $phone_reward = $trade_info->is_phone ? PHONE_REWARD : 0;

        // 活动押金
        $trade_deposit = 0;
        // 活动保证金
        $trade_payment = 0;
        // 活动邮费
        $trade_post_fee = 0;

        // 赠送流量单（浏览+加购）
        $award_num = $this->award_num($total_num);

        $trade_info_upd = [
            'total_num' => $total_num,
            'award_num' => $award_num,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'phone_reward' => $phone_reward,
            'order_fee_point' => $order_fee_point,
            'order_dis_point' => $order_dis_point,
            'trade_payment' => $trade_payment,
            'trade_post_fee' => $trade_post_fee,
            'trade_deposit' => $trade_deposit,
            'trade_step' => 4
        ];

        $key = ['id' => $trade_id, 'user_id' => $user_id];
        $trade_item_upd = ['order_prompt' => $order_prompt];
        $trade_search = $this->db->get_where('rqf_trade_search', ['trade_id' => $trade_id])->result();
        if (count($nums) != count($trade_search)) {
            echo 3;
            return;
        }

        $nums_sum = 0;
        $trade_search_upd = [];
        foreach ($nums as $k => $v) {
            $nums_sum += $v;
            $trade_search_upd[] = ['id' => $trade_search[$k]->id, 'num' => $v, 'surplus_num' => $v];
        }

        if ($nums_sum != $total_num) {
            echo 3;
            return;
        }

        $item_key = ['trade_id' => $trade_id];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $item_key);
        $this->write_db->update_batch('rqf_trade_search', $trade_search_upd, 'id');
        $this->write_db->close();

        echo 0;
    }

    /** * 退款订单 － 第四步 **/
    private function refund_step4($trade_info)
    {
        $data = $this->data;
        $user_id = intval($this->session->userdata('user_id'));
        $data['trade_info'] = $trade_info;
        $data['trade_select'] = $this->trade->trade_select($trade_info);
        $data['interval_list'] = $this->conf->interval_list();
        // 活动单数超过10单，删除自定义好评及评价内容
        $this->write_db = $this->load->database('write', true);
        if ($trade_info->total_num > 10) {
            $result = $this->write_db->query('DELETE FROM `rqf_trade_service` WHERE `trade_id` = ? AND `service_name` in ?', [intval($trade_info->id), ['setting_eval', 'setting_picture']]);
            $this->write_db->delete('rqf_setting_eval', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        } elseif ($trade_info->total_num > 5) {
            // 活动单数超过5单，删除图文好评及评价内容
            $this->write_db->delete('rqf_trade_service', ['trade_id' => intval($trade_info->id), 'service_name' => 'setting_picture']);
            $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
        }
        $this->write_db->close();
        // 增值服务
        $res = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
        $trade_service = [];
        foreach ($res as $v) {
            $trade_service[$v->service_name] = $v;
        }

        // 提升完成活动速度
        $data['add_speed_val'] = isset($trade_service['add_speed']) ? $trade_service['add_speed']->price : 0;
        // 加赏活动佣金
        $data['has_add_reward'] = isset($trade_service['add_reward']);
        $data['add_reward_val'] = isset($trade_service['add_reward']) ? intval($trade_service['add_reward']->price) : 3;
        // 优先审单
        $data['has_first_check'] = isset($trade_service['first_check']);
        // 千人千面设置 地域限制
        $data['area_limit'] = isset($trade_service['area_limit']);
        $data['area_limit_list'] = isset($trade_service['area_limit']) ? explode(',', $trade_service['area_limit']->param) : [];
        // 千人千面设置 性别选择
        $data['sex_limit'] = isset($trade_service['sex_limit']);
        $data['sex_limit_val'] = isset($trade_service['sex_limit']) ? $trade_service['sex_limit']->param : '0';
        // 仅限钻级别的买号可接此活动
        $data['reputation_limit'] = isset($trade_service['reputation_limit']);
        // 仅限淘气值1000以上买号可接此活动
        $data['taoqi_limit'] = isset($trade_service['taoqi_limit']);
        // 定时发布
        $data['has_set_time'] = isset($trade_service['set_time']);
        $data['set_time_val'] = isset($trade_service['set_time']) ? $trade_service['set_time']->param : '';
        // 分时发布
        $data['custom_time_price'] = isset($trade_service['custom_time_price']) ? json_decode($trade_service['custom_time_price']->param, true) : [];
        $data['set_time_pre_val'] = intval($trade_info->start_time) > 0 ? date('Y-m-d', $trade_info->start_time) : date('Y-m-d');
        // 分时单数
        $interval_nums = [1, 2, 5, 10, 20, 50];
        foreach ($interval_nums as $k => $v) {
            if ($v >= $trade_info->total_num && ($v != 1)) {
                unset($interval_nums[$k]);
            }
        }

        $data['interval_nums'] = $interval_nums;
        // 间隔发布
        /**
         * $data['has_set_interval'] = (isset($trade_service['set_interval'])) || ($trade_info->total_num >= 20);
         * if ($trade_info->total_num == 1) {
         * $data['has_set_interval'] = false;
         * }
         * $data['set_interval_disabled'] = (($trade_info->total_num >= 20) || ($trade_info->total_num == 1));
         * */
        $data['set_interval_disabled'] = false;
        $data['has_set_interval'] = isset($trade_service['set_interval']);
        if (isset($trade_service['set_interval'])) {
            $params = explode('|', $trade_service['set_interval']->param);
            $set_interval_val = $params[0];
            $interval_num_val = $params[1];
        } else {
            $set_interval_val = '10m';
            $interval_num_val = '1';
        }
        $data['set_interval_val'] = $set_interval_val;
        $data['interval_num_val'] = $interval_num_val;

        // 金币小计
        $point_subtotal = $trade_info->total_fee;
        if ($trade_info->is_phone) {
            $point_subtotal = bcadd($point_subtotal, ORDER_DIS_PRICE, 4);
        }
        $data['point_subtotal'] = $point_subtotal;

        // 增值服务优惠折扣
        $discount_list = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        foreach ($discount_list as $item) {
            $data['discount'][$item->service_name] = intval($item->discount);
        }

        $this->load->view('trade/refund_step4', $data);
    }

    /** * 退款订单 － 第四步 提交 **/
    public function refund_step4_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }
        $this->write_db = $this->load->database('write', true);
        $trade_info = $this->trade->get_trade_info($trade_id);
        $trade_service = [];
        $service_point = 0;
        // 增值服务优惠折扣
        $discount_query = $this->db->query('select service_name, discount from rqf_added_service_discount where user_id = ? and start_time <= ? and end_time > ? and discount < 100 ', [$user_id, time(), time()])->result();
        $discount_list = [];
        foreach ($discount_query as $item) {
            $discount_list[$item->service_name] = intval($item->discount);
        }

        // 提升完成活动速度
        $add_speed = intval($this->input->post('add_speed'));
        if ($add_speed) {
            if (!in_array($add_speed, [10, 20, 30])) {
                $add_speed = 10;
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_speed',
                'price' => $add_speed,
                'num' => 1,
                'pay_point' => $add_speed,
                'param' => '',
                'comments' => '提升完成活动速度'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_speed = 0;
        }

        // 加赏活动佣金
        $add_reward = $this->input->post('add_reward');
        $add_reward_point = intval($this->input->post('add_reward_point'));
        if ($add_reward) {
            $add_reward_point = max($add_reward_point, 2);
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward',
                'price' => $add_reward_point,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($add_reward_point, $trade_info->total_num, 4),
                'param' => '',
                'comments' => '加赏活动佣金'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $add_reward_point = 0;
        }

        // 优先审核
        $first_check = $this->input->post('first_check');
        if ($first_check) {
            $first_check_val = 1;
            $tmp_price = FIRST_CHECK_PRICE;
            if (array_key_exists('first_check', $discount_list) && $discount_list['first_check'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['first_check'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'first_check',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => '',
                'comments' => '优先审核'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $first_check_val = 0;
        }

        // 定时发布
        $set_time = $this->input->post('set_time');
        $set_time_val = $this->input->post('set_time_val');
        if ($set_time && $set_time_val) {
            if (strtotime($set_time_val) <= time()) {
                exit(json_encode(['error' => 1, 'message' => '设置的定时发布时间应大于当前时间']));
            }
            $tmp_price = 3;
            if (array_key_exists('set_time', $discount_list) && $discount_list['set_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_time_val,
                'comments' => '定时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $start_time = strtotime($set_time_val);
        } else {
            $start_time = 0;
        }
        // 定时结束任务
        $set_over_time = $this->input->post('set_over_time');
        $set_over_time_val = $this->input->post('set_over_time_val');
        if ($set_over_time && $set_over_time_val) {
            $compare_time = ($set_time && $set_time_val) ? strtotime($set_time_val) : time();
            if (strtotime($set_over_time_val) <= $compare_time + 3600) {
                exit(json_encode(['error' => 1, 'message' => '结束时间、与活动时间至少错开一个小时']));
            }
            $tmp_price = 2;
            if (array_key_exists('set_over_time', $discount_list) && $discount_list['set_over_time'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_over_time'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_over_time',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => $set_over_time_val,
                'comments' => '定时结束'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 活动分时发布、与间隔发布 目前暂定为互斥关系 二选一
        $interval_param = '';
        $custom_time_price = $this->input->post('custom_time_price');       // 分时发布
        $set_interval = $this->input->post('set_interval');                 // 间隔发布
        if ($custom_time_price && $set_interval) {
            exit(json_encode(['error' => 1, 'message' => '分时发布、与间隔发布暂不可以同时发布 ，请确认']));
        } elseif ($custom_time_price) {
            // 分时发布
            $total_nums = 0;
            $custom_time_price_list = [];
            // 查看分时发布开始时间
            $set_time_pre_val = empty(trim($this->input->post('set_time_pre_val'))) ? strtotime('+1 hour') : strtotime(trim($this->input->post('set_time_pre_val')));
            $reference_hour = date('H', $set_time_pre_val);
            foreach ($custom_time_price as $item) {
                if (intval($item['nums']) <= 0) continue;
                if (intval($item['hour']) < $reference_hour) {
                    //exit(json_encode(['error' => 1, 'message' => '分时发布点应大于当前时间、或定时发布的时间，请确认']));
                }
                $total_nums += intval($item['nums']);
                $custom_time_price_list[$item['hour']] = $item['nums'];
            }
            if ($total_nums != $trade_info->total_num) {
                exit(json_encode(['error' => 1, 'message' => '总活动单数与时间点单数累加值应一致，请确认']));
            }
            $tmp_price = CUSTOM_TIME_PRICE;
            if (array_key_exists('custom_time_price', $discount_list) && $discount_list['custom_time_price'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['custom_time_price'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'custom_time_price',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => json_encode($custom_time_price_list),
                'comments' => '分时发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $pc_num = 0;
            $phone_num = 0;
            $start_time = $set_time_pre_val;
        } elseif ($set_interval) {
            // 间隔发布
            $set_interval_val = $this->input->post('set_interval_val');
            $interval_num = $this->input->post('interval_num');
            $interval_list = $this->conf->interval_list();
            $interval_list_keys = array_keys($interval_list);
            if (!in_array($set_interval_val, $interval_list_keys)) {
                $set_interval_val = $interval_list_keys[0];
            }
            $tmp_price = SET_INTERVAL_PRICE;
            if (array_key_exists('set_interval', $discount_list) && $discount_list['set_interval'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['set_interval'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'set_interval',
                'price' => $tmp_price,
                'num' => 1,
                'pay_point' => $tmp_price,
                'param' => "{$set_interval_val}|{$interval_num}",
                'comments' => '间隔发布'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
            $interval_param = "{$set_interval_val}|{$interval_num}";
            $pc_num = 0;
            $phone_num = 0;
        } else {
            $pc_num = $trade_info->is_pc ? $trade_info->total_num : 0;
            $phone_num = $trade_info->is_phone ? $trade_info->total_num : 0;
        }

        // 延长买家购物周期
        $extend_cycle = intval($this->input->post('extend_cycle'));
        if ($extend_cycle && in_array($extend_cycle, [2, 3])) {
            if ($extend_cycle == 2) {
                $tmp_price = EXTEND_CYCLE1_PRICE;
            } else {
                $tmp_price = EXTEND_CYCLE2_PRICE;
            }
            if (array_key_exists('extend_cycle', $discount_list) && $discount_list['extend_cycle'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['extend_cycle'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'extend_cycle',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $extend_cycle,
                'comments' => '延长买家购物周期'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        } else {
            $extend_cycle = 0;
        }

        // 限制买号重复进店下单
        $shopping_end = intval($this->input->post('shopping_end'));
        if ($shopping_end) {
            $tmp_price = SHOPPING_END_BOX;
            if (array_key_exists('shopping_end', $discount_list) && $discount_list['shopping_end'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['shopping_end'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'shopping_end',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $shopping_end,
                'comments' => '限制买号重复进店下单'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        // 千人千面设置 - 地域限制
        $area_limit = $this->input->post('area_limit');
        if ($area_limit == '1') {
            $area_limit_city = $this->input->post('area_limit_city');
            $tmp_price = AREA_LIMIT;
            if (array_key_exists('area_limit', $discount_list) && $discount_list['area_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['area_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'area_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => implode(',', $area_limit_city),
                'comments' => '千人千面－地域限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 性别限制
        $sex_limit = $this->input->post('sex_limit');
        if ($sex_limit == '1') {
            $sex_limit_val = $this->input->post('sex_limit_val');
            $tmp_price = SEX_LIMIT;
            if (array_key_exists('sex_limit', $discount_list) && $discount_list['sex_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['sex_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'sex_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => $sex_limit_val,
                'comments' => '千人千面－性别限制'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 钻级别的买号
        $reputation_limit = $this->input->post('reputation_limit');
        if ($reputation_limit == '1') {
            $tmp_price = REPUTATION_LIMIT;
            if (array_key_exists('reputation_limit', $discount_list) && $discount_list['reputation_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['reputation_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'reputation_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 5,           // 标记5以上就是钻级
                'comments' => '千人千面－钻级别的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }
        // 千人千面设置 - 淘气值1000的买号
        $taoqi_limit = $this->input->post('taoqi_limit');
        if ($taoqi_limit == '1') {
            $tmp_price = TAOQI_LIMIT;
            if (array_key_exists('taoqi_limit', $discount_list) && $discount_list['taoqi_limit'] < 100) {
                $tmp_price = round(bcmul($tmp_price, $discount_list['taoqi_limit'] / 100, 4), 2);
            }
            $tmp_info = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'taoqi_limit',
                'price' => $tmp_price,
                'num' => $trade_info->total_num,
                'pay_point' => bcmul($tmp_price, $trade_info->total_num, 4),
                'param' => 1000,
                'comments' => '千人千面－淘气值1000的买号'
            ];

            $trade_service[] = $tmp_info;
            $service_point = bcadd($service_point, $tmp_info['pay_point'], 4);
        }

        $trade_point = bcadd($trade_info->order_fee_point, $trade_info->order_dis_point, 4);
        $trade_point = bcadd($trade_point, $service_point, 4);
        if ($trade_info->trade_type == '115') {
            $start_time = strtotime('2019-11-10 23:55:00');
        } else if ($trade_info->trade_type == '215') {
            $start_time = strtotime('2019-12-11 23:55:00');
        } else {
            $start_time = time();
        }
        $trade_info_upd = [
            'add_reward' => bcmul($add_reward_point, ADD_REWARD_POINT_PERCENT, 4),
            'pic_reward' => 0,
            'recommend_weight' => $add_speed,
            'extend_cycle' => $extend_cycle,
            'first_check' => $first_check_val,
            'service_point' => $service_point,
            'trade_point' => $trade_point,
            'trade_step' => 5,
            'plat_refund' => 1,
            'start_time' => $start_time,
            'interval' => $interval_param,
            'eval_type' => 0,
            'pc_num' => $pc_num,
            'phone_num' => $phone_num,
            'no_print' => 4
        ];

        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id, 'trade_step' => 4];
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);

        if ($this->write_db->affected_rows()) {
            $this->write_db->delete('rqf_trade_service', ['trade_id' => $trade_id]);
            $this->write_db->insert_batch('rqf_trade_service', $trade_service);
        }
        $this->write_db->close();

        exit(json_encode(['error' => 0]));
    }

    /** * 退款订单 － 第五步 **/
    private function refund_step5($trade_info)
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['trade_info'] = $trade_info;
        $data['user_info'] = $this->user->get_user_info($user_id);
        $shop_info = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $data['shop_ww'] = $shop_info->shop_ww;
        $this->load->view('trade/refund_step5', $data);
    }

    /** * 退款订单 － 第五步 提交 **/
    public function refund_step5_submit()
    {
        $this->write_db = $this->load->database('write', true);

        $trade_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

        $trade_info = $this->trade->get_trade_info($trade_id);

        if (!in_array($trade_info->trade_status, ['0', '5'])) {
            redirect('center');
            return;
        }

        if (in_array($trade_info->plat_id, [1, 2])) {
            $shop_info_sql = "select * from rqf_bind_shop where id = {$trade_info->shop_id}";
            $shop_info = $this->write_db->query($shop_info_sql)->row();
            if (empty($shop_info)) {
                redirect('center');
                return;
            }
            $show_ww = $shop_info->shop_ww;
            $auth_info2 = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 2])->row();
            if (empty($auth_info2)) {
                $auth_info = $this->db->get_where('rqf_shop_auth_info', ['shop_ww' => $show_ww, 'auth_type' => 1])->row();
                if (empty($auth_info)) {
                    $result = $this->ddx($show_ww);
                    if ($result['code'] == 0) {
                        $insert_array['shop_ww'] = $show_ww;
                        $insert_array['auth_type'] = 1;
                        $insert_array['is_order'] = $result['is_order'];
                        $insert_array['expires_time'] = strtotime($result['expires_time']);
                        $insert_array['deadline'] = strtotime($result['deadline']);
                        $this->write_db->insert('rqf_shop_auth_info', $insert_array);
                    } else {
                        error_back($result['msg']);
                        return;
                    }
                } else {
                    if ($auth_info->expires_time < time()) {
                        error_back('授权过期，需要重新授权');
                        return;
                    }
                }
            } else {
                if ($auth_info2->expires_time < time()) {
                    error_back('授权过期，需要重新授权');
                    return;
                }
            }
        }

        // 使用押金
        $has_deposit = true;
        // 使用金币
        $has_point = true;
        // 账户押金
        $user_deposit = 0;
        if ($has_deposit) {
            $user_deposit = $user_info->user_deposit;
        }
        // 账户金币
        $user_point = 0;
        if ($has_point)
            $user_point = $user_info->user_point;

        // 活动押金
        $trade_deposit = $trade_info->trade_deposit;

        // 活动金币
        $trade_point = $trade_info->trade_point;

        // 金币支付
        $pay_point = 0;

        // 押金支付
        $pay_deposit = 0;

        // 押金转金币
        $deposit_to_point = 0;

        // 第三方支付
        $pay_third = 0;

        if (bccomp($trade_point, $user_point, 2) > 0) {

            // $pay_point = $user_point;

            // $deposit_to_point = bcsub($trade_point, $user_point, 2);

            error_back('账户金币不足!');
            return;
        } else {

            $pay_point = $trade_point;
        }

        $trade_deposit = bcadd($trade_deposit, $deposit_to_point, 2);

        if (bccomp($trade_deposit, $user_deposit, 2) > 0) {

            // $pay_deposit = $user_deposit;

            // $pay_third = bcsub($trade_deposit, $user_deposit, 2);

            error_back('账户押金不足!');
            return;
        } else {
            $pay_deposit = $trade_deposit;
        }

        if ($pay_third == 0) {
            $sql = "update rqf_trade_info
                      set trade_step = 6, trade_status = 1, pay_point = {$pay_point}, pay_deposit = {$pay_deposit}, pay_third = {$pay_third}, pay_time = ?
                    where id = {$trade_id} and user_id = {$user_id} and trade_step = 5 and trade_status in (0,5)";
            $this->write_db->query($sql, [time()]);
            if ($this->write_db->affected_rows()) {
                // 押金转金币
                if ($deposit_to_point > 0) {
                    $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
                    $user_deposit = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 200,
                        'score_nums' => '-' . $deposit_to_point,
                        'last_score' => bcsub($user_info->user_deposit, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_deposit,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                    $user_point = [
                        'user_id' => $user_id,
                        'shop_id' => $trade_info->shop_id,
                        'action_time' => time(),
                        'action_type' => 100,
                        'score_nums' => '+' . $deposit_to_point,
                        'last_score' => bcadd($user_info->user_point, $deposit_to_point, 2),
                        'frozen_score_nums' => 0,
                        'last_frozen_score' => $user_info->frozen_point,
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];

                    $this->write_db->insert('rqf_bus_user_point', $user_point);

                    $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$deposit_to_point, $deposit_to_point, $user_id]);
                }

                $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

                // 冻结押金

                $user_deposit = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_deposit,
                    'last_score' => bcsub($user_info->user_deposit, $trade_info->trade_deposit, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_deposit,
                    'last_frozen_score' => bcadd($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                // 冻结金币

                $user_point = [
                    'user_id' => $user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time' => time(),
                    'action_type' => 300,
                    'score_nums' => '-' . $trade_info->trade_point,
                    'last_score' => bcsub($user_info->user_point, $trade_info->trade_point, 2),
                    'frozen_score_nums' => '+' . $trade_info->trade_point,
                    'last_frozen_score' => bcadd($user_info->frozen_point, $trade_info->trade_point, 2),
                    'trade_sn' => $trade_info->trade_sn,
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $sql = 'update rqf_users
                        set user_deposit = user_deposit - ?,
                            frozen_deposit = frozen_deposit + ?,
                            user_point = user_point - ?,
                            frozen_point = frozen_point + ?
                            where id = ?';

                $this->write_db->query($sql, [$trade_info->trade_deposit, $trade_info->trade_deposit, $trade_info->trade_point, $trade_info->trade_point, $user_id]);

                // 操作日志
                $action_info = [
                    'trade_id' => $trade_info->id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 1,
                    'trade_note' => '活动已支付',
                    'add_time' => time(),
                    'created_user' => $this->session->userdata('nickname'),
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $action_info);
            }

            redirect('trade/step/' . $trade_id);
            return;
        }
    }

    /** 退款订单 － 第六步 */
    private function refund_step6($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $this->load->view('trade/refund_step6', $data);
    }

    /** 拼多多操作 － 第二步 */
    private function pdd_char_eval_step2($trade_info)
    {
        $data = $this->data;
        $data['trade_info'] = $trade_info;
        $data['trade_item'] = $this->trade->get_trade_item($trade_info->id);
        // 活动关键词信息
        $trade_search = $this->trade->get_trade_search($trade_info->id);
        $data['app_search'] = $trade_search['app'];
        if (empty($data['app_search'][0]->kwd)) {
            $data['app_search'][0]->classify1 = '拼团价格';
            $data['app_search'][0]->classify2 = '有团开团，无团再开';
            $data['app_search'][0]->classify3 = '1';
        }
        $this->load->view('trade/pdd_char_eval_step2', $data);
    }

    /** 拼多多第二步提交 */
    public function pdd_char_eval_step2_submit()
    {
        $trade_id = intval($this->uri->segment(3));
        $user_id = intval($this->session->userdata('user_id'));
        $trade_info = $this->trade->get_trade_info($trade_id);
        $old_trade_search = $this->trade->get_trade_search($trade_id);
        if ($user_id <= 0) {
            exit(json_encode(['code' => 1, 'msg' => '用户未登录']));
        }
        // 传入参数
        $goods_name = trim($this->input->post('goods_name'));
        $goods_url = trim($this->input->post('goods_url'));
        $price = floatval($this->input->post('price'));
        $price_type = trim($this->input->post('price_type'));
        $show_price = $this->input->post('show_price');
        $buy_num = intval($this->input->post('buy_num'));
        $color = trim($this->input->post('color'));
        $size = trim($this->input->post('size'));
        $goods_img = trim($this->input->post('app_img1_base64'));
        $order_style = $this->input->post('order_style');
        $is_chat = $this->input->post('is_chat');
        $item_id = $this->trade->get_item_id($goods_url, $trade_info->plat_id);
        if ($goods_name == '') {
            exit(json_encode(['code' => 2, 'msg' => '请输入商品名称']));
        }
        if ($goods_url == '') {
            exit(json_encode(['code' => 3, 'msg' => '请输入商品链接']));
        }
        if (empty($item_id)) {
            exit(json_encode(['code' => 3, 'msg' => '请检查您录入商品链接，不是合法的商品链接']));
        }
        if (empty($price)) {
            exit(json_encode(['code' => 4, 'msg' => '请输入商品价格']));
        }
        if ($buy_num < 1) {
            exit(json_encode(['code' => 5, 'msg' => '请输入购买件数']));
        }
        if (empty($goods_img)) {
            exit(json_encode(['code' => 6, 'msg' => '请上传搜索商品主图']));
        }
        // 搜索关键词
        $trade_search = [];
        $app_kwd = $this->input->post('app_kwd');
        $app_low_price = $this->input->post('app_low_price');
        $app_high_price = $this->input->post('app_high_price');
        $app_area = $this->input->post('app_area');
        $goods_cate = $this->input->post('goods_cate');
        // 关键词验证
        foreach ($app_kwd as $item) {
            if (empty(trim($item))) {
                exit(json_encode(['code' => 6, 'msg' => '搜索关键词不能为空']));
            }
        }

        $tmp_info = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_info->trade_sn,
            'plat_id' => $trade_info->plat_id,
            'search_img' => $goods_img,
            'search_img2' => '',
            'classify1' => $price_type,         // 单购价格、拼团价格
            'classify2' => $order_style,        // 有团开团，无团再开、 开团、 参团、 单买
            'classify3' => $is_chat,            // 1 => 聊天、2 => 不聊
            'classify4' => '',
            'order_way' => '',
        ];

        $old_app_search = $old_trade_search['app'];
        foreach ($app_kwd as $k => $v) {
            $tmp_info['kwd'] = $v;
            $tmp_info['low_price'] = isset($app_low_price[$k]) ? $app_low_price[$k] : '';
            $tmp_info['high_price'] = isset($app_high_price[$k]) ? $app_high_price[$k] : '';
            $tmp_info['discount'] = '';
            $tmp_info['area'] = isset($app_area[$k]) ? $app_area[$k] : '';
            $tmp_info['goods_cate'] = isset($goods_cate[$k]) ? $goods_cate[$k] : '';
            $tmp_info['num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->num : 0;
            $tmp_info['surplus_num'] = isset($old_app_search[$k]) ? $old_app_search[$k]->surplus_num : 0;
            $trade_search[] = $tmp_info;
        }
        // 任务单计费
        $order_fee_obj = $this->fee->order_fee_obj($trade_info->trade_type, $price * $buy_num);
        if (empty($order_fee_obj)) {
            echo json_encode(['code' => 7, 'msg' => '系统错误']);
            return;
        }
        // 更新任务单信息
        $trade_info_upd = [
            'item_id' => $item_id,
            'price' => $price,
            'buy_num' => $buy_num,
            'is_pc' => 0,
            'is_phone' => 1,
            'total_fee' => $order_fee_obj->total_fee,
            'base_reward' => $order_fee_obj->base_reward,
            'snatch_gold' => 0,
            'is_show' => 1,
            'is_post' => 1,
            'post_fee' => 0,
            'trade_step' => 3,
        ];
        $trade_info_key = ['id' => $trade_id, 'user_id' => $user_id];
        // 商品信息
        $trade_item_upd = [
            'goods_name' => $goods_name,
            'goods_img' => $goods_img,
            'goods_url' => $goods_url,
            'item_id' => $item_id,
            'price' => $price,
            'show_price' => $show_price,
            'buy_num' => $buy_num,
            'color' => $color,
            'size' => $size,
            'is_post' => 1,
        ];
        $trade_item_key = ['trade_id' => $trade_id];

        $this->write_db = $this->load->database('write', true);
        $this->write_db->update('rqf_trade_info', $trade_info_upd, $trade_info_key);
        $this->write_db->update('rqf_trade_item', $trade_item_upd, $trade_item_key);
        $this->write_db->delete('rqf_trade_search', ['trade_id' => $trade_id]);
        $this->write_db->insert_batch('rqf_trade_search', $trade_search);
        $this->write_db->close();

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }


    /**
     * ajax图片上传
     */
    public function ajax_upload()
    {
        $base64 = $this->input->post('base64');
        $path = '';
        if ($base64) {
            $path = $this->base64->to_img($base64, UPLOAD_TRADE_INFO_DIR);
            $result = qiniu_upload(ltrim($path, '/'));
            if (isset($result[0]['key'])) {
                exit(CDN_URL . '/' . $result[0]['key']);
            } else {
                exit('');
            }
        }

        exit('');
    }

    /*** 一键发布  **/
    public function one_key()
    {
        $clone_id = intval($this->uri->segment(3));
        $user_id = intval($this->session->userdata('user_id'));
        $trade_sn = $this->uniq->create_trade_sn();
        $referer = $this->input->server('HTTP_REFERER');
        // 开启事务
        $this->write_db = $this->load->database('write', true);
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();

        // 活动基本信息
        $trade_info = $this->trade->get_trade_info($clone_id);
        if (!in_array($trade_info->trade_status, ['2', '3', '4'])) {
            redirect($referer);
            return;
        }
        $trade_type = ($trade_info->trade_type == '3' && $trade_info->eval_type == '4') ? 1 : intval($trade_info->trade_type);
        $bind_shop = $this->db->get_where('rqf_bind_shop', ['id' => $trade_info->shop_id])->row();
        $trade_info_ins = [
            'trade_sn' => $trade_sn,
            'user_id' => $user_id,
            'shop_id' => $trade_info->shop_id,
            'plat_id' => $trade_info->plat_id,
            'trade_type' => $trade_type,
            'is_pc' => $trade_info->is_pc,
            'is_phone' => $trade_info->is_phone,
            'item_id' => $trade_info->item_id,
            'trade_step' => 2,
            'trade_status' => 0,
            'price' => $trade_info->price,
            'buy_num' => $trade_info->buy_num,
            'is_post' => $trade_info->is_post,
            'total_num' => $trade_info->total_num,
            'pc_num' => $trade_info->pc_num,
            'phone_num' => $trade_info->phone_num,
            'created_time' => time(),
            'clone_id' => $clone_id,
            'is_show' => $trade_info->is_show,
            'no_print' => $bind_shop->no_print
        ];
        $this->write_db->insert('rqf_trade_info', $trade_info_ins);
        $trade_id = $this->write_db->insert_id();
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect($referer);
            return;
        }

        // 活动商品信息
        $trade_item = $this->trade->get_trade_item($clone_id);
        $trade_item_ins = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_sn,
            'goods_name' => $trade_item->goods_name,
            'goods_img' => $trade_item->goods_img,
            'goods_url' => $trade_item->goods_url,
            'item_id' => $trade_item->item_id,
            'price' => $trade_item->price,
            'show_price' => $trade_item->show_price,
            'buy_num' => $trade_item->buy_num,
            'color' => $trade_item->color,
            'size' => $trade_item->size,
            'is_post' => $trade_item->is_post,
            'order_prompt' => $trade_item->order_prompt,
            'weight' => $trade_item->weight,
            'task_requirements' => $trade_item->task_requirements
        ];

        $this->write_db->insert('rqf_trade_item', $trade_item_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect($referer);
            return;
        }

        // 关键词信息
        $trade_search = $this->write_db->get_where("rqf_trade_search", ['trade_id' => $clone_id])->result();
        $trade_search_ins = [];
        foreach ($trade_search as $v) {
            $trade_search_ins[] = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_sn,
                'plat_id' => $v->plat_id,
                'search_img' => $v->search_img,
                'search_img2' => $v->search_img2,
                'kwd' => $v->kwd,
                'classify1' => $v->classify1,
                'classify2' => $v->classify2,
                'classify3' => $v->classify3,
                'classify4' => $v->classify4,
                'low_price' => $v->low_price,
                'high_price' => $v->high_price,
                'area' => $v->area,
                'discount' => $v->discount,
                'order_way' => $v->order_way,
                'goods_cate' => $v->goods_cate,
                'num' => $v->num
            ];
        }
        $this->write_db->insert_batch('rqf_trade_search', $trade_search_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect($referer);
            return;
        }
        if ($trade_info->trade_type == '90') {
            $trade_scan = $this->write_db->get_where("rqf_trade_scan", ['trade_id' => $clone_id])->result();
            $trade_scan_ins = [];
            foreach ($trade_scan as $v) {
                $trade_scan_ins[] = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_sn,
                    'plat_id' => $v->plat_id,
                    'goods_url' => $v->goods_url,
                    'goods_name' => $v->goods_name,
                    'shop_name' => $v->shop_name,
                    'price' => $v->price,
                    'search_img' => $v->search_img,
                    'search_img2' => $v->search_img2,
                    'item_id' => $v->item_id,
                    'kwd' => $v->kwd,
                    'low_price' => $v->low_price,
                    'high_price' => $v->high_price,
                    'area' => $v->area,
                    'discount' => $v->discount,
                    'order_way' => $v->order_way,
                    'goods_cate' => $v->goods_cate,
                ];
            }
            $this->write_db->insert_batch('rqf_trade_scan', $trade_scan_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }
        }
        // 增值服务
        $trade_service = $this->db->get_where("rqf_trade_service", ['trade_id' => $clone_id])->result();
        $trade_service_ins = [];
        foreach ($trade_service as $v) {
            if (in_array($v->service_name, ['setting_eval', 'setting_picture'])) continue;          // 去掉自定义好评
            $trade_service_ins[] = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_sn,
                'service_name' => $v->service_name,
                'price' => $v->price,
                'num' => $v->num,
                'pay_point' => $v->pay_point,
                'param' => $v->param,
                'comments' => $v->comments
            ];
        }
        $this->write_db->insert_batch('rqf_trade_service', $trade_service_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect($referer);
            return;
        }

        // 操作日志
        $trade_action_ins = [
            'trade_id' => $trade_id,
            'trade_sn' => $trade_sn,
            'trade_status' => 0,
            'trade_note' => '一键重发活动',
            'add_time' => time(),
            'created_user' => $this->session->userdata('nickname'),
            'comments' => ''
        ];
        $this->write_db->insert("rqf_trade_action", $trade_action_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect($referer);
            return;
        }

        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }

        redirect('trade/step/' . $trade_id);
    }

    /**
     * 审核不通过取消
     */
    public function uncheck_cancel()
    {
        $user_id = $this->session->userdata('user_id');
        $trade_id = intval($this->uri->segment(3));
        $trade_info = $this->trade->get_trade_info($trade_id);
        $sql = "update rqf_trade_info set trade_status = 9 where user_id = ? and id = ? and trade_status = 5";
        $this->write_db = $this->load->database('write', true);
        $this->write_db->query($sql, [intval($user_id), $trade_id]);
        if ($this->write_db->affected_rows()) {
            $trade_action = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'trade_status' => 9,
                'trade_note' => '审核不通过取消活动',
                'add_time' => time(),
                'created_user' => $this->session->userdata('nickname'),
                'comments' => ''
            ];
            $this->write_db->insert('rqf_trade_action', $trade_action);
        }
        $this->write_db->close();

        $referer = $this->input->server('HTTP_REFERER');
        redirect($referer);
    }

    /**
     * 撤销活动
     */
    public function cancel()
    {
        $user_id = intval($this->session->userdata('user_id'));
        $trade_id = intval($this->uri->segment(3));
        $trade_info = $this->trade->get_trade_info($trade_id);
        if (!$trade_info || $trade_info->user_id != $user_id) {
            redirect('center');
            return;
        }

        $referer = $this->input->server('HTTP_REFERER');
        $this->write_db = $this->load->database('write', true);
        // 未支付
        if ($trade_info->trade_status == '0') {
            $sql = "update rqf_trade_info set trade_status = 9 where user_id = {$user_id} and id = {$trade_id} and trade_status = 0";
            $this->write_db->query($sql);
            if ($this->write_db->affected_rows()) {
                $trade_action = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'trade_status' => 9,
                    'trade_note' => '商家取消活动',
                    'add_time' => time(),
                    'created_user' => $this->session->userdata('nickname'),
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $trade_action);
            }
        } elseif ($trade_info->trade_status == '1') {           // 已支付
            // 开启事务
            $this->write_db->trans_strict(FALSE);
            $this->write_db->trans_begin();
            $sql = "update rqf_trade_info set trade_status = 9 where user_id = {$user_id} and id = {$trade_id} and trade_status = 1";
            $this->write_db->query($sql);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            $trade_action = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'trade_status' => 9,
                'trade_note' => '商家取消活动',
                'add_time' => time(),
                'created_user' => $this->session->userdata('nickname'),
                'comments' => ''
            ];
            $this->write_db->insert('rqf_trade_action', $trade_action);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
            $sql = "update rqf_users 
                    set 
                    user_deposit = user_deposit + {$trade_info->trade_deposit},
                    frozen_deposit = frozen_deposit - {$trade_info->trade_deposit},
                    user_point = user_point + {$trade_info->trade_point},
                    frozen_point = frozen_point - {$trade_info->trade_point}
                    where id = {$user_id}
                    and frozen_deposit >= {$trade_info->trade_deposit}
                    and frozen_point >= {$trade_info->trade_point}";
            $this->write_db->query($sql);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }
            // 浏览任务单没有押金
            $user_deposit_ins = [
                'user_id' => $user_id,
                'shop_id' => $trade_info->shop_id,
                'action_time' => time(),
                'action_type' => 404,
                'score_nums' => '+' . $trade_info->trade_deposit,
                'last_score' => bcadd($user_info->user_deposit, $trade_info->trade_deposit, 2),
                'frozen_score_nums' => '-' . $trade_info->trade_deposit,
                'last_frozen_score' => bcsub($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                'trade_sn' => $trade_info->trade_sn,
                'order_sn' => '',
                'pay_sn' => '',
                'created_user' => $this->session->userdata('nickname'),
                'trade_pic' => ''
            ];
            $this->write_db->insert('rqf_bus_user_deposit', $user_deposit_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            $user_point_ins = [
                'user_id' => $user_id,
                'shop_id' => $trade_info->shop_id,
                'action_time' => time(),
                'action_type' => 404,
                'score_nums' => '+' . $trade_info->trade_point,
                'last_score' => bcadd($user_info->user_point, $trade_info->trade_point, 2),
                'frozen_score_nums' => '-' . $trade_info->trade_point,
                'last_frozen_score' => bcsub($user_info->frozen_point, $trade_info->trade_point, 2),
                'trade_sn' => $trade_info->trade_sn,
                'order_sn' => '',
                'pay_sn' => '',
                'created_user' => $this->session->userdata('nickname'),
                'trade_pic' => ''
            ];
            $this->write_db->insert('rqf_bus_user_point', $user_point_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            if ($this->write_db->trans_status() === TRUE) {
                $this->write_db->trans_commit();
            } else {
                $this->write_db->trans_rollback();
            }
        } elseif (in_array($trade_info->trade_status, ['2', '6'])) {                 // 进行中 暂停中
            // 开启事务
            $this->write_db->trans_strict(FALSE);
            $this->write_db->trans_begin();
            $sql = "update rqf_trade_info set trade_status = 9 where user_id = {$user_id} and id = {$trade_id} and trade_status in (2, 6)";
            $this->write_db->query($sql);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            //删除浏览任务详情表数据
            $sql = "SELECT
                        traffic_id,
                        sum( IF ( normal_price > 0, 1, 0 ) ) AS normal_price,
                        sum( IF ( collect_goods > 0, 1, 0 ) ) AS collect_goods,
                        sum( IF ( add_to_cart > 0, 1, 0 ) ) AS add_to_cart,
                        sum( IF ( collect_shop > 0, 1, 0 ) ) AS collect_shop,
                        sum( IF ( get_coupon > 0, 1, 0 ) ) AS get_coupon,
                        sum( IF ( item_evaluate > 0, 1, 0 ) ) AS item_evaluate,
                        sum( IF ( compare_goods > 0, 1, 0 ) ) AS compare_goods,
                        sum( IF ( like_goods > 0, 1, 0 ) ) AS like_goods
                    FROM
                        rqf_trade_traffic_detail 
                        WHERE  
                        trade_id= ?
                    GROUP BY
                        traffic_id ";
            $traffic_detail_list = $this->db->query($sql, [intval($trade_id)])->result();

            if ($traffic_detail_list) {
                foreach ($traffic_detail_list as $v) {
                    $sql = " UPDATE rqf_trade_traffic 
                            SET surplus_num = surplus_num + ?,
                            collect_goods = collect_goods + ?,
                            add_to_cart = add_to_cart + ?,
                            collect_shop = collect_shop + ?,
                            get_coupon = get_coupon + ?,
                            item_evaluate = item_evaluate + ?,
                            compare_goods = compare_goods + ?,
                            like_goods = like_goods + ? 
                            WHERE
                                id =  ? ";

                    $this->write_db->query($sql, [$v->normal_price, $v->collect_goods, $v->add_to_cart, $v->collect_shop, $v->get_coupon, $v->item_evaluate, $v->compare_goods, $v->like_goods, intval($v->traffic_id)]);
                    if (!$this->write_db->affected_rows()) {
                        $this->write_db->trans_rollback();
                        redirect($referer);
                        return;
                    }
                }

                $sql = "DELETE FROM rqf_trade_traffic_detail WHERE trade_id= ? ";
                $this->write_db->query($sql, [intval($trade_id)]);
                if (!$this->write_db->affected_rows()) {
                    $this->write_db->trans_rollback();
                    redirect($referer);
                    return;
                }

                $this->write_db->update('rqf_trade_traffic', ['trade_status' => 9], ['trade_id' => $trade_id]);
            }


            $trade_action = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'trade_status' => 9,
                'trade_note' => '商家取消活动',
                'add_time' => time(),
                'created_user' => $this->session->userdata('nickname'),
                'comments' => ''
            ];
            $this->write_db->insert('rqf_trade_action', $trade_action);   //任务操作日志记录
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }
            if ($trade_info->trade_type == '10') {       //如果类型为流量订单
                $this->load->model('Traffic_Model', 'traffic');
                $cancel_refund = $this->traffic->cancel_refund($trade_info);
            } else {
                $cancel_refund = $this->trade->cancel_refund($trade_info);
            }
            $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
            $sql = "update rqf_users 
                    set 
                    user_deposit = user_deposit + {$cancel_refund->deposit},
                    frozen_deposit = frozen_deposit - {$cancel_refund->deposit},
                    user_point = user_point + {$cancel_refund->point},
                    frozen_point = frozen_point - {$cancel_refund->point}
                    where id = {$user_id}
                    and frozen_deposit >= {$cancel_refund->deposit}
                    and frozen_point >= {$cancel_refund->point}";
            $this->write_db->query($sql);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }
            $user_deposit_ins = [
                'user_id' => $user_id,
                'shop_id' => $trade_info->shop_id,
                'action_time' => time(),
                'action_type' => 404,
                'score_nums' => '+' . $cancel_refund->deposit,
                'last_score' => bcadd($user_info->user_deposit, $cancel_refund->deposit, 2),
                'frozen_score_nums' => '-' . $cancel_refund->deposit,
                'last_frozen_score' => bcsub($user_info->frozen_deposit, $cancel_refund->deposit, 2),
                'trade_sn' => $trade_info->trade_sn,
                'order_sn' => '',
                'pay_sn' => '',
                'created_user' => $this->session->userdata('nickname'),
                'trade_pic' => ''
            ];
            $this->write_db->insert('rqf_bus_user_deposit', $user_deposit_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            $user_point_ins = [
                'user_id' => $user_id,
                'shop_id' => $trade_info->shop_id,
                'action_time' => time(),
                'action_type' => 404,
                'score_nums' => '+' . $cancel_refund->point,
                'last_score' => bcadd($user_info->user_point, $cancel_refund->point, 2),
                'frozen_score_nums' => '-' . $cancel_refund->point,
                'last_frozen_score' => bcsub($user_info->frozen_point, $cancel_refund->point, 2),
                'trade_sn' => $trade_info->trade_sn,
                'order_sn' => '',
                'pay_sn' => '',
                'created_user' => $this->session->userdata('nickname'),
                'trade_pic' => ''
            ];

            $this->write_db->insert('rqf_bus_user_point', $user_point_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect($referer);
                return;
            }

            if ($this->write_db->trans_status() === TRUE) {
                $this->write_db->trans_commit();
            } else {
                $this->write_db->trans_rollback();
            }
        }

        $this->write_db->close();

        redirect($referer);
    }

    /**
     * 追加增值服务
     */
    public function append_service_submit()
    {

        $trade_id = $this->input->post('trade_id');

        $user_id = $this->session->userdata('user_id');

        // 提升完成活动速度
        $add_speed = intval($this->input->post('upgrade'));

        // 加赏佣金
        $add_reward_ext = intval($this->input->post('add_reward'));

        $add_reward_check = $this->input->post('a_plus_point');

        $this->write_db = $this->load->database('write', true);

        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

        $trade_info = $this->write_db->get_where('rqf_trade_info', ['id' => $trade_id, 'user_id' => $user_id])->row();

        if (empty($trade_info)) {
            redirect('center');
            return;
        }

        $append_service_point = 0;

        // 开启事务
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();

        if ($add_speed) {

            if (!in_array($add_speed, [10, 20, 30])) {
                $add_speed = 10;
            }

            $add_speed_check = $this->write_db->get_where('rqf_trade_service', ['trade_id' => $trade_id, 'service_name' => 'add_speed'])->row();

            if ($add_speed_check) {

                $sql = "update rqf_trade_service
                        set price = price + {$add_speed}, pay_point = pay_point + {$add_speed}
                        where trade_id = {$trade_id} and service_name = 'add_speed'";

                $this->write_db->query($sql);
            } else {

                $trade_service_ins = [
                    'trade_id' => $trade_id,
                    'trade_sn' => $trade_info->trade_sn,
                    'service_name' => 'add_speed',
                    'price' => $add_speed,
                    'num' => 1,
                    'pay_point' => $add_speed,
                    'param' => '',
                    'comments' => '提升完成活动速度'
                ];

                $this->write_db->insert('rqf_trade_service', $trade_service_ins);
            }

            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                redirect('center');
                return;
            }

            $append_service_point += $add_speed;
        }

        if ($add_reward_ext && $add_reward_check) {

            $order_cnts = $this->trade->order_cnts($trade_info);

            $add_cnt = $order_cnts->not_started + $order_cnts->not_pay;

            $trade_service_ins = [
                'trade_id' => $trade_id,
                'trade_sn' => $trade_info->trade_sn,
                'service_name' => 'add_reward_ext',
                'price' => $add_reward_ext,
                'num' => $add_cnt,
                'pay_point' => bcmul($add_reward_ext, $add_cnt, 4),
                'param' => '',
                'comments' => '追加活动赏金'
            ];

            $this->write_db->insert('rqf_trade_service', $trade_service_ins);

            $append_service_point = bcadd($append_service_point, $trade_service_ins['pay_point'], 4);

            $trade_add_reward = bcmul($add_reward_ext, ADD_REWARD_POINT_PERCENT, 4);
        } else {
            $trade_add_reward = 0;
        }

        // 更新活动数据
        $sql = "update rqf_trade_info
                set add_reward = add_reward + {$trade_add_reward}, 
                recommend_weight = recommend_weight + {$add_speed}, 
                service_point = service_point + {$append_service_point},
                trade_point = trade_point + {$append_service_point}
                where id = {$trade_id}
                and user_id = {$user_id}";

        $this->write_db->query($sql);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('center');
            return;
        }

        // 扣减用户金币
        $sql = "update rqf_users
                set user_point = user_point - {$append_service_point},
                frozen_point = frozen_point + {$append_service_point}
                where id = {$user_id}
                and user_point >= {$append_service_point}";

        $this->write_db->query($sql);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('center');
            return;
        }

        $user_point_ins = [
            'user_id' => $user_id,
            'action_time' => time(),
            'action_type' => 306,
            'score_nums' => '-' . $append_service_point,
            'last_score' => bcsub($user_info->user_point, $append_service_point, 2),
            'frozen_score_nums' => '+' . $append_service_point,
            'last_frozen_score' => bcadd($user_info->frozen_point, $append_service_point, 2),
            'trade_sn' => $trade_info->trade_sn,
            'order_sn' => '',
            'pay_sn' => '',
            'created_user' => $this->session->userdata('nickname'),
            'trade_pic' => ''
        ];

        $this->write_db->insert('rqf_bus_user_point', $user_point_ins);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('center');
            return;
        }

        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }

        $referer = $this->input->server('HTTP_REFERER');

        redirect($referer);
    }

    /**
     * 取消活动返还金额计算
     */
    public function get_cancel_vars()
    {
        $trade_id = $this->input->post('trade_id');
        $user_id = $this->session->userdata('user_id');
        $cancel_vars = ['surplus_num' => 0, 'deposit' => 0, 'point' => 0];
        // 撤销任务单记录
        $trade_info = $this->db->get_where('rqf_trade_info', ['id' => $trade_id, 'user_id' => $user_id])->row();
        if ($trade_info) {
            if ($trade_info->trade_type == '10') {
                $this->load->model('Traffic_Model', 'traffic');
                $cancel_refund = $this->traffic->cancel_refund($trade_info);
            } else {
                $cancel_refund = $this->trade->cancel_refund($trade_info);
            }
            // 返回结果集
            $cancel_vars['surplus_num'] = $cancel_refund->surplus_num;
            $cancel_vars['deposit'] = floatval($cancel_refund->deposit);
            $cancel_vars['point'] = floatval($cancel_refund->point);
        }

        echo json_encode($cancel_vars);
    }


    /**
     * 未付款取消
     */
    public function nonpayment_cancel()
    {
        $user_id = intval($this->session->userdata('user_id'));
        $trade_id = $this->input->post('trade_id');
        $trade_info = $this->trade->get_trade_info($trade_id);
        if (!$trade_info || $trade_info->user_id != $user_id) {
            exit(json_encode(['code' => 1, 'msg' => 'fails']));
        }
        $referer = $this->input->server('HTTP_REFERER');
        if ($trade_info->trade_status == '0') {
            //删除对应关联表
            $this->write_db = $this->load->database('write', true);

            $this->write_db->delete('rqf_trade_info', ['id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_trade_service', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_trade_item', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_trade_search', ['trade_id' => intval($trade_info->id)]);
            $this->write_db->delete('rqf_trade_action', ['trade_id' => intval($trade_info->id)]);
            if ($trade_info->trade_type == '10') {
                $this->write_db->delete('rqf_trade_traffic', ['trade_id' => intval($trade_info->id)]);
            }
            if (in_array($trade_info->eval_type, [3, 4, 5])) {
                $this->write_db->delete('rqf_setting_eval', ['trade_id' => intval($trade_info->id)]);
                $this->write_db->delete('rqf_setting_img', ['trade_id' => intval($trade_info->id)]);
            }
            $this->write_db->close();
        } else {
            echo json_encode(['code' => 1, 'msg' => '不正确的状态值']);
        }

        echo json_encode(['code' => 0, 'msg' => 'ok']);
    }

    /**
     * 每一单 50% 几率赠送1流量（浏览+加购） 通过第三方接口
     * @param $total_num
     * @return int
     */
    private function award_num($total_num)
    {
        $award_num = 0;

//        for ($i = 0; $i < $total_num; $i++) {
//            $probability = mt_rand(1, 100);
//
//            if ($probability <= 50) {
//                $award_num += 1;
//            }
//        }

        return $award_num;
    }
}
