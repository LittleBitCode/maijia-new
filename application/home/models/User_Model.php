<?php

/**
 * 名称:用户模型
 * 担当:
 */
class User_Model extends CI_Model {

	/**
     * __construct
	 */
	public function __construct () {
		
		parent::__construct();
	}

	/**
	 * 根据id获取用户信息
	 */
	public function get_user_info($user_id, $db=null) {

		$sql = "SELECT *,AES_DECRYPT(mobile_decode,salt) mobile FROM rqf_users WHERE id = ? and user_type = 1 ";

        if ($db) {
        	$user_info = $db->query($sql,array($user_id))->row();

            // $user_info = $db->get_where('rqf_users', ['id' => $user_id])->row();
        } else {
        	$user_info = $this->db->query($sql,array($user_id))->row();
            // $user_info = $this->db->get_where('rqf_users', ['id' => $user_id])->row();
        }

		return $user_info;
	}

	/**
	 * 获取盐值
	 */
	public function get_salt() {

		$salt_arr = array_merge(range(0,9),range('a','z'),range('A','Z'));

		shuffle($salt_arr);

		return implode('', array_slice($salt_arr, 0, 6));
	}


	/**
     * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
     * @param string $user_name 姓名
     * @return string 格式化后的姓名
     */
    public function substr_cut ($user_name) {
        $strlen = mb_strlen($user_name, 'utf-8');
        $firstStr = mb_substr($user_name, 0, 3, 'utf-8');
        $lastStr = mb_substr($user_name, -4, 4, 'utf-8');

        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 7) . $lastStr;
    }

	/**
	 * 添加用户信息
	 */
	public function user_add($user_info) {

		$this->write_db = $this->load->database('write', true);

		$this->write_db->insert('rqf_users', $user_info);

		$id = $this->write_db->insert_id();

		// 加密手机号和QQ号
		$mobile_decode = $user_info['mobile_decode'];
		$qq_decode = $user_info['qq_decode'];
		$aes_salt = $user_info['salt'];

		$sql = "UPDATE rqf_users SET mobile_decode = AES_ENCRYPT(?,?),qq_decode = AES_ENCRYPT(?,?) WHERE id = ? and user_type = 1";
		$this->write_db->query($sql,array($mobile_decode,$aes_salt,$qq_decode,$aes_salt,$id));

		$this->write_db->close();

		return $id;
	}

	/**
	 * 保存用户信息
	 */
	public function user_save($info, $key) {

		$this->write_db = $this->load->database('write', true);

		$this->write_db->update('rqf_users', $info, $key);

        $this->write_db->close();
	}

	/**
	 * 用户信息写入cookie
	 */
    public function write_cookie($user_id)
    {

        // 获取用户信息
        $userinfo = $this->get_user_info($user_id);

        // 加载验证库
        $this->load->library('authtoken');

        $auth_code = $this->config->item('auth_code');

        $cookie_domain = $this->config->item('cookie_domain');

        $auth_info = array(
            $userinfo->id,
            $userinfo->mobile_ciphertext,
            $userinfo->nickname,
            $userinfo->group_id
        );

        $auth_token = $this->authtoken->encode($auth_info, COOKIE_EXPIRE_TIME, $auth_code);
        setcookie(COOKIE_NAME, $auth_token, time() + COOKIE_EXPIRE_TIME, '/', $cookie_domain);
    }

	/**
	 * 更新用户缓存信息
	 */
	public function update_user_info($flag = false) {

		// 加载验证函数
		$this->load->library('authtoken');

		$cook_uid = null;

		if (isset($_COOKIE[COOKIE_NAME]) && $_COOKIE[COOKIE_NAME]) {
			$auth_code = $this->config->item('auth_code');
			list($cook_uid, $mobile, $nickname) = $this->authtoken->decode($_COOKIE[COOKIE_NAME], $auth_code);
		}

		$sess_uid = null;

		if (isset($this->session->userdata['user_id'])) {
			$sess_uid = $this->session->userdata['user_id'];
		}
                
		if ((!$sess_uid && $cook_uid) || $sess_uid != $cook_uid || $flag) {
			// 获取用户信息
			$userinfo = $this->get_user_info($cook_uid);

			if (empty($userinfo)) {
				// $this->session->sess_destroy();
				setcookie(COOKIE_NAME, '', time()-1, '/', $this->config->item('cookie_domain'));
			} else {
				$sess_info = [];
				// 用户id
				$sess_info['user_id']		 = $userinfo->id;
				// 昵称
				$sess_info['nickname']		 = $userinfo->nickname;
				// 头像
				$sess_info['face']		     = $userinfo->face;
				// 用户组id
				$sess_info['group_id']		 = $userinfo->group_id;
				// 到期时间
				$sess_info['expire_time']	 = $userinfo->expire_time;
				// 用户名
				$sess_info['mobile']	 = $userinfo->mobile;

				$this->session->set_userdata($sess_info);
			}
		}
	}


	/*
    *返回ip
    */
    public function get_ip(){

        if($_SERVER['REMOTE_ADDR']){
            return $_SERVER['REMOTE_ADDR'];
        }elseif($_SERVER['HTTP_X_FORWARD_FOR']){
            return $_SERVER['HTTP_X_FORWARD_FOR'];
        }else{
            return '';
        }

    }

}
