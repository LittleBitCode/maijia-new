function login(){
	$('#login-tpis').hide();
	var username = $('#username').val();
	var password = $('#password').val();
	if(!username){
		$('#login-tpis').text('用户名不能为空').show();
		return false;
	};
	if(!password){
		$('#login-tpis').text('密码不能为空').show();
		return false;
	};
}
function validate(){
	$('#login-tpis').hide();
	var check_code = $('#check_code').val();
	var username = $('#username').val();
	var password = $('#password').val();
	if(!username){
		$('#login-tpis').text('用户名不能为空').show();
		return false;
	};
	if(!password){
		$('#login-tpis').text('密码不能为空').show();
		return false;
	};
	if(!check_code){
		$('#login-tpis').text('验证码不能为空').show();
		return false;
	};
}
function enterPress(e) { 
	if (e.keyCode == 13) { 
		login(); 
	}	
}
function enterPressCode(e) { 
	if (e.keyCode == 13) { 
		validate(); 
	}	
}