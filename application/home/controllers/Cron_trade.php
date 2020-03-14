<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:计划活动控制器
 * 担当:
 */
class Cron_trade extends CI_Controller {

    /**
     * __construct
     */
    public function __construct () {

        parent::__construct();

        $this->write_db = $this->load->database('write', true);
    }

    /**
     * 转必做活动
     * 条件: 8小时没有买手接单
     * 5分钟执行一次
     */
    public function to_must()
    {
        $limit_time = strtotime('-8 hour');
        $sql = "select * from rqf_trade_info where trade_status = 2 and is_must = 0 and last_time < {$limit_time} limit 10";
        $res = $this->write_db->query($sql)->result();
        foreach ($res as $v) {
            $this->set_must($v);
        }
    }
    
    /**
     * 设置必做活动
     */
    private function set_must($v)
    {
        $sql = "update rqf_trade_info set is_must = 1 where id = {$v->id}";
        $this->write_db->query($sql);
        // 操作日志
        $action_info = [
            'trade_id' => $v->id,
            'trade_sn' => $v->trade_sn,
            'trade_status' => $v->trade_status,
            'trade_note' => '转为必做活动',
            'add_time' => time(),
            'created_user' => 'system',
            'comments' => ''
        ];
        $this->write_db->insert('rqf_trade_action', $action_info);

        echo $v->trade_sn . 'to must trade<br/>';
    }

    /**
     * 设置活动发布间隔计划
     * 5分钟执行一次
     */
    public function trade_interval_plan()
    {
        $t = time();
        $sql = "select * from rqf_trade_info where trade_status = 2 and `interval` <> '' and interval_status = 0 and start_time < {$t} limit 10";
        $res = $this->write_db->query($sql)->result();
        foreach ($res as $v) {
            $this->create_interval_plan($v);
        }
    }

    /**
     * 创建分时计划
     */
    private function create_interval_plan($v)
    {
        $this->write_db->query("update rqf_trade_info set interval_status = 1 where id = {$v->id}");
        $this->load->model('Conf_Model', 'conf');
        $interval_list = $this->conf->interval_list();
        $tmp = explode('|', $v->interval);
        $interval_key = $tmp[0];
        $interval_num = $tmp[1];
        $interval_str = $interval_list[$interval_key]['strtotime'];
        $nums = $this->split($v->total_num, $interval_num);

        $t = time();
        $interval_plan = [];
        for ($i = 0; $i < count($nums); $i++) {
            $tmp = [
                'trade_id' => $v->id,
                'trade_sn' => $v->trade_sn,
                'num' => $nums[$i],
                'update_time' => $t
            ];
            $interval_plan[] = $tmp;
            $t = strtotime($interval_str, $t);
        }

        if ($interval_plan) {
            $this->write_db->insert_batch('rqf_interval_plan', $interval_plan);
            echo "{$v->trade_sn}间隔分时计划设置完成 at " . date('Y-m-d H:i:s') . PHP_EOL;
        } else {
            echo "{$v->trade_sn}间隔分时计划设置异常 at " . date('Y-m-d H:i:s') . PHP_EOL;
        }
    }

    /**
     * 分割数组
     */
    private function split($sum, $step)
    {
        $arr = [];
        while (true) {
            if ($sum < $step) {
                if ($sum > 0) {
                    $arr[] = $sum;
                }
                break;
            } else {
                $arr[] = $step;
            }
            $sum -= $step;
        }

        return $arr;
    }

    /**
     * 设置活动分时发布计划
     * 5分钟执行一次
     */
    public function trade_custom_interval_plan()
    {
        $sql = 'select i.id, i.trade_sn, i.created_time, i.start_time, s.param
                  from rqf_trade_info i left join rqf_trade_service s on i.id = s.trade_id and s.service_name = ? 
                 where i.trade_status = 2 and i.custom_interval = 0 and i.start_time < ? and s.id is not null limit 10 ';
        $res = $this->write_db->query($sql, ['custom_time_price', time()])->result();
        foreach ($res as $item) {
            $this->create_custom_interval_plan($item);
        }
    }

