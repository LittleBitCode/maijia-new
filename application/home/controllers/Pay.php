<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:支付回调控制器
 * 担当:
 */
class Pay extends Ext_Controller
{

    /**
     * __construct
     */
    public function __construct()
    {

        parent::__construct();

        $this->load->driver('cache');

        $this->load->model('User_Model', 'user');

        $this->write_db = $this->load->database('write', true);
    }

    /**
     * 快钱支付成功
     */
    public function success() {

        $data = $this->data;

        $this->load->model('User_Model','user');
        $user_id =$this->session->userdata('user_id');
        $user_info = $this->user->get_user_info($user_id);
        $data['user_info'] = $user_info;
        $this->load->view('pay/success',$data);
    }

    /**
     * 快钱支付失败
     *
     */
    public function error(){
        $data = $this->data;
        $this->load->model('User_Model','user');
        $user_id =$this->session->userdata('user_id');
        $user_info = $this->user->get_user_info($user_id);
        $data['user_info'] = $user_info;
        $this->load->view('pay/error',$data);
    }
}