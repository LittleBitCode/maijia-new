<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:计划活动控制器
 * 担当:
 */
class Cron_trade1 extends CI_Controller {

    /**
     * __construct
     */
    public function __construct () {

        parent::__construct();

        $this->write_db = $this->load->database('write', true);
    }

    /**
     * 删除并发报错信息
     */
    public function order_delete()
    {
        $sql = "DELETE FROM `rqf_print_err` WHERE comment = ''";
        $this->write_db->query($sql);
    }

    /**
     * 打印快递单
     */
    public function order_print($suffix = 0)
    {
        $t = time();
        $sql = "SELECT o.* FROM `rqf_trade_order_%s` o 
                LEFT JOIN `rqf_print_err` e ON o.order_sn = e.order_sn 
                WHERE order_status = 1 AND o.print_time = 0 AND o.express_sn = '' AND o.no_print = 0 AND o.delay_send_time < {$t} AND o.check_error = 0 AND o.check_time > 0 and e.id IS NULL 
                LIMIT 0, 35";
        $res = $this->write_db->query(sprintf($sql, $suffix))->result();
        if (!empty($res)) {
            foreach ($res as $v) {
                $this->do_print($suffix, $v);
            }
        }
    }

    public function my_print() {

        // error_reporting(E_ALL);

        // ini_set('display_errors', 1);

        $order_sn = $this->uri->segment(3);

        echo 'order_sn:'. $order_sn .'<br>';

        if (empty($order_sn)) {
            echo 'params empty';
            die;
        }

        $suffix = order_suffix($order_sn);

        $row = $this->db->get_where("rqf_trade_order_{$suffix}", ['order_sn'=>$order_sn])->row();

        if (empty($row)) {
            echo 'order info empty';
            die;
        }

        $this->do_print($suffix, $row);
    }

    /**
     * 打印
     */
    private function do_print($suffix, $v) {

        echo $v->order_sn . ' print finish<br/>' . PHP_EOL;

        $t = time();

        $limit_eval_time = strtotime("+2 day");

        // 绑定店铺信息
        $bind_shop = $this->db->get_where('rqf_bind_shop', ['id'=>$v->shop_id])->row();

        $seller_address = [
            "provincial"=>$bind_shop->province,
            "city"=>$bind_shop->city,
            "district"=>$bind_shop->region,
            "address"=>$bind_shop->address
        ];

        // 扩展地址验证
        $address_ext = $this->write_db->get_where('rqf_address_ext', ['order_sn'=>$v->order_sn])->row();
        // 收件人信息
        if ($address_ext) {
            $receiver_address = [
                "provincial"=>$address_ext->province,
                "city"=>$address_ext->city,
                "district"=>$address_ext->region,
                "address"=>$address_ext->address
            ];

            $reciever_name = $address_ext->receive_name;
            $reciever_phone = $address_ext->receive_mobile;
        } else {

            $bind_account = $this->write_db->get_where('rqf_bind_account', ['id'=>$v->account_id])->row();
            $receiver_address = [
                "provincial"=>$bind_account->province,
                "city"=>$bind_account->city,
                "district"=>$bind_account->region,
                "address"=>$bind_account->address
            ];

            $reciever_name = $bind_account->receive_name;
            $reciever_phone = $bind_account->receive_mobile;
        }


        $dingdan_item = [
            "dingdanhao"=>"",
            "seller_name"=>$bind_shop->send_name,
            "seller_phone"=>$bind_shop->send_mobile,
            "seller_address"=>$seller_address,
            "reciever_name"=>$reciever_name,
            "reciever_phone"=>$reciever_phone,
            "reciever_address"=>$receiver_address,
            "post_id"=>$bind_shop->net_no,
            "weight"=>"2.0",
            "lanjian_num"=>"",
            "goods_name"=>"",
            "mark"=>""
        ];

        // 包裹重量
        $trade_item = $this->write_db->get_where('rqf_trade_item', ['trade_id'=>$v->trade_id])->row();

        if ($trade_item->weight >= 0.1 && $trade_item->weight <= 40) {
            $dingdan_item['weight'] = $trade_item->weight;
        } else {
            $dingdan_item['weight'] = '2.0';
        }

        if (strlen($v->pay_sn) > 18) {
            $dingdan_item['dingdanhao'] = substr($v->pay_sn, 0, 18);
        } else {
            $dingdan_item['dingdanhao'] = $v->pay_sn;
        }

        // params
        $params = array();
        $params['name'] = 'jiekou_chongpaiming';
        $params['secret'] = 'chonpami315#!%';
        $params['express'] = '韵达淘宝快递';
        $params['dingdan'] = json_encode([$dingdan_item]);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "http://www.51baoguo.com:3001/api/v1/buyorder");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $res = curl_exec($curl);

