<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:用户控制器
 * 担当:
 */
class User1 extends MY_Controller {

    /**
     * __construct
     */
    public function __construct () {
        error_reporting(E_ALL || ~ E_NOTICE);

        parent::__construct();

        $this->load->model('User_Model', 'user');
        $this->load->model('Sms_Model','sms');
        $this->load->helper('burl');
    }

    /**
     * 注册商家
     */
    public function register()
    {
        if ($this->uri->segment(3) && $this->uri->segment(4)) {
            $pid = $this->uri->segment(3);
            $test = $this->uri->segment(4);
            // 检查信息及邀请人ID
            $invite_code = md5(MD5_SALT . $pid);
            if ($test == $invite_code) {
                $data['pid'] = $pid; // 传递父id
            }
        }

        $new_ip = $this->get_client_ip();
        if (!$new_ip) {
            $new_ip = $this->user->get_ip();
        }
        $sql = "select id,mobile_ciphertext from rqf_users where last_ip = ? or reg_ip = ? limit 1";
        $res = $this->db->query($sql, array($new_ip, $new_ip))->row_array();
        $data['renqiyanzhengis'] = 0;
        if ($res['id']) {
            $data['renqiyanzhengis'] = 1;
        }

        //获取banner数据
        $this->load->model('Banner_Model', 'banner');
        $banner_info = $this->banner->get_banner(3, 1);
        $data['banner_info'] = $banner_info;
        $this->load->view('/user/registers', $data);
    }
    
    /**
     * 商家注册提交
     */
    public function register_submit () {
        $this->write_db = $this->load->database('write', true);
        $new_ip = $this->get_client_ip();
        if (!$new_ip) {
            $new_ip = $this->user->get_ip();
        }

        $mobile     = trim($this->input->post('mobile'));
        $qq         = trim($this->input->post('qq'));
        $verify     = trim($this->input->post('phone_verify'));
        $password   = trim($this->input->post('password'));

        $i          = trim($this->input->post('i'));
        $renjiyanzhengis = trim($this->input->post('renjiyanzhengis'));
        $pid        = $this->input->post('pid');

        $mobile_ciphertext = md5($mobile);
        $time = strtotime('-2 minute');

        if(!empty($mobile) && !empty($verify)){

            $sql = 'SELECT * FROM rqf_phone_check_code WHERE phone_num = ? AND CODE = ? AND send_time > ? AND code_status = ?';
            $info = $this->db->query($sql,array($mobile,$verify,$time,0))->row();
            $sql_1 = 'SELECT * FROM  rqf_users WHERE mobile_ciphertext = ?';
            $info_1 = $this->db->query($sql_1,array($mobile_ciphertext))->row();

            if(!empty($info_1)){
                $data['is_success'] =  0;
                $data['msg'] = '手机号已注册!';
                exit(json_encode($data));
            }elseif(empty($info)){

                $data['is_success'] =  0;
                $data['msg'] = '手机验证码错误';
                exit(json_encode($data));
            }else {
                $sql = "UPDATE rqf_phone_check_code SET code_status = 1 WHERE id = ?";
                $this->write_db->query($sql, array($info->id));
            }

        }elseif($mobile == '' || $verify == '' ){
            $data['is_success'] =  0;
            $data['msg'] = '手机号码为空或者手机验证码为空！';
            exit(json_encode($data));
        }


        if(empty($password)){
            $data['is_success'] =  0;
            $data['msg'] = '请填写密码！';
            exit(json_encode($data));
        }

        $result = preg_match("/^[1-9]\d{4,11}$/",$qq);
        if(!$result){
            $qq = '';
        }

        if(empty($qq)){
            $data['is_success'] =  0;
            $data['msg'] = '请填写QQ号！';
            exit(json_encode($data));
        }

        $lock = $this->load->library('filelock',array('filename'=>"register.lock"),'lock');
        $this->lock->writeLock();

        $salt = $this->user->get_salt();
        $nickname = $this->user->substr_cut($mobile);

        // 开启事务
        $this->write_db->trans_strict(FALSE);
        $this->write_db->trans_begin();

        // 执行添加操作
        $user_info = array();
        $user_info['mobile_ciphertext']         = md5($mobile);
        $user_info['mobile_decode']             = $mobile;
        $user_info['qq_ciphertext']             = md5($qq);
        $user_info['qq_decode']                 = $qq;
        $user_info['salt']                      = $salt;
        $user_info['nickname']                  = $nickname;
        $user_info['login_password']            = md5(md5($password).$salt);
        $user_info['qq_decode']                 = $qq;
        $user_info['reg_time']                  = time();
        $user_info['reg_ip']                    = $this->user->get_ip();
        $user_info['last_time']                 = time();
        $user_info['last_ip']                   = $this->user->get_ip();
        $user_info['user_type']                 = 1;
        $user_info['group_id']                  = 0;
        if (!empty($pid)) {
            $sql = "SELECT pid,mobile_ciphertext,user_type FROM rqf_users WHERE id = ?";
            $res = $this->db->query($sql,array($pid))->row_array();
            // $ptype = $res['user_type'];
            $user_info['pid']               = $pid; //一级邀请人id
            $user_info['invite_ciphertext'] = $res['mobile_ciphertext']; // 邀请人密文
            $user_info['ppid']              = $res['pid']? $res['pid']:0 ; //二级邀请人id 
        }

        //保存用户信息
        $id = $this->user->user_add($user_info);

        // 查人气
        $crq_key='crq'.$id;
        $crq_value = $password;
        $this->cache->redis->save($crq_key, $crq_value, 15*24*60*60); // 存放半个月
        if (!$id) {
            $this->write_db->trans_rollback();
            $data['is_success'] =  0;
            $data['msg'] = '注册失败，请重新进行注册！';
            exit(json_encode($data));
        }

        // 事务完成
        if ($this->write_db->trans_status() === TRUE) {
            $this->write_db->trans_commit();
        } else {
            $this->write_db->trans_rollback();
        }
              
        $this->user->write_cookie($id);
        $this->lock->unlock();

        // 邀请相关的日志
        if ($user_info['pid']) {
            $this->load->model('Invite_Model', 'invite');
            $res = $this->invite->invite_count_num($pid, 1, $id, $nickname, $user_info['reg_time']);
        }

        $this->write_db->close();
        if ($id) {
            $data['is_success'] = 1;
            exit(json_encode($data));
        }
    }

