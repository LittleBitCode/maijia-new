<?php

/**
 * 名称：流量操作模型
 * 担当:
 */
class Traffic_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /** 获取状态 **/
    public function get_traffic_status()
    {
        $status_list = ['0' => '已接手，待开始', '1' => '已提交，待审核', '2' => '已审核、待发放佣金', '3' => '审核不通过', '7' => '已完成', '97' => '超时取消', '98' => '已取消', '99' => '已放弃'];
        return $status_list;
    }

    // 流量类型单价设置
    public function get_traffic_list($cur_time, $type = null) {
        $list = [
            'normal_price' => ['title' => '访客', 'price' => '0.8', 'rewards' => '0.5'],
            'collect_goods' => ['title' => '收藏商品', 'price' => '0.5', 'rewards' => '0.1'],
            'collect_shop' => ['title' => '收藏店铺', 'price' => '0.6', 'rewards' => '0.1'],
            'add_to_cart' => ['title' => '加入购物车', 'price' => '0.6', 'rewards' => '0.1'],
            'get_coupon' => ['title' => '申请优惠券', 'price' => '0.5', 'rewards' => '0.1'],
            'item_evaluate' => ['title' => '进入评价页', 'price' => '0.4', 'rewards' => '0.1'],
            'like_goods' => ['title' => '商品点赞', 'price' => '0.2', 'rewards' => '0.1'],
            'compare_goods' => ['title' => '货比三家', 'price' => '0.8', 'rewards' => '0.1'],
        ];

        if ($type && array_key_exists($type, $list)) {
            return $list[$type];
        } else {
            return $list;
        }
    }

    /** 获取按日期分布记录 **/
    public function get_traffic_distribution($trade_id)
    {
        $sql = 'select * from rqf_trade_traffic where trade_id = ? order by search_id, start_time ';
        return $this->db->query($sql, [intval($trade_id)])->result();
    }

    /** 获取按日期分布汇总 **/
    public function get_traffic_total($trade_id)
    {
        $sql = 'select count(t.id) total_cnts, count(distinct t.search_id) search_cnts, sum(t.total_traffics) total_traffics, sum(t.collect_goods) collect_goods
                     , sum(t.like_goods) like_goods, sum(t.add_to_cart) add_to_cart, sum(t.collect_shop) collect_shop, sum(t.get_coupon) get_coupon, sum(t.item_evaluate) item_evaluate, sum(t.compare_goods) compare_goods, i.created_time
                 from rqf_trade_traffic t left join rqf_trade_info i on t.trade_id = i.id where t.trade_id = ?';
        $query_list = $this->db->query($sql, [intval($trade_id)])->row_array();
        if ($query_list) {
            $days = intval($query_list['total_cnts'] / $query_list['search_cnts']);
            $traffic_list = $this->get_traffic_list(intval($query_list['created_time']));
            foreach ($traffic_list as $key => $item){
                $traffic_list[$key]['days'] = $days;
                if ('normal_price' == $key) {
                    $traffic_list[$key]['cnts'] = intval($query_list['total_traffics']);
                    $traffic_list[$key]['amount'] = $item['price'] * intval($query_list['total_traffics']);
                } else {
                    $traffic_list[$key]['cnts'] = intval($query_list[$key]);
                    $traffic_list[$key]['amount'] = $item['price'] * intval($query_list[$key]);
                }
            }

            return $traffic_list;
        } else {
            return false;
        }
    }

    /** 商家端展示 **/
    public function get_traffic_total_bussiness($trade_id)
    {
        $sql = 'select t.id, t.search_id, t.total_traffics, t.traffic_dist, i.created_time from rqf_trade_traffic t left join rqf_trade_info i on t.trade_id = i.id where t.trade_id = ?';
        $query_list = $this->db->query($sql, [intval($trade_id)])->result();
        if ($query_list) {
            $query_item = [
                'total_cnts' => 0, 'search_cnts' => 0, 'created_time' => 0,
                'total_traffics' => 0, 'collect_goods' => 0, 'add_to_cart' => 0, 'collect_shop' => 0, 'get_coupon' => 0, 'item_evaluate' => 0, 'compare_goods' => 0, 'like_goods' => 0
            ];
            $arr_search_id = [];
            foreach ($query_list as $item) {
                $query_item['total_cnts'] += 1;
                $query_item['created_time'] = $item->created_time;
                $traffic_dist = json_decode($item->traffic_dist, true);
                $query_item['total_traffics'] += intval($item->total_traffics);
                $query_item['collect_goods'] += intval($item->total_traffics * $traffic_dist['collect_goods']);
                $query_item['add_to_cart'] += intval($item->total_traffics * $traffic_dist['add_to_cart']);
                $query_item['collect_shop'] += intval($item->total_traffics * $traffic_dist['collect_shop']);
                $query_item['get_coupon'] += intval($item->total_traffics * $traffic_dist['get_coupon']);
                $query_item['item_evaluate'] += intval($item->total_traffics * $traffic_dist['item_evaluate']);
                $query_item['compare_goods'] += intval($item->total_traffics * $traffic_dist['compare_goods']);
                $query_item['like_goods'] += intval($item->total_traffics * $traffic_dist['like_goods']);
                $arr_search_id[] = $item->search_id;
            }
            $query_item['search_cnts'] = count(array_unique($arr_search_id));
            // 处理数据
            $days = intval($query_item['total_cnts'] / $query_item['search_cnts']);
            $traffic_list = $this->get_traffic_list(intval($query_item['created_time']));
            foreach ($traffic_list as $key => $item){
                $traffic_list[$key]['days'] = $days;
                if ('normal_price' == $key) {
                    $traffic_list[$key]['cnts'] = intval($query_item['total_traffics']);
                    $traffic_list[$key]['amount'] = $item['price'] * intval($query_item['total_traffics']);
                } else {
                    $traffic_list[$key]['cnts'] = intval($query_item[$key]);
                    $traffic_list[$key]['amount'] = $item['price'] * intval($query_item[$key]);
                }
            }

            return $traffic_list;
        } else {
            return false;
        }
    }

    /** * 获取取消返还资金信息 */
    public function cancel_refund($trade_info)
    {
        if ($trade_info->trade_status == '1') {
            return (object)['surplus_num' => $trade_info->total_num, 'deposit' => 0, 'point' => $trade_info->trade_point];
        } elseif (in_array($trade_info->trade_status, ['2', '6'])) {
            // 统计还未开始的订单数
            $sql = 'select sum(t.total_traffics) total_traffics, sum(t.collect_goods) collect_goods, sum(t.add_to_cart) add_to_cart, sum(t.collect_shop) collect_shop, 
                           sum(t.get_coupon) get_coupon, sum(t.item_evaluate) item_evaluate, sum(t.compare_goods) compare_goods, sum(t.like_goods) like_goods, sum(t.surplus_num) surplus_num, i.created_time
                      from rqf_trade_traffic t left join rqf_trade_info i on t.trade_id = i.id where trade_id = ? ';
            $traffic_item = $this->db->query($sql, [intval($trade_info->id)])->row();

            $sql ="SELECT
                    traffic_id,
                    sum( IF ( normal_price > 0, 1, 0 ) ) AS normal_price,
                    sum( IF ( collect_goods > 0, 1, 0 ) ) AS collect_goods,
                    sum( IF ( add_to_cart > 0, 1, 0 ) ) AS add_to_cart,
                    sum( IF ( collect_shop > 0, 1, 0 ) ) AS collect_shop,
                    sum( IF ( get_coupon > 0, 1, 0 ) ) AS get_coupon,
                    sum( IF ( item_evaluate > 0, 1, 0 ) ) AS item_evaluate,
                    sum( IF ( compare_goods > 0, 1, 0 ) ) AS compare_goods,
                    sum( IF ( like_goods > 0, 1, 0 ) ) AS like_goods
                FROM
                    rqf_trade_traffic_detail 
                WHERE
                    trade_id = ?";
            $traffic_detail = $this->db->query($sql, [intval($trade_info->id)])->row();

            if ($traffic_detail)
            {
                $traffic_item->collect_goods +=$traffic_detail->collect_goods;
                $traffic_item->add_to_cart +=$traffic_detail->add_to_cart;
                $traffic_item->collect_shop +=$traffic_detail->collect_shop;
                $traffic_item->get_coupon +=$traffic_detail->get_coupon;
                $traffic_item->item_evaluate +=$traffic_detail->item_evaluate;
                $traffic_item->compare_goods +=$traffic_detail->compare_goods;
                $traffic_item->surplus_num +=$traffic_detail->normal_price;
                $traffic_item->like_goods +=$traffic_detail->like_goods;
            }
            if (!$traffic_item) {
                return (object)['surplus_num' => 0, 'deposit' => 0, 'point' => 0];
            }
            // 统计未开始的任务数
            $service_point = 0;
            if (intval($traffic_item->total_traffics) == intval($traffic_item->surplus_num)) {
                // 退还增值服务费用
                $trade_service = $this->db->get_where('rqf_trade_service', ['trade_id' => intval($trade_info->id)])->result();
                foreach ($trade_service as $v) {
                    if (in_array($v->service_name, ['first_check', 'set_time', 'set_over_time'])) {
                        continue;
                    }
                    $service_point = bcadd($service_point, $v->pay_point, 4);
                }

            } else {
                $traffic_average_list = ['add_reward', 'area_limit', 'sex_limit'];
                $sql = 'select * from rqf_trade_service where trade_id = ? and service_name in ? ';
                $trade_service = $this->db->query($sql, [intval($trade_info->id), $traffic_average_list])->result();
                foreach ($trade_service as $v) {
                    $service_point = floatval($v->price * $traffic_item->surplus_num);
                }
            }

            // 发布任务单费用
            $trade_point = 0;
            $price_arr = $this->get_traffic_list(intval($traffic_item->created_time));
            $trade_point += floatval($traffic_item->surplus_num * $price_arr['normal_price']['price']);
            $trade_point += floatval($traffic_item->collect_goods * $price_arr['collect_goods']['price']);
            $trade_point += floatval($traffic_item->add_to_cart * $price_arr['add_to_cart']['price']);
            $trade_point += floatval($traffic_item->collect_shop * $price_arr['collect_shop']['price']);
            $trade_point += floatval($traffic_item->get_coupon * $price_arr['get_coupon']['price']);
            $trade_point += floatval($traffic_item->item_evaluate * $price_arr['item_evaluate']['price']);
            $trade_point += floatval($traffic_item->compare_goods * $price_arr['compare_goods']['price']);
            $trade_point += floatval($traffic_item->like_goods * $price_arr['like_goods']['price']);
            // 合计费用
            $total_point = bcadd($trade_point, $service_point, 4);
            // 返回结果集
            return (object)['surplus_num' => $traffic_item->surplus_num, 'deposit' => 0, 'point' => $total_point];
        }
    }

    /** 首页任务单各状态数量 */
    public function traffic_order_cnts($trade_id, $total_nums)
    {
        $ongoing = 0;           // 进行中
        $wait_send = 0;         // 已提交、待审核
        $wait_refund = 0;       // 待发放佣金
        $finished = 0;          // 已完成
        $not_pay = 0;           // 已接手
        $not_started = 0;       // 未接单

        $sql = 'select traffic_status, count(*) cnts from rqf_traffic_record_union where trade_id = ? and traffic_status <= 7 group by traffic_status ';
        $res = $this->db->query($sql, [intval($trade_id)])->result();
        foreach ($res as $v) {
            if ($v->traffic_status == '7') {
                $finished += intval($v->cnts);
            } else {
                $ongoing += intval($v->cnts);
            }
            // 已提交、待审核
            if ($v->traffic_status == '1') {
                $wait_send += intval($v->cnts);
            }
            // 待发放佣金
            if ($v->traffic_status == '2') {
                $wait_refund += intval($v->cnts);
            }
            // 已接手
            if ($v->traffic_status == '0') {
                $not_pay += intval($v->cnts);
            }
        }

        $sql = 'select apply_num, finish_num from `rqf_trade_info` where id = ? ';
        $res = $this->db->query($sql, [intval($trade_id)])->row();
        $finished = $res->finish_num;
        $not_pay = $res->apply_num;

        $not_started = $total_nums - $finished - $ongoing;
        $not_started = max($not_started, 0);

        return (object)['ongoing' => $ongoing, 'wait_send' => $wait_send, 'wait_refund' => $wait_refund, 'finished' => $finished, 'not_started' => $not_started, 'not_pay' => $not_pay];
    }

    /** 普通任务单发布时，搭配浏览任务展示列表 */
    public function normal_task_traffic_show($total_nums, $service_list, $cur_time)
    {
        if ($cur_time < strtotime('2019-03-01')) {
            $traffic_list = [
                'normal_price' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中浏览商品：', 'price' => '0.5', 'nums' => 0, 'total' => 0],
                'collect_goods' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中收藏商品：', 'price' => '0.55', 'nums' => 0, 'total' => 0],
                'add_to_cart' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中加购商品：', 'price' => '0.8', 'nums' => 0, 'total' => 0],
                'collect_shop' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中收藏店铺：', 'price' => '0.65', 'nums' => 0, 'total' => 0],
                'get_coupon' => ['title' => '&nbsp;其中申请优惠券：', 'price' => '0.55', 'nums' => 0, 'total' => 0],
                'item_evaluate' => ['title' => '&nbsp;进入宝贝评价页：', 'price' => '0.4', 'nums' => 0, 'total' => 0],
            ];
        } else {
            $traffic_list = [
                'normal_price' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中浏览商品：', 'price' => '0.8', 'nums' => 0, 'total' => 0],
                'collect_goods' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中收藏商品：', 'price' => '0.5', 'nums' => 0, 'total' => 0],
                'add_to_cart' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中加购商品：', 'price' => '0.6', 'nums' => 0, 'total' => 0],
                'collect_shop' => ['title' => '&nbsp;&nbsp;&nbsp;&nbsp;其中收藏店铺：', 'price' => '0.6', 'nums' => 0, 'total' => 0],
                'get_coupon' => ['title' => '&nbsp;其中申请优惠券：', 'price' => '0.5', 'nums' => 0, 'total' => 0],
                'item_evaluate' => ['title' => '&nbsp;进入宝贝评价页：', 'price' => '0.4', 'nums' => 0, 'total' => 0],
            ];
        }
        // 先处理计算
        foreach ($traffic_list as $key => $item) {
            if (array_key_exists($key, $service_list)) {
                $traffic_list[$key]['nums'] = intval($service_list[$key]);
                $traffic_list[$key]['total'] = floatval($service_list[$key] * $total_nums * $item['price']);
            }
        }

        return $traffic_list;
    }
}
