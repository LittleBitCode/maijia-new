<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:计划活动控制器
 * 担当:
 */
class Cron_order extends CI_Controller {

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->write_db = $this->load->database('write', true);
    }

    /**
     * 更新可接单数
     * 每天00:00执行
     */
    public function update_brush_num()
    {
        // 已经使用照妖镜处理过淘宝账号 每日可接五个单子  is_checked 标记
        $sql = "update rqf_bind_account set surplus_brush_num = 5 where plat_id = 1 and account_status = 1 and surplus_brush_num < 5 and is_checked > 0 and is_app = 1 ";
        $this->write_db->query($sql);
        // 还没有使用照妖镜处理过淘宝账号 每日可接三个单子
        $sql = "update rqf_bind_account set surplus_brush_num = 3 where plat_id = 1 and account_status = 1 and surplus_brush_num < 3 and is_checked = 0 and is_app = 1 ";
        $this->write_db->query($sql);
        // 其他平台不管
        $sql = "update rqf_bind_account set surplus_brush_num = 5 where plat_id != 1 and account_status = 1 and surplus_brush_num < 5 and is_app = 1 ";
        $this->write_db->query($sql);

        // 更新浏览订单数
        $sql = "update rqf_bind_account set surplus_traffic_num = 30 where account_status = 1 and surplus_traffic_num < 30 ";
        $this->write_db->query($sql);
        echo date('Y-m-d H:i:s'). " dono !<br/>";
    }

    /**
     * 文字好评，图文好评超时取消
     * 超过2小时未完成自动取消
     * 5分钟执行一次
     */
    public function cancel_order_1()
    {
        $limit = strtotime('-2 hour');
        $sql = "select * from rqf_trade_order_%s where order_status = 0 and trade_type in (1, 3, 5, 6, 7, 140) and first_end_time = 0 and first_start_time < {$limit} limit 10";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();
        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();
        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();
        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();
        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();
        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();
        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();
        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();
        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();
        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();


        foreach ($res_0 as $v) $this->exec_cancel($v, '0');
        foreach ($res_1 as $v) $this->exec_cancel($v, '1');
        foreach ($res_2 as $v) $this->exec_cancel($v, '2');
        foreach ($res_3 as $v) $this->exec_cancel($v, '3');
        foreach ($res_4 as $v) $this->exec_cancel($v, '4');
        foreach ($res_5 as $v) $this->exec_cancel($v, '5');
        foreach ($res_6 as $v) $this->exec_cancel($v, '6');
        foreach ($res_7 as $v) $this->exec_cancel($v, '7');
        foreach ($res_8 as $v) $this->exec_cancel($v, '8');
        foreach ($res_9 as $v) $this->exec_cancel($v, '9');
    }

    /**
     * 回访活动第一天超时取消
     * 超过1小时未完成自动取消
     * 5分钟执行一次
     */
    public function cancel_order_2()
    {
        $limit = strtotime('-2 hour');
        $sql = "select * from rqf_trade_order_%s where order_status = 0 and trade_type = 2 and first_end_time = 0 and order_step < 4 and first_start_time < {$limit} limit 5";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();
        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();
        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();
        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();
        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();
        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();
        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();
        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();
        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();
        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_0 as $v) $this->exec_cancel($v, '0');
        foreach ($res_1 as $v) $this->exec_cancel($v, '1');
        foreach ($res_2 as $v) $this->exec_cancel($v, '2');
        foreach ($res_3 as $v) $this->exec_cancel($v, '3');
        foreach ($res_4 as $v) $this->exec_cancel($v, '4');
        foreach ($res_5 as $v) $this->exec_cancel($v, '5');
        foreach ($res_6 as $v) $this->exec_cancel($v, '6');
        foreach ($res_7 as $v) $this->exec_cancel($v, '7');
        foreach ($res_8 as $v) $this->exec_cancel($v, '8');
        foreach ($res_9 as $v) $this->exec_cancel($v, '9');
    }

    /**
     * 回访活动第二天超时取消
     * 已接手超过2小时未完成自动取消
     * 5分钟执行一次
     */
    public function cancel_order_3()
    {
        $limit = strtotime('-2 hour');
        $sql = "select * from rqf_trade_order_%s where order_status = 0 and trade_type = 2 and day_index = 2 and second_start_time > 0 and second_start_time < {$limit} limit 5";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();
        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();
        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();
        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();
        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();
        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();
        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();
        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();
        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();
        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_0 as $v) $this->exec_cancel($v, '0');
        foreach ($res_1 as $v) $this->exec_cancel($v, '1');
        foreach ($res_2 as $v) $this->exec_cancel($v, '2');
        foreach ($res_3 as $v) $this->exec_cancel($v, '3');
        foreach ($res_4 as $v) $this->exec_cancel($v, '4');
        foreach ($res_5 as $v) $this->exec_cancel($v, '5');
        foreach ($res_6 as $v) $this->exec_cancel($v, '6');
        foreach ($res_7 as $v) $this->exec_cancel($v, '7');
        foreach ($res_8 as $v) $this->exec_cancel($v, '8');
        foreach ($res_9 as $v) $this->exec_cancel($v, '9');
    }

    /**
     * 回访活动第二天超时取消
     * 未接手未完成23:50自动取消
     * 每天23:50执行
     */
    public function cancel_order_4()
    {
        $limit = strtotime('-2 hour');
        $sql = "select * from rqf_trade_order_%s where order_status = 0 and trade_type = 2 and day_index = 2 and second_start_time = 0";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();
        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();
        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();
        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();
        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();
        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();
        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();
        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();
        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();
        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_0 as $v) $this->exec_cancel($v, '0');
        foreach ($res_1 as $v) $this->exec_cancel($v, '1');
        foreach ($res_2 as $v) $this->exec_cancel($v, '2');
        foreach ($res_3 as $v) $this->exec_cancel($v, '3');
        foreach ($res_4 as $v) $this->exec_cancel($v, '4');
        foreach ($res_5 as $v) $this->exec_cancel($v, '5');
        foreach ($res_6 as $v) $this->exec_cancel($v, '6');
        foreach ($res_7 as $v) $this->exec_cancel($v, '7');
        foreach ($res_8 as $v) $this->exec_cancel($v, '8');
        foreach ($res_9 as $v) $this->exec_cancel($v, '9');
    }

    /**
     * 执行取消
     */
    private function exec_cancel($v, $suffix)
    {
        // 开启事务
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();

        // 1. 更新子活动状态
        $sql = "update rqf_trade_order_{$suffix} set order_status = 97 where id = {$v->id} and order_status = 0";
        $this->write_db->query($sql);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return;
        }

        // 2. 记录操作日志
        $action_info_ins = [
            'order_id' => $v->id,
            'order_sn' => $v->order_sn,
            'order_status' => 97,
            'order_note' => '超时取消',
            'add_time' => time(),
            'created_user' => 'system',
            'comments' => ''
        ];
        $this->write_db->insert("rqf_order_action_{$suffix}", $action_info_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return;
        }

        // 3. 扣减用户冻结金币并记录日志
        $user_info = $this->write_db->get_where('rqf_users', ['id' => $v->user_id])->row();
        $sql = "update rqf_users set frozen_point = frozen_point - 1 where id = {$v->user_id} and frozen_point >= 1";
        $this->write_db->query($sql);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return;
        }

        $user_point_ins = [
            'user_id' => $v->user_id,
            'action_time' => time(),
            'action_type' => 502,
            'score_nums' => '0',
            'last_score' => $user_info->user_point,
            'frozen_score_nums' => '-1',
            'last_frozen_score' => ($user_info->frozen_point - 1),
            'trade_sn' => $v->trade_sn,
            'order_sn' => $v->order_sn,
            'pay_sn' => '',
            'created_user' => 'system',
            'trade_pic' => ''
        ];
        $this->write_db->insert("rqf_buy_user_point_{$suffix}", $user_point_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return;
        }

        // 返还活动数
        if ($v->channel == '1') {
            $sql = "update rqf_trade_info set pc_num = pc_num + 1, apply_num = apply_num - 1 where id = {$v->trade_id} and apply_num > 0";
        } else {
            $sql = "update rqf_trade_info set phone_num = phone_num + 1, apply_num = apply_num - 1 where id = {$v->trade_id} and apply_num > 0";
        }
        $this->write_db->query($sql);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return;
        }

        $trade_info = $this->write_db->get_where('rqf_trade_info', ['id' => $v->trade_id])->row();
        if ($trade_info->trade_status == '9') {
            $bus_user_info = $this->write_db->get_where('rqf_users', ['id' => $trade_info->user_id])->row();
            $reward_deposit = bcdiv($trade_info->trade_deposit, $trade_info->total_num, 2);
            $reward_point = 0;
            $reward_point = bcadd($reward_point, $trade_info->total_fee, 2);
            if ($trade_info->is_phone) {
                $reward_point = bcadd($reward_point, 0.5, 2);
            }

            $trade_service = $this->db->get_where('rqf_trade_service', ['trade_id' => $trade_info->id])->result();
            $service_point = 0;
            $average_refund_list = ['plat_refund', 'bus_refund', 'set_traffic', 'kwd_eval', 'setting_eval', 'set_shipping', 'shopping_end', 'area_limit', 'sex_limit', 'reputation_limit', 'taoqi_limit', 'setting_picture', 'traffic_list', 'newhand'];
            foreach ($trade_service as $v1) {
                if (in_array($v1->service_name, $average_refund_list)) {
                    $service_point = bcadd($service_point, $v1->price, 2);
                }
            }
            $reward_point = bcadd($reward_point, $service_point, 2);
            $reward_point = bcadd($reward_point, $v->add_reward * 2, 2);
            $sql = "update rqf_users 
                       set user_deposit = user_deposit + {$reward_deposit}, frozen_deposit = frozen_deposit - {$reward_deposit}, user_point = user_point + {$reward_point}, frozen_point = frozen_point - {$reward_point}
                     where id = {$bus_user_info->id} and frozen_deposit >= {$reward_deposit} and frozen_point >= {$reward_point}";
            $this->write_db->query($sql);

            $user_deposit_ins = [
                'user_id' => $bus_user_info->id,
                'shop_id' => $v->shop_id,
                'action_time' => time(),
                'action_type' => 410,
                'score_nums' => '+' . $reward_deposit,
                'last_score' => bcadd($bus_user_info->user_deposit, $reward_deposit, 2),
                'frozen_score_nums' => '-' . $reward_deposit,
                'last_frozen_score' => bcsub($bus_user_info->frozen_deposit, $reward_deposit, 2),
                'trade_sn' => $v->trade_sn,
                'order_sn' => $v->order_sn,
                'pay_sn' => '',
                'created_user' => 'system',
                'trade_pic' => ''
            ];
            $this->write_db->insert("rqf_bus_user_deposit", $user_deposit_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                return;
            }

            $user_point_ins = [
                'user_id' => $bus_user_info->id,
                'shop_id' => $v->shop_id,
                'action_time' => time(),
                'action_type' => 411,
                'score_nums' => '+' . $reward_point,
                'last_score' => bcadd($bus_user_info->user_point, $reward_point, 2),
                'frozen_score_nums' => '-' . $reward_point,
                'last_frozen_score' => bcsub($bus_user_info->frozen_point, $reward_point, 2),
                'trade_sn' => $v->trade_sn,
                'order_sn' => $v->order_sn,
                'pay_sn' => '',
                'created_user' => 'system',
                'trade_pic' => ''
            ];
            $this->write_db->insert("rqf_bus_user_point", $user_point_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                return;
            }
        } else {
            // 进行中的任务单 需要返还各种参数
            // 图文好评，把图文好评已经分配的图片还原
            if ($trade_info->trade_type == '3') {
                $sql = "update rqf_setting_img set update_status = 0, update_time = 0 WHERE order_sn = ? ";
                $result = $this->write_db->query($sql, $v->order_sn);
            }
            // 指定评价内容 数据还原
            if ($trade_info->eval_type == '3') {
                $sql = "update rqf_setting_eval set update_status = 0, update_time = 0 where order_sn = ? ";
                $result = $this->write_db->query($sql, $v->order_sn);
            }
            // 返还检索关键词
            $sql = 'update rqf_trade_search set surplus_num = surplus_num + 1 where id = ? and trade_id = ? ';
            $result = $this->write_db->query($sql, [intval($v->search_id), intval($v->trade_id)]);
            // 限制买号重复进店下单  取消任务单，删除记录
            $sql = 'delete from rqf_trade_shopping_end where user_id = ? and binding_id = ? and shop_id = ? limit 1 ';
            $result = $this->write_db->query($sql, [intval($v->user_id), intval($v->account_id), intval($v->shop_id)]);
            // 记录该IP接任务信息
            $sql = 'delete from rqf_trade_order_ip_recode where user_id = ? and account_id = ? and trade_id = ? and shop_id = ? ';
            $result = $this->write_db->query($sql, [intval($v->user_id), intval($v->account_id), intval($v->trade_id), intval($v->shop_id)]);
        }

        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }

        // 记录取消次数
        $this->load->model('Cancel_Model', 'cancel');
        $this->cancel->add_times($v->user_id, $v->plat_id, $v->account_id);

        echo $v->order_sn . " canceled<br/>";
    }

    /** 浏览订单超时取消 扣减0.5金币 **/
    public function cancel_traffic_order() {
        $limit = strtotime('-1 hour');
        $sql = "select * from rqf_traffic_record_%s where traffic_status = 0 and add_time < ". $limit;
        for ($i = 0; $i < 10; $i++) {
            $query_result = $this->write_db->query(sprintf($sql, $i))->result();
            foreach ($query_result as $item) {
                $this->traffic_do_cancel($item, $i);
            }
        }
    }

    /** 处理浏览订单取消操作 */
    private function traffic_do_cancel($traffic_order, $suffix) {
        // 开启事务
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();
        // 1. 更新子活动状态
        $sql = "update rqf_traffic_record_{$suffix} set traffic_status = 97 where id = ? and traffic_status = 0";
        $this->write_db->query($sql, [intval($traffic_order->id)]);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return false;
        }

        // 2. 记录操作日志
        $action_info_ins = [
            'order_id' => intval($traffic_order->id),
            'order_sn' => $traffic_order->order_sn,
            'order_status' => 97,
            'order_note' => '超时取消',
            'add_time' => time(),
            'created_user' => 'system',
            'comments' => ''
        ];
        $this->write_db->insert("rqf_traffic_action_{$suffix}", $action_info_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return false;
        }

        // 3. 扣减用户金币并记录日志
        $user_info = $this->write_db->get_where('rqf_users', ['id' => intval($traffic_order->user_id)])->row();
        $sql = "update rqf_users set user_point = user_point - 0.5 where id = ? ";
        $this->write_db->query($sql, intval($traffic_order->user_id));
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return false;
        }
        // 金币操作记录
        $user_point_ins = [
            'user_id' => intval($traffic_order->user_id),
            'action_time' => time(),
            'action_type' => 200,
            'score_nums' => '0.5',
            'last_score' => floatval($user_info->user_point) - 0.5,
            'frozen_score_nums' => '0',
            'last_frozen_score' => $user_info->frozen_point,
            'trade_sn' => $traffic_order->trade_sn,
            'order_sn' => $traffic_order->order_sn,
            'pay_sn' => '',
            'created_user' => 'system',
            'trade_pic' => ''
        ];
        $this->write_db->insert("rqf_buy_user_point_{$suffix}", $user_point_ins);
        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            return false;
        }

        // 返还活动数
        $trade_info = $this->write_db->get_where('rqf_trade_info', ['id' => intval($traffic_order->trade_id)])->row();
        if ($trade_info->trade_status == '9') {
            $bus_user_info = $this->write_db->get_where('rqf_users', ['id' => intval($trade_info->user_id)])->row();
            $reward_point = 0;
            $this->load->model('Traffic_Model', 'traffic');
            $traffic_list = $this->traffic->get_traffic_list(intval($trade_info->created_time));
            $reward_point += floatval($traffic_list['normal_price']['price']);
            $reward_point += floatval($trade_info->add_reward);
            if (floatval($traffic_order->collect_goods) > 0) {
                $reward_point += floatval($traffic_list['collect_goods']['price']);
            }
            if (floatval($traffic_order->collect_shop) > 0) {
                $reward_point += floatval($traffic_list['collect_shop']['price']);
            }
            if (floatval($traffic_order->add_to_cart) > 0) {
                $reward_point += floatval($traffic_list['add_to_cart']['price']);
            }
            if (floatval($traffic_order->get_coupon) > 0) {
                $reward_point += floatval($traffic_list['get_coupon']['price']);
            }
            if (floatval($traffic_order->item_evaluate) > 0) {
                $reward_point += floatval($traffic_list['item_evaluate']['price']);
            }
            if (floatval($traffic_order->compare_goods) > 0) {
                $reward_point += floatval($traffic_list['compare_goods']['price']);
            }

            $sql = "update rqf_users set user_point = user_point + ?, frozen_point = frozen_point - ? where id = ? and frozen_point >= ? ";
            $this->write_db->query($sql, [floatval($reward_point), floatval($reward_point), intval($bus_user_info->id), floatval($reward_point)]);
            // 记录金币操作记录
            $user_point_ins = [
                'user_id' => intval($bus_user_info->id),
                'shop_id' => intval($traffic_order->shop_id),
                'action_time' => time(),
                'action_type' => 411,
                'score_nums' => '+' . $reward_point,
                'last_score' => bcadd($bus_user_info->user_point, $reward_point, 2),
                'frozen_score_nums' => '-' . $reward_point,
                'last_frozen_score' => bcsub($bus_user_info->frozen_point, $reward_point, 2),
                'trade_sn' => $traffic_order->trade_sn,
                'order_sn' => $traffic_order->order_sn,
                'pay_sn' => '',
                'created_user' => 'system',
                'trade_pic' => ''
            ];
            $this->write_db->insert("rqf_bus_user_point", $user_point_ins);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                return false;
            }
        } else {
            // 返还检索关键词
            $traffic_sql = 'update rqf_trade_traffic set surplus_num = surplus_num + 1 ';
            // 设置任务要求
            if (floatval($traffic_order->collect_goods) > 0) {
                $traffic_sql .= ', collect_goods = collect_goods + 1 ';
            }
            if (floatval($traffic_order->add_to_cart) > 0) {
                $traffic_sql .= ', add_to_cart = add_to_cart + 1 ';
            }
            if (floatval($traffic_order->collect_shop) > 0) {
                $traffic_sql .= ', collect_shop = collect_shop + 1 ';
            }
            if (floatval($traffic_order->get_coupon) > 0) {
                $traffic_sql .= ', get_coupon = get_coupon + 1 ';
            }
            if (floatval($traffic_order->item_evaluate) > 0) {
                $traffic_sql .= ', item_evaluate = item_evaluate + 1 ';
            }
            if (floatval($traffic_order->compare_goods) > 0) {
                $traffic_sql .= ', compare_goods = compare_goods + 1 ';
            }
            $traffic_sql .= 'where id = '. intval($traffic_order->traffic_id);
            $this->write_db->query($traffic_sql);
            if (!$this->write_db->affected_rows()) {
                $this->write_db->trans_rollback();
                return false;
            }
        }

        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }

        echo $traffic_order->order_sn . " canceled " .date('Y-m-d H:i:s') . PHP_EOL;
    }




    /**
     * 回访活动至第二天
     */
    public function back_visit_next_day()
    {
        echo 'start ：'. date('Y-m-d H:i:s', time());
        $limit_time = strtotime(date('Y-m-d'));
        $current_time = time() - 3600*8;
        $sql = "update rqf_trade_order_%s set day_index = 2, order_step = 5 where trade_type = 2 and order_status = 0 and day_index = 1 and order_step = 4 and first_end_time < {$limit_time} and first_end_time < {$current_time} and channel <> 4";
        $this->write_db->query(sprintf($sql, '0'));
        $this->write_db->query(sprintf($sql, '1'));
        $this->write_db->query(sprintf($sql, '2'));
        $this->write_db->query(sprintf($sql, '3'));
        $this->write_db->query(sprintf($sql, '4'));
        $this->write_db->query(sprintf($sql, '5'));
        $this->write_db->query(sprintf($sql, '6'));
        $this->write_db->query(sprintf($sql, '7'));
        $this->write_db->query(sprintf($sql, '8'));
        $this->write_db->query(sprintf($sql, '9'));

        echo ' ...... '. date('Y-m-d H:i:s', time()). PHP_EOL;
    }


    /** 超过48小时未确认返款，系统自动更新 */
    public function batch_confirm_plat_refund()
    {
        echo 'start at ：'. date('Y-m-d H:i:s', time()). PHP_EOL;
        $limit_time = strtotime("-2 day");
        for ($idx = 0; $idx < 10; $idx++) {
            $sql = "select o.id, o.order_sn 
                      from rqf_trade_order_{$idx} o left join rqf_trade_info i on o.trade_id = i.id
                     where o.comment_time > 0 and o.comment_time < ? and o.order_status = 4 and o.plat_refund = 1 and o.bus_modified = 0 and ROUND(o.order_money, 2) <= ROUND(i.price * i.buy_num + i.post_fee, 2)";
            $query_order_list = $this->write_db->query($sql, [$limit_time])->result_array();
            $update_order_list = [];
            foreach ($query_order_list as $trade_order) {
                $sql = "update rqf_trade_order_{$idx} set order_status = 5, confirm_time = ? where id = ? and order_status = 4 and plat_refund = 1 and bus_modified = 0 limit 1 ";
                $this->write_db->query($sql, [time(), intval($trade_order['id'])]);
                if ($this->write_db->affected_rows()) {
                    $order_action_ins = [
                        'order_id' => $trade_order['id'],
                        'order_sn' => $trade_order['order_sn'],
                        'order_status' => 5,
                        'order_note' => '后台操作商家平台返款',
                        'add_time' => time(),
                        'created_user' => 'system',
                        'comments' => '买家确认收货好评超过48小时，系统自动确认返款'
                    ];
                    $this->write_db->insert("rqf_order_action_{$idx}", $order_action_ins);
                }

                $update_order_list[] = $trade_order['order_sn'];
            }

            if (count($update_order_list) > 0) {
                echo implode(',', $update_order_list) . PHP_EOL;
            }
        }

        echo ' ...... '. date('Y-m-d H:i:s', time()). PHP_EOL;
    }
}
