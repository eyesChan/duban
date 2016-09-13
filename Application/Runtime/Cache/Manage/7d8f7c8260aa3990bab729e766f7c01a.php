<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" type="text/css" href="/Public/Js/dialog/skins/default.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/base.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/frame.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/container.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/print.css" media="print"/>
    <link href="/Public/Js/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Public/Js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/Public/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/Public/Js/frame.js"></script>
    <script type="text/javascript" src="/Public/Js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="/Public/Js/dialog/lhgdialog.min.js"></script>
    <script type="text/javascript" src="/Public/Js/jquery.validate.js"></script>
    <script type="text/javascript" src="/Public/Js/messages_zh.min.js"></script>
    <title>工作流管理系统</title>
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
                           
                            <span><?php echo ($cvo["title"]); ?>
                                <?php if($cvo['tip']): endif; ?></span>
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
	            	
    <link rel="stylesheet" type="text/css" href="/Public/css/moduleManage.css" />
    <div class="container-padding">
        <div class="wrap-table mt10">
            <form method="POST" action="<?php echo U(save);?>" name="searchForm" id="sform">
                <input type="hidden" name='id' value="<?php echo I('get.id');?>"/>
                <div class="wrap-table">
                    <table class="tbl">
                        <colgroup>
                            <col width="10%">
                            <col width="80%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <td class="cap"><em>*</em>模块编码：</td>
                            <td colspan="3">
                                <input name="menu_num" type="text"  class="input-text ml15" id="menu_num" value="<?php echo ($auth["menu_num"]); ?>" size="21">
                                <span class="error-message hide"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><em>*</em>模块名称：</td>
                            <td colspan="3">
                                <input name="menu_name" type="text"  class="input-text ml15" id="menu_name" value="<?php echo ($auth["title"]); ?>" size="21">
                                <span class="error-message hide"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="cap"><span class="rq">*</span>上级模块：</td>
                            <td colspan="3">
                                <select  class="ml15" id="level" name="level">
                                    <option value="0">请选择</option>
                                    <option value="1" <?php if($auth['pid'] == 0): ?>selected="selected"<?php endif; ?>>顶级目录</option>
                                    <?php if(is_array($module_list)): foreach($module_list as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $auth['pid']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["title"]); ?></option>
                                        <?php if(is_array($vo["children"])): foreach($vo["children"] as $key=>$vo2): ?><option value="<?php echo ($vo2["id"]); ?>" <?php if($vo2['id'] == $auth['pid']): ?>selected="selected"<?php endif; ?>>|--<?php echo ($vo2["title"]); ?></option><?php endforeach; endif; endforeach; endif; ?>
                                </select>
                            </td>
                            </tr>
                        <tr>
                            <td class="cap">模块状态：</td>
                            <td colspan="3">
                                <input class="ml15" name="hide" type="radio" value="0" <?php if($auth['hide'] == 0): ?>checked<?php endif; ?>> 显示
                                <input type="radio" name="hide" value="1" <?php if($auth['hide'] == 1): ?>checked<?php endif; ?>> 隐藏
                            </td>
                        </tr>

                        </tr>
                        <tr>
                            <td class="cap"><span class="rq">*</span>文件路径：</td>
                            <td colspan="3"><input name="menu_url" type="text"  class="input-text ml15" id="menu_url" value="<?php echo ($auth["name"]); ?>" size="21" placeholder="Home/Controller/action"></td>
                        </tr>
                        <tr>
                            <td class="cap">图标路径：</td>
                            <td colspan="3"><input name="map_url" type="text"  class="input-text ml15" id="map_url" value="<?php echo ($auth["map_url"]); ?>" size="21"></td>
                        </tr>
                        <tr>
                            <td class="cap">功能描述：</td>
                            <td colspan="3"><textarea name="mark" cols="21" class="input-text ml15" id="mark"><?php echo ($auth["tip"]); ?></textarea></td>
                        </tr>
                        <tr>
                            <td class="cap">&nbsp;</td>
                            <td colspan="3" class="clear">
                                <input type="button"  style="margin-left: 30px;" value="修改"  onclick="sel1(2)" class="btn btn-a fl btn btn-c btn-xl button" >
                                <a class="back-a module-back fl" href='javascript:history.go(-1);'>返回</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </form>
            <div class="pagination"> </div>
        </div>
    </div>
    <script type="text/javascript" src="/Public/Js/moduleManage.js"></script>

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