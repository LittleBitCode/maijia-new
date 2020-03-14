<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:开通/续费控制器
 * 担当:
 */
class Group extends Ext_Controller {

    /**
     * __construct
     */
    public function __construct () {

        parent::__construct();

        $this->load->model('Conf_Model', 'conf');

        $this->load->model('Uniq_Model', 'uniq');

        $this->load->model('Invite_Model', 'invite');
    }

    /**
     * 开通/续费会员
     */
    public function pay_group()
    {
        $data = $this->data;
        $user_info = $data['user_info'];
        $base_time = max($user_info->expire_time, time());
        $group_price_list = $this->conf->group_price_list();
        foreach ($group_price_list as $k => $v) {
            $group_price_list[$k]['expire_date'] = date('Y-m-d', strtotime("+{$k} month", $base_time));
        }

        $data['group_price_list'] = $group_price_list;
        $data['group_name'] = $this->is_vip() ? 'VIP会员' : '普通会员';
        if ($this->is_vip()) {
            $group_name = 'VIP会员';
            $surplus_timestamp = $user_info->expire_time - time();
            $day_timestamp = 24 * 60 * 60;
            $surplus_days = intval($surplus_timestamp / $day_timestamp);
        } else {
            $group_name = '普通会员';
            $surplus_days = 0;
        }

        $data['group_name'] = $group_name;
        $data['surplus_days'] = $surplus_days;

        $this->load->view('group/pay_group', $data);
    }

    /**
     * 开通/续费会员提交
     */
    public function pay_group_submit()
    {
        if (!IS_POST) {
            echo '非法提交';
            return;
        }

        $pay_time = intval($this->input->post('payvip'));
        $group_price_list = $this->conf->group_price_list();
        $pay_times = array_keys($group_price_list);
        if (!in_array($pay_time, $pay_times)) {
            $pay_time = $pay_times[1];
        }
        // 支付金额
        $price = $group_price_list[$pay_time]['price'];
        // 会员信息
        $user_id = $this->session->userdata('user_id');
        $this->write_db = $this->load->database('write', true);
        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

        // 账户押金
        $user_deposit = $user_info->user_deposit;
        // 金币支付
        $pay_point = 0;

        $surplus_price = $price;

        // 计算押金
        if (bccomp($surplus_price, $user_deposit, 2) > 0) {
            exit(json_encode(['status' => 0, 'message' => '账户押金不足']));
        } else {
            $pay_deposit = $surplus_price;
        }
        // 获取会员有效时间
        $base_time = max($user_info->expire_time, time());
        $expire_time = strtotime("+{$pay_time} month", $base_time);
        $sql = "update rqf_users
                   set user_deposit = user_deposit - {$pay_deposit}, user_point = user_point - {$pay_point}, expire_time = {$expire_time}, group_id = 1
                 where id = {$user_id} and user_deposit >= {$pay_deposit} and user_point >= {$pay_point}";
        $this->write_db->query($sql);
        if ($this->write_db->affected_rows()) {
            // 扣减押金日志
            if ($pay_deposit > 0) {
                $user_deposit = [
                    'user_id' => $user_id,
                    'action_time' => time(),
                    'action_type' => 203,
                    'score_nums' => '-' . $pay_deposit,
                    'last_score' => bcsub($user_info->user_deposit, $pay_deposit, 2),
                    'frozen_score_nums' => 0,
                    'last_frozen_score' => $user_info->frozen_deposit,
                    'trade_sn' => '',
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert("rqf_bus_user_deposit", $user_deposit);
            }

            // 扣减金币日志
            if ($pay_point > 0) {
                $user_point = [
                    'user_id' => $user_id,
                    'action_time' => time(),
                    'action_type' => 201,
                    'score_nums' => '-' . $pay_point,
                    'last_score' => bcsub($user_info->user_point, $pay_point, 2),
                    'frozen_score_nums' => 0,
                    'last_frozen_score' => $user_info->frozen_point,
                    'trade_sn' => '',
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];

                $this->write_db->insert("rqf_bus_user_point", $user_point);
            }

            // 购买会员赠送金币
            $give_point = $group_price_list[$pay_time]['give_point'];
            if ($give_point > 0) {
                $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();
                $user_point = [
                    'user_id' => $user_id,
                    'action_time' => time(),
                    'action_type' => 103,
                    'score_nums' => '+' . $give_point,
                    'last_score' => bcadd($user_info->user_point, $give_point, 2),
                    'frozen_score_nums' => 0,
                    'last_frozen_score' => $user_info->frozen_point,
                    'trade_sn' => '',
                    'order_sn' => '',
                    'pay_sn' => '',
                    'created_user' => $this->session->userdata('nickname'),
                    'trade_pic' => ''
                ];
                $this->write_db->insert("rqf_bus_user_point", $user_point);
                $this->write_db->query("update rqf_users set user_point = user_point + ? where id = ?", [$give_point, $user_id]);
            }

            // 记录购买会员日志
            $pay_group_ins = [
                'user_id' => $user_id,
                'user_type' => 1,
                'group_id' => 1,
                'group_price' => $price,
                'group_month' => $pay_time,
                'add_time' => time(),
                'pay_time' => time(),
                'pay_sn' => '',
                'pay_point' => $pay_point,
                'pay_deposit' => $pay_deposit,
                'comments' => "购买会员{$pay_time}个月,有效期至:" . date('Y-m-d H:i:s', $expire_time),
            ];

            $this->write_db->insert('rqf_pay_group', $pay_group_ins);
        }

        // 购买会员/续费会员 根据时长 给父亲 发奖励
        $this->invite->group_parents_point(1, 1, $user_id, $pay_time);
        $check_shop = $this->db->get_where('rqf_bind_shop', ['user_id' => $user_id])->row();
        if ($check_shop) {
            exit(json_encode(['status' => 1, 'url' => '/center']));
        } else {
            exit(json_encode(['status' => 1, 'url' => '/center/bind']));
        }
    }
}
