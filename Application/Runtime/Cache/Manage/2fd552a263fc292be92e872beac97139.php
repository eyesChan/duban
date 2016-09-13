<?php if (!defined('THINK_PATH')) exit();?> <link rel="stylesheet" type="text/css" href="/Public/css/roleManage.css" />
    <div class="role-padding container-padding">
        <div class="jxssp tb1">
            <form id="roleFrom" method="POST" action="<?php echo U(add);?>" enctype="multipart/form-data">
                <table class="tb1">
                <caption>新增角色</caption>
                    <tr>
                        <td class="right role-edit-td role-n"><span style="color:red;">*</span>角色名称：</td>
                        <td class="role-edit-td role-input">
                            <input class="role-name-input input-text text" type="text" name="title" maxlength="30">
                            <input class="Role-name-hidden" type="hidden" name="$_id" value="-100">
                            <span class="err-message hide"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right role-n">描述：</td>
                        <td class="role-input">
                            <textarea name="describe" maxlength="100"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="op-td">
                            <input type="hidden" name="p" value="<?php echo ($p); ?>"/>
                            <input type="submit" name="submit" value="添加" id="btn-save" class="btn btn-c btn-xl button mr10">
                           
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
    <script type="text/javascript" src="/Public/Js/roleManage.js"></script>