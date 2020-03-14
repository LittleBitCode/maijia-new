<?php

/**
 * 商家个人中心模型
 *
 */
class Center_Model extends CI_Model
{

    /**
     * construct
     */
    public function __construct()
    {

        parent::__construct();

        $this->load->model('Conf_Model', 'conf');
    }

    /**
     * 押金相关变量
     */
    public function deposit_vars($user_info)
    {

        // 可用押金
        $deposit_vars['user_deposit'] = $user_info->user_deposit;

        // 总押金
        $deposit_vars['total_deposit'] = bcadd($user_info->user_deposit, $user_info->frozen_deposit, 2);

        // 提现冻结押金
        $sql = "select ifnull(sum(withdrawal_amount),0) sum from rqf_user_withdrawal
                where user_id = {$user_info->id}
                and withdrawal_type = 3
                and withdrawal_status in (0,1,4)";

        $row = $this->db->query($sql)->row();

        $with_frozen_deposit = $row->sum;

        $deposit_vars['with_frozen_deposit'] = $with_frozen_deposit;

        // 活动冻结押金
        $deposit_vars['trade_frozen_deposit'] = bcsub($user_info->frozen_deposit, $with_frozen_deposit, 2);

        return (object)$deposit_vars;
    }

    /**
     * 邀请奖励相关变量
     */
    public function reward_vars()
    {

        $user_id = $this->session->userdata('user_id');

        $d = date('Ymd');

        // 合计完成活动奖励
        $total_order_reward = 0;
        // 合计购买会员奖励
        $total_group_reward = 0;
        // 合计邀请奖励
        $total_invite_reward = 0;
        // 今日完成活动奖励
        $today_order_reward = 0;
        // 今日购买会员奖励
        $today_group_reward = 0;
        // 今日邀请奖励
        $today_invite_reward = 0;

        $sql = "select ifnull(sum(total_reward_points),0) sum from rqf_reward_info where user_id = {$user_id}";

        $row = $this->db->query($sql)->row();

        $total_order_reward = $row->sum;

        $sql = "select ifnull(total_reward_points,0) sum from rqf_reward_info where user_id = {$user_id} and record_date = {$d}";

        $row = $this->db->query($sql)->row();

        $total_invite_reward = $row->sum;


        $total_invite_reward = bcadd($total_order_reward, $total_group_reward, 2);

        $today_invite_reward = bcadd($today_order_reward, $today_group_reward, 2);

        return (object)[
            'total_order_reward' => $total_order_reward * 1,
            'total_group_reward' => $total_group_reward * 1,
            'total_invite_reward' => $total_invite_reward * 1,
            'today_order_reward' => $today_order_reward * 1,
            'today_group_reward' => $today_group_reward * 1,
            'today_invite_reward' => $today_invite_reward * 1
        ];
    }

    /**
     * 网站公告列表
     * @param $type 公告类型
     * 1  => 商家个人中心公告 0 => 买手个人中心公告
     */
    public function notice_list()
    {
        $sql = "SELECT type,contents,url,created_time,end_time 
                  FROM rqf_notice
                 WHERE type = 1 AND show_status = 1 AND is_delete = 0 AND end_time >= ? order by created_time desc ";
        $return = $this->db->query($sql, array(time()))->result();
        foreach ($return as $key => $item) {
            $return[$key]->url = $this->replace_host($item->url);
        }

        return $return;
    }

