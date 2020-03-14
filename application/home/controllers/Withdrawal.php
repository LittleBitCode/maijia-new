<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:提现控制器
 * 担当:
 */
class Withdrawal extends Ext_Controller {

    /**
     * __construct
     */
    public function __construct () {

        parent::__construct();

        $this->load->model('Uniq_Model', 'uniq');

        $this->load->helper('encrypt_helper');

        $this->load->helper('burl');
    }

    /**
     * 提现本金
     */
    public function deposit() {

        $data = $this->data;

        $user_id = $this->session->userdata('user_id');

        // $bus_account = $this->db->get_where('rqf_bus_account', ['user_id'=>$user_id])->row();

        // if (empty($bus_account)) {
        //     redirect('center/withdrawal_info');
        //     return;
        // }

        // $data['bus_account'] = $bus_account;

        // $data['bank_info'] = $this->db->get_where('rqf_bank_info', ['bank_short_name'=>$buy_account->bank_short_name])->row();
        
        // 验证冻结
        $sql = "select * from rqf_users_authority where user_id = {$user_id} and status = 1 and type in (1,99)";

        $row = $this->db->query($sql)->row();

        if ($row) {
            redirectmessage('/center/index', '您的账号被禁止提现', '进入个人中心页面', 5);
            return;
        }


        // 验证黑名单
        $sql = "select * from rqf_withdrawal_blacklist where user_id = {$user_id} and type = 3 and deleted = 0";

        $row = $this->db->query($sql)->row();

        if ($row) {
            redirectmessage('/center/index', '您的账号被禁止提现', '进入个人中心页面', 5);
            return;
        }

        $this->load->view('withdrawal/deposit', $data);
    }

    /**
     * 提现本金提交
     */
    public function with_deposit_submit() {

        $with_deposit = floatval($this->input->post('with_deposit'));

        $with_password = trim($this->input->post('with_password'));

        $user_id = $this->session->userdata('user_id');

        $user_info = $this->db->get_where('rqf_users', ['id'=>$user_id])->row();

        if (bccomp($with_deposit, $user_info->user_deposit, 2) > 0) {
            error_back('提现金额不能大于账户余额!');
            return;
        }

        if ($with_deposit < BUS_WITH_LIMIT) {
            error_back('提现金额不能小于'.BUS_WITH_LIMIT.'元');
            return;
        }

        if ($user_info->trade_password != md5(md5($with_password).$user_info->salt)) {
            error_back('提现密码错误!');
            return;
        }

        // 验证冻结
        $sql = "select * from rqf_users_authority where user_id = {$user_id} and status = 1 and type in (1,99)";

        $row = $this->db->query($sql)->row();

        if ($row) {
            redirectmessage('/center/index', '您的账号被禁止提现', '进入个人中心页面', 5);
            return;
        }

        // 验证黑名单
        $sql = "select * from rqf_withdrawal_blacklist where user_id = {$user_id} and type = 3 and deleted = 0";

        $row = $this->db->query($sql)->row();

        if ($row) {
            redirectmessage('/center/index', '您的账号被禁止提现', '进入个人中心页面', 5);
            return;
        }

        // 提现本金
        $withdrawal_amount = $with_deposit;

        // 手续费
        $withdrawal_amount_fee = bcmul($withdrawal_amount, BUS_WITH_PERCENT, 2);

        $withdrawal_amount_fee = max($withdrawal_amount_fee, 2);

        // 到账金额
        $user_amount = bcsub($withdrawal_amount, $withdrawal_amount_fee, 2);

        // 提现编号
        $withdrawal_sn = $this->uniq->create_withdrawal_sn('ST');

        $user_withdrawal_ins = [
            'user_id'=>$user_id,
            'withdrawal_type'=>3,
            'withdrawal_sn'=>$withdrawal_sn,
            'withdrawal_amount'=>$withdrawal_amount,
            'withdrawal_amount_fee'=>$withdrawal_amount_fee,
            'user_amount'=>$user_amount,
            'withdrawal_cnt'=>1,
            'withdrawal_status'=>0,
            'add_time'=>time()
        ];

        $this->write_db = $this->load->database('write', true);

        // 开启事务
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();

        // 1. 添加提现记录
        
        $this->write_db->insert('rqf_user_withdrawal', $user_withdrawal_ins);

        $withdrawal_id = $this->write_db->insert_id();

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('withdrawal/deposit');
            return;
        }

