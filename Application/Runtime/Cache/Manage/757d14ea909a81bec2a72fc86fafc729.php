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
	            	
<link rel="stylesheet" type="text/css" href="/Public/css/roleManage.css" />
    <div class="role-padding container-padding" style="padding-top: 40px;">
        <div class="jxssp tb1">
            <form  id="au-form"  class="auRole" method="POST" action="<?php echo U(save);?>" enctype="multipart/form-data">
                <div class="current-role" >
                    <input type="hidden" name="id" value="<?php echo ($role_info['id']); ?>">
                    <input type="hidden" name="p" value="<?php echo ($p); ?>">
                    当前授权角色：<?php echo ($role_info['title']); ?>
                </div>
                <div class="table-wrap">
                    <table class="tb1 tb2" >
                        <tbody>
                        <?php if(is_array($menu_info)): foreach($menu_info as $key=>$v): if(isset($v['children'])): if(is_array($v['children'])): foreach($v['children'] as $key=>$vv): ?><tr>
                                        <td colspan="2"  class="au-role">
                                            <input id="singleselect<?php echo ($v['id']); ?>_<?php echo ($vv['id']); ?>" class="allCheck" type='checkbox' name="box[]"
                                    <?php if($vv["is_check"] == 1): ?>checked='checked'<?php endif; ?>
                                     value="<?php echo ($v['id']); ?>,<?php echo ($vv['id']); ?>" ><?php echo ($v['title']); ?>><?php echo ($vv['title']); ?></td>
                                    </tr>
                                    <tr class="au-ro-tr clear">
                                        <td  class="op-td1 clear right au-ro-td au-td-op">操作权限：</td>
                                        <td class="op-td1 clear au-ro-td au2" >
                                    <?php if(is_array($vv['children'])): foreach($vv['children'] as $key=>$vvv): ?><input id="singleselect<?php echo ($vvv['id']); ?>" class="au-chec" type='checkbox' name="box[]" <?php if($vvv["is_check"] == 1): ?>checked='checked'<?php endif; ?>value="<?php echo ($vvv['id']); ?>"><span class="operated-auth"style="display:inline-block;margin: 0 10px;"><?php echo ($vvv['title']); ?></span><?php endforeach; endif; ?>
                                    </td>
                                    </tr>
                                    <tr class="au-ro-tr">
                                        <td class="op-td1 right au-ro-td au-td1">查看权限：</td>
                                        <td class="op-td1 au-ro-td au3">
                                            <input class="au-ra s-all-ra"  type='radio' name="view_premission[<?php echo ($vv['id']); ?>]" value="<?php echo ($vv['id']); ?>-1" <?php if($vv["pre"] == 1): ?>checked='checked'<?php endif; ?> ><span class="check-auth">全部</span>
                                    <input class="au-ra" type='radio' name="view_premission[<?php echo ($vv['id']); ?>]" value="<?php echo ($vv['id']); ?>-2" <?php if($vv["pre"] == 2): ?>checked='checked'<?php endif; ?> ><span class="check-auth">部门</span>
                                    <input class="au-ra per-r" type='radio' name="view_premission[<?php echo ($vv['id']); ?>]" value="<?php echo ($vv['id']); ?>-3" <?php if($vv["pre"] == 3): ?>checked='checked'<?php endif; ?> ><span class="check-auth">个人</span>
                                    </td>
                                    </tr><?php endforeach; endif; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="2" class="au-role">
                                    <input id="singleselect<?php echo ($v['id']); ?>" class="" type='checkbox' name="box[]" <?php if($v["is_check"] == 1): ?>checked='checked'<?php endif; ?> value="<?php echo ($v['id']); ?>"><?php echo ($v['title']); ?></td>
                                </tr><?php endif; endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class=" text-center op-wrap">
                        <input type="submit" value="保存" class="filter-search" name="sub" id="sub" />
                        <input type="reset" value="取消" class="filter-search" name="sub" id="reset" onclick="javascript:history.back(-1)"/>
                </div>
            </form>
        </div>
    </div>
     <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
     <script type="text/javascript" src="/Public/Js/roleManage.js"></script>

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