    /**
     * 创建分时计划
     */
    private function create_custom_interval_plan($item)
    {
        $this->write_db->query("update rqf_trade_info set custom_interval = 1 where id = ? ", [intval($item->id)]);
        $param_list = json_decode($item->param, true);
        $reference_time = $item->start_time ? $item->start_time : time();
        $interval_plan = [];
        $single_nums = 0 ;
        foreach ($param_list as $key => $nums) {
            $point_date = date('Y-m-d', $reference_time). ' '. $key. ':00:00';
            $point_time = strtotime($point_date);
            $next_hour_time = $point_time + 3600;
            if ($next_hour_time <= time()) {
                // 超过指定时间点，累计到下一个时间点
                $single_nums += intval($nums);
                continue;
            } else {
                if ($point_time <= time()) {
                    $point_time = time();
                }
                $single_nums += intval($nums);
                // 第一单在五分钟内放出去
                $interval_plan[] = [
                    'trade_id' => $item->id,
                    'trade_sn' => $item->trade_sn,
                    'num' => 1,
                    'update_time' => rand($point_time, $point_time + 300)
                ];
                if ($single_nums > 1) {
                    $point_time = $point_time + 300;
                    $interval = intval(($next_hour_time - 1800 - $point_time) / $single_nums);      // 在前半小时内全部放完
                    for ($i = 1; $i < $single_nums; $i++) {
                        $interval_plan[] = [
                            'trade_id' => $item->id,
                            'trade_sn' => $item->trade_sn,
                            'num' => 1,
                            'update_time' => rand($point_time, $point_time + $interval - 1)
                        ];

                        $point_time += $interval;
                    }
                }
                $single_nums = 0;   // 下一个时间点单数
            }
        }

        if ($interval_plan) {
            $this->write_db->insert_batch('rqf_interval_plan', $interval_plan);
            echo "{$item->trade_sn}分时计划设置完成 at " . date('Y-m-d H:i:s') . PHP_EOL;
        } else {
            echo "{$item->trade_sn}分时计划设置异常 at " . date('Y-m-d H:i:s') . PHP_EOL;
        }
    }

    /**
     * 更新分时计划
     * 2分钟执行一次
     */
    public function update_interval_plan()
    {
        $t = time();
        $sql = "select * from rqf_interval_plan where update_time < {$t}";
        $res = $this->write_db->query($sql)->result();
        foreach ($res as $v) {
            $this->write_db->query("update rqf_trade_info set pc_num = pc_num + {$v->num} where id = {$v->trade_id} and is_pc > 0 and pc_num < total_num");
            $this->write_db->query("update rqf_trade_info set phone_num = phone_num + {$v->num} where id = {$v->trade_id} and is_phone > 0 and phone_num < total_num");
            $this->write_db->delete("rqf_interval_plan", ['id' => $v->id]);
            echo $v->trade_sn . '<br/>';
        }
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
        $sql = "SELECT o.*, s.param FROM `rqf_trade_order_%s` o 
                    LEFT JOIN `rqf_print_err` e ON o.order_sn = e.order_sn 
                    LEFT JOIN `rqf_trade_service` s on o.trade_id = s.trade_id and s.service_name = 'set_shipping' 
                 WHERE order_status = 1 AND o.print_time = 0 AND o.express_sn = '' AND o.no_print = 0 AND o.delay_send_time < {$t} AND o.check_error = 0 AND o.check_time > 0 AND o.trade_type not in (111, 114) 
                   and e.id IS NULL and s.id is not null LIMIT 0, 35";
        $res = $this->write_db->query(sprintf($sql, $suffix))->result();
        if (!empty($res)) {
            foreach ($res as $v) {
                if (empty(trim($v->param))) {
                    echo '任务单：' . $v->trade_sn . ' 快递设置异常' . PHP_EOL;
                    continue ;
                }
                // 打印
                $bus_user_suffix = suffix($v->bus_user_id);
                if ($bus_user_suffix > 1 && 'sto' == $v->param) {
                    $this->do_print($suffix, $v);
                } else {
                    $this->do_print_package($suffix, $v);
                }
            }
        }
    }

