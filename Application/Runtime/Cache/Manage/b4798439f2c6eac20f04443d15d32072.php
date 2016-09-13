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
	            	
    <link rel="stylesheet" type="text/css" href="/Public/css/enroll.css" />
    <div class="container-padding">
        <div class="filter-main clear">
            <form method="post" action="<?php echo U(index);?>" id="enrollForm">
                <div class="filter-item"><span>所属幼儿园</span>
                    <select name="kindergarten" id="kindergarten">
                        <option value="0">全部</option>
                        <?php if(is_array($dept_info)): foreach($dept_info as $key=>$item): if( $item["dept_id"] == $serach_data["kindergarten"] ): ?><option value="<?php echo ($item["dept_id"]); ?>" selected="selected"><?php echo ($item["dept_name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($item["dept_id"]); ?>" ><?php echo ($item["dept_name"]); ?></option><?php endif; endforeach; endif; ?>
                    </select>
                </div>
                <div class="filter-item"><span>所属年级</span>
                    <select name="class" id="class">
                        <option value="0">全部</option>
                        <?php if(is_array($grade)): foreach($grade as $key=>$item): if( $item["value"] == $serach_data["class"] ): ?><option value="<?php echo ($item["value"]); ?>" selected=true><?php echo ($item["name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($item["value"]); ?>" ><?php echo ($item["name"]); ?></option><?php endif; endforeach; endif; ?>
                    </select>
                </div>
                <div class="filter-item"><span>状态</span>
                    <select name="status" id="status">
                        <option value="2">全部</option>
                        <option value="0" <?php if($serach_data["status"] === 0): ?>selected<?php endif; ?>>未录取</option>
                        <option value="1" <?php if($serach_data["status"] == 1): ?>selected<?php endif; ?>>已录取</option>
                    </select>
                </div>
                <div class="filter-item" id="item-name">姓名
                    <label>
                        <input name="realname" type="text" id="realname" class="class_name" value="<?php echo ($serach_data["realname"]); ?>" autocomplete="off">
                    </label>
                </div>
                <input class="fl filter-btn filter-search" type="submit" value="筛选"/>
                <!-- <div class="filter-line"></div> -->
                <a href="<?php echo U('addEnroll',array('uid'=>''));?>">
                    <input class="fl filter-btn filter-add pr-add-input" type="button" value=""/>
                </a>
            </form>
        </div>
        <div class="tb1 jxssp user-main">
            <table class="tbl tbl-list">
                <colgroup>
                    <col width="8%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th width="90">
                            <label class="checkbox-default user-checkboxAll" for="allSelect"></label>
                            <input class="hide" type="checkbox" id='allSelect' onClick="allSelect()">
                            序号</th>
                        <th>学生姓名</th>
                        <th>所属年级</th>
                        <th>所属幼儿园</th>
                        <th>年龄</th>
                        <th>性别</th>
                        <th>联系电话</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <!-- <div class="pwd-prompt hide single-pwd-pro">
                    <img src="/Public/images/iconfont_sure.png" alt=""/>
                    <span>重置密码成功！</span>
                </div> -->
                <tbody class="user-tbody">
                <?php if(is_array($list)): foreach($list as $k=>$item): ?><tr>
                        <td>
                            <label class="checkbox-default checkbox-normal" for="singleSelect<?php echo ($item["id"]); ?>"></label>
                            <input type="checkbox" name="childrenBox[]" class="singleSelect" id="singleSelect<?php echo ($item["id"]); ?>" value="<?php echo ($item["id"]); ?>"><?php echo ($k+1); ?>                        </td>
                        <td><?php echo ($item["realname"]); ?></td>
                        <td><?php echo ($grade[$item['class']]['name']); ?></td>
                        <td><?php echo ($dept_info[$item['kindergarten']]['dept_name']); ?></td>
                        <td><?php echo ($item["age"]); ?></td>
                        <td><?php if($item["sex"] == 1): ?>女<?php else: ?>男<?php endif; ?></td>
                    <td><?php echo ($item["tel"]); ?></td>
                    <td>
                    <?php if($item["status"] == 1): ?>已录取
                        <?php else: ?>未录取<?php endif; ?>                    </td>

                    <td class="clear">
                        <a class="fl operate-btn mr5" title="删除" onclick='singleDel(<?php echo ($item["id"]); ?>)'>
                            <span class="operate-span-del"></span>
                        </a>
                        <a class="de-s hide" id="dl<?php echo ($item["id"]); ?>">
                            <img src="/Public/images/iconfont_sure.png" alt=""/>
                            <span></span>
                        </a>
                        <a class="fl operate-btn mr5" title="编辑" href="<?php echo U('saveEnroll',array('id'=>$item[id],'p'=>$p));?>">
                            <span class="operate-span-edit"></span>                        </a>
                        <a class="fl operate-btn mr5" title="查看"  href="<?php echo U('showEnroll',array('id'=>$item[id],'p'=>$p));?>">
                            <span class="operate-span-check"></span>                        </a>
                        <a class="fl operate-btn mr5" title="录取" onclick='admission(<?php echo ($item["id"]); ?>)'>
                            <?php if($item["status"] == 0): ?><span class="operate-span-disabled"></span><?php endif; ?>
                        </a>                    </td>
                    </tr><?php endforeach; endif; ?>
                </tbody>
            </table>
            <div class="clear">
                <div class="batch-operate fl">
                    <div class="pwd-prompt batch-prompt hide">
                        <img src="/Public/images/iconfont_sure.png" alt=""/>
                        批量重置密码成功！
                    </div>
                    批量操作
                    <a href="javascript:javascipt:;" onClick="delAll()" class="user-batch-del batch-del">
                        <img src="/Public/images/batch_del.png" alt=""/>删除</a>|
                    <a href="javascript:;" onClick="admissionAll('')" class="user-batch-reset batch-del">
                        <img src="/Public/images/batch_reset.png"  alt=""/>录取</a>
                    <a class="save-p hide" id="save-p">
                        <img src="/Public/images/iconfont_sure.png" alt=""/>
                        <span></span>
                    </a>
                </div>
                <div class="pagination fr">
                    <?php echo ($page); ?>

                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Public/Js/baseOperate.js"></script>
    <script type="text/javascript" src="/Public/Js/enrollManage.js"></script>

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