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
	            	
    <link rel="stylesheet" type="text/css" href="/Public/css/userManage.css" />
    <div class="container-padding" style="margin-top: 20px;">
       
        <form method="post" action="<?php echo U(index);?>">
        <table class="tbl">
            <caption>查询条件</caption>
            <tr>
                <td class="cap">用户名：</td>
                <td><input class="input-text text" name="nickname" value="<?php echo ($info[nickname]); ?>" type="text"/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="text-center">
                        <input class="btn btn-a submit" type="submit" value="筛选"/>
                        <a href="<?php echo U('addUser',array('uid'=>''));?>">
                            <input class="btn btn-a submit" type="button" value="新增"/>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
        
        </form>
  
        <div class="tb1 jxssp user-main">
            <table class="tbl tbl-list">
            <caption>用户列表</caption>
                <colgroup>
                    <col width="13%">
                    <col width="13%">
                    <col width="13%">
                    <col width="13%">
                    <col width="13%">
                    <col width="35%">
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" id='allSelect' onclick="allSelect()">
                        序号</th>
                    <th>用户名</th>
                    <th>真实姓名</th>
                    <th>手机号</th>
                    <th>角色</th>
                    <th>操作</th>
                </tr>
                </thead>
                <div class="pwd-prompt hide single-pwd-pro">
                    <img src="/Public/images/iconfont_sure.png" alt=""/>
                    <span></span>
                </div>
                <tbody class="user-tbody">
                <?php if(is_array($list)): foreach($list as $key=>$item): ?><tr>
                        <td>
                            
                            <input type="checkbox" name="childrenBox[]" class="singleSelect" id="singleSelect<?php echo ($item["uid"]); ?>" value="<?php echo ($item["uid"]); ?>"><?php echo ($key +1); ?>
                        </td>
                        <td><?php echo ($item["nickname"]); ?></td>
                        <td><?php echo ($item["name"]); ?></td>
                        <td><?php echo ($item["phone"]); ?></td>
                        <td>
                            <?php if(is_array($role)): foreach($role as $key=>$val): if($val[uid] == $item[uid]): echo ($val["title"]); ?>&nbsp;&nbsp;<?php endif; endforeach; endif; ?>
                        </td>

                        <td class="clear">

                            <a class="fl gl-btn common-operate-btn mr5 common-blue-btn" href="<?php echo U('allocationRole',array('uid'=>$item[uid],'p'=>$p));?>">
                            <input type="button" class="btn btn-c btn-xl button" value="关联角色"></a>
                            <a class="fl cz-btn common-operate-btn mr5 common-green-btn" onclick='singleReset(<?php echo ($item["uid"]); ?>)' href="javascript:;">
                            <input type="button" class="btn btn-c btn-xl button" value="重置密码"></a>

                            <a class="fl operate-btn mr5" title="删除" onclick='singleDel(<?php echo ($item["uid"]); ?>)'>
                            <input type="button" class="operate-span-del btn btn-c btn-xl button" value="删除">
                            </a>
                            <a class="fl operate-btn mr5" title="编辑" href="<?php echo U('saveUser',array('uid'=>$item[uid],'p'=>$p));?>">
                            <input type="button" class="operate-span-edit btn btn-c btn-xl button" value="编辑">
                            </a>
                           
                        </td>
                    </tr><?php endforeach; endif; ?>
                </tbody>


            </table>
            <div class="clear operate-content">
                <div class="batch-operate fl">
                    <div class="pwd-prompt batch-prompt hide">
                        <img src="/Public/images/iconfont_sure.png" alt=""/>
                        <span></span>
                    </div>
                    批量操作：
                    <a href="javascript:javascipt:;" onclick="delAll()" class="user-batch-del batch-del">
                        <img src="/Public/images/batch_del.png" alt=""/>删除</a>|
                    <a href="javascript:;" onclick="resetAll('')" class="user-batch-reset batch-del">
                        <img src="/Public/images/batch_reset.png"  alt=""/>重置密码</a>
                </div>
                <div class="pagination fr">
                    <?php echo ($page); ?>

                </div>

            </div>
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