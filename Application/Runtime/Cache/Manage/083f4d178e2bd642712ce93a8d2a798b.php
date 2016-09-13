<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" type="text/css" href="/Public/Js/dialog/skins/default.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Js/easyui/easyui.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Js/easyui/icon.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Js/easyui/demo.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Js/zTree/zTreeStyle.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/base.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/frame.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/container.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/common.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/rewriteTree.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/roleManage.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/print.css" media="print"/>
    <link href="/Public/Js/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Public/Js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/Public/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/Public/Js/frame.js"></script>
    <script type="text/javascript" src="/Public/Js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="/Public/Js/dialog/lhgdialog.min.js"></script>
    <script type="text/javascript" src="/Public/Js/jquery.validate.js"></script>
    <script type="text/javascript" src="/Public/Js/messages_zh.min.js"></script>
    <script type="text/javascript" src="/Public/Js/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/Public/Js/zTree/jquery.ztree.min.js"></script>
    <title>跳转提示</title>
    <!--[if IE 6]>
    <script type="text/javascript" src="js/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('.jns-logo,.ico,.tip,.ico-arrow-collapse,.ico-arrow-extend');
    </script>
    <![endif]-->
</head>
<body class="easyui-layout">
<!--顶部通栏-->
<div data-options="region:'north',border:false" style="height:120px;">
    <div class="header">
    <div class="header-inner com-width">
        <div class="header-hd">
            <div class="header-top-bar clear">
                <a href="<?php echo U('Index/index');?>" id="home" >首页</a>
                <div class="line"></div>
                <!-- <a href="<?php echo U('Ucenter/modifyPsd');?>"></a> -->
                <a class="updatePwd" address="<?php echo U('Ucenter/modifyPsd');?>" href="javascript:;"><span>修改密码</span></a>
                <div class="line"></div>
                <a href="<?php echo U('Login/logout');?>">退出</a>
            </div>
            <div class="logo">
                <img src="/Public/images/logo_pic.png" alt=""/>
            </div>
        </div>
    </div>
</div>
</div>
<!--左侧菜单栏-->
<div data-options="region:'west',split:true" style="width: 200px;">
    <ul id="webMenu_list" class="ztree">
    </ul>
</div>
<!--中部管理部分-->
<div data-options="region:'center'">
    <div id="tt" class="easyui-tabs" data-options="fit:true">
    </div>
</div>
<div id="mainTabs" class="easyui-menu"></div>
<!--右键菜单-->
<!-- <div id="mm" class="easyui-menu" style="width:120px;">
    <div id="mm-tabclosrefresh" data-options="name:6">刷新</div>
    <div id="mm-tabclose" data-options="name:1">关闭</div>
    <div id="mm-tabcloseall" data-options="name:2">全部关闭</div>
    <div id="mm-tabcloseother" data-options="name:3">除此之外全部关闭</div>
    <div class="menu-sep"></div>
    <div id="mm-tabcloseright" data-options="name:4">当前页右侧全部关闭</div>
    <div id="mm-tabcloseleft" data-options="name:5">当前页左侧全部关闭</div> -->
</div>
</div>
<script type="text/javascript" src="/Public/Js/index.js"></script>
</body>
</html>