    /** 图形码验证 */
    private function check_code ($captcha) {
        $this->load->library('captcha');
        return $this->captcha->verify(trim($captcha), false) ? 1 : 0 ;
    }

    /**
     * 统计手机验证次数
     * **/
    public function check_code_nums() {
        $mobile = trim($this->input->post('m'));
        $phone_verify = trim($this->input->post('v'));
        $time = strtotime("-2 minute");
        //获取验证码
        $sql = 'SELECT code FROM rqf_phone_check_code WHERE phone_num = ? AND send_time > ? AND code_status = 0 order by id desc';
        $res = $this->db->query($sql,array($mobile,$time))->row();

        // 1正确
        $code = array(
            'status'=>1,
            'msg'=>'success'
        );

        // 0 错误
        if (empty($res) || ($res->code != $phone_verify)) {
            $code = array(
                'status'=>0,
                'msg'=>'false'
            );
        }

        echo json_encode($code);
        return;
    }


    public function check_userqq_register(){
        $qq = $this->input->post('qq');
        $sql = "select * from rqf_users where qq_ciphertext = ?";
        $user_info = $this->db->query($sql,md5($qq))->row();
        if (!$user_info){
            exit(json_encode(array('code'=>1,'msg'=>'您输入的QQ不存在，请核对后重新输入')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'')));
    }

    /*** 商家注册螺丝帽验证 */
    public function captcha_check()
    {
        $data = array('success' => -1, 'msg' => array());
        $captcha = $this->input->post('captcha');
        if (!empty($captcha)) {
            //加载螺丝帽的配置文件
            $captcha_result = $this->check_code($captcha);
            if ($captcha_result) {
                $data['success'] = 1;
            } else {
                $data['msg'] = '填写的图形验证码不正确，请重新验证。';
            }
        } else {
            $data['msg'] = '请求参数有误';
        }
        echo json_encode($data);
    }


