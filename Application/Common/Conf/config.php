<?php
return array(
    /* 数据库配置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '192.168.5.66', // 服务器地址
    //'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'duban', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'root', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'db_', // 数据库表前缀

    /* 操作成功、失败跳转页面 */
    'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump',
    'TMPL_ACTION_ERROR' => 'Public:dispatch_jump',
    'URL_MODEL' => 2,
    /* 超级管理员id */
    'ADMINISTRATOR' => array(1),
    /* 不需要控制的权限 */
    'NO__AUTH' => array('Manage/Login/index', 'Manage/Login/logout','Manage/Index/index', 'Manage/Ucenter/modifyPsd','Manage/MessageManage/showList','Manage/MessageManage/showDetail'),

    /* 加密串 */
    'ENCRYPT_STR' => 'kindergarten201607',
    /* 重置密码 */
    'RESET_PWD' => '123abc',
    /* 默认密码 */
    'LOCAL_PWD' => '123abc',
    'DATA_ACCESS' => array(
        'ALL' => 1, //全部权限
        'DEPARTMENT' => 2, //部门
        'PERSONAL' => 3, //个人
    ),
    'OPEN_FTP'=>'1', //上传启用ftp
);


