/**
 * Created by pactera on 2016/7/20.
 */
//全选
function allSelect() {
    if ($("#allSelect").attr('checked') == "checked") {
        $(':checkbox[name=childrenBox\\[\\]]').attr('checked', true);
    } else {
        $(':checkbox[name=childrenBox\\[\\]]').attr('checked', false);
    }
}
var baseOperateJs = {
    // 取消全选操作
    cancelAll: function (target) {
        var count = 0;
        $("[name=childrenBox\\[\\]]").on("click", function () {
            var $trLen = $(target).find(".singleSelect").length;
            if ($(target).children().find("[type=checkbox]:checked").length < $trLen) {
                $("#allSelect").removeAttr('checked');
            } else {
                $("#allSelect").attr('checked', true);
            }
        });
    }
};

