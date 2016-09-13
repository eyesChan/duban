<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" type="text/css" href="/Public/Js/dialog/skins/default.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Js/zTree/departzTree.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/base.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/frame.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/container.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/print.css" media="print"/>
    <link href="/Public/Js/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/Public/css/moduleManage.css" />
    <script type="text/javascript" src="/Public/Js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/Public/Js/zTree/jquery.ztree.min.js"></script>
    <script type="text/javascript" src="/Public/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/Public/Js/frame.js"></script>
    <script type="text/javascript" src="/Public/Js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="/Public/Js/dialog/lhgdialog.min.js"></script>

    <script type="text/javascript" src="/Public/Js/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="/Public/Js/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" src="/Public/Js/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript" src="/Public/Js/jquery.validate.js"></script>
    <script type="text/javascript" src="/Public/Js/messages_zh.min.js"></script>
    <js href="/Public/Js/util.js">
    
    <title>
        幼儿园管理系统
    </title>

    <!--[if IE 6]>
    <script type="text/javascript" src="js/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('.jns-logo,.ico,.tip,.ico-arrow-collapse,.ico-arrow-extend');
    </script>
    <![endif]-->
</head>
<body>
<!--头部开始 -->
<div class="header">
    <div class="header-inner com-width">
        <div class="header-hd">
            <div class="header-top-bar clear">

                <a href="<?php echo U('Index/index');?>" id="home" >首页</a>
                <div class="line"></div>
                <a href="<?php echo U('Ucenter/modifyPsd');?>">
                    <span>修改密码</span>
                </a>
                <div class="line"></div>
                <a href="<?php echo U('Login/logout');?>">退出</a>

                <div class="login-person">
                    <div class="clear">
                        <div class="fl">您好！<span><?php echo ($admin_info["UNAME"]); ?> </span>欢迎登录</div>
                    </div>

                </div>
            </div>
            <div class="logo">
                <img src="/Public/images/logo_pic.png" alt=""/>
            </div>
        </div>
        <div class="header-bd">
            <div class="location">
                <!--当前位置：<a href="#">111</a> ~ <a href="#">222</a> ~ <a href="#">333</a>--> 
                当前位置：<?php echo ($crumbs_str); ?>
            </div>
        </div>
    </div>
</div>

