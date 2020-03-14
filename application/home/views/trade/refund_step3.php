<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/trade.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<title>商家报名活动-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
    <?php $this->load->view("/common/top", ['site' => 'trade']); ?>
    <div class="com_title">商家报名活动</div>
    <!-- 发活动顶部活动步骤进度start -->
    <div class="trade_top">
        <div class="Process">
            <ul class="clearfix">
              <li style="width:20%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
              <li style="width:20%" class="cur"><em class="Processyes">2</em><span>填写商品信息</span></li>
              <li style="width:20%"><em class="Processyes">3</em><span>选择活动数量</span></li>          
              <li style="width:20%"><em>4</em><span>选增值服务</span></li>
              <li style="width:20%"><em>5</em><span>支付</span></li>
              <li style="width:20%" class="Processlast"><em>6</em><span>发布成功</span></li>
            </ul>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="trade_box">
        <div class="step3_box">
            <h1>3.选择活动数量
                <p>已选择：<span>
                <?php echo $trade_select['plat_name']; ?>&nbsp;|&nbsp;
                <?php echo $trade_select['shop_name']; ?>&nbsp;|&nbsp;
                <?php echo $trade_select['type_name']; ?>
                </p>
            </h1>
            <div class="row">
                <div class="col-xs-12" style="padding-top: 4px;padding-bottom: 6px;">所有商家都要注意在<?php echo PROJECT_NAME; ?>推广务必严格控制好以下2点：</div>
                <div class="col-xs-4 red">1. 推广比例一定不要过高，最高不能超过40% ;</div>
                <div class="col-xs-6 red">2. 移动端搜索转化率务必不要过高，保持在行业平均转化率的1.5倍左右最佳</div>
            </div>
            <div class="task_number_box white_box">
                <h3>1.请选择活动数量</h3>
                <p class="select_number">
                    <label><input name="taocan" type="checkbox" class="tc-name" data-enable="1" value="1" <?php if ($trade_info->total_num == '1'): ?>checked<?php endif; ?>>1单</label>
                    <label><input name="taocan" type="checkbox" class="tc-name" data-enable="3" value="3" <?php if ($trade_info->total_num == '3'): ?>checked<?php endif; ?>>3单</label>
                    <label><input name="taocan" type="checkbox" class="tc-name" data-enable="5" value="5" <?php if ($trade_info->total_num == '5'): ?>checked<?php endif; ?>>5单</label>
                    <label><input name="taocan" type="checkbox" class="tc-name" data-enable="10" value="10" <?php if ($trade_info->total_num == '10'): ?>checked<?php endif; ?>>10单</label>
                    <label><input name="taocan" type="checkbox" class="tc-name" data-enable="20" value="20" <?php if ($trade_info->total_num == '20'): ?>checked<?php endif; ?>>20单</label>
                    <label><input name="taocan" type="checkbox" class="tc-name" data-enable="100" value="100" <?php if ($trade_info->total_num == '100'): ?>checked<?php endif; ?>>100单</label>
                    <label><input name="taocan" type="checkbox" class="tc-name custom" data-enable="<?php if ($is_custom){ echo $custom_val;}else{ echo '1';} ?>" data-zk="<?php if ($is_custom){ echo $custom_val;}else{ echo '1';} ?>" value="<?php if ($is_custom){ echo $custom_val;}else{ echo '1';} ?>" <?php if ($is_custom): ?>checked<?php endif; ?>>自定义</label>
                    <input type="number" name="taocan_number" class="data-enable" value="<?php echo $custom_val; ?>" min="1" max="500" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">单
                    <span>(1-500单)</span>
                </p>
                <p class="careful"><span>注意</span>：请注意控制好店铺的转化率！不得超过3%！如果因转化率过高，导致商品降权，<?php echo PROJECT_NAME; ?>概不负责！</p>
            </div>
            <?php if(!in_array($trade_info->trade_type, ['4', '5', '7'])): ?>
            <div class="keyword_distribution white_box">
                <h3>2.请设置成交关键词分布<span><span class="red">注：</span>各关键词订单总数需要为<em class="singular"><?php echo $trade_info->total_num; ?></em>单</span></h3>
                <?php 
                    $i = $j = $k = $l = 1;
                    foreach ($trade_search as $v):
                        if ($v->plat_id == '1') {
                            $tmp_idx = $i++;
                        } elseif ($v->plat_id == '2') {
                            $tmp_idx = $j++;
                        } elseif ($v->plat_id == '3' || $v->plat_id == '4') {
                            $tmp_idx = $k++;
                        } elseif ($v->plat_id == '5') {
                            $tmp_idx = $l++;
                        }
                ?>
                <p><?php echo $plat_names[$v->plat_id]; ?>关键词<?php echo $tmp_idx; ?>：<?php echo $v->kwd; ?><input name="nums[]" type="number" min="1" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" value="<?php echo $v->num; ?>" />单</p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="white_box">
                <h3><?= (in_array($trade_info->trade_type, ['4', '5', '7'])) ? 2 : 3 ?>.下单提示<span class="red">注：买手接活动时可看见该提示，提示内容自由填写，如：商品在第*页*行、聊天时不要问发货地和哪家快递等。属可选项，限<em>255</em>字内。</span></h3>
                <textarea  class="message_text" maxlength="255" rows="5" placeholder=""><?php echo $trade_item->order_prompt; ?></textarea>
            </div>
            <div class="f20"><p>费用小计</p></div>
            <table class="table table-bordered text-center">
                <tr>
                    <th class="text-center grey_th">分类</th>
                    <th class="text-center grey_th">单价</th>
                    <th class="text-center grey_th">数量</th>
                    <th class="text-center grey_th">合计</th>
                </tr>
                <tr>
                    <td><span class="red">服务费</span></td>
                    <td><span class="red"><?php echo $trade_info->total_fee; ?></span>金币/单</td>
                    <td><span class="sd_number red"><?php echo $trade_info->total_num; ?></span>单</td>
                    <td><span class="sd_fee_total red"><?php echo bcmul($trade_info->total_fee,$trade_info->total_num,2); ?></span>金币</td>
                </tr>
                <!-- 只有手机关键词的时候展示订单分布 -->
                <?php if ($trade_info->is_phone): ?>
                <tr>
                    <td><span class="red">订单分布</span></td>
                    <td><span class="red"><?php echo ORDER_DIS_PRICE; ?></span>金币/单</td>
                    <td><span class="sd_number red"><?php echo $trade_info->total_num; ?></span>单</td>
                    <td><span class="fb_fee_total red"><?php echo bcmul($trade_info->total_num, ORDER_DIS_PRICE,2); ?></span>金币</td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <div class="next_box">
            <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
            <a href="javascript:;" class="next_step">下一步</a>
        </div>
        
    </div>
    
    <?php $this->load->view("/common/footer"); ?>

