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
    <style type="text/css">
        .eval_ipt{
            width: 460px;
            height: 28px;
            border: 1px solid #bddffd;
            background-color: #fbfdff;
            padding-left: 5px;
        }
    </style>
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
        <div class="step_pic_box">
            <h1>3.选择活动数量<p>已选择：<span>
                <?php echo $trade_select['plat_name']; ?>&nbsp;|&nbsp;
                <?php echo $trade_select['shop_name']; ?>&nbsp;|&nbsp;
                <?php echo $trade_select['type_name']; ?></p>
            </h1>
            <div class="row">
                <div class="col-xs-12">所有商家都要注意在<?php echo PROJECT_NAME; ?>推广务必严格控制好以下2点：</div>
                <div class="col-xs-4 red">1. 推广比例一定不要过高，最高不能超过40% ;</div>
                <div class="col-xs-6 red">2. 移动端搜索转化率务必不要过高，保持在行业平均转化率的1.5倍左右最佳</div>
            </div>
            <div class="white_box">
                <h3>1.上传商品照片：<span>系统会随机安排买手发布图文评价（最少发布1单，每单+4金币）</span></h3>
                <p><span class="red">注意：</span>1、每组照片拍摄的角度、背景不能一样</p>
                <p style="padding-left: 42px;">2、请将你的商品根据你要的图文评价数量，拍摄不同的组数，每组可传1-5张商品图片，每张图片不可大于2Mb</p>
                <div class="pic_list_box">
                    <?php
                        foreach ($setting_img as $k=>$v):
                            $tmp_cnt = 0;
                    ?>
                    <div class="pic_list">
                        <div class="pic_list_info">
                            <b>第<?php echo $k+1; ?>单的照片</b>
                            <?php if ($k>0): ?>
                            <a href="javascript:;" class="pic_list_del">删除</a>
                            <?php endif; ?>
                            设置图片商品的规格：
                            <?php if ($no_editable): ?>
                            <input type="text" name="color[]" placeholder="如：颜色" value="<?php echo $trade_item->color; ?>" readonly class="form-control"/>
                            <input type="text" name="size[]" placeholder="如：尺码" value="<?php echo $trade_item->size; ?>" readonly class="form-control"/>
                            <?php else: ?>
                            <input type="text" name="color[]" placeholder="如：颜色" value="<?php echo $v->color; ?>" class="form-control"/>
                            <input type="text" name="size[]" placeholder="如：尺码" value="<?php echo $v->size; ?>" class="form-control"/>
                            <?php endif; ?>
                            <span>主要针对商品规格不同颜色,花色,款式进行设置,和好评图片保持一致</span>
                        </div>
                        <ul class="up_load_list <?php if ($v->img5): ?>img_5<?php endif; ?>">
                            <?php if ($v->img1): $tmp_cnt++; ?>
                            <li>
                                <div class="uploaded_img_preview">
                                    <i style="display: block;" class="remove_upload_img"></i>
                                    <img class="uploaded_goods_imgs" src="<?= $v->img1; ?>" width="130" height="130" />
                                    <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?php echo  $v->img1; ?>" />
                                </div>
                            </li>
                            <?php endif; ?>
    
                            <?php if ($v->img2): $tmp_cnt++; ?>
                            <li>
                                <div class="uploaded_img_preview">
                                    <i style="display: block;" class="remove_upload_img"></i>
                                    <img class="uploaded_goods_imgs" src="<?= $v->img2; ?>" width="130" height="130" />
                                    <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?php echo  $v->img2; ?>" />
                                </div>
                            </li>
                            <?php endif; ?>
    
                            <?php if ($v->img3): $tmp_cnt++; ?>
                            <li>
                                <div class="uploaded_img_preview">
                                    <i style="display: block;" class="remove_upload_img"></i>
                                    <img class="uploaded_goods_imgs" src="<?= $v->img3; ?>" width="130" height="130" />
                                    <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?php echo  $v->img3; ?>" />
                                </div>
                            </li>
                            <?php endif; ?>
    
                            <?php if ($v->img4): $tmp_cnt++; ?>
                            <li>
                                <div class="uploaded_img_preview">
                                    <i style="display: block;" class="remove_upload_img"></i>
                                    <img class="uploaded_goods_imgs" src="<?= $v->img4; ?>" width="130" height="130" />
                                    <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?php echo  $v->img4; ?>" />
                                </div>
                            </li>
                            <?php endif; ?>
    
                            <?php if ($v->img5): $tmp_cnt++; ?>
                            <li>
                                <div class="uploaded_img_preview">
                                    <i style="display: block;" class="remove_upload_img"></i>
                                    <img class="uploaded_goods_imgs" src="<?= $v->img5; ?>" width="130" height="130" />
                                    <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?php echo  $v->img5; ?>" />
                                </div>
                            </li>
                            <?php endif; ?>
    
                            <?php if ($tmp_cnt < 5): ?>
                            <li>
                                <div class="uploaded_img_preview">
                                    <i style="display: none;" class="remove_upload_img"></i>
                                    <img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" width="130" height="130" />
                                    <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="" />
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <div style="margin-bottom:12px;"><span style="display:inline-block;width:64px;"><span class="red">* </span>评价：</span><input type="text" name="comment[]"  class="eval_ipt" value="<?= $v->content; ?>" size="200" /></div>
                        <p style="padding-left: 10px;"></p>
                    </div>
                    <?php endforeach; ?>

                </div>
                
                <div class="add_pic_list" <?php if (count($setting_img)>=5): ?>style="display:none;"<?php endif; ?>><a href="javascript:;"><em class="add_icon">+</em>增加一单商品图片</a><span>（最多发布5单活动）</span></div>
            </div>
            <div class="pic_list_num_total">共发布<span class="pic_list_num"><?php echo $trade_info->total_num; ?></span>单活动</div>
            
            <div class="white_box">
                <h3>3.下单提示<span class="red">注：买手接活动时可看见该提示，提示内容自由填写，如：商品在第*页*行、聊天时不要问发货地和哪家快递等。属可选项，限255字内。</span></h3>
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
                    <td><span class="pic_list_num red"><?php echo $trade_info->total_num; ?></span>单</td>
                    <td><span class="pic_list_fee_total red"><?php echo bcmul($trade_info->total_fee,$trade_info->total_num,2); ?></span>金币</td>
                </tr>
                <!-- 只有手机关键词的时候展示订单分布 -->
                <?php if ($trade_info->is_phone): ?>
                <tr>
                    <td><span class="red">订单分布</span></td>
                    <td><span class="red"><?php echo ORDER_DIS_PRICE; ?></span>金币/单</td>
                    <td><span class="sd_number red"><?php echo $trade_info->total_num; ?></span>单</td>
                    <td><span class="fb_fee_total red"><?php echo bcmul($trade_info->total_num, ORDER_DIS_PRICE, 2); ?></span>金币</td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
    </div>
    <div class="next_box">
        <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
        <a href="javascript:;" class="next_step">下一步</a>
    </div>
    
    <?php $this->load->view("/common/footer"); ?>

