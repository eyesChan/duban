<?php if (!defined('THINK_PATH')) exit();?><!-- 右侧幼儿园管理内容start-->

<form method="post" action="" name="departmentForm" novalidate="novalidate">
    <table class="tbl">
        <caption>部门资料</caption>
        <tbody>
        <tr>
            <td width="25%" class="right">
                <p>部门编号：</p>
            </td>
            <td>
                <span class="d-item-show"><?php echo ($res[dept][dept_num]); ?></span>
                <input id="dept_id" type="hidden" name="dept[dept_id]" value="<?php echo ($res[dept][dept_id]); ?>" />
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门名称：</p>
            </td>
            <td>
                <span class="d-name d-item-show"><?php echo ($res[dept][dept_name]); ?></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门负责人：</p>
            </td>
            <td>
                <span class="d-person d-item-show"><?php echo ($res[dept][dept_manager]); ?></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门电话：</p>
            </td>
            <td>
                <span class="d-item-show"><?php echo ($res[dept][dept_tel]); ?></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>部门传真：</p>
            </td>
            <td>
                <span class="d-item-show"><?php echo ($res[dept][dept_fax]); ?></span>
            </td>
        </tr>
        <tr>
            <td width="25%" class="right">
                <p>机构类别：</p>
            </td>
            <td>
                <?php if($res[dept][dept_category] == 0): ?><span class="d-item-show">幼儿园</span><?php endif; ?>
                <?php if($res[dept][dept_category] == 1): ?><span class="d-item-show">非幼儿园</span><?php endif; ?>
            </td>
        </tr>
        <?php if($res[dept][dept_category] == 0): ?><tbody id="hide-tr">
                <tr>
                    <td width="25%" class="right">
                        <p>园所伙食费收费规则：</p>
                    </td>
                    <td>
                        <?php if($res[money_rule][food_money_rule] == 0): ?><span class="d-item-show">按每月固定22天收费</span><?php endif; ?>
                        <?php if($res[money_rule][food_money_rule] == 1): ?><span class="d-item-show">按每月实际应出勤天数收费</span><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="right">
                        <p>保育费月出勤天数取值方式：</p>
                    </td>
                    <td>
                        <?php if($res[money_rule][save_money_rule] == 0): ?><span class="d-item-show">按每月固定22天收费</span><?php endif; ?>
                        <?php if($res[money_rule][save_money_rule] == 1): ?><span class="d-item-show">按照每月实际应出勤天数收费</span><?php endif; ?>
                    </td>
                </tr>
                <tr class="byf-rule">
                    <td width="25%" class="right">
                        <p>园所保育费退费规则：</p>
                    </td>
                    <td>
                        <div>
                            本月出勤天数小于等于<span class="d-item-show"><?php echo ($res[money_rule][back_save_value]); ?></span>
                            <?php if($res[money_rule][back_save_when] == 0): ?><span class="d-item-show">天</span><?php endif; ?>
                            <?php if($res[money_rule][back_save_when] == 1): ?><span class="d-item-show">%</span><?php endif; ?>
                            ，<?php if($res[money_rule][back_money_rule] == 1): ?><span class="d-item-show">按百分比</span><?php endif; ?>
                            <?php if($res[money_rule][back_money_rule] == 2): ?><span class="d-item-show">按缺勤天数</span><?php endif; ?>
                            退还<span class="d-item-show <?php if($res[money_rule][back_money_rule] == 2): ?>hide<?php endif; ?>"><?php echo ($res[money_rule][back_money_value]); ?>%</span>费用
                        </div>
                        <div>
                            本月出勤天数大于<span class="d-item-show"><?php echo ($res[money_rule][back_save_value]); ?>
                            <?php if($res[money_rule][back_save_when] == 0): ?>天<?php endif; ?>
                            <?php if($res[money_rule][back_save_when] == 1): ?>%<?php endif; ?></span>时，将不退还费用。
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="right">
                        <p>收/退款单号/幼儿学号前缀:</p>
                    </td>
                    <td>
                        <span class="d-item-show"><?php echo ($res[money_rule][kg_prifex]); ?></span>
                    </td>
                </tr>
            </tbody><?php endif; ?>
        <tr>
            <td width="25%" class="right">
                <p>备注：</p>
            </td>
            <td>
                <span class="d-item-show"><?php echo ($res[dept][dept_remark]); ?></span>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="text-center">
        <div class="common-prompt dep-prompt hide">
            <img src="/Public/images/iconfont_sure.png" alt=""/>
            <span></span>
        </div>
        <input id="add-d-node-2" type="button" value="在此节点下新增" class="filter-search add-d-node"/>
        <input id="edit-d-node-2" type="button" value="编辑" class="filter-search edit-d-node"/>
        <input id="del-d-node-2" class="filter-search del-d-node" type="button" value="删除"/>
    </div>
</form>
<!-- 右侧幼儿园管理内容end-->