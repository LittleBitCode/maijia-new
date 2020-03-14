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
<link rel="stylesheet" href="/static/css/binding.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<title>绑定店铺-<?php echo PROJECT_NAME; ?></title>
<style>.form-control{padding-left:8px !important;}</style>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
    <div class="con_box">
        <div class="binding_box">
            <div class="binding_notice">请先完成下面的店铺信息，绑定店铺后即可进入报名活动页面</div>
            <!-- 已绑定列表start -->
            <div class="bind_list_box">
            	<h3>已绑定的店铺<span>每个平台可绑定3个店铺</span></h3>
                <table class="table table-hover table-bordered">
                    <thead>
                	<tr>
                        <th width="20%">店铺名称</th>
                        <!-- <th width="35%">发件地址</th> -->
                        <th width="30%">店铺网址</th>
                        <th width="15%">状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($bind_shop_list) && is_array($bind_shop_list)){ ?>
                    <?php foreach ($bind_shop_list as $key => $value) { ?>
                    <tr>
                        <td><?= $value->shop_name; ?></td>
                        <!-- <td><?= $value->province. ' '. $value->city. ' '. $value->region. ' '. $value->address; ?></td>-->
                        <td><?= $value->shop_url; ?></td>
                        <td><?= ($value->shop_status == '1') ? '审核通过':'审核中'; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else{ ?>
                    <tr><td colspan="4" style="text-align: center; height: 80px; line-height: 100px; color: #337ab7; font-size: 16px;">暂还没有绑定好店铺</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- 已绑定列表end -->
            <div class="binding_shop">
            	<div class="binding_shop_type">
                    <ul>
                    <?php foreach ($bind_list as $key => $value) { ?>
                        <li class="label-img-<?= $value['name'] ?><?= ($value['name'] == $bind_shop) ? '':'-gray'; ?>" data-plat_type="<?= $value['name'] ?>">
                            <?php if($value['name'] == $bind_shop): ?><img src="/static/imgs/binding/icon_left.gif"/><?php endif; ?>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
                <div class="bind_shop_r">
                	<h3>绑定新店铺<small>（仅活动对应的买手可见，不会被泄露）</small></h3>
                    <?php if(count($bind_shop_list) < 3): ?>
                    <div class="bind_detail">
                        <?php if(in_array($bind_shop,array('tmall','taobao'))):?>
                        <div class="bind_div_list">
                            <label>店铺主旺旺：</label>
                            <input type="text" class="form-control shop_id" style="display: inline-block;"/>
                            <span><small>（店铺主旺旺绑定后无法修改和删除）</small></span>
                        </div>
                        <?php endif;?>
                        <div class="bind_div_list">
                            <label>店铺名称：</label>
                            <input type="text" class="form-control shop_name" style="display: inline-block;"/>
                            <span><small>（店铺名称绑定后无法修改和删除）</small></span>
                        </div>
                        <div class="bind_div_list" style="display:none;">
                            <label>店铺类目：</label>
                            <select class="form-control cate_one" name="category_one" style="width: 128px;">
                                <option value="">请选择</option>
                                <?php foreach ($cate_list as $k=>$v):?>
                                    <option value="<?php echo $v['id'];?>"><?php echo $v['category'];?></option>
                                <?php endforeach;?>
                            </select>  
                            <select class="form-control cate_two" name="category_two" style="width: 128px;">
                                <option value="">请选择</option>
                            </select>      
                        </div>
                        <div class="bind_div_list">
                            <label>店铺首页网址：</label>
                            <input type="text" class="form-control shop_url" />
                        </div>
                        <div class="bind_div_list address" style="display:none;">
                        	<label>发件地址：</label>
                            <select name="province" class="form-control province" style="width: 156px;">
                                <option value="">请选择所在省份</option>
                                <?php foreach ($province_list as $key => $value): ?>
                                    <option value="<?php echo $value['region_id'] ?>"><?php echo $value['region_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <select name="city" class="form-control city" style="width: 128px;">
                                <option value="">请选择城市</option>
                            </select>
                            <select name="county" class="form-control county" style="width: 128px;">
                                <option value="">请选择区域</option>
                            </select>
                        </div>
                        <p class="address_detail" style="display:none"><input type="text" class="form-control send_address"/></p>
                        
                        <div class="bind_div_list" style="display:none">
                            <label>发件人姓名：</label>
                            <input type="text" class="form-control username" />
                        </div>
                        
                        <div class="bind_div_list" style="display:none">
                            <label>发件人电话：</label>
                            <input type="text" maxlength="11" class="form-control phone" onKeyUp="value=value.replace(/[^\d]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" style="display: inline-block" />
                            <span style="white-space: nowrap"><small>（请填写您店铺的真实发件人信息，此信息会打印在快递面单上）</small></span>
                        </div>

                        <?php if(in_array($bind_shop,array('taobao','tmall','jd'))): ?>
                        <div class="bind_div_list">
                            <label>验证码：</label>
                            <div class="bind_code">
                                <span class="copy-code"><?php echo $bind_shop_goods_code; ?></span>
                                <a href="javascript:;" class="J_copytext copy-code1" data-copy="<?php echo $bind_shop_goods_code; ?>">复制</a>
                            </div>
                        </div>
                        
                        <div class="code_info">
                            <p>1、将验证码加到您的店铺里某个商家商品的标题上，类似这样：</p>
                            <span class="<?= $bind_shop; ?>"><?php echo $bind_shop_goods_code; ?></span>
                            <img src="/static/imgs/binding/<?php echo $bind_shop;?>.png" style="width:100%;" />
                            <br /><br />
                            <p>2、再将这个商品的详情页链接，复制到下面输入框</p>
                            <p>提示：店铺绑定成功后，商品标题中添加的验证码可以去掉；</p>
                        </div>
                        <?php endif; ?>
                        <div class="bind_div_list">
                            <label>商品网址（URL）：</label>
                            <input type="text" class="form-control good_url" />
                        </div>
                        
                        <p class="bind_prompt">如无法绑定店铺或绑定店铺失败，请联系在线客服处理</p>
                    </div>
                    <input type="button" class="bind_sub" style="display:inline-block " value="确认绑定" />
                    <?php endif; ?>
                </div>
                
            </div>
            
            
        </div>
        
    </div>
    
    <?php $this->load->view("/common/footer"); ?>

<script src="/static/js/city_dict.js"></script>
<script type="text/javascript">

$(function(){
	//确认绑定
	$(".bind_sub").bind("click",function(){
        if($(this).hasClass('no_bind_sub'))return;
		var plat_type = "<?php echo $bind_shop;?>"; //店铺类型
		var shop_id   = $(".shop_id").val();
		var shop_name = $(".shop_name").val();
//        var shop_cate1 = $(".cate_one").val();
//        var shop_cate2 = $(".cate_two").val();
		var shop_url  = $(".shop_url").val();
//		var province  = $(".province").find('option:selected').text();
//		var city      = $(".city").find('option:selected').text();
//		var county    = $(".county").find('option:selected').text();
//		var username  = $(".username").val();
//		var phone     = $(".phone").val();
		var good_url  = $(".good_url").val();
//        var send_addr = $(".send_address").val();
		var bind_sub  = true;
//		var cate_one = $('.cate_one').val();
//		var cate_two = $('.cate_two').val();
        var phone_reg = /^1(3|4|5|7|8)\d{9}$/;

        if(shop_id==''){
            toastr.warning("店铺旺旺不能为空，请填写");
            return false;
        }
        if(shop_name==''){
            toastr.warning("店铺名称不能为空，请填写");
            return false;
        }

//		if(cate_one == '' || cate_two == ''){
//            toastr.warning("店铺类目不能为空，请填写");
//            return false;
//        }
		if(shop_url==''){
            toastr.warning("店铺首页网址不能为空，请填写");
            return false;
		}else{
			if( plat_type == "taobao" ){
				if(shop_url.indexOf("tmall.com")<1 && (shop_url.indexOf("taobao.com")>0 || shop_url.indexOf("fliggy.com")>0 || shop_url.indexOf("jiyoujia.com")>0 )){
					
				}else{
                    toastr.warning("请检查链接是否为淘宝链接");
					return false;
				}
                if(good_url.indexOf("taobao.com")>0 ||good_url.indexOf("fliggy.com")>0 || good_url.indexOf("jiyoujia.com")>0 ){

                }else{
                    toastr.warning("请检查链接是否为淘宝链接");
                    return false;
                }
			}else if( plat_type == "tmall" ){
			    if(shop_id.indexOf("旗舰店")>0|| shop_id.indexOf("专卖店")>0 || shop_id.indexOf("专营店")>0){

                }else{
                    toastr.warning("天猫名字必须包含有：旗舰店，专卖店，专营店");
                    return false;
                }
				if(shop_url.indexOf("tmall.com")>0 || shop_url.indexOf("tmall.hk")>0 || shop_url.indexOf("yao.95095.com")>0 || shop_url.indexOf("fliggy.com")>0 || shop_url.indexOf("jiyoujia.com")>0 ){
					
				}else{
                    toastr.warning("请检查链接是否为天猫链接");
					return false;
				}
                if(good_url.indexOf("tmall.com")>0||good_url.indexOf("fliggy.com")>0 || good_url.indexOf("jiyoujia.com")>0 ){

                }else{
                    toastr.warning("请检查链接是否为天猫链接");
                    return false;
                }
			}else if( plat_type == "jd"){
				if(shop_url.indexOf("jd.com")>0){
					
				}else{
                    toastr.warning("请检查链接是否为京东链接");
					return false;
				}
			}else{
				if(shop_url.indexOf(".com")>0){
					
				}else{
                    toastr.warning("请输入正确的店铺网址");
					return false;
				}
			}
		}
//        var check_province = $(".province").val();
//        var check_city = $(".city").val();
//        var check_country = $(".county").val();
//        if(!check_province || !check_city || !check_country || check_province == '请选择省份' || check_city == '请选择城市' || check_country == '请选择区域'){
//            toastr.warning("请选择正确的发件地址");
//            return false;
//		}

//		if(username==''){
//            toastr.warning("发件人姓名不能为空");
//            return false;
//		}
//		if(send_addr == ''){
//            toastr.warning("详细发件地址不能为空");
//            return false;
//        }
//		if(phone=='' ){ // || (!(phone_reg.test(phone)))
//            toastr.warning("手机号格式不正确");
//            return false;
//		}
		if(good_url == ''){
            toastr.warning("商品网址不能为空");
            return false;
		}
		//验证所有商品链接及店铺练级
        if(good_url.substr(0,4) != 'http'){
            toastr.warning("请输入正确的商品网址");
            return false;
        }
        if( shop_url.substr(0,4) !='http'){
            toastr.warning("请输入正确的店铺网址");
            return false;
        }

        //基本验证后可提交
        $('.bind_sub').addClass('no_bind_sub');
        $.ajax({
            url: '/center/add_shop',
            type: "post",
            dataType: 'json',
            data:{
                plat_type:plat_type,
                shop_id:shop_id,
                shop_url:shop_url,
//                province:province,
//                city:city,
//                county:county,
//                username:username,
//                phone:phone,
                goods_url:good_url,
                shop_name:shop_name,
//                send_addr:send_addr,
                copy_code:$(".copy-code").html(),
//                cate_one:cate_one,
//                cate_two:cate_two,
            },
            success: function (data) {
                if(data.is_success == 1){
                    window.location.href='/center/bind/'+plat_type;
                }else{
                    toastr.error(data.msg);
                    return false;
                }
            }
        })
	})
	
	$.getScript('/static/js/jquery.zclip.min.js',function(){  //复制文本的插件
		$('.J_copytext').zclip({
			path:'/static/js/ZeroClipboard.swf',
			copy:function(){
				return $(this).attr('data-copy');
			}, 
			afterCopy:function(){
				$('.J_copytext').removeClass('J_copytextok');
				$(this).text('复制成功').addClass('J_copytextok');
			}
		});
	});

    //获取二级地址信息 旺旺常用登录地选择地址  所在地区前面展示
    $(".province").change(function() {
        $(this).siblings(".city").children('option:first').nextAll().remove();
        $(this).siblings(".county").children('option:first').nextAll().remove();
        var $this = $(this);
        var url = '/center/ajax_get_addr';
        var province = $(this).val();
        var data = {pid:province};
        $.post(url, data, function(res) {
            var option = '';

            $.each(res,function(index,value){
                option += '<option value="'+value.region_id+'">'+value.region_name+'</option>';
            });

            $this.siblings(".city").append(option);
        },'json');

        var province_name = $(this).find('option:selected').text()+'&nbsp;&nbsp;';
        var city_name = '请选择所在市&nbsp;&nbsp;';
        $(this).parents('form').find('.province_name_text').html(province_name);
        $(this).parents('form').find('.city_name_text').html(city_name);
    });
    //获取三级地址信息
    $(".city").change(function() {
        $(this).siblings(".county").children('option:first').nextAll().remove();
        var $this = $(this);
        var url = '/center/ajax_get_addr';
        var city = $(this).val();
        var data = {pid:city};
        $.post(url, data, function(res) {
            var option = '';

            $.each(res,function(index,value){
                option += '<option value="'+value.region_id+'">'+value.region_name+'</option>';
            });
            $this.siblings(".county").append(option);
        },'json');

        var city_name = $(this).find('option:selected').text()+'&nbsp;&nbsp;';
        $(this).parents('form').find('.city_name_text').html(city_name);
    });

    $(".binding_shop_type li").bind('click', function () {
        document.location.href = '/center/bind/' + $(this).data('plat_type');
    });

//    //一二级类目
//    $(".cate_one").change(function(event) {
//        $(".cate_two option:first").nextAll().remove();
//        // ajax请求返回成功之后进行的操作
//        var cate_one = $(this).val();
//        var param = {cate_one:cate_one};
//        $.post('/center/get_cate',param,function (res) {
//            var option = "";
//            $.each(res,function(index,value){
//                option += '<option value="'+value.id+'">'+value.category+'</option>';
//            });
//            $(".cate_two").append(option);
//        },'json');
//    });
})
</script>
</body>
</html>