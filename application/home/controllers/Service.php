<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// 用来处理服务内容
class Service extends MY_Controller
{
    // 验证码
    public function captcha()
    {
        $this->cache_use = false;
        // cookie imgcde
        $this->load->library('captcha');
        $this->captcha->create_image();
        $this->captcha->stroke();
        exit;
    }

    // 异步实时验证
    public function captcha_ajax()
    {
        $this->load->library('captcha');
        $code = trim($this->input->post('code'));
        if ($code) {
            $vdata['status'] = $this->captcha->verify($code, false) ? 1 : 0;
        } else {
            $vdata['status'] = 0;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($vdata));
    }
}