$(function(){
	// 用户名键入
	// $('#usermoblie').focus(function(event) {
	// 	$(this).parent('.inp').find('.warn').show();
	// }).blur(function(event) {
	// 	check_mobile();
	// });
	// 用户qq键入
	// $('#userqq').focus(function(event) {
	// 	$(this).parent('.inp').find('.warn').show();
	// }).blur(function(event) {
	// 	check_qq();
	// });
	// 螺丝帽验证
	// 
	// 
	// 
	// 验证码键入
	$('#phone_verify').focus(function(event) {
		$(this).parent('.inp').find('.error').hide();
	}).blur(function(event) {
		check_phone_verify();
	});
	// 密码验证
	$('#password').focus(function(event) {
		$(this).parent('.inp').find('.error').hide();
	}).blur(function(event) {
		check_password();
	});
	// 协议部分验证
	$('input[name="login_remember"]').click(function(event) {
		if($(this).is(':checked')){
			$('.sub_btn').removeClass('disable');
		}else{
			$('.sub_btn').addClass('disable');
		}
	});

})

function check_mobile(){
	$('#usermoblie').parent('.inp').find('.ok').hide();
	var mobile = $.trim($('#usermoblie').val());
	if(!mobile){
		$('#usermoblie').parent('.inp').find('.error').text('手机号不能为空').show();
		return false;
	}else if(!(/^1[34578]\d{9}$/.test(mobile))){
		$('#usermoblie').parent('.inp').find('.error').text('手机号格式不正确').show();
		return false;
	}else{
		$('#usermoblie').parent('.inp').find('.warn').hide();
		$('#usermoblie').parent('.inp').find('.error').hide();
		$('#usermoblie').parent('.inp').find('.ok').show();
		return true;
	}
	
}
function check_qq(){
	$('#userqq').parent('.inp').find('.ok').hide();
	var qq = $.trim($('#userqq').val());
	var qq_reg=/^\d{5,15}$/;
	if((!qq) || (!qq_reg.test(qq))){
		$('#userqq').parent('.inp').find('.error').text('QQ号为5~10位数字').show();
		return false;
	}else{
		$('#userqq').parent('.inp').find('.warn').hide();
		$('#userqq').parent('.inp').find('.error').hide();
		$('#userqq').parent('.inp').find('.ok').show();
		return true;
	};
}
// 螺丝帽回调验证，成功return true;失败return false;
function check_verify(){
	
}
function check_phone_verify(){
	$('#phone_verify').parent('.inp').find('.ok').hide();
	var phone_verify = $.trim($('#phone_verify').val());
	if(!phone_verify){
		$('#phone_verify').parent('.inp').find('.error').text('验证码不能为空').show();
		return false;
	}else{
		// 此处写ajax进行手机验证码校验
		$('#phone_verify').parent('.inp').find('.warn').hide();
		$('#phone_verify').parent('.inp').find('.error').hide();
		return true;
	}
}
function check_password(){
	$('#password').parent('.inp').find('.ok').hide();
	var password = $.trim($('#password').val());
	if(!password){
		$('#password').parent('.inp').find('.error').text('密码不能为空').show();
		return false;
	}else if(password.length<6||password.length>16){
		$('#password').parent('.inp').find('.error').text('密码长度必须为6~16位').show();
		return false;
	}else{
		$('#password').parent('.inp').find('.error').hide();
		$('#password').parent('.inp').find('.ok').show();
		return true;
	}
}
function check_usertype(){
	if(!$('input[name="user_type"]:checked').size()){
		$('input[name="user_type"]').parents('.inp').find('.error').text('请选择角色').show();
		return false;
	}else{
		$('input[name="user_type"]').parents('.inp').find('.error').hide();
		$('input[name="user_type"]').parents('.inp').find('.ok').show();
		return true;
	}
}