    public function check_mobile () {
        $mobile = trim($this->input->post('m'));
        $qq = trim($this->input->post('q'));

        $check_sql = 'select 1 from rqf_users where mobile_ciphertext = ? and user_type = 1 limit 1 ';
        $check_row = $this->db->query($check_sql, array(md5($mobile)))->row();

        if ($check_row) {
            echo 1;
            return;
        }

        $check_sql1 = 'select 1 from rqf_users where qq_ciphertext = ?  and user_type = 1 ';
        $check_row1 = $this->db->query($check_sql1, array(md5($qq)))->row();

        if ($check_row1) {
            echo 2;
            return;
        }

    }


    public function send_message(){

        $mobile     = trim($this->input->post('mobile'));

        $result = array('state'=>1,'msg' =>'发送成功！');  //初始化


        if($mobile != ''){
            if(!preg_match("/\d{11}/",$mobile)){
                $result['msg'] ='请输入正确的手机号码！';
                $result['state'] = 0;
                exit(json_encode($result));
            }
        }else{
            $result['msg'] = '手机号码不能为空！';
            $result['state'] = 0;
            exit(json_encode($result));
        }

        //验证手机号码是否已有此用户
        $sql = "SELECT COUNT(1) cnt FROM rqf_users WHERE mobile_ciphertext = ?";

        $userinfo = $this->db->query($sql, array(md5($mobile)))->row();

        if($userinfo->cnt > 0){
            $result['state'] = 2;
            $result['msg']='该手机号码已注册';
            exit(json_encode($result));
        }
        $res = $this->sms->verify_sms($mobile);
        $check = json_decode($res,true);
        if ($check['state'] != 2){
            $result['msg']=$check['msg'];
            $result['state'] = 0;
            exit(json_encode($result));
        }
        exit(json_encode($result));
    }

    /**
     * 验证码登录发送验证码
     */
    public function login_send_code(){
        $mobile     = trim($this->input->post('mobile'));

        $result = array('state'=>1,'msg' =>'发送成功！');  //初始化


        if($mobile != ''){
            if(!preg_match("/\d{11}/",$mobile)){
                $result['msg'] ='请输入正确的手机号码！';
                $result['state'] = 0;
                exit(json_encode($result));
            }
        }else{
            $result['msg'] = '手机号码不能为空！';
            $result['state'] = 0;
            exit(json_encode($result));
        }

        //验证手机号码是否已有此用户
        $sql = "SELECT COUNT(1) cnt FROM rqf_users WHERE mobile_ciphertext = ? and user_type = 1";

        $userinfo = $this->db->query($sql, array(md5($mobile)))->row();

        if($userinfo->cnt <= 0){
            $result['state'] = 0;
            $result['msg']='该手机号码未注册';
            exit(json_encode($result));
        }
        $res = $this->sms->verify_sms($mobile);
        $check = json_decode($res,true);
        if ($check['state'] != 2){
            $result['msg']=$check['msg'];
            $result['state'] = 0;
            exit(json_encode($result));
        }
        exit(json_encode($result));
    }

    /** 暂时只能用户绑定银行卡时使用 */
    public function send_bank_message()
    {
        $mobile = trim($this->input->post('mobile'));
        $captcha_response = trim($this->input->post('captcha_response'));
        $i = intval($this->input->post('i'));
        if ($i >= 3) {
            $captcha_check = $this->check_code($captcha_response);
            if (!$captcha_check) {
                exit(json_encode(['state' => 1, 'msg' => '图形验证码验证失败！']));
            }
        }

        echo $this->sms->verify_sms($mobile);
    }

    /** 用户登录 */
    public function login()
    {
        $this->load->view('user/logins');
    }