        // 2. 冻结用户押金
        
        $user_info = $this->write_db->get_where('rqf_users', ['id'=>$user_id])->row();

        $sql = "update rqf_users
                set 
                user_deposit = user_deposit - {$with_deposit},
                frozen_deposit = frozen_deposit + {$with_deposit}
                where id = {$user_id}
                and user_deposit >= {$with_deposit}";

        $this->write_db->query($sql);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('withdrawal/deposit');
            return;
        }

        // 3. 记录日志
        
        $user_deposit_ins = [
            'user_id'=>$user_id,
            'action_time'=>time(),
            'action_type'=>302,
            'score_nums'=>'-'.$with_deposit,
            'last_score'=>bcsub($user_info->user_deposit, $with_deposit, 2),
            'frozen_score_nums'=>'+'.$with_deposit,
            'last_frozen_score'=>bcadd($user_info->frozen_deposit, $with_deposit, 2),
            'trade_sn'=>'',
            'order_sn'=>'',
            'pay_sn'=>$withdrawal_sn,
            'created_user'=>$this->session->userdata('nickname'),
            'trade_pic'=>''
        ];

        $this->write_db->insert("rqf_bus_user_deposit", $user_deposit_ins);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('withdrawal/deposit');
            return;
        }

        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }

        redirect('center/record_list/2');
    }

    /**
     * 撤销提现
     */
    public function cancel() {

        $withdrawal_id = intval($this->uri->segment(3));

        $user_id = $this->session->userdata('user_id');

        $withdrawal_info = $this->db->get_where('rqf_user_withdrawal', ['id'=>$withdrawal_id, 'user_id'=>$user_id])->row();

        if (empty($withdrawal_info)) {
            error_back('提现信息异常!');
            return;
        }

        if ($withdrawal_info->withdrawal_status != '0') {
            error_back('当前提现状态不可撤销提现!');
            return;
        }

        $referer = $this->input->server('HTTP_REFERER');

        $this->write_db = $this->load->database('write', true);

        // 开启事务
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();

        // 1. 更新提现状态
        $sql = "update rqf_user_withdrawal
                set
                withdrawal_status = 6
                where id = {$withdrawal_id}
                and user_id = {$user_id}
                and withdrawal_status = 0";

        $this->write_db->query($sql);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect($referer);
            return;
        }

        // 2. 解冻用户押金
        
        $user_info = $this->write_db->get_where('rqf_users', ['id'=>$user_id])->row();

        $sql = "update rqf_users
                set 
                user_deposit = user_deposit + {$withdrawal_info->withdrawal_amount},
                frozen_deposit = frozen_deposit - {$withdrawal_info->withdrawal_amount}
                where id = {$user_id}
                and frozen_deposit >= {$withdrawal_info->withdrawal_amount}";

        $this->write_db->query($sql);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('withdrawal/deposit');
            return;
        }

        // 3. 记录日志
        
        $user_deposit_ins = [
            'user_id'=>$user_id,
            'action_time'=>time(),
            'action_type'=>408,
            'score_nums'=>'+'.$withdrawal_info->withdrawal_amount,
            'last_score'=>bcadd($user_info->user_deposit, $withdrawal_info->withdrawal_amount, 2),
            'frozen_score_nums'=>'-'.$withdrawal_info->withdrawal_amount,
            'last_frozen_score'=>bcsub($user_info->frozen_deposit, $withdrawal_info->withdrawal_amount, 2),
            'trade_sn'=>'',
            'order_sn'=>'',
            'pay_sn'=>$withdrawal_info->withdrawal_sn,
            'created_user'=>$this->session->userdata('nickname'),
            'trade_pic'=>''
        ];

        $this->write_db->insert("rqf_bus_user_deposit", $user_deposit_ins);

        if (!$this->write_db->affected_rows()) {
            $this->write_db->trans_rollback();
            redirect('withdrawal/deposit');
            return;
        }

        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }

        redirect($referer);
    }
}
