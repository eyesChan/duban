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
	            	
   <link rel="stylesheet" type="text/css" href="/Public/css/expenseRule.css" />
    <div class="container-padding">
        <div class="filter-main clear">
        <form method="post" action="<?php echo U(index);?>">
            <div class="filter-item"><span>所属幼儿园</span>
                <select name="dept_id" id="dept_id">
                    <option value="0">全部</option>
                    <?php if(is_array($kids)): foreach($kids as $key=>$item): ?><option value="<?php echo ($item['dept_id']); ?>" <?php if($item['dept_id'] == $dept_id): ?>selected="true"<?php endif; ?> ><?php echo ($item['dept_name']); ?></option><?php endforeach; endif; ?>
                </select>
            </div>
            <div class="filter-item"><span>费项类型</span>
                <select name="expense_type" id="expense_type">
                    <option value="0" <?php if($expense_type == 0): ?>selected="true"<?php endif; ?>>全部</option>
                    <option value='1' <?php if($expense_type == 1): ?>selected="true"<?php endif; ?>>保育费</option>
                    <option value='2' <?php if($expense_type == 2): ?>selected="true"<?php endif; ?>>伙食费</option>
                    <option value='3' <?php if($expense_type == 3): ?>selected="true"<?php endif; ?>>其他费项</option>
                </select>
            </div>
            <input class="fl filter-btn filter-search" type="submit" value="筛选"/>
            <div class="filter-line"></div>
            <a href="<?php echo U('addExpenseRule');?>">
                <input class="fl filter-btn filter-add" type="button" value=""/>
            </a>
        </form>
        </div>

        <div class="tb1 jxssp user-main">
            <table class="tbl tbl-list">
                <colgroup>
                    <col width="8%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="15%">
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <input class="hide" type="checkbox" id='allSelect' onclick="allSelect()">
                        序号</th>
                    <th>费项名称</th>
                    <th>费项类型</th>
                    <th>所属幼儿园</th>
                    <th>单价</th>
                    <th>收费方式</th>
                    <th>创建时间</th>
                    <th>费项说明</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="user-tbody">
                <?php if(is_array($res['expense_rule'])): foreach($res['expense_rule'] as $key=>$item): ?><tr>
                        <td>
                            <input type="checkbox" name="childrenBox[]" class="singleSelect" id="singleSelect<?php echo ($item["uid"]); ?>" value="<?php echo ($item["uid"]); ?>"><?php echo ($key +1); ?>
                        </td>
                        <td><?php echo ($item["expense_name"]); ?></td>
                        <td>
                            <?php switch($item["expense_type"]): case "1": ?>保育费<?php break;?>
                                <?php case "2": ?>伙食费<?php break;?>
                                <?php case "3": ?>其他费项<?php break;?>
                                <?php default: endswitch;?>
                        </td>
                        <td>
                            <?php if(is_array($kids)): foreach($kids as $key=>$v): if($item["dept_id"] == $v['dept_id']): echo ($v["dept_name"]); endif; endforeach; endif; ?>
                        </td>
                        <td><?php echo ($item["expense_price"]); ?></td>
                        <td>
                            <?php if($item["charge_mod"] == 1): ?>按月<?php endif; ?>
                            <?php if($item["charge_mod"] == 2): ?>按学期<?php endif; ?>
                        </td>
                        <td><?php echo ($item["creation_time"]); ?></td>
                        <td><?php echo ($item["expense_caption"]); ?></td>
                        <td class="clear expense-op" >
                            <a class="fl operate-btn mr5" title="删除" onclick="deleteExpenseRule(<?php echo ($item['expense_id']); ?>)">
                                <span class="operate-span-del"></span>
                            </a>
                            <a class="de-s hide" id="dl<?php echo ($item['expense_id']); ?>">
                            <img src="/Public/images/iconfont_sure.png" alt=""/>
                            <span></span>
                         </a>
                            <a class="fl operate-btn mr5" title="编辑" href="<?php echo U('updateExpenseRule',array('expense_id'=>$item['expense_id']));?>">
                                <span class="operate-span-edit"></span>
                            </a>
                        </td>
                    </tr><?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination fr">
            <?php echo ($page); ?>
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