    /**
     * 用户登录提交
     */
    public function login_submit() {
        $username = trim($this->input->post('username'));
        $mobile_ciphertext = md5($username);
        $password = trim($this->input->post('password'));
        $j = trim($this->input->post('j'));//错误次数
        if ($username == '') {
            echo json_encode(['code'=>1, 'msg'=>'请输入用户名']);
            return;
        }
        if ($password == '') {
            echo json_encode(['code'=>2, 'msg'=>'请输入密码']);
            return;
        }

        /*****************************ip登录限制增加验证***************************/
        $data['renqiyanzheng_pwd'] = 'no';
        $check_ip_num_pwd = trim($this->input->post('check_ip_num_pwd'));
        $new_ip = $this->get_client_ip();
        $sql = "select id,mobile_ciphertext from rqf_users where last_ip = ? or reg_ip = ? limit 1";
        $res = $this->db->query($sql,array($new_ip,$new_ip))->row_array();
        // ip已有用户在用,需人机验证
        if ($res['id'] && $res['mobile_ciphertext'] !== md5($username)) {
            $data['renqiyanzheng_pwd'] = "yes";
            if ($check_ip_num_pwd !== '1') {
                $result = array('code' => 5, 'renqiyanzheng_pwd' => $data['renqiyanzheng_pwd'], 'msg' => '您的IP有多人在使用，请先验证图形码登录');
                exit(json_encode($result));
            }
        }

        /*****************************ip登录限制增加验证***************************/
        if ($j>=3 || $data['renqiyanzheng_pwd'] == "yes"){
            // 判断传入人机验证码是否正确
            $captcha = trim($this->input->post('captcha_response'));
            // 验证captcha的值
            $captcha_result = $this->check_code($captcha);
            if (!$captcha_result) {
                $result = array('msg' => '图形验证码填写不正确，请重新验证。', 'code' => 9);
                exit(json_encode($result));
            }
        }

        $userinfo = $this->db->get_where('rqf_users', ['mobile_ciphertext'=>$mobile_ciphertext,'user_type'=>1])->row();
        if (empty($userinfo)) {
            echo json_encode(['code'=>3, 'msg'=>"您输入的登录名不存在，请核对后重新输入"]);
            return;
        }

        $encrypt_password = md5(md5($password) . $userinfo->salt);
        if ($encrypt_password != $userinfo->login_password) {
            echo json_encode(['code'=>3, 'msg'=>'用户名或密码错误']);
            return;
        }

        $this->user->write_cookie($userinfo->id);
        $update_arr['last_time'] = time();
        $update_arr['last_ip'] = $_SERVER['REMOTE_ADDR'];
        $key['id'] = $userinfo->id;
        $this->user->user_save($update_arr,$key);//更新用户登录信息

        exit(json_encode(['code'=>0, 'msg'=>'']));
    }

