<?php

/**
 * 此文件用于定义公共函数
 * 
 * On 2016/07/05 to create by zhangjiawang
 * 
 */

/**
 * 格式化打印数组
 * 
 * @author zhangjiawang
 * @param array $arr 要打印的数组
 */
function p($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

/**
 * 添加操作日志
 * 
 * @author zhangjiawang
 * @param string $log_content 操作描述
 * @param int $result 操作结果 0 失败 1 成功
 * @return int 成功返回true 失败返回false
 * 
 */
function writeOperationLog($log_content, $result) {

    $uid = session('S_USER_INFO.UID');
    $time = date('Y-m-d H:i:s', time());
    $moudle_url = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
    $moudle_info = M('auth_rule')->where("name = '" . $moudle_url . "'")->find();
    if ($moudle_info['level'] != 2) {
	$moudle_info_p = M('auth_rule')->where("id = '" . $moudle_info['pid'] . "'")->find();
    }
    $moudle_name = $moudle_info_p['title'];
    $data = array(
	'moudle_name' => $moudle_name,
	'operation_user_id' => $uid,
	'ip_address' => get_client_ip(),
	'time' => $time,
	'log_content' => '【' . $moudle_info['title'] . '】' . $log_content,
	'result' => $result,
	'status' => 1
    );

    $res = M('operation_log')->add($data);

    return $res ? true : false;
}

/**
 * 数据加密
 * 
 * @author liu qiang <qiang.liu17@pactera.com>
 * @param  array  $data 被加密数据
 * @return string 加密后字符串
 */
function encrypt_data($data) {
    if (!is_array($data)) {
	$data = (array) $data;
    }
    ksort($data);
    $str = http_build_query($data);
    $code = base64_encode(hash_hmac('sha1', $str, C('ENCRYPT_STR'), true));
    return $code;
}

/**
 * 检查是否登录
 * 
 * @author liuqiang
 * @return int 用户ID
 */
function is_login() {
    $user = session('S_USER_INFO');
    if (empty($user)) {
	return 0;
    } else {
	return session('S_USER_SIGN') == encrypt_data($user) ? $user['UID'] : 0;
    }
}

/**
 * 检查验证码是否正确
 * 
 * @author Qiang Liu <qiang.liu17@pactera.com>
 * @param string $verify_code 验证码
 * @return Boolean TRUE:正确 FALSE:错误
 */
function check_verify($verify_code, $id = 1) {
    $verify = new \Think\Verify();
    return $verify->check($verify_code, $id);
}

/**
 * 添加登录日志
 * @author wangqian
 * @param type $status      1登录2退出
 * @param type $operating   登录的结果
 * @param type $nickname    用户登陆失败时，无缓存，则传登陆用户名
 * @return type
 */
function addLoginLog($status, $operating, $nickname = '') {

    //如果退出并且没有缓存，说明已经退出，无须加日志
    $uid = session('S_USER_INFO.UID');
    if ($status == 0 && empty($uid)) {
	return true;
    }
    $data['login_ip'] = get_client_ip();
    $data['server_ip'] = $_SERVER["SERVER_ADDR"];
    $data['login_uid'] = session('S_USER_INFO.UID');
    $data['login_uname'] = $nickname ? $nickname : session('S_USER_INFO.UNAME');
    $data['login_time'] = date('Y-m-d H:i:s', time());
    $data['login_result'] = $operating;
    $data['login_status'] = $status;

    $res = M('LoginLog')->data($data)->add();
    return $res ? true : false;
}

/**
 * 获取无限级数组
 * 
 * @author lizuofeng
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function getTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0) {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
	// 创建基于主键的数组引用
	$refer = array();
	foreach ($list as $key => $data) {
	    $refer[$data[$pk]] = & $list[$key];
	}
	foreach ($list as $key => $data) {
	    // 判断是否存在parent
	    $parentId = $data[$pid];
	    if ($root == $parentId) {
		$tree[] = & $list[$key];
	    } else {
		if (isset($refer[$parentId])) {
		    $parent = & $refer[$parentId];
		    $parent[$child][] = & $list[$key];
		}
	    }
	}
    }
    return $tree;
}

/**
 * 获取模块菜单级别
 * 
 * @author lizuofeng
 * @param $level 模块等级
 * @param $status 是否可用 1可用 0不可用
 * @param $hide 是否隐藏 1隐藏 0正常
 * @return int 成功返回true 失败返回false
 * 
 */
function getMoudel($level = '', $status = '1', $hide = '0') {
    $where[] = array();
    if (!empty($level)) {
	$where['level'] = $level;
    }
    $where['status'] = $status;
    $where['hide'] = $hide;
    $arr = M('auth_rule')->where($where)->select();
    print_r($arr);
}

/**
 * 获取用户的当前模块数据权限
 * 
 * @author lishuaijie
 * @param $uid 用户Id
 * @param $name  模块名称
 * @return 数据权限
 */
function getDataAccess($uid, $name = '') {
    if (empty($name)) {
	$name = __CONTROLLER__ . '/index';
    }
    if (empty($uid)) {
	return C('OTHER.PARAMTER_ERROR');
    }
    if (in_array($uid, C("ADMINISTRATOR"))) {
	return C('DATA_ACCESS.ALL');
    }
    $module_name = ltrim($name, '/');
    $module_info = D('auth_rule')
	    ->where(array('name' => "$module_name", 'level' => 2))
	    ->find();
    //模块Id
    $module_id = $module_info['id'];
    //获取用户所有用的角色 
    $user_model = new Manage\Model\UserModel();
    $user_group_info = $user_model->getRole($uid);
    if (empty($user_group_info)) {
	return C('OTHER.NO_GROUP');
    }
    $group_id_info = array();
    foreach ($user_group_info as $val) {
	$group_id_info[] = $val['id'];
    }
    $group_id = implode($group_id_info, ',');
    //获取 角色对应的数据权限列表
    $data_access_info = D('auth_group')
	    ->where(array('id' => array('in', $group_id), 'status' => 1))
	    ->field('view_premission')
	    ->select();

    $access_info = array();
    $all_access = $module_id . '-' . C('DATA_ACCESS.ALL');
    $department_access = $module_id . '-' . C('DATA_ACCESS.DEPARTMENT');
    $personal_access = $module_id . '-' . C('DATA_ACCESS.PERSONAL');
    foreach ($data_access_info as $val) {
	$access_info = explode(',', $val['view_premission']);
	if (in_array($all_access, $access_info)) {
	    $data_all_access = true;
	}
	if (in_array($department_access, $access_info)) {
	    $data_department_access = true;
	}
	if (in_array($personal_access, $access_info)) {
	    $data_personal_access = true;
	}
    }
    if (true === $data_all_access) {
	return C('DATA_ACCESS.ALL');
    }
    if (true === $data_department_access) {
	return C('DATA_ACCESS.DEPARTMENT');
    }
    if (true === $data_personal_access) {
	return C('DATA_ACCESS.PERSONAL');
    }
}