<!--头部结束 -->
<!--主体开始 -->
<div class="wrap clear">
    <div class="sidebar">

    <ul class="sys-menu">
        <?php if(is_array($main_menu)): $i = 0; $__LIST__ = $main_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <h2 <?php if(($i) == "1"): endif; ?>>
                    <!--<i class="ico ico-pencil"></i>-->
                    <a href="javascript:void 0;">
                        <?php echo ($vo["title"]); ?>
                    </a>
                    <i class="ico-arrow-collapse"></i>
                </h2>
            <?php if(!empty($vo['children'])): ?><ul class="sub-sys-menu" <?php if(in_array($vo['id'], $selectmenu)): ?>style="display:block"<?php endif; ?>>
                    <?php if(is_array($vo['children'])): $i = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cvo): $mod = ($i % 2 );++$i;?><li <?php if(in_array($cvo['id'], $selectmenu)): ?>class="current"<?php endif; ?>>

                        <a href="/<?php echo ($cvo["name"]); ?>.html" target="_self">
                            <img class="fl sysmenu-icon" src="/Public/images/sysmenu_icon.png" alt=""/>
                            <span><?php echo ($cvo["title"]); ?>
                                <?php if($cvo['tip']): endif; ?></span>
                            <img class="sysmenu_icon_right" src="/Public/images/sysmenu_icon_right.png" alt=""/>

                        </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul><?php endif; ?>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>

    <div class="content">
        <div class="frame-wrap">
            <div class="main-frame" id="main-frame">
	            <div class="inner-container">
	            	
 <link rel="stylesheet" type="text/css" href="/Public/css/expenseRule.css" />
    <div class="container-padding">
        <div class="tb1 jxssp tb1-noborder">
        <form name="query-form" id="expense_rule_update_form" >
            <div class="wrap-table mt10">
                <table class="tbl">
                    <colgroup>
                        <col width="10%">
                        <col width="80%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <td class="cap"><em>*</em>所属幼儿园：</td>
                        <td>
                            <select disabled="disabled" class="ml15" title="请选择部门" name="expense_rule[dept_id]">
                                <option value="">--请选择幼儿园--</option>
                                <?php if(is_array($kids)): foreach($kids as $key=>$vo): ?><option value="<?php echo ($vo['dept_id']); ?>" <?php if($vo[dept_id] == $expense_rule[dept_id]): ?>selected="true"<?php endif; ?> ><?php echo ($vo["dept_name"]); ?></option><?php endforeach; endif; ?>
                            </select>
                          <!--   <label class="error hide" id="select_error" style="color:red">请选择所属幼儿园！</label> -->
                        </td>
                    </tr>
                    <tr>
                        <td class="cap"><em>*</em>费项名称：</td>
                        <td colspan="3">
                            <input  maxlength="30" type="text" class="input-text ml15 expensename-input" name="expense_rule[expense_name]" value="<?php echo ($expense_rule[expense_name]); ?>" />
                            <span class="error-message hide"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cap"><em>*</em>费项类型：</td>
                        <td colspan="3">
                            <input type="radio" name="expense_rule[expense_type]" value="1" class="live-exp input-text ml15" <?php if($expense_rule[expense_type] == 1): ?>checked="true"<?php endif; ?>>保育费
                            <input type="radio" name="expense_rule[expense_type]" value="2" class="food-exp input-text ml15" <?php if($expense_rule[expense_type] == 2): ?>checked="true"<?php endif; ?>>伙食费
                            <input type="radio" name="expense_rule[expense_type]" value="3" class="other-exp input-text ml15" <?php if($expense_rule[expense_type] == 3): ?>checked="true"<?php endif; ?>>其他费项
                        </td>
                    </tr>
                    <tr>
                        <td class="cap"><em>*</em>收费方式：</td>
                        <td colspan="3">
                            <input type="radio" name="expense_rule[charge_mod]" value="1" class="m-ex input-text ml15" <?php if($expense_rule[charge_mod] == 1): ?>checked="true"<?php endif; ?>>按月
                            <input type="radio" name="expense_rule[charge_mod]" value="2" class="t-ex input-text ml15" <?php if($expense_rule[charge_mod] == 2): ?>checked="true"<?php endif; ?>>按学期
                        </td>
                    </tr>
                    <tr>
                        <td class="cap"><em>*</em>费项单价：</td>
                        <td colspan="3">
                            <input type="text" maxlength="8" class="input-text ml15 expense-price-input" name="expense_rule[expense_price]" value="<?php echo ($expense_rule[expense_price]); ?>" />
                            <span class="pre-expense">元/月</span>
                            <span class=" error-message1 hide" style="color: red"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cap">费项说明：</td>
                        <td colspan="3">
                            <textarea  class="ttar"  cols="40" rows="7"  maxlength="100" class="ml15 user-textarea" name="expense_rule[expense_caption]"><?php echo ($expense_rule[expense_caption]); ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="clear term-save" style="position: relative;">
                <input type="hidden"  name="expense_rule[expense_id]" value="<?php echo ($expense_rule[expense_id]); ?>" class="fl hidden-id">
                <input value="保存" type="submit"  id="btn_expense_rule_update_save" class="btn btn-a no_disable fl">
                <a class="save-p hide" id="save-p" >
                            <img  src="/Public/images/iconfont_sure.png" alt=""/>
                            <span></span>
                    </a>
                <a  href='<?php echo U("index",array("p"=>$p));?>' id="btn_Cancel" class=" no_disable back-a fl">返回</a>
            </div>
        </form>
    </div>
    </div>
<script type="text/javascript" src="/Public/Js/expenseRule.js"></script>

	            </div>
            </div>
        </div>
    </div>
</div>
<!--主体结束 -->
<script type="text/javascript">
    //页面功能初始化
    frame.init();
    frame.backHome();
</script>
<script type="text/javascript" src="/Public/Js/common.js"></script>
</body>
</html>