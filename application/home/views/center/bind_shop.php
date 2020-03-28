<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico" />
    <link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>" />
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/static/css/binding.css?v=<?= VERSION_TXT ?>" />
    <link rel="stylesheet" href="/static/toast/toastr.min.css" />
    <title>绑定店铺-<?php echo PROJECT_NAME; ?></title>
    <style>
        .form-control{padding-left:8px !important;}
        .Process{padding:36px 30px 0 0; overflow:hidden; position:relative;}
        .Process ul{position:relative; zoom:1; height:6px; line-height:6px;width:196px;}
        .Process ul li{float:left; background: url(/static/imgs/trade/Processdis.png) repeat-x; height:6px; position:relative;}
        .Process ul li.Processlast{position:absolute; left:100%; background:none; top:0;}
        .Process ul li em{background:url(/static/imgs/trade/Process.png) no-repeat -85px 0; width:34px; height:34px; font-size:14px; line-height:34px; display:block; position:absolute; left:-17px; top:-12px; text-align:center;}
        .Process ul li span{position:absolute; left:-50%; height:32px; font-size:12px; color:#666; line-height:40px; top:-46px; text-align:center; width:100%; display:block; overflow:hidden;}
        .Process ul li.cur{background:url(/static/imgs/trade/Processpass.png) repeat-x}
        .Process ul li em.Processyes{color:#FFF; background-position:0px 0px;}
        .title_left{float:left;width: 50%;line-height: 64px;font-size:20px;}
        .title_left span{font-weight:bold}
        .title_right{float:left; width: 50%;text-align: right;text-align: -webkit-right;}
        .bind_head{width:100%; display: inline-block;}
        .code_info .taobao {
            margin-top: 29px;
            margin-left: 401px;
        }
    </style>
</head>
<body>
<?php $this->load->view("/common/top1", ['site' => 'manage']); ?>
<div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
    <div class="contain" style="min-height: 71vh;width: 947px;float: left;">
        <div class="binding_box">
            <div class="bind_head">
                <div class="title_left"><span>绑定新店铺</span><small><small>（仅活动对应的买手可见，不会被泄露）</small></small></div>
                <div class="Process title_right">
                    <ul class="clearfix">
                        <li style="width:96px" class="cur"><em class="Processyes">1</em><span>注册账号</span></li>
                        <li style="width:96px" class="cur"><em class="Processyes">2</em><span>购买VIP</span></li>
                        <li style="width:96px" class="Processlast"><em class="Processyes">3</em><span>绑定店铺</span></li>
                    </ul>
                </div>
            </div>
            <div class="binding_notice">请先完成下面的店铺信息，绑定店铺后即可进入报名活动页面</div>
            <div class="binding_shop">
                <div class="bind_shop_r">
                    <div class="bind_detail">
                        <div class="bind_div_list">
                            <label>店铺类型：</label>
                            <?php foreach ($bind_list as $key => $value): ?>
                                <label style="padding-left:16px;width:84px;text-align:left"><input type="checkbox"  value="<?= $value['name'] ?>" name="shop_type" <?= ($value['name'] == $bind_shop) ? 'checked':''; ?> class="binding_shop_type" />&nbsp;<?= $value['pname']; ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if(count($bind_shop_list) <  MAX_BIND_SHOP_CNT ): ?>
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
                            <div class="bind_div_list shipping_box">
                                <label>选择快递：</label>
                                <?php foreach ($shipping_list as $key => $item): if ($item['is_show'] == '0') continue; ?>
                                    <label style="width:86px;text-align:center;white-space: nowrap;"><input type="checkbox" value="<?= $key ?>" <?= ($item['default']) ? 'checked':''; ?> class="shipping" name="shipping" />&nbsp;<?= $item['name'] ?></label>
                                <?php endforeach; ?>
                                <small style="display: inline-block;margin-left: 178px">平台根据您的选择提供真实物流快递，3元/单。（自发快递由买手提供签收服务。为了订单安全，自发快递请勿使用虚假单号。 本服务活动期间暂免费）</small>
                            </div>
                            <div class="bind_div_list address">
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
                            <p class="address_detail"><input type="text" class="form-control send_address"/></p>

                            <div class="bind_div_list">
                                <label>发件人姓名：</label>
                                <input type="text" class="form-control username" />
                            </div>

                            <div class="bind_div_list">
                                <label>发件人电话：</label>
                                <input type="text" maxlength="11" class="form-control phone" onKeyUp="value=value.replace(/[^\d]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" style="display: inline-block" />
                                <span style="white-space: nowrap"><small>（请填写您店铺的真实发件人信息，此信息会打印在快递面单上）</small></span>
                            </div>

<!--                            --><?php //if(in_array($bind_shop,array('taobao','tmall','jd', 'pdd'))): ?>
<!--                                <div class="bind_div_list">-->
<!--                                    <label>验证码：</label>-->
<!--                                    <div class="bind_code">-->
<!--                                        <span class="copy-code">--><?php //echo $bind_shop_goods_code; ?><!--</span>-->
<!--                                        <a href="javascript:;" class="J_copytext copy-code1" data-copy="--><?php //echo $bind_shop_goods_code; ?><!--">复制</a>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                                <div class="code_info">-->
<!--                                    <p>1、将验证码加到您的店铺里某个商家商品的标题上，类似这样：</p>-->
<!--                                    <span class="--><?//= $bind_shop; ?><!--">--><?php //echo $bind_shop_goods_code; ?><!--</span>-->
<!--                                    <img style="width: 816px;" src="/static/imgs/binding/--><?php //echo $bind_shop;?><!--.png" />-->
<!--                                    <br /><br />-->
<!--                                    <p>2、再将这个商品的详情页链接，复制到下面输入框</p>-->
<!--                                    <p>提示：店铺绑定成功后，商品标题中添加的验证码可以去掉；</p>-->
<!--                                </div>-->
<!--                            --><?php //endif; ?>
                            <div class="bind_div_list">
                                <label>商品网址（URL）：</label>
                                <input type="text" class="form-control good_url" />
                            </div>

                            <p class="bind_prompt">如无法绑定店铺或绑定店铺失败，请联系在线客服处理</p>
                        </div>
                        <input type="button" class="bind_sub" style="display:inline-block;background-color: #ff6b71;border: 1px solid #ff6b71; " value="确认绑定" />
                    <?php endif; ?>
                    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-body" style="text-align:center;margin-top:400px;">
                                <img src="/static/imgs/jiazaizhong.gif">
                            </div>
                        </div>
                    </div>
                    <!-- 模态框（Modal） -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" >
                                <div class="modal-header"style="border:none !important;">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel" style="text-align:center;font-size:30px;margin-top:20px;">
                                        <img src="/static/imgs/bindimg.png">
                                        &nbsp;&nbsp;恭喜,你的店铺已经绑定成功
                                    </h4>
                                </div>
                                <div class="modal-body" style="text-align:center;font-size:15px;">
                                    <span>无需等待客服审核即可直接发布任务，如你绑定的店铺有任何问题</span><br><span>客服会第一时间QQ、电话通知你</span>
                                </div>
                                <div class="modal-footer" style="border:none !important;text-align:center!important;">
                                    <a href="/trade/step" style="display:block"><button style="background:#ed702c;width:200px;height:40px;color:white;border-radius:5px;">前去报名活动</button></a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal -->
                    </div>
                </div>


            </div>


            <!-- 已绑定列表start -->
            <div class="bind_list_box">
                <h3>已绑定的店铺<span>每个平台可绑定<?= MAX_BIND_SHOP_CNT ?>个店铺</span></h3>
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 98px">所属平台</th>
                        <th style="width: 206px">店铺名</th>
                        <?php if(in_array($bind_shop,array('tmall','taobao'))): ?>
                            <th style="width: 206px">店铺旺旺</th>
                        <?php endif; ?>
                        <th>店铺网址</th>
                        <th style="width: 118px">状态</th>
                        <th style="width: 110px">绑定日期</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($bind_shop_list) && is_array($bind_shop_list)){ ?>
                        <?php foreach ($bind_shop_list as $key => $value) { ?>
                            <tr style="height: auto;">
                                <td><?= $value->plat_name; ?></td>
                                <td style="white-space:nowrap"><?= $value->shop_name; ?></td>
                                <?php if(in_array($bind_shop,array('tmall','taobao'))):?>
                                    <td><?= $value->shop_ww; ?></td>
                                <?php endif; ?>
                                <td class="overfloat-hidden-3"><?= $value->shop_url; ?></td>
                                <td style="white-space:nowrap"><?= ($value->shop_status == '1') ? '审核通过':'审核中'; ?></td>
                                <td style="white-space:nowrap"><?= date('Y-m-d H:i', $value->add_time); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else{ ?>
                        <tr><td colspan="6" style="text-align: center; height: 80px; line-height: 100px; color: #337ab7; font-size: 16px;">暂还没有绑定好店铺</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div style="width:100%;text-align:center">共 <?= count($bind_shop_list) ?> 条</div>
            </div>
            <!-- 已绑定列表end -->
        </div>
    </div>
</div>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
    $(function(){
        //确认绑定
        $(".bind_sub").bind("click", function () {
            var plat_type = "<?php echo $bind_shop;?>"; //店铺类型
            var shop_id = $(".shop_id").val();
            var shop_name = $(".shop_name").val();
            var shop_url = $(".shop_url").val();
            var province = $(".province").find('option:selected').text();
            var city = $(".city").find('option:selected').text();
            var county = $(".county").find('option:selected').text();
            var username = $(".username").val();
            var phone = $(".phone").val();
            var good_url = $(".good_url").val();
            var send_addr = $(".send_address").val();
            var shipping = $('.shipping_box').find('input[name="shipping"]:checked');
            var bind_sub = true;
            var phone_reg = /^1(3|4|5|7|8|9)\d{9}$/;

            if (shop_id == '') {
                toastr.warning("店铺旺旺不能为空，请填写");
                return false;
            }
            if (shop_name == '') {
                toastr.warning("店铺名称不能为空，请填写");
                return false;
            }
            if (shop_url == '') {
                toastr.warning("店铺首页网址不能为空，请填写");
                return false;
            }
            if (shipping.length <= 0) {
                toastr.warning("请选择店铺快递的配送方式");
                return false;
            }
            if (plat_type == "taobao") {
                if (shop_url.indexOf("tmall.com") < 1 && (shop_url.indexOf("taobao.com") > 0 || shop_url.indexOf("fliggy.com") > 0 || shop_url.indexOf("alitrip.com") > 0 || shop_url.indexOf("jiyoujia.com") > 0)) {

                } else {
                    toastr.warning("请检查链接是否为淘宝链接");
                    return false;
                }
            } else if (plat_type == "tmall") {
                if (shop_url.indexOf("tmall.com") > 0 || shop_url.indexOf("tmall.hk") > 0 || shop_url.indexOf("yao.95095.com") > 0) {

                } else {
                    toastr.warning("请检查链接是否为天猫链接");
                    return false;
                }
                if (good_url.indexOf("tmall.com") > 0 || good_url.indexOf("tmall.hk") > 0 || good_url.indexOf("95095.com") > 0 || good_url.indexOf("fliggy.com") > 0 || good_url.indexOf("jiyoujia.com") > 0) {

                } else {
                    toastr.warning("请检查链接是否为天猫链接");
                    return false;
                }
            } else if (plat_type == "jd") {
                if (shop_url.indexOf("jd.com") > 0) {

                } else {
                    toastr.warning("请检查链接是否为京东链接");
                    return false;
                }
            } else {
                if (shop_url.indexOf(".com") > 0) {

                } else {
                    toastr.warning("请输入正确的店铺网址");
                    return false;
                }
            }

            var check_province = $(".province").val();
            var check_city = $(".city").val();
            var check_country = $(".county").val();
            if (!check_province || !check_city || !check_country || check_province == '请选择省份' || check_city == '请选择城市' || check_country == '请选择区域') {
                toastr.warning("请选择正确的发件地址");
                return false;
            }

            if (username == '') {
                toastr.warning("发件人姓名不能为空");
                return false;
            }
            if (send_addr == '') {
                toastr.warning("详细发件地址不能为空");
                return false;
            }
            if (phone == '') { // || (!(phone_reg.test(phone)))
                toastr.warning("手机号格式不正确");
                return false;
            }
            if (good_url == '') {
                toastr.warning("商品网址不能为空");
                return false;
            }
            // 基本验证后可提交
            $.ajax({
                url: '/center/add_shop',
                type: "post",
                dataType: 'json',
                data: {
                    plat_type: plat_type,
                    shop_id: shop_id,
                    shop_url: shop_url,
                    province: province,
                    city: city,
                    county: county,
                    username: username,
                    phone: phone,
                    goods_url: good_url,
                    shop_name: shop_name,
                    send_addr: send_addr,
                    copy_code: $(".copy-code").html(),
                    shipping: shipping.val()
                },
                success: function (data) {
                    if (data.is_success == 1) {
                        $('#myModal').modal('show');
                    } else {
                        toastr.error(data.msg);
                        return false;
                    }
                }
            })
        });

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

        $(".binding_shop_type").click(function () {
            $('#myModal2').modal('show');
            location.href = '/center/bind/' + $(this).val();
        });
        // 快递配送选择
        $(".shipping_box label").click(function () {
            var $this = $(this);
            $this.siblings("label").children("input").attr("checked", false);
            $this.children("input").prop("checked", true);
        });
    })
</script>
</body>
</html>