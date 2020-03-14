<?php

/**
 * 邀请模型
 * @author hu
 */
class Invite_Model extends CI_Model
{

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function get_rewards_rate()
    {
        return ['first_reward' => 0.5, 'secode_reward' => 0.5];
    }

    /**
     * 是否有邀请过试客
     */
    public function has_invite()
    {
        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            return false;
        }

        $sql = "select id from sk_invite_buyer where parent_id = {$user_id}";

        return $this->db->query($sql)->row();
    }

    /**
     * 添加邀请试客记录
     */
    public function add_invite_buyer_record($user_id, $order_sn)
    {
        $this->write_db = $this->load->database('write', true);

        $this->load->model('User_Model', 'user');

        $user_info = $this->user->get_user_info($user_id);

        if ($user_info->user_type != 2) {
            return;
        }

        if ($user_info->pid == 0) {
            return;
        }

        $check_row = $this->write_db->get_where('sk_invite_buyer', ['user_id' => $user_id])->row();

        if ($check_row) {
            return;
        }

        $invite_buyer = [
            'parent_id' => $user_info->pid,
            'record_date' => date('Ymd'),
            'user_id' => $user_id,
            'user_name' => $user_info->nickname,
            'order_sn' => $order_sn,
            'add_time' => time(),
            'comments' => ''
        ];
        $this->write_db->insert('sk_invite_buyer', $invite_buyer);

        // 活动时间内注册的试客， 被邀请人没有奖励过
        $table = 'sk_order_ticket_' . suffix($user_info->pid);
        $sql = "select count(1) cnt from {$table} where user_id=? and from_status = 16";
        $reward_ticket = $this->db->query($sql, [$user_info->pid])->row();

        $sql = "select count(1) cnt from sk_invite_buyer where parent_id = ? and user_id != ?";
        $invite_row = $this->db->query($sql, [$user_info->pid, $user_id])->row();

        $this->load->model('Order_Model', 'order');
        $order_info = $this->order->get_trade_order_by_sn($order_sn);

        if ($user_info->reg_time >= strtotime('2017-05-26 00:00:00') && $reward_ticket->cnt == 0 && $invite_row->cnt == 0) {
            $insert_info = [];
            $insert_info['user_id'] = $user_info->pid;
            $insert_info['limit_money'] = 100;
            $insert_info['terminal'] = 0;
            $insert_info['start_time'] = strtotime(date("Y-m-d 00:00:00"));
            $insert_info['end_time'] = strtotime('00:00:00 +30 days') - 1;
            $insert_info['trade_id'] = $order_info->trade_id;
            $insert_info['trade_sn'] = $order_info->trade_sn;
            $insert_info['order_id'] = $order_info->id;
            $insert_info['order_sn'] = $order_info->order_sn;
            $insert_info['add_user'] = $user_info->nickname;
            $insert_info['add_time'] = time();
            $insert_info['from_text'] = '邀请好友得必中活动首次邀请赠送';
            $insert_info['from_status'] = 16;


            $table = 'sk_order_ticket_' . suffix($user_info->pid);
            $this->write_db->insert($table, $insert_info);
        }
    }

    /**
     * 添加邀请商家记录
     */
    public function add_invite_business_record($user_id, $pay_sn)
    {

        $this->write_db = $this->load->database('write', true);

        $this->load->model('User_Model', 'user');

        $user_info = $this->user->get_user_info($user_id);

        if ($user_info->group_id != 4) {
            return;
        }

        if ($user_info->pid == 0) {
            return;
        }

        $parent_info = $this->user->get_user_info($user_info->pid);

        if ($parent_info->group_id != '4' || $parent_info->expire_time < time()) {
            return;
        }

        $check_row = $this->db->get_where('sk_invite_business', ['user_id' => $user_id])->row();

        if ($check_row) {
            return;
        }

        $parent_row = $this->db->get_where('sk_invite_business', ['parent_id' => $user_info->pid])->row();

        $reward_money = 688;

        if ($parent_row) {
            $reward_money = 1000;
        }

        $invite_business = [
            'parent_id' => $user_info->pid,
            'record_date' => date('Ymd'),
            'user_id' => $user_id,
            'reward_money' => $reward_money,
            'reward_status' => 1,
            'pay_sn' => $pay_sn,
            'add_time' => time(),
            'comments' => ''
        ];

        $this->write_db->insert('sk_invite_business', $invite_business);

        if ($this->write_db->affected_rows()) {

            $parent_info = $this->user->get_user_info($user_info->pid);

            $suffix = suffix($user_info->pid);

            $user_deposit = [
                'user_id' => $user_info->pid,
                'action_time' => time(),
                'action_type' => 108,
                'score_nums' => '+' . $reward_money,
                'last_score' => ($parent_info->user_deposit + $reward_money),
                'frozen_score_nums' => 0,
                'last_frozen_score' => $parent_info->frozen_deposit,
                'trade_sn' => '',
                'order_sn' => '',
                'pay_sn' => $pay_sn,
                'created_user' => 'system',
                'trade_pic' => ''
            ];

            $this->write_db->insert("sk_user_deposit_{$suffix}", $user_deposit);

            $this->write_db->query("update sk_users set user_deposit = user_deposit + ? where id = ?", [$reward_money, $user_info->pid]);
        }
    }

    /**
     * 获取邀请试客信息
     */
    public function get_invite_buyer_vars()
    {

        $user_id = $this->session->userdata('user_id');

        $d = date('Ymd');

        $invite_vars = [];

        // 成功邀请试客
        $sql = "select count(1) cnt from sk_invite_buyer where parent_id = {$user_id}";

        $row = $this->db->query($sql)->row();

        $invite_vars['success_cnt'] = $row->cnt;

        // 邀请总数量
        $sql = "select count(1) cnt from sk_users where pid = {$user_id}";

        $row = $this->db->query($sql)->row();

        $invite_vars['invite_cnt'] = $row->cnt;

        $invite_vars['unsuccess_cnt'] = max(($invite_vars['invite_cnt'] - $invite_vars['success_cnt']), 0);

        // 当日成功领取数量
        $sql = "select count(1) cnt from sk_invite_buyer where parent_id = {$user_id} and record_date = {$d}";

        $row = $this->db->query($sql)->row();

        $invite_vars['today_success_cnt'] = $row->cnt;

        return (object)$invite_vars;
    }

    /**
     * 获取邀请试客未领取明细
     */
    public function get_unsuccess_detail()
    {

        $user_id = $this->session->userdata('user_id');

        $sql = "select a.id,a.nickname,aes_decrypt(a.mp4,a.id) mobile,ifnull(c.qq,'') qq,a.reg_time from sk_users a
				left join sk_invite_buyer b on a.id = b.user_id
				left join sk_user_attr c on a.id = c.user_id
				where a.pid = {$user_id}
				and b.user_id is null order by a.id desc";
        $res = $this->db->query($sql)->result();

        foreach ($res as $key => $value) {
            $suffix = suffix($value->id);
            $table = 'sk_trade_order_' . $suffix;
            $sql = "select count(1) cnt from {$table} where user_id = ?  and first_end_time > 0 ";
            $row = $this->db->query($sql, [$value->id])->row();
            $value->is_finish_first = 0;
            if ($row->cnt > 0) {
                $value->is_finish_first = 1;
            }
        }
        return $res;
    }

    /**
     * 获取邀请商家信息
     */
    public function get_invite_business_vars()
    {

        $user_id = $this->session->userdata('user_id');

        $invite_vars = [];

        // 成功邀请商家
        $sql = "select ifnull(count(1),0) cnt from sk_invite_business where parent_id = {$user_id}";

        $row = $this->db->query($sql)->row();

        $invite_vars['success_cnt'] = $row->cnt;

        // 邀请总数量
        $sql = "select ifnull(count(1),0) cnt from sk_users where pid = {$user_id} and user_type = 1";

        $row = $this->db->query($sql)->row();

        $invite_vars['invite_cnt'] = $row->cnt;

        $invite_vars['unsuccess_cnt'] = max(($invite_vars['invite_cnt'] - $invite_vars['success_cnt']), 0);

        return (object)$invite_vars;
    }

    /**
     * 本月邀请试客奖励
     */
    public function get_invite_buyer_reward()
    {

        $user_id = $this->session->userdata('user_id');

        $t = strtotime(date('Y-m-01 00:00:01'));

        $sql = "select count(1) cnt from sk_invite_buyer where parent_id = {$user_id} and add_time >= {$t}";

        $row = $this->db->query($sql)->row();

        $invite_reward = 0;

        if ($row->cnt <= 0)
            $invite_reward = 0;
        else if ($row->cnt <= 2)
            $invite_reward = $row->cnt * 3;
        elseif ($row->cnt <= 7)
            $invite_reward = $row->cnt * 5;
        elseif ($row->cnt >= 8)
            $invite_reward = $row->cnt * 10;

        return $invite_reward;
    }

    /**
     * 本月邀请商家奖励
     */
    public function get_invite_business_reward()
    {

        $user_id = $this->session->userdata('user_id');

        $t = strtotime(date('Y-m-01 00:00:01'));

        $sql = "select ifnull(sum(reward_money),0) sum from sk_invite_business where parent_id = {$user_id} and add_time >= {$t}";

        $row = $this->db->query($sql)->row();

        return $row->sum;
    }

    /**
     * 下次邀请商家奖励
     */
    public function get_next_invite_reward()
    {

        $user_id = $this->session->userdata('user_id');

        $row = $this->db->get_where('sk_invite_business', ['parent_id' => $user_id])->row();

        $next_invite_reward = 688;

        if ($row)
            $next_invite_reward = 1000;

        return $next_invite_reward;
    }


    /**
     * 邀请主页数据展示
     */
    public function invite_url($user_id)
    {
        $result = ['invite' => []];
        // 邀请商家注册数
        $redis_key = 'TOTAL_INVITE_REWARDS' . $user_id;
        $rewards_list = $this->cache->redis->get($redis_key);
        if (!$rewards_list) {
            $sql = 'select ifnull(sum(total_invite_nums), 0) total_invite_nums from rqf_invite_info where pid = ? ';
            $query_item = $this->db->query($sql, [$user_id])->row_array();
            $rewards_list['total_invite_nums'] = $query_item['total_invite_nums'];

            $sql = 'select ifnull(sum(i.finish_num), 0) total_num from rqf_invite_detail d left join rqf_trade_info i on d.user_id = i.user_id where d.pid = ? and d.record_flag = 0 and i.trade_status in (1, 2, 3, 6)  ';
            $query_item = $this->db->query($sql, [$user_id])->row_array();
            $rewards_list['total_num'] = $query_item['total_num'];
            $rewards_list['total_reward_points'] = number_format(floatval($query_item['total_num'] * 0.5), 2);

            $this->cache->redis->save($redis_key, $rewards_list, 600);
        }
        $result['invite'] = $rewards_list;

        // 邀请排行榜
        $redis_key = 'TOTAL_INVITE_REWARDS_RANK';
        $query_list = $this->cache->redis->get($redis_key);
        if (!$query_list) {
            $sql = 'select count(distinct(d.user_id)) total_invite_nums, sum(ifnull(i.finish_num, 0)) total_num , u.nickname
                  from rqf_invite_detail d 
                    left join rqf_trade_info i on d.user_id = i.user_id and i.trade_status in (1, 2, 3, 6) 
                    left join rqf_users u on d.pid = u.id
                 where d.record_flag = 0 
              group by d.pid order by total_num desc limit 3 ';
            $query_list = $this->db->query($sql, [$user_id])->result_array();
            foreach ($query_list as $key => $item) {
                $query_list[$key]['total_reward_points'] = number_format(floatval($item['total_num'] * 0.5), 2);
            }

            $this->cache->redis->save($redis_key, $query_list, 600);
        }
        $result['rank_list'] = $query_list;

        return $result ;
    }


    /**
     *
     */
    public function invite_record($user_id, $page = 1)
    {
        $data = array();

        # 分页信息
        $page = intval($page) ? intval($page) : 1;

        $per_page = 10;

        $offset = ($page - 1) * $per_page;

        $cnt_sql = "SELECT count(1) cnt FROM rqf_invite_info WHERE pid = ?";

        $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();


        #邀请会员记录 已天为单位
        $sql = "SELECT * FROM rqf_invite_info WHERE pid = ? ORDER BY record_date DESC LIMIT ?,?";
        $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();

        $data['data'] = $res;

        #分页
        $this->load->library('pagination');

        $config['base_url'] = "/invite/invite_record";
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';

        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        return $data;

    }

    /**
     *
     */
    public function invite_record_detail($user_id, $record_date)
    {
        $data = array();

        #邀请会员记录 已天为单位
        $sql = "SELECT record_date, total_invite_nums, total_vip_nums, total_reward_points, total_renew_vip_nums FROM rqf_invite_info WHERE pid = ? AND record_date = ?";
        $res = $this->db->query($sql, [intval($user_id), $record_date])->row_array();
        if (!$res) {
            $data['record'] = ['record_date' => $record_date, 'total_invite_nums' => 0, 'total_vip_nums' => 0, 'total_reward_points' => 0, 'total_renew_vip_nums' => 0];
        } else {
            $data['record'] = $res;
        }
        #记录详情列表
        $sql = "SELECT d.*,AES_DECRYPT(u.qq_decode,u.salt) qq 
                  FROM rqf_invite_detail d LEFT JOIN rqf_users u ON d.user_id = u.id  
                 WHERE d.pid = ? AND d.record_date = ? ";
        $res = $this->db->query($sql, [intval($user_id), $record_date])->result();
        $data['lists'] = $res;

        return $data;
    }


    /**
     */
    public function invite_reward($user_id, $page = 1)
    {
        $data = array();

        # 分页信息
        $page = intval($page) ? intval($page) : 1;

        $per_page = 10;

        $offset = ($page - 1) * $per_page;

        $cnt_sql = "SELECT count(1) cnt FROM rqf_reward_info WHERE user_id = ?";

        $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();


        #邀请会员记录 已天为单位
        $sql = "SELECT * FROM rqf_reward_info WHERE user_id = ? ORDER BY record_date DESC LIMIT ?,?";
        $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();

        $data['data'] = $res;

        #分页
        $this->load->library('pagination');

        $config['base_url'] = "/invite/invite_reward";
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';

        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        return $data;

    }


    /**
     *
     */
    public function invite_reward_detail($user_id, $record_date)
    {
        $data = array();

        #活动奖励记录 已天为单位
        $sql = "SELECT * FROM rqf_reward_info WHERE user_id = ? AND record_date = ?";
        $res = $this->db->query($sql, [$user_id, $record_date])->row();

        $data['reward'] = $res;


        #一共有多少买手做活动
        $sql = "SELECT 
                user_id,
                COUNT(1) count,
                SUM(p_reward_points) p_reward_points,
                SUM(pp_reward_points) pp_reward_points
                FROM rqf_reward_detail 
                WHERE user_id = ? 
                AND record_date = ? 
                GROUP BY user_id";
        $res = $this->db->query($sql, [$user_id, $record_date])->result();

        $data['lists'] = $res;

        foreach ($res as $key => &$value) {
            $id = $value->user_id;

            #每个买手做的活动详情
            $sql = "SELECT * FROM rqf_reward_detail WHERE user_id = ? AND record_date = ?";
            $lis = $this->db->query($sql, [$user_id, $record_date])->result();

            $data['lists'][$key]->lists = $lis;
        }

        return $data;

    }


    /**
     * 还未购买VIP的会员
     */
    public function get_novip_data($user_id)
    {
        # bus未购买VIP 
        $sql = 'SELECT *,AES_DECRYPT(qq_decode,salt) qq_decode FROM rqf_users WHERE pid = ? AND expire_time = ? ORDER BY reg_time DESC';

        $res = $this->db->query($sql, [$user_id, 0])->result();


        foreach ($res as $k => $v) {
            $res[$k]->title = '未获得会员';
        }

        return $res;

    }


    /**
     *
     */
    public function get_expired_data($user_id)
    {
        #当前时间
        $now = time();

        # buy过期会员
        $sql = 'SELECT *,AES_DECRYPT(qq_decode,salt) qq_decode FROM rqf_users WHERE pid = ? AND expire_time <> 0 AND expire_time < ? ORDER BY reg_time DESC';

        $res = $this->db->query($sql, array($user_id, $now))->result();


        foreach ($res as $k => $v) {
            $res[$k]->title = '会员已过期';
        }

        return $res;

    }

    /**
     * 30天未做活动
     */
    public function get_days_30_data($user_id)
    {
        #30天之前
        $line_time = strtotime('-30 day');

        # buy30天未做活动
        $sql = 'SELECT *,AES_DECRYPT(qq_decode,salt) qq_decode FROM rqf_users WHERE pid = ?  AND last_task_time < ? AND last_task_time > ? ORDER BY reg_time DESC';

        $res = $this->db->query($sql, array($user_id, $line_time, 0))->result();


        foreach ($res as $k => $v) {
            if ($v->user_type == 1) {
                $res[$k]->title = '超过30天没有发活动';
            } else {
                $res[$k]->title = '超过30天没有接活动';
            }
        }


        return $res;

    }


    /**
     * 购买会员/续费会员 根据时长 给pid 发奖励
     * @return
     * @param  $record_flag   购买人是 1 购买VIP 2 续费VIP
     * @param  $type          购买人是 1 商家 2 买手
     * @param  $user_id       购买会员的id
     * @param  $long_month    购买时长 3  6  9  12  24  48 月
     */
    public function group_parents_point($record_flag = 1, $type = 0, $user_id = 0, $long_month = 0)
    {
        try {
            $this->write_db = $this->load->database('write', true);
            $this->write_db->trans_begin();

            $type = intval($type);

            $user_id = intval($user_id);

            $long_month = intval($long_month);

            if ($record_flag == 1) {
                $record_text = '购买会员';
            } else {
                $record_text = '续费VIP';
            }

            if (!$user_id) {
                return;
            }

            # 会员类型是否符合要求
            if (!in_array($type, [1, 2])) {
                throw new Exception("会员类型不符合要求!", 1);
            }

            # 如果你是商家购买会员
            if ($type == 1) {
                # 购买时长是否符合要求
                if (!in_array($long_month, [3, 6, 12, 24, 48])) {
                    throw new Exception("购买时长不符合要求!", 1);
                }

                # 获取自己的邀请人的id 邀请人user_type
                $sql = "SELECT pid,user_type,AES_DECRYPT(mobile_decode,salt) mobile_decode FROM rqf_users WHERE id = ? and user_type = 1";

                $res = $this->write_db->query($sql, [$user_id])->row();

                if (!$res) {
                    throw new Exception("未获取到该用户信息，请检查!", 1);
                }

                if ($res->user_type != 1) {
                    throw new Exception("您不是商家，无法发放奖励，请检查!", 1);
                }

                $comments = "商家.$res->mobile_decode.$record_text.$long_month.个月赠送金币";

                # 如果有pid 那么给父亲发金币
                if (intval($res->pid)) {
                    # 获取自己的父亲的用户信息
                    $info_sql = "SELECT id,nickname,user_point,frozen_point,user_type FROM rqf_users WHERE id = ?";

                    $user_info = $this->write_db->query($info_sql, [$res->pid])->row();

                    if (!$user_info) {
                        throw new Exception("获取pid详情失败，无法发放奖励，请检查!", 1);
                    }

                    # 获取需要奖励多少金币
                    $point = $this->get_bus_point($long_month);

                    # 更新主表sql
                    $user_sql = "UPDATE rqf_users SET user_point = user_point + $point WHERE id = $user_info->id";

                    # 更新主表
                    if (!$this->write_db->query($user_sql)) {
                        throw new Exception("发放奖励失败!", 1);
                    }

                    # rqf_buy_user_point_[] 插入金币变化日志
                    $insert_point_res = $this->insert_point($user_info->user_type, $user_info, $point, 103, $comments);

                    if (!$insert_point_res) {
                        throw new Exception("插入金币变化日志失败!", 1);
                    }

                    # 购买会员| 续费会员 统计
                    $insert_invite_res = $this->insert_invite($user_id, $type, $record_flag, $long_month, 1, $comments);
                    if (!$insert_invite_res) {
                        throw new Exception("插入购买会员| 续费会员 统计失败!", 1);
                    }

                }

            }

            # 如果你是买手购买会员
            if ($type == 2) {
                # 购买时长是否符合要求
                if (!in_array($long_month, [3, 6, 9, 12, 24])) {
                    throw new Exception("购买时长不符合要求!", 1);
                }

                # 获取自己的邀请人的id 邀请人user_type
                $sql = "SELECT pid,user_type,AES_DECRYPT(mobile_decode,salt) mobile_decode FROM rqf_users WHERE id = ? and user_type = 1";

                $res = $this->write_db->query($sql, [$user_id])->row();

                if (!$res) {
                    throw new Exception("未获取到该用户信息，请检查!", 1);
                }

                if ($res->user_type != 2) {
                    throw new Exception("您不是买手，无法发放奖励，请检查!", 1);
                }

                $comments = "买手.$res->mobile_decode.$record_text.$long_month.个月赠送金币";

                # 如果有 pid
                if (intval($res->pid)) {
                    # 获取自己的父亲的用户信息
                    $info_sql = "SELECT id,nickname,user_point,frozen_point,user_type FROM rqf_users WHERE id = ?";

                    $user_info = $this->write_db->query($info_sql, [$res->pid])->row();

                    if (!$user_info) {
                        throw new Exception("获取pid详情失败，无法发放奖励，请检查!", 1);
                    }

                    # 获取需要奖励多少金币
                    $point = $this->get_buy_point($long_month);

                    # 更新主表sql
                    $user_sql = "UPDATE rqf_users SET user_point = user_point + $point WHERE id = $user_info->id";

                    # 更新主表
                    if (!$this->write_db->query($user_sql)) {
                        throw new Exception("发放奖励失败!", 1);
                    }

                    # rqf_buy_user_point_[] 插入金币变化日志
                    $insert_point_res = $this->insert_point($user_info->user_type, $user_info, $point, 103, $comments);

                    if (!$insert_point_res) {
                        throw new Exception("插入金币变化日志失败!", 1);
                    }

                    # 购买会员| 续费会员 统计
                    $insert_invite_res = $this->insert_invite($user_id, $type, $record_flag, $long_month, 1, $comments);
                    if (!$insert_invite_res) {
                        throw new Exception("插入购买会员| 续费会员 统计失败!", 1);
                    }
                }


            }

            $this->write_db->trans_commit();
            $this->write_db->close();

            return true;

        } catch (Exception $e) {
            $this->write_db->trans_rollback();
            return;
        }

    }

    /**
     * 获取商家购买会员时长对应奖励的金币
     * @return
     * @param  $long_month  购买时长 3  6  12  24  48 月
     */
    private function get_bus_point($long_month = 0)
    {
        $point = 0;
        $this->load->model('Conf_Model', 'conf');
        $group_price_list = $this->conf->group_price_list();
        if (array_key_exists($long_month, $group_price_list)) {
            $point = $group_price_list[$long_month]['rewards'];
        }

        return $point;
    }

    /**
     * 获取买手购买会员时长对应奖励的金币
     * @return
     * @param  $long_month  购买时长 3  6  9 12  24 月
     */
    private function get_buy_point($long_month = 0)
    {
        return 0 ;
        /**
        switch ($long_month) {
            case 3:
                $point = 7.5;
                break;
            case 6:
                $point = 15;
                break;
            case 9:
                $point = 22.5;
                break;
            case 12:
                $point = 30;
                break;
            case 24:
                $point = 60;
                break;
            default:
                $point = 0;
                break;
        }
        return $point;
        **/
    }


    /**
     * 插入金币变化日志
     * @return
     * @param  {int}      $type 1 商家 2买手
     * @param  {obj}      $user_info  商家\买手 用户信息
     * @param  {int}      $money  金币数量
     * @param  {int}      $action_type  金币变化类型
     * @param  {varchar}  $comments  备注
     */
    private function insert_point($type, $user_info, $money, $action_type = 105, $comments = '')
    {
        if (!in_array($type, [1, 2])) {
            return false;
        }

        # 1商家用户  2买手用户
        $type == 1 && ($table_name = 'rqf_bus_user_point');

        $type == 2 && ($table_name = 'rqf_buy_user_point_' . suffix($user_info->id));

        # 插入金币变化的sql语句
        $point_sql = "INSERT INTO 
                {$table_name} (
                user_id,
                action_time,
                action_type,
                score_nums,
                last_score,
                frozen_score_nums,
                last_frozen_score,
                trade_sn,
                order_sn,
                pay_sn,
                created_user,
                comments,
                trade_pic
                ) VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
                )";

        $insert_arr = array(
            $user_info->id,
            time(),
            $action_type,
            '+' . $money,
            $user_info->user_point + $money,
            0,
            $user_info->frozen_point,
            '',
            '',
            '',
            'system',
            $comments,
            ''
        );

        # 执行插入金币变化的sql语句
        if (!$this->write_db->query($point_sql, $insert_arr)) {
            return false;
        }
        return true;
    }


    /**
     * 购买会员| 续费会员
     * @return
     * @param  {int}     $user_id     用户id
     * @param  {int}     $user_type   1 商家  2 买手  (购买 | 续费)
     * @param  {int}     $record_flag 1 购买会员  2  续费VIP
     * @param  {int}     $long_month  购买时长 3  6  9  12  24  48 月
     * @param  {boolean} $is_reward   是否已经奖励 1 yes 2no
     * @param  {varchar} $remarks     备注
     */
    private function insert_invite($user_id = 0, $user_type = 0, $record_flag = 0, $long_month = 0, $is_reward = 1, $remarks = '')
    {

        # 会员类型 是否符合 要求
        if (!in_array($user_type, [1, 2])) {
            return false;
        }

        # record_flag 是否符合 要求
        if (!in_array($record_flag, [1, 2])) {
            return false;
        }

        # 获取自己的用户信息
        $sql = "SELECT pid,user_type,nickname,reg_time FROM rqf_users WHERE id = ?";

        $res = $this->write_db->query($sql, [$user_id])->row();

        if (!$res) {
            throw new Exception("未获取到该用户信息，统计数据写入失败，请检查!", 1);
        }


        # 获取自己的父亲的用户信息
        $info_sql = "SELECT id,nickname,user_point,frozen_point,user_type FROM rqf_users WHERE id = ?";

        $user_info = $this->write_db->query($info_sql, [$res->pid])->row();

        if (!$user_info) {
            throw new Exception("获取pid详情失败，统计数据写入失败，请检查!", 1);
        }


        if ($user_type == 1) { # 如果你是商家购买会员

            if ($res->user_type != 1) {
                throw new Exception("您不是商家，统计数据写入失败，请检查!", 1);
            }

            # 根据购买时长奖励金币
            $point = $this->get_bus_point($long_month);


        } else { # 如果你是买手购买会员

            if ($res->user_type != 2) {
                throw new Exception("您不是买手，统计数据写入失败，请检查!", 1);
            }

            # 根据购买时长奖励金币
            $point = $this->get_buy_point($long_month);

        }


        # 当前日期
        $now_date = date('Ymd', strtotime('now'));

        # 写入rqf_invite_detail
        $insert_arr = array(
            $user_info->id,
            $now_date,
            $record_flag,
            $user_type,
            $user_id,
            $res->nickname,
            date('Ymd', $res->reg_time),
            $long_month,
            $point,
            $is_reward,
            $remarks
        );

        $insert_sql = "INSERT INTO 
                    rqf_invite_detail (
                    pid,
                    record_date,
                    record_flag,
                    type,
                    user_id,
                    user_name,
                    reg_date,
                    pay_month,
                    reward_points,
                    is_reward,
                    remarks
                    ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if (!$this->write_db->query($insert_sql, $insert_arr)) {
            throw new Exception("统计数据详情写入失败!", 1);
        }

        if ($user_type == 1) { #商家
            $buy_vip_num = 0;
            $bus_vip_num = 1;

            $buy_vip_points = 0;
            $bus_vip_points = $point;

        } else { #买手
            $buy_vip_num = 1;
            $bus_vip_num = 0;

            $buy_vip_points = $point;
            $bus_vip_points = 0;

        }

        if ($record_flag == 1) {# 购买
            $sql = "INSERT INTO rqf_invite_info(
                        pid,
                        record_date,

                        total_vip_nums, 
                        buy_pay_vip_nums, 
                        bus_pay_vip_nums,

                        total_renew_vip_nums,
                        buy_renew_vip_nums,
                        bus_renew_vip_nums,

                        total_reward_points,
                        buy_reward_points,
                        bus_reward_points,

                        buy_pay_vip_points,
                        bus_pay_vip_points,

                        buy_renew_vip_points,
                        bus_renew_vip_points
                    ) VALUES (
                        $user_info->id,
                        $now_date,

                        1,
                        $buy_vip_num,
                        $bus_vip_num,

                        0,
                        0,
                        0,

                        $buy_vip_points+$bus_vip_points,
                        $buy_vip_points,
                        $bus_vip_points,

                        $buy_vip_points,
                        $bus_vip_points,

                        0,
                        0
                    ) ON DUPLICATE KEY
                    UPDATE total_vip_nums   = total_vip_nums+1, 
                           buy_pay_vip_nums = buy_pay_vip_nums+$buy_vip_num, 
                           bus_pay_vip_nums = bus_pay_vip_nums+$bus_vip_num,

                           total_renew_vip_nums = total_renew_vip_nums,
                           buy_renew_vip_nums   = buy_renew_vip_nums,
                           bus_renew_vip_nums   = bus_renew_vip_nums,

                           buy_pay_vip_points = buy_pay_vip_points+$buy_vip_points,
                           bus_pay_vip_points = bus_pay_vip_points+$bus_vip_points,

                           total_reward_points = total_reward_points+$buy_vip_points+$bus_vip_points,
                           buy_reward_points   = buy_reward_points+$buy_vip_points,
                           bus_reward_points   = bus_reward_points+$bus_vip_points,

                           buy_renew_vip_points = buy_renew_vip_points,
                           bus_renew_vip_points = bus_renew_vip_points";

        } else {# 续费
            $sql = "INSERT INTO rqf_invite_info(
                        pid,
                        record_date,

                        total_vip_nums, 
                        buy_pay_vip_nums, 
                        bus_pay_vip_nums,

                        total_renew_vip_nums,
                        buy_renew_vip_nums,
                        bus_renew_vip_nums,

                        total_reward_points,
                        buy_reward_points,
                        bus_reward_points,

                        buy_pay_vip_points,
                        bus_pay_vip_points,

                        buy_renew_vip_points,
                        bus_renew_vip_points
                    ) VALUES (
                        $user_info->id,
                        $now_date,

                        0,
                        0,
                        0,

                        1,
                        $buy_vip_num,
                        $bus_vip_num,

                        $buy_vip_points+$bus_vip_points,
                        $buy_vip_points,
                        $bus_vip_points,

                        0,
                        0,

                        $buy_vip_points,
                        $bus_vip_points
                    ) ON DUPLICATE KEY
                    UPDATE total_vip_nums   = total_vip_nums, 
                           buy_pay_vip_nums = buy_pay_vip_nums, 
                           bus_pay_vip_nums = bus_pay_vip_nums,

                           total_renew_vip_nums = total_renew_vip_nums+1,
                           buy_renew_vip_nums   = buy_renew_vip_nums+$buy_vip_num,
                           bus_renew_vip_nums   = bus_renew_vip_nums+$bus_vip_num,

                           buy_pay_vip_points = buy_pay_vip_points,
                           bus_pay_vip_points = bus_pay_vip_points,

                           total_reward_points = total_reward_points+$buy_vip_points+$bus_vip_points,
                           buy_reward_points   = buy_reward_points+$buy_vip_points,
                           bus_reward_points   = bus_reward_points+$bus_vip_points,

                           buy_renew_vip_points = buy_renew_vip_points+$buy_vip_points,
                           bus_renew_vip_points = bus_renew_vip_points+$bus_vip_points";

        }

        if (!$this->write_db->query($sql)) {
            throw new Exception("数据统计失败!", 1);
        } else {
            return true;
        }

    }


    /**
     * 邀注册统计
     * @return
     * @param  $pid        邀请人id
     * @param  $user_type  注册人是 1商家 还是 2买手
     * @param  $user_id    注册人id
     * @param  $user_name  注册人nickname
     * @param  $reg_time   注册时间
     */
    public function invite_count_num($pid = 0, $user_type = 1, $user_id = 0, $user_name = '', $reg_time = 0)
    {
        if (!$pid || !$user_id || !$reg_time) {
            return false;
        }
        // 会员类型 是否符合 要求
        if (!in_array($user_type, [1, 2])) {
            return false;
        }
        // 当前日期
        $now_date = date('Ymd', strtotime('now'));
        // 注册日期转换 xxxx/xx/xx
        $reg_date = date('Ymd', $reg_time);

        $this->write_db = $this->load->database('write', true);
        $this->write_db->trans_begin();
        try {
            // 写入rqf_invite_detail
            $insert_arr = array($pid, $now_date, 0, $user_type, $user_id, $user_name, $reg_date, 0, 0, 0, '邀请注册会员');
            $insert_sql = "INSERT INTO rqf_invite_detail (pid, record_date, record_flag, type, user_id, user_name, reg_date, pay_month, reward_points, is_reward, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if (!$this->write_db->query($insert_sql, $insert_arr)) {
                throw new Exception("统计数据详情写入失败!", 1);
            }

            if ($user_type == 1) {          // 商家
                $buy_invite_nums = 0;
                $bus_invite_nums = 1;
            } else {                        // 买手
                $buy_invite_nums = 1;
                $bus_invite_nums = 0;
            }

            // 写入rqf_invite_info
            $query_item = $this->db->query('select id from rqf_invite_info where pid = ? and record_date = ?', [$pid, $now_date])->row();
            if ($query_item) {
                $sql = 'update rqf_invite_info set total_invite_nums = total_invite_nums + 1, buy_invite_nums = buy_invite_nums + ?, bus_invite_nums = bus_pay_vip_nums + ? where id = ?';
                $result = $this->write_db->query($sql, [$buy_invite_nums, $bus_invite_nums, intval($query_item->id)]);
            } else {
                $sql = 'INSERT INTO rqf_invite_info(pid, record_date, total_invite_nums, buy_invite_nums, bus_invite_nums) VALUES (?, ?, 1, ?, ?)';
                $result = $this->write_db->query($sql, [$pid, $now_date, $buy_invite_nums, $bus_invite_nums]);
            }
            if (!$result) {
                throw new Exception("数据统计失败!", 1);
            }

            $this->write_db->trans_commit();
            $this->write_db->close();
            return true;
        } catch (Exception $e) {
            $this->write_db->trans_rollback();
            $this->write_db->close();
            return false;
        }
    }

    /** 会员完成任务统计 */
    public function get_finish_tast_reward($user_id, $page, $page_size = 6)
    {
        $page = (intval($page) <= 1) ? 1 : intval($page);
        $off_size = ($page - 1) * $page_size;
        $user_list = [];
        $result_list = [];

        $sql = 'select count(d.id) cnts from rqf_invite_detail d where d.record_flag = 0 and d.pid = ? ';
        $query_count = $this->db->query($sql, [intval($user_id)])->row_array();

        // 一级邀请会员
        $sql = 'select d.user_id, d.user_name, ifnull(sum(i.total_num), 0) total_num
                  from rqf_invite_detail d left join rqf_trade_info i on d.user_id = i.user_id and i.trade_status in (1, 2, 3, 6)
                 where d.record_flag = 0 and d.pid = ? group by d.user_id limit ?, ? ';
        $query_list = $this->db->query($sql, [intval($user_id), $off_size, $page_size])->result_array();

        // 二级邀请会员
        if ($query_list) {
            foreach ($query_list as $item) {
                $user_list[] = $item['user_id'];
                $result_list[$item['user_id']] = $item;
            }

            $str_user = implode(',', $user_list);
            $sql = 'select d.pid, count(distinct d.user_id) user_cnts, ifnull(sum(i.total_num), 0) total_num
                      from rqf_invite_detail d left join rqf_trade_info i on d.user_id = i.user_id and i.trade_status in (1, 2, 3, 6)
                     where d.record_flag = 0 and d.pid in ('. $str_user .') group by d.pid ';
            $query = $this->db->query($sql)->result_array();
            foreach ($query as $item) {
                $result_list[$item['pid']]['s_cnts'] = $item['user_cnts'];
                $result_list[$item['pid']]['s_total_num'] = $item['total_num'];
            }
        }

        // 分页参数
        $config['base_url'] = "/invite/invite_url";
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $query_count['cnts'];
        $config['per_page'] = $page_size;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';
        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();

        return ['list' => $result_list, 'pagination' => $pagination];
    }

    /** 解除商家会员邀请关系 */
    public function remove_invite_relation($pid, $user_id)
    {
        $sql = 'delete from rqf_invite_detail where pid = ? and user_id = ? and record_flag = 0 limit 1 ';
        $this->write_db = $this->load->database('write', true);
        $result = $this->write_db->query($sql, [intval($pid), intval($user_id)]);
        if ($result) {
            $sql = 'update rqf_users set pid = 0 where id = ? and pid = ? and user_type = 1 limit 1 ';
            $result = $this->write_db->query($sql, [intval($user_id), intval($pid)]);
        }
        $this->write_db->close();
        return $result ;
    }
}
