<?php

/**
 * 名称:配置模型
 * 担当:
 */
class Conf_Model extends CI_Model {

    /**
     * 会员价格列表
     */
    public function group_price_list()
    {
        return [
            3 => ['month' => 3, 'price' => 1500, 'title' => '', 'give_point' => 0, 'rewards' => 900, 'default' => false],
            6 => ['month' => 6, 'price' => 1800, 'title' => '赠送100元金币', 'give_point' => 100, 'rewards' => 1080, 'default' => false],
            12 => ['month' => 12, 'price' => 2100, 'title' => '赠送200元金币', 'give_point' => 200, 'rewards' => 1260, 'default' => false],
            24 => ['month' => 24, 'price' => 2400, 'title' => '赠送300元金币', 'give_point' => 300, 'rewards' => 1440, 'default' => true],
            48 => ['month' => 48, 'price' => 3000, 'title' => '赠送300元金币', 'give_point' => 300, 'rewards' => 1800, 'default' => false]
        ];
    }

    /**
     * 购买金币列表
     */
    public function recharge_point_list() {

        return [100, 500, 1000, 2000];
    }

    /**
     * 支付方式列表
     */
    public function get_pay_type_list() {

        return [
            ['pay_id' => 18, 'pay_title' => '使用<span class="red">支付宝</span>支付<span class="f-pl5">(支付宝账号)</span>'],
            ['pay_id' => 19, 'pay_title' => '使用<span class="red">微信</span>支付<span class="f-pl5">(微信账号)</span>'],
            ['pay_id' => 1, 'pay_title' => '使用<span class="red">工商银行</span>支付'],
            ['pay_id' => 2, 'pay_title' => '使用<span class="red">建设银行</span>支付'],
            ['pay_id' => 3, 'pay_title' => '使用<span class="red">中国银行</span>支付'],
            ['pay_id' => 4, 'pay_title' => '使用<span class="red">农业银行</span>支付'],
            ['pay_id' => 5, 'pay_title' => '<span class="red">更多银行</span>支付']
        ];
    }

    /**
     * 平台列表
     */
    public function plat_list() {

        return [
            '1' => ['pname'=>'淘宝', 'name'=>'taobao', 'url'=>'www.taobao.com', 'cnt'=>0],
            '2' => ['pname'=>'天猫', 'name'=>'tmall', 'url'=>'www.tmall.com', 'cnt'=>0],
            // '3' => ['pname'=>'1号店', 'name'=>'yhd', 'url'=>'www.yhd.com', 'cnt'=>0],
            '4' => ['pname'=>'京东', 'name'=>'jd', 'url'=>'www.jd.com', 'cnt'=>0],
            // '5' => ['pname'=>'聚美', 'name'=>'jumei', 'url'=>'', 'cnt'=>0],
            // '6' => ['pname'=>'亚马逊', 'name'=>'amazon', 'url'=>'', 'cnt'=>0],
            // '7' => ['pname'=>'当当', 'name'=>'dangdang', 'url'=>'www.dangdang.com', 'cnt'=>0],
            // '8' => ['pname'=>'拍拍', 'name'=>'paipai', 'url'=>'', 'cnt'=>0],
            // '9' => ['pname'=>'阿里巴巴', 'name'=>'alibaba', 'url'=>'', 'cnt'=>0],
            // '10' => ['pname'=>'蘑菇街', 'name'=>'mogujie', 'url'=>'www.mogujie.com', 'cnt'=>0],
            // '11' => ['pname'=>'美丽说', 'name'=>'meilishuo', 'url'=>'', 'cnt'=>0],
            // '12' => ['pname'=>'国美', 'name'=>'guomei', 'url'=>'', 'cnt'=>0],
            // '13' => ['pname'=>'苏宁', 'name'=>'suning', 'url'=>'', 'cnt'=>0]，
            '14' => ['pname' => '拼多多', 'name' => 'pdd', 'url' => 'mobile.yangkeduo.com', 'cnt' => 0],
        ];
    }

