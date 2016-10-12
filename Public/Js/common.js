/**
 * Created by pactera on 2016/7/11.
 */
(function (config) {
    config['extendDrag'] = true;
    config['lock'] = true;
    config['fixed'] = true;
    config['okVal'] = '确定';
    config['cancelVal'] = '取消';
    config['max'] = false;
    config['min'] = false;
    // [more..]
})($.dialog.setting);

// 用户名验证
jQuery.validator.addMethod("isUsername", function (value, element) {
    var tel = /^[\u4E00-\u9FA5A-Za-z0-9_]{1,20}$/;
    return this.optional(element) || (tel.test(value));
}, "用户名格式不正确！");

// 姓名验证
jQuery.validator.addMethod("isName", function (value, element) {
    var tel = /^[\u4E00-\u9FA5A-Za-z ]{1,30}$/;
    return this.optional(element) || (tel.test(value));
}, "请输入真实姓名！");
//手机验证
jQuery.validator.addMethod("isPhone", function (value, element) {
    var tel = /(13\d|14[57]|15[^4,\D]|17[678]|18\d)\d{8}|170[059]\d{7}/;
    return this.optional(element) || (tel.test(value));
}, "手机号格式不正确！");

// 邮箱验证
jQuery.validator.addMethod("isEmail", function (value, element) {
    var tel = /^([a-zA-Z0-9_-.])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
    return this.optional(element) || (tel.test(value));
}, "邮箱格式不正确！");
// 学生姓名验证
jQuery.validator.addMethod("isStName", function (value, element) {
    var tel = /^[\u4e00-\u9fa5]{1,20}$/;
    return this.optional(element) || (tel.test(value));
}, "姓名格式不正确！");
// 联系人姓名验证/^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/
jQuery.validator.addMethod("isPhoneAndMob", function (value, element) {
    var tel =  /^((0\d{2}-\d{8})|(0\d{3}-\d{7})|(1[35784]\d{9}))$/;
    // /^((0\d{2,3}-\d{7,8})|(1[35784]\d{9}))$/
    return this.optional(element) || (tel.test(value));
}, "联系方式格式不正确！");

function checkAuth(data) {
    if (data.status == 0) {
        //显示没有权限提示
        $.dialog({
            id: 'msg',
            title: '提示消息',
            content: '没有权限',
            width: 200,
            height: 100,
            left: '50%',
            top: '50%',
            fixed: true,
            drag: false,
            resize: false,
            time: 2,
            lock: true
        });
        return false;
    }
    return true;
}
