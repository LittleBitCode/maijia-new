<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:帮助中心控制器
 * 担当:
 */
class Help extends MY_Controller {


    /**
     * 商家帮助中心
     */
    public function business () {

        $data = $this->data;

        $index = ($this->uri->segment(3))?$this->uri->segment(3):1;
		$data['index'] = $index;
        $this->load->view('help/business', $data);
    }

    /**
     * 试客帮助中心
     */
    public function buyer () {

        $data = $this->data;

        $index_m = ($this->uri->segment(3))?$this->uri->segment(3):1;
		$data['index_m'] = $index_m;
        $this->load->view('help/buyer', $data);
    }
	
	/**
     * 关于我们
     */
	public function about(){
		$data = $this->data;
		$this->load->view('help/about',$data);
	}

	/**
     * 广告服务
     */
	public function service(){
		$data = $this->data;
		$this->load->view('help/service',$data);
	}

	/**
     * 联系我们
     */
	public function contact(){
		$data = $this->data;
		$this->load->view('help/contact',$data);
	}

	/**
     * 诚聘英才
     */
	public function join_us(){
		$data = $this->data;
		$this->load->view('help/join_us',$data);
	}

	/**
     * 用户协议
     */
	public function agreement(){
		$data = $this->data;
		$this->load->view('help/agreement',$data);
	}

}