    /**
     * 活动类型
     */
    public function trade_type_list($plat_id) {
        if (in_array($plat_id, [1, 2])) {
            return [
                '1' => ['type_name' => '普通搜索订单&nbsp;（支持指定文字、关键词、图文、视频评价）', 'limit_point' => '3.80', 'comment' => '买家通过商家提供的关键词，从淘宝，天猫，手淘APP通过自然搜索入店找到商品，之后完成下单等操作；确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品评价标签。'],
                '90' => ['type_name' => '超级搜索订单&nbsp;（竞品流量劫持；超级精准打标；手淘首页流量截取；双关键词（长尾词搜索，核心词成交））', 'limit_point' => '4.80', 'comment' => '买家预先通过商家提供的多个指定的关键词与商品进行浏览之后，再通过指定的关键词找到自己的商品进行下单购买，此活动可以通过优秀的竞争对手产品进行强行打标，然后获取更大的权重值！'],
                '2' => ['type_name' => '回访订单（首日加购、次日下单）', 'limit_point' => '7.80', 'comment' => '买家第一天通过商家提供的关键词，从淘宝，天猫，手淘APP通过自然搜索入店找到商品，然后加入购物车；第二天买手通过购物车，完成下单等操作；确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品加购权重及加购购买权重。'],
                // '3' => ['type_name' => '普通搜索订单+图文好评', 'limit_point' => '14.40', 'comment' => '买家通过商家提供的关键词，从淘宝，天猫，手淘APP通过自然搜索入店找到商品，之后完成下单等操作；确认收货后，会根据商家提供的好评图片进行评价，有利于优化评价内容及转化率。'],
                '4' => ['type_name' => '聚划算', 'limit_point' => '3.80', 'comment' => '买家通过聚划算入店，找到商家的聚划算商品后下单；买家确认收货后，会根据商家的要求进行好评等操作。'],
                '5' => ['type_name' => '淘抢购', 'limit_point' => '3.80', 'comment' => '买家通过手淘APP入店，找到商家的淘抢购商品后下单；买家确认收货后，会根据商家的要求进行好评等操作。'],
                '10' => ['type_name' => '流量订单', 'limit_point' => '0.8', 'comment' => '买家通过任务关键词进店，完成搜索、点击、收藏、加购等操作，适合多数类目商品。快速提高关键词无线端展现，提升无线端排名和收藏加购等人气权重，带来真实流量。'],
                //'111' => ['type_name' => '双十一定金任务', 'limit_point' => '6.80', 'comment' => '买家通过任务关键词进店，完成搜索、点击、收藏、支付定金等操作，买家支付完成之后，无需支付尾款，并直接给用户进行返款，因为定金不退。'],
                //'112' => ['type_name' => '双十一定金+尾款任务', 'limit_point' => '10.80', 'comment' => '买家通过任务关键词进店，完成搜索、点击、收藏、支付定金+尾款等操作，买家支付完成之后，双十一当天支付尾款。确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品加购权重及加购购买权重'],
                //'114' => ['type_name' => '双十一回访订单', 'limit_point' => '10.80', 'comment' => '买家第一天通过商家提供的关键词，从淘宝，天猫，手淘APP通过自然搜索入店找到商品，然后加入购物车；双十一当天买手通过购物车，完成下单等操作；确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品加购权重及加购购买权重。'],
                //'115' => ['type_name' => '双十一退款订单（默认 2019-11-10 23:57:00 发出）', 'limit_point' => '5.80', 'comment' => '买家双十一当天通过任务关键词进店，完成搜索、点击、收藏、加购，下单等操作，次日进行退款操作，此任务有效提升双十一当天赛马坑产排名'],
                //'211' => ['type_name' => '双十二定金任务', 'limit_point' => '6.80', 'comment' => '买家通过任务关键词进店，完成搜索、点击、收藏、支付定金等操作，买家支付完成之后，无需支付尾款，并直接给用户进行返款，因为定金不退。'],
                //'212' => ['type_name' => '双十二定金+尾款任务', 'limit_point' => '10.80', 'comment' => '买家通过任务关键词进店，完成搜索、点击、收藏、支付定金+尾款等操作，买家支付完成之后，双十二当天支付尾款。确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品加购权重及加购购买权重'],
                //'214' => ['type_name' => '双十二回访订单', 'limit_point' => '10.80', 'comment' => '买家第一天通过商家提供的关键词，从淘宝，天猫，手淘APP通过自然搜索入店找到商品，然后加入购物车；双十二当天买手通过购物车，完成下单等操作；确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品加购权重及加购购买权重。'],
                //'215' => ['type_name' => '双十二退款订单（默认 2019-12-11 23:57:00 发出）', 'limit_point' => '5.80', 'comment' => '买家双十二当天通过任务关键词进店，完成搜索、点击、收藏、加购，下单等操作，次日进行退款操作，此任务有效提升双十二当天赛马坑产排名'],
            ];
        } elseif ($plat_id == '4') {
            return [
                '6' => ['type_name' => '普通搜索订单&nbsp;（支持指定文字、关键词、图文、视频评价）', 'limit_point' => '5.70', 'comment' => '买家通过商家提供的关键词，从京东，手机APP通过自然搜索入店找到商品，之后完成下单等操作；确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品评价标签。'],
                '7' => ['type_name' => '链接直拍', 'limit_point' => '4.70', 'comment' => '买家通过商家提供的商家链接，直接进入商品页，完成浏览、下单等操作；确认收货后，会根据商家提供的好评关键词进行评价。'],
                '10' => ['type_name' => '流量订单', 'limit_point' => '0.8', 'comment' => '买家通过任务关键词进店，完成搜索、点击、收藏、加购等操作，适合多数类目商品。快速提高关键词无线端展现，提升无线端排名和收藏加购等人气权重，带来真实流量。']
            ];
        } elseif ($plat_id == '14') {
            return [
                '140' => ['type_name' => '普通搜索订单&nbsp;（支持指定文字、关键词、图文、视频评价）', 'limit_point' => '3.0', 'comment' => '买家通过商家提供的关键词，从拼多多手机APP通过自然搜索入店找到商品，之后完成下单等操作；确认收货后，会根据商家提供的好评关键词进行评价，有利于优化商品评价标签。'],
            ];
        }
    }

