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
	            	
<link rel="stylesheet" type="text/css" href="/Public/css/termManagement.css" />
 <div class="role-padding container-padding" style="padding-top: 40px;">
    <div class="wrap-table mt10">
        <form  method="post" action="<?php echo U(Loginlog/index);?>" onSubmit="return check();" name="queryOrder-form" class="login-form">
            <table class="tbl ad-tb" >
                <colgroup>
                    <col width="10%">
                    <col width="15%">
                    <col width="15%">
                    <col width="50%">
                </colgroup>
                <tbody  class="tbd">
                    <tr>
                        <td class="cap lon own-k">所属幼儿园：</td>
                        <td class="lon check-kg">
                            <select  class="log-sel"name="dept_id">
                                <option value ="-1">全部</option>
                                <?php if(is_array($list)): foreach($list as $key=>$vo): ?><option value ="<?php echo ($vo['dept_id']); ?>" <?php if($post["dept_id"] == $vo['dept_id']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo['dept_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </td>
                        <td class="cap lon st">学期年份：</td>
                        <td class="lon go-sear">
                            <input type="text" id="term_year" value="<?php echo ($post['term_year']); ?>" onclick="WdatePicker({dateFmt:'yyyy',maxDate:'<?php echo ($maxdate); ?>',minDate:'<?php echo ($mindate); ?>'})" name="term_year" class="Wdate input-text">&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" value="筛选" id="btn_query" class="btn btn-a t-s">
                                <a href="<?php echo U('TermManagement/addTerm');?>">
                                    <input class="filter-btn filter-add"  type="button"/>
                                </a>
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
                <col width="15%">
                <col width="10%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr class="add-term-tr">
                    <th>序号</th>
                    <th>所属幼儿园</th>
                    <th>学期年份</th>
                    <th>学期类型</th>
                    <th>收费月份</th>
                    <th>考勤开始日期</th>
                    <th>考勤结束日期</th>
                    <th class="op">操作</th>
                </tr>
            </thead>
            <tbody class="addT-tb" id="OrderListTbody">
            <?php if(is_array($data)): foreach($data as $k=>$data): ?><tr>
                    <td><?php echo ($k+1); ?></td>
                    <td><?php echo ($data['dept_name']); ?></td>
                    <td><?php echo ($data['term_year']); ?></td>
                    <td>
                        <?php switch($data["term_type"]): case "1": ?>春季<?php break;?>
                            <?php case "2": ?>暑假<?php break;?>
                            <?php case "3": ?>秋季<?php break;?>
                            <?php default: ?>寒假<?php endswitch;?>
                    </td>
                    <td><?php echo ($data['toll_month']); ?>月</td>
                    <td><?php echo ($data['work_stime']); ?></td>
                    <td><?php echo ($data['work_etime']); ?></td>
                    <td style="position: relative;">
                        <a class="fl operate-btn mr5" title="删除" onclick="delTerm(<?php echo ($data['id']); ?>);">
                            <span class="operate-span-del cur-span"></span>
                        </a>
                        <a class="de-s hide" id="dl<?php echo ($data['id']); ?>">
                            <img src="/Public/images/iconfont_sure.png" alt=""/>
                            <span></span>
                         </a>
                        <a class="fl operate-btn mr5" title="编辑" href="<?php echo U('editTerm',array('id'=>$data['id']));?>">
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
 <script type="text/javascript" src="/Public/Js/termManage.js"></script>

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