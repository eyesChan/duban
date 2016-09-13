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
	            	
<link rel="stylesheet" type="text/css" href="/Public/css/roleManage.css" />
   <div class="role-padding container-padding">
	  <form action="<?php echo U(index);?>" class="r-from">
	     <table class="tbl">
            <caption>查询条件</caption>
            <tr>
                <td class="cap">角色名称：</td>
                <td><input type="text" class="input-text text" name="search" value="<?php echo ($search); ?>"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="text-center">
	                    <a href="<?php echo U(add,array('p'=>$p));?>">
							<input type="button" class="btn btn-a submit" value="新增"/>
						</a>
                    </div>
                </td>
            </tr>
        </table>
		<div class="tb1 jxssp">
		    <table class="tbl tbl-list" width="100%">
		    <caption>角色列表</caption>
			<colgroup>
			    <col width="13%">
			    <col width="25%">
			    <col width="37%">
			    <col width="25%">
			</colgroup>
			<thead>
			    <tr>
					<th>
					    <input type='checkbox' name='cbox' id="allSelect" >序号</th>
					<th>角色名称</th>
					<th>描述</th>
					<th>操作</th>
			    </tr>
			</thead>
			<tbody class="role-tbody">
			<?php if(is_array($info)): foreach($info as $key=>$v): ?><tr class="role-row">
				<td class="role-row-td">
				    <?php if(in_array(($v['id']), is_array($admin)?$admin:explode(',',$admin))): ?><input  type="checkbox" name="childrenBox[]" class="singleSelect"><?php echo ($key +1); ?>
			    <?php else: ?>
			    <label class="checkbox-normal diy-checkbox-normal checkbox-default  diy-checkbox" for="singleSelect<?php echo ($v['id']); ?>"></label>
			    <input id="singleSelect<?php echo ($v['id']); ?>" type='checkbox' class="singleSelect" name='cbox[]' value="<?php echo ($v['id']); ?>"><?php echo ($key +1); endif; ?>

			    </td>
			    <td style="line-height: 20px;" class="role-row-td"><?php echo ($v["title"]); ?></td>
			    <td style="line-height: 20px;" class="role-row-td"><?php echo ($v["describe"]); ?></td>
			    <?php if(in_array(($v['id']), is_array($admin)?$admin:explode(',',$admin))): ?><td class="role-row-td"></td>
			    <?php else: ?>
			    <td class="role-row-td">
				<a class="role-link-btn common-blue-btn mr5" href="<?php echo U('Role/authorizeRole',array('id'=>$v['id'],'p'=>$p));?>">
				<input type="button" class="btn btn-c btn-xl button" value="角色授权"></a>
				<a class="role-ediit operate-btn" title="编辑" href="<?php echo U('Role/edit',array('id'=>$v['id'],'p'=>$p));?>">
				<input type="button" class="operate-span-edit btn btn-c btn-xl button" value="编辑">
				</a>
				<a onclick='singleDel(<?php echo ($v['id']); ?>)'  class="role-delete operate-btn" title="删除" ><input type="button" class="operate-span-del btn btn-c btn-xl button" value="删除">
			    </td><?php endif; ?>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="batch-operate fl">
                批量操作
                <a href="javascript:javascipt:;" onclick="roleDelAll()" class="user-batch-del batch-del">
                    <img src="/Public/images/batch_del.png" alt=""/>删除</a>
            </div>
            <div class="pagination fr">
                <?php echo ($page); ?>
            </div>
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