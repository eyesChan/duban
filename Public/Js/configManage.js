//单条删除
function singleDel(msg_sys_id) {
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