<script src="/static/js/exif.js"></script>
<script src="/static/js/lrz.js"></script>
<script type="text/javascript">
$(function(){
    // 图片上传、删除操作
    $("body").on("click", ".uploaded_goods_img", function () {
        $(this).siblings("input").click();
    }).on("click", ".remove_upload_img", function () {
        var _parents_ul = $(this).parents(".up_load_list");
        $(this).parents("li").remove();
        if (_parents_ul.hasClass('img_5')){
            _parents_ul.removeClass('img_5');
            _parents_ul.find('li').show();
        }
    });

	$(".add_pic_list a").click(function(){
		var pic_list_num = $(".pic_list").size()+1;
		$(".pic_list_box").append('<div class="pic_list">'+
                	'<div class="pic_list_info">'+
                    	'<b>第<em>'+pic_list_num+'</em>单的照片</b><a href="javascript:;" class="pic_list_del">删除</a>'+
                        '上传图片商品的规格：'+
                        <?php if ($no_editable): ?>
                        '<input type="text" name="color[]" placeholder="如：颜色" value="<?php echo $trade_item->color; ?>" readonly class="form-control"/><input type="text" name="size[]" placeholder="如：尺码" value="<?php echo $trade_item->size; ?>" readonly class="form-control"/>'+
                        <?php else: ?>
                        '<input type="text" name="color[]" placeholder="如：颜色" class="form-control"/><input type="text" name="size[]" placeholder="如：尺码" class="form-control"/>'+
                        <?php endif; ?>
                        '<span>(非必填)主要针对商品规格不同颜色,花色,款式进行设置,和好评图片保持一致</span>'+
                    '</div>'+
                    '<ul class="up_load_list">'+
                    	'<li>'+
                            '<div class="uploaded_img_preview">'+
                                '<i style="display: none;" class="remove_upload_img"></i>'+
                                '<img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" width="130" height="130" />'+
                                '<input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" path="" onChange="javascript:setImagePreview(this);" uploaded="" base64="" hidden="hidden" />'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
            '<div style="margin-bottom:12px;"><span style="display:inline-block;width:64px;"><span class="red">* </span>评价：</span><input type="text" name="comment[]" class="eval_ipt" value="" size="200" /></div>'+
            '<p style="padding-left: 10px;"></p>'+
                '</div>');
        $(".pic_list_num").html(pic_list_num);
		$(".sd_number").html(pic_list_num);
        $(".pic_list_fee_total").html(parseInt(pic_list_num)*(<?php echo $trade_info->total_fee; ?>*10)/10);
		$(".fb_fee_total").html(parseInt(pic_list_num)*<?php echo ORDER_DIS_PRICE; ?>);
		if(pic_list_num>4){
			$(".add_pic_list").hide();
		}
	});

	$("body").on("click",".pic_list_del",function(){
		$(".add_pic_list").show();
		var pic_list_num = $(".pic_list").size()+1;
		$(this).parents(".pic_list").remove();
		for(var i=0;i<pic_list_num;i++){
			$(".pic_list").eq(i).find("em").html(i+1);
		}
        $(".pic_list_num").html(pic_list_num-2);
		$(".sd_number").html(pic_list_num-2);
        $(".pic_list_fee_total").html(parseInt(pic_list_num-2)*(<?php echo $trade_info->total_fee; ?>*10)/10);
		$(".fb_fee_total").html(parseInt(pic_list_num-2)*<?php echo ORDER_DIS_PRICE; ?>);
	});

	//下一步点击，验证是否每组图片都有填写
    $("body").on("click", ".next_step", function () {
        var pic_err = false;
        var pic_list_num = $(".pic_list").size();
        for (var i = 0; i < pic_list_num; i++) {
            if ($('.pic_list').eq(i).find('.uploaded_goods_imgs').size() < 1) {
                pic_err = true;
            }
        }

        if (pic_err) {
            toastr.warning("每组图片至少上传1张");
            return false;
        } else {
            var color = rtn_array($('input[name="color[]"]'));
            var size = rtn_array($('input[name="size[]"]'));
            var imgs = rtn_imgs();
            var contents=rtn_array($('input[name="comment[]"]'));
            var order_prompt = $('.message_text').val();
            if (imgs == 0) {
                return false;
            }
            // 数据提交
            $.ajax({
                type: "POST",
                url: "/trade/pic_eval_step3_submit/<?php echo $trade_info->id; ?>",
                data: {
                    "color[]": color,
                    "size[]": size,
                    "imgs[]": imgs,
                    "contents": contents,
                    "order_prompt": order_prompt
                },
                datatype: "json",
                success: function (d) {
                    location.href = "/trade/step/<?php echo $trade_info->id; ?>";
                }
            });
        }
    })
});

