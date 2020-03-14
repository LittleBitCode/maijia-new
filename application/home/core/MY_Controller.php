<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:父类控制器
 * 担当:
 */
class MY_Controller extends CI_Controller
{
    /** __construct */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_Model', 'user');
        $data['is_login'] = $this->is_login();
        $data['is_vip'] = $this->is_vip();
        $this->data = $data;
    }

    /**
     * 验证是否登录
     */
    public function is_login()
    {
        $this->user->update_user_info(true);
        $user_id = $this->session->userdata('user_id');
        // $mobile = $this->session->userdata('mobile');
        return $user_id;
    }

    /**
     * 是否为VIP
     */
    public function is_vip()
    {
        if (!$this->is_login()) {
            return false;
        }
        $group_id = $this->session->userdata('group_id');
        $expire_time = $this->session->userdata('expire_time');
        return ($group_id == 1 && $expire_time > time());
    }
}

/**
 * 名称:扩展控制器
 * 担当:
 */
class Ext_Controller extends MY_Controller
{

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->is_login()) {
            return redirect('/user/login');
            // return redirect('/user/home');
        }

        $data = $this->data;
        $data['left_index'] = '';
        $user_id = $this->session->userdata('user_id');
        $data['user_info'] = $this->user->get_user_info($user_id);
        // 域名不限制账号白名单
        $mobile_whitelist = ['19941142918'];
        if (!in_array($data['user_info']->mobile, $mobile_whitelist)) {
            if ($data['user_info']->reg_imei) {
                $server_name = trim($this->input->server('SERVER_NAME'));
                if ($data['user_info']->reg_imei != $server_name) {
                    redirect("http://{$data['user_info']->reg_imei}");
                    return;
                }
            } else {
                $server_name = trim($this->input->server('SERVER_NAME'));
                if ('vip.09jl.com' != $server_name) {
                    redirect("http://vip.09jl.com");
                    return;
                }
            }
        }

        $this->load->model('Review_Model', 'review');
        $data['review_plat_cnts'] = $this->review->review_plat_cnts();
        // 常见问题、网站公告
        $lock_key = 'BUS_NOTICE_QUESTION_LIST_'. strtoupper($_SERVER['HTTP_HOST']);
        $data_list = unserialize($this->cache->redis->get($lock_key));
        if ($data_list) {
            $data['bus_notice_list'] = $data_list['bus_notice_list'];
            $data['bus_question_list'] = $data_list['bus_question_list'];
        } else {
            $this->load->model('Center_Model', 'center');
            $bus_notice_list = $this->center->notice_list();
            $bus_question_list = $this->center->question_list(0);
            // 数据保存到redis
            $data_list = ['bus_notice_list' => $bus_notice_list, 'bus_question_list' => $bus_question_list];
            $this->cache->redis->save($lock_key, serialize($data_list), 60*60);

            $data['bus_notice_list'] = $data_list['bus_notice_list'];
            $data['bus_question_list'] = $data_list['bus_question_list'];
        }

        $this->data = $data;
    }
}