    /**
     * 活动类型名称列表
     */
    public function trade_type_name_list() {
        return [
            '1' => '普通搜索订单',
            '90' => '超级搜索订单',
            '2' => '回访订单',
            '3' => '普通搜索订单+图文好评',
            '4' => '聚划算',
            '5' => '淘抢购',
            '6' => '普通搜索订单',
            '7' => '链接直拍',
	        '10' => '流量订单',
            '111' => '双十一定金任务',
            '112' => '双十一定金+尾款任务',
            '113' => '双十一当天任务',
            '114' => '双十一回访订单',
            '115' => '双十一退款订单',
            '140' => '拼多多搜索订单',
            '211' => '双十二定金任务',
            '212' => '双十二定金+尾款任务',
            '213' => '双十二当天任务',
            '214' => '双十二回访订单',
            '215' => '双十二退款订单',
        ];
    }

    /**
     * 增值服务列表
     */
    public function get_trade_service_list() {

        return [
            'quick_refund' => ['name' => '快速返款', 'description' => '选择此服务，试客完成试用后，平台将直接使用押金为您操作返款，商家无需耗费时间、人力处理退款，减少押款周期'],
            'sys_select' => ['name' => '系统代抽奖', 'description' => '系统将自动为商家抽选出更适合商家商品品类的试客，节约商家运营成本和时间，同时有利于试客中奖分布更广泛'],
            'set_location' => ['name' => '精准投放', 'description' => '选择此服务后，琳琅将根据地区、性别、年龄段、收入、职业筛选准确的人群申请试用'],
            'add_visit' => ['name' => '提升商品人气权重', 'description' => '选择此服务后，连续申请3天完成试用申请未中奖的试客将获得平台赠送的金币，试客参与试用的积极性越大'],
            'first_check' => ['name' => '优先审核', 'description' => '选择此服务后，琳琅将会优先审核您发布的试用活动'],
            'plat_check' => ['name' => '活动审核', 'description' => '选择此服务后，9-24点发布的试用活动，琳琅会在30分钟内审核完毕，若活动发布有问题，系统会驳回让您修改，客服会第一时间与您联系'],
            'recommend' => ['name' => '首页推荐', 'description' => '选择此项服务后，您发布的试用商品将在活动上线48小时后每隔15分钟将轮播展示在琳琅首页【平台推荐】和商品列表页【推荐试用】的位置，直至试用活动结束'],
            'good_comment' => ['name' => '关键字好评', 'description' => '选择此服务后，您可以设置试客对商品好评的内容范围'],
            'pic_comment' => ['name' => '设置图文好评', 'description' => '选择此服务后，中奖试客需进行图文好评，商家不可强制要求试客真人露脸上镜，试客可选择只拍商品照片'],
            'user_show' => ['name' => '买家秀', 'description' => '选择此服务后，试客上传图片必须真人露脸上镜（建议服饰类、箱包类商家选择此项），中奖试客均为平台挑选的优质试客，优质的买家秀能极大的提升宝贝转化率'],
            'extend_cycle' => ['name' => '延长试用周期', 'description' => '选择此服务不会影响试客申请人数，仅推荐重复购买率低的商品使用，如：家居、家电、高价值的商品等'],
            'chase_service' => ['name' => '追评基础服务', 'description' => '试客会在确认好评后的5-10天内随机时间对您的试用商品追加评价'],
            'chase_good_comment' => ['name' => '追评关键字好评', 'description' => '选择此服务后，系统会将以下其中1个关键词随机分配给1名中奖试客，试客评价内容必须含有商家设定的关键词'],
            'chase_pic_show' => ['name' => '追评图文好评', 'description' => '选择此服务后，试客会在淘宝好评中上传3-5张商品照片'],
            'chase_add_favour' => ['name' => '追评额外收藏宝贝', 'description' => '选择此服务后，试客将额外收藏商家店铺中的商品，从而提高店铺回访权重和宝贝收藏权重'],
            'chase_first_check' => ['name' => '追评优先审核', 'description' => '选择此服务后，后台客服人员优先审核你发布的追评活动'],
            'delay_days' => ['name' => '延迟抽奖', 'description' => '选择此服务后，系统会对你的活动延迟抽奖'],
            'trade_ask' => ['name' => '商品问大家', 'description' => '选择此服务，试客将按要求进行提问/回答，优化商品问大家，将有助于手淘置顶提问和回答，大幅提升商品转化率，减少客服咨询'],
            'reward_gold' => ['name' => '加赏赏金', 'description' => '增加金币数越多，试客完成活动的积极性越大，极大提高试客申请活动的积极性'],
            'reward_debris' => ['name' => '试用碎片激励', 'description' => '选择此服务，系统将给予所有申请试客3个试用碎片(1碎片=0.1元)，可刺激更多试客前来关注申请']
        ];
    }

