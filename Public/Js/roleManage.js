
// 角色管理js
// 角色管理首页全选按钮事件
$("#all").on('click', function () {
    $status = $("#all").attr('checked');
    if ($status) {
        $(':checkbox[name=cbox\\[\\]]').attr('checked', true);
        $(".checkbox-normal").addClass("checkbox-checked");
    } else {
        $(':checkbox[name=cbox\\[\\]]').attr('checked', false);
        $(".checkbox-normal").removeClass("checkbox-checked");
    }

});
//单独删除操作
function singleDel(id) {
    $.ajax({
        type: "get",
        url: "/Manage/Role/checkRelation/id/" + id + '/flag/1',
        dataType: "json",
        success: function (data) {
            checkAuth(data);//调用commonjs中的方法，显示没有权限提示
            if (data.status == 101) {
                $.dialog({
                    title: '提示信息', content: data.msg, ok: function () {
                        this.close();
                        if ($("label.checkbox-normal").hasClass("checkbox-checked")) {
                            $("label.checkbox-normal").removeClass("checkbox-checked");
                        };
                    }
                });
            } else {
                $.dialog({
                    title: '提示信息', content: '确定删除该角色？', ok: function () {

                        location.href = "/Manage/Role/del/id/" + id + '/flag/1';
                    },
                    cancel: function () {
                        this.close();
                    }
                });
            }
        }
    });
};

// 批量删除方法
function roleDelAll() {
    var cbox = $("input[name=cbox\\[\\]]:checked");
    var id = '';
    for (var i = 0; i < cbox.length; i++) {
        id += cbox.eq(i).val();
        id += ',';
    }
    id = id.substring(0, id.length - 1);
    if (!$(".checkbox-normal").hasClass("checkbox-checked")) {
        $.dialog({
            title: '提示信息', content: '请选择要删除的角色!'
        });
    } else {

        $.ajax({
            type: "get",
            url: "/Manage/Role/checkRelation/id/" + id,
            dataType: "json",
            success: function (data) {
                checkAuth(data);//调用commonjs中的方法，显示没有权限提示
                if (data.status == 100) {
                    $.dialog({
                        title: '提示信息', content: data.msg, ok: function () {
                            this.close();
                            if ($("label.checkbox-normal").hasClass("checkbox-checked")) {
                                $("label.checkbox-normal").removeClass("checkbox-checked");
                            }
                            ;
                            if ($(".role-checkboxAll").hasClass("checkbox-checked")) {
                                $(".role-checkboxAll").removeClass("checkbox-checked");
                            }
                            ;
                        }
                    });
                } else {
                    $.dialog({
                        title: '提示信息', content: '确定删除该角色？', ok: function () {

                            location.href = "/Manage/Role/del/id/" + id;
                        },
                        cancel: function () {
                            this.close();
                        }
                    });
                }
            }
        });
    }
};

//调用方法，取消全选样式
baseOperateJs.cancelAll(".role-tbody", ".role-checkboxAll");

/****关联角色js开始****/
// 初始化判断角色关联，如果已经赋权，则第一行tr被选中
function initJudge() {
    $(".au-role").each(function () {//获取每一个系统管理下面的模块
        var checkboxes = $(this).parent().next().children(".au2").find(':checkbox');//获取到其兄弟节点中的复选框
        checkboxes.each(function () {//遍历每一个复选框
            var that = $(this);
            if (that.is(":checked")) {//判断每一个复选框是否被选中
                that.parent().parent().prev().children("td").find(':checkbox').attr("checked", true);
            }
        })
    })
}
initJudge();

//获取到每一个系统管理所在的td中的input复选框，为其绑定事件，调用selectRole()方法
$(".au-role").each(function () {
    var inputs = $(this).find("input");
    inputs.each(function () {
        var that = $(this);
        that.on("click", function () {
            selectRole(that);
        })
    })
});
//点击每一个系统管理所在的td中的input复选框
function selectRole(id) {
    $status = id.attr('checked');
    if ($status) {
        id.parent().parent().next().last("tr").find(':checkbox').attr('checked', true);
        id.parent().parent().next().next().last(".au3").find('.per-r').attr('checked', true);

    } else {
        id.parent().parent().next().last("tr").find(':checkbox').attr('checked', false);
        id.parent().parent().next().next().last(".au3").find(':radio').attr('checked', false);

    }
}
;

