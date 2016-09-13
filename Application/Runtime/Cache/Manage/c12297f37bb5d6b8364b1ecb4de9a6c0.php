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
	            	
<link rel="stylesheet" type="text/css" href="/Public/css/loginlog.css" />
  <div class="role-padding container-padding">
    <div class="wrap-table mt10">
        <form  method="post" action="<?php echo U(OperationLog/index);?>" name="queryOrder-form" class="login-form">
            <table class="tbl">
                <caption>查询条件</caption>
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="50%">
                </colgroup>
                <tbody>
                    <tr>
                        <td class="cap">操作人：</td>
                        <td class="op-td">
                            <input type="text" name="nickname" value="<?php echo ($param['nickname']); ?>" maxlength="20" class="input-text">&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td class="cap">IP地址：</td>
                        <td class="op-td">
                            <input type="text" name="ip_address" maxlength="20" value="<?php echo ($param['ip_address']); ?>" class="input-text">&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td class="cap">操作日期：</td>
                        <td class="op-td" style="position: relative;">
                            <input type="text" id="d4311"  value="<?php echo ($param['begin_time']); ?>" onclick="WdatePicker({ maxDate:'#F{ $dp.$D(\'d4312\')||\'2050-10-01\' }' })" name="begin_time" class="Wdate input-text"> 至&nbsp;<input type="text" id="d4312" value="<?php echo ($param['end_time']); ?>" onclick="WdatePicker({ minDate:'#F{ $dp.$D(\'d4311\') }',maxDate:'2050-10-01' })" name="end_time" class="Wdate input-text">
                            <a class="error-message1 error-id hide">
                                  <img src="/Public/images/login/error.png" alt=""/>
                                   <span>开始日期不能大于结束日期！</span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="cap">日志内容：</td>
                        <td class="op-td">
                            <input type="text" name="log_content" value="<?php echo ($param['log_content']); ?>" maxlength="20"  class="input-text">&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td class="cap">模块名：</td>
                        <td class="op-td">
                            <input type="text" name="moudle_name" value="<?php echo ($param['moudle_name']); ?>" maxlength="20" class="input-text">&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td class="cap"></td>
                        <td>
                            <input type="submit" value="筛选" id="btn_query" class="btn btn-a submit">
                          <!--   <input type="button" value="导出" id="btn_query" class="btn btn-a op-input"> -->
                        </td>

                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div class="wrap-table-a">
        <table class="tbl tbl-list">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="35%">
            </colgroup>
            <thead>
                <tr>
                    <th class="th1">序号</th>
                    <th>操作日期</th>
                    <th>操作人</th>
                    <th>IP地址</th>
                    <th>模块名</th>
                    <th>操作结果</th>
                    <th class="th4" style="text-align: center;">日志内容</th>
                </tr>
            </thead>
            <tbody id="OrderListTbody" class="log-tb">
            <?php if(is_array($list)): foreach($list as $key=>$item): ?><tr>
                    <td><?php echo ($key+1); ?></td>
                    <td><?php echo ($item['time']); ?></td>
                    <td><?php echo ($item['nickname']); ?></td>
                    <td><?php echo ($item['ip_address']); ?></td>
                    <td><?php echo ($item['moudle_name']); ?></td>
                    <td><?php if( $item['result'] == 1): ?>操作成功<?php else: ?>操作失败<?php endif; ?></td>
                    <td style="text-align: left;"><?php echo ($item['log_content']); ?></td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination fr page-padding" >
        <?php echo ($page); ?>
    </div>
</div>
<!-- <script type="text/javascript" src="/Public/Js/opera.js"></script> -->

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