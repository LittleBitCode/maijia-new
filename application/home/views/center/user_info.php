<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<link rel="stylesheet" href="/static/css/userinfo.css">
<title>基本信息-<?php echo PROJECT_NAME; ?></title>
<style> .form-control { display: inline-block; }</style>
</head>
<body>
	<?php $this->load->view("/common/top1", ['site' => 'member']); ?>
    <div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
	<div class="content flex" style="width: 947px;float: left;">
		<div class="right_wrap">
			<div class="right_content">
				<p class="title2">账号设置</p>
               	<div class="userinfo_item">
               		<div class="edit_wrap"></div>
               	</div>
               	<div class="userinfo_item">
               		<div class="userinfo_item_head">
               			<span class="userinfo_item_name">登录密码</span>
	               		<p class="userinfo_item_state color_red">已设置</p>
	               		<div class="draw_edit_btn_wrap">
	               			<a class="userinfo_item_edit edit_btn" href="javascript:;">修改</a>
	               		</div>
               		</div>

               		<div class="edit_wrap submit1_wrap">
               			<form action="update_login_password" method="POST" name = "update_login_password">
               				<p class="edit_wrap_title">为了您的账号安全，请定期更换登录密码，并确保登录密码设置与提现密码不一样。</p>
	               			<div class="edit_item">
	               				<span class="edit_item_name">原密码</span>
								<span class="glyphicon glyphicon-lock" style="position: absolute; margin-top: 7px; margin-left: 12px; color: #aaa;"></span>
								<input class="old_pwd account_pwd form-control" type="password" placeholder="请输入密码" name="password">
	               				<img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
	               				<span class="error">密码格式错误</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name">新密码</span>
								<span class="glyphicon glyphicon-lock" style="position: absolute; margin-top: 7px; margin-left: 12px; color: #aaa;"></span>
	               				<input class="new_pwd account_pwd form-control" type="password" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[\u4e00-\u9fa5]/g,''))" onkeyup="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'')" placeholder="请输入新密码" name = "new_password">
								<img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
	               				<span class="error">密码为6~16位数字、字母包含两种</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name">确认新密码</span>
								<span class="glyphicon glyphicon-lock" style="position: absolute; margin-top: 7px; margin-left: 12px; color: #aaa;"></span>
	               				<input class="news_pwd account_pwd form-control" type="password" placeholder="请再次输入新密码" name = "two_new_password">
								<img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
	               				<span class="error">两次输入密码不一致</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name"></span>
	               				<a class="submit_form submit1" href="javascript:;">提&nbsp;&nbsp;交</a>
	               			</div>
               			</form>
               		</div>
               	</div>



               	<div class="userinfo_item">
               		<div class="userinfo_item_head">
               			<span class="userinfo_item_name">提现密码</span>
	               		<p class="userinfo_item_state color_red"><?php if(empty($result->trade_password)) echo '未设置'; else echo '已设置'; ?></p>
	               		<div class="draw_edit_btn_wrap">
               			<?php 
	               			if(empty($result->trade_password)) 
	               				echo '<a class="userinfo_item_edit edit_btn" href="javascript:;">设置</a>'; 
	               				else 
	               				echo '<a class="userinfo_item_edit edit_btn" href="javascript:;">修改</a>';
	               		?>
	               		</div>	
               		</div>
               		<div class="edit_wrap">
               			<div class="three_steps">
               				<ul class="three_steps_list">
								<li class="steps_item1 steps_item_complete1">
									<span>1</span>
									<p>验证已绑定手机号</p>
								</li>
								<li class="steps_item1 steps_item_underway1">
									<span>2</span>
									<p>重设提现密码</p>
								</li>
								<li class="steps_item2 steps_item_underway1">
									<span>3</span>
									<p>成功</p>
								</li>
							</ul>
							<ul class="three_steps_list_bg">
								<li class="steps_item_bg2 steps_item_bg_complete2"></li>
								<li class="steps_item_bg2"></li>
							</ul>
               			</div>

               			<form class="draw_pwd_edit1" action="" method="" name = "" style="display:block">
	               			<div class="edit_item inlin_flex align-items">
	               				<span class="edit_item_name yzm" style="display: none;">图形码：</span>
	               				<!-- <strong class="img_code"> -->
			                    <div class="input_wrap renjiyanzheng" style="display: none;">
			                        <div class="border_wrap">
			                            <input type="text" class="form-control" id="captcha_response" name="captcha_response" value="" maxlength="4" style="display: table-row; width: 140px;" />
										<img id='code2' src="<?php echo site_url('service/captcha'); ?> " alt="" width="92" height="33" style="margin-top: -4px;" onclick="create_code()" />
										<span class="error" style="display: none" id="error_img">图形验证码输入有误，请检查</span>
			                        </div>
			                        <p></p>
			                    </div>
	               				<!-- </strong> -->
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name">已验证手机：</span>
	               				<span><?php echo $result -> mobile; ?></span>
	               				<a class="get_phone_code get_phone_code_btn btn-default" href="javascript:;">发送验证码</a>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name">手机验证码：</span>
	               				<input class="news_pwd phone_code form-control" type="text" maxlength="6" placeholder="请输入验证码" name = "check_verification_code" />
	               				<span class="error">验证码错误</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name"></span>
	               				<a class="submit_form submit2" href="javascript:;">提&nbsp;&nbsp;交</a>
	               			</div>
               			</form>


               			<form class="draw_pwd_edit2" action="update_trade_password" method="POST" name = "update_trade_password" style="display:none">
	               			<div class="edit_item">
	               				<span class="edit_item_name">请输入新的提现密码</span>
								<span class="glyphicon glyphicon-lock" style="position: absolute; margin-top: 7px; margin-left: 12px; color: #aaa;"></span>
	               				<input class="new_draw_pwd account_pwd form-control" type="password" onblur="prompt(this)" onfocus="prompts(this)" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[\u4e00-\u9fa5]/g,''))" onkeyup="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'')" placeholder="请输入密码" name = "new_password">
	               				<img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
	               				<span class="input_prompt">长度6~16位数字、字母、字符包含两种 </span>
	               				<span class="error">密码为6~16位数字、字母包含两种</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name">再次输入新的提现密码</span>
								<span class="glyphicon glyphicon-lock" style="position: absolute; margin-top: 7px; margin-left: 12px; color: #aaa;"></span>
	               				<input class="news_draw_pwd account_pwd form-control" type="password" placeholder="请再次输入密码" name = "two_new_password">
	               				<img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
	               				<span class="error">两次输入密码不一致</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name"></span>
	               				<a class="submit_form submit3" href="javascript:;">提&nbsp;&nbsp;交</a>
	               			</div>
               			</form>
               			<p class="draw_pwd_success" style="display:none">重置提现密码成功！</p>
               		</div>
               	</div>





               	<div class="userinfo_item2">
               		<p class="userinfo_item2_head">联系方式</p>

               		<!-- <div class="userinfo_contact">
               			<p class="userinfo_contact_item">
               				<span class="contact_type">微信</span>
               				<span class="contact_number"><?php echo $result -> weixin ?></span>
               				<a class="contact_edit edit_btn" href="javascript:;">修改</a>
               			</p>
               			<div class="edit_wrap edit_wrap2">
               				<p class="edit_wrap_title">为了便于商家与您联系，请填写您的常用微信号</p>
               				<div class="edit_item">
               			               				<span class="edit_item_name">常用微信号</span>
               			               				<input class="wx_number contact_type_number" type="text" onblur="prompt(this)" onfocus="prompts(this)" placeholder="请输入微信号">
               			               				<span class="input_prompt">请输入常用微信号</span>
               			               				<span class="error">微信号不能为空</span>
               			               			</div>
               			               			<div class="edit_item">
               			               				<span class="edit_item_name"></span>
               			               				<a class="submit_form submit4" href="javascript:;">提&nbsp;&nbsp;交</a>
               			               			</div>
               			</div>
               		</div> -->

               		<div class="userinfo_contact">
               			<p class="userinfo_contact_item" style="margin-top: 24px;">
               				<span class="contact_type">QQ</span>
							<span class="contact_number"><?php echo $result->qq; ?></span>
               				<a class="contact_edit edit_btn" href="javascript:;">修改</a>
               			</p>
               			<div class="edit_wrap edit_wrap2">
               				<p class="edit_wrap_title">为了便于商家与您联系，请填写您的常用QQ</p>
               				<div class="edit_item">
	               				<span class="edit_item_name">新的QQ号码</span>
								<span class="glyphicon glyphicon-headphones" style="position: absolute; margin-top: 7px; margin-left: 12px; color: #aaa;"></span>
	               				<input class="qq_number contact_type_number form-control" type="text" onblur="prompt(this)" onfocus="prompts(this)" placeholder="请输入QQ" />
	               				<span class="input_prompt">请输入常用QQ</span>
	               				<span class="error">QQ不能为空</span>
	               			</div>
	               			<div class="edit_item">
	               				<span class="edit_item_name"></span>
	               				<a class="submit_form submit5" href="javascript:;">提&nbsp;&nbsp;交</a>
	               			</div>
               			</div>
               		</div>
	               		
               		<div class="userinfo_contact">
               			<p class="userinfo_contact_item">
               				<span class="contact_type">手机</span>
               				<span class="contact_number"><?php echo $result -> mobile; ?></span>
               				<span class="contact_state">已验证完成</span>
               			</p>
               		</div>
               	</div>
            </div>
		</div>
	</div>
	</div>
	<!-- 示例截图 -->
    <script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
    <script language="javascript" src="/static/toast/toastr.min.js"></script>
	<script type="text/javascript">
		// 验证码切换
		function create_code(){
			var URL = "<?php echo site_url('service/captcha');?>";
			document.getElementById('code2').src = URL+'?'+Math.random()*10000;
		}
		// 密码输入出现一键删除按钮
        $('.account_pwd').focus(function(event) {
            $(this).siblings('.input_val_close').show();
        });

        $('.account_pwd').blur(function(event) {
        	if($(this).val() == ""){
        		$(this).siblings('.input_val_close').hide();
        	}
        });

        // 一键删除密码按钮
        $('.input_val_close').click(function(event) {
            $(this).siblings('input').val("");
        });

		// 验证码3次错误 出现人机验证 初始化为0
		var count = 0;
		// 输入规则提示
		function prompts (event) {
			$(event).siblings('.input_prompt').show().siblings('.error').hide();
		}
		function prompt (event) {
			$(event).siblings('.input_prompt').hide();
		}
		$(function(){
			$(".edit_btn").click(function(event) {
				$('.edit_wrap').slideUp('fast');
				if($(this).hasClass('active_show')){
					$(this).removeClass('active_show');
				}else{
					$(this).addClass('active_show');
					if($(this).hasClass("contact_edit")){
						$(this).parent(".userinfo_contact_item").next('.edit_wrap').slideDown('fast');
					}else{
						$(this).parents(".userinfo_item_head").next('.edit_wrap').slideDown('fast');
					}
					
				}
			});
			// 修改密码
			$(".submit1").click(function(event) {
				$(".error").hide();
				var old_pwd = $(".old_pwd").val();
				var new_pwd = $(".new_pwd").val();
				var news_pwd = $(".news_pwd").val();
				var pwd_reg = /^(?![A-Z]+$)(?![a-z]+$)(?!\d+$)(?![\W_]+$)\S{6,16}$/;
				if(old_pwd.length<6 || old_pwd.length>16){
					toastr.warning("密码格式错误");
					return false;
				}
				if(new_pwd.length<6 || new_pwd.length>16 || !pwd_reg.test(new_pwd)){
					toastr.warning("密码为6~16位数字、字母包含两种");
					return false;
				}
				
				if(news_pwd == "" || news_pwd!== new_pwd){
					toastr.warning("两次输入密码不一致");
					return false;
				}
				// 一次性获取form表单所有的value值
				// console.log($("form[name= update_login_password]").serializeArray());return;
				var pwd_json = {password:old_pwd,new_password:new_pwd,two_new_password:news_pwd};
				$.ajax({
					url:"/center/update_login_password",
					type:"post",
					dataType: 'json',
					data:pwd_json,
					success:function(res){
						if(res.success == true) {
							//alert(res.msg);
							window.location.reload();
						}else{
							toastr.error(res.msg);
						}
					},
					error : function(res) {
						toastr.error("系统异常，请联系管理同学");
					}
				});
			});


			// 发送验证码请求
			$(".edit_item").on("click",".get_phone_code_btn",function(){
				// 图形验证码
				var captcha_response = $('#captcha_response').val();
				if(captcha_response == "" && count >= 3){
					toastr.warning("请先进行图形验证码验证");
					return false;
				}

				if (getCodeSwitch) return false;
				$.ajax({
						url:"/center/send_verification_code",
						type:"post",
						dataType: 'json',
						data:{count: count, captcha_response: captcha_response},
						success:function(res){
							if(res.success == true) {
								countDown = 60;
								getCode();
							}else{
								create_code();
								toastr.error(res.msg);
							}
						},
						error : function(res) {
							create_code();
						}
				});
			});


			// 修改提现密码第一步(验证验证码) 第二步
			$(".submit2").click(function(event) {
				$(".error").hide();

				// 人机验证
				var captcha_response = $('#captcha_response').val();
				if(captcha_response == "" && count >= 3){
					$(".yzm").css('display','block'); 
					$(".renjiyanzheng").css('display','block');
					++count;
					if(captcha_response == "" && count > 4) {
						toastr.warning("请先进行图形验证码验证");
						return false;
					}
					return;
				}

				var phone_code = $(".phone_code").val();
				if(phone_code == ""){
					toastr.warning("短信验证码不能为空")
					return false;
				}
				$.ajax({
						url:"/center/check_verification_code",
						type:"post",
						dataType: 'json',
						data:{
							count : count,
							vcode : phone_code,
							captcha_response : captcha_response
						},
						success:function(res){
							if(res.success == true) {
								$(".three_steps .steps_item_complete1").next(".steps_item_underway1").addClass('steps_item_complete1').removeClass('steps_item_underway1');
								$(".three_steps .steps_item_bg2").addClass("steps_item_bg_complete2");
								$(".draw_pwd_edit1").hide().next(".draw_pwd_edit2").show();
							}else{
								++count;
								create_code();
								toastr.error(res.msg);
							}
						}
				});
			}); 


			//修改提现密码
			$(".submit3").click(function(event) {
				$(".error").hide();
				var new_draw_pwd = $(".new_draw_pwd").val();
				var news_draw_pwd = $(".news_draw_pwd").val();

				var pwd_reg = /^(?![A-Z]+$)(?![a-z]+$)(?!\d+$)(?![\W_]+$)\S{6,16}$/;
				if(new_draw_pwd<6 || new_draw_pwd>16 || !(pwd_reg.test(new_draw_pwd))){
					toastr.warning("密码为6~16位数字、字母包含两种");
					return false;
				}
				// 含有两种字符并且长度在6-16为之间了
				if(news_draw_pwd == ""|| news_draw_pwd!==new_draw_pwd){
					toastr.warning("两次输入密码不一致");
					return false;
				};
				//$("form[name='update_trade_password']").submit();	
				$.ajax({
						url:"/center/update_trade_password",
						type:"post",
						dataType: 'json',
						data:{
							new_password : new_draw_pwd,
							two_new_password : news_draw_pwd,
						},
						success:function(res){
							if(res.success == true) {
								window.location.reload();
							}else{
								toastr.error(res.msg);
							}
						}
				});
			});

			// 修改WX号
			$(".submit4").click(function(event) {
				$(".error").hide();
				var wx_number = $(".wx_number").val();
				if(wx_number == ""){
					toastr.warning("微信号不能为空");
					return false;
				}
				$.ajax({
						url:"/center/update_weixin",
						type:"post",
						dataType: 'json',
						data:{
							weixin : wx_number
						},
						success:function(res){
							if(res.success == true) {
								window.location.reload();
							}else{
								toastr.error(res.msg);
							}
						}
				});
			});

			// 修改QQ号
			$(".submit5").click(function(event) {
				$(".error").hide();
				var reg = /^[0-9]{5,25}$/;
				var qq_number = $(".qq_number").val();
				var qq_number = $(".qq_number").val();
				if(qq_number == ""){
					toastr.warning("QQ不能为空");
					return;
				}
				if(!reg.test(qq_number)){
					toastr.warning("QQ应为5-25位数字");
					return false;
				}
				$.ajax({
						url:"/center/update_qq",
						type:"post",
						dataType: 'json',
						data:{
							qq : qq_number
						},
						success:function(res){
							if(res.success == true) {
								window.location.reload();
							}else{
								toastr.error(res.msg);
							}
						}
				});
			});


			// 修改邮箱 填写新邮箱
			/*$(".submit6").click(function(event) {
				$(".error").hide();
				var email = $(".email").val();
				var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
				if(!reg.test(email)){
					$(".email").siblings('.error').show();
					return;
				};
			});*/
		});
		// 短信验证码获取
		var timer = null;
		var getCodeSwitch = false;
		var countDown = 60;
		function getCode(){
			getCodeSwitch = true;
			timer = setInterval(function(_this){
				countDown-=1;
				$('.get_phone_code').text(countDown+"s后重新获取");
				if(countDown < 0){
					clearInterval(timer);
					$('.get_phone_code').text("获取验证码");
					getCodeSwitch = false;
				}
			},1000);
		}
	</script>
</body>
<?php $this->load->view("/common/footer"); ?>
</html>