    /**
     * 常见问题列表
     * @param $type 问题类型
     *  '0' => '【商家个人中心】',
     * '1' => '【买手个人中心】',
     * '2' => '【买手活动列表】',
     * '3' => '【搜索商品步】',
     * '4' => '【核对商品】',
     * '5' => '【浏览店铺及在线聊天】',
     * '6' => '【放入购物车】',
     * '7' => '【下单付款】 ',
     * '8' => '【绑定买号】',
     * '9' => '【确认收货好评】',
     * '10' => '【已付款，待发货】',
     * '11' => '【已收货,待商家确认返款】',
     * '12' => '【提现管理】 ',
     * '13' => '【佣金记录】',
     * '14' => '【垫付本金提现】',
     * '15' => '【核实平台返款本金】',
     * '16' => '【商家已返款,待买手确认】',
     * '17' => '【买手个人中心 侧边】',
     */
    public function question_list($type)
    {
        $sql = "SELECT type,contents,url,created_time,end_time 
                  FROM rqf_question
                 WHERE type = ? AND show_status = 1 AND is_delete = 0 AND end_time >= ?
                order by created_time desc ";
        $return = $this->db->query($sql, array($type, time()))->result();
        foreach ($return as $key => $item) {
            $return[$key]->url = $this->replace_host($item->url);
        }

        return $return;
    }

    /**
     * 获得绑定店铺类目
     *
     */
    public function get_cate($cate_id)
    {
        $sql = "select * from rqf_category where pid = ? and status = 0";
        $res = $this->db->query($sql, array($cate_id));
        if ($res) {
            return $res->result_array();
        } else {
            return array();
        }
    }

    /**
     * @param  用户信息
     */
    public function userinfo($user_id)
    {
        $sql = 'SELECT salt, login_password, trade_password, AES_DECRYPT(mobile_decode,salt) mobile, AES_DECRYPT(qq_decode,salt) qq, AES_DECRYPT(weixin_decode,salt) weixin
    			 FROM rqf_users
    		    WHERE id = ? and user_type = 1';
        $res = $this->db->query($sql, [$user_id])->row();
        if ($res) {
            $res->mobile == null && ($res->mobile = '');
            $res->qq == null && ($res->qq = '');
            $res->weixin == null && ($res->weixin = '');;
        }
        return $res;
    }


    /**
     * 修改登录密码
     */
    public function update_login_password($user_id, $new_password)
    {

        $sql = "SELECT salt FROM rqf_users WHERE id = $user_id";
        $res = $this->db->query($sql)->row();

        if ($res) {
            $this->write_db = $this->load->database('write', true);

            $new_password = MD5(MD5($new_password) . $res->salt);

            $sql = "UPDATE rqf_users SET
	                        login_password = ?
	                      WHERE 
                             id  = ? ";
            $this->write_db->query($sql, array(
                $new_password,
                $user_id
            ));
            if ($this->write_db->affected_rows() > 0) return true;
        }
        return false;

    }


    /**
     * 获取发送验证码mobile
     */
    public function send_verification_code($user_id)
    {
        $sql = "SELECT AES_DECRYPT(mobile_decode,salt) mobile FROM rqf_users WHERE id = {$user_id} and user_type = 1";
        $res = $this->db->query($sql)->row();
        if ($res) {
            return $res->mobile;
        } else {
            return false;
        }
    }

    /**
     * 统计一个手机一天内发送的验证码次数
     */
    public function sms_num($mobile)
    {
        # 当天的零点
        $day_begin = strtotime(date('Y-m-d', time()));

        # 当天的24
        $day_end = $day_begin + 24 * 60 * 60;

        $sql = "SELECT COUNT(1) num 
                FROM rqf_phone_check_code 
                WHERE phone_num = $mobile 
                AND request_time > $day_begin 
                AND request_time < $day_end";
        $res = $this->db->query($sql)->row();

        return $res->num;
    }


    /**
     */
    public function insert_msg($mobile, $code)
    {
        $this->write_db = $this->load->database('write', true);

        #短信有效时间120
        $time = time();
        $sql = "INSERT INTO 
                rqf_phone_check_code (
                phone_num,
                code,
                request_time,
                send_time,
                issuccess,
                notes
                ) VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
                )";
        $res = $this->write_db->query($sql, array(
            $mobile,
            $code,
            $time,
            $time,
            1,
            '发送成功'
        ));
        $this->write_db->close();

