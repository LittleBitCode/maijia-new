<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:商家个人中心控制器
 * 担当:
 */
class Center extends Ext_Controller {

    /**
     * __construct
     */
    public function __construct () {
               
        parent::__construct();
        $this->load->model('Conf_Model', 'conf');
        $this->load->model('Bind_Model','bind');
        $this->load->model('Center_Model', 'center');
        $this->load->model('Review_Model', 'review');
        $this->load->model('Sms_Model', 'sms');
        $this->load->library('Common');
    }

    /**
     * 个人中心
     */
    public function index()
    {
        $data = $this->data;
        $user_info = $data['user_info'];
        $user_id = intval($this->session->userdata('user_id'));
        // 安全等级
        $data['safety_level'] = $this->center->get_safety_level_info();
        // 押金相关变量
        $data['deposit_vars'] = $this->center->deposit_vars($user_info);
        // 任务订单状态相关
        $data['status_list'] = $this->review->get_order_status_nums();

        // 首页弹出框
        $redis_key = 'HOME_PAGE_TIPS_' . $user_info->id;
        $redis_home_tips = $this->cache->redis->get($redis_key);
        if (!$redis_home_tips) {
            $expire_times = strtotime(date('Y-m-d', strtotime('+1 day'))) - time();
            $this->cache->redis->save($redis_key, 1, $expire_times);
        }
        $data['show_tips'] = $redis_home_tips;

        //流量订单数量
        $sql = 'select count(*) as cnts from rqf_traffic_record_union where bus_user_id = ? ';
        $row = $this->db->query($sql,$user_id)->row();
        if ($row){
            $data['traffic_num']= $row->cnts;
        }
        $this->load->view('center/index', $data);
    }

    /**
     * 活动管理
     */
    public function trade_manage()
    {
        $data = $this->data;
        $data['status_list'] = $this->review->get_order_status_nums();
        $this->load->view('center/trade_manage', $data);
    }

    /**
     * 已完成的活动
     */
    public function trade_finished()
    {
        $data = $this->data;
        $this->load->view('center/trade_finished', $data);
    }