<script type="text/javascript">
$(function(){
    // 点击选项
    $('.select_number label').click(function () {
        var $this = $(this), $par = $this.parent();
        if ($('.select_number').find('input:checked').length > 0) {
            $this.siblings().find('input').removeAttr('checked');
            var _enable = $this.find('input').attr('data-enable') || 0;
            if (_enable == 0) {
                _enable = $('.data-enable').val() || 0;
            }
            $('.singular').text(_enable);
            $(".sd_number").text(_enable);
            $par.addClass('active').siblings().removeClass('active');
            // 关键词分布
            var _distribution = $('input[name="nums[]"]');
            if (_distribution.length == 1) {
                _distribution.val(_enable);
            }
        } else {
            $this.find('input').prop("checked", true);
        }

        fee_total();
    });
    
    // 自定义操作
    $('.data-enable').on('blur keyup', function () {
        var $this = $(this);
        if ($this.val() < 1) {
            $this.val(1);
        }
        if ($this.val() > 500) {
            $this.val(500);
        }
        _enable = $this.val();
        $('.singular').text(_enable);
        $(".sd_number").text(_enable);
        if ($('.custom:checked').length <= 0) {
            $('.select_number').find('input:checked').removeAttr('checked');
            $('.custom').prop('checked', true);
        }
        $('.custom').val($this.val());
        // 关键词分布
        var _distribution = $('input[name="nums[]"]');
        if (_distribution.length == 1) {
            _distribution.val(_enable);
        }
        fee_total();
    });

    //点击下一步
    var _check_search_num = "<?= (in_array($trade_info->trade_type, ['4', '5', '7'])) ? 0 : 1 ?>";
    var _trade_id = '<?= $trade_info->id; ?>';
    $(".next_step").click(function(){
        if (_check_search_num == 1) {
            //总单数
            var sd_number = $(".select_number input:checked").val();
            //设置关键字分布单数
            var distribution_num = 0;
            var _flag = true ;
            $(".keyword_distribution p").each(function () {
                var _single_num = parseFloat($(this).children("input").val());
                if (_single_num <= 0) {
                    _flag = false ;
                }
                distribution_num += _single_num;
            });
            if (_flag == false) {
                toastr.warning("请确认每组关键词分布至少要有一单");
                return false;
            }
            if (distribution_num != sd_number) {
                toastr.warning("成交关键词分布的订单总数需为" + sd_number + "单");
                return false;
            }
        }
        var total_num = $('input[name="taocan"]:checked').val();
        var total_num_custom = $('input[name="taocan_number"]').val();
        var nums = rtn_array($('input[name="nums[]"]'));
        var order_prompt = $('.message_text').val();
        $.ajax({
            type: "POST",
            url: "/trade/refund_step3_submit/" + _trade_id,
            data: {
                "total_num": total_num,
                "total_num_custom": total_num_custom,
                "nums[]": nums,
                "order_prompt": order_prompt
            },
            datatype: "json",
            success: function (d) {
                if (d == 0){
                    location.href = "/trade/step/" + _trade_id
                } else if(d == 2 || d == 3) {
                    toastr.error('任务单数、与关键字分配的单数总数不一致，请确认');
                    return false;
                } else {
                    toastr.error('非法参数');
                    return false;
                }
            }
        });
    });
});

// 活动费用计算
function fee_total() {
    //活动费用
    var sd_fee = <?php echo $trade_info->total_fee; ?>;
    //单数
    var sd_num = $(".select_number input:checked").val();
    var custom_num = $('input[name="taocan_number"]').val();
    if (sd_num == "0") {
        sd_num = custom_num;
    }
    // 计算得总金币
    $(".sd_fee_total").text((parseFloat(sd_fee) * parseFloat(sd_num)).toFixed(2));
    // 如果是手机端活动执行下面订单分布计算
    var task_type = 'phone';
    if (task_type == 'phone') {
        $(".fb_fee_total").text((parseFloat(<?php echo ORDER_DIS_PRICE; ?>) * parseFloat(sd_num)).toFixed(2));
    }
}

// 对象转数组
function rtn_array(obj) {
    var tmpArr = new Array();
    obj.each(function (){
        tmpArr.push($(this).val());
    });

    return tmpArr;
}

</script>
</body>
</html>