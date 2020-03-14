<?php
/**
 * 发送短信
 *
 */

class Sms_Model extends CI_Model
{
    public function verify_sms($mobile)
    {
        $this->write_db = $this->load->database('write', true);
        //添加当天发送次数限制
        $start = strtotime(date('Y-m-d', time()));
        $end = $start + 24 * 60 * 60;

        $sql = "SELECT COUNT(1) num FROM rqf_phone_check_code WHERE phone_num = ? AND request_time > ? AND request_time < ?";
        $res = $this->db->query($sql, array($mobile, $start, $end))->row_array();

        $count = $res['num'];

        if ($count >= 5) {
            $result['state'] = 1;
            $result['msg'] = '今日请求验证码超过次数限制，请联系客服';
            return json_encode($result);
        }

        //获取注册随机验证码
        $check_code = mt_rand(100000, 999999);

        //获取此手机上一条验证码
        $sql = "SELECT * FROM rqf_phone_check_code WHERE phone_num = ? AND request_time > ? AND code_status = ? ORDER BY send_time DESC LIMIT 1";
        $old_code = $this->db->query($sql, array($mobile, strtotime('-10 minute'), 0))->row();
        if ($old_code) {
            $check_code = $old_code->code;
        }

        //请求时间
        $request_time = strtotime('now');
        // 此处短信接口
        $this->load->helper('sms_helper');
        $statusCode = sendTemplatesubmail($mobile, array('code' => $check_code, 'minute' => 10), 'S9lE3');  // 更改短信模板
        //发送短信成功
        if ($statusCode['status'] == 'success') {
            $notes = "短信发送成功，验证码十分钟内有效";
            $issuccess = 1;
            $send_time = strtotime('now');
            $send_data = array(
                "phone_num" => $mobile,
                "code" => $check_code,
                "request_time" => $request_time,
                "send_time" => $send_time,
                "issuccess" => $issuccess,
                "notes" => $notes,
            );
            $this->write_db->insert("rqf_phone_check_code", $send_data);
            $result['state'] = 2;
            $result['msg'] = '短信发送成功，验证码十分钟内有效';

        } else {
            $notes = "发送失败,处理状态码：" . $statusCode['msg'];
            $issuccess = 0;
            $send_time = 0;
            $send_data = array(
                "phone_num" => $mobile,
                "code" => $check_code,
                "request_time" => $request_time,
                "send_time" => $send_time,
                "issuccess" => $issuccess,
                "notes" => $notes,
            );
            $this->write_db->insert("rqf_phone_check_code", $send_data);
            $result['state'] = 1;
            $result['msg'] = '发送失败';

        }

        $this->write_db->close();

        return json_encode($result);
    }

}
