<?php if (!defined('THINK_PATH')) exit();?>    <link rel="stylesheet" type="text/css" href="/Public/css/userManage.css" />
    <div class="container-padding">
        <div class="tb1 jxssp tb1-noborder">
            <form method="POST" name="query-form" action="<?php echo ($submit); ?>" id="SalerSearchForm" onsubmit="return check();">
                <div class="wrap-table mt10">
                    <table class="tbl">
                        <colgroup>
                            <col width="10%">
                            <col width="80%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <td class="cap"><span class="rq">*</span>用户名</td>
                            <td colspan="3">
                                <input type="text" class="input-text ml15 username-input" name="data[nickname]"
                                       value="<?php echo ($data[nickname]); ?>"
                                       placeholder="请输入用户名" maxlength="20">
                                <span class="error-message hide"></span>
                            </td>

                        </tr>
                        <tr>
                            <td class="cap">真实姓名</td>
                            <td colspan="3">
                                <input type="text" class="input-text ml15 realname-input" name="data[name]"
                                       value="<?php echo ($data[name]); ?>"
                                       placeholder="请输入真实姓名" maxlength="30">
                            </td>
                        </tr>
                        <tr>
                            <td class="cap">性别</td>
                            <td colspan="3">
                                <input type="radio" name="data[sex]" value="0" class="input-text ml15"
                                <?php if($data[sex] == 0 ): ?>checked="checked"<?php endif; ?>
                                >男
                                <input type="radio" name="data[sex]" value="1" class="input-text ml15"
                                <?php if($data[sex] == 1 ): ?>checked="checked"<?php endif; ?>
                                >女
                            </td>
                        </tr>
                        <tr>
                            <td class="cap">手机号码</td>
                            <td colspan="3">
                                <input type="text" class="input-text ml15 tel-input" name="data[phone]"
                                       value="<?php echo ($data[phone]); ?>"
                                       placeholder="请输入手机号" maxlength="11">
                            </td>
                        </tr>
                        <tr>
                            <td class="cap">邮箱</td>
                            <td colspan="3">
                                <input type="text" class="input-text ml15 email-input" name="data[email]"
                                       value="<?php echo ($data[email]); ?>" maxlength="50" placeholder="请输入邮箱">
                            </td>
                        </tr>
                        <tr>
                            <td class="cap">描述</td>
                            <td colspan="3">
                            <textarea maxlength="100" class="ml15 user-textarea"
                                      name="data[description]"><?php echo ($data[description]); ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clear">
                    <input type="hidden" name="uid" value="<?php echo ($data[uid]); ?>" class="fl hidden-id">
                    <input type="submit" value="保存" id="btn_Save" class="btn-c btn-xl button btn btn-a fl">

                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
    <script type="text/javascript" src="/Public/Js/userManage.js"></script>