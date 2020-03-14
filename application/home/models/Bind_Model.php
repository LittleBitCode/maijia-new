<?php

/**
 * 名称:绑定模型
 * 担当:
 */
class Bind_Model extends CI_Model {

    /**
     * __construct
     */
    public function __construct() {

        parent::__construct();
        $this->_table = 'rqf_bind_shop';
    }

    /**
     * 获取一个默认绑定店铺
     */
    public function default_bind_shop($user_id) {

        $row = $this->db->get_where('rqf_bind_shop', ['user_id'=>$user_id, 'plat_id'=>1,'is_show'=>1])->row();

        if ($row) {
            return $row;
        }

        $row = $this->db->get_where('rqf_bind_shop', ['user_id'=>$user_id, 'plat_id'=>2,'is_show'=>1])->row();

        if ($row) {
            return $row;
        }

    	return $this->db->get_where('rqf_bind_shop', ['user_id'=>$user_id,'is_show'=>1])->row();
    }

    /**
     * 所有店铺列表
     */
    public function all_shop_list($user_id) {

        $shop_list = $this->db->get_where('rqf_bind_shop', ['user_id'=>$user_id,'is_show'=>1])->result();

        $shop_list_ext = [];

        foreach ($shop_list as $v) {
            $shop_list_ext[$v->id] = $v;
        }

        return $shop_list_ext;
    }

    /**
     * 绑定店铺列表
     */
    public function bind_shop_list($user_id, $plat_id) {

        return $this->db->get_where('rqf_bind_shop', ['user_id'=>$user_id, 'plat_id'=>$plat_id,'is_show'=>1])->result();
    }

    /**
     * 绑定店铺数量列表
     */
    public function bind_shop_cnt_list($user_id) {

        $res = $this->db->get_where('rqf_bind_shop', ['user_id'=>$user_id,'is_show'=>1])->result();

        $this->load->model('Conf_Model', 'conf');

        $plat_list = $this->conf->plat_list();

        foreach ($res as $v) {

            $plat_list[$v->plat_id]['cnt'] += 1;
        }

        return $plat_list;
    }

    /**
     * 获取店铺名
     */
    public function get_shop_name($bind_id) {

        $row = $this->db->select('shop_name')->get_where('sk_bind_shop', ['id'=>$bind_id])->row();

        return $row->shop_name;
    }

    /**
     * 获取店铺旺旺
     */
    public function get_shop_ww ($bind_id) {

        $row = $this->db->select('shop_ww')->get_where('sk_bind_shop', ['id'=>$bind_id])->row();

        return $row->shop_ww;
    }

    /**
     * 获取加密店铺名
     */
    public function get_encrypt_shop_name ($bind_id) {

        $row = $this->db->select('shop_name')->get_where('sk_bind_shop', ['id'=>$bind_id])->row();

        $len = mb_strlen($row->shop_name, 'UTF-8');

        $encrypt_shop_name = '';

        for ($i=0; $i<$len; $i++) {
            if ($i % 2 == 0) {
                $encrypt_shop_name .= mb_substr($row->shop_name, $i, 1, 'UTF-8');
            } else {
                $encrypt_shop_name .= '*';
            }
        }

        return $encrypt_shop_name;
    }
    
    /**
     * 获取加密旺旺名
     */
    public function get_encrypt_shop_ww ($bind_id) {

        $row = $this->db->select('shop_ww')->get_where('sk_bind_shop', ['id'=>$bind_id])->row();

        $len = mb_strlen($row->shop_ww, 'UTF-8');

        $encrypt_shop_ww = '';

        for ($i=0; $i<$len; $i++) {
            if ($i % 2 == 0) {
                $encrypt_shop_ww .= mb_substr($row->shop_ww, $i, 1, 'UTF-8');
            } else {
                $encrypt_shop_ww .= '*';
            }
        }

        return $encrypt_shop_ww;
    }

    /**
     * 获取城市数据
     */
    public function get_city_list ($province_name) {

        $province_row = $this->db->get_where('sk_bank_province', ['province_name'=>$province_name])->row();

        if (empty($province_row)) {
            return [];
        }

        $city_list = $this->cache->redis->get('SK_CITY_LIST_'.$province_row->id);

        if (empty($city_list)) {

            $city_list = $this->db->get_where('sk_bank_city', ['pid'=>$province_row->id])->result();

            $this->cache->redis->save('SK_CITY_LIST_'.$province_row->id, $city_list, 360000);
        }

        return $city_list;
    }

    //获取地址信息
    public function get_addr_list($pid){
        $sql = "select * from rqf_region where parent_id = ?";
        $res=$this->db->query($sql,array($pid));
        if ($res){
            $return = $res->result_array();
        }else{
            $return = array();
        }
        return $return;
    }
    /**
     * 获取支行数据
     */
    public function get_sub_branch_list ($short_name, $province_name, $city_name) {

        $key = [
            'bank_short_name'=>$short_name,
            'province'=>$province_name,
            'city'=>$city_name
        ];

        return $this->db->select('bank_code,sub_branch')->get_where('sk_bank_info', $key)->result();
    }

    /**
     * 随机验证码
     */
    public function get_rand_code () {

        $nums = range(0,9);

        $chars = range('A','Z');

        shuffle($nums);

        shuffle($chars);

        return "{$nums[0]}{$nums[1]}{$nums[2]}{$nums[3]}-{$chars[0]}{$chars[1]}{$chars[2]}{$chars[3]}";
    }

}
