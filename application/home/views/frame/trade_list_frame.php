<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico" />
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css?v=<?= VERSION_TXT ?>" />
    <link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>" />
    <link rel="stylesheet" href="/static/css/center.css?v=<?= VERSION_TXT ?>" />
    <link rel="stylesheet" href="/static/css/layout.css?v=<?= VERSION_TXT ?>" />
    <link rel="stylesheet" href="/static/css/business_center.css?v=<?= VERSION_TXT ?>" />
    <script type="text/javascript" src="/static/js/jquery-1.12.4.min.js?v=<?= VERSION_TXT ?>"></script>
    <script language="javascript" src="/static/bootstrap/js/bootstrap.min.js?v=<?= VERSION_TXT ?>"></script>
    <script language="javascript" src="/static/My97DatePicker/WdatePicker.js?v=<?= VERSION_TXT ?>"></script>
    <title>商家活动列表-<?php echo PROJECT_NAME; ?></title>
    <style>
        #myTab_two.nav-tabs>li.active>a, #myTab_two.nav-tabs>li>a:hover{
            border-top:0px solid #228BEC;
            color: white;
            background-color: #ff6b71;
        }
        #myTab_two.nav-tabs>li.active>a, #myTab_two.nav-tabs>li>a:hover {
            background-color: #ed702c;
            border-top: 3px solid #ed702c;
            font-size: 16px;
            color: white;
            /* background-color: #fff; */
            /* border-top: 3px solid #228BEC; */
        }
        .task_list_search .search_sub {
            background-color: #ff6b71;
            border: 1px solid #ff6b71;
        }
        #myTab_two.nav-tabs{
            background-color: white;
        }
    </style>