    /**
     * 资金记录
     */
    public function record_list()
    {
        $data = $this->data;
        $t = intval($this->uri->segment(3));
        if (!in_array($t, [1, 2, 3, 4, 5, 6])) {
            $t = 1;
        }

        $data['t'] = $t;
        $user_id = intval($this->session->userdata('user_id'));

        // 分页信息
        $page = intval($this->input->get('page')) ? intval($this->input->get('page')) : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        $str_req = '?';

        if ($t == 1) {
            // 押金记录
            $order_sn = trim($this->input->get('sn'));
            $start_time = $this->input->get('st');
            $end_time = $this->input->get('et');
            $plat_id = intval($this->input->get('plat'));
            $shop_id = intval($this->input->get('shop'));
            $condition = '';
            if ($order_sn) {
                if (strpos($order_sn, '-')) {
                    $condition .= ' and d.order_sn = "'. $order_sn .'"';
                    $str_req .= '&sn='. $order_sn;
                }else{
                    $condition .= ' and (d.trade_sn = "'. $order_sn .'" or d.pay_sn = "'. $order_sn .'")';
                    $str_req .= '&sn='. $order_sn;
                }
            }
            if ($start_time) {
                $condition .= ' and d.action_time >= '. strtotime($start_time);
                $str_req .= '&st='. $start_time;
            }
            if ($end_time) {
                $condition .= ' and d.action_time < '. (strtotime($end_time) + 86400);
                $str_req .= '&et='. $end_time;
            }
            if ($shop_id > 0) {
                $condition .= ' and d.shop_id = '. $shop_id;
                $str_req .= '&shop='. $shop_id;
            }
            if ($plat_id > 0) {
                $condition .= ' and s.plat_id = '. $plat_id;
                $str_req .= '&plat='. $plat_id;
            }

            $plat_type_list = $this->conf->plat_list();
            $sql = "select d.*, s.plat_id, s.shop_name
                      from rqf_bus_user_deposit d left join rqf_bind_shop s on d.shop_id = s.id
                     where d.user_id = ? {$condition} order by d.id desc limit ?, ?";
            $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();
            $deposit_type_list = $this->conf->deposit_type_list();
            foreach ($res as $k => $v) {
                $res[$k]->type_name = $deposit_type_list[$v->action_type];
                $res[$k]->plat_id = isset($plat_type_list[$v->plat_id]) ? $plat_type_list[$v->plat_id]['pname'] : '';
                $res[$k]->sn = '';
                if ($v->order_sn) {
                    $res[$k]->sn = $v->order_sn;
                } elseif ($v->trade_sn) {
                    $res[$k]->sn = $v->trade_sn;
                }
                if (empty($res[$k]->sn)) {
                    $res[$k]->sn = $v->pay_sn;
                }
            }
            $cnt_sql = "select count(d.id) cnt
                          from rqf_bus_user_deposit d left join rqf_bind_shop s on d.shop_id = s.id
                         where d.user_id = ? {$condition} order by d.id desc ";
            $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();
            // 搜索店铺
            $data['shop_list'] = $this->db->query('select id, shop_name from rqf_bind_shop where user_id = ? and is_show = 1', [$user_id])->result_array();
            $data['plat_type_list'] = $plat_type_list;
            $data['deposit_res'] = $res;
            $data['params'] = ['sn' => $order_sn, 'start_time' => $start_time, 'end_time' => $end_time, 'plat' => $plat_id, 'shop' => $shop_id];
        } elseif ($t == 2) {
            // 提现记录
            $status = is_null($this->input->get('status')) ? 99 : intval($this->input->get('status'));
            $start_time = $this->input->get('st');
            $end_time = $this->input->get('et');
            $condition = '';
            if ($status < 99) {
                $condition .= ' and withdrawal_status = '. $status;
                $str_req .= '&status='. $status;
            }
            if ($start_time) {
                $condition .= ' and add_time >= '. strtotime($start_time);
                $str_req .= '&st='. $start_time;
            }
            if ($end_time) {
                $condition .= ' and add_time < '. (strtotime($end_time) + 86400);
                $str_req .= '&et='. $end_time;
            }

            $sql = "select * from rqf_user_withdrawal where user_id = ? {$condition} order by id desc limit ?, ?";
            $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();
            $with_status_list = $this->conf->with_status_list();
            foreach ($res as $k => $v) {
                $res[$k]->status_text = $with_status_list[$v->withdrawal_status];
            }

            $data['withdrawal_res'] = $res;
            $cnt_sql = "select count(1) cnt from rqf_user_withdrawal where user_id = ? {$condition}";
            $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();
            // 提现状态值列表
            $data['status_list'] = $this->conf->with_status_list();
            $data['params'] = ['status' => $status, 'start_time' => $start_time, 'end_time' => $end_time];
        } elseif ($t == 3) {
            // 金币记录
            $order_sn = trim($this->input->get('sn'));
            $start_time = $this->input->get('st');
            $end_time = $this->input->get('et');
            $plat_id = intval($this->input->get('plat'));
            $shop_id = intval($this->input->get('shop'));
            $condition = '';
            if ($order_sn) {
                if (strpos($order_sn, '-')) {
                    $condition .= ' and p.order_sn = "'. $order_sn .'"';
                    $str_req .= '&sn='. $order_sn;
                }else{
                    $condition .= ' and (p.trade_sn = "'. $order_sn .'" or p.pay_sn = "'. $order_sn .'")';
                    $str_req .= '&sn='. $order_sn;
                }
            }
            if ($start_time) {
                $condition .= ' and p.action_time >= '. strtotime($start_time);
                $str_req .= '&st='. $start_time;
            }
            if ($end_time) {
                $condition .= ' and p.action_time < '. (strtotime($end_time) + 86400);
                $str_req .= '&et='. $end_time;
            }
            if ($shop_id > 0) {
                $condition .= ' and p.shop_id = '. $shop_id;
                $str_req .= '&shop='. $shop_id;
            }
            if ($plat_id > 0) {
                $condition .= ' and s.plat_id = '. $plat_id;
                $str_req .= '&plat='. $plat_id;
            }

            $plat_type_list = $this->conf->plat_list();
            $sql = "select p.*, s.plat_id, s.shop_name
                      from rqf_bus_user_point p left join rqf_bind_shop s on p.shop_id = s.id
                     where p.user_id = ? {$condition} order by p.id desc limit ?, ?";
            $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();
            $point_type_list = $this->conf->point_type_list();
            foreach ($res as $k => $v) {
                $res[$k]->type_name = $point_type_list[$v->action_type];
                $res[$k]->plat_id = isset($plat_type_list[$v->plat_id]) ? $plat_type_list[$v->plat_id]['pname'] : '';
                $res[$k]->sn = '';
                if ($v->order_sn) {
                    $res[$k]->sn = $v->order_sn;
                } elseif ($v->trade_sn) {
                    $res[$k]->sn = $v->trade_sn;
                }
            }
            $cnt_sql = "select count(p.id) cnt
                          from rqf_bus_user_point p left join rqf_bind_shop s on p.shop_id = s.id
                         where p.user_id = ? {$condition} order by p.id desc";
            $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();
            // 搜索店铺
            $data['shop_list'] = $this->db->query('select id, shop_name from rqf_bind_shop where user_id = ? and is_show = 1', [$user_id])->result_array();
            $data['plat_type_list'] = $plat_type_list;
            $data['point_res'] = $res;
            $data['params'] = ['sn' => $order_sn, 'start_time' => $start_time, 'end_time' => $end_time, 'plat' => $plat_id, 'shop' => $shop_id];
        } elseif ($t == 4) {
            // 会员记录
            $start_time = $this->input->get('st');
            $end_time = $this->input->get('et');
            $condition = '';
            if ($start_time) {
                $condition .= ' and add_time >= '. strtotime($start_time);
                $str_req .= '&st='. $start_time;
            }
            if ($end_time) {
                $condition .= ' and add_time < '. (strtotime($end_time) + 86400);
                $str_req .= '&et='. $end_time;
            }
            $sql = "select * from rqf_pay_group where user_id = ? and user_type = 1 {$condition} order by id desc limit ?, ?";
            $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();

            $data['group_res'] = $res;
            $cnt_sql = "select count(1) cnt from rqf_pay_group where user_id = ? and user_type = 1 {$condition}";
            $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();
            $data['params'] = ['start_time' => $start_time, 'end_time' => $end_time];
        } elseif ($t == 5) {
            // 充值记录
            $start_time = $this->input->get('st');
            $end_time = $this->input->get('et');
            $status = is_null($this->input->post('status')) ? 99 : intval($this->input->post('status'));

            $condition = '';
            if ($start_time) {
                $condition .= ' and add_time >= '. strtotime($start_time);
                $str_req .= '&st='. $start_time;
            }
            if ($end_time) {
                $condition .= ' and add_time < '. (strtotime($end_time) + 86400);
                $str_req .= '&et='. $end_time;
            }
            if ($status < 99) {
                $condition .= ' and pay_status = '. $status;
                $str_req .= '&status='. $status;
            }
            $sql = "select * from rqf_pay_log where user_id = ? and pay_status = 1 {$condition} order by id desc limit ?, ?";
            $res = $this->db->query($sql, [$user_id, $offset, $per_page])->result();

            $data['deposit_res'] = $res;
            $cnt_sql = "select count(1) cnt from rqf_pay_log where user_id = ? and pay_status = 1 {$condition}";
            $cnt_row = $this->db->query($cnt_sql, [$user_id])->row();
            $data['params'] = ['status' => $status, 'start_time' => $start_time, 'end_time' => $end_time];
        } elseif ($t == 6) {
            $start_time = $this->input->get('st');
            $end_time = $this->input->get('et');
            $order_sn = $this->input->get('sn');
            $trade_sn = $this->input->get('tn');
            $condition = '';
            if ($start_time) {
                $condition .= ' and o.refund_time >= '. strtotime($start_time);
            }
            if ($end_time) {
                $condition .= ' and o.refund_time < '. strtotime($end_time);
            }
            if ($order_sn) {
                $condition .= ' and o.order_sn = "'. $order_sn .'"';
            }
            if ($trade_sn) {
                $condition .= ' and o.trade_sn = "'. $trade_sn .'"';
            }

            // 统计记录条数
            $sql = 'select count(*) cnts from rqf_trade_order_union o where o.bus_user_id = ? and o.order_status = 7 '. $condition ;
            $cnt_row = $this->db->query($sql, [intval($user_id)])->row();
            // 查询记录
            $sql = 'select o.trade_id, o.trade_sn, o.order_sn, o.refund_time, s.shop_name, s.plat_name
                      from rqf_trade_order_union o left join rqf_bind_shop s on o.shop_id = s.id 
                     where o.bus_user_id = ? and o.order_status = 7 '. $condition . ' order by o.trade_id desc, o.refund_time limit ?, ?';
            $res = $this->db->query($sql, [intval($user_id), $offset, $per_page])->result();

            $data['params'] = ['start_time' => $start_time, 'end_time' => $end_time, 'order_sn' => $order_sn, 'trade_sn' => $trade_sn];
            $data['rewards_order_list'] = $res;
        }

        // 分页
        $this->load->library('pagination');

        $config['base_url'] = "/center/record_list/{$t}". $str_req;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $cnt_row->cnt;
        $config['per_page'] = $per_page;
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['next_link'] = '下一页 >';
        $config['prev_link'] = '< 上一页';

        $config['cur_tag_open'] = '<a class="now">';
        $config['cur_tag_close'] = '</a>';

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('center/record_list', $data);
    }


