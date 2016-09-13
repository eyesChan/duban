<?php if (!defined('THINK_PATH')) exit();?><!-- 右侧幼儿园编辑管理内容start-->
<form id="dept_update" method="post" name="departmentForm" novalidate="novalidate">
    <table class="tbl">
        <caption>编辑部门</caption>
        <tbody>
        <tr>
            <td width="25%" class="right">
                <p><em>*</em>部门编号：</p>
            </td>
            <td>
                <input type="text" maxlength="30" class="add-d-num input-text text short" autocomplete="off"
                       name="dept[dept_num]" value="<?php echo ($res[dept][dept_num]); ?>">
                <input id="dept_id" type="hidden" name="dept[dept_id]" value="<?php echo ($res[dept][dept_id]); ?>">
                <input type="hidden" name="money_rule[id]" value="<?php echo ($res[money_rule][id]); ?>" >
                <span class="error-message error-message1 hide"></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p><em>*</em>部门名称：</p>
            </td>
            <td>
                <input type="text" maxlength="30" class="add-d-name input-text text short" autocomplete="off"
                       name="dept[dept_name]" value="<?php echo ($res[dept][dept_name]); ?>">
                <span class="error-message error-message2 hide"></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p><em>*</em>上级部门：</p>
            </td>
            <td>
                <select name="dept[dept_pid]" class="up-depart">
                    <option value='0'>顶级部门</option>
                    <?php if(is_array($downlist)): foreach($downlist as $key=>$vo): ?><option value="<?php echo ($vo[dept_id]); ?>"
                        <?php if( $vo[dept_id] == $res[dept][dept_pid]): ?>selected="true"<?php endif; ?>>
                        <?php echo str_repeat("|--",$vo[dept_level]).$vo[dept_name];?>
                        </option><?php endforeach; endif; ?>
                </select>
                <span class="error-message hide"></span>
            </td>

        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门负责人：</p>
            </td>
            <td>
                <input type="text" maxlength="30" class="add-d-person input-text text short" autocomplete="off"
                       name="dept[dept_manager]" value="<?php echo ($res[dept][dept_manager]); ?>">
                <span class="error-message hide"></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门电话：</p>
            </td>
            <td>
                <input maxlength="17" type="text" class="add-d-tel input-text text short" autocomplete="off" name="dept[dept_tel]"
                       value="<?php echo ($res[dept][dept_tel]); ?>">
                <span class="error-message hide"></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门传真：</p>
            </td>
            <td>
                <input maxlength="17" type="text" class="add-d-fax input-text text short" autocomplete="off" name="dept[dept_fax]"
                       value="<?php echo ($res[dept][dept_fax]); ?>">
                <span class="error-message hide"></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>机构类别：</p>
            </td>
            <td>
                <span id="hide-input">
                    <input type="radio" name="dept[dept_category]" class="add-kid" value="0"
                <?php if($res[dept][dept_category] == 0): ?>checked<?php endif; ?>
                /> 幼儿园 </span>
                <input type="radio" name="dept[dept_category]" class="add-nokid" value="1"
                <?php if($res[dept][dept_category] == 1): ?>checked<?php endif; ?>
                /> 非幼儿园
            </td>
        </tr>
        <tbody id="hide-tr">
        <tr>
            <td width="25%" class="right">
                <p><em>*</em>园所伙食费收费规则：</p>
            </td>
            <td>
                <select name="money_rule[food_money_rule]">
                    <option value="0"
                    <?php if($res[money_rule][food_money_rule] == 0): ?>selected="true"<?php endif; ?>
                    >按每月固定22天收费</option>
                    <option value="1"
                    <?php if($res[money_rule][food_money_rule] == 1): ?>selected="true"<?php endif; ?>
                    >按照每月实际应出勤天数收费</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p><em>*</em>保育费月出勤天数取值方式：</p>
            </td>
            <td>
                <select name="money_rule[save_money_rule]">
                    <option value="0"
                    <?php if($res[money_rule][save_money_rule] == 0): ?>selected="true"<?php endif; ?>
                    >按每月固定22天收费</option>
                    <option value="1"
                    <?php if($res[money_rule][save_money_rule] == 1): ?>selected="true"<?php endif; ?>
                    >按每月实际应出勤天数收费</option>
                </select>
            </td>
        </tr>
        <tr class="byf-rule">
            <td width="25%" class="right">
                <p><em>*</em>园所保育费退费规则：</p>
            </td>
            <td>
                本月出勤天数小于等于<input type="text" class="percent" autocomplete="off" name="money_rule[back_save_value]" value="<?php echo ($res[money_rule][back_save_value]); ?>"
                                 onkeyup="this.value=this.value.replace(/\D/g,'')"
                                 onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
                <select class="kq-day" name="money_rule[back_save_when]">
                    <option value="0" <?php if($res[money_rule][back_save_when] == 0): ?>selected="true"<?php endif; ?> >天</option>
                    <option value="1" <?php if($res[money_rule][back_save_when] == 1): ?>selected="true"<?php endif; ?> >%</option>
                </select>，
                <select name="money_rule[back_money_rule]" class="rule">
                    <option value="1" <?php if($res[money_rule][back_money_rule] == 1): ?>selected="true"<?php endif; ?> >按百分比</option>
                    <option value="2" <?php if($res[money_rule][back_money_rule] == 2): ?>selected="true"<?php endif; ?> >按缺勤天数</option>
                </select>
                退还

                <span class="day-hide <?php if($res[money_rule][back_money_rule] == 2): ?>hide<?php endif; ?>">
                    <input class="rule-txt" type="text" autocomplete="off" name="money_rule[back_money_value]" value="<?php if($res[money_rule][back_money_value] <= 100): echo ($res[money_rule][back_money_value]); endif; ?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
                    <span class="rule-style">%</span>
                </span>
            费用
                <div>
                    本月出勤天数大于<input type="text" name="money_rule[noback_save_value]" value="<?php if($res[money_rule][back_save_when] == 0): echo ($res[money_rule][back_save_value]); ?>天<?php endif; if($res[money_rule][back_save_when] == 1): echo ($res[money_rule][back_save_value]); ?>%<?php endif; ?>" class="percent-style" disabled/>时，
                    将不退还费用。
                    <span class="error-message error-message3 hide"></span>
                </div>

            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p><em>*</em>收/退款单号/幼儿学号前缀:</p>
            </td>
            <td>
                <input type="text" maxlength="5" class="add-order-num input-text text short" autocomplete="off"
                       name="money_rule[kg_prifex]" value="<?php echo ($res[money_rule][kg_prifex]); ?>">
                <span class="error-message error-message4 hide"></span>
            </td>
        </tr>
        </tbody>

        <tr>
            <td width="25%" class="right">
                <p>备注：</p>
            </td>
            <td>
                <input type="text" class="add-d-remark input-text text short" autocomplete="off"
                       name="dept[dept_remark]" value="<?php echo ($res[dept][dept_remark]); ?>" maxlength="200"/>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="text-center">
        <div class="common-prompt dep-prompt hide">
            <img src="/Public/images/iconfont_sure.png" alt=""/>
            <span></span>
        </div>
        <input id="edit-save-node" type="button" value="保存" class="filter-search edit-save-node"/>
        <input id="edit-back" type="button" value="返回" class="filter-search back-d"/>
    </div>
</form>
<!-- 右侧幼儿园编辑管理内容end-->