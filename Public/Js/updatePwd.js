/**
 * Created by pactera on 2016/7/19.
 */
$(function () {
    var updatePwd = {
        init: function () {

            //新密码
            $("#new_password").on("blur", function () {
                if ($(this).val().length < 6 && $(this).val() != "") {
                    $(".error-message2").addClass("show").removeClass("hide").html("请输入至少6位新密码！");
                } else {
                    $(".error-message2").addClass("hide").removeClass("show");
                }
            });
            //确认密码
            $("#confirm_password").on("blur", function () {
                if ($(this).val() != $("#new_password").val() && $(".error-message2").hasClass("hide")) {
                    $(".error-message3").addClass("show").removeClass("hide").html("两次新密码输入不一致！");
                } else {
                    $(".error-message3").addClass("hide").removeClass("show");
                }
            });
            //原始密码正确性判断
            $("#old_password").on("blur", function () {

                var old_password = $("#old_password").val();
                var new_password = $("#new_password").val();
                var confirm_password = $("#confirm_password").val();
                $.ajax({
                    url: "/Manage/Ucenter/modifyPsd",
                    data: {
                        "old_password": old_password,
                        "new_password": new_password,
                        "confirm_password": confirm_password
                    },
                    dataType: "json",
                    type: 'get',
                    success: function (data) {
                        console.log(data);
                        if (data.status == 701) {
                            $(".error-message1").addClass("show").removeClass("hide").html(data.msg);
                        } else {
                            $(".error-message1").addClass("hide").removeClass("show");
                        }
                        if (data.status == 703) {
                            $(".update-pwd-pro").show();
                            setTimeout(function () {
                                location.href = '/Manage/Login/index.html';
                            }, 2000);
                        }
                    }
                });
            });
            $(".update-save").on("click", function () {
                var old_password = $("#old_password").val();
                var new_password = $("#new_password").val();
                var confirm_password = $("#confirm_password").val();
                if (old_password == "") {
                    $(".error-message1").addClass("show").removeClass("hide").html("请输入原登陆密码！");
                } else if ($(".error-message1").hasClass("hide") && $("#new_password").val() == "") {
                    $(".error-message2").addClass("show").removeClass("hide").html("请输入新登陆密码！");
                } else if ($(".error-message2").hasClass("hide") && $("#confirm_password").val() == "") {
                    $(".error-message3").addClass("show").removeClass("hide").html("请输入确认新密码！");
                }
                $.ajax({
                    url: "/Manage/Ucenter/modifyPsd",
                    data: {
                        "old_password": old_password,
                        "new_password": new_password,
                        "confirm_password": confirm_password
                    },
                    dataType: "json",
                    type: 'get',
                    success: function (data) {
                        if (data.status == 703) {
                            $(".update-pwd-pro").show();
                            setTimeout(function () {
                                location.href = '/Manage/Login/index.html';
                            }, 2000);
                        }
                    }
                });
            });
        }
    };
    updatePwd.init();
});
