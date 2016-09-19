//新增动作
$("#add-btn").on("click", function () {

    //judgeNull();
    //标题
    var msg_sys_title = $(".notice-title").val();
    //开始时间
    var msg_sys_starttime = $(".start-time").val();
    //结束时间
    var msg_sys_endtime = $(".end-time").val();
    //内容
    var msg_sys_content = UE.getEditor('CONTENT').getContent();

    if (!$(".error-message").hasClass("show")) {
        $.ajax({
            url: "/Manage/MessageManage/add",
            data: {
                'msg_sys_title': msg_sys_title,
                'msg_sys_starttime': msg_sys_starttime,
                'msg_sys_endtime': msg_sys_endtime,
                'msg_sys_content': msg_sys_content
            },
            type: 'post',
            dataType: "json",
            success: function (data) {
                //checkAuth(data);
                if (data.code == 200) {
                    showPro(".save-success", data.status, "/Manage/MessageManage/index");
                }
            },
            error: function () {
            }
        });
    }
});

//编辑页面
$("#edit-btn").on("click", function () {

    //judgeNull();
    //标题
    var msg_sys_title = $(".notice-title").val();
    //开始时间
    var msg_sys_starttime = $(".start-time").val();
    //结束时间
    var msg_sys_endtime = $(".end-time").val();
    //内容
    var msg_sys_content = UE.getEditor('editor').getContent();
    //当前系统消息ID
    var msg_sys_id = $(".edit-id").val();

    if (!$(".error-message").hasClass("show")) {
        $.ajax({
            url: "/Manage/MessageManage/edit",
            data: {
                'msg_sys_title': msg_sys_title,
                'msg_sys_starttime': msg_sys_starttime,
                'msg_sys_endtime': msg_sys_endtime,
                'msg_sys_content': msg_sys_content,
                'msg_sys_id': msg_sys_id
            },
            type: 'post',
            dataType: "json",
            success: function (data) {
                //checkAuth(data);
                if (data.code == 200) {
                    showPro(".save-success", data.status, "/Manage/MessageManage/index/");
                }
            },
            error: function () {
            }

        });
    }
});

function singleDel(msg_sys_id) {
    var top = $(this).offset();
    console.log(top)
    $.dialog({
        title: '提示信息', content: '确认删除该系统消息吗？', ok: function () {
            $.ajax({
                url: "/Manage/MessageManage/delete/msg_sys_id/" + msg_sys_id,
                dataType: "json",
                type: 'get',
                success: function (data) {
                    //checkAuth(data);
                    showPro(".notice-prompt", data, "/Manage/MessageManage/index");
                },
                error: function () {
                }
            });
        },
        cancel: function () {
        }
    });
}

function changeStatus(msg_sys_id, msg_sys_status) {
    $.ajax({
        url: "/Manage/MessageManage/changeStatus",
        data: {
            "msg_sys_id": msg_sys_id,
            'msg_sys_status': msg_sys_status
        },
        dataType: "json",
        type: 'post',
        success: function (data) {
            //checkAuth(data);
            if (data.code == 200) {
                showPro(".notice-prompt", data.status, "/Manage/MessageManage/index");
            }
        },
        error: function () {
        }
    });
}


$("#addnotice").on("blur", ".notice-title", function () {
    judgeMust($(this), ".error-message1");
});
$("#addnotice").on("blur", ".start-time", function () {
    if ($(this).val() != "") {
        $(".error-message2").addClass("hide").removeClass("show");
    }
});
$("#addnotice").on("blur", ".end-time", function () {
    if ($(this).val() != "") {
        $(".error-message3").addClass("hide").removeClass("show");
    }
});

function judgeMust(target, msg) {
    if (target.val() == "") {
        $(msg).addClass("show").removeClass("hide");
    } else {
        $(msg).addClass("hide").removeClass("show");
    }
}

function showPro(target, msg, loca) {
    $(target).show().children("span").html(msg);
    setTimeout(function () {
        $(target).hide();
        location.href = loca;
    }, 1500);
}

function judgeNull() {
    //开始时间
    if ($(".start-time").val() == "") {
        $(".error-message2").addClass("show").removeClass("hide");
    } else {
        $(".error-message2").addClass("hide").removeClass("show");
    }
    //结束时间
    if ($(".end-time").val() == "") {
        $(".error-message3").addClass("show").removeClass("hide");
    } else {
        $(".error-message3").addClass("hide").removeClass("show");
    }
    //公告标题
    if ($(".notice-title").val() == "") {
        $(".error-message1").addClass("show").removeClass("hide");
        return false;
    } else {
        $(".error-message1").addClass("hide").removeClass("show");
    }
    if ($(".error-message").hasClass("show")) {
        return false;
    }
}