    /**
     * 活动状态列表
     */
    public function trade_status_list() {

        return [
            '0' => '未支付',
            '1' => '已支付',
            '2' => '进行中',
            '3' => '已完成',
            '4' => '已接完',
            '5' => '审核不通过',
            '6' => '暂停中',
            // '7' => '修改待审核',
            '9' => '已取消'
        ];
    }

    /**
     * 包邮类型列表
     */
    public function get_post_type_list() {

        return [
            '1' => ['name' => '包邮试用', 's_name' => '包邮'],
            '0' => ['name' => '付邮试用', 's_name' => '付邮']
        ];
    }

    /** 快递配送类型 */
    public function get_shipping_type_list($type = null) {
        $list = [
            'yto' => ['name' => '圆通快递', 'price' => 3, 'default' => 1, 'is_show' => 1],
            'sto' => ['name' => '申通快递', 'price' => 3, 'default' => 0, 'is_show' => 1],
            'yunda' => ['name' => '韵达快递', 'price' => 3, 'default' => 0, 'is_show' => 0],
            'zto' => ['name' => '中通快递', 'price' => 3, 'default' => 0, 'is_show' => 1],
            'self' => ['name' => '自发快递赠送小礼品', 'price' => 0, 'default' => 0, 'is_show' => 1],
        ];

        if (!$type) {
            return $list;
        } else {
            if (array_key_exists($type, $list)) {
                return $list[$type];
            } else {
                return false;
            }
        }
    }