        if ($res) return true;
        return;
    }

    /**
     * 检查验证码
     */
    public function check_verification_code($user_id, $vcode)
    {
        $sql = "SELECT AES_DECRYPT(mobile_decode,salt) mobile FROM rqf_users WHERE id = $user_id and user_type = 1";
        $res = $this->db->query($sql)->row_array();
        #检查手机号码是否符合
        if (Common::check_phone($res['mobile'])) {
            $now_time = time();
            $mobile = $res['mobile'];

            $sql = "SELECT id, code, send_time 
                      FROM rqf_phone_check_code 
                    WHERE phone_num = $mobile AND issuccess = 1 AND code_status = 0 ORDER BY send_time DESC LIMIT 1";
            $res = $this->db->query($sql)->row_array();
            if ($res && is_array($res)) {
                $code = $res['code'];
                $send_time = $res['send_time'];
                if ($code == $vcode && ($now_time - $send_time) <= 600) {
                    $this->write_db = $this->load->database('write', true);
                    $sql = "UPDATE rqf_phone_check_code SET code_status = ? WHERE id  = ? ";
                    $this->write_db->query($sql, array(1, $res['id']));

                    return true;
                }
            }
        }
        return false;
    }


    /**
     * 修改提现密码
     */
    public function update_trade_password($user_id, $new_password)
    {
        $sql = "SELECT salt FROM rqf_users WHERE id = $user_id";
        $res = $this->db->query($sql)->row();

        if ($res) {
            $this->write_db = $this->load->database('write', true);

            $new_password = MD5(MD5($new_password) . $res->salt);

            $sql = "UPDATE rqf_users SET
	                        trade_password = ?
                      WHERE 
	                        id  = ? ";
            $this->write_db->query($sql, array(
                $new_password,
                $user_id
            ));
            if ($this->write_db->affected_rows() > 0) return true;
        }
        return false;

    }

    /** 更新用户名微信账号 */
    public function update_weixin($user_id, $weixin)
    {
        // 检查改微信是否被绑定
        $sql = "SELECT COUNT(1) cot FROM rqf_users WHERE weixin_ciphertext = ?";
        $res = $this->db->query($sql, [MD5($weixin)])->row();
        if ($res->cot) {
            return Common::failure("该微信已被绑定!", '', "");
        }

        # 更新微信
        $this->write_db = $this->load->database('write', true);
        $sql = "UPDATE rqf_users SET weixin_decode = AES_ENCRYPT(?,salt), weixin_ciphertext = ? WHERE id  = ? ";
        $this->write_db->query($sql, array($weixin, MD5($weixin), $user_id));
        if ($this->write_db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /** 更新QQ账号 */
    public function update_qq($user_id, $qq)
    {
        # 检查改QQ是否被绑定
        $sql = "SELECT COUNT(1) cot FROM rqf_users WHERE qq_ciphertext = ?";
        $res = $this->db->query($sql, [MD5($qq)])->row();
        if ($res->cot) {
            return Common::failure("该QQ已被绑定!", '', "");
        }

        # 更新QQ
        $this->write_db = $this->load->database('write', true);
        $sql = "UPDATE rqf_users SET qq_decode = AES_ENCRYPT(?,salt), qq_ciphertext = ? WHERE id  = ? ";
        $this->write_db->query($sql, array($qq, MD5($qq), $user_id));
        if ($this->write_db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取首页部分安全等级
     */
    public function get_safety_level_info()
    {
        $user_id = $this->session->userdata('user_id');
        $sql = "select * from rqf_users where id = ?";

        $user_info = $this->db->query($sql, array($user_id));
        if ($user_info) {
            $return['user_info'] = $user_info->row();
        } else {
            $return['user_info'] = array();
        }
        //获取提现账户信息
        $sql = "select * from rqf_bus_account WHERE user_id = ?";
        $account_info = $this->db->query($sql, array($user_id))->row();
        if ($account_info) {
            $return['account_info'] = $account_info;
        } else {
            $return['account_info'] = array();
        }
        return $return;
    }

    /** 切换帮忙中心URL链接 */
    private function replace_host($url)
    {
        $parse_url = parse_url($url);
        return HELP_CENTER_URL. $parse_url['path'];
    }
}
