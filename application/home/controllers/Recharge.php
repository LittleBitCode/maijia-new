<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:充值控制器
 * 担当:
 */
class Recharge extends Ext_Controller {

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Conf_Model', 'conf');
        $this->load->model('Uniq_Model', 'uniq');
        $this->load->model('Base64_Model', 'base64');
        $this->load->helper('qiniu_helper');
    }

    /*** 充值押金 */
    public function deposit()
    {
        $data = $this->data;
        $user_id = intval($data['is_login']);
        $type = in_array($this->uri->segment(3), ['alipay', 'wx', 'bank', 'unionpay']) ? $this->uri->segment(3) : 'bank' ;
        if ('alipay' == $type) {
            // 支付宝账号
            $alipay_account_list = $this->conf->get_recharge_alipay_account();
            $alipay_key = $user_id % count($alipay_account_list);
            $data['alipay_info'] = $alipay_account_list[$alipay_key];
        } elseif ('wx' == $type) {
            $wx_account_list = $this->conf->get_recharge_wx_account();
            $wx_key = $user_id % count($wx_account_list);
            $data['wx_info'] = $wx_account_list[$wx_key];
        } elseif ('bank' == $type) {
            // 银行卡
            $bank_account_list = $this->conf->get_recharge_bank_account();
            $bank_key = $user_id % count($bank_account_list);
            $data['bank_info'] = $bank_account_list[$bank_key];
        } elseif ('unionpay' == $type) {
            $user_id = intval($this->session->userdata('user_id'));
            $data['bank_pay_list'] = $this->conf->get_bank_pay_list();
            $rechart_list_check = $this->db->query('select id from rqf_bus_unautocheck where userid = ? and type = 2', [$user_id])->row();
            if ($rechart_list_check) {
                $data['bank_pay_list']['99'] = ['bank_code' => '', 'title' => '其他银行', 'img' => '', 'credit_cart' => 1, 'is_show' => 1];
            }
        }

        $data['type'] = $type;
        $this->load->view('recharge/deposit', $data);
    }

    /**
     * 充值押金提交
     */
    public function deposit_submit()
    {
        if (!IS_POST) {
            echo '非法提交';
            return;
        }

        // 提交参数
        $pay_deposit = floatval($this->input->post('recharge_number'));
        $pay_id = intval($this->input->post('pay_id'));
        $cart_type = intval($this->input->post('cart_type'));       // 1 => saving   2 => credit
        $bank_pay_list = $this->conf->get_bank_pay_list();
        $pay_id_list = array_keys($bank_pay_list);
        if (!in_array($pay_id, $pay_id_list) && $pay_id != '99') {
            error_back('请选择页面上现有的银行进行充值!');
            return;
        }
        if ($pay_deposit < 500) {
            error_back('充值押金不能小于500元!');
            return;
        } elseif ($pay_deposit > 50000) {
            error_back('充值押金不能大于50000元!');
            return;
        }

        $user_id = intval($this->session->userdata('user_id'));
        $prefix = 'SJ';
        $pay_sn = $this->uniq->create_pay_sn($prefix);

        // 快钱参数
        $params = [
            'orderId' => $pay_sn,
            'productName' => "充值押金" . $pay_deposit . "元",
            'orderAmount' => $pay_deposit * 100
        ];

        if ('99' == $pay_id) {
            $params['bankId'] = '';
            $params['payType'] = 10;
            $comment = '快钱充值';
        } else {
            $pay_item = $bank_pay_list[$pay_id];
            $params['bankId'] = $pay_item['bank_code'];
            $params['payType'] = ($cart_type == 2) ? '10-2' : '10-1';
            $comment = ($cart_type == 2) ? $pay_item['title'] . '信用卡充值' : $pay_item['title'] . '储蓄卡充值';
        }

        // 记录支付日志
        $pay_log = [
            'user_id' => $user_id,
            'pay_type' => 1,
            'pay_id' => $pay_id,
            'pay_sn' => $pay_sn,
            'call_id' => 0,
            'pay_point' => 0,
            'pay_deposit' => 0,
            'pay_third' => $pay_deposit,
            'add_time' => time(),
            'pay_time' => 0,
            'pay_status' => 0,
            'comment' => $comment,
        ];
        $this->write_db = $this->load->database('write', true);
        $this->write_db->insert('rqf_pay_log', $pay_log);
        $this->write_db->close();

        // 快钱请求
        $this->load->library('kuaiqian', $params);
        $this->kuaiqian->send();
    }

    /**
     * 充值金币
     */
    public function point()
    {
        $data = $this->data;
        $this->load->view('recharge/point', $data);
    }

    /**
     * 充值金币提交
     */
    public function point_submit()
    {
        if (!IS_POST) {
            exit(json_encode(['status' => 0, 'msg' => '非法提交']));
        }

        $recharge_point = intval($this->input->post('point'));
        $recharge_point_list = $this->conf->recharge_point_list();
        if (!in_array($recharge_point, $recharge_point_list)) {
            $recharge_point = $recharge_point_list[0];
        }

        // 会员信息
        $this->write_db = $this->load->database('write', true);
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->write_db->get_where('rqf_users', ['id' => $user_id])->row();

        // 账户押金
        $user_deposit = $user_info->user_deposit;
        if (bccomp($recharge_point, $user_deposit, 2) > 0) {
            exit(json_encode(['status' => 0, 'msg' => '账户押金不足']));
        } else {
            $pay_deposit = $recharge_point;
        }
        // 押金扣减记录
        $user_deposit = [
            'user_id' => $user_id,
            'action_time' => time(),
            'action_type' => 201,
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
        $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);
        // 金币充值记录
        $user_point = [
            'user_id' => $user_id,
            'action_time' => time(),
            'action_type' => 101,
            'score_nums' => '+' . $pay_deposit,
            'last_score' => bcadd($user_info->user_point, $pay_deposit, 2),
            'frozen_score_nums' => 0,
            'last_frozen_score' => $user_info->frozen_point,
            'trade_sn' => '',
            'order_sn' => '',
            'pay_sn' => '',
            'created_user' => $this->session->userdata('nickname'),
            'trade_pic' => ''
        ];

        $this->write_db->insert('rqf_bus_user_point', $user_point);
        $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$pay_deposit, $pay_deposit, $user_id]);
        $this->write_db->close();
        exit(json_encode(['status' => 1, 'msg' => '充值操作成功']));
    }

    // ajax请求是否支付成功
    public function check_pay()
    {
        $pay_sn = $this->input->post('pay_sn');
        $res = $this->cache->redis->get("TEEGON_" . $pay_sn);
        if ($res) {
            exit(json_encode(array('status' => 1, 'msg' => '支付成功')));
        } else {
            exit(json_encode(array('status' => 0, 'msg' => '尚未支付')));
        }
    }

    /** 支付宝扫码支付生成随机 */
    public function get_random_code()
    {
        // 增加文件锁
        $lock = $this->load->library('filelock', array('filename' => "randomcode.lock"), 'lock');
        $this->lock->writeLock();

        $user_id = intval($this->session->userdata('user_id'));
        $random = $this->uniq->create_alipay_random();
        $this->write_db = $this->load->database('write', true);
        $alipay_pay_recode_data = [
            'user_id' => $user_id,
            'trade_no' => '',
            'title' => $random,
            'add_time' => time()
        ];
        $this->write_db->insert('rqf_alipay_pay_recode', $alipay_pay_recode_data);
        $this->write_db->close();
        $this->lock->unlock();      // 解锁
        echo $random ;
    }

    /** 检查支付宝充值状态 */
    public function check_recharge_status()
    {
        $random_code = $this->input->post('code');
        $user_id = intval($this->session->userdata('user_id'));
        if (empty($random_code)) {
            echo 0 ; exit();
        }

        $sql = 'select 1 from rqf_alipay_pay_recode where user_id = ? and title = ? and is_success = 1 and pay_time > 0';
        $query_item = $this->db->query($sql, [$user_id, $random_code])->row();
        if ($query_item) {
            echo 1 ; exit();
        } else {
            echo 0 ; exit();
        }
    }
}