    /**
     * 押金类型
     */
    public function deposit_type_list() {
        /**
         * 100+:押金增加
         * 200+:押金减少
         * 300+:押金冻结
         * 400+:押金解冻
         */
        return [
            '100' => '报名活动充值押金',
            '101' => '充值押金',
            '102' => '子活动结算',
            '103' => '子活动本金返款',
            '104' => '充值押金(转金币)',
            '105' => '充值押金(转会员)',
            '106' => '充值押金(后台)',
            '107' => '补偿快递费',
            '108' => '迁移押金',
            '110' => '活动完成返还本金',
            '111' => '流水号充值押金',
            '116' => '系统结算',
            '200' => '押金转金币',
            '201' => '充值金币扣减押金',
            '202' => '子活动结算补扣押金',
            '203' => '购买会员扣减押金',
            '204' => '扣减押金(后台)',
            '205' => '押金转金币(后台)',
            '300' => '报名活动冻结押金',
            '301' => '充值金币冻结押金',
            '302' => '提现冻结押金',
            '303' => '购买会员冻结押金',
            '304' => '冻结押金(后台)',
            '305' => '提现冻结本金',
            '400' => '报名活动解冻押金',
            '401' => '充值金币解冻押金',
            '402' => '购买会员解冻押金',
            '403' => '解冻押金(后台)',
            '404' => '取消活动解冻押金',
            '405' => '部分支付超时解冻押金',
            '406' => '活动结算解冻押金',
            '407' => '子活动完成解冻押金',
            '408' => '撤销提现解冻押金',
            '409' => '审核不通过解冻押金',
            '410' => '子活动撤销解冻押金',
            '500' => '子活动结算扣减冻结',
            '501' => '提现成功扣减冻结',
            '502' => '扣减冻结(后台)'
        ];
    }

