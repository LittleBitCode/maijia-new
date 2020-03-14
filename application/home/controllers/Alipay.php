<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:交易流水号充值
 * 担当:
 */
class Alipay extends CI_Controller
{
    protected $alidirect_account = "";
    protected $alidirect_pid = "";                  // www.zfbjk.com的商户ID
    protected $alidirect_key = "";                  // www.zfbjk.com的商户密钥

    protected $gmwang_account = "";
    protected $gmwang_pid = "";                  // www.zfbjk.com的商户ID
    protected $gmwang_key = "";                  // www.zfbjk.com的商户密钥

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->alidirect_account = '120999044@qq.com';
        $this->alidirect_pid = '25804';
        $this->alidirect_key = 'f723c2be98d13814ca11f7d7638d25d5';

        $this->gmwang_account = 'chongpaiming';
        $this->gmwang_pid = '26384';
        $this->gmwang_key = 'd9989169bf8cf359c435caab8e843290';
    }

    /**
     * 买手充值首页
     */
    public function index()
    {
        $tradeNo = isset($_POST['tradeNo']) ? $_POST['tradeNo'] : '';
        $Money = isset($_POST['Money']) ? $_POST['Money'] : 0;
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $memo = isset($_POST['memo']) ? $_POST['memo'] : '';
        $pay_time = isset($_POST['Paytime']) ? $_POST['Paytime'] : '';
        $Sign = isset($_POST['Sign']) ? $_POST['Sign'] : '';
        $title = iconv("UTF-8", "GB2312//IGNORE", $title);
        // MD5签名验证通过后，判断订单是否存在，判断订单是否已处理，判断订单金额是否与通知中的金额一致
        if (strtoupper(md5($this->alidirect_pid . $this->alidirect_key . $tradeNo . $Money . $title . $memo)) == strtoupper($Sign)) {
            // 先检查是否已经成功
            $this->write_db = $this->load->database('write', true);
            // 检查已存在的记录
            $sql = 'select rid, user_id, trade_no, title, money, is_success, pay_time from rqf_alipay_pay_recode where title = ? ';
            $pay_recode_query = $this->write_db->query($sql, [$title])->row();
            if ($pay_recode_query) {
                if (in_array($pay_recode_query->is_success, ['1', '2', '3'])) {
                    // 已标记成功
                    $this->write_db->close();
                    exit('Success');
                } else {
                    // 检查支付时间要求在30分钟内容完成
                    $expired_time = strtotime("-30 minutes");
                    if (strtotime($pay_time) < $expired_time) {
                        // 标记更新支付超时
                        $sql = 'update rqf_alipay_pay_recode set trade_no = ?, money = ?, contents = ?, is_success = 2, pay_time = ? where rid = ? limit 1 ';
                        $this->write_db->query($sql, [$tradeNo, $Money, json_encode($_POST), time(), intval($pay_recode_query->rid)]);

                        // 支付成功
                        $this->write_db->close();
                        exit('Success');
                    } else {
                        // 支付成功
                        $pay_log_data = [
                            'user_id' => intval($pay_recode_query->user_id),
                            'pay_type' => 1,
                            'pay_id' => 18,
                            'pay_sn' => $tradeNo,
                            'call_id' => 0,
                            'pay_point' => '0',
                            'pay_deposit' => '0',
                            'pay_third' => $Money,
                            'add_time' => time(),
                            'pay_time' => strtotime($pay_time),
                            'pay_status' => 1,
                            'comment' => $title,
                        ];
                        $result = $this->write_db->insert('rqf_pay_log', $pay_log_data);

                        // 更新用户资金记录
                        $user_info = $this->write_db->query('select id, nickname, user_deposit, frozen_deposit from rqf_users where id = ? ', [intval($pay_recode_query->user_id)])->row();
                        $user_deposit = [
                            'user_id' => intval($pay_recode_query->user_id),
                            'action_time' => time(),
                            'action_type' => 101,
                            'score_nums' => '+' . $Money,
                            'last_score' => bcadd($user_info->user_deposit, $Money, 2),
                            'frozen_score_nums' => 0,
                            'last_frozen_score' => $user_info->frozen_deposit,
                            'trade_sn' => '',
                            'order_sn' => '',
                            'pay_sn' => $tradeNo,
                            'created_user' => $user_info->nickname,
                            'trade_pic' => ''
                        ];
                        $result = $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);
                        $result = $this->write_db->query("update rqf_users set user_deposit = user_deposit + ? where id = ?", [$Money, intval($pay_recode_query->user_id)]);

                        // 标记更新成功
                        $sql = 'update rqf_alipay_pay_recode set trade_no = ?, money = ?, contents = ?, is_success = 1, pay_time = ? where rid = ? limit 1 ';
                        $this->write_db->query($sql, [$tradeNo, $Money, json_encode($_POST), time(), intval($pay_recode_query->rid)]);

                        // 支付成功
                        $this->write_db->close();
                        exit('Success');
                    }
                }
            } else {
                // 没有找到提交记录 只记录
                $pay_recode_data = [
                    'user_id' => 0, 'trade_no' => $tradeNo, 'title' => $title, 'money' => $Money, 'contents' => json_encode($_POST), 'is_success' => 3,
                    'pay_time' => strtotime($pay_time), 'add_time' => time()
                ];

                $result = $this->write_db->insert('rqf_alipay_pay_recode', $pay_recode_data);
                $this->write_db->close();
                exit('Success');
            }
        } else {
            // Sign签名验证失败
            exit('Fail');
        }
    }

    /**
     * 买手充值首页 - 王光明账号
     */
    public function gmwang()
    {
        $tradeNo = isset($_POST['tradeNo']) ? $_POST['tradeNo'] : '';
        $Money = isset($_POST['Money']) ? $_POST['Money'] : 0;
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $memo = isset($_POST['memo']) ? $_POST['memo'] : '';
        $pay_time = isset($_POST['Paytime']) ? $_POST['Paytime'] : '';
        $Sign = isset($_POST['Sign']) ? $_POST['Sign'] : '';
        $title = iconv("UTF-8", "GB2312//IGNORE", $title);
        // MD5签名验证通过后，判断订单是否存在，判断订单是否已处理，判断订单金额是否与通知中的金额一致
        if (strtoupper(md5($this->gmwang_pid . $this->gmwang_key . $tradeNo . $Money . $title . $memo)) == strtoupper($Sign)) {
            // 先检查是否已经成功
            $this->write_db = $this->load->database('write', true);
            // 检查已存在的记录
            $sql = 'select rid, user_id, trade_no, title, money, is_success, pay_time from rqf_alipay_pay_recode where title = ? ';
            $pay_recode_query = $this->write_db->query($sql, [$title])->row();
            if ($pay_recode_query) {
                if (in_array($pay_recode_query->is_success, ['1', '2', '3'])) {
                    // 已标记成功
                    $this->write_db->close();
                    exit('Success');
                } else {
                    // 检查支付时间要求在30分钟内容完成
                    $expired_time = strtotime("-30 minutes");
                    if (strtotime($pay_time) < $expired_time) {
                        // 标记更新支付超时
                        $sql = 'update rqf_alipay_pay_recode set trade_no = ?, money = ?, contents = ?, is_success = 2, pay_time = ? where rid = ? limit 1 ';
                        $this->write_db->query($sql, [$tradeNo, $Money, json_encode($_POST), time(), intval($pay_recode_query->rid)]);

                        // 支付成功
                        $this->write_db->close();
                        exit('Success');
                    } else {
                        // 支付成功
                        $pay_log_data = [
                            'user_id' => intval($pay_recode_query->user_id),
                            'pay_type' => 1,
                            'pay_id' => 18,
                            'pay_sn' => $tradeNo,
                            'call_id' => 0,
                            'pay_point' => '0',
                            'pay_deposit' => '0',
                            'pay_third' => $Money,
                            'add_time' => time(),
                            'pay_time' => strtotime($pay_time),
                            'pay_status' => 1,
                            'comment' => $title,
                        ];
                        $result = $this->write_db->insert('rqf_pay_log', $pay_log_data);

                        // 更新用户资金记录
                        $user_info = $this->write_db->query('select id, nickname, user_deposit, frozen_deposit from rqf_users where id = ? ', [intval($pay_recode_query->user_id)])->row();
                        $user_deposit = [
                            'user_id' => intval($pay_recode_query->user_id),
                            'action_time' => time(),
                            'action_type' => 101,
                            'score_nums' => '+' . $Money,
                            'last_score' => bcadd($user_info->user_deposit, $Money, 2),
                            'frozen_score_nums' => 0,
                            'last_frozen_score' => $user_info->frozen_deposit,
                            'trade_sn' => '',
                            'order_sn' => '',
                            'pay_sn' => $tradeNo,
                            'created_user' => $user_info->nickname,
                            'trade_pic' => ''
                        ];
                        $result = $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);
                        $result = $this->write_db->query("update rqf_users set user_deposit = user_deposit + ? where id = ?", [$Money, intval($pay_recode_query->user_id)]);

                        // 标记更新成功
                        $sql = 'update rqf_alipay_pay_recode set trade_no = ?, money = ?, contents = ?, is_success = 1, pay_time = ? where rid = ? limit 1 ';
                        $this->write_db->query($sql, [$tradeNo, $Money, json_encode($_POST), time(), intval($pay_recode_query->rid)]);

                        // 支付成功
                        $this->write_db->close();
                        exit('Success');
                    }
                }
            } else {
                // 没有找到提交记录 只记录
                $pay_recode_data = [
                    'user_id' => 0, 'trade_no' => $tradeNo, 'title' => $title, 'money' => $Money, 'contents' => json_encode($_POST), 'is_success' => 3,
                    'pay_time' => strtotime($pay_time), 'add_time' => time()
                ];

                $result = $this->write_db->insert('rqf_alipay_pay_recode', $pay_recode_data);
                $this->write_db->close();
                exit('Success');
            }
        } else {
            // Sign签名验证失败
            exit('Fail');
        }
    }
}