/**
 * Created by pactera on 2016/7/13.
 */
function sel1(param) {
    if (param == 2) {
        //添加 判断字段
        if ($('#menu_num').val() == false) {
            $.dialog.alert('请填写模块编码');
            return false;
        } else if ($('#menu_name').val() == false) {
            $.dialog.alert('请填写模块名称');
            return false;
        } else if ($('#level option:selected').val() == 0) {
            $.dialog.alert('请选择模块等级');
            return false;
        } else if ($('#level option:selected').val() == 2 || $('#level option:selected').val() == 3) {
            if ($('#pid').val() == false) {
                $.dialog.alert('请选择上级目录');
                return false;
            } else {
                if ($('#menu_url').val() == false) {
                    $.dialog.alert('请填写模块地址');
                    return false;
                } else {
                    $('#sform').submit();
                }
            }
        } else {
            $('#sform').submit();
        }
    } else if (param == 1) {
        // 查询
        $('#sform').submit();
    }
}