    /**
     * 金币类型
     */
    public function point_type_list() {
        /**
         * 100+:金币增加
         * 200+:金币减少
         * 300+:金币冻结
         * 400+:金币解冻
         * 500+:扣减冻结
         */
        return [
            '100' => '押金转金币',
            '101' => '充值金币',
            '102' => '充值金币(后台)',
            '103' => '购买会员赠送金币',
            '104' => '系统赠送金币',
            '105' => '完成活动奖励金币',
            '106' => '注册赠送20金币',
            '107' => '徒弟完成活动奖励',
            '108' => '徒孙完成活动奖励',
            '109' => '押金转金币(后台)',
            '110' => '迁移金币',
            '111' => '流水号充值金币',
            '112' => '邀请完成任务奖励',
            '113' => '邀请完成任务奖励',
            '116' => '系统结算',
            '200' => '放弃活动扣减金币',
            '201' => '购买会员扣减金币',
            '202' => '购买店铺扣减金币',
            '203' => '扣减金币(后台)',
            '204' => '转夺宝币扣减金币',
            '300' => '报名活动冻结金币',
            '301' => '购买会员冻结金币',
            '303' => '冻结金币(后台)',
            '304' => '接手活动冻结金币',
            '305' => '提现冻结金币',
            '306' => '追加增值服务冻结金币',
            '400' => '报名活动解冻金币',
            '401' => '购买会员解冻金币',
            '403' => '解冻金币(后台)',
            '404' => '取消活动解冻金币',
            '405' => '部分支付超时解冻金币',
            '406' => '活动结算解冻金币',
            '407' => '活动完成解冻金币',
            '408' => '取消活动解冻金币',
            '409' => '活动审核不通过解冻金币',
            '410' => '撤销提现解冻金币',
            '411' => '子活动撤销解冻金币',
            '500' => '扣除活动冻结金币(后台)',
            '501' => '提现成功扣减冻结',
            '502' => '取消活动扣减冻结金币',
            '503' => '扣除冻结金币(后台)'
        ];
    }

    /**
     * 子活动状态列表
     */
    public function order_status_list() {

        /**
         * 特殊状态描述:
         * 97:超时取消 超过2小时未完成
         * 98:已取消 后台取消活动
         * 99:已放弃 买手主动放弃活动
         */
        return [
            '0' => '已接手，待下单',
            '1' => '已下单，待发货',
            '2' => '已付款，待发货',
            '3' => '已发货，待收货',
            '4' => '已收货，待退款',
            '5' => '已返款，待买手确认',
            '6' => '买手驳回订单',
            '7' => '已完成',
            '97' => '超时取消',
            '98' => '已取消',
            '99' => '已放弃'
        ];
    }


    /**
     * 提现状态列表
     */
    public function with_status_list() {

        return [
            '0' => '待审核',
            '1' => '已审核',
            '2' => '已完成',
            '3' => '提现失败',
            '4' => '返款中',
            '5' => '返款失败',
            '6' => '已撤销'
        ];
    }
	
    /**
     * 夺宝币类型
     * @return type
     */
    public function get_snatch_type(){
        return [
            '1'=>'提现转夺宝币',
            '2'=>'真人秀转夺宝币',
            '3'=>'追评转夺宝币',
        ];
    }

    /**
     * 分时发布列表
     */
    public function interval_list() {

        return [
            '10m'=>['name'=>'10分钟', 'strtotime'=>'+10 minute'],
            '15m'=>['name'=>'15分钟', 'strtotime'=>'+15 minute'],
            '30m'=>['name'=>'30分钟', 'strtotime'=>'+30 minute'],
            '1h'=>['name'=>'1小时', 'strtotime'=>'+1 hour'],
            '2h'=>['name'=>'2小时', 'strtotime'=>'+2 hour'],
            '3h'=>['name'=>'3小时', 'strtotime'=>'+3 hour'],
            '5h'=>['name'=>'5小时', 'strtotime'=>'+5 hour'],
            '8h'=>['name'=>'8小时', 'strtotime'=>'+8 hour'],
            '12h'=>['name'=>'12小时', 'strtotime'=>'+12 hour'],
            '24h'=>['name'=>'24小时', 'strtotime'=>'+24 hour'],
            '2d'=>['name'=>'2天', 'strtotime'=>'+2 day'],
            '4d'=>['name'=>'4天', 'strtotime'=>'+4 day']
        ];
    }

   
    /** 充值支付宝列表 **/
    public function get_recharge_alipay_account()
    {
        return [
//          ['qrcode' => '/static/imgs/alipay/qr_20190603_d.png', 'name' => '于玲玲'],
            // ['qrcode' => '/static/imgs/alipay/qr_20190603_t.png', 'name' => '汤锋'],
            // ['qrcode' => '/static/imgs/alipay/qr_20190603_w.png', 'name' => '王园园'],
            ['qrcode' => '/static/imgs/alipay/qr_20190603_y.png', 'name' => '李永超'],
//          ['qrcode' => '/static/imgs/alipay/QR_2.png', 'name' => '王光明'],
//          ['qrcode' => '/static/imgs/alipay/QR.png', 'name' => '谢小标'],
            // ['qrcode' => '/static/imgs/alipay/QR_3.png', 'name' => '林心怡'],
        ];
    }

