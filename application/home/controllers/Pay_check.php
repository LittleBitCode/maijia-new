<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:支付回调控制器
 * 担当:
 */
class Pay_check extends MY_Controller {

    /**
     * __construct
     */
    public function __construct() {

        parent::__construct();

        $this->load->driver('cache');

        $this->load->model('User_Model', 'user');

        $this->load->model('Trade_Model', 'trade');

        $this->write_db = $this->load->database('write', true);
    }

    /**
     * 天工回调
     */
    public function teegon_callback() {

        if (!IS_POST) {
            return;
        }

        // 支付编号
        $pay_sn = trim($this->input->post('order_no'));
        // $pay_sn = $this->uri->segment(3);

        // 订单金额
        $amount = floatval($this->input->post('amount'));

        // 签名信息
        $sign = $this->input->post('sign');

        // 支付是否成功
        $is_success = trim($this->input->post('is_success'));

        if ($is_success != 'true') {
            return;
        }

        $pay_log = $this->db->get_where('rqf_pay_log', ['pay_sn'=>$pay_sn])->row();

        if ($pay_log->pay_status != 0) {
            return;
        }

        // if (bccomp($pay_log->pay_third, $amount, 2) != 0) {
        //     return;
        // }

        switch ($pay_sn{1}) {
            // 支付活动(商家)
            // case 'A':
            //     $this->pay_trade($pay_log);
            //     $this->cache->redis->save("TEEGON_".$pay_sn, 1, 300);
            //     break;
            // 充值押金(商家)
            case 'J':
                $this->pay_deposit($pay_log);
                $this->cache->redis->save("TEEGON_".$pay_sn, 2, 300);
                break;
            // 充值金币(商家)
            case 'F':
                $this->pay_point($pay_log);
                $this->cache->redis->save("TEEGON_".$pay_sn, 3, 300);
                break;
            // // 购买会员(商家)
            // case 'D':
            //     $this->pay_group($pay_log);
            //     $this->cache->redis->save("TEEGON_".$pay_sn, 4, 300);
            //     break;
        }
    }

    /**
     * 快钱回调(宝和)
     */
    public function kq_callback () {

        //人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
        $kq_check_all_para=$this->kq_ck_null($_REQUEST['merchantAcctId'],'merchantAcctId');
        //网关版本，固定值：v2.0,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['version'],'version');
        //语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['language'],'language');
        //签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['signType'],'signType');
        //支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['payType'],'payType');
        //银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['bankId'],'bankId');
        //商户订单号，,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['orderId'],'orderId');
        //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['orderTime'],'orderTime');
        //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['orderAmount'],'orderAmount');
        // 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['dealId'],'dealId');
        //银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['bankDealId'],'bankDealId');
        //快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['dealTime'],'dealTime');
        //商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['payAmount'],'payAmount');
        //费用，快钱收取商户的手续费，单位为分。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['fee'],'fee');
        //扩展字段1，该值与提交时相同
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['ext1'],'ext1');
        //扩展字段2，该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['ext2'],'ext2');
        //处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['payResult'],'payResult');
        //错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['errCode'],'errCode');

        $trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
        $MAC=base64_decode($_REQUEST['signMsg']);
    
        $fp = fopen("./public_cert/ytu/99bill.cert.rsa.20340630.cer", "r");
        $cert = fread($fp, 8192); 
        fclose($fp); 
        $pubkeyid = openssl_get_publickey($cert); 
        $ok = openssl_verify($trans_body, $MAC, $pubkeyid);

