<?php
/**
 * 名称:提现模型
 * 担当:@胡 海平
 */
class Withdrawal_Model extends Common_Model {
    
    /**
     * construct
     */
    public function __construct() {

        parent::__construct();

        $this->load->model('User_Model', 'user');
    }
    
    /**
     * 获取当月提现次数
     */
    public function get_withdrawal_cnt () {

        $user_id = $this->session->userdata('user_id');

        $st = strtotime(date('Y-m-01 00:00:01'));

        $et = strtotime('+1 month', $st);

        $sql = 'select count(1) cnt from sk_user_withdrawal where user_id = ? and add_time >= ? and add_time < ? and withdrawal_status in (0,1,2,4)';

        $row = $this->db->query($sql, [$user_id, $st, $et])->row();

        return $row->cnt;
    }

    /**
     * 金额，笔数统计
     */
    public function get_withdrawal_vars ($user_id) {

        if (empty($user_id)) {
            $user_id = $this->session->userdata('user_id');
        }

        $user_info = $this->user->get_user_info($user_id);

        $limit_time = strtotime('-'.DEPOSIT_FROZE_DAYS.' days');

        $suffix = suffix($user_id);

        $withdrawal_vars = [];

        // 待返还金额，笔数
        $waiting_refund_cnt = 0;
        $waiting_refund_sum = 0;

        $sql = "select order_money from sk_trade_order_{$suffix}
                where user_id = {$user_id}
                and order_status > 3 and order_status < 11";

        $res = $this->db->query($sql)->result();

        foreach ($res as $v) {
            $waiting_refund_cnt += 1;
            $waiting_refund_sum = bcadd($waiting_refund_sum, $v->order_money, 2);
        }

        $withdrawal_vars['waiting_refund_cnt'] = $waiting_refund_cnt;
        $withdrawal_vars['waiting_refund_sum'] = $waiting_refund_sum;

        // 已返款，冻结中金额，笔数
        $freezing_cnt = 0;
        $freezing_sum = 0;

        $sql = "select withdrawal_money,withdrawal_id from sk_trade_order_{$suffix}
                where user_id = {$user_id}
                and order_status in (11,12,13) and refund_time > {$limit_time}";

        $res = $this->db->query($sql)->result();

        foreach ($res as $v) {

            if ($v->withdrawal_id == '0') {
                $freezing_cnt += 1;
                $freezing_sum = bcadd($freezing_sum, $v->withdrawal_money, 2);
            }
        }

        $withdrawal_vars['freezing_cnt'] = $freezing_cnt;
        $withdrawal_vars['freezing_sum'] = $freezing_sum;

        // 已返款，已解冻金额，笔数
        $thawing_cnt = 0;
        $thawing_sum = 0;

        $sql = "select withdrawal_money,withdrawal_id from sk_trade_order_{$suffix}
                where user_id = {$user_id}
                and order_status in (11,12,13) and refund_time < {$limit_time}";

        $res = $this->db->query($sql)->result();

        foreach ($res as $v) {

            if ($v->withdrawal_id == '0') {
                $thawing_cnt += 1;
                $thawing_sum = bcadd($thawing_sum, $v->withdrawal_money, 2);
            }
        }

        $withdrawal_vars['thawing_cnt'] = $thawing_cnt;
        $withdrawal_vars['thawing_sum'] = $thawing_sum;

        // 总笔数
        $withdrawal_vars['total_cnt'] = $withdrawal_vars['waiting_refund_cnt'] + $withdrawal_vars['freezing_cnt'] + $withdrawal_vars['thawing_cnt'];

        // 总金额
        $tmp = bcadd($withdrawal_vars['waiting_refund_sum'], $withdrawal_vars['freezing_sum'], 2);
        $withdrawal_vars['total_sum'] = bcadd($tmp, $withdrawal_vars['thawing_sum'], 2);

        // 账户结余
        $tmp = bcadd($withdrawal_vars['freezing_sum'], $withdrawal_vars['thawing_sum'], 2);
        $withdrawal_vars['last_surplus'] = bcsub($user_info->user_deposit, $tmp, 2);

        // 可提现总额
        $withdrawal_vars['withdrawal_money'] = bcsub($user_info->user_deposit, $withdrawal_vars['freezing_sum'], 2);

        // 可提现金额
        $withdrawal_money_limit = 0;

        if ($withdrawal_vars['withdrawal_money'] >= 200) {
            $withdrawal_money_limit = intval($withdrawal_vars['withdrawal_money'] / 100) * 100;
        }

        $withdrawal_vars['withdrawal_money_limit'] = $withdrawal_money_limit;

        return (object)$withdrawal_vars;
    }

    /**
     * 最近本金提现记录
     */
    public function get_withdrawal_record () {

        $user_id = $this->session->userdata('user_id');

        $sql = "select * from sk_user_withdrawal
                where user_id = ? order by id desc limit 10";

        return $this->db->query($sql, [$user_id])->result();
    }

    /**
     * 添加提现记录
     */
    public function add ($user_withdrawal) {

        $this->write_db = $this->load->database('write', true);

        $this->write_db->insert('sk_user_withdrawal', $user_withdrawal);

        return $this->write_db->insert_id();
    }

    /**
     * 计算剩余时间
     */
    public function get_surplus_time ($t) {
        $dval = $t - strtotime('-'.DEPOSIT_FROZE_DAYS.' days');

        $day_s = 24 * 3600;

        $v = ceil($dval / $day_s);

        if ($v > 1) {
            return $v . '天';
        } else {
            return ceil($dval / 3600) . '小时';
        }
    }
}