    public function get_recharge_wx_account()
    {
        return [
            ['qrcode' => '/static/imgs/alipay/wx_z.png', 'name' => '祝玲玲'],
        ];
    }

    /** 中信银行卡列表 **/
    public function get_recharge_bank_account()
    {
        return [
            // ['account' => '6217711118678307', 'account_name' => '姜琳', 'bank_name' => '中信银行'],
            ['account' => '6217711118678299', 'account_name' => '李永超', 'bank_name' => '中信银行'],
        ];
    }

    /** 信用卡充值列表 **/
    public function get_bank_pay_list()
    {
        $list = [
            '1' => ['bank_code' => 'ICBC', 'title' => '工商银行', 'img' => '/static/imgs/account/ICBC.png', 'credit_cart' => 1, 'is_show' => 1],
            '2' => ['bank_code' => 'CCB', 'title' => '建设银行', 'img' => '/static/imgs/account/CCB.png', 'credit_cart' => 1, 'is_show' => 0],
            '3' => ['bank_code' => 'BOC', 'title' => '中国银行', 'img' => '/static/imgs/account/BOC.png', 'credit_cart' => 1, 'is_show' => 1],
            '4' => ['bank_code' => 'ABC', 'title' => '农业银行', 'img' => '/static/imgs/account/ABC.png', 'credit_cart' => 1, 'is_show' => 1],
            '5' => ['bank_code' => 'CMB', 'title' => '招商银行', 'img' => '/static/imgs/account/CMB.png', 'credit_cart' => 1, 'is_show' => 0],
            '6' => ['bank_code' => 'CITIC', 'title' => '中信银行', 'img' => '/static/imgs/account/CITIC.png', 'credit_cart' => 1, 'is_show' => 1],
            '7' => ['bank_code' => 'CMBC', 'title' => '民生银行', 'img' => '/static/imgs/account/CMBC.png', 'credit_cart' => 1, 'is_show' => 0],
            '8' => ['bank_code' => 'CEB', 'title' => '光大银行', 'img' => '/static/imgs/account/CEB.png', 'credit_cart' => 1, 'is_show' => 0],
            '10' => ['bank_code' => 'SPDB', 'title' => '上海浦发银行', 'img' => '/static/imgs/account/SPDB.png', 'credit_cart' => 1, 'is_show' => 1],
            '11' => ['bank_code' => 'PAB', 'title' => '平安银行', 'img' => '/static/imgs/account/SPABANK.png', 'credit_cart' => 1, 'is_show' => 1],
//          '12' => ['bank_code' => 'BCOM', 'title' => '交通银行', 'img' => '/static/imgs/account/COMM.png', 'credit_cart' => 1, 'is_show' => 1],
            '13' => ['bank_code' => 'CIB', 'title' => '兴业银行', 'img' => '/static/imgs/account/CIB.png', 'credit_cart' => 1, 'is_show' => 0],
            '14' => ['bank_code' => 'PSBC', 'title' => '中国邮政银行', 'img' => '/static/imgs/account/PSBC.png', 'credit_cart' => 1, 'is_show' => 1],
        ];

        return $list;
    }
    
    /**
     * 评价类型列表
     */
    public function get_eval_type_list()
    {
        return [
            '0' => '自由好评',
            '1' => '默认好评',
            '2' => '关键词好评',
            '3' => '自定义好评',
            '4' => '图文好评',
            '5' => '视频评价'
        ];
    }
}