</head>
<body>
<div class="contain" style="width: 947px;">
    <div class="box_3">
        <ul id="myTab_two" class="nav nav-tabs">
            <li class="<?= ($t == 1) ? 'active':''; ?>"><a href="/frame/trade_list_frame">全部的（<span class="<?= $trade_cnts['all'] ? 'red':''; ?>"><?= $trade_cnts['all']; ?></span>）</a></li>
            <li class="<?= ($t == 2) ? 'active':''; ?>"><a href="/frame/trade_list_frame/2">进行中（<span class="<?= $trade_cnts['ongoing'] ? 'red':''; ?>"><?= $trade_cnts['ongoing']; ?></span>）</a></li>
            <li class="<?= ($t == 3) ? 'active':''; ?>"><a href="/frame/trade_list_frame/3">已完成（<span class="<?= $trade_cnts['finished'] ? 'red':''; ?>"><?= $trade_cnts['finished']; ?></span>）</a></li>
            <li class="<?= ($t == 4) ? 'active':''; ?>"><a href="/frame/trade_list_frame/4">待付款（<span class="<?= $trade_cnts['unpayed'] ? 'red':''; ?>"><?= $trade_cnts['unpayed']; ?></span>）</a></li>
            <li class="<?= ($t == 5) ? 'active':''; ?>"><a href="/frame/trade_list_frame/5">审核不通过（<span class="<?= $trade_cnts['unchecked'] ? 'red':''; ?>"><?= $trade_cnts['unchecked']; ?></span>）</a></li>
        </ul>
        <div class="tab-content">
            <div class="task_list_search">
                <form action="/frame/trade_list_frame/<?php echo $t; ?>" method="get">
                    <span>平台：</span>
                    <select name="plat_id" class="form-control" style="width: 108px;">
                        <option>全部</option>
                        <?php foreach ($plat_list as $k=>$v): ?>
                            <option value="<?php echo $k; ?>" <?php if ($k == $plat_id): ?>selected<?php endif; ?>><?php echo $v['pname']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span>店铺：</span>
                    <select name="shop_id" class="form-control" style="width:128px;">
                        <option>全部</option>
                        <?php foreach ($shop_list as $v): ?>
                            <option value="<?php echo $v->id; ?>" <?php if ($v->id == $shop_id): ?>selected<?php endif; ?>><?php echo $v->shop_name; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span>类型：</span>
                    <select name="trade_type" class="form-control" style="width: 142px;">
                        <option>全部</option>
                        <?php foreach ($trade_type_name_list as $k=>$v): ?>
                            <option value="<?php echo $k; ?>" <?php if ($k == $trade_type): ?>selected<?php endif; ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span>状态：</span>
                    <select name="sub_status" class="form-control" style="width: 108px;">
                        <option>全部</option>
                        <option value="1" <?php if ($sub_status == 1): ?>selected<?php endif; ?>>未接单</option>
                        <option value="2" <?php if ($sub_status == 2): ?>selected<?php endif; ?>>进行中</option>
                        <option value="3" <?php if ($sub_status == 3): ?>selected<?php endif; ?>>待发货</option>
                        <option value="4" <?php if ($sub_status == 4): ?>selected<?php endif; ?>>待退款</option>
                        <option value="5" <?php if ($sub_status == 5): ?>selected<?php endif; ?>>已完成</option>
                    </select>
                    <p style="height:10px;"></p>
                    <select name="ttime" class="form-control" style="margin-right:0;width:108px;"><option <?= ($ttime == '1') ? 'selected': ''; ?> value="1">发布时间：</option><option <?= ($ttime == '2') ? 'selected': ''; ?> value="2">支付时间：</option></select>
                    <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?php echo $st; ?>" />-<input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?php echo $et; ?>" />

                    <!--<p style="height:10px;"></p>-->
                    <span>评价类型：</span>
                    <select name="eval_type" class="form-control" style="width: 108px;">
                        <option>全部</option>
                        <option value="1" <?php if ($eval_type == 1): ?>selected<?php endif; ?>>自由好评</option>
                        <option value="2" <?php if ($eval_type == 2): ?>selected<?php endif; ?>>默认好评</option>
                        <option value="3" <?php if ($eval_type == 3): ?>selected<?php endif; ?>>关键词好评</option>
                        <option value="4" <?php if ($eval_type == 4): ?>selected<?php endif; ?>>自定义好评</option>
                        <option value="5" <?php if ($eval_type == 5): ?>selected<?php endif; ?>>图文好评</option>
                        <option value="6" <?php if ($eval_type == 6): ?>selected<?php endif; ?>>视频评价</option>
                    </select>

                    <p style="height:10px;"></p>
                    <span>高级搜索：</span>
                    <select name="key" class="form-control" style="width: 108px;">
                        <option>请选择</option>
                        <option value="1" <?php if ($key == 1): ?>selected<?php endif; ?>>活动编号</option>
                        <option value="2" <?php if ($key == 2): ?>selected<?php endif; ?>>子活动号</option>
                        <option value="3" <?php if ($key == 3): ?>selected<?php endif; ?>>订单号</option>
                        <option value="4" <?php if ($key == 4): ?>selected<?php endif; ?>>买号</option>
                        <option value="5" <?php if ($key == 5): ?>selected<?php endif; ?>>商品名称</option>
                        <option value="6" <?php if ($key == 6): ?>selected<?php endif; ?>>运单号</option>
                    </select>
                    <input name="val" type="text" class="form-control task_search" value="<?php echo $val; ?>" />
                    <input type="submit" class="btn search_sub" value="查&nbsp;询" />
                </form>
            </div>
            <div class="tab-pane fade in active" id="all_task">
                <table class="table task_table">
                    <tr>
                        <th width="25%">商品</th>
                        <th width="25%">活动编号</th>
                        <th width="40%">状态分布</th>
                        <th width="10%"></th>
                    </tr>
                    <?php if($t == '5'): ?>
                        <?php foreach ($res as $v): ?>
                            <tr>
                                <td>
                                    <h6><img src="/static/imgs/icon/<?= $v->bind_shop->plat_name; ?>.png"><?= $v->bind_shop->shop_name; ?></h6>
                                    <div class="row">
                                        <div class="col-xs-4"><img src="<?= $v->trade_item->goods_img. '?imageView/3/w/128/h/128' ?>"/></div>
                                        <div class="col-xs-8"><?= $v->trade_item->goods_name; ?></div>
                                    </div>
                                </td>
                                <td>
                                    <p style="margin: 20px 5px 0 5px;">发布数量：<span class="red"><?= $v->total_num; ?></span>单</p>
                                    <p style="margin: 0 5px;">使用押金：<span class="red"><?= $v->trade_deposit; ?></span>元（已退款）</p>
                                    <p style="margin: 0 5px;">使用金币：<span class="red"><?= $v->trade_point; ?></span>（已返还）</p>
                                </td>
                                <td>
                                    <dl>
                                        <dt>
                                            <span>审核时间：</span><span class="red"><?= date('Y-m-d H:i', $v->check_time); ?></span><br />
                                            <span>审核不通过原因：</span>
                                            <?php foreach ($v->trade_uncheck as $k1=>$v1): ?>
                                                <p><?= $v1->reason; ?></p>
                                            <?php endforeach; ?>
                                        </dt>
                                    </dl>
                                </td>
                                <td>
                                    <div class="up_reset">
                                        <a href="/trade/step/<?php echo $v->id; ?>" class="failed_update" target="_blank">修改任务</a>
                                        <a href="/trade/uncheck_cancel/<?php echo $v->id; ?>" class="failed_reset">取消发布</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($res as $v): ?>
                            <tr>
                                <td>
                                    <h6><img src="/static/imgs/icon/<?= $v->bind_shop->plat_name; ?>.png"><?= $v->bind_shop->shop_name; ?></h6>
                                    <div class="row">
                                        <div class="col-xs-4"><img src="<?= ($v->trade_item->goods_img) ? $v->trade_item->goods_img.'?imageView/3/w/128/h/128' : ''; ?>" /></div>
                                        <div class="col-xs-8 overfloat-hidden-3"><?= $v->trade_item->goods_name; ?></div>
                                    </div>
                                </td>
                                <td>
                                    <p><?= $trade_type_name_list[$v->trade_type] ?>：<a href="/detail/trade/<?= $v->id; ?>" target="_blank"><?= $v->trade_sn; ?></a></p>
                                    <p>发布时间：<?= date('Y-m-d H:i',$v->created_time); ?></p>
                                    <p>支付时间：<?= ($v->pay_time <= 0) ? '--' : date('Y-m-d H:i',$v->pay_time); ?></p>
                                </td>
                                <td>
                                    <dl>
                                        <dt>
                                            活动状态：<span class="red"><?= $v->status_text; ?></span>
                                            <?php if ($v->trade_status == '0'): ?>
                                                <a href="javascript:;" class="blue_light nonpayment_cancel_btn" trade_id="<?= $v->id; ?>">取消未支付活动</a>
                                            <?php elseif ($v->trade_status == '2' && ($v->total_num > $v->apply_num)): ?>
                                                <a href="javascript:;" class="blue_light revoke_task_btn" trade_id="<?= $v->id; ?>">撤销未接单活动</a>
                                                <span class="grey">|</span>
                                                <a href="javascript:;" class="blue_dark accelerate_task_complete" surplus_num="<?= $v->order_cnts->not_pay + $v->order_cnts->not_started; ?>" order_id="<?= $v->id; ?>">加快活动进度</a>
                                            <?php elseif ($v->trade_status == '6' && ($v->total_num > $v->apply_num)): ?>
                                                <a href="javascript:;" class="blue_light revoke_task_btn" trade_id="<?= $v->id; ?>">撤销未接单活动</a>
                                            <?php endif; ?>
                                        </dt>
                                        <?php if ($v->trade_type == '10'): ?>
                                            <dd>进行中(<span class="red"><?= $v->order_cnts->ongoing; ?></span>)<span class="grey">|</span></dd>
                                            <dd>已提交、待审核(<span class="red"><?= $v->order_cnts->wait_send; ?></span>)<span class="grey">|</span></dd>
                                            <dd>待发放佣金(<span class="red"><?= $v->order_cnts->wait_refund; ?></span>)<span class="grey">|</span></dd>
                                            <dd>未接单(<span class="red"><?= $v->order_cnts->not_started; ?></span>)<span class="grey">|</span></dd>
                                            <dd>已完成(<span class="red"><?= $v->order_cnts->finished; ?></span>)</dd>
                                        <?php else: ?>
                                            <dd>进行中(<span class="red"><?= $v->order_cnts->ongoing; ?></span>)<span class="grey">|</span></dd>
                                            <dd>待发货(<span class="red"><?= $v->order_cnts->wait_send; ?></span>)<span class="grey">|</span></dd>
                                            <dd>待退款(<span class="red"><?= $v->order_cnts->wait_refund; ?></span>)<span class="grey">|</span></dd>
                                            <dd>未接单(<span class="red"><?= $v->order_cnts->not_started; ?></span>)<span class="grey">|</span></dd>
                                            <dd>已完成(<span class="red"><?= $v->order_cnts->finished; ?></span>)：待评价</dd>
                                        <?php endif; ?>
                                    </dl>
                                </td>
                                <td>
                                    <?php if (in_array($v->trade_status, ['2','3','4'])): ?>
                                        <a href="/trade/one_key/<?= $v->id; ?>" class="btn btn-blue" target="_blank">一键重发</a>
                                    <?php elseif ($v->trade_status == '0'): ?>
                                        <a href="<?= '/trade/step/'. $v->id ?>" class="btn btn-blue" target="_blank">继续支付</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
                <div class="pager"><?php echo $pagination; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- 加快活动完成速度弹窗 -->