    /** 51baoguo 打印 */
    private function do_print($suffix, $v)
    {
        echo $v->order_sn . ' print finish at: ' . date('Y-m-d H:i:s') . PHP_EOL;
        $t = time();
        $limit_eval_time = strtotime("+2 day");
        // 绑定店铺信息
        $bind_shop = $this->write_db->get_where('rqf_bind_shop', ['id' => $v->shop_id])->row();
        $seller_address = [
            "provincial" => $bind_shop->province,
            "city" => $bind_shop->city,
            "district" => $bind_shop->region,
            "address" => $bind_shop->address
        ];

        // 扩展地址验证
        $address_ext = $this->write_db->get_where('rqf_address_ext', ['order_sn' => $v->order_sn])->row();
        // 收件人信息
        if ($address_ext) {
            $receiver_address = [
                "provincial" => $address_ext->province,
                "city" => $address_ext->city,
                "district" => $address_ext->region,
                "address" => $address_ext->address
            ];

            $reciever_name = $address_ext->receive_name;
            $reciever_phone = $address_ext->receive_mobile;
        } else {
            $bind_account = $this->write_db->get_where('rqf_bind_account', ['id' => $v->account_id])->row();
            $receiver_address = [
                "provincial" => $bind_account->province,
                "city" => $bind_account->city,
                "district" => $bind_account->region,
                "address" => $bind_account->address
            ];

            $reciever_name = $bind_account->receive_name;
            $reciever_phone = $bind_account->receive_mobile;
        }

        // 获取设置增值服务（快递设置）
        $express_type = $v->param;
        $dingdan_item = [
            "dingdanhao" => "",
            "seller_name" => $bind_shop->send_name,
            "seller_phone" => $bind_shop->send_mobile,
            "seller_address" => $seller_address,
            "reciever_name" => $reciever_name,
            "reciever_phone" => $reciever_phone,
            "reciever_address" => $receiver_address,
            "post_id" => $bind_shop->net_no,
            "weight" => "2.0",
            "lanjian_num" => $bind_shop->net_no,
            "goods_name" => "",
            "mark" => ""
        ];

        // 包裹重量
        $trade_item = $this->write_db->get_where('rqf_trade_item', ['trade_id' => $v->trade_id])->row();
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
        $params['dingdan'] = json_encode([$dingdan_item]);
        if ($express_type == 'yunda') {
            if ($v->plat_id == '4' || $v->plat_id == '14') {
                $params['express'] = '韵达非淘宝快递';
            } else {
                $params['express'] = '韵达淘宝快递';
            }
        } elseif ($express_type == 'sto') {
            if ($v->plat_id == '4') {
                $params['express'] = '申通京东';
            } elseif ($v->plat_id == '14') {
                $params['express'] = '申通拼多多';
            } else {
                $params['express'] = '申通快递';
            }
        } else {
            echo '任务单：' . $v->trade_sn . ' 快递设置异常' . PHP_EOL;
            return 0;
        }

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
            $sql = "update rqf_trade_order_{$suffix} set express_type = ?, express_sn = ?, print_time = ?, limit_eval_time = ?, order_status = 2 where id = ? and order_status = 1 and express_sn = ''";
            $res = $this->write_db->query($sql, [$express_type, $express_sn, $t, $limit_eval_time, intval($v->id)]);
            if ($this->write_db->affected_rows()) {
                // 更新操作日志
                $action_info_ins = [
                    'order_id' => $v->id,
                    'order_sn' => $v->order_sn,
                    'order_status' => 2,
                    'order_note' => '系统打印快递单',
                    'add_time' => $t,
                    'created_user' => '51baoguo',
                    'comments' => $params['express']. '：'. $express_sn,
                ];
                $this->write_db->insert("rqf_order_action_{$suffix}", $action_info_ins);
            }
        } else {
            $print_err_data = [
                'add_time' => time(),
                'order_sn' => $v->order_sn,
                'err_no' => $rtndata['code'],
                'comment' => '51baoguo => '. $rtndata['msg'],
            ];
            $this->write_db->insert("rqf_print_err", $print_err_data);
            echo '订单号：'. $v->order_sn. ' 异常消息：'. $rtndata['msg']. PHP_EOL;
        }
    }
    /** 快递机 **/
    private function do_print_package($suffix, $v)
    {
        $limit_eval_time = strtotime("+2 day");
        // 绑定店铺信息
        $bind_shop = $this->write_db->get_where('rqf_bind_shop', ['id' => $v->shop_id])->row();
        $seller_address = [
            "provincial" => $bind_shop->province,
            "city" => $bind_shop->city,
            "district" => $bind_shop->region,
            "address" => $bind_shop->address
        ];

        // 扩展地址验证
        $address_ext = $this->write_db->get_where('rqf_address_ext', ['order_sn' => $v->order_sn])->row();
        // 收件人信息
        if ($address_ext) {
            $receiver_address = [
                "provincial" => $address_ext->province,
                "city" => $address_ext->city,
                "district" => $address_ext->region,
                "address" => $address_ext->address
            ];

            $reciever_name = $address_ext->receive_name;
            $reciever_phone = $address_ext->receive_mobile;
        } else {
            $bind_account = $this->write_db->get_where('rqf_bind_account', ['id' => $v->account_id])->row();
            $receiver_address = [
                "provincial" => $bind_account->province,
                "city" => $bind_account->city,
                "district" => $bind_account->region,
                "address" => $bind_account->address
            ];

            $reciever_name = $bind_account->receive_name;
            $reciever_phone = $bind_account->receive_mobile;
        }

        // 获取设置增值服务（快递设置）
        $express_type = $v->param;

        // 传入参数集
        $jsondata = [
            "orderSn" => "",
            "sendName" => $bind_shop->send_name,
            "sendTel" => $bind_shop->send_mobile,
            "receiveName" => $reciever_name,
            "receiveTel" => $reciever_phone,
            "goodsName" => "",
            "comment" => "",
            'weight' => '2.0',
            "sendNetNo" => $bind_shop->net_no,
            "sendProvince" => $seller_address['provincial'],
            "sendCity" => $seller_address['city'],
            "sendDistrict" => $seller_address['district'],
            "sendStreet" => $seller_address['address'],
            "receiveProvince" => $receiver_address['provincial'],
            "receiveCity" => $receiver_address['city'],
            "receiveDistrict" => $receiver_address['district'],
            "receiveStreet" => $receiver_address['address'],
        ];

        // 包裹重量
        $trade_item = $this->write_db->get_where('rqf_trade_item', ['trade_id' => $v->trade_id])->row();
        if (floatval($trade_item->weight) >= 0.1 && floatval($trade_item->weight) <= 40) {
            $jsondata['weight'] = $trade_item->weight;
        }
        $jsondata['goodsName'] = $trade_item->goods_name;
        if (strlen($v->pay_sn) > 18) {
            $jsondata['orderSn'] = substr($v->pay_sn, 0, 18);
        } else {
            $jsondata['orderSn'] = $v->pay_sn;
        }

        // params
        $params = array();
        $params['partnerid'] = 'chenzhen';
        $params['password'] = 'c88d0fab6a28bc22f7c63f27ef08f67e';
        $params['jsondata'] = json_encode($jsondata);
        $params['validation'] = md5($params['partnerid']. $params['password']. $params['jsondata']);
        if ($express_type == 'sto') {
            if ($v->plat_id == '4') {
                $params['type'] = 'STJD';           // 申通京东专用
            } elseif ($v->plat_id == '14') {
                $params['type'] = 'STPDD';          // 申通拼多多专用
            } else {
                $params['type'] = 'STKD';           // 申通淘宝专用快递
            }
        } elseif ($express_type == 'yto') {
            $params['type'] = 'YTKD';               // 圆通快递
        } elseif ($express_type == 'zto') {
            $params['type'] = 'ZTKD';               // 中通快递
        } else {
            echo '任务单：' . $v->trade_sn . ' 快递设置异常' . PHP_EOL;
            return 0;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://139.198.125.145/api/order/add");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        $res = curl_exec($curl);
        curl_close($curl);

        $rtndata = json_decode($res, true);
        if ($rtndata['res'] == '0') {
            $express_sn = $rtndata['express_no'];
            $sql = "update rqf_trade_order_{$suffix} set express_type = ?, express_sn = ?, print_time = ?, limit_eval_time = ?, order_status = 2 where id = ? and order_status = 1 and express_sn = ''";
            $res = $this->write_db->query($sql, [$express_type, $express_sn, time(), $limit_eval_time, intval($v->id)]);
            if ($this->write_db->affected_rows()) {
                // 更新操作日志
                $action_info_ins = [
                    'order_id' => $v->id,
                    'order_sn' => $v->order_sn,
                    'order_status' => 2,
                    'order_note' => '系统打印快递单',
                    'add_time' => time(),
                    'created_user' => '快递机',
                    'comments' => $params['type']. '：'. $express_sn,
                ];
                $this->write_db->insert("rqf_order_action_{$suffix}", $action_info_ins);
            }

            echo $v->order_sn . ' do_print_package done at: ' . date('Y-m-d H:i:s') . PHP_EOL;
        } else {
            $error_arr = [
                '1' => '快递类型错误', '2' => 'partnerid 不存在', '3' => '签名错误', '4' => 'json 数据解析错误', '5' => '未知系统错误',
                '6' => '用户金币不足', '7' => '订单号重复', '8' => '下单时间异常', '9' => '订单号必填', '10' => '发件地址无法解析',
                '11' => '发货地址必填', '12' => '收件地址无法解析', '13' => '收货地址必填', '14' => '字段超长', '15' => '快递重量格式错误',
            ];
            $comment = isset($error_arr[$rtndata['res']]) ? $error_arr[$rtndata['res']] : '异常消息超出预定的';
            $print_err_data = [
                'add_time' => time(),
                'order_sn' => $v->order_sn,
                'err_no' => $rtndata['res'],
                'comment' => '快递机 => '. $comment,
            ];
            $this->write_db->insert("rqf_print_err", $print_err_data);

            echo $v->order_sn . ' do_print_package error message: ' . $comment . PHP_EOL;
        }
    }

    /**
     * 不打印快递单，更新限制收货时间
     */
    public function order_no_print() {

        $sql = "select * from rqf_trade_order_%s where order_status = 1 and no_print > 0 and check_error = 0 and check_time > 0 and trade_type not in (111, 114, 115) limit 20";

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
    private function do_no_print($suffix, $v)
    {
        echo $v->order_sn . ' print finish<br/>' . PHP_EOL;
        $t = time();
        $limit_eval_time = strtotime("+{$v->no_print} day");
        // $limit_eval_time = strtotime("+2 day");
        $sql = "update rqf_trade_order_{$suffix} set limit_eval_time = {$limit_eval_time}, order_status = 2, print_time = {$t} where id = {$v->id}";
        $this->write_db->query($sql);
        $action_info_ins = [
            'order_id' => $v->id,
            'order_sn' => $v->order_sn,
            'order_status' => 2,
            'order_note' => '系统更新待发货',
            'add_time' => $t,
            'created_user' => 'system',
            'comments' => ''
        ];

        $this->write_db->insert("rqf_order_action_{$suffix}", $action_info_ins);
    }

    /**
     * 系统自动发货
     */
    public function auto_send_out() {

        $limit_time = strtotime('-48 hour');

        $sql = "select * from rqf_trade_order_%s where order_status = 2 and print_time < {$limit_time} limit 20";

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
    private function send_out($suffix, $v)
    {
        $t = time();
        $sql = "update rqf_trade_order_{$suffix} set send_time = {$t}, order_status = 3 where id = {$v->id} and order_status = 2";
        $this->write_db->query($sql);
        if ($this->write_db->affected_rows()) {
            $action_info = [
                'order_id' => $v->id,
                'order_sn' => $v->order_sn,
                'order_status' => 3,
                'order_note' => '系统自动发货',
                'add_time' => $t,
                'created_user' => 'system',
                'comments' => ''
            ];

            $this->write_db->insert("rqf_order_action_{$suffix}", $action_info);
        }
    }

    /**
     * 更新任务状态为已完成
     */
    public function set_trade_finish()
    {
        $this->write_db = $this->load->database('write', true);
        $chk_sql = "SELECT id, trade_sn FROM `rqf_trade_info` WHERE trade_status = 2 AND trade_type <> 10 AND total_num = finish_num AND pc_num = 0 AND phone_num = 0 LIMIT 50";
        $chk_res = $this->write_db->query($chk_sql)->result();
        $upt_sql = "UPDATE `rqf_trade_info` SET trade_status = 3 WHERE id = ? AND trade_status = 2 AND total_num = finish_num AND pc_num = 0 AND phone_num = 0";
        foreach ($chk_res as $key => $val) {
            $this->write_db->query($upt_sql, [intval($val->id)]);
            if ($this->write_db->affected_rows() > 0) {
                // 操作日志
                $action_info = [
                    'trade_id' => intval($val->id),
                    'trade_sn' => $val->trade_sn,
                    'trade_status' => 3,
                    'trade_note' => '任务已完成',
                    'add_time' => time(),
                    'created_user' => 'system',
                    'comments' => ''
                ];
                $this->write_db->insert('rqf_trade_action', $action_info);

                echo $val->trade_sn . ' finished !'. PHP_EOL;
            }
        }

        // 浏览订单检查完成
        $chk_sql = 'select i.id, i.trade_sn from rqf_trade_info i where i.trade_status = 2 and i.trade_type = 10 
                       and not exists (select 1 from rqf_trade_traffic t where i.id = t.trade_id and t.trade_status = 2 and t.surplus_num > 0) 
                       and not exists (select 1 from rqf_traffic_record_union o where i.id = o.trade_id and o.traffic_status < 7) limit 50';
        $upt_sql = "UPDATE `rqf_trade_info` SET trade_status = 3 WHERE id = ? AND trade_status = 2 ";
        $query_res = $this->write_db->query($chk_sql)->result();
        foreach ($query_res as $item) {
            $this->write_db->query($upt_sql, [intval($item->id)]);
            if ($this->write_db->affected_rows() > 0) {
                // 操作日志
                $action_info = [
                    'trade_id' => intval($item->id),
                    'trade_sn' => $item->trade_sn,
                    'trade_status' => 3,
                    'trade_note' => '任务已完成',
                    'add_time' => time(),
                    'created_user' => 'system',
                    'comments' => ''
                ];
                $this->write_db->insert('rqf_trade_action', $action_info);

                echo $item->trade_sn . ' finished !'. PHP_EOL;
            }
        }

        $this->write_db->close();
    }

    /** 增值服务 定时结束任务 **/
    public function trade_auto_set_over()
    {
        echo 'Trade_auto_set_over start at => '. date('Y-m-d H:i:s'). PHP_EOL;
        $sql = 'select i.* from rqf_trade_service s left join rqf_trade_info i on s.trade_id = i.id where s.service_name = ? and param <= ? and i.trade_status < 9 and i.trade_type <> 10 ';
        $query_list = $this->db->query($sql, ['set_over_time', date('Y-m-d H:i:s')])->result();
        // 更新取消操作
        $this->load->model('Trade_Model', 'trade');
        $this->write_db = $this->load->database('write', true);
        foreach ($query_list as $trade_info) {
            // 未支付
            if ($trade_info->trade_status == '0') {
                // 未付款 直接取消
                $sql = 'update rqf_trade_info set trade_status = 9 where user_id = ? and id = ? and trade_status = 0';
                $result = $this->write_db->query($sql, [intval($trade_info->user_id), intval($trade_info->id)]);
                if ($result) {
                    $trade_action = [
                        'trade_id' => intval($trade_info->id),
                        'trade_sn' => $trade_info->trade_sn,
                        'trade_status' => 9,
                        'trade_note' => '活动单定时取消',
                        'add_time' => time(),
                        'created_user' => 'system',
                        'comments' => ''
                    ];
                    $this->write_db->insert('rqf_trade_action', $trade_action);
                }
            } elseif ($trade_info->trade_status == '1') {
                // 已付款 任务全部返还
                $sql = 'update rqf_trade_info set trade_status = 9 where user_id = ? and id = ? and trade_status = 1';
                $result = $this->write_db->query($sql, [intval($trade_info->user_id), intval($trade_info->id)]);
                if ($result) {
                    $trade_action = [
                        'trade_id' => intval($trade_info->id),
                        'trade_sn' => $trade_info->trade_sn,
                        'trade_status' => 9,
                        'trade_note' => '活动单定时取消',
                        'add_time' => time(),
                        'created_user' => 'system',
                        'comments' => ''
                    ];
                    $this->write_db->insert('rqf_trade_action', $trade_action);
                }

                $user_info = $this->write_db->get_where('rqf_users', ['id' => intval($trade_info->user_id)])->row();
                $sql = "update rqf_users set user_deposit = user_deposit + ?, frozen_deposit = frozen_deposit - ?, user_point = user_point + ?, frozen_point = frozen_point - ? where id = ? and frozen_deposit >= ? and frozen_point >= ?";
                $result = $this->write_db->query($sql, [floatval($trade_info->trade_deposit), floatval($trade_info->trade_deposit), floatval($trade_info->trade_point), floatval($trade_info->trade_point), intval($trade_info->user_id), floatval($trade_info->trade_deposit), floatval($trade_info->trade_point)]);
                if ($result) {
                    // 浏览任务单没有押金
                    $user_deposit_ins = [
                        'user_id' => intval($trade_info->user_id),
                        'shop_id' => intval($trade_info->shop_id),
                        'action_time' => time(),
                        'action_type' => 404,
                        'score_nums' => '+' . $trade_info->trade_deposit,
                        'last_score' => bcadd($user_info->user_deposit, $trade_info->trade_deposit, 2),
                        'frozen_score_nums' => '-' . $trade_info->trade_deposit,
                        'last_frozen_score' => bcsub($user_info->frozen_deposit, $trade_info->trade_deposit, 2),
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => 'system',
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit_ins);

                    // 佣金记录
                    $user_point_ins = [
                        'user_id' => intval($trade_info->user_id),
                        'shop_id' => intval($trade_info->shop_id),
                        'action_time' => time(),
                        'action_type' => 404,
                        'score_nums' => '+' . $trade_info->trade_point,
                        'last_score' => bcadd($user_info->user_point, $trade_info->trade_point, 2),
                        'frozen_score_nums' => '-' . $trade_info->trade_point,
                        'last_frozen_score' => bcsub($user_info->frozen_point, $trade_info->trade_point, 2),
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => $this->session->userdata('nickname'),
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_point', $user_point_ins);
                }
            } elseif (in_array($trade_info->trade_status, ['2', '6'])) {
                // 进行中 暂停中
                $sql = "update rqf_trade_info set trade_status = 9 where user_id = ? and id = ? and trade_status in (2, 6)";
                $result = $this->write_db->query($sql, [intval($trade_info->user_id), intval($trade_info->id)]);
                if ($result) {
                    $trade_action = [
                        'trade_id' => intval($trade_info->id),
                        'trade_sn' => $trade_info->trade_sn,
                        'trade_status' => 9,
                        'trade_note' => '活动单定时取消',
                        'add_time' => time(),
                        'created_user' => 'system',
                        'comments' => ''
                    ];
                    $this->write_db->insert('rqf_trade_action', $trade_action);
                }
                // 可返还的任务订单数
                $cancel_refund = $this->trade->cancel_refund($trade_info);
                $user_info = $this->write_db->get_where('rqf_users', ['id' => intval($trade_info->user_id)])->row();
                $sql = "update rqf_users set user_deposit = user_deposit + ?, frozen_deposit = frozen_deposit - ?, user_point = user_point + ?, frozen_point = frozen_point - ? where id = ? and frozen_deposit >= ? and frozen_point >= ?";
                $result = $this->write_db->query($sql, [floatval($cancel_refund->deposit), floatval($cancel_refund->deposit), floatval($cancel_refund->point), floatval($cancel_refund->point), intval($trade_info->user_id), floatval($cancel_refund->deposit), floatval($cancel_refund->point)]);
                if ($result) {
                    // 返还押金
                    $user_deposit_ins = [
                        'user_id' => intval($trade_info->user_id),
                        'shop_id' => intval($trade_info->shop_id),
                        'action_time' => time(),
                        'action_type' => 404,
                        'score_nums' => '+' . $cancel_refund->deposit,
                        'last_score' => bcadd($user_info->user_deposit, $cancel_refund->deposit, 2),
                        'frozen_score_nums' => '-' . $cancel_refund->deposit,
                        'last_frozen_score' => bcsub($user_info->frozen_deposit, $cancel_refund->deposit, 2),
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => 'system',
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_deposit', $user_deposit_ins);
                    // 返还佣金
                    $user_point_ins = [
                        'user_id' => intval($trade_info->user_id),
                        'shop_id' => intval($trade_info->shop_id),
                        'action_time' => time(),
                        'action_type' => 404,
                        'score_nums' => '+' . $cancel_refund->point,
                        'last_score' => bcadd($user_info->user_point, $cancel_refund->point, 2),
                        'frozen_score_nums' => '-' . $cancel_refund->point,
                        'last_frozen_score' => bcsub($user_info->frozen_point, $cancel_refund->point, 2),
                        'trade_sn' => $trade_info->trade_sn,
                        'order_sn' => '',
                        'pay_sn' => '',
                        'created_user' => 'system',
                        'trade_pic' => ''
                    ];
                    $this->write_db->insert('rqf_bus_user_point', $user_point_ins);
                }
            }
            echo '任务单：'. $trade_info->trade_sn. '已取消'. PHP_EOL;
        }
        $this->write_db->close();

        echo 'Trade_auto_set_over done at => '. date('Y-m-d H:i:s'). PHP_EOL;
    }

}
