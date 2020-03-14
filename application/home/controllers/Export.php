<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:数据导出控制器
 * 担当:
 */
class Export extends Ext_Controller
{

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Trade_Model', 'trade');
        $this->load->model('Conf_Model', 'conf');
    }

    /**
     * 活动详情页--买号信息
     */
    public function account_info()
    {
        $trade_id = intval($this->input->post('trade_id'));
        $user_id = $this->session->userdata('user_id');
        $trade_info = $this->db->get_where('rqf_trade_info', ['id' => $trade_id, 'user_id' => $user_id])->row();
        if (empty($trade_info)) {
            return;
        }

        $status_text_list = [
            '0' => '已接手，待付款',
            '1' => '快递单打印中',
            '2' => '已付款，待发货',
            '3' => '快递单已打印，待收货',
            '4' => '已收货，待退款',
            '5' => '已操作返款，待买手确认',
            '6' => '已收货，待退款',
            '7' => '已完成'
        ];

        // 平台返款
        $str_data = "买号,订单号,状态,快递名称,快递单号,买手提交时间,退款金额\r\n";
        $sql = "select o.id, o.channel, o.order_sn, o.express_sn, o.express_type, o.order_money, o.order_status, o.pay_sn, o.pay_time, o.order_money, b.account_name
                  from rqf_trade_order_union o left join rqf_bind_account b on o.account_id = b.id 
                 where o.bus_user_id = ? and o.trade_id = ? and o.order_status <= 7 ";
        $res = $this->db->query($sql, [$user_id, $trade_id])->result();
        $shipping_type_list = $this->conf->get_shipping_type_list();
        foreach ($res as $v) {
            // 买号
            $str_data .= $v->account_name . "\t,";
            // 订单号
            $str_data .= $v->pay_sn . "\t,";
            // 状态
            $str_data .= "{$status_text_list[$v->order_status]}" . "\t,";
            // 快递名称
            $express_type = empty($v->express_type) ? '自发快递' : $shipping_type_list[$v->express_type]['name'];
            $str_data .= $express_type . "\t,";
            // 快递单号
            $str_data .= $v->express_sn . "\t,";
            // 买手提交时间
            $str_data .= date('Y-m-d H:i:s', $v->pay_time) . "\t,";
            // 退款金额
            $str_data .= $v->order_money . "\r\n";
        }

        $d = date('Ymd');

        $this->load->helper('download');
        force_download("{$trade_info->trade_sn}买手信息_{$d}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
        return;
    }

    /**
     * 发货页面--发货信息
     */
    public function send_info()
    {
        $t = intval($this->uri->segment(3));
        if (!in_array($t, [1, 2, 3, 4, 5, 6])) {
            $t = 1;
        }
        if ($t == 1) {
            $where = 'and o.order_status in (0,1,2,3)';
        } elseif ($t == 2) {
            $where = 'and o.order_status = 0';
        } elseif ($t == 3) {
            $where = 'and o.order_status = 1';
        } elseif ($t == 4) {
            $where = 'and o.order_status = 2';
        } elseif ($t == 5) {
            $where = 'and o.order_status = 3';
        } elseif ($t == 6) {
            $where = 'and o.order_status > 7 and o.first_start_time >= '. strtotime('-2 day');
        }
        $user_id = $this->session->userdata('user_id');
        $order_id = $this->input->post('order_id');
        $order_ids = implode(',', $order_id);
        if (empty($order_ids)) {
            $order_ids = '0';
        }

        $sql = "select o.*, s.shop_name, a.account_name
                  from rqf_trade_order_union o 
                    left join rqf_bind_shop s on o.shop_id = s.id
                    left join rqf_bind_account a on o.account_id = a.id
                  where o.bus_user_id = {$user_id} and o.id in ({$order_ids}) {$where}";
        $res = $this->db->query($sql)->result();
        $plat_list = $this->conf->plat_list();
        $status_list = $this->conf->order_status_list();
        $str_data = "平台,店铺,买手旺旺号,订单号,订单金额,买手提交时间,快递方式,快递单号,平台活动号,活动状态,生成时间\r\n";
        $shipping_type_list = $this->conf->get_shipping_type_list();
        foreach ($res as $v) {
            // 平台
            $str_data .= $plat_list[$v->plat_id]['pname'] . "\t,";
            // 店铺
            $str_data .= $v->shop_name . "\t,";
            // 买手旺旺号
            $str_data .= $v->account_name . "\t,";
            // 订单号
            $str_data .= $v->pay_sn . "\t,";
            // 订单金额
            $str_data .= $v->order_money . "\t,";
            // 买手提交时间
            if ($v->pay_time) {
                $str_data .= date('Y-m-d H:i:s', $v->pay_time) . "\t,";
            } else {
                $str_data .= "\t,";
            }
            // 快递方式
            $express_type = empty($v->express_type) ? '自发快递' : $shipping_type_list[$v->express_type]['name'];
            $str_data .= $express_type . "\t,";
            // 快递单号
            $str_data .= $v->express_sn . "\t,";
            // 平台活动号
            $str_data .= $v->order_sn . "\t,";
            // 活动状态
            $str_data .= $status_list[$v->order_status] . "\t,";
            // 生成时间
            $str_data .= date('Y-m-d H:i') . "\t\r\n";
        }

        $d = date('Ymd');

        $this->load->helper('download');
        force_download("订单信息_{$d}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
        return;
    }

    /**
     * 发货页面--发货信息(全部)
     */
    public function send_info_all()
    {
        $t = intval($this->uri->segment(3));
        if (!in_array($t, [1, 2, 3, 4, 5, 6])) {
            $t = 1;
        }

        if ($t == 1) {
            $where = 'and o.order_status in (0,1,2,3)';
        } elseif ($t == 2) {
            $where = 'and o.order_status = 0';
        } elseif ($t == 3) {
            $where = 'and o.order_status = 1';
        } elseif ($t == 4) {
            $where = 'and o.order_status = 2';
        } elseif ($t == 5) {
            $where = 'and o.order_status = 3';
        } elseif ($t == 6) {
            $where = 'and o.order_status > 7 and o.first_start_time >= '. strtotime('-2 day');
        }

        $user_id = $this->session->userdata('user_id');
        $sql = "select o.*, s.shop_name, a.account_name
                  from rqf_trade_order_union o
                    left join rqf_bind_shop s on o.shop_id = s.id
                    left join rqf_bind_account a on o.account_id = a.id
                 where o.bus_user_id = {$user_id} {$where}";
        $res = $this->db->query($sql)->result();
        $plat_list = $this->conf->plat_list();
        $status_list = $this->conf->order_status_list();
        $str_data = "平台,店铺,买手旺旺号,订单号,订单金额,买手提交时间,快递方式,快递单号,平台活动号,活动状态,生成时间\r\n";
        $shipping_type_list = $this->conf->get_shipping_type_list();
        foreach ($res as $v) {
            // 平台
            $str_data .= $plat_list[$v->plat_id]['pname'] . "\t,";
            // 店铺
            $str_data .= $v->shop_name . "\t,";
            // 买手旺旺号
            $str_data .= $v->account_name . "\t,";
            // 订单号
            $str_data .= $v->pay_sn . "\t,";
            // 订单金额
            $str_data .= $v->order_money . "\t,";
            // 买手提交时间
            if ($v->pay_time)
                $str_data .= date('Y-m-d H:i:s', $v->pay_time) . "\t,";
            else
                $str_data .= "\t,";
            // 快递方式
            $express_type = empty($v->express_type) ? '自发快递' : $shipping_type_list[$v->express_type]['name'];
            $str_data .= $express_type . "\t,";
            // 快递单号
            $str_data .= $v->express_sn . "\t,";
            // 平台活动号
            $str_data .= $v->order_sn . "\t,";
            // 活动状态
            $str_data .= $status_list[$v->order_status] . "\t,";
            // 生成时间
            $str_data .= date('Y-m-d H:i') . "\t\r\n";
        }

        $d = date('Ymd');
        $this->load->helper('download');
        force_download("订单信息_{$d}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
        exit();
    }

    /** 导出用户押金记录 **/
    public function user_deposit_list()
    {
        $user_id = intval($this->session->userdata('user_id'));
        $start_time = $this->input->get('st');
        $end_time = $this->input->get('et');

        $sql = "select d.*, s.plat_id, s.shop_name
                      from rqf_bus_user_deposit d left join rqf_bind_shop s on d.shop_id = s.id
                     where d.user_id = ? and d.action_time >= ? and d.action_time < ? order by d.id desc ";
        $query_list = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time) + 86400])->result();
        $plat_type_list = $this->conf->plat_list();
        $deposit_type_list = $this->conf->deposit_type_list();
        $str_data = "店铺,收入（元）,支出（元）,冻结（元）,结余（元）,操作时间,活动编号,备注\r\n";
        foreach ($query_list as $item) {
            $plat_name = isset($plat_type_list[$item->plat_id]) ? $item->shop_name . '/' . $plat_type_list[$item->plat_id]['pname'] : '--';
            $order_sn = $item->order_sn;
            if ($item->trade_sn) {
                $order_sn = $item->trade_sn;
            }
            if (empty($order_sn)) {
                $order_sn = $item->pay_sn;
            }
            // 数据填充
            $str_data .= $plat_name . "\t,";
            if (floatval($item->score_nums) > 0) {
                $str_data .= $item->score_nums . ',,';
            } else {
                $str_data .= ',' . $item->score_nums . ',';
            }
            $str_data .= $item->frozen_score_nums . ',';
            $str_data .= $item->last_score . ',';
            $str_data .= date('Y-m-d H:i', $item->action_time) . "\t,";
            $str_data .= $order_sn . "\t,";
            $str_data .= $deposit_type_list[$item->action_type] . "\r\n";
        }

        $d = date('Ymd');
        $this->load->helper('download');
        force_download("押金记录_{$d}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
        exit();
    }

    /** 导出用户金币记录 **/
    public function user_points_list()
    {
        $user_id = intval($this->session->userdata('user_id'));
        $start_time = $this->input->get('st');
        $end_time = $this->input->get('et');

        $sql = "select d.*, s.plat_id, s.shop_name
                      from rqf_bus_user_point d left join rqf_bind_shop s on d.shop_id = s.id
                     where d.user_id = ? and d.action_time >= ? and d.action_time < ? order by d.id desc ";
        $query_list = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time) + 86400])->result();
        $plat_type_list = $this->conf->plat_list();
        $deposit_type_list = $this->conf->deposit_type_list();
        $str_data = "店铺,收入（个）,支出（个）,冻结（个）,结余（个）,操作时间,活动编号,备注\r\n";
        foreach ($query_list as $item) {
            $plat_name = isset($plat_type_list[$item->plat_id]) ? $item->shop_name . '/' . $plat_type_list[$item->plat_id]['pname'] : '--';
            $order_sn = $item->order_sn;
            if ($item->trade_sn) {
                $order_sn = $item->trade_sn;
            }
            if (empty($order_sn)) {
                $order_sn = $item->pay_sn;
            }
            // 数据填充
            $str_data .= $plat_name . "\t,";
            if (floatval($item->score_nums) > 0) {
                $str_data .= $item->score_nums . ',,';
            } else {
                $str_data .= ',' . $item->score_nums . ',';
            }
            $str_data .= $item->frozen_score_nums . ',';
            $str_data .= $item->last_score . ',';
            $str_data .= date('Y-m-d H:i', $item->action_time) . "\t,";
            $str_data .= $order_sn . "\t,";
            $str_data .= $deposit_type_list[$item->action_type] . "\r\n";
        }

        $d = date('Ymd');
        $this->load->helper('download');
        force_download("金币记录_{$d}.csv", mb_convert_encoding($str_data, "GBK", "UTF-8"));
        exit();
    }

    /** 订单明细报表 */
    public function capital_recode()
    {
        $shop_id = intval($this->input->get('shop'));
        $start_time = trim($this->input->get('st'));
        $end_time = trim($this->input->get('et'));
        $user_id = intval($this->session->userdata('user_id'));
        if (empty($start_time) || empty($end_time)) {
            exit('请确定导出报表的时间范围');
        }
        if (strtotime($start_time) > strtotime($end_time)) {
            $tmp_time = $start_time;
            $start_time = $end_time;
            $end_time = $tmp_time;
        }
        if (strtotime($end_time) - strtotime($start_time) > 86400*30) {
            exit('导出数据的时间范围请确认在一个月时间内');
        }

        $condition = '';
        if ($shop_id > 0) {
            $condition .= ' and t.shop_id = '. $shop_id;
        }

        // 导出excel
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();
        // Sheet1 data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet(0)->setTitle('任务单表');
        // Field names in the first row
        $col = 0;
        $objPHPExcel->getActiveSheet(0)
            ->setCellValueByColumnAndRow($col++, 1, '店铺名')
            ->setCellValueByColumnAndRow($col++, 1, '任务编号')
            ->setCellValueByColumnAndRow($col++, 1, '发布时间')
            ->setCellValueByColumnAndRow($col++, 1, '商品名称')
            ->setCellValueByColumnAndRow($col++, 1, '商品链接')
            ->setCellValueByColumnAndRow($col++, 1, '发布总单数')
            ->setCellValueByColumnAndRow($col++, 1, '已接手单数')
            ->setCellValueByColumnAndRow($col++, 1, '撤销单数')
            ->setCellValueByColumnAndRow($col++, 1, '消耗押金（元）')
            ->setCellValueByColumnAndRow($col, 1, '消耗金币（个）');
        // 数据统计
        $sql = 'select t.id, t.trade_sn, t.created_time, t.trade_status, t.total_num, t.apply_num, t.phone_num, t.pc_num, t.trade_deposit, t.trade_point, t.pay_deposit, t.pay_point, i.goods_img, i.goods_name, i.goods_url, s.shop_name
                  from rqf_trade_info t left join rqf_trade_item i on t.id = i.trade_id left join rqf_bind_shop s on t.shop_id = s.id  
                 where t.user_id = ? and t.is_show = 1 and t.created_time >= ? and t.created_time < ?'. $condition. ' order by t.created_time desc';
        $query_list = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime("$end_time+1day")])->result();
        foreach ($query_list as $key => $item) {
            $query_list[$key]->cancel_nums = 0;
            if ($item->total_num != $item->apply_num) {
                if ($item->trade_status == '0' || $item->trade_status == '5') {
                    // 没有确认付款的（未支付、或审核未通过的）
                    $query_list[$key]->apply_num = 0;
                    $query_list[$key]->cancel_nums = $item->total_num;
                    $query_list[$key]->trade_deposit = 0;
                    $query_list[$key]->trade_point = 0;
                } elseif ($item->trade_status == '6' || $item->trade_status == '9') {
                    // 查看已完成的单子
                    $sql = 'select ifnull(count(distinct order_sn), 0) cnts, sum(score_nums + frozen_score_nums) trade_deposit from rqf_bus_user_deposit where user_id = ? and action_type in (102, 500) and trade_sn = ?';
                    $query = $this->db->query($sql, [$user_id, $item->trade_sn])->row();
                    $query_list[$key]->apply_num = $query->cnts;
                    $query_list[$key]->cancel_nums = $item->total_num - $query->cnts;
                    $query_list[$key]->trade_deposit = abs(floatval($query->trade_deposit));
                    // 使用金币统计
                    $sql = 'select round(ifnull(sum(score_nums), 0), 2) trade_point from rqf_bus_user_point where user_id = ? and trade_sn = ? ';
                    $query = $this->db->query($sql, [$user_id, $item->trade_sn])->row();
                    $query_list[$key]->trade_point = abs(floatval($query->trade_point));
                } else {
//                    // 查看已取消的单子（审核通过，预备、或者正在进行当中的）
//                    $sql = 'select ifnull(count(*), 0) cnts from rqf_bus_user_deposit where user_id = ? and action_type >= 400 and action_type < 500 and trade_sn = ?';
//                    $query = $this->db->query($sql, [$user_id, $item->trade_sn])->row();
//                    $query_list[$key]->cancel_nums = $query->cnts;
//                    $query_list[$key]->apply_num = $item->total_num - $query->cnts;
                }
            }
        }

        // Fetching the table data
        $row = 2;
        foreach($query_list as $item)
        {
            $col = 0 ;
            $objPHPExcel->getActiveSheet(0)
                ->setCellValueByColumnAndRow($col++, $row, $item->shop_name)
                ->setCellValueByColumnAndRow($col++, $row, $item->trade_sn)
                ->setCellValueByColumnAndRow($col++, $row, date('Y-m-d H:i', $item->created_time). "\t")
                ->setCellValueByColumnAndRow($col++, $row, $item->goods_name)
                ->setCellValueByColumnAndRow($col++, $row, $item->goods_url)
                ->setCellValueByColumnAndRow($col++, $row, $item->total_num)
                ->setCellValueByColumnAndRow($col++, $row, $item->apply_num)
                ->setCellValueByColumnAndRow($col++, $row, $item->cancel_nums)
                ->setCellValueByColumnAndRow($col++, $row, $item->trade_deposit)
                ->setCellValueByColumnAndRow($col, $row, $item->trade_point);
            $row++;
        }

        // Sheet2 data
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        // 数据统计
        $sql = 'select t.trade_sn, t.trade_type,t.created_time,  i.goods_img, i.goods_name, i.goods_url, i.buy_num, i.price, o.order_sn, o.first_start_time, o.order_money, o.order_status, o.pay_sn, b.account_name, s.kwd
                  from rqf_trade_info t 
                      left join rqf_trade_item i on t.id = i.trade_id 
                      left join rqf_trade_order_union o on t.id = o.trade_id and t.user_id = o.bus_user_id
                      left join rqf_trade_search s on o.search_id = s.id 
                      left join rqf_bind_account b on o.account_id = b.id
                 where t.user_id = ? and t.is_show = 1 and t.created_time >= ? and t.created_time < ?'. $condition. ' and o.order_status <= 7 order by t.created_time desc, o.trade_sn ';
        $query_order = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time)])->result();

        // Field names in the first row
        $col = 0;
        $objPHPExcel->getActiveSheet(1)->setTitle("订单明细表");
        $objPHPExcel->getActiveSheet(1)
            ->setCellValueByColumnAndRow($col++, 1, '任务编号')
            ->setCellValueByColumnAndRow($col++, 1, '订单编号')
            ->setCellValueByColumnAndRow($col++, 1, '发布时间')
            ->setCellValueByColumnAndRow($col++, 1, '接手时间')
            ->setCellValueByColumnAndRow($col++, 1, '商品名')
            ->setCellValueByColumnAndRow($col++, 1, '商品链接')
            ->setCellValueByColumnAndRow($col++, 1, '买家ID')
            ->setCellValueByColumnAndRow($col++, 1, '订单状态')
            ->setCellValueByColumnAndRow($col++, 1, '检查关键词')
            ->setCellValueByColumnAndRow($col++, 1, '下单订单号')
            ->setCellValueByColumnAndRow($col, 1, '订单付款金额');
        // Fetching the table data
        $row = 2;
        $order_status_list = $this->conf->order_status_list();
        foreach($query_order as $item)
        {
            $col = 0;
            $objPHPExcel->getActiveSheet(1)
                ->setCellValueByColumnAndRow($col++, $row, $item->trade_sn)
                ->setCellValueByColumnAndRow($col++, $row, $item->order_sn)
                ->setCellValueByColumnAndRow($col++, $row, date('Y-m-d H:i', $item->created_time). "\t")
                ->setCellValueByColumnAndRow($col++, $row, date('Y-m-d H:i', $item->first_start_time). "\t")
                ->setCellValueByColumnAndRow($col++, $row, $item->goods_name)
                ->setCellValueByColumnAndRow($col++, $row, $item->goods_url)
                ->setCellValueByColumnAndRow($col++, $row, $item->account_name. "\t")
                ->setCellValueByColumnAndRow($col++, $row, $order_status_list[$item->order_status])
                ->setCellValueByColumnAndRow($col++, $row, in_array($item->trade_type, ['4', '5', '7']) ? '--' : $item->kwd)
                ->setCellValueByColumnAndRow($col++, $row, $item->pay_sn. "\t")
                ->setCellValueByColumnAndRow($col, $row, ($item->order_status == '0') ? ($item->price * $item->buy_num) : $item->order_money);
            $row++;
        }

        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="订单明细表—' . $start_time . '_' . $end_time . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
}
