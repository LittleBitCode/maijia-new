<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/center.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/account.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<title><?php echo $html_title; ?>-<?php echo PROJECT_NAME; ?></title>
<style>.form-control { display: inline-block; }</style>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'member']); ?>
    <div class="center_box">
        <div class="account_box ">
            <h1>提现/退款账号管理<small style="margin-left: 16px;">银行卡与支付宝请一同填写提交，否则系统无法审核提现账号</small></h1>
            <!-- 绑定银行卡start -->
            <div class="account_bank white_box">
                <div class="bind_bank">
                    <?php if(empty($account['bank_status'])):?>
                    <!-- 银行卡未绑定 -->
                    <div class="bind_title">
                        <img src="/static/imgs/icon/Bank-card.png" class="logo" />
                        <b>银行卡</b><p><span>未绑定</span></p>
                    </div>
                    <!-- <a href="javascript:;" class="bind_a bind_btn">绑定</a> -->
                    <div class="bind_bank_box" style="display: block;">
                        <div class="prompt">银行卡开户名必须为<span>真实姓名</span>，和绑定的支付宝姓名一致，绑定后将<span>不能修改账号信息</span></div>
                        <div class="bind_bank_con">
                            <div class="username_div">
                                <label>姓名：</label>
                                <input type="text" name="username" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" class="form-control" />
                            </div>
                            <div class="back_div">
                                <label>选择银行：</label>
                                <select name="bank_type" class="bank_type form-control" style="width: 400px;">
                                    <option value="">请选择</option>
                                    <?php foreach($payment_list as $key => $item): ?>
                                    <option <?php if($account['bank_short_name']==$key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $item; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="address_div">
                                <label>开户行城市：</label>
                                <input type="text" class="provincebox form-control" id="provinceclose" name="province" />
                                <div class="province_detail" style="display:none;">
                                    <span class="close">x</span>
                                    <div id="provincebox" class="provinceclose" style="padding: 16px;">
                                        <div>华北
                                            <span>北京市</span><span>天津市</span><span>河北省</span><span>山西省</span><span>内蒙古自治区</span>
                                        </div>
                                        <div>华东
                                            <span>上海市</span><span>江苏省</span><span>浙江省</span><span>安徽省</span><span>福建省</span><span>江西省</span><span>山东省</span>
                                        </div>
                                        <div>华中
                                            <span>河南省</span><span>湖北省</span><span>湖南省</span>
                                        </div>
                                        <div>华南
                                            <span>广东省</span><span>广西自治区</span><span>海南省</span>
                                        </div>
                                        <div>东北
                                            <span>辽宁省</span><span>吉林省</span><span>黑龙江省</span>
                                        </div>
                                        <div>西南
                                            <span>重庆市</span><span>四川省</span><span>贵州省</span><span>云南省</span><span>西藏自治区</span>
                                        </div>
                                        <div>西北
                                            <span>陕西省</span><span>甘肃省</span><span>青海省</span><span>宁夏自治区</span><span>新疆自治区</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" class="citybox form-control" style="display:none;" name="city" />
                                <div class="city_detail" style="display:none;">
                                    <span class="close">x</span>
                                    <div id="citybox" style="padding: 16px">
                                        <span>北京</span><span>上海</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bankname_div">
                                <label>开户行支行名称：</label>
                                <input id="bankforlast" type="text" name="account_subbranchs" class="form-control" />
                                <input type="hidden" value="" regname="account_subbranch" id="account_subbranch" name="account_subbranch" />
                                <a href="javascript:;" class="backa"></a>
                                <div id="auto_div">
                                </div>
                            </div>

                            <div class="bankcode_div">
                                <label>银行卡号：</label>
                                <input type="text" id="bank" class="form-control bank_card_input" aid=0  unique=1 name="account_card" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')" />
                            </div>
                        </div>

                        <div class="button_div">
                            <input type="button" value="提&nbsp;&nbsp;交" class="bank_sub" />
                        </div>
                    </div>
                    <!-- 银行卡未绑定 -->
                    <?php endif;?>



                    <?php if($account['bank_status'] == '2' || $account['bank_status'] == '1'):?>
                    <!-- 绑定银行卡通过 -->
                    <div class="bind_title bank_inner">
                    <?php $account_cart = implode(' ', str_split($account['account_card'], 4)); ?>
                        <div class="bank_inner_tit">
                            <img src="/static/imgs/icon/Bank-card.png" class="logo" />
                            <b>银行卡</b>
                            <p>
                                <?php if ($account['bank_status'] == '1'): ?>
                                    <span class="orange">待审核</span>
                                <?php elseif ($account['bank_status'] == '2'):?>
                                    <span class="green">审核通过</span>
                                <?php endif ?>
                            </p>
                        </div>
                        <div class="bank_inner_box">
                            <img src="/static/imgs/account/<?php echo $account['bank_short_name'];?>.png">
                            <span class="bank_cord"><?php echo $account_cart;?></span>
                            <span class="username"><?php echo $account['true_name'];?></span>
                        </div>
                    </div>
                    <!-- 绑定银行卡通过 -->
                    <?php endif;?>


                    <?php if($account['bank_status'] == '3'):?>
                    <!-- 银行卡审核不通过修改start -->
                    <?php $account_cart = implode(' ', str_split($account['account_card'], 4)); ?>
                    <div class="bind_title bank_inner">
                        <div class="bank_inner_tit">
                            <img src="/static/imgs/icon/Bank-card.png" class="logo" />
                            <b>银行卡</b>
                            <p><span class="orange">审核不通过</span></p>
                        </div>
                        <div class="bank_inner_box">
                            <img src="/static/imgs/account/<?php echo $account['bank_short_name'];?>.png">
                            <span class="username"><?php echo $account['true_name'];?></span>
                            <span class="bank_cord"><?php echo $account_cart;?></span>

                        </div>
                    </div>
                    <!-- 修改首先手机号验证 -->
                    <div class="bank_update" style="<?php  if($op == 'setbank' && $step == 2) {echo "display:none";} else {echo "display:block";} ?>">
                        <form action="/center/set_bank" method="post" id="send_code_setbank">
                            <div class="verify_div renjiyanzheng" style="display: none;">
                            <!-- <div class="verify_div renjiyanzheng" > -->
                                <label>图形码：</label>
                                <div class="verify_img">
                                    <input type="text" class="form-control" id="captcha_response" name="captcha_response" value="" maxlength="4" style="width: 140px; display: table-row;" />
                                    <img id='code2' src="<?php echo site_url('service/captcha'); ?> " alt="" width="92" height="32" onclick="create_code()" style="margin-left: 20px;" />
                                </div>
                                 <span class="error" style="margin-left: 15px;margin-top: 8px; display:none;"></span>
                            </div>
                            <div class="phone_number">
                                <label class="tit">已验证手机：</label>
                                <div class="phone_info">
                                    <em><?php echo $userinfo['mobile']?substr($userinfo['mobile'], 0, 3).'****'.substr($userinfo['mobile'], 7, 4):'';?></em>
                                    <a class="btn-default verify_a" onClick="cord();" href="javascript:;" style="">发送验证码</a>
                                    <i></i>
                                    <span></span>
                                </div>
                            </div>
                            <div class="phone_verify">
                                <label class="tit">手机验证码：</label>
                                <input class="icon-verify form-control" type="text" name="phone_code" maxlength="6" />
                            </div>
                            <div class="bank_update_but"><input type="button" value="提&nbsp;交" /></div>
                        </form>
                    </div>
                    <!-- 修改绑定银行卡 -->
                    <div class="bind_bank_box" style="<?php  if($op == 'setbank' && $step == 2) {echo "display:block";} else {echo "display:none";} ?>">
                        <div class="prompt">银行卡开户名必须为<span>真实姓名</span>，和绑定的支付宝姓名一致，绑定后将<span>不能修改账号信息</span></div>
                        <div class="bind_bank_con">
                            <div class="username_div_ok">
                                <label>开户名：</label>
                                <em><?php echo $account['true_name'];?></em>
                                <p>（银行开户名默认和您绑定的支付宝姓名一致）</p>
                                <input type="hidden" name="username" value="<?php echo $account['true_name'];?>" />
                                <input type="hidden" name="account_id" value="<?php echo $account['id'];?>" />
                            </div>
                            <div class="back_div">
                                <label>选择银行：</label>
                                <select name="bank_type" class="bank_type">
                                    <option value="">请选择</option>
                                    <?php foreach($payment_list as $key => $item): ?>
                                    <option <?php if($account['bank_short_name']==$key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $item; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                            <div class="address_div">
                                <label>开户行城市：</label>
                                <input type="text" class="provincebox" id="provinceclose" name="province" value="<?php echo $account['province']; ?>" />
                                <div class="province_detail" style="display:none;">
                                    <span class="close">x</span>
                                    <div id="provincebox" class="provinceclose" style="padding: 16px;">
                                        <div>华北
                                            <span>北京市</span><span>天津市</span><span>河北省</span><span>山西省</span><span>内蒙古自治区</span>
                                        </div>
                                        <div>华东
                                            <span>上海市</span><span>江苏省</span><span>浙江省</span><span>安徽省</span><span>福建省</span><span>江西省</span><span>山东省</span>
                                        </div>
                                        <div>华中
                                            <span>河南省</span><span>湖北省</span><span>湖南省</span>
                                        </div>
                                        <div>华南
                                            <span>广东省</span><span>广西自治区</span><span>海南省</span>
                                        </div>
                                        <div>东北
                                            <span>辽宁省</span><span>吉林省</span><span>黑龙江省</span>
                                        </div>
                                        <div>西南
                                            <span>重庆市</span><span>四川省</span><span>贵州省</span><span>云南省</span><span>西藏自治区</span>
                                        </div>
                                        <div>西北
                                            <span>陕西省</span><span>甘肃省</span><span>青海省</span><span>宁夏自治区</span><span>新疆自治区</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" class="citybox" style="<?php if(!$account['city']) echo 'display:none;'; ?>" name="city" value="<?php echo $account['city']; ?>"  />
                                <div class="city_detail" style="display:none;">
                                    <span class="close">x</span>
                                    <div id="citybox" style="padding:16px"><p>
                                        <?php foreach($city_list as $key => $item): ?>
                                            <?php if($key > 0 && $key % 6 == 0) echo '</p><p>' ;?>
                                            <span><?php echo $item; ?></span>
                                        <?php endforeach; ?>
                                    </p></div>
                                </div>
                            </div>

                            <div class="bankname_div">
                                <label>开户行支行名称：</label>
                                <input id="bankforlast" type="text" name="account_subbranchs" value="<?php  echo $account['sub_branch'];?>"/>
                                <input type="hidden" value="" regname="account_subbranch" id="account_subbranch" name="account_subbranch" />
                                <a href="javascript:;" class="backa"></a>
                                <div id="auto_div">
                                    <?php foreach($bank_list  as $key =>$item): ?>
                                         <div id="<?php echo $key;    ?>" class="bank_item" style="font: 14px/25px arial; height: 25px; padding: 0px 8px; cursor: pointer;"><?php echo $item;?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="bankcode_div" >
                                <label>银行卡号：</label>
                                <input type="text" id="bank" class="bank_card_input" name="account_card" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')" aid="<?php echo $account['id'];?>" unique=1  value="<?php  echo $account['account_card'];?>" />
                            </div>

                        </div>

                        <div class="button_div">
                            <input type="button" value="确&nbsp;&nbsp;认" class="bank_sub" />
                        </div>
                    </div>
                    <!-- 银行卡审核不通过-修改end -->
                    <?php endif;?>


                </div>
            </div>
            <!-- 绑定银行卡end -->

            <!-- 绑定支付宝start -->
            <div class="account_bank white_box">
                <div class="bind_zfb">
                    <?php if($account['alipay_status'] == '0' || empty($account['alipay_status']) ):?>
                    <!-- 支付宝未绑定 -->
                    <div class="bind_title">
                        <img src="/static/imgs/icon/Alipay.png" class="logo" />
                        <b>支付宝</b>
                        <p><span>未绑定</span></p>
                    </div>
                    <div class="bind_zfb_box" style="<?php if( empty($account['alipay_status']) && empty($account['bank_status']) ) echo 'display: none;';?>">
                        <div class="prompt">支付宝必须填写<span>真实姓名</span>，请务必准确填写，绑定后将<span>不能修改账号信息</span></div>

                        <form action="/center/set_zfb_account" method="post" enctype="multipart/form-data" id="zfb_sub">
                        <div class="bind_zfb_con">
                            <h3>第一步：绑定支付宝</h3>
                            <!-- 修改时姓名不能修改 -->

                            <?php if ($account['true_name']): ?>
                                <div class="zfb_name_ok">
                                    <label style="margin-left:-30px;">姓名：</label>
                                    <em><?php echo $account['true_name'];?></em>
                                    <p>（支付宝姓名默认和您绑定的银行卡姓名一致）</p>
                                    <input type="hidden" name="zfb_name" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" value="<?php echo $account['true_name'];?>" />
                                </div>
                            <?php else:?>
                                <!-- 修改时姓名不能修改 -->
                                <div class="zfb_name">
                                    <label>姓名：</label>
                                    <input type="text" name="zfb_name" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')"/>
                                    <em>支付宝必须和银行卡姓名一致，不可修改</em>
                                </div>
                            <?php endif ?>
                            <div class="zfb_account">
                                <label>支付宝账号：</label>
                                <input type="text" name="zfb_account" class="form-control alipay_account_input" aid=0  unique=2 />
                            </div>
                            <div class="prompt"></div>

                            <h3>第二步：上传支付宝基本信息截图</h3>
                            <p class="bind_zfb_p">
                                为确保您的<?php echo PROJECT_NAME; ?>账号及退款账号为同一人，请登录您的【<span>支付宝</span>】，点击【<span>账号设置</span>】，
                                截取您的支付宝【<span>基本信息</span>】作为审核凭证。
                            </p>
                            <div class="zfb_img_min">
                                <p>如图所示：</p>
                                <a class="big_img" src="/static/imgs/account/zfbbig.jpg" href="javascript:;">点击放大截取示例
                                    <img src="/static/imgs/account/zfbbig.jpg" class="big_img" style="width: 64px;" />
                                </a>
                            </div>
                            <div class="bind_zfb_img">
                                <label>上传截图：</label>
                                <!-- <input type="file" name="bind_zfb_img" id="aligpay_img" onChange="javascript:binding_upload(this);" uploaded="0" base64="" /> -->
                                <div class="upload_wrap">
                                    <img  width="100" height="100" src="/static/imgs/logo/upload_logo.png">
                                    <input type="file" name="bind_zfb_img" id="aligpay_img" onchange="javascript:binding_upload(this);" uploaded="0" base64="">
                                </div>
                                <span class="bind_zfb_img_span">（jpg、gif、png，大小不超过1Mb）</span>
                            </div>
                        </div>
                        </form>

                        <div class="button_div">
                            <input value="提&nbsp;&nbsp;交" class="zfb_sub" type="button">
                        </div>
                    </div>
                    <?php endif;?>

                    <?php if($account['alipay_status'] == '2' || $account['alipay_status'] == '1'):?>
                    <!-- 支付宝绑定通过 -->
                    <div class="bind_title_zfb bank_inner">
                        <div class="bank_inner_tit">
                            <img src="/static/imgs/icon/Alipay.png" class="logo" />
                            <b>支付宝</b>
                            <p>
                                <?php if ($account['alipay_status'] == '1'): ?>
                                    <span class="orange">待审核</span>
                                <?php elseif ($account['alipay_status'] == '2'):?>
                                    <span class="green">审核通过</span>
                                <?php endif ?>
                            </p>
                        </div>
                        <div class="bank_inner_box">
                            <span class="zfbaccount"><?php echo $account['alipay_account']?></span>
                            <span class="username"><?php echo $account['true_name']?></span>
                            <span class="bind_pay_img enlarge_img"><img src="<?= $account['alipay_img'] ?>" class="big_img" style="margin-top:26px;"></span>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if($account['alipay_status'] == '3'):?>
                    <!-- 支付宝绑定未通过 -->
                    <div class="bind_title_zfb bind_title bank_inner">
                        <div class="bank_inner_tit">
                            <img src="/static/imgs/icon/Alipay.png" class="logo" />
                            <b>支付宝</b>
                            <p><span class="orange">审核不通过</span></p>
                        </div>
                        <div class="bank_inner_box">
                            <span class="zfbaccount"><?php echo $account['alipay_account']?></span>
                            <span class="username"><?php echo $account['true_name']?></span>
                            <span class="bind_pay_img enlarge_img"><img src="<?= $account['alipay_img'] ?>" class="big_img" style="margin-top:26px;"></span>
                        </div>
                    </div>
                    <div class="bind_zfb_box" style="display: block;">
                        <div class="prompt">支付宝必须填写<span>真实姓名</span>，请务必准确填写，绑定后将<span>不能修改账号信息</span></div>
                        <form action="/center/set_zfb_account" method="post" enctype="multipart/form-data" id="zfb_sub">
                        <div class="bind_zfb_con">
                            <h3>第一步：绑定支付宝</h3>

                            <?php if ($account['true_name']): ?>
                                <div class="zfb_name_ok">
                                    <label style="margin-left:-30px;">姓名：</label>
                                    <em><?php echo $account['true_name'];?></em>
                                    <p>（支付宝姓名默认和您绑定的银行卡姓名一致）</p>
                                    <input type="hidden" name="zfb_name" value="<?php echo $account['true_name'];?>" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" />
                                </div>
                            <?php else:?>
                                <!-- 修改时姓名不能修改 -->
                                <div class="zfb_name">
                                    <label>姓名：</label>
                                    <input type="text" name="zfb_name" onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')"/>
                                    <em>支付宝必须和银行卡姓名一致，不可修改</em>
                                </div>
                            <?php endif ?>

                            <div class="zfb_account">
                                <label>支付宝账号：</label>
                                <input type="text" name="zfb_account" class="alipay_account_input" aid="<?php echo $account['id'];?>" value="<?php echo $account['alipay_account'];?>" unique=2 />
                            </div>
                            <div class="prompt"></div>

                            <h3>第二步：上传支付宝基本信息截图</h3>
                            <p class="bind_zfb_p">为确保您的<?php echo PROJECT_NAME; ?>账号及退款账号为同一人，请登录您的【<span>支付宝</span>】，点击【<span>账号设置</span>】，</p>
                            <div class="zfb_img_min">截取您的支付宝【<span>基本信息</span>】作为审核凭证。<a class="big_img" src="/static/imgs/account/zfbbig.jpg" href="javascript:;">点击放大截取示例<img src="/static/imgs/account/zfbbig.jpg" class="big_img" style="width:64px;" /></a></div>
                            <div class="bind_zfb_img">
                                <label>上传截图：</label>
                                <div class="upload_wrap">
                                    <img  width="100" height="100" src="/static/imgs/logo/upload_logo.png">
                                    <input type="file" name="bind_zfb_img" id="tengpay_img" onchange="javascript:binding_upload(this);" uploaded="0" base64="">
                                </div>
                                <span class="bind_zfb_img_span">jpg、gif、png，大小不超过1Mb</span>
                            </div>
                        </div>
                        </form>

                        <div class="button_div">
                            <input value="确&nbsp;&nbsp;认" class="zfb_sub" type="button">
                        </div>
                    </div>
                    <?php endif;?>


                </div>
            </div>
            <!-- 绑定支付宝end -->

            <!-- 温馨提示 -->
            <!-- <div class="reminder">
                <div>温馨提示：绑定提现账号必须两个全部绑定，没有完成则不能接手活动。支付宝、财付通和银行卡绑定必须是本人真实姓名和账户，绑定的三个账户必须是同一个人，所有信息务必准确填写，绑定后将不能修改账户信息，如有问题请联系客服QQ<a style="color: red;" target="_blank" href="http://q.url.cn/AB9vcc?_type=wpa&qidian=true">800828297</a></div>
                <p>为确保您资金安全，绑定后提现信息不可修改，即便账号被盗，您账号里的资金也不会转移到他人的账户中</p>
            </div>  -->
        </div>
    </div>
<!-- 查看支付宝大图弹窗 -->
<div class="modal fade big_img_tan" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <img src="" style="width: 1024px;" />
        </div>
    </div>
</div>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script language="javascript" src="/static/js/exif.js"></script>
<script language="javascript" src="/static/js/lrz.js"></script>
<script type="text/javascript">
$(function(){
    // 选择银行
    $(".bank_type").change(function(){
        $(this).siblings(".error").remove();
    });
	//选择地区
	$(".provincebox").click(function(){
        if($(".bank_type").val() == ""){
            toastr.warning("请先选择银行");
            return false;
        };
        $(".province_detail").show();
        $(".city_detail").hide();
    });
	$(".close").click(function(){
    	$(this).parent().hide();
    });
	//选择城市后展示市区列表
    $("#provincebox div span").click(function () {
        var _province = $(this).html();
        $(".provincebox").val(_province);
        $(".province_detail").hide();
        $(".citybox").val('').show();
        $.post('/center/get_bank_region', {province: _province}, function (data) {
            var _result = $.parseJSON(data);
            if (_result.error == '0') {
                var _city_list = _result.message;
                var _city_html = '<p>';
                for (var i in _city_list) {
                    if (i > 0 && i % 6 == 0) {
                        _city_html += '</p><p>';
                    }
                    _city_html += '<span>' + _city_list[i] + '</span>';
                }
                _city_html += '</p>';
                $('#citybox').html(_city_html);
                $(".citybox").val('').show();
                //$(".city_detail").show();
            } else {
                toastr.error(_result.message);
            }
        });
    });
	
	//选择市区
	$(".citybox").click(function(){
        $(".city_detail").show();
        $(".province_detail").hide();
    });

	$(document).on("click", "#citybox span", function () {
        var _bn = $('.bank_type').val();
		var _prov = $('.provincebox').val();
		var _city = $(this).html();
        if(_bn==''){
			toastr.warning("请先选择银行名称");
            return false;
		}
		if(_prov==''){
			toastr.warning("请先选择银行所有城市");
            return false;
		}
		$(".citybox").val(_city);
		$(".city_detail").hide();
		//选择市区
		
		$.post('/center/get_bank_list', {bn:_bn, prov:_prov, city:_city}, function(data){
		    var result = $.parseJSON(data);
            $("#auto_div").children().remove();
            var back_list = "";
            $.each(result.bank,function(key,val){
                back_list += '<div id="'+ key +'" class="bank_item" style="font: 14px/25px arial; height: 25px; padding: 0px 8px; cursor: pointer;">'+ result.bank[key] +'</div>';
            });
            $("#auto_div").append(back_list);
			$(".citybox").val(_city);
			$('#bankforlast').val('');
			$(".city_detail").hide();
		});
		
    });
	
	//新添加的下拉框按钮
	$("#bankforlast").click(function(){
		var flag = $("#auto_div").css("display");
		if (flag == "none") {
			$("#auto_div").css("display", "block");
		}
		else {
			$("#auto_div").css("display", "none");
		}
	});
	
    $("#auto_div").on("click",".bank_item",function(){
        var zhihangval = $(this).html();
        var zhihangID  = $(this).attr("id");
        $("#bankforlast").val(zhihangval);
        $("#account_subbranch").val(zhihangID);
        $(this).parent().hide();
    });

    //提交绑定支付宝
    $(".zfb_sub").click(function(){
        $(".zfb_account").find('.error').remove();

        var zfb_name    = $('input[name="zfb_name"]').val().toString().replace(/ /g, "");
        var zfb_account = $('input[name="zfb_account"]').val();
        var zfb_img     = $('input[name="bind_zfb_img"]').val();
        // var alipayReg = /(^1[345789]\d{9}$)|(^[a-z0-9]+([._\\-a-z0-9]|[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$)/;
        //初始化错误
        $(".bind_zfb_box").find("span.error").remove();
        if( zfb_name == '' ){
            $(".zfb_name").append('<span class="error">姓名不能为空</span>');
        }else if(zfb_name.length<2 || zfb_name.length>6){
            $(".zfb_name").append('<span class="error">姓名为２～６位汉字</span>');
        }
        
        if( zfb_account == '' ){
            $(".zfb_account").append('<span class="error">支付宝账号不能为空</span>');
        }// else if(!(alipayReg.test(zfb_account))){
        //     $(".zfb_account").append('<span class="error">支付宝账号格式错误</span>');
        // }
        
        if( zfb_img == ''){
            $(".bind_zfb_img").append('<span class="error">上传截图不能为空</span>');
        }

        
        if( $(".bind_zfb_box").find("span.error").size() == 0 ){
            check_alipay();
            if(c_alipay)return;

            var aligpay_img = $('input[name="bind_zfb_img"]').attr("base64");
            // 提交支付宝绑定信息
            var data = {
                true_name:zfb_name,
                alipay_account:zfb_account,
                alipay_img:aligpay_img,
                };
            $.ajax({
                 type: "POST",
                 url: "/center/set_zfb_account",
                 data :data,
                 dataType:"json",
                 async:true,
                 success: function(res) {
                    if(res.err == 0) {
                        alert(res.info);
                    }
                    location.reload();
                 }
            });
        }
    });

	//提交绑定银行卡
	$(".bank_sub").click(function(){
		var username   = $('input[name="username"]').val().toString().replace(/ /g, "");
		var bank_type  = $(".bank_type").val();
		var province   = $('input[name="province"]').val();
		var city       = $('input[name="city"]').val();
		var bankforlast= $("#bankforlast").val();
		var accountcard = $('input[name="account_card"]').val();
        accountcard = accountcard.toString().replace(/ /g, "");
        var id   = $('input[name="account_id"]').val();

		if( username == '' ){
			toastr.warning("姓名不能为空"); return false;
		}else if(username.length<2 || username.length>6){
            toastr.warning("姓名为２～６为汉字"); return false;
        }
		if( bank_type == '' ){
			toastr.warning("请先选择银行"); return false;
		}
		
		if( province == '' || city == '' ){
			toastr.warning("请选择开户行所在地区"); return false;
		}
		
		if( bankforlast == '' ){
            toastr.warning("请填写开户行支行名称"); return false;
		}
		var bankReg = /^([1-9]{1})(\d{14}|\d{15}|\d{17}|\d{18})$/;
		if( accountcard == '' ){
            toastr.warning("银行卡号不能为空"); return false;
		}else if(!bankReg.test(accountcard)){
            toastr.warning("银行卡号为15,16,18或19位的数字"); return false;
        }
		
		if( $(".bind_bank_con").find("span.error").size() == 0 ){
            check_bank();
            if (c_bank) return;
            $.ajax({
                 type: "POST",
                 url: "/center/set_bank_account",
                 data : "account_name="+username+"&account_card="+accountcard+"&province="+province+"&city="+city+"&sub_branch="+bankforlast+"&id="+id+"&bank_type="+bank_type,
                 dataType:"json",
                 async:true,
                 success: function(res) {
                    // console.log(res);
                    if(res.err == 0) {
                        toastr.error(res.info);
                    }
                    location.reload();
                 }
            });            
		}
	})
	
    // 修改
    $(".edit_bind_btn").click(function(){
        $(this).parents(".bind_title").next("div").toggle();
    });


    // 银行卡号是否使用验证
    $(".bank_card_input").blur(function(){
        check_bank();
    });

    var c_bank;
    function check_bank(){
        var number = $(".bank_card_input").val().toString().replace(/ /g, "");
        var id = $(".bank_card_input").attr('aid');
        var unique = $(".bank_card_input").attr('unique');
        if(!number){
            c_bank = true;
            return;
        }

        $.ajax({
            type: "POST",
            data: {number:number,id:id,unique:unique},
            dataType: "json",
            async:false,
            url: "/center/check_unique",
            success: function(result) {
                if (result.err == 0) {
                    //alert(result.info);
                    toastr.error("银行卡号已存在");
                    c_bank = true;
                }else{
                    c_bank = false;
                } 
            }
        });
    }


    // 支付宝号是否使用验证
    $(".alipay_account_input").blur(function(){
        check_alipay();
    });

    var c_alipay;
    function check_alipay(){
        var number = $(".alipay_account_input").val().toString().replace(/ /g, "");
        var id = $(".alipay_account_input").attr('aid');
        var unique = $(".alipay_account_input").attr('unique');
        var alipayReg = /(^1[345789]\d{9}$)|(^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+)/;
        if( number == '' ){
            toastr.warning("支付宝账号不能为空");
            c_alipay = true;
            return false;
        }else if(!(alipayReg.test(number))){
            toastr.warning("支付宝账号格式错误");
            c_alipay = true;
            return false;
        }

        $.ajax({
            type: "POST",
            data: {number:number,id:id,unique:unique},
            dataType: "json",
            async:false,
            url: "/center/check_unique",
            success: function(result) {
                if (result.err == 0) {
                    toastr.error("支付宝账号已存在");
                    c_alipay = true;
                }else{
                    c_alipay = false;
                } 
            }
        });
    }
});

	//修改银行卡手机号验证
	function cord() {
    	var mobile  = "<?= $userinfo['mobile']; ?>";
        var captcha_response = null;
        if (i >= 3 || $(".renjiyanzheng").is(':visible')) {
            captcha_response = $('#captcha_response').val();
            if (captcha_response == '') {
                toastr.warning("请先填写图形验证码");
                return false;
            }
        }

        $.ajax({
        	type: "POST",
            data: {mobile: mobile, captcha_response: captcha_response, i: i},
            dataType: "json",
            url: "/user/send_bank_message",
            success: function(result) {
                if (result.state == 2) {
                    $(".verify_a").parent("div").children("span").remove();
                    wait = 60;
                    cords();    // 正确后调用样式
                } else if (result.state == 1) {
                    create_code();
                    $(".verify_a").parent("div").children("span").remove();
                    toastr.error(result.msg);
                } 
            },
            error: function() {
                create_code();
                $(".verify_a").parent("div").children("span").remove();
                toastr.error("验证码发送失败");
                return false;
            }
        });
    }

    // 手机验证码时间刷新
    var wait = 60;
    function cords() {
        if (wait == 0) {
			$(".verify_a").siblings("i").hide();
            $(".verify_a").show().html('获取验证码');
            wait = 60;
        } else {
			$(".verify_a").hide();
            $(".verify_a").siblings("i").show().html(wait+'s后可重发');
            wait--;
            setTimeout(function() {
                cords();
            }, 1000)
        }
    }

    if (i>3) {
        var captcha = false;
    }

    //修改银行卡验证手机号提交
    var i = 0;
    $(".bank_update_but input").click(function(){
        $(".phone_verify").children('.error').remove();
        var mobile = '<?= $userinfo['mobile'];?>';
        var phone_verify = $('input[name="phone_code"]').val();
        var captcha_response = null;
        $(".bank_update_but").children("span.error").remove();
        if (i >= 3 || $(".renjiyanzheng").is(':visible')){
            captcha_response = $('#captcha_response').val();
            if (captcha_response == ''){
                toastr.warning("请先填写图形验证码");
                return false;
            }
        }
        if( phone_verify == '' ){
            toastr.warning("手机验证码不能为空");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/center/check_code_nums",
            data: {"m": mobile, "v": phone_verify, "i": i, "captcha": captcha_response},
            dataType: "json",
            async:false,
            success: function (d) {
                if (d.status != 1) {
                    i++;
                    if (i >= 3) {
                        $(".renjiyanzheng").show();
                        create_code();
                    }
                    toastr.error(d.msg);
                } else {
                    $('#send_code_setbank').submit();
                }
            }
        });
    });

	//点击查看大图
    $(".big_img").click(function () {
        $(".big_img_tan img").attr("src", $(this).attr("src"));
        $(".big_img_tan").modal();
    });

    // 图片上传按钮
    $(".upload_wrap img").click(function(){
        $(this).siblings('input[type=file]').click();
    });
	//绑定买号上传图片验证
    function binding_upload(event) {
        var docObj=document.getElementById(event.id);
        lrz(event.files[0],  function(res){
            $(event).attr('base64', res.base64);
            $(event).attr('uploaded', 1);
        });
        // 验证图片格式
        if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])){
            toastr.warning("图片格式错误");
            $(event).attr('base64', "");
            $(event).attr('uploaded', 0);
            return false;
        }else{
            //验证图片大小
            if( docObj.files[0]['size'] > 1024*1024 ){
                toastr.warning("图片格式错误");
                $(event).attr('base64', "");
                $(event).attr('uploaded', 0);
                return false;
            }
        }

        //显示预览图片
        if(docObj.files && docObj.files[0]){
            //火狐下，直接设img属性
            $(event).siblings('img').prop("src",window.URL.createObjectURL(docObj.files[0]))
        }
    }

    // 验证码切换
    function create_code(){
        var URL = "<?php echo site_url('service/captcha');?>";
        document.getElementById('code2').src = URL+'?'+Math.random()*10000;
    }
</script>
</body>
</html>