function setImagePreview(obj) {
    var $this = $(obj);
    var _file = obj.files[0];
    if (_file.size >= 1024 * 1024 * 2) {
        toastr.warning("图片不能大于2M");
        return false;
    }
    var type = "";
    if ($this.val() != '') {
        type = $this.val().match(/^(.*)(\.)(.{1,8})$/)[3];
        type = type.toUpperCase();
    }
    if (type != "JPEG" && type != "PNG" && type != "JPG" && type != "GIF") {
        toastr.warning("图片格式必须是gif、jpg、png中的一种");
        return false;
    }
    if ($this.parents('li').siblings().length > 5) {
        return false;
    }else if ($this.parents('li').siblings().length >= 4) {
        $this.parents(".up_load_list").addClass('img_5');
        //当上传第五张图片的时候，后续需加一个隐藏域，方便检查图片是否上传成功
        var up_load_5 = '<li style="display: none">' +
            '<div class="uploaded_img_preview">' +
            '<i style="display: none;" class="remove_upload_img"></i>' +
            '<img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" width="130" height="130" />' +
            '<input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="" base64="" hidden="hidden" path="" />' +
            '</div></li>';
        $this.parents(".up_load_list").append(up_load_5);
    } else {
        var up_load = '<li>' +
            '<div class="uploaded_img_preview">' +
            '<i style="display: none;" class="remove_upload_img"></i>' +
            '<img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" width="130" height="130" />' +
            '<input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="" base64="" hidden="hidden" path="" />' +
            '</div></li>';
        $this.parents(".up_load_list").append(up_load);
    }

    $this.siblings('.uploaded_goods_img').prop("src", '/static/imgs/jiazaizhong.gif').addClass('uploaded_goods_imgs').removeClass('uploaded_goods_img').siblings('.remove_upload_img').show();
    lrz(_file, function (res) {
        $.ajax({
            type: "POST",
            url: "/trade/ajax_upload",
            data: {"base64": res.base64},
            datatype: "json",
            success: function (pic_url) {
                if (pic_url == '') {
                    toastr.error('图片上传失败，请重新上传');
                    $this.parent().find('.uploaded_goods_imgs').attr('src', '/static/imgs/icon/set_img.png');
                } else {
                    $this.attr('uploaded', 1);
                    $this.attr('path', pic_url);
                    $this.parent().find('.uploaded_goods_imgs').attr('src', pic_url);
                }
            }
        });
    });

	$this.parents('.up_load_list').siblings("p").html("");
}

// 对象转数组
function rtn_array(obj) {
    var tmpArr = new Array();
    obj.each(function () {
        tmpArr.push($(this).val());
    });

    return tmpArr;
}

// 返回图片路径数组
function rtn_imgs() {
    var tmpArr = new Array();
    var img_upload_flag = true;
    $('ul.up_load_list').each(function () {
        var tmpArr2 = new Array();
        var _upload_fail = [];
        $(this).find('.uploaded_goods_img_val').each(function () {
            if ($(this).attr('path') != '' && $(this).attr('uploaded') == '1') {
                tmpArr2.push($(this).attr('path'));
            } else {
                _upload_fail.push(1);
            }
        });
        tmpArr.push(tmpArr2);
        if (_upload_fail.length >= 2) {
            img_upload_flag = false;
        }
    });

    if (img_upload_flag == false){
        toastr.warning('请稍等检查上传的图片，有部分图片正在上传、或者上传失败了');
        return 0 ;
    } else {
        return tmpArr;
    }
}

</script>
</body>
</html>