<div class="accelerate_speed_wrap">
    <div class="popup_wrap accelerate_speed" style="display: none;">
        <form id="add_form" action="/trade/append_service_submit" method="post">
            <input type="hidden" name="trade_id"  value="" />
            <div class="popup_wraps">
                <img class="close" src="/static/imgs/business/record_close.png" alt="">
                <div class="popup_contente" style="text-align: left;">
                    <div class="accelerate_speed_first" style="display: block;">
                        <p class="accelerate_speed_title"><strong>快速完成活动</strong><span>充值到账可能会有延时，若<i class="color_red">30分钟</i>内未到账请联系客服</span></p>

                        <div class="upgrade green_bd">
                            <p class="accelerate_speed_first_title">1.升级为推荐活动并提升排名：<span>增加金币数越多，推荐活动排名越靠前</span></p>
                            <ul>
                                <li>
                                    <input type="checkbox" value="10" name="upgrade" id="upgrade_point_10">
                                    <label for="upgrade_point_10">+10金币</label>
                                </li>
                                <li>
                                    <input type="checkbox" value="20" name="upgrade" id="upgrade_point_20">
                                    <label for="upgrade_point_20">+20金币</label>
                                </li>
                                <li>
                                    <input type="checkbox" value="30" name="upgrade" id="upgrade_point_30">
                                    <label for="upgrade_point_30">+30金币</label>
                                </li>
                            </ul>
                        </div>
                        <div class="a_plus green_bd">
                            <p class="accelerate_speed_first_title">2.加赏活动佣金：<span>增加金币数越多，买手完成活动的积极性越大，买手会优先做此类活动</span></p>
                            <div>
                                <input type="checkbox" name="a_plus_point" id="a_plus_point" >
                                <label for="a_plus_point">每单加赏佣金</label>
                                <input name="add_reward" type="text" min="2" value="2" class="form-control a_plus_point" onkeyup="this.value=this.value.replace(/\D/g,'');" onafterpaste="this.value=this.value.replace(/\D/g,'')" style="width: 64px"/>
                                金币，共计<span class="order_number">3</span>单&nbsp;X&nbsp;
                                <span class="color_red a_plus_point_number">2</span>金币&nbsp;=&nbsp;
                                <span class="color_red total_a_plus_point">6</span>金币
                            </div>
                        </div>
                        <div class="next_wrap">
                            <p class="">需支付<span class="total_a_plus_upgrade color_red">0.00</span>金币</p>
                            <p>
                                <a class="accelerate_speed_go_pay next_btn" href="javascript:;">下一步</a>
                                <a class="no_accelerate_speed close" href="javascript:;">取消</a>
                            </p>
                        </div>
                    </div>
                    <div class="accelerate_speed_second" style="display: none;">
                        <p class="accelerate_speed_title"><strong>增值服务</strong></p>
                        <p class="need_pay_money">需要支付：<span class="color_red total_a_plus_upgrade">10.00</span>金币</p>
                        <div class="pay_type">
                            <div class="pay_list_box">
                                <div class="pay_check pay_check_points"><!-- <label><input type="checkbox" name="points" /> -->使用<?php echo PROJECT_NAME; ?>支付&nbsp;(可用<?php echo PROJECT_NAME; ?>：<span><?php echo $user_info->user_point; ?></span> 金币)1金币 = 1元<!-- </label> --><p>支付：<em class="points_pay_number">100.00</em> 金币</p></div>
                                <div style="display: none;" class="pay_check pay_check_recharge"><label><input type="checkbox" name="recharge" />使用押金支付&nbsp;(可用押金：<span><?php echo $user_info->user_deposit; ?></span>元)</label><p>支付：<em class="points_pay_number">100.00</em> 金币</p></div>
                            </div>
                            <div class="prev_wrap">
                                <a href="javascript:;" class="prev_btn">上一步</a>
                                <a href="javascript:;" class="true_pay_but pay_but_dis" >确认付款</a>
                            </div>
                            <!--<p class="lack_deposit"><span class="deposit_ande_point">金币还差<em class="points_pay_number">100.00</em></span>，请联系客服QQ<a target="_blank" href="www.baidu.com" alt=""></a>进行充值操作</p>-->
                            <p class="lack_deposit"><span class="deposit_ande_point">金币还差<em class="points_pay_number">100.00</em></span>，请联系客服进行充值操作</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            var $parent = self.parent.$;
            $(function() {
                // 关闭加快进度弹窗
                $('.close').click(function(event) {
                    $(this).parents('.popup_wrap').hide();
                });
                // 选择推荐的金币数
                $('input[name=upgrade]').change(function(event) {
                    if($(this).prop("checked")){
                        $(this).parent('li').siblings('li').children('input[name=upgrade]').prop('checked',false);
                    }
                    total_point();
                });


                // 是否选择加赏佣金
                $('input[name=a_plus_point]').change(function(event) {
                    total_point();
                });

                // 设置每单加赏金币数
                $('.a_plus_point').keyup(function (event) {
                    var order_number = parseInt($('.order_number').text());   // 订单数量
                    var a_plus_point = parseInt($('.a_plus_point').val());    // 每单加赏金币数
                    $(".a_plus_point_number").text(a_plus_point);
                    $('.total_a_plus_point').text(order_number * a_plus_point);
                    total_point();
                }).blur(function (event) {
                    var $this = $(this), _val = parseInt($this.val());
                    if (_val < 2) {
                        $this.val(2);
                        $this.trigger('keyup');
                    }
                });

                // 增值服务选择完成 进行下一步
                $('.accelerate_speed_go_pay').click(function(event) {
                    $('.next_wrap').children('p').find('.error').remove();
                    if($('.total_a_plus_upgrade').text()>0){
                        $('.accelerate_speed_first').hide().siblings('.accelerate_speed_second').show();
                        points_pay($('.total_a_plus_upgrade').eq(0).text(),<?php echo $user_info->user_point; ?>);
                    }else{
                        $('.next_wrap').children('p').eq(0).append('<span style="line-height: 18px;" class="error">请选择增值服务</span>');
                    }
                });
                // 上一步
                $('.prev_btn').click(function(event) {
                    $('.accelerate_speed_first').show().siblings('.accelerate_speed_second').hide();
                });

                // $('.pay_check input').change(function(event) {
                //     pay_check();
                // });


                //支付提交
                $(".true_pay_but").click(function(){
                    if($(this).hasClass('pay_but_dis'))return;
                    $(this).parents('.popup_wrap').hide();
                    $('#add_form')[0].submit();
                });

            });

            function total_point(a){
                $('.error').remove();

                var total_points = 0;
                var total_a_plus_point = parseInt( $('.total_a_plus_point').text() );// 加赏金币

                if($('#upgrade_point_10').prop('checked')){
                    total_points += 10;
                }else if($('#upgrade_point_20').prop('checked')){
                    total_points += 20;
                }else if($('#upgrade_point_30').prop('checked')){
                    total_points += 30;
                }


                if($('input[name=a_plus_point]').prop("checked")){
                    total_points+= total_a_plus_point;
                }


                $('.total_a_plus_upgrade').text( total_points );
            }

            // 支付金币是否足够
            function points_pay(points_number,points){
                var points_number = parseFloat(points_number)*10000; //需要支付金额
                var points        = parseFloat(points)*10000; //可用金币
                if( points>0 ){
                    $(".pay_check_points").addClass("cur");
                    $(".pay_check_points").find("p").show();

                    if( points>points_number || points==points_number ){
                        $(".pay_check_points").find('em').html(points_number/10000);

                        $(".lack_deposit").hide();
                        $('.true_pay_but').addClass('pay_but').removeClass('pay_but_dis');
                    }else{
                        var pay_recharge = points_number-points;
                        $(".pay_check_points").find('em').html(points/10000);

                        $(".lack_deposit").show().find('.points_pay_number').html( pay_recharge /10000 );
                        $('.true_pay_but').addClass('pay_but_dis').removeClass('pay_but');
                    }
                }else{
                    $(".lack_deposit").show().find('.deposit_ande_point').html("</em>或者金币还差<em class='points_pay_number'>"+ points_number/10000);
                    $('.true_pay_but').addClass('pay_but_dis').removeClass('pay_but');
                }
            }
        </script>
    </div>