    /**
     * 提现/退款账号管理(rqf_bus_account)
     */
    public function withdrawal_info()
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['html_title'] = '提现帐号管理';
        $data['op'] = $this->input->get('op');
        $data['step'] = (int)$this->input->get('step');
        $judge = $this->input->get('verify');
        if (!empty($data['op']) && !empty($data['step']) && !empty($judge)) {
            $verify = $this->cache->redis->get('setbusbank-' . $user_id);
            // die;
            if ($verify == "" || !$verify || $judge != $verify) {
                $this->load->helper('burl');
                redirectmessage('/center/withdrawal_info', '没有进行手机验证，非法操作', '账号管理', 5);
                return false;
            }
        }

        // 获取银行信息
        $this->load->model('Payment_Model', 'payment');
        $data['payment_list'] = $this->payment->get_weibo_bank_list();
        // 查找绑定账户信息
        $sql = 'SELECT a.*, i.bank_short_name, i.sub_branch, i.province, i.city 
                FROM `rqf_bus_account` a 
                LEFT JOIN `rqf_bank_info` i ON a.sub_branch = i.sub_branch 
                WHERE a.user_id = ?';
        $account = $this->db->query($sql, array($user_id))->row_array();

        $sql = 'SELECT DISTINCT city FROM rqf_bank_info WHERE province = ?';
        $query_list = $this->db->query($sql, array($account['province']))->result();
        $city_list = array();
        foreach ($query_list as $row) {
            $city_list[] = $row->city;
        }