        if ($ok == 1) {

            if ($_REQUEST['payResult'] == '10') {

                // 支付编号
                $pay_sn = trim($_REQUEST['orderId']);
                // 订单金额
                $amount = $_REQUEST['payAmount'] / 100;

                $pay_log = $this->db->get_where('rqf_pay_log', ['pay_sn'=>$pay_sn])->row();

                if ($pay_log->pay_status != 0) {
                    return;
                }

                // if (bccomp($pay_log->pay_third, $amount, 2) != 0) {
                //     return;
                // }

                switch ($pay_sn{1}) {
                    // 支付活动(商家)
                    // case 'A':
                    //     $this->pay_trade($pay_log);
                    //     break;
                    // 充值押金(商家)
                    case 'J':
                        $this->pay_deposit($pay_log);
                        break;
                    // 充值金币(商家)
                    case 'F':
                        $this->pay_point($pay_log);
                        break;
                    // // 购买会员(商家)
                    // case 'D':
                    //     $this->pay_group($pay_log);
                    //     break;
                }

                $rtnOK = 1;
                $rtnUrl = DOMAIN_URL."/center";

            } else {
                $rtnOK = 0;
                $rtnUrl = DOMAIN_URL."/pay_check/error";
            }

        } else {
            $rtnOK = 0;
            $rtnUrl = DOMAIN_URL."/pay_check/error";
        }

