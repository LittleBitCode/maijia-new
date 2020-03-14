<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称：商家财务系列
 */
class Finance extends Ext_Controller
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /** 商家任务清单 **/
    public function summary()
    {
        $data = $this->data;
        $user_id = intval($this->session->userdata('user_id'));
        $shop_id = intval($this->input->get('shop_id'));
        $start_time = trim($this->input->get('st'));
        $end_time = trim($this->input->get('et'));
        $page = intval($this->input->get('page'));
        $page = ($page <= 1) ? 1 : $page;
        $page_size = 5 ;
        if (empty($start_time)) {
            $start_time = date('Y-m-d', strtotime('-7 day'));
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d', time());
        }
        if (strtotime($start_time) > strtotime($end_time)) {
            $tmp_time = $start_time;
            $start_time = $end_time;
            $end_time = $tmp_time;
        }

        // 所有店铺
        $redis_key = 'FINANCE_SUMMARY_SHOP_' . $user_id;
        $shop_list = $this->cache->redis->get($redis_key);
        if (!$shop_list) {
            $this->load->model('Bind_Model', 'bind');
            $query_list = $this->bind->all_shop_list($user_id);
            $data['shop_list'] = [];
            foreach ($query_list as $item) {
                $shop_list[] = ['id' => $item->id, 'shop_name' => $item->shop_name];
            }
            $this->cache->redis->save($redis_key, $shop_list, 600);
        }
        $data['shop_list'] = $shop_list;

        $condition = '';
        if ($shop_id > 0) {
            $condition .= ' and t.shop_id = '. $shop_id;
        }
        // 翻页时 不加载曲线数据
        $redis_key = 'FINANCE_SUMMARY_LINE_CHART_'. $user_id;
        $line_data = $this->cache->redis->get($redis_key);
        if ($page <= 1 || !$line_data) {
            $summary = [];
            $idx = strtotime($start_time);
            while ($idx < strtotime($end_time) + 86400) {
                $key = date('Y-m-d', $idx);
                $summary[$key] = ['deposit' => 0.00, 'points' => 0.00];
                $idx = $idx + 86400;
            }

            // 任务数统计
            $sql = 'select count(*) cnts, sum(total_num) total_num from rqf_trade_info t where t.user_id = ? and t.is_show = 1 and t.created_time >= ? and t.created_time < ?' . $condition;
            $query_nums = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time) + 86400])->row();
            // 统计每天分布曲线图
            $sql = 'select cast(from_unixtime(t.created_time) as char(10)) date_time, sum(d.score_nums) trade_deposit
                     from rqf_trade_info t left join rqf_bus_user_deposit d on t.user_id = d.user_id and t.trade_sn = d.trade_sn
                    where t.user_id = ? and t.is_show = 1 and t.created_time >= ? and t.created_time < ?'. $condition. ' group by date_time ';
            $summary_deposit_query = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time) + 86400])->result();
            $total_deposit = 0;
            foreach ($summary_deposit_query as $item) {
                $total_deposit -= floatval($item->trade_deposit);
                $summary[$item->date_time]['deposit'] = abs($item->trade_deposit);
            }
            // 使用金币统计
            $sql = 'select cast(from_unixtime(t.created_time) as char(10)) date_time, sum(p.score_nums) trade_point
                     from rqf_trade_info t left join rqf_bus_user_point p on t.user_id = p.user_id and t.trade_sn = p.trade_sn
                    where t.user_id = ? and t.is_show = 1 and t.created_time >= ? and t.created_time < ?'. $condition. ' group by date_time ';
            $summary_point_query = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time) + 86400])->result();
            $total_point = 0;
            foreach ($summary_point_query as $item) {
                $total_point -= floatval($item->trade_point);
                $summary[$item->date_time]['points'] = abs($item->trade_point);
            }

            // 分折数组 处理曲线数据
            $line_summary = [];
            foreach ($summary as $key => $item) {
                $line_summary['labels'][] = $key;
                $line_summary['deposit'][] = $item['deposit'];
                $line_summary['points'][] = $item['points'];
            }

            $query_nums->trade_point = $total_point;
            $query_nums->trade_deposit = $total_deposit;
            $line_data = ['query_nums' => $query_nums, 'line_summary' => $line_summary];
            $this->cache->redis->save($redis_key, $line_data, 600);
        } else {
            $query_nums = $line_data['query_nums'];
            $line_summary = $line_data['line_summary'];
        }

        // 页面数据加载
        $sql = 'select t.id, t.trade_sn, t.created_time, t.trade_status, t.total_num, t.apply_num, t.phone_num, t.pc_num, i.goods_img, i.goods_name, 0 as cancel_nums
                     , if(t.trade_status in (0, 5), 0, (select sum(score_nums) from rqf_bus_user_deposit where user_id = t.user_id and trade_sn = t.trade_sn)) trade_deposit
                     , if(t.trade_status in (0, 5), 0, (select sum(score_nums) from rqf_bus_user_point where user_id = t.user_id and trade_sn = t.trade_sn)) trade_point
                  from rqf_trade_info t left join rqf_trade_item i on t.id = i.trade_id
                 where t.user_id = ? and t.is_show = 1 and t.created_time >= ? and t.created_time < ?'. $condition. ' order by t.created_time desc limit ?, ?';
        $query_list = $this->db->query($sql, [$user_id, strtotime($start_time), strtotime($end_time) + 86400, ($page-1)*$page_size, $page_size])->result();
        foreach ($query_list as $key => $item) {
            if ($item->total_num != $item->apply_num) {
                if ($item->trade_status == '0' || $item->trade_status == '5') {
                    // 没有确认付款的（未支付、或审核未通过的）
                    $query_list[$key]->apply_num = 0;
                    $query_list[$key]->cancel_nums = $item->total_num;
                } elseif ($item->trade_status == '6' || $item->trade_status == '9') {
                    // 查看已完成的单子
                    $sql = 'select ifnull(count(distinct order_sn), 0) cnts from rqf_bus_user_deposit where user_id = ? and action_type in (102, 500) and trade_sn = ?';
                    $query = $this->db->query($sql, [$user_id, $item->trade_sn])->row();
                    $query_list[$key]->apply_num = $query->cnts;
                    $query_list[$key]->cancel_nums = $item->total_num - $query->cnts;
                }
            }
        }

        // 加载分页
        $this->load->library('pagination');
        $config['base_url'] = '/finance/summary/?shop_id='. $shop_id .'&st='. $start_time .'&et='. $end_time;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $query_nums->cnts;
        $config['per_page'] = $page_size;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';
        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['trade_list'] = $query_list;
        $data['summary'] = $query_nums;
        $data['params'] = ['shop_id' => $shop_id, 'start_time' => $start_time, 'end_time' => $end_time];
        $data['line_summary'] = $line_summary;

        $this->load->view('review/finance_summary', $data);
    }


}
