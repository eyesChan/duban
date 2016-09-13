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
	            	
    <link rel="stylesheet" type="text/css" href="/Public/css/noticeManage.css" />
    <div class="role-padding container-padding">
        <div class="filter-main clear">
            <form method="post" action="<?php echo U(index);?>">

                <div class="filter-item"><span>状态:</span>
                    <select name="status">
                        <option value="100">--全部--</option>
                        <option value="2"
                        <?php if($status == '2'): ?>selected='selected'<?php endif; ?>
                        >未发布</option>
                        <option value="1"
                        <?php if($status == '1'): ?>selected='selected'<?php endif; ?>
                        >已发布</option>
                        <option value="3"
                        <?php if($status == '3'): ?>selected='selected'<?php endif; ?>
                        >已失效</option>
                        <option value="0"
                        <?php if($status == '0'): ?>selected='selected'<?php endif; ?>
                        >已停用</option>
                    </select>
                </div>

                <div class="filter-item"><span>公告标题</span>
                    <input type="text" name="title" value="<?php echo ($title); ?>">
                </div>

                <input class="fl filter-btn filter-search" type="submit" value="筛选"/>
                <a href="<?php echo U(add,array('p'=>$p));?>">
                    <input class="fl filter-btn filter-add" type="button"/>
                </a>
            </form>
        </div>
        <div class="tb1">
            <form action="<?php echo U(index);?>">
                <div class="jxssp">
                    <div class="common-prompt notice-prompt hide">
                        <img src="/Public/images/iconfont_sure.png" alt=""/>
                        <span></span>
                    </div>
                    <table class="tbl" width="100%">
                        <colgroup>
                            <col width="5%">
                            <col width="20%">
                            <col width="25%">
                            <col width="10%">
                            <col width="10%">
                            <col width="20%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="role-table-title role-tit">序号</th>
                            <th class="role-table-title">公告标题</th>
                            <th class="role-table-title">起始时间</th>
                            <th class="role-table-title">状态</th>
                            <th class="role-table-title">录入人</th>
                            <th class="role-table-title">录入时间</th>
                            <th class="role-table-title op">操作</th>
                        </tr>
                        </thead>

                        <tbody class="role-tbody">
                        <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr>
                                <td><?php echo ($key +1); ?></td>
                                <td><?php echo ($v['title']); ?></td>
                                <td><?php echo ($v['start_time']); ?>至<?php echo ($v['end_time']); ?></td>
                                <?php if($v['status'] == 1): ?><td>已发布</td>
                                    <?php elseif($v['status'] == 2): ?>
                                    <td>未发布</td>
                                    <?php elseif($v['status'] == 3): ?>
                                    <td>已失效</td>
                                    <?php else: ?>
                                    <td>已停用</td><?php endif; ?>
                                <td><?php echo ($v['name']); ?></td>
                                <td><?php echo ($v['create_time']); ?></td>
                                <td>
                                    <a class="fl operate-btn mr5" title="编辑"
                                       href="<?php echo U('NoticeManage/edit',array('id'=>$v['id'],'p'=>$p));?>">
                                        <span class="operate-span-edit"></span>
                                    </a>&nbsp;
                                    <a class="fl operate-btn mr5" title="删除" href="javascript:void(0);"
                                       onclick="singleDel(<?php echo ($v['id']); ?>)">
                                        <span class="operate-span-del"></span>
                                    </a>&nbsp;
                                    <?php if($v['status'] == 1): ?><a  class="fl operate-btn mr5" href="javascript:void(0);"
                                                                         onclick="changeStatus(<?php echo ($v['id']); ?>, 0)">
                                        <span title="已停用" class="operate-span-disabled"></span>
                                    </a>
                                        <?php elseif($v['status'] == 0 or $v['status'] == 2): ?>
                                        <a  class="fl operate-btn mr5" href="javascript:void(0);" onclick="changeStatus(<?php echo ($v['id']); ?>, 1)">
                                            <span title="发布" class="operate-span-open"></span>
                                        </a>
                                        <?php elseif($v['status'] == 3): endif; ?>
                                </td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="pagination fr">
                <?php echo ($page); ?>
            </div>
        </div>
        <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
        <script type="text/javascript" src="/Public/Js/noticeManage.js"></script>

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