$(".au-role").each(function () {//获取每一个系统管理下面的模块
    var checkboxes = $(this).parent().next().children(".au2").find(':checkbox');//获取到其兄弟节点中的复选框
    var radios = $(this).parent().next().next().last(".au3").find(':radio');//获取到其兄弟节点中的单选框
    radios.each(function () {
        var _that = $(this);
        _that.on("click", function () {
            if (_that.is(":checked")) {
                _that.parent().parent().prev().prev().children("td").find(':checkbox').attr("checked", true);
            }
        })
    });

    checkboxes.each(function () {//遍历每一个复选框
        var that = $(this);
        that.on("click", function () {
            if (that.is(":checked")) {//判断每一个复选框是否被选中
                that.parent().parent().prev().children("td").find(':checkbox').attr("checked", true);//找到每一个系统管理所在的td中的input为其添加一个类
                var checkbox = that.siblings(":checkbox");
                checkbox.each(function () {
                    var that = $(this);
                    if (that.is(":checked")) {
                        that.parent().parent().prev().children("td").find(':checkbox').attr("checked", true);
                    }
                })
            }
        })
    })
});
/****关联角色js结束****/

//验证角色名称格式是否正确的方法
function isRoleName(id) {
    // 在键盘按下并释放及提交后验证提交表单
    id.validate({
        rules: {
            // "title": {
            //     required: true,
            //     maxlength: 30,
            //     isRoleName:true
            // },
            "describe": {
                maxlength: 100
            }
        },
        submitHandler: function (form) {
            if ($(".err-message").hasClass("show")) {
                return false;
            } else {
                form.submit();
            }
        }
    });
}
//调用角色名称格式是否正确的方法
$().ready(function () {
    isRoleName($("#roleFrom"));//角色增加页面和编辑页面调用方法
});

// ^[\u4e00-\u9fa5_a-zA-Z0-9\s\\]+$
var rolename = /^[\u4E00-\u9FA5A-Za-z0-9_]{1,30}$/;
$("#btn-save").on("click", function () {
    if ($(".role-name-input").val().replace(/\s/g, "") == "" || $(this).val() == null) {
        $(".err-message").addClass("show").removeClass("hide").html("请输入角色名称！");
    }
});

// 验证角色名称唯一性
$(".role-name-input").on("blur", function () {
    if ($(this).val().replace(/\s/g, "") == "" || $(this).val() == null) {
        $(".err-message").addClass("show").removeClass("hide").html("请输入角色名称！");
    } else if (!rolename.test($(this).val())) {
        $(".err-message").addClass("show").removeClass("hide").html("角色名称格式不正确！");
    } else {
        $(".err-message").addClass("hide").removeClass("show")
    }
    ;
    var rVal = $(".role-name-input").val();
    var id = $(".Role-name-hidden").val();//

    var url;
    if (id == -100) {
        url = "/Manage/Role/checkRole/title/" + rVal;
    } else {
        url = "/Manage/Role/checkRole/title/" + rVal + "/id/" + id;
    }
    $().ready(function () {
        $("#roleFrom").validate({
            rules: {
                "expense_rule[expense_caption]": {
                    maxlength: 100
                }
            },
            submitHandler: function (form) {
                if ($(".err-message").hasClass("show")) {
                    return false;
                } else {
                    //验证通过之后请求
                    $.ajax({
                        type: "get",
                        url: url,
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 705) {
                                if (!$(".err-message").hasClass("show")) {
                                    $(".err-message").addClass("show").removeClass("hide").html(data.msg);
                                }
                                // return false;
                            }
                        }
                    });
                }
            }
        });

    });
});
//全选操作
$("#allSelect").on('click', function () {
    allSelect();
});