        echo "<result>$rtnOK</result><redirecturl>$rtnUrl</redirecturl>";
    }

    /**
     * 快钱回调(翰韬)
     */
    public function kq2_callback () {

        //人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
        $kq_check_all_para=$this->kq_ck_null($_REQUEST['merchantAcctId'],'merchantAcctId');
        //网关版本，固定值：v2.0,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['version'],'version');
        //语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['language'],'language');
        //签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['signType'],'signType');
        //支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['payType'],'payType');
        //银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['bankId'],'bankId');
        //商户订单号，,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['orderId'],'orderId');
        //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['orderTime'],'orderTime');
        //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['orderAmount'],'orderAmount');
        // 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['dealId'],'dealId');
        //银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['bankDealId'],'bankDealId');
        //快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['dealTime'],'dealTime');
        //商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['payAmount'],'payAmount');
        //费用，快钱收取商户的手续费，单位为分。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['fee'],'fee');
        //扩展字段1，该值与提交时相同
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['ext1'],'ext1');
        //扩展字段2，该值与提交时相同。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['ext2'],'ext2');
        //处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['payResult'],'payResult');
        //错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
        $kq_check_all_para.=$this->kq_ck_null($_REQUEST['errCode'],'errCode');

        $trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
        $MAC=base64_decode($_REQUEST['signMsg']);
    
        $fp = fopen("./public_cert/leiyan/dapiliang/99bill.cert.rsa.20340630.cer", "r");
        $cert = fread($fp, 8192); 
        fclose($fp); 
        $pubkeyid = openssl_get_publickey($cert); 
        $ok = openssl_verify($trans_body, $MAC, $pubkeyid);

        if ($ok == 1) {

            if ($_REQUEST['payResult'] == '10') {

                // 支付编号
                $pay_sn = trim($_REQUEST['orderId']);
                // 订单金额
                $amount = $_REQUEST['payAmount'] / 100;

                $pay_log = $this->db->get_where('rqf_pay_log', ['pay_sn'=>$pay_sn])->row();

                if ($pay_log->pay_status != 0) {
                    return;
                }

                // if (bccomp($pay_log->pay_third, $amount, 2) != 0) {
                //     return;
                // }

                switch ($pay_sn{1}) {
                    // 支付活动(商家)
                    // case 'A':
                    //     $this->pay_trade($pay_log);
                    //     break;
                    // 充值押金(商家)
                    case 'J':
                        $this->pay_deposit($pay_log);
                        break;
                    // 充值金币(商家)
                    case 'F':
                        $this->pay_point($pay_log);
                        break;
                    // // 购买会员(商家)
                    // case 'D':
                    //     $this->pay_group($pay_log);
                    //     break;
                }

                $rtnOK = 1;
                $rtnUrl = DOMAIN_URL."/center";

            } else {
                $rtnOK = 0;
                $rtnUrl = DOMAIN_URL."/pay_check/error";
            }

        } else {
            $rtnOK = 0;
            $rtnUrl = DOMAIN_URL."/pay_check/error";
        }

        echo "<result>$rtnOK</result><redirecturl>$rtnUrl</redirecturl>";
    }

    /**
     * 支付活动(商家)
     */
    private function pay_trade($pay_log) {

        $sql = "update rqf_pay_log set pay_status = 1, pay_time = ? where id = ? and pay_status = 0";

        $trade_info = $this->write_db->get_where("rqf_trade_info", ['id'=>$pay_log->call_id])->row();

        $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

        $this->write_db->query($sql, [time(), $pay_log->id]);

        if ($this->write_db->affected_rows()) {

            // 解冻金币
            if ($pay_log->pay_point > 0) {

                $user_point = [
                    'user_id'=>$pay_log->user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time'=>time(),
                    'action_type'=>400,
                    'score_nums'=>'+'.$pay_log->pay_point,
                    'last_score'=>bcadd($user_info->user_gold, $pay_log->pay_point, 2),
                    'frozen_score_nums'=>'-'.$pay_log->pay_point,
                    'last_frozen_score'=>bcsub($user_info->frozen_point, $pay_log->pay_point, 2),
                    'trade_sn'=>$trade_info->trade_sn,
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $this->write_db->query("update rqf_users set user_point = user_point + ?,frozen_point = frozen_point - ? where id = ?", [$pay_log->pay_point,$pay_log->pay_point,$pay_log->user_id]);
            }

            // 解冻押金
            if ($pay_log->pay_deposit > 0) {

                $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

                $user_deposit = [
                    'user_id'=>$pay_log->user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time'=>time(),
                    'action_type'=>400,
                    'score_nums'=>'+'.$pay_log->pay_deposit,
                    'last_score'=>bcadd($user_info->user_deposit, $pay_log->pay_deposit, 2),
                    'frozen_score_nums'=>'-'.$pay_log->pay_deposit,
                    'last_frozen_score'=>bcsub($user_info->frozen_deposit, $pay_log->pay_deposit, 2),
                    'trade_sn'=>$trade_info->trade_sn,
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                $this->write_db->query("update rqf_users set user_deposit = user_deposit + ?,frozen_deposit = frozen_deposit - ? where id = ?", [$pay_log->pay_deposit,$pay_log->pay_deposit,$pay_log->user_id]);
            }

            // 充值押金
            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            $user_deposit = [
                'user_id'=>$pay_log->user_id,
                'shop_id' => $trade_info->shop_id,
                'action_time'=>time(),
                'action_type'=>100,
                'score_nums'=>'+'.$pay_log->pay_third,
                'last_score'=>($user_info->user_deposit + $pay_log->pay_third),
                'frozen_score_nums'=>0,
                'last_frozen_score'=>$user_info->frozen_deposit,
                'trade_sn'=>$trade_info->trade_sn,
                'order_sn'=>'',
                'pay_sn'=>$pay_log->pay_sn,
                'created_user'=>$user_info->nickname,
                'trade_pic'=>''
            ];

            $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

            $this->write_db->query("update sk_bus_users set user_deposit = user_deposit + ? where id = ?", [$pay_log->pay_third,$pay_log->user_id]);

            // 押金转金币
            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            $pay_trade_deposit = bcadd($pay_log->pay_deposit, $pay_log->pay_third, 2);

            if (bccomp($pay_trade_deposit, $trade_info->trade_deposit, 2) == 1) {

                $deposit_to_point = bcsub($pay_trade_deposit, $trade_info->trade_deposit, 2);

                $user_deposit = [
                    'user_id'=>$pay_log->user_id,
                    'shop_id' => $trade_info->shop_id,
                    'action_time'=>time(),
                    'action_type'=>200,
                    'score_nums'=>'-'.$deposit_to_point,
                    'last_score'=>bcsub($user_info->user_deposit, $deposit_to_point, 2),
                    'frozen_score_nums'=>0,
                    'last_frozen_score'=>$user_info->frozen_deposit,
                    'trade_sn'=>$trade_info->trade_sn,
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                $user_point = [
                    'user_id'=>$pay_log->user_id,
                    'action_time'=>time(),
                    'action_type'=>100,
                    'score_nums'=>'+'.$deposit_to_point,
                    'last_score'=>bcadd($user_info->user_point, $deposit_to_point, 2),
                    'frozen_score_nums'=>0,
                    'last_frozen_score'=>$user_info->frozen_point,
                    'trade_sn'=>$trade_info->trade_sn,
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_gold', $user_point);

                $this->write_db->query('update rqf_users set user_deposit = user_deposit - ?, user_point = user_point + ? where id = ?', [$deposit_to_point, $deposit_to_point, $pay_log->user_id]);
            }

            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            // 冻结押金
            $user_deposit = [
                'user_id'=>$pay_log->user_id,
                'shop_id' => $trade_info->shop_id,
                'action_time'=>time(),
                'action_type'=>300,
                'score_nums'=>'-'.$trade_info->trade_deposit,
                'last_score'=>bcsub($user_info->user_deposit, $trade_info->trade_deposit, 2),
                'frozen_score_nums'=>'+'.$trade_info->trade_deposit,
                'last_frozen_score'=>bcadd($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                'trade_sn'=>$trade_info->trade_sn,
                'order_sn'=>'',
                'pay_sn'=>$pay_log->pay_sn,
                'created_user'=>$user_info->nickname,
                'trade_pic'=>''
            ];

            $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

            // 冻结金币
            $user_point = [
                'user_id'=>$pay_log->user_id,
                'action_time'=>time(),
                'action_type'=>300,
                'score_nums'=>'-'.$trade_info->trade_point,
                'last_score'=>bcsub($user_info->user_point, $trade_info->trade_point, 2),
                'frozen_score_nums'=>'+'.$trade_info->trade_point,
                'last_frozen_score'=>bcadd($user_info->frozen_point, $trade_info->trade_point, 2),
                'trade_sn'=>$trade_info->trade_sn,
                'order_sn'=>'',
                'pay_sn'=>$pay_log->pay_sn,
                'created_user'=>$user_info->nickname,
                'trade_pic'=>''
            ];

            $this->write_db->insert('rqf_bus_user_gold', $user_point);

            $sql = 'update rqf_users 
                    set user_deposit = user_deposit - ?,
                        frozen_deposit = frozen_deposit + ?,
                        user_point = user_point - ?,
                        frozen_point = frozen_point + ?
                        where id = ?';

            $this->write_db->query($sql, [$trade_info->trade_deposit, $trade_info->trade_deposit, $trade_info->trade_point, $trade_info->trade_point, $pay_log->user_id]);

            // 更新活动状态
            $info = [
                'trade_step'=>6,
                'trade_status'=>1,
                'pay_point'=>$pay_log->pay_point,
                'pay_deposit'=>$pay_log->pay_deposit,
                'pay_bank'=>$pay_log->pay_third
            ];

            $key = [
                'id'=>$trade_info->id,
                'user_id'=>$pay_log->user_id,
                'trade_step'=>5,
                'trade_status'=>0
            ];

            $this->write_db->update("rqf_trade_info", $info, $key);

            // 操作日志
            $action_info = [
                'trade_id'=>$trade_info->id,
                'trade_sn'=>$trade_info->trade_sn,
                'trade_status'=>1,
                'trade_note'=>'活动已支付',
                'add_time'=>time(),
                'created_user'=>$user_info->nickname,
                'comments'=>''
            ];

            $this->write_db->insert('rqf_trade_action', $action_info);
        }
    }

    /**
     * 充值押金(商家)
     */
    private function pay_deposit($pay_log) {

        $sql = "update rqf_pay_log set pay_status = 1, pay_time = ? where id = ? and pay_status = 0";

        $this->write_db->query($sql, [time(), $pay_log->id]);

        if ($this->write_db->affected_rows()) {

            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            $user_deposit = [
                'user_id'=>$pay_log->user_id,
                'action_time'=>time(),
                'action_type'=>101,
                'score_nums'=>'+'.$pay_log->pay_third,
                'last_score'=>bcadd($user_info->user_deposit, $pay_log->pay_third, 2),
                'frozen_score_nums'=>0,
                'last_frozen_score'=>$user_info->frozen_deposit,
                'trade_sn'=>'',
                'order_sn'=>'',
                'pay_sn'=>$pay_log->pay_sn,
                'created_user'=>$user_info->nickname,
                'trade_pic'=>''
            ];

            $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

            $this->write_db->query("update rqf_users set user_deposit = user_deposit + ? where id = ?", [$pay_log->pay_third,$pay_log->user_id]);
        }
    }

    /**
     * 充值金币(商家)
     */
    private function pay_point($pay_log) {

        $sql = "update rqf_pay_log set pay_status = 1, pay_time = ? where id = ? and pay_status = 0";

        $this->write_db->query($sql, [time(), $pay_log->id]);

        if ($this->write_db->affected_rows()) {

            // 解冻押金
            if ($pay_log->pay_deposit > 0) {

                $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

                $user_deposit = [
                    'user_id'=>$pay_log->user_id,
                    'action_time'=>time(),
                    'action_type'=>401,
                    'score_nums'=>'+'.$pay_log->pay_deposit,
                    'last_score'=>bcadd($user_info->user_deposit, $pay_log->pay_deposit, 2),
                    'frozen_score_nums'=>'-'.$pay_log->pay_deposit,
                    'last_frozen_score'=>bcsub($user_info->frozen_deposit, $pay_log->pay_deposit, 2),
                    'trade_sn'=>'',
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                $user_deposit = [
                    'user_id'=>$pay_log->user_id,
                    'action_time'=>time(),
                    'action_type'=>200,
                    'score_nums'=>'-'.$pay_log->pay_deposit,
                    'last_score'=>$user_info->user_deposit,
                    'frozen_score_nums'=>0,
                    'last_frozen_score'=>($user_info->frozen_deposit - $pay_log->pay_deposit),
                    'trade_sn'=>'',
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                $user_point = [
                    'user_id'=>$pay_log->user_id,
                    'action_time'=>time(),
                    'action_type'=>101,
                    'score_nums'=>'+'.$pay_log->pay_deposit,
                    'last_score'=>($user_info->user_point + $pay_log->pay_deposit),
                    'frozen_score_nums'=>0,
                    'last_frozen_score'=>$user_info->frozen_point,
                    'trade_sn'=>'',
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $this->write_db->query("update rqf_users set frozen_deposit = frozen_deposit - ?, user_point = user_point + ? where id = ?", [$pay_log->pay_deposit,$pay_log->pay_deposit,$pay_log->user_id]);
            }

            // 充值金币
            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            $user_point = [
                'user_id'=>$pay_log->user_id,
                'action_time'=>time(),
                'action_type'=>101,
                'score_nums'=>'+'.$pay_log->pay_third,
                'last_score'=>($user_info->user_point + $pay_log->pay_third),
                'frozen_score_nums'=>0,
                'last_frozen_score'=>$user_info->frozen_point,
                'trade_sn'=>'',
                'order_sn'=>'',
                'pay_sn'=>$pay_log->pay_sn,
                'created_user'=>$user_info->nickname,
                'trade_pic'=>''
            ];

            $this->write_db->insert('rqf_bus_user_point', $user_point);

            $this->write_db->query('update rqf_users set user_point = user_point + ? where id = ?', [$pay_log->pay_third, $pay_log->user_id]);
        }
    }

    /**
     * 购买会员(商家)
     */
    private function pay_group ($pay_log) {

        $sql = "update rqf_pay_log set pay_status = 1, pay_time = ? where id = ? and pay_status = 0";

        $this->write_db->query($sql, [time(), $pay_log->id]);

        if ($this->write_db->affected_rows()) {

            // 解冻押金
            if ($pay_log->pay_deposit > 0) {

                $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

                $user_deposit = [
                    'user_id'=>$pay_log->user_id,
                    'action_time'=>time(),
                    'action_type'=>402,
                    'score_nums'=>'+'.$pay_log->pay_deposit,
                    'last_score'=>bcadd($user_info->user_deposit, $pay_log->pay_deposit, 2),
                    'frozen_score_nums'=>'-'.$pay_log->pay_deposit,
                    'last_frozen_score'=>bcsub($user_info->frozen_deposit, $pay_log->pay_deposit, 2),
                    'trade_sn'=>'',
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

                $this->write_db->query("update rqf_users set user_deposit = user_deposit + ?,frozen_deposit = frozen_deposit - ? where id = ?", [$pay_log->pay_deposit,$pay_log->pay_deposit,$pay_log->user_id]);
            }

            // 解冻金币
            if ($pay_log->pay_point > 0) {

                $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

                $user_point = [
                    'user_id'=>$pay_log->user_id,
                    'action_time'=>time(),
                    'action_type'=>401,
                    'score_nums'=>'+'.$pay_log->pay_point,
                    'last_score'=>bcadd($user_info->user_point, $pay_log->pay_point, 2),
                    'frozen_score_nums'=>'-'.$pay_log->pay_point,
                    'last_frozen_score'=>bcsub($user_info->frozen_point, $pay_log->pay_point, 2),
                    'trade_sn'=>'',
                    'order_sn'=>'',
                    'pay_sn'=>$pay_log->pay_sn,
                    'created_user'=>$user_info->nickname,
                    'trade_pic'=>''
                ];

                $this->write_db->insert('rqf_bus_user_point', $user_point);

                $this->write_db->query("update rqf_users set user_point = user_point + ?,frozen_point = frozen_point - ? where id = ?", [$pay_log->pay_point,$pay_log->pay_point,$pay_log->user_id]);
            }

            // 充值押金
            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            $user_deposit = [
                'user_id'=>$pay_log->user_id,
                'action_time'=>time(),
                'action_type'=>105,
                'score_nums'=>'+'.$pay_log->pay_third,
                'last_score'=>bcadd($user_info->user_deposit, $pay_log->pay_third, 2),
                'frozen_score_nums'=>0,
                'last_frozen_score'=>$user_info->frozen_deposit,
                'trade_sn'=>'',
                'order_sn'=>'',
                'pay_sn'=>$pay_log->pay_sn,
                'created_user'=>$user_info->nickname,
                'trade_pic'=>''
            ];

            $this->write_db->insert('rqf_bus_user_deposit', $user_deposit);

            $this->write_db->query("update rqf_users set user_deposit = user_deposit + ? where id = ?", [$pay_log->pay_third,$pay_log->user_id]);

            // 扣减押金
            $pay_deposit = bcadd($pay_log->pay_deposit, $pay_log->pay_third, 2);

            // 扣减金币
            $pay_point = $pay_log->pay_point;

            $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

            $base_time = max($user_info->expire_time, time());

            $pay_time = $pay_log->call_id;

            $expire_time = strtotime("+{$pay_time} month", $base_time);

            $sql = "update rqf_users
                    set
                    user_deposit = user_deposit - {$pay_deposit}, 
                    user_point = user_point - {$pay_point}, 
                    expire_time = {$expire_time}, 
                    group_id = 1
                    where id = {$pay_log->user_id}
                    and user_deposit >= {$pay_deposit}
                    and user_gold >= {$pay_gold}";

            $this->write_db->query($sql);

            if ($this->write_db->affected_rows()) {
                
                //citybear 04-12 记录商家开通会员花费（第三方）
                // $record_info = [
                //     'user_id' => $pay_log->user_id,
                //     'type' => 1,
                //     'gold' => $pay_log->pay_gold,
                //     'deposit' => $pay_log->pay_deposit,
                //     'third' => $pay_log->pay_third,
                //     'pay_sn' => $pay_log->pay_sn,
                //     'add_date' => date('Ymd'),
                //     'add_time' => time()
                // ];
                // $this->write_db->insert("sk_business_vip_record", $record_info);
  
                // 扣减押金日志
                if ($pay_deposit > 0) {
                    $user_deposit = [
                        'user_id'=>$pay_log->user_id,
                        'action_time'=>time(),
                        'action_type'=>203,
                        'score_nums'=>'-'.$pay_deposit,
                        'last_score'=>bcsub($user_info->user_deposit, $pay_deposit, 2),
                        'frozen_score_nums'=>0,
                        'last_frozen_score'=>$user_info->frozen_deposit,
                        'trade_sn'=>'',
                        'order_sn'=>'',
                        'pay_sn'=>$pay_log->pay_sn,
                        'created_user'=>$user_info->nickname,
                        'trade_pic'=>''
                    ];

                    $this->write_db->insert("rqf_bus_user_deposit", $user_deposit);
                }

                // 扣减金币日志
                if ($pay_point > 0) {
                    $user_point = [
                        'user_id'=>$pay_log->user_id,
                        'action_time'=>time(),
                        'action_type'=>201,
                        'score_nums'=>'-'.$pay_point,
                        'last_score'=>bcsub($user_info->user_point, $pay_point, 2),
                        'frozen_score_nums'=>0,
                        'last_frozen_score'=>$user_info->frozen_point,
                        'trade_sn'=>'',
                        'order_sn'=>'',
                        'pay_sn'=>$pay_log->pay_sn,
                        'created_user'=>$user_info->nickname,
                        'trade_pic'=>''
                    ];

                    $this->write_db->insert("rqf_bus_user_point", $user_point);
                }

                // 购买会员赠送金币
                $group_price_list = $this->conf->group_price_list();

                $give_point = $group_price_list[$pay_time]['give_point'];

                if ($give_point > 0) {

                    $user_info = $this->write_db->get_where("rqf_users", ['id'=>$pay_log->user_id])->row();

                    $user_point = [
                        'user_id'=>$user_id,
                        'action_time'=>time(),
                        'action_type'=>103,
                        'score_nums'=>'+'.$give_point,
                        'last_score'=>bcadd($user_info->user_point, $give_point, 2),
                        'frozen_score_nums'=>0,
                        'last_frozen_score'=>$user_info->frozen_point,
                        'trade_sn'=>'',
                        'order_sn'=>'',
                        'pay_sn'=>'',
                        'created_user'=>$this->session->userdata('nickname'),
                        'trade_pic'=>''
                    ];

                    $this->write_db->insert("rqf_bus_user_point", $user_point);

                    $this->write_db->query("update rqf_users set user_point = user_point + ? where id = ?", [$give_point, $pay_log->user_id]);
                }
            }
        }
    }

    /**
     * 快钱参数校验
     */
    private function kq_ck_null ($kq_va, $kq_na) {

        if ($kq_va == "") {
            return $kq_va = "";
        } else {
            return $kq_va=$kq_na.'='.$kq_va.'&';
        }
    }

    /**
     * 验证状态
     */
    public function check_status() {

        $pay_sn = $this->input->post('pay_sn');

        $this->load->driver('cache');

        $status = $this->cache->redis->get('TEEGON_'.$pay_sn);

        echo intval($status);
    }

    /**
     * 快钱支付成功
     */
    public function success() {

        $data = $this->data;

        $this->load->view('pay/success');
    }
    
    /**
     * 商家支付成功，第三方付款后，本地账户没有更新，执行更新
     */	
    public function t() {

        $pay_sn = $this->uri->segment(3);

        $pay_log = $this->db->get_where('rqf_pay_log', ['pay_sn'=>$pay_sn])->row();

        switch ($pay_sn{1}) {
            // 充值押金(商家)
            case 'J':
                $this->pay_deposit($pay_log);
        }
    }
}
