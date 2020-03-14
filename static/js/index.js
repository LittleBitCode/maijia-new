
// 登录
var appLogin=angular.module("myLogin",[]);
appLogin.controller("LoginForm",function($scope){
    $scope.submit=function(){
        $.ajax({
            url: "/login",
            data: {
                userPhone: $scope.userPhone,           //手机号码
                password: $scope.password,             //密码
                agreement:$scope.agreement             //是否记住密码
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                var code = toInt(data.code);
                if (code == 0) {
                    //登陆成功保存密码
                    save_passwd();
                    window.location.replace("/home");
                }else{
                    popupMsg(data.msg);
                }
            }
        });
    };
});