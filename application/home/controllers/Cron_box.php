<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:计划活动控制器
 * 担当:
 */
class Cron_box extends CI_Controller {

    /**
     * __construct
     */
    public function __construct () {

        parent::__construct();

        $this->write_db = $this->load->database('write', true);
    }

    /**
     * 流量推送
     * 每5分钟执行一次
     */
    public function traffic_record () {

        echo 'traffic record on ' . date('Y-m-d H:i:s') . ' start<br/>';

        $sql = "SELECT * FROM `rqf_traffic_record_%s` WHERE update_status = 0 AND update_time = 0 LIMIT 20";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();
        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();
        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();
        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();
        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();
        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();
        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();
        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();
        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();
        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_0 as $v) $this->traffic_curl($v);
        foreach ($res_1 as $v) $this->traffic_curl($v);
        foreach ($res_2 as $v) $this->traffic_curl($v);
        foreach ($res_3 as $v) $this->traffic_curl($v);
        foreach ($res_4 as $v) $this->traffic_curl($v);
        foreach ($res_5 as $v) $this->traffic_curl($v);
        foreach ($res_6 as $v) $this->traffic_curl($v);
        foreach ($res_7 as $v) $this->traffic_curl($v);
        foreach ($res_8 as $v) $this->traffic_curl($v);
        foreach ($res_9 as $v) $this->traffic_curl($v);

        echo 'traffic record on ' . date('Y-m-d H:i:s') . ' end<br/>';
    }

    /**
     * 流量推送调用接口
     */
    private function traffic_curl ($v) {

        $suffix = suffix($v->user_id);

        $t = time();

        $params = [
            'trade_sn'=>$v->trade_sn,
            'order_sn'=>$v->order_sn,
            'bind_name'=>$v->shop_name,
            'bind_plat'=>$v->plat_name,
            'goods_name'=>$v->goods_name,
            'goods_url'=>$v->goods_url,
            'trade_nums'=>$v->trade_nums,
            'goods_img'=>$v->goods_img,
            'goods_price'=>$v->goods_price,
            'item_key'=>$v->item_key,
            'keywords_info'=>$v->keywords_info,
            'keywords_sort'=>$v->keywords_sort,
            'keywords_price'=>$v->keywords_price,
            'keywords_area'=>$v->keywords_area,
            'keywords_discount'=>$v->keywords_discount,
            'keywords_category'=>$v->keywords_category,
            'sum_traffics'=>$v->sum_traffics,
            'collect_goods'=>$v->collect_goods,
            'collect_shop'=>$v->collect_shop,
            'add_to_cart'=>$v->add_to_cart,
            'get_coupon'=>$v->get_coupon,
            'item_evaluate'=>$v->item_evaluate
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://app-a.renqibaohe.com/publish/receive_flow_from_58");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $data = curl_exec($curl);

        curl_close($curl);

        $data_arr = json_decode($data, true);

        // var_dump($data);

        // var_dump($data_arr);die;

        if ($data_arr['state'] == '1') {
            $sql = "update rqf_traffic_record_{$suffix}
                    set update_status = 1, update_time = {$t}
                    where id = {$v->id}
                    and update_status = 0";

            $this->write_db->query($sql);

            echo $v->order_sn . ' traffic push success!<br/>';
        } else {
            $sql = "update rqf_traffic_record_{$suffix}
                    set update_status = 2, update_time = {$t}
                    where id = {$v->id}
                    and update_status = 0";

            $this->write_db->query($sql);

            echo $v->order_sn . ' traffic push error!<br/>';
        }
    }

    /**
     * 夺宝币转账
     * 每分钟执行一次
     */
    public function snatch_record() {

        echo 'snatch record on ' . date('Y-m-d H:i:s') . ' start<br/>';

        $sql = "select * from rqf_snatch_record_%s where transfer_status = 0 and mobile <> '' limit 10";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();
        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();
        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();
        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();
        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();
        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();
        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();
        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();
        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();
        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_0 as $v) $this->snatch_gold_curl($v);
        foreach ($res_1 as $v) $this->snatch_gold_curl($v);
        foreach ($res_2 as $v) $this->snatch_gold_curl($v);
        foreach ($res_3 as $v) $this->snatch_gold_curl($v);
        foreach ($res_4 as $v) $this->snatch_gold_curl($v);
        foreach ($res_5 as $v) $this->snatch_gold_curl($v);
        foreach ($res_6 as $v) $this->snatch_gold_curl($v);
        foreach ($res_7 as $v) $this->snatch_gold_curl($v);
        foreach ($res_8 as $v) $this->snatch_gold_curl($v);
        foreach ($res_9 as $v) $this->snatch_gold_curl($v);

        echo 'snatch record on ' . date('Y-m-d H:i:s') . ' end<br/>';
    }

    /**
     * 夺宝币curl调用接口
     */
    private function snatch_gold_curl($v) {

        $suffix = suffix($v->user_id);

        $params = array();
        $params['phone_num'] = $v->mobile;
        $params['signType'] = 'MD5';
        $params['trade_money'] = $v->snatch_gold;
        $params['notes'] = $v->comment;
        $params['trade_account'] = $v->order_sn;

        $sign = $this->getSign($params);
        $params['sign'] = $sign;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "http://app.renqibaohe.com/outer/rqf_task_money");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $data = curl_exec($curl);

        curl_close($curl);

        $data_arr = json_decode($data, true);

        if ($data_arr['code'] == '0000') {
            $sql = "update rqf_snatch_record_%s set transfer_time = ?, transfer_status = 1 where id = ? and transfer_status = 0";
            $this->write_db->query(sprintf($sql, $suffix), array(time(), $v->id));
        } elseif ($data_arr['code'] == '1000') {
            $sql = "update rqf_snatch_record_%s set transfer_time = ?, transfer_status = 2 where id = ? and transfer_status = 0";
            $this->write_db->query(sprintf($sql, $suffix), array(time(), $v->id));
        }
    }

    /**
     * ..
     */
    private function _argSort($array) {
        ksort($array);       
        return $array;
    }

    /**
     * ..
     */
    private function _sign($prestr, $signType) {
        $mysign = "";

        if ($signType == 'MD5') {
            $mysign = md5($prestr);
        } elseif ($signType == 'SHA-256') {
            $mysign = hash('sha256',$prestr);
        } else {
            die("Does not support the xx signature type");
        }

        return $mysign;
    }
    
    /**
     * ..
     */ 
    private function getSign($_post) {
         
        $wai_prestr = '';
        $wai_prestr = $this->_argSort($_post);
         
        $tmp_pos = end($wai_prestr).'as1212A54DASD212AS';   //匹配最后元素的值
         
        $tmpkeys = array_keys($wai_prestr);
    
        $emp_endkey = end($tmpkeys);            //去最后一个元素的键
    
        array_pop($wai_prestr);
    
        $wai_prestr[$emp_endkey] = $tmp_pos;

        $arg = '';
         
        foreach ($wai_prestr as $key => $val) { //循环生成url链接串
            $arg .= $key . "=" . $val ."&";
        }
    
        $prestr = substr($arg, 0, count($arg) - 2);//减掉多余的&
    
        return $this->_sign($prestr,$_post['signType']);
    }
}
