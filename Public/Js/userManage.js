/**
 * Created by pactera on 2016/7/12.
 */
//批量删除
function delAll() {
    if (!$(".checkbox-normal").hasClass("checkbox-checked")) {
        $.dialog({
            title: '提示信息', content: '请选择要删除的用户！'
        });
    } else {
        $.dialog({
            title: '提示信息', content: '确认删除选中的所有用户吗？', ok: function () {
                del_id = '';
                $(':checkbox[name=childrenBox\\[\\]][checked=checked]').each(function (i) {
                    del_id += $(':checkbox[name=childrenBox\\[\\]][checked=checked]')[i].value;
                    del_id += ',';
                });
                del_id = del_id.substring(0, del_id.length - 1);
                location.href = "/Manage/User/delUser/uid/" + del_id;
            },
            cancel: function () {
                this.close();
            }
        });
    }
}
//单个重置密码
function singleReset(del_id) {
    $.ajax({
        type: "post",
        url: "/Manage/User/resetPwd/uid/" + del_id,
        data: "uid=" + del_id,
        success: function (data) {
            checkAuth(data);
            var dataJson = JSON.parse(data);
            if (dataJson.code == 200) {
                $(".single-pwd-pro").show().children("span").html(dataJson.status);
                setTimeout(function () {
                    $(".single-pwd-pro").hide();
                }, 1000);
            } else {
                $(".single-pwd-pro").show().children("span").html(dataJson.status);
                setTimeout(function () {
                    $(".single-pwd-pro").hide();
                }, 1000);
            }
        }
    });
}
//批量重置密码
function resetAll(uid) {
    del_id = '';
    //判断uid是否为空
    if (uid > 0) {
        //单条重置密码
        del_id = uid;
    } else {
        $(':checkbox[name=childrenBox\\[\\]][checked=checked]').each(function (i) {
            del_id += $(':checkbox[name=childrenBox\\[\\]][checked=checked]')[i].value;
            del_id += ',';
        });
        //判断复选框是否选中
        if (del_id === "") {
            //进行提示
            $.dialog({
                title: '提示信息', content: '请选择需要重置密码的用户！'
            });
            return true;
        } else {
            $.ajax({
                type: "post",
                url: "/Manage/User/resetPwd/uid/" + del_id,
                data: "uid=" + del_id,
                success: function (data) {
                    checkAuth(data);
                    var dataJson = JSON.parse(data);
                    if (dataJson.code == 200) {
                        del_id = del_id.substring(0, del_id.length - 1);
                        $(".batch-prompt").show().children("span").html(dataJson.status);
                        setTimeout(function () {
                            $(".batch-prompt").hide().children("span").html(dataJson.status);
                        }, 1500);
                    } else {
                        $(".batch-prompt").show().children("span").html(dataJson.status);
                        setTimeout(function () {
                            $(".batch-prompt").hide().children("span").html(dataJson.status);
                        }, 1500);
                    }
                }
            });

        }
    }
}
var username = /^[\u4E00-\u9FA5A-Za-z0-9_]{1,20}$/;
$("#btn_Save").on("click", function () {
    if ($(".username-input").val() == "") {
        $(".error-message").addClass("show").removeClass("hide").html("请输入用户名！");
    }
});

//用户名验证唯一性
$(".username-input").on("blur", function () {
    // checkNickName uid nickname
    if ($(this).val().replace(/\s+/g, "") == "") {
        $(".error-message").addClass("show").removeClass("hide").html("请输入用户名！");
    } else if (!username.test($(this).val())) {
        $(".error-message").addClass("show").removeClass("hide").html("用户名格式不正确！");
    } else {
        $(".error-message").addClass("hide").removeClass("show")
    }

    var uVal = $(".username-input").val();
    var uId = $(".hidden-id").val();
    $.ajax({
        type: "get",
        url: "/Manage/User/checkNickName",
        data: {"uid": uId, "nickname": uVal},
        success: function (data) {
            var dataJson = JSON.parse(data);
            if (dataJson.code == 100) {
                if (!$(".error-message").hasClass("show")) {
                    $(".error-message").addClass("show").removeClass("hide").html(dataJson.status);
                }
                return false;
            }
        }
    });

});

//普通验证 非空或者格式验证
$().ready(function () {
// 在键盘按下并释放及提交后验证提交表单
    $("#SalerSearchForm").validate({
        rules: {
            "data[name]": {
                isName: true
            },
            "data[phone]": {
                isPhone: true
            },
            "data[email]": {
                isEmail: true
            }
        },
        messages: {
            "data[email]": "邮箱格式不正确！"
        },
        submitHandler: function (form) {
            if ($(".error-message").hasClass("show")) {
                return false;
            } else {
                form.submit();
            }
        }
    });
});
//调用方法，取消全选样式
baseOperateJs.cancelAll(".user-tbody", ".user-checkboxAll");

//单独删除操作
function singleDel(del_id) {
    $.dialog({
        title: '提示信息', content: '确认删除该用户吗？', ok: function () {
            location.href = "/Manage/User/delUser/uid/" + del_id;
        },
        cancel: function () {
        }
    });
}
//全选操作
$("#allSelect").on('click', function () {
    allSelect();
});

//管理全部角色选中全选
var nLen = $(".user-allocationRole .checkbox-normal").length;
var cLen = $(".user-allocationRole .checkbox-checked").length;
if (nLen == cLen) {
    $(".user-allocationRole #allSelect").attr('checked', true);
    $(".user-allocationRole .user-checkboxAll").addClass("checkbox-checked");
} else {
    $(".user-allocationRole #allSelect").removeAttr('checked');
    $(".user-allocationRole .user-checkboxAll").removeClass("checkbox-checked");
}

$(".checkbox-default").click(function () {
    console.log(1);
});