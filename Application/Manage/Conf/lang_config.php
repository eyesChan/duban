<?php

return array(
    /* 公共提示 */
    'COMMON' => array(
	'ADD_SUCCESS' => '保存成功',
	'ADD_ERROR' => '保存失败',
	'EDIT_SUCCESS' => '保存成功',
	'EDIT_ERROR' => '保存失败',
	'DEL_SUCCESS' => '删除成功',
	'DEL_ERROR' => '删除失败',
        'AUTH_ERROR' => array(
            'status' => 0,
            'info' => '没有权限'
        ),
	'CHECK_SUCCESS' => array(
	    'code' => 200,
	    'status' => '验证成功',
	),
	'PARAMTER_ERROR' => array(
	    'code' => 100,
	    'status' => '参数有误',
	),
	'SUCCESS_ADD' => array(
	    'code' => 200,
	    'status' => '保存成功'
	),
	'ERROR_ADD' => array(
	    'code' => 100,
	    'status' => '保存失败'
	),
	'SUCCESS_EDIT' => array(
	    'code' => 200,
	    'status' => '保存成功'
	),
	'ERROR_EDIT' => array(
	    'code' => 100,
	    'status' => '保存失败'
	),
	'SUCCESS_DEL' => array(
	    'code' => 200,
	    'status' => '删除成功'
	),
        'SUCCESS_DELS' => array(
	    'code' => 200,
	    'status' => '批量删除成功'
	),
	'ERROR_DEL' => array(
	    'code' => 100,
	    'status' => '删除失败'
	),
	'ADD_SUCCESS_ARR' => array(
	    'code' => 100,
	    'status' => '保存失败'
	),
	'ADD_ERROR_ARR' => array(
	    'code' => 200,
	    'status' => '保存成功'
	),
        'UPLOAD_ERROR'=>array(
            'code'=>100,
            'status'=>'上传失败',
        ),
        'UPLOAD_SUCCESS'=>array(
            'code'=>200,
            'status'=>'上传成功'
        ),
        'DOCDEL_ERROR' => array(
	    'code' => 100,
	    'status' => '撤回失败'
	),
	'DOCDEL_SUCCESS' => array(
	    'code' => 200,
	    'status' => '撤回成功'
	),
        'IMPORT_ERROR' => array(
	    'code' => 100,
	    'status' => '导入失败'
	),
	'IMPORT_SUCCESS' => array(
	    'code' => 200,
	    'status' => '导入成功'
	),
        'EXPORT_ERROR' => array(
	    'code' => 100,
	    'status' => '导出失败'
	),
	'EXPORT_SUCCESS' => array(
	    'code' => 200,
	    'status' => '导出成功'
	),
    ),
    /* 用户相关提示 */
    'USER' => array(
	'LOGIN_SUCCESS' => array('code' => 200, 'status' => '登录成功'),
	'ERROR_VERIFY_CODE' => array('code' => 903, 'status' => '验证码错误'),
	'ERROR_USERNAME' => array('code' => 901, 'status' => '用户不存在'),
	'ERROR_PASSWORD' => array('code' => 902, 'status' => '密码错误'),
	'DISABLE_USER' => array('code' => 904, 'status' => '用户已被禁用'),
	'NICKNAME_EXISTENCE' => array('status' => '用户名已被使用', 'code' => 100),
	'LASK_PARAMTER' => array('status' => '缺少必要参数', 'code' => 100),
	'PARAMTER_ERROR' => array('status' => '参数有误', 'code' => 100),
	'ADD_SUCCESS' => array('status' => '保存成功', 'code' => 200),
	'ADD_ERROR' => array('status' => '保存失败', 'code' => 100),
	'SAVE_SUCCESS' => array('status' => '保存成功', 'code' => 200),
	'SAVE_ERROR' => array('status' => '保存失败', 'code' => 100),
	'ERROR_ALLOCATIONROLE' => '保存失败',
	'SUCCESS_ALLOCATIONROLE' => '保存成功',
	'SUCCESS_DELETE' => '删除成功！',
	'SUCCESS_DELETES' => '批量删除成功！',
	'ERROR_DELETE' => '删除失败',
	'ERROR_DISABLE' => '禁用操作失败',
	'SUCCESS_DISABLE' => '禁用成功',
	'SUCCESS_OPEN' => '启用成功',
	'ERROR_RESET' => '重置密码失败',
	'SUCCESS_RESET' => '重置密码成功',
	'SUCCESS_RESETS' => '批量重置密码成功',
        'USER_ERROR'=>array('code'=>100,'status'=>'当前用户不能删除 '),
	'OLD_PWD_ERROR' => array('status' => 701, 'msg' => "原登录密码不正确！"),
	'PWD_DIFFERENT' => array('status' => 702, 'msg' => "两次密码输入不一致！"),
	'PWD_MODIFY_SUCCESS' => array('status' => 703, 'msg' => "修改密码成功，请重新登录"),
	'PWD_MODIFY_ERROR' => array('status' => 704, 'msg' => "密码格式不符合规则"),
	'CHECK_ROLE_ERROR' => array('status' => 705, 'msg' => "该角色名称已被使用!"),
	'CHECK_ROLE_PASS' => array('status' => 200),
    ),
    'ROLE' => array(
	'ROLE_DELETE_ERROR' => '该角色已关联用户，暂不能删除',
	'ROLE_DELETE_ERROR2' => '存在已关联用户的角色，暂不能删除！',
	'ROLE_DELETE_AUTHORIZE_SUCCESS' => '授权成功',
	'ROLE_DELETE_AUTHORIZE_ERROR' => '授权失败',
	'ROLE_FORMAT_ERROR' => '角色名称格式不正确!',
	'ROLE_DESC_ERROR' => '描述格式不正确!',
	'ROLE_IS_EXIST' => '该角色名称已被使用！',
	'ROLE_DELETE_RELATION' => array('status' => 100, 'msg' => "存在已关联用户的角色，暂不能删除！"),
	'ROLE_DELETE_RELATION1' => array('status' => 101, 'msg' => "该角色已关联用户，暂不能删除！"),
	'ROLE_DELETE_RELATION2' => array('status' => 200),
    ),
    'OTHER' => array(
	'PARAMTER_ERROR' => '参数有误',
	'NO_GROUP' => array('code' => '100', 'status' => '用户未分配角色'),
    ),
    /* 模块管理提示 */
    'MOUDEL' => array(
	'MOUDEL_EXIST' => '模块编码,已存在',
	'MENU_URL_EXIST' => '文件路径,已存在',
	'MENU_NUM_EXIST' => '模块编码,已存在',
	'CHILD_EXIST' => '存在子模块，不允许删除',
    ),
  /* 文档管理提示 */
   'DOCFILE'=>array(
       'SZIE_TYPE'=>'文件的类型或大小不符',
   )
);
