/**
 * Created by pactera on 2016/7/4.
 */
//清空值
window.onload = function () {
    var userName = $(".login-id");
    var pwd = $(".login-pwd");
    var code = $(".login-verification");
    userName.val("");
    pwd.val("");
    code.val("");
};
$(function () {
    var LoginJs = {
        init: function () {

            //刷新验证码
            var verifyimg = $(".verifyimg").attr("src");
            $(".reloadverify").click(function () {
                if (verifyimg.indexOf('?') > 0) {
                    $(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
                } else {
                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
                }
            });

            //添加和移除边框线样式
            var $input = $(".login-common-input");
            $input.on("focus", function () {
                $(this).addClass("focus-border");
                $(this).siblings(".error-message").hide();
            });
            $input.on("blur", function () {
                $(this).removeClass("focus-border");
            });
            $input.on("change", function () {
                $(this).siblings(".error-message").hide();
            });
            //placeholder兼容处理
            $("input").placeholder();
            //登录时校验输入项正确性
            $(".login-button").on("click", function () {
                LoginJs.loginBtn();
            });
            LoginJs.keyLogin();

        },
        //回车键登录
        keyLogin: function () {
            $("body").on("keydown", function (event) {
                if (event.keyCode == 13)   //回车键的键值为13
                    LoginJs.loginBtn();  //调用登录按钮的登录事件
            });
        },
        loginBtn: function () {
            var userName = $(".login-id");
            var pwd = $(".login-pwd");
            var code = $(".login-verification");

            var uVal = userName.val().replace(/\s/g, "");
            var pVal = pwd.val().replace(/\s/g, "");
            var cVal = code.val().replace(/\s/g, "");

            //判断空值
            if (uVal == "") {
                $(".error-id").show().children("span").html("请输入用户名！");
                $(".error-ver").hide();
                return false;
            } else if (pVal == "") {
                $(".error-pwd").show().children("span").html("请输入密码！");
                $(".error-ver").hide();
                return false;
            } else if (cVal == "") {
                $(".error-ver").show().children("span").html("请输入验证码！");
                return false;
            } else {
                //请求数据
                $.ajax({
                    type: "post",
                    url: "/Manage/Login/index",
                    data: {"nickname": uVal, "password": pVal, "verify_code": cVal},
                    success: function (data) { //php 页面返回值状态
                        var dataJson = data;
                        if (dataJson.code == "901") {
                            $(".error-id").show().children("span").html("用户名错误！");
                            code.val("");
                            $("#checkCode").attr("src", "/Manage/Login/verify.html?" + Math.random() + "");
                        } else if (dataJson.code == "902") {
                            $(".error-pwd").show().children("span").html("密码错误！");
                            code.val("");
                            $("#checkCode").attr("src", "/Manage/Login/verify.html?" + Math.random() + "");
                        } else if (dataJson.code == "903") {
                            $(".error-ver").show().children("span").html("验证码错误！");
                            code.val("");
                            $("#checkCode").attr("src", "/Manage/Login/verify.html?" + Math.random() + "");
                        } else if (dataJson.code == "904") {
                            $(".error-id").show().children("span").html("该用户是禁用状态，暂不能登录系统！");
                            code.val("");
                            $("#checkCode").attr("src", "/Manage/Login/verify.html?" + Math.random() + "");
                        } else {
                            window.location = "/Manage/Index/index.html";
                        }
                    }
                });
            }

        }

    };
    LoginJs.init();
});