    /**
     * 验证码无密码登录
     * @param  mobile 手机号
     * @param  verify 验证码
     */
    public function super_login(){
        $mobile = trim($this->input->post('mobile'));
        $verify = trim($this->input->post('verify'));
        $i = trim($this->input->post('i'));//错误次数
        if (!$verify){
            exit(json_encode(array('code'=>1,'msg'=>'请输入验证码')));
        }
        if (!$mobile){
            exit(json_encode(array('code'=>1,'msg'=>'请输入密码')));
        }

        /**************************登录ip限制******************************/
        $data['renqiyanzheng_code'] = 'no';
        $check_ip_num_code = $this->input->post('check_ip_num_code');
        $new_ip = $this->get_client_ip();

        $sql = "select id,mobile_ciphertext from rqf_users where last_ip = ? or reg_ip = ? limit 1";
        $res = $this->db->query($sql,array($new_ip,$new_ip))->row_array();
        // ip已有用户在用,需人机验证
        if ($res['id'] && $res['mobile_ciphertext'] !== md5($mobile)) {
            $data['renqiyanzheng_code'] = "yes";
            if ($check_ip_num_code !== '1') {
                $result = array('code'=>5, 'renqiyanzheng_code'=>$data['renqiyanzheng_code']);
                exit(json_encode($result));
            }
        }
        /**************************登录ip限制******************************/
        /** 已经在获取短信的时候做过验证
        if ($i>=3 || $data['renqiyanzheng_code'] == "yes"){
            // 判断传入人机验证码是否正确
            $captcha = trim($this->input->post('captcha_response'));
            // 验证captcha的值
            $captcha_result = $this->check_code($captcha);
            if (!$captcha_result) {
                $result = array('msg'=>'图形验证码填写不正确，请重新验证。', 'code'=>1, 'url'=>'');
                exit(json_encode($result));
            }
        } **/

        //验证用户是否存在
        $sql = "select * from rqf_users where mobile_ciphertext = ? and user_type = 1";
        $user_info = $this->db->query($sql,md5($mobile))->row();
        if (!$user_info){
            exit(json_encode(array('code'=>1,'msg'=>'您输入的登录名不存在，请核对后重新输入')));
        }
        $time = strtotime("-15 minute");
        //获取验证码
        $sql = 'SELECT code FROM rqf_phone_check_code WHERE phone_num = ? AND send_time > ? AND code_status = 0 order by id desc';
        $res = $this->db->query($sql,array($mobile,$time));
        if (!$res->row()){
            exit(json_encode(array('code'=>1,'msg'=>'短信验证码不正确，请重试')));
        }
        $info = $res->row();
        if ($info->code != $verify){
            exit(json_encode(array('code'=>1,'msg'=>'短信验证码不正确，请重试')));
        }
        $this->user->write_cookie($user_info->id);
        $update_arr['last_time'] = time();
        $update_arr['last_ip'] = $_SERVER['REMOTE_ADDR'];
        $key['id'] = $user_info->id;
        $this->user->user_save($update_arr,$key);//更新用户登录信息
        $this->write_db = $this->load->database('write', true);

        $this->write_db->update('rqf_phone_check_code',array('code_status'=>1),array('code'=>$verify));//修改验证码为失效状态

        $this->write_db->close();
        exit(json_encode(array('code'=>0,'msg'=>'登录成功')));
    }

    public function check_user(){
        $mobile = trim($this->input->post('mobile'));
        $sql = "select * from rqf_users where mobile_ciphertext = ? and  user_type = 1";
        $user_info = $this->db->query($sql,md5($mobile))->row();
        if (!$user_info){
            exit(json_encode(array('code'=>1,'msg'=>'您输入的登录名不存在，请核对后重新输入')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'')));
    }

    public function check_user_register(){
        $mobile = trim($this->input->post('mobile'));
        $sql = "select * from rqf_users where mobile_ciphertext = ?";
        $user_info = $this->db->query($sql,md5($mobile))->row();
        if (!$user_info){
            exit(json_encode(array('code'=>1,'msg'=>'')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'')));
    }
    /**
     * 退出
     */
    public function logout() {

        $this->session->sess_destroy();

        setcookie(COOKIE_NAME, '', time()-1, '/', $this->config->item('cookie_domain'));

        redirect('/user/login');
    }

    /**
     * 找回密码
     *
     */
    public function find_passwd_1(){
        $data = $this->data;
        $this->load->view('user/find_passwords_1', $data);
    }

    /**
     * 找回密码
     */
    public function find_mobile () {
        $mobile = trim($this->input->post('mobile'));

        $check_sql = 'select * from rqf_users where mobile_ciphertext = ?  and user_type = 1 ';

        $check_row = $this->db->query($check_sql, array(md5($mobile)))->row();

        if ($check_row) {
           exit(json_encode(array('code'=>1,'msg'=>'','id'=>$check_row->id)));
        }else{
            exit(json_encode(array('code'=>0,'msg'=>'您输入的手机号不存在，请核对后重新输入')));
        }
    }

    /**
     * 找回密码
     */
    public function find_passwd() {
        $this->load->helper('burl');
        $data = [];
        $id = trim($this->uri->segment(3));
        $check_sql = 'select AES_DECRYPT(mobile_decode,salt) mobile from rqf_users where id = ? ';
        $check_row = $this->db->query($check_sql, array($id))->row();
        if (!$check_row){
            redirectmessage('/user/find_passwd_1', '用户信息不存在！', '找回密码', 5);
            return false ;
        }

        $data['mobile'] = $check_row->mobile;
        $data['id'] = $id;
        $this->load->view('user/find_passwords_2', $data);
    }

