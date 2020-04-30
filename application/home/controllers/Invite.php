<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:邀请控制器
 * 担当:
 */
class Invite extends Ext_Controller
{

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Invite_Model', 'invite');
    }

    /** 邀请会员 */
    public function invite_url()
    {
        $data = $this->data;
        $page_size = 6 ;
        $page = intval($this->input->get('page'));
        $user_id = intval($this->session->userdata('user_id'));

        // 页面展示统计数据
        $data['result'] = $this->invite->invite_url($user_id);
        $this->load->model('Conf_Model', 'conf');
        $data['group_price_list'] = $this->conf->group_price_list();
        $data['rewards_rate'] = $this->invite->get_rewards_rate();
        $data['invite_task_rewards'] = $this->invite->get_finish_tast_reward($user_id, $page, $page_size);

        $sql = "select count(1) cnt from rqf_users where pid = {$user_id} and user_type = 1";
        $row = $this->db->query($sql)->row();
        $data['invite_num'] = $row->cnt;
        // 邀请获得奖励
        $sql = "select count(1) cnt from rqf_bus_user_point where user_id = {$user_id} and action_type in (112,113)";
        $row = $this->db->query($sql)->row();
        $data['invite_order_num'] = $row->cnt;
        $data['invite_order_reward'] = $row->cnt * 0.5;
        // 查看用户是否有发布任务单
        $sql = "select 1 from rqf_trade_info where user_id = ? and pay_time > 0 limit 1 ";
        $row = $this->db->query($sql, [$user_id])->row();

        // 邀请白名单
        $chk_row = $this->db->get_where('rqf_invite_white_list', ['user_id'=>$user_id])->row();

        $data['enable_invite'] = $row || $chk_row;
        // 邀请注册地址 DOMAIN_URL 网站域名
        $code = MD5(MD5_SALT . $user_id);
        if (!$data['enable_invite']) {
            $code = substr_replace($code, '******', 1, strlen($code) - 2);
        }
        $data['url'] = DOMAIN_URL . "/user/register/$user_id/$code";
        $this->load->helper('curl_helper');

        //邀请短链接获取
        $redis_key = 'SHORT_URL' . $user_id;
        $url_short = $this->cache->redis->get($redis_key);
        if (!$url_short)
        {
            $url_short = getShortUrl($data['url']);
            if ($url_short) {
                $this->cache->redis->save($redis_key, $url_short, 600);
            }
        }
        if ($url_short)
        {
            $data['url']=$url_short;
        }
        $this->load->view('invite/invite_url', $data);
    }

    /**
     * 邀请记录
     */
    public function invite_record()
    {
        $data = $this->data;
        $page = $this->input->get('page');
        $user_id = $this->session->userdata('user_id');
        $data['result'] = $this->invite->invite_record($user_id, $page);

        $this->load->view('invite/invite_record', $data);
    }

    /**
     * 邀请记录详情
     */
    public function invite_record_detail($record_date)
    {
        if (empty($record_date)) {
            redirect('/invite/invite_record');
        }

        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['result'] = $this->invite->invite_record_detail($user_id, $record_date);

        $this->load->view('invite/invite_record_detail', $data);
    }

    /**
     * 奖励记录
     */
    public function invite_reward()
    {
        $data = $this->data;
        $page = $this->input->get('page');
        $user_id = $this->session->userdata('user_id');
        $data['result'] = $this->invite->invite_reward($user_id, $page);

        $this->load->view('invite/invite_reward', $data);
    }

    /**
     * 奖励记录详情
     */
    public function invite_reward_detail($record_date)
    {
        if (empty($record_date)) {
            redirect('/invite/invite_record');
        }
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');

        $data['result'] = $this->invite->invite_reward_detail($user_id, $record_date);

        $this->load->view('invite/invite_reward_detail', $data);

    }

    /**
     * 失效奖励
     */
    public function failure_reward()
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        # 分页信息
        $page = $this->input->get('page');
        $page = intval($page) ? intval($page) : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        # 操作类型
        $type = $this->uri->segment(3);
        if (!in_array($type, array('all', 'novip', 'expired', 'days_30'))) {
            $type = 'all';
        }

        switch ($type) {
            case 'all':
                $all = $this->invite->get_all_data($user_id);
                break;
            case 'novip':
                $novip = $this->invite->get_novip_data($user_id);
                break;
            case 'expired':
                $expired = $this->invite->get_expired_data($user_id);
                break;
            case 'days_30':
                $days_30 = $this->invite->get_days_30_data($user_id);
                break;
        }

        $data['all'] = isset($all) ? $all : false;
        $data['novip'] = isset($novip) ? $novip : false;
        $data['expired'] = isset($expired) ? $expired : false;
        $data['days_30'] = isset($days_30) ? $days_30 : false;
        $data['type'] = $type;

        #分页
        $this->load->library('pagination');
        $config['base_url'] = "/invite/failure_reward/all";
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = count($all);
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';

        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('invite/failure_reward', $data);
    }

    /** 删除会员邀请关系 */
    public function remove_invite_relation()
    {
        $sub_user_id = intval($this->input->post('uid'));
        $user_id = $this->session->userdata('user_id');
        if ($sub_user_id <= 0) {
            exit(json_encode(['status' => 0, 'message' => '提交的参数不正确']));
        }

        $result = $this->invite->remove_invite_relation($user_id, $sub_user_id);
        if ($result) {
            exit(json_encode(['status' => 1]));
        } else {
            exit(json_encode(['status' => 0, 'message' => '数据更新失败了']));
        }
    }

}