        curl_close($curl);

        $rtndata = json_decode($res, true);

        if ($rtndata['code'] == '1001') {

            $express_sn = $rtndata['result']['order'][0]['yundanhao'];

            var_dump($dingdan_item);

            echo '快递单号:'. $express_sn;die;

            // $sql = "update rqf_trade_order_{$suffix} set express_sn = {$express_sn}, print_time = {$t}, limit_eval_time = {$limit_eval_time}, order_status = 2 where id = {$v->id} and order_status = 1 and express_sn = ''";

            // $this->write_db->query($sql);

            // if ($this->write_db->affected_rows()) {
            //     $action_info_ins = [
            //         'order_id'=>$v->id,
            //         'order_sn'=>$v->order_sn,
            //         'order_status'=>2,
            //         'order_note'=>'系统打印快递单',
            //         'add_time'=>$t,
            //         'created_user'=>'system',
            //         'comments'=>''
            //     ];

            //     $this->write_db->insert("rqf_order_action_{$suffix}", $action_info_ins);
            // }

        } else {

            var_dump($rtndata);

            var_dump($dingdan_item);

            $print_err_data = [
                'add_time' => time(),
                'order_sn' => $v->order_sn,
                'err_no' => $rtndata['code'],
                'comment' => $rtndata['msg'],
            ];
            $this->write_db->insert("rqf_print_err", $print_err_data);
        }
    }

    /**
     * 不打印快递单，更新限制收货时间
     */
    public function order_no_print() {

        $sql = "select * from rqf_trade_order_%s 
                where order_status = 1
                and no_print > 0
                and check_error = 0
                and check_time > 0
                limit 20";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();

        foreach ($res_0 as $v) $this->do_no_print('0', $v);

        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();

        foreach ($res_1 as $v) $this->do_no_print('1', $v);

        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();

        foreach ($res_2 as $v) $this->do_no_print('2', $v);

        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();

        foreach ($res_3 as $v) $this->do_no_print('3', $v);

        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();

        foreach ($res_4 as $v) $this->do_no_print('4', $v);

        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();

        foreach ($res_5 as $v) $this->do_no_print('5', $v);

        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();

        foreach ($res_6 as $v) $this->do_no_print('6', $v);

        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();

        foreach ($res_7 as $v) $this->do_no_print('7', $v);

        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();

        foreach ($res_8 as $v) $this->do_no_print('8', $v);

        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_9 as $v) $this->do_no_print('9', $v);
    }

    /**
     * 不打印
     */
    private function do_no_print($suffix, $v) {

        echo $v->order_sn . ' print finish<br/>' . PHP_EOL;

        $t = time();

        // $limit_eval_time = strtotime("+{$v->no_print} day");
        $limit_eval_time = strtotime("+2 day");

        $sql = "update rqf_trade_order_{$suffix} set limit_eval_time = {$limit_eval_time}, order_status = 2, print_time = {$t} where id = {$v->id}";

        echo $sql;

        $this->write_db->query($sql);

        $action_info_ins = [
            'order_id'=>$v->id,
            'order_sn'=>$v->order_sn,
            'order_status'=>2,
            'order_note'=>'系统更新待发货',
            'add_time'=>$t,
            'created_user'=>'system',
            'comments'=>''
        ];

        $this->write_db->insert("rqf_order_action_{$suffix}", $action_info_ins);
    }

    /**
     * 系统自动发货
     */
    public function auto_send_out() {

        $limit_time = strtotime('-48 hour');

        $sql = "select * from rqf_trade_order_%s 
                where order_status = 2
                and print_time < {$limit_time}
                limit 20";

        $res_0 = $this->write_db->query(sprintf($sql, '0'))->result();

        foreach ($res_0 as $v) $this->send_out('0', $v);

        $res_1 = $this->write_db->query(sprintf($sql, '1'))->result();

        foreach ($res_1 as $v) $this->send_out('1', $v);

        $res_2 = $this->write_db->query(sprintf($sql, '2'))->result();

        foreach ($res_2 as $v) $this->send_out('2', $v);

        $res_3 = $this->write_db->query(sprintf($sql, '3'))->result();

        foreach ($res_3 as $v) $this->send_out('3', $v);

        $res_4 = $this->write_db->query(sprintf($sql, '4'))->result();

        foreach ($res_4 as $v) $this->send_out('4', $v);

        $res_5 = $this->write_db->query(sprintf($sql, '5'))->result();

        foreach ($res_5 as $v) $this->send_out('5', $v);

        $res_6 = $this->write_db->query(sprintf($sql, '6'))->result();

        foreach ($res_6 as $v) $this->send_out('6', $v);

        $res_7 = $this->write_db->query(sprintf($sql, '7'))->result();

        foreach ($res_7 as $v) $this->send_out('7', $v);

        $res_8 = $this->write_db->query(sprintf($sql, '8'))->result();

        foreach ($res_8 as $v) $this->send_out('8', $v);

        $res_9 = $this->write_db->query(sprintf($sql, '9'))->result();

        foreach ($res_9 as $v) $this->send_out('9', $v);
    }

    /**
     * 发货
     */
    private function send_out($suffix, $v) {

        $t = time();

        $sql = "update rqf_trade_order_{$suffix} set send_time = {$t}, order_status = 3 where id = {$v->id} and order_status = 2";

        $this->write_db->query($sql);

        if ($this->write_db->affected_rows()) {

            $action_info = [
                'order_id'=>$v->id,
                'order_sn'=>$v->order_sn,
                'order_status'=>3,
                'order_note'=>'系统自动发货',
                'add_time'=>$t,
                'created_user'=>'system',
                'comments'=>''
            ];

            $this->write_db->insert("rqf_order_action_{$suffix}", $action_info);
        }
    }

    /**
     * 更新任务状态为已完成
     */
    public function set_trade_finish() {

        $chk_sql = "SELECT * FROM `rqf_trade_info` WHERE trade_status = 2 AND total_num = finish_num AND pc_num = 0 AND phone_num = 0 LIMIT 50";

        $chk_res = $this->write_db->query($chk_sql)->result();

        $upt_sql = "UPDATE `rqf_trade_info` SET trade_status = 3 WHERE id = ? AND trade_status = 2 AND total_num = finish_num AND pc_num = 0 AND phone_num = 0";

        foreach ($chk_res as $key => $val) {

            $this->write_db->query($upt_sql, array($val->id));

            if ($this->write_db->affected_rows() > 0) {
                // 操作日志
                $action_info = [
                    'trade_id' => $val->id,
                    'trade_sn' => $val->trade_sn,
                    'trade_status' => 3,
                    'trade_note' => '任务已完成',
                    'add_time' => time(),
                    'created_user' => 'system',
                    'comments' => ''
                ];

                $this->write_db->insert('rqf_trade_action', $action_info);

                echo $val->trade_sn . ' finished !<br/>';
            }
        }
    }
}
