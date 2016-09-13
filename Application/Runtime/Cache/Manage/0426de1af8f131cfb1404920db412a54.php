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
    <title>幼儿园管理系统</title>
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
	            	
    <link rel="stylesheet" type="text/css" href="/Public/css/departmentManage.css" />
    <!-- 页面上部导航end-->
    <div class="btn-common add-dept-btn">
        <a class="depart-add"><input id="add-node" class="fr depart-add-btn filter-add filter-btn" type="button"/></a>
    </div>
    <div class="clear manage-main">
        <!-- 左侧幼儿园树形图start-->
        <div class="ztree-manage fl">
            <div class="department-fl clear">
                <!--左侧类目、全部商品信息-->
                <div class="department-tree fl">
                    <div class="department-tree-detail fl">
                        <ul id="department" class="department-Ztree"></ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 左侧幼儿园树形图end-->
        <!-- 右侧幼儿园管理内容start-->
        <div class="department-item commmon-depart tb1">
            <form method="post" action="" name="departmentForm"
                  novalidate="novalidate" id="index-id">
                <input type="hidden" name="ACCOUNT_ID" value="-2" class="hidden">
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
                        <?php if($res[money_rule][food_money_rule] == 1): ?><span class="d-item-show">按照每月实际应出勤天数收费</span><?php endif; ?>
                        </td>
                        </tr>
                        <tr>
                            <td width="25%" class="right">
                                <p>保育费月出勤天数取值方式：</p>
                            </td>
                            <td>
                        <?php if($res[money_rule][save_money_rule] == 0): ?><span class="d-item-show">按每月固定22天收费</span><?php endif; ?>
                        <?php if($res[money_rule][save_money_rule] == 1): ?><span class="d-item-show">按每月实际应出勤天数收费</span><?php endif; ?>
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
                    <input id="add-d-node-1" type="button" value="在此节点下新增" class="filter-search add-d-node"/>
                    <input id="edit-d-node-1" type="button" value="编辑" class="filter-search edit-d-node"/>
                    <input id="del-d-node-1" class="filter-search del-d-node" type="button" value="删除"/>
                </div>
            </form>
        </div>
        <!-- 右侧幼儿园管理内容end-->
    </div>

    <script type="text/javascript" src="/Public/Js/departmentManage.js"></script>

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