</div>
<!-- 撤销活动弹窗 -->
<div class="reboke_task_wrap">
    <div class="popup_wrap cancel_task_wrap" style="display: none;">
        <form action="">
            <input type="hidden" name="" value="">
            <div class="popup_wraps" style="width: 330px;margin-left: -165px;">
                <img class="close2" src="/static/imgs/business/record_close.png" alt="">
                <div class="popup_contente">
                    <p>当前活动还有<span class="cancel_surplus_num"></span>单未接手，撤销未接手活动</p>
                    <p>将解冻押金<span class="cancel_refund_deposit"></span>元，退回<?php echo PROJECT_NAME; ?>金币<span class="cancel_refund_point"></span></p>
                    <div class="edit_btn_wrap">
                        <a class="confirm_btn" href="javascript:;">确认</a>
                        <a class="close_btn close2" href="javascript:;">关闭</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        // 撤销活动弹窗显示
        // $(".revoke_task_btn").click(function(event) {
        //     $(".cancel_task_wrap").show();
        // });
        // 确认撤销活动
        $(".confirm_btn").click(function(event) {
            $(this).parents(".popup_bg").hide();


            // $(".back_money_wrap").show();
            // start = 5;
            // count();
        });
        // 撤销活动返还冻结押金和<?php echo PROJECT_NAME; ?>点弹窗关闭
        $(".close2").click(function(event) {
            $(".cancel_task_wrap").hide();
            // 撤销完成后需要进行的操作
        });
    </script>