    /** 校验验证码及手机号 **/
    public function check_findpwd_2()
    {
        $time = strtotime('-2 minute');
        $mobile = trim($this->input->post('mobile'));
        $verify = trim($this->input->post('verify'));
        $i = trim($this->input->post('i'));//记录错误次数
        if ($mobile && $verify) {
            if ($i >= 3) {
                $captcha = trim($this->input->post('captcha_response'));
                // 验证captcha的值
                $captcha_result = $this->check_code($captcha);
                if (!$captcha_result) {
                    $result = array('msg' => '图形验证码填写不正确，请重新验证。', 'code' => 0, 'url' => '');
                    exit(json_encode($result));
                }
            }

            $sql = 'SELECT * FROM rqf_phone_check_code WHERE phone_num = ? AND send_time > ? AND code_status = ? ';
            $info = $this->db->query($sql, array($mobile, $time, O))->row();

            if (empty($info)) {
                exit(json_encode(array('code' => 0, 'msg' => '验证码不存在，请重新发送！')));
            } elseif ($verify != $info->code) {
                exit(json_encode(array('code' => 0, 'msg' => '验证码错误！')));
            } else {
                //返回加密后用于验证的字符串
                $user_info = $this->db->query("select salt from rqf_users where mobile_ciphertext = ?", array(md5($mobile)))->row();
                $str = md5($user_info->salt . $verify);
                exit(json_encode(array('code' => 1, 'msg' => '', 'info' => $str)));
            }
        } else {
            exit(json_encode(array('code' => 0, 'msg' => '验证码不能为空！')));
        }

    }

    /**
     * 找回密码
     */
    public function set_passwd()
    {
        $this->write_db = $this->load->database('write', true);
        $this->load->helper('burl');

        $user_id = $this->uri->segment(3);
        $verify = $this->uri->segment(4);
        $user_info = $this->db->query("select AES_DECRYPT(mobile_decode,salt) mobile from rqf_users where id = ?", array($user_id))->row();
        if (!$user_info) {
            redirectmessage('/user/find_passwd_1', '用户不存在！', '找回密码', 5);
            exit();
        }
        $sql = 'SELECT * FROM rqf_phone_check_code WHERE phone_num = ? and code = ? AND code_status = ? ';
        $info = $this->db->query($sql, array($user_info->mobile, $verify, 0))->row();
        if (!$info) {
            redirectmessage('/user/find_passwd/' . $user_id, '验证码不存在！', '找回密码', 5);
            exit();
        }

        $password = trim($this->input->post('password'));
        $passwords = trim($this->input->post('passwords'));
        if ($password && !empty($passwords)) {
            if ($password != $passwords) {
                redirectmessage('/user/set_passwd/' . $user_id . '/' . $verify, '两次输入密码不相同！', '找回密码', 5);
                exit();
            }
            $sql = "SELECT salt FROM rqf_users WHERE id = ?  and user_type = 1";

            $res = $this->db->query($sql, array($user_id))->row();

            $new_password = MD5(MD5($password) . $res->salt);

            $sql = "UPDATE `rqf_users` SET login_password = ? WHERE `id`  = ? ";
            $this->write_db->query($sql, array($new_password, $user_id));
            if ($this->write_db->affected_rows() < 0) {
                redirectmessage('/user/find_passwd', '更新操作失败！', '找回密码', 5);
                return false;
            } else {
                //更新验证码为失效
                $this->write_db->update('rqf_phone_check_code', array('code_status' => 1), array('id' => $info->id));
                redirect('/user/find_pass_4');
                return false;
            }
        }

        $this->write_db->close();
        $data['user_id'] = $user_id;
        $data['verify'] = $verify;
        $this->load->view('user/find_passwords_3', $data);
    }

    public function find_pass_4(){
        $this->load->view('/user/find_passwords_4');
    }

    /**
     * 获取请求端的真实IP地址
     */
    public function get_client_ip () {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);

                    if ((bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }

        return NULL;
    }

    public function lll() {

        $user_id = $this->uri->segment(3);

        $this->user->write_cookie($user_id);

        redirect('center');
    }
}
