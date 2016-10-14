//新增动作
$("#add-btn").on("click", function () {

    if($(".config-category").val() == "-100"){
        $.dialog.alert("请选择参数类别！");
        return false;
    }else if($.trim($(".config-name").val()) == "" ){
        $.dialog.alert("请填写参数名称！");
        return false;
    }else {
        $.ajax({
            url: "/Manage/configManage/add",
            data: $('#add_config_info').serialize(),
            async: false,
            type: 'post',
            dataType: "json",
            success: function (data) {
                //checkAuth(data);
                if (data.code == 200) {
                    showPro(".save-success", data.status, "/Manage/configManage/index");
                }
            },
            error: function () {
            }
        });
    }
});

//编辑页面
$("#edit-btn").on("click", function () {
    if($("[name=config_key]").val() == "-100"){
        $.dialog.alert("请选择参数类别！");
        return false;
    }else if($.trim($("[name=config_descripion]").val()) == "" ){
        $.dialog.alert("请填写参数名称！");
        return false;
    }else {
        $.ajax({
            url: "/Manage/configManage/edit",
            data: $('#edit_config_info').serialize(),
            async: false,
            type: 'post',
            dataType: "json",
            success: function (data) {
                //checkAuth(data);
                if (data.code == 200) {
                    showPro(".save-success", data.status, "/Manage/configManage/index/");
                }
            },
            error: function () {
            }

        });
    }

});

//修改状态（停用/发布）
function changeStatus(config_id, config_status) {
    $.ajax({
        url: "/Manage/configManage/changeStatus",
        data: {
            "config_id": config_id,
            "config_status": config_status
        },
        dataType: "json",
        type: 'post',
        success: function (data) {
            //checkAuth(data);
            if (data.code == 200) {
                showPro(".notice-prompt", data.status, "/Manage/configManage/index");
            }
        },
        error: function () {
        }
    });
}

//单条删除
function singleDel(config_id) {
    $.dialog({
        title: '提示信息', content: '确认删除系统参数吗？', ok: function () {
            $.ajax({
                url: "/Manage/configManage/delete/config_id/" + config_id,
                dataType: "json",
                type: 'get',
                success: function (data) {
                    //checkAuth(data);
                    showPro(".notice-prompt", data, "/Manage/configManage/index");
                },
                error: function () {
                }
            });
        },
        cancel: function () {
        }
    });
}

//执行结果提示框
function showPro(target, msg, loca) {
    $(target).show().children("span").html(msg);
    setTimeout(function () {
        $(target).hide();
        location.href = loca;
    }, 1500);
}