</div>

<script type="text/javascript">
    $(window.parent.document).find("#trade_list").css("height",$("body").height());
    var $parent = self.parent.$;
    $(function() {
        // 撤销活动弹窗
        $(".revoke_task_btn").click(function (event) {
            var trade_id = $(this).attr('trade_id');
            $.ajax({
                type: "POST",
                url: "/trade/get_cancel_vars",
                data: {
                    "trade_id": trade_id
                },
                dataType: "json",
                success: function (d) {
                    $('.cancel_surplus_num').text(d.surplus_num);
                    $('.cancel_refund_deposit').text(d.deposit);
                    $('.cancel_refund_point').text(d.point);
                    $('.confirm_btn').attr('href', "/trade/cancel/" + trade_id);
                    $parent('#iframe_popup').html($('.reboke_task_wrap').html());
                    $(".cancel_task_wrap").show();
                    // $('.cancel_task_wrap').hide();
                }
            });
        });

        // 开启加快活动进度弹窗
        $('.accelerate_task_complete').click(function (event) {
            $('.order_number').text($(this).attr('surplus_num'));
            $('.total_a_plus_point').text($(this).attr('surplus_num') * 2);
            $('input[name=trade_id]').val($(this).attr('order_id'));
            $parent('#iframe_popup').html($('.accelerate_speed_wrap').html());
            $('.accelerate_speed').show();
            // $('.accelerate_speed').hide();
        });


        // 取消未支付活动
        $(".nonpayment_cancel_btn").click(function (event) {
            var trade_id = $(this).attr('trade_id');
            var this_click=$(this);
            $.ajax({
                type: "POST",
                url: "/trade/nonpayment_cancel",
                data: {
                    "trade_id": trade_id
                },
                dataType: "json",
                success: function (e) {
                    if (e.code<1)
                    {
                        this_click.parent().parent().parent().parent().remove();

                        var all_num =  $("#myTab_two li:eq(0) a span");
                        var non_num =  $("#myTab_two li:eq(3) a span");
                        if (non_num.text()>0){
                            all_num.text(all_num.text()-1);
                            non_num.text(non_num.text()-1);
                        }
                    }
                }
            });
        });

    });
</script>
</body>
</html>