        $sql = 'SELECT bank_code, sub_branch FROM rqf_bank_info WHERE bank_short_name = ? AND province = ? AND city = ?';
        $query_list = $this->db->query($sql, array($account['bank_short_name'], $account['province'], $account['city']))->result();
        $bank_list = array();
        foreach ($query_list as $row) {
            $bank_list[$row->bank_code] = $row->sub_branch;
        }

        $sql = " SELECT AES_DECRYPT(mobile_decode,salt) mobile FROM rqf_users WHERE id = ? and user_type = 1";
        $userinfo = $this->db->query($sql, array($user_id))->row_array();

        $data['userinfo'] = $userinfo;
        $data['account'] = $account;
        $data['city_list'] = $city_list;
        $data['bank_list'] = $bank_list;

        $this->load->view('center/withdrawal_info', $data);
    }

    /**
     * 绑定账号数据唯一验证
     */
    public function check_unique() {

        $number = trim($this->input->post('number'));
        $id = $this->input->post('id');
        $unique = $this->input->post('unique');

        switch ($unique) {
            case 1:
                $unique_type = 'account_card';
                break;
            case 2:
                $unique_type = 'alipay_account';
                break;         
        }

        $where = "";
        if ($id) {
            $where = " and id !=".$id;
        }

        $sql = "select id  from rqf_bus_account where {$unique_type} = ? ".$where;
        $res = $this->db->query($sql,array($number))->row_array();

        if($res) {
            echo json_encode(array('err'=>0, 'info'=>'已存在'));
            return;
        } else {
            echo json_encode(array('err'=>1, 'info'=>'不存在'));
            return;

        }
    }

    /**
     * 设置银行提现/退款账号(rqf_bus_account)
     */
    public function set_bank_account()
    {
        $this->write_db = $this->load->database('write', true);
        $user_id = $this->session->userdata('user_id');
        // 添加锁定  限制点击频率  3s
        $lock_key = 'bank_account-' . $user_id;
        $user_tip_imp = $this->cache->redis->get($lock_key);
        if ($user_tip_imp) {
            echo json_encode(array('err' => 0, 'info' => '3秒之内请不要频繁提交'));
            return;
        } else {
            $this->cache->redis->save($lock_key, 1, 3);
        }

        $id = intval($this->input->post('id'));
        if ($id) {
            $true_name = $this->input->post('account_name');
            $sql = "select true_name from rqf_bus_account where user_id = ? and id = ? ";
            $res = $this->db->query($sql, array($user_id, $id))->row_array();
            if ($res['true_name'] == $true_name) {
                $data['account_card'] = trim($this->input->post('account_card'));
                $data['province'] = trim($this->input->post('province'));
                $data['city'] = trim($this->input->post('city'));
                $data['sub_branch'] = trim($this->input->post('sub_branch'));
                $data['bank_short_name'] = trim($this->input->post('bank_type'));
                $data['bank_status'] = 2; // 待审核 后台不再作审核

                $this->write_db->where('id', $id);
                $this->write_db->update('rqf_bus_account', $data);
                if (!$this->write_db->affected_rows()) {
                    echo json_encode(array('err' => 0, 'info' => '修改银行卡操作失败'));
                    return;
                } else {
                    echo json_encode(array('err' => 1, 'info' => '修改银行卡操作成功'));
                }
            } else {
                echo json_encode(array('err' => 0, 'info' => '提交信息的有误'));
            }
        } else {
            $data = array();
            $data['user_id'] = $user_id;
            $data['account_name'] = trim($this->input->post('account_name'));
            $data['true_name'] = $data['account_name'];
            $data['account_card'] = trim($this->input->post('account_card'));
            $data['province'] = trim($this->input->post('province'));
            $data['city'] = trim($this->input->post('city'));
            $data['sub_branch'] = trim($this->input->post('sub_branch'));
            $data['bank_short_name'] = trim($this->input->post('bank_type'));
            $data['bank_status'] = 2; // 待审核
            $this->write_db->insert('rqf_bus_account', $data);
            if (!$this->write_db->affected_rows()) {
                echo json_encode(array('err' => 0, 'info' => '绑定银行卡操作失败'));
                return;
            } else {
                echo json_encode(array('err' => 1, 'info' => '绑定银行卡操作成功'));
            }
        }

        $this->write_db->close();
    }





    public function set_zfb_account() {
        $this->write_db = $this->load->database('write', true);
        $user_id = $this->session->userdata('user_id');

        $data = array();
        $data['true_name'] = trim($this->input->post('true_name'));
        $data['alipay_account'] = trim($this->input->post('alipay_account'));
        $data['alipay_status'] = 2; // 待审核

        $sql = "SELECT id FROM rqf_bus_account WHERE user_id = ? AND true_name = ?";
        $res = $this->db->query($sql,array($user_id,$data['true_name']))->row_array();

        $id = $res['id'];

        if ($id) {

            // 上传支付宝图片
            $this->load->model('Base64_Model','base64');

            $aligpay_img = $this->input->post('alipay_img');

            $data['alipay_img'] = $this->base64->to_img($aligpay_img,UPLOAD_USER_INFO_DIR);
            $this->load->helper('qiniu_helper');
            qiniu_upload(ltrim($data['alipay_img'], '/'));
            $data['alipay_img'] = CDN_URL. $data['alipay_img'];
            $this->write_db->where('id',$id);
            $this->write_db->update('rqf_bus_account',$data);
            

            if (!$this->write_db->affected_rows()) {
                echo json_encode(array('err'=>0, 'info'=>'绑定支付宝操作失败'));
                return;
            } else {
                echo json_encode(array('err'=>1, 'info'=>'绑定支付宝操作成功')); 
            }

        } else {
            
                echo json_encode(array('err'=>0, 'info'=>'提交信息的有误'));

        }

        $this->write_db->close();

    }


    /**
     * 设置银行卡信息 发送手机验证
     * **/
    public function set_bank() {
        $this->write_db = $this->load->database('write', true);
        $user_id = $this->session->userdata('user_id');
        $data = $this->data;
        $sql = " SELECT AES_DECRYPT(mobile_decode,salt) mobile FROM rqf_users WHERE id = ? and user_type = 1";
        $userinfo = $this->db->query($sql,array($user_id))->row_array();

        // 验证码
        $phone_code = $this->input->post('phone_code');
        $time = strtotime('-10 minute');


        $sql = 'SELECT * FROM rqf_phone_check_code WHERE phone_num = ?  AND send_time > ? AND code_status = ?  ORDER BY send_time DESC LIMIT 1';
        $row = $this->db->query($sql, array($userinfo['mobile'],$time,0))->row_array();

        if (empty($row)) {
            header('content-type:text/html; charset=utf-8');
            echo '<script>alert("验证码不存在，请重新发送");history.back();</script>';
            return;
        }
        if ($row['code'] != $phone_code) {
            redirect('/center/withdrawal_info?left_list_id=2');
        } else {
            $sql = "UPDATE rqf_phone_check_code SET code_status = 1 WHERE id = ?";
            $this->write_db->query($sql, array($row['id']));
        }

        $verify = md5($userinfo['mobile'].date('Y-m-d H:i:s'));
        $this->write_db->close();

        // 存储redis缓存
        $this->load->driver('cache', array('adapter'=>'redis'));
        $this->cache->redis->save('setbusbank-'.$user_id, $verify, 3600*48);

        redirect('/center/withdrawal_info?left_list_id=2&op=setbank&step=2&verify='.$verify);

    }


    /**
     * 统计手机验证次数
     * **/
    public function check_code_nums()
    {
        $mobile = trim($this->input->post('m'));
        $phone_verify = trim($this->input->post('v'));
        $count = intval($this->input->post('i'));
        $captcha = trim($this->input->post('captcha'));
        $time = strtotime("-10 minute");

        // 验证码
        if ($count >= 3){
            if (!$captcha) {
                exit(json_encode(['status' => 0, 'msg' => '请先填写图形验证码']));
            } else {
                $captcha_check = $this->check_code($captcha);
                if (!$captcha_check) {
                    exit(json_encode(['status' => 0, 'msg' => '图形验证码验证不正确，请确认']));
                }
            }
        }

        // 获取验证码
        $sql = 'SELECT code FROM rqf_phone_check_code WHERE phone_num = ? AND send_time > ? AND code_status = 0 order by id desc';
        $res = $this->db->query($sql, array($mobile, $time))->row();
        // 1正确
        $code = array('status' => 1, 'msg' => '');
        // 0 错误
        if (empty($res) || ($res->code != $phone_verify)) {
            $code = array('status' => 0, 'msg' => '短信验证码验证失败');
        }

        exit(json_encode($code));
    }

    /**
     * 根据银行省份名称 获取对应的所在城市列表
     *  **/
    public function get_bank_region() {

        $province = $this->input->post('province');

        if (empty($province)) {
            echo json_encode(array('error'=>'1', 'message'=>'请先选择开户行所在省份'));
            exit ;
        }

        $sql = 'SELECT DISTINCT city FROM rqf_bank_info WHERE province = ?';
        $query_list = $this->db->query($sql, array($province))->result();

        $city_list = array();
        foreach ($query_list as $row) {
            $city_list[] = $row->city;
        }

        echo json_encode(array('error'=>'0', 'message'=>$city_list));
        exit ;
    }


    /**
     * 根据银行编号、地址 获取所有银行名称列表
     * **/
    public function get_bank_list() {

        $bank_name = $this->input->post('bn');
        $province = $this->input->post('prov');
        $city = $this->input->post('city');

        $sql = 'SELECT bank_code, sub_branch FROM rqf_bank_info WHERE bank_short_name = ? AND province = ? AND city = ?';
        $query_list = $this->db->query($sql, array($bank_name, $province, $city))->result();

        $bank_list = array();
        foreach ($query_list as $row) {
            $bank_list[$row->bank_code] = $row->sub_branch;
        }

        echo json_encode(array('bank'=>$bank_list));
        exit ;
    }


    /**
     * 绑定店铺
     */
    public function bind()
    {
        $data = $this->data;

        $user_id = $this->session->userdata('user_id');
        $bind_shop = $this->uri->segment('3');
        //平台列表
        $palt_list = $this->conf->plat_list();
        $check_arr = array();
        foreach ($palt_list as $key => $value) {
            array_push($check_arr, $value['name']);
        }
        $cate_list = $this->center->get_cate(-1);//获得第一级类目数据
        if (!$bind_shop || !in_array($bind_shop, $check_arr)) {
            $bind_shop = 'taobao';
        }
        //获取plat_id
        foreach ($palt_list as $k => $v) {
            if ($v['name'] == $bind_shop) $plat_id = $k;
        }
        //已绑定店铺列表
        $bind_shop_list = $this->bind->bind_shop_list($user_id, $plat_id);
        //获取地址信息
        $province_list = $this->bind->get_addr_list(1);//获取第一级省份数据
        $nums = range(0, 9);
        $chars = range('A', 'Z');
        shuffle($nums);
        shuffle($chars);
        $data['bind_shop_goods_code'] = implode(array_slice($nums, 0, 4)) . '-' . implode(array_slice($chars, 0, 4));//店铺验证码
        $data['cate_list'] = $cate_list;
        $data['bind_shop'] = $bind_shop;
        $data['bind_list'] = $palt_list;
        $data['province_list'] = $province_list;
        $data['bind_shop_list'] = $bind_shop_list;
        $data['shipping_list'] = $this->conf->get_shipping_type_list();
        $this->load->view('center/bind_shop', $data);
    }

    /**
     * ajax获取二三级地址信息
     *
     */
    public function ajax_get_addr(){
        $pid = $this->input->post('pid');
        $sql = "select * from rqf_region where parent_id = ?";
        $res = $this->db->query($sql,$pid);
        if ($res) {
            $return = $res->result_array();
        } else {
            $return = array();
        }
        exit(json_encode($return));
    }
    /**
     * 获取一级对应的二级类目
     *
     */
    public function get_cate(){
        $cate_id = $this->input->post('cate_one');
        $res = $this->center->get_cate($cate_id);

        if ($res){
            exit(json_encode($res));
        }else{
            exit(json_encode(array()));
        }
    }
    /**
     * 添加店铺
     */
    public function add_shop()
    {
        error_reporting(0);
        $plat_type = $this->input->post('plat_type');       // 店铺类型
        $shop_id = $this->input->post('shop_id');           // 店铺主旺旺
        $shop_url = $this->input->post('shop_url');         // 店铺地址
        $province = $this->input->post('province');         // 省
        $city = $this->input->post('city');                 // 市
        $county = $this->input->post('county');             // 区
        $send_addr = $this->input->post('send_addr');       // 详细地址
        $username = $this->input->post('username');         // 发件人姓名
        $phone = $this->input->post('phone');               // 发件人电话
        $goods_url = $this->input->post('goods_url');       // 商品地址
        $shop_name = $this->input->post('shop_name');       // 店铺名
        $copy_code = $this->input->post('copy_code');       // 验证码
        $user_id = $this->session->userdata('user_id');
        $shipping = $this->input->post('shipping');         // 店铺配送快递
        $palt_list = $this->conf->plat_list();              // 获取平台列表
        $check_arr = array();
        foreach ($palt_list as $key => $value) {
            array_push($check_arr, $value['name']);
        }
        if (empty($plat_type) || !in_array($plat_type, $check_arr)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '绑定状态有误')));
        }
        if (empty($province)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '省不能为空')));
        }
        if (empty($shop_url)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '商品链接不能为空')));
        }
        if (empty($shipping)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '请选择店铺快递的配送方式')));
        }
        if (empty($city)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '市不能为空')));
        }
        if (empty($county)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '区域不能为空')));
        }
        if (empty($username)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '区域不能为空')));
        }
        if (empty($phone)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '发件人姓名不能为空')));
        }
        if (empty($shop_name)) {
            exit(json_encode(array('is_success' => 0, 'msg' => '店铺名称不能为空')));
        }

        if (in_array($plat_type, array('taobao', 'tmall'))) {
            $this->load->helper('curl_helper');
            if (empty($shop_id)) {
                exit(json_encode(array('is_success' => 0, 'msg' => '店铺名称主旺旺不能为空')));
            }
            $check_sql2 = 'select count(1) cnt from rqf_bind_shop where shop_ww = ?';
            $res2 = $this->db->query($check_sql2, array($shop_id))->row();
            if ($res2->cnt > 0) {
                exit(json_encode(array('is_success' => 0, 'msg' => '该旺旺名称已被绑定！！')));
            }
            $content = curl_get($shop_url);
            preg_match('/<META\s+name="microscope-data"\s+content="([\w\W]*?)"/si', $content,$meta);
            $shop_info = $meta[1];
            if (empty($shop_info)) {
                exit(json_encode(array('is_success' => 0, 'msg' => '未获取到店铺信息')));
            }
            $info_list = explode('userId=', $shop_info);
            $shop_user_id = $info_list[1];
        } else {
            $shop_id = '';
            $shop_user_id = 0;
        }

        $check_sql = 'select count(1) cnt from rqf_bind_shop where user_id = ? and plat_name = ? and is_show = 1';
        $res = $this->db->query($check_sql, array($user_id, $plat_type));

        if ($res) {
            $check_res = $res->row();
        }

        if ($check_res && $check_res->cnt >= 10) {
            exit(json_encode(array('is_success' => 0, 'msg' => '绑定店铺超过十个！')));
        }
        if (in_array($plat_type, array('taobao', 'tmall'))) {
            $check_sql = "select count(1) cnt from rqf_bind_shop where shop_name = '{$shop_name}'";//天猫和淘宝的店铺名验重复
        } else {
            $check_sql = "select count(1) cnt from rqf_bind_shop where shop_name = '{$shop_name}' and plat_name = '{$plat_type}'";
        }
        $res = $this->db->query($check_sql);
        if ($res) {
            $check_res = $res->row();
        }
        if ($check_res && $check_res->cnt > 0) {
            exit(json_encode(array('is_success' => 0, 'msg' => '该店铺名已存在，请检查')));
        }
        if (in_array($plat_type, array('taobao', 'tmall', 'jd', 'pdd'))) {
            $result = $this->check_bind_shop_params($copy_code, $goods_url);
            if ($result == false) {
                exit(json_encode(array('is_success' => 0, 'msg' => '验证页面标题中未找到匹配的验证码！')));
            }
        }

        // 获取plat_id
        foreach ($palt_list as $k => $v) {
            if ($v['name'] == $plat_type) $plat_id = $k;
        }

        $time = time();
        $no_print = 0;      // 默认 打印
        $this->write_db = $this->load->database('write', true);



        $sql = "insert into rqf_bind_shop (`user_id`, `plat_name`, `plat_id`, `shop_name`, `shop_ww`, `shop_user_id`, `shop_url`, `check_url`, `province`, `city`, `region`, `address`, `send_name`, `send_mobile`, `shop_status`, `add_time`, `no_print`, `shipping_type`) 
                select '{$user_id}', '{$plat_type}', '{$plat_id}', '{$shop_name}', '{$shop_id}', '{$shop_user_id}', '{$shop_url}', '{$goods_url}', '{$province}', '{$city}', '{$county}', '{$send_addr}', '{$username}', '{$phone}', 0, '{$time}', '{$no_print}', '{$shipping}'
                from dual where not exists(select 1 from rqf_bind_shop where user_id = {$user_id} and shop_ww = '{$shop_id}' and shop_name = '{$shop_name}' and plat_id = '{$plat_id}')";
        $this->write_db->query($sql);
        $insert_id = $this->write_db->insert_id();
        $this->write_db->close();
        if ($insert_id) {
            exit(json_encode(array('is_success' => 1)));
        } else {
            exit(json_encode(array('is_success' => 0, 'msg' => '入库失败')));
        }
    }

    /**
     * @param $check_code
     * @param $goods_url
     * @return bool
     * 检查店铺验证码以及店铺商品地址
     */
    public function check_bind_shop_params($check_code='',$goods_url=''){
        $goods_link_arr = parse_url($goods_url);
        $goods_params = [];
        parse_str($goods_link_arr['query'], $goods_params);
        $web_content = mb_convert_encoding($this->curl_get_html($goods_url),'UTF-8','GBK');
        if (mb_strpos($web_content, $check_code) === false) {
            $web_content = mb_convert_encoding($this->curl_get_html($goods_url),'UTF-8','UTF-8');
            if (mb_strpos($web_content, $check_code) === false) {
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    public function curl_get_html($url){
        $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);  //0表示不输出Header，1表示输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        //关闭cURL资源，并且释放系统资源
        curl_close($curl);
        return $data;
    }

    /**
    * 用户信息
    */
    public function user_info()
    {
        $data = $this->data;
        $user_id = $this->session->userdata('user_id');
        $data['result'] = $this->center->userinfo($user_id);
        $this->load->view('center/user_info', $data);
    }

    /** */
     public function update_login_password() {
        $user_id = $this->session->userdata('user_id');
        #老密码
        $password         = addslashes($this->input->post('password'));
        #新密码
        $new_password     = addslashes($this->input->post('new_password'));
        #重复新密码
        $two_new_password = addslashes($this->input->post('two_new_password'));

        if(!preg_match("/^(?![^a-zA-Z]+$)(?!\D+$)/", $password)) {
            return Common::failure("密码必须为6-16位字母和数字!", "", "");
        }

        if(!preg_match("/^(?![^a-zA-Z]+$)(?!\D+$)/", $new_password)) {
            return Common::failure("密码必须为6-16位字母和数字!", "", "");
        }

        if(!preg_match("/^(?![^a-zA-Z]+$)(?!\D+$)/", $two_new_password)) {
            return Common::failure("密码必须为6-16位字母和数字!", "", "");
        }

        #获取用户信息中的老登录密码和颜值
        $userinfo = $this->center->userinfo($user_id);
        $salt     = $userinfo->salt;
        $l_pass   = $userinfo->login_password;

        #加密新的登录密码
        $the_new_password = MD5(MD5($new_password).$salt);

        #加密原的登录密码
        $the_password = MD5(MD5($password).$salt);

        if($password == $new_password || $the_new_password == $l_pass) {
            return Common::failure("新密码与旧密码相同!", "", "");
        }

        if($the_password != $l_pass) {
            return Common::failure("原密码输入错误!", "", "");
        }

        if($two_new_password != $new_password) {
            return Common::failure("2次输入密码不一致!", "", "");
        }

        $data = $this->center->update_login_password($user_id,$new_password);

        if($data) return Common::success("修改成功!", $data, "");
        return Common::failure("修改失败!", $data, "");

    }


    /**
    * 发送验证码
    */
    public function send_verification_code() {
        $user_id = $this->session->userdata('user_id');
        $mobile = $this->center->send_verification_code($user_id);
        #检查手机号码是否符合
        if(!Common::check_phone($mobile)){
            return Common::failure("手机号码不正确!", $mobile, "");
        }
        // 验证码检查
        $count = intval($this->input->post('count'));
        $captcha_response = $this->input->post('captcha_response');
        if ($count >= 3) {
            $captcha_check = $this->check_code($captcha_response);
            if (!$captcha_check) {
                exit(json_encode(['success' => false, 'msg' => '图形验证码验证失败！']));
            }
        }

        $sms_num = $this->get_sms_num($mobile);
        if($sms_num >= 5) return Common::failure("今日请求验证码超过次数限制，请联系客服!", $sms_num, "");
        #获取注册随机验证码
        $verification_code = $this->sms->verify_sms($mobile);
        if(json_decode($verification_code)->state == 2) {
            #写入MySQL
            //$this->insert_msg($mobile,$check_code);
            return Common::success("发送成功!", $sms_num, "");
        }
        return Common::failure("发送失败!", $sms_num, "");

    }

    /**
    * 获取当天发送的验证码次数
    */ 
    public function get_sms_num($mobile) {
        #检查手机号码是否符合
        if(!Common::check_phone($mobile)){
            return 0;
        }

        $sms_num = $this->center->sms_num($mobile);

        return $sms_num;
    }


    /**
    * 发送验证码写MySQL
    */
    public function insert_msg($mobile,$code) {
        if(!Common::check_phone($mobile)){
            return;
        }
        $code = addslashes($code);

        $res = $this->center->insert_msg($mobile,$code);
        if($res) return true;
        return;

    }

    /**
    * 检查验证码
    */
    public function check_verification_code()
    {
        $user_id = $this->session->userdata('user_id');
        # yzm输入错误次数
        $count = intval($this->input->post('count'));
        # yzm
        $vcode = addslashes($this->input->post('vcode'));
        # 图形验证码
        $captcha = trim($this->input->post('captcha_response'));
        # 验证码次数大于3次进行人机验证
        if ($count >= 3) {
            # 验证 人机验证
            $data1 = $this->check_code($captcha);
            if (!$data1) return Common::failure("图像验证码验证失败！", null, "");
        }
        # 验证 yzm
        if ($vcode == '') return Common::failure("验证码不能为空！", "", "");
        $data = $this->center->check_verification_code($user_id, $vcode);
        if (!$data) return Common::failure("短信验证码验证失败！", $data, "");

        return Common::success("验证成功！", $data, "");
    }

    /** 图形码验证 */
    private function check_code($captcha)
    {
        $this->load->library('captcha');
        return $this->captcha->verify(trim($captcha), false) ? 1 : 0;
    }

    /**
    * 修改提现密码
    */
    public function update_trade_password() {
        $user_id = $this->session->userdata('user_id');
        #新密码
        $new_password     = addslashes($this->input->post('new_password'));
        #2次输入新密码
        $two_new_password = addslashes($this->input->post('two_new_password'));

        if(empty(trim($new_password))) {
            return Common::failure("密码不能为空!", "", "");
        }

        if(!preg_match("/^(?![^a-zA-Z]+$)(?!\D+$)/", $new_password)) {
            return Common::failure("密码必须为6-16位字母和数字!", "", "");
        }

        if($new_password !== $two_new_password) return Common::failure("2次密码不一致!", "", "");

        #获取用户信息中的老登录密码和颜值
        $userinfo = $this->center->userinfo($user_id);
        $salt     = $userinfo->salt;
        $l_pass   = $userinfo->login_password;
        #加密新的提现密码
        $the_new_password = MD5(MD5($new_password).$salt);

        if($new_password == $the_new_password ) {
            return Common::failure("新密码与旧密码相同!", "", "");
        }

        $data  = $this->center->update_trade_password($user_id,$new_password);

        if($data) return Common::success("修改成功!", $data, "");

        return Common::failure("修改失败!", $data, "");   

    }

    /**
    * 修改微信
    */
    public function update_weixin() {
        $user_id = $this->session->userdata('user_id');
        #weixin
        $weixin  = addslashes($this->input->post('weixin'));

        $data  = $this->center->update_weixin($user_id,$weixin);

        if($data) return Common::success("修改成功!", $data, "");

        return Common::failure("修改失败!", $data, "");   

    }


     /**
    * 修改qq
    */
    public function update_qq() {
        $user_id = $this->session->userdata('user_id');
        #weixin
        $qq  = addslashes($this->input->post('qq'));

        $data  = $this->center->update_qq($user_id,$qq);

        if($data) return Common::success("修改成功!", $data, "");
        
        return Common::failure("修改失败!", $data, "");   

    }


}
