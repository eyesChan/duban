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
	            	
    <link rel="stylesheet" type="text/css" href="/Public/css/userManage.css" />
    <div class="container-padding">
        <div class="wrap-table mt10 user-allocationRole">
            <form action="<?php echo U(allocationRole);?>" method="post" class="user-allocationRole-form">
                <table class="tbl tbl-list">
                    <caption>当前用户：<?php echo ($info["nickname"]); ?></caption>
                    <colgroup>
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>
                            <label class="checkbox-default user-checkboxAll" for="allSelect"></label>
                            <input class="hide" type="checkbox" id='allSelect'>
                            <span class="cr">选择角色</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="user-tbody">
                    <tr>
                        <?php if(is_array($group_info)): $Ukey = 0; $__LIST__ = $group_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($Ukey % 2 );++$Ukey;?><td>
                                <label class='checkbox-default checkbox-normal
                                   <?php if(is_array($user_group_info)): foreach($user_group_info as $key=>$item): if( $vo["id"] == $item["group_id"] ): ?>checkbox-checked<?php endif; endforeach; endif; ?>
                                   '
                                       for="singleSelect<?php echo ($vo["id"]); ?>"></label>
                                <input type='checkbox' class="singleSelect"
                                <?php if(is_array($user_group_info)): foreach($user_group_info as $key=>$item): if( $vo["id"] == $item["group_id"] ): ?>checked=checked<?php endif; endforeach; endif; ?>
                                id="singleSelect<?php echo ($vo["id"]); ?>" value='<?php echo ($vo["id"]); ?>'
                                name='childrenBox[]'>
                                <span><?php echo ($vo["title"]); ?></span>

                            </td>

                            <?php if($Ukey %5 == 0): ?></tr><tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <input type="hidden" name='uid' value="<?php echo ($info["uid"]); ?>">
                    <input class="btn btn-a no_disable fl" type="submit" name='submit' value="保存"/>
                    <a href="<?php echo U(index);?>" class="no_disable back-a fl">返回</a>
                </div>
            </form>


        </div>
    </div>
    <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
    <script type="text/javascript" src="/Public/Js/userManage.js"></script>

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