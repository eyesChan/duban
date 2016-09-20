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

/**
 *  会议类型
 * @author lishuaijie
 * @return 所有会议类型
 */
function getMeetType() {
    $config_meemting = D('config_meeting_type');
    $meeting_type = $config_meemting->where(array('meeting_type_state' => 1))->select();
    return $meeting_type;
}
/**
 *  会议级别
 * @author lishuaijie
 * @return 所有会议级别
 */
function getMeetLevel() {
    $config_meemting = D('config_meeting_level');
    $meeting_level = $config_meemting->where(array('meeting_level_state' => 1))->select();
    return $meeting_level;
}
/**
 *  会议形式
 * @author lishuaijie
 * @return 所有会议形式
 */
function getMeetForm() {
    $config_meemting = D('config_meeting_form');
    $meeting_form = $config_meemting->where(array('meeting_form_state' => 1))->select();
    return $meeting_form;
}

/*
 * 邮件发送函数
 * @author xiaohui
 * @param $to  邮件收件人地址
 * @param $titlle  邮件标题
 * @param $content  邮件内容
 * @return object 
 */
 function sendMail($to, $title, $content) {

    Vendor('PHPMailer.PHPMailerAutoload');     
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Port = '465'; 
    $mail->SMTPSecure = 'ssl'; 
    $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
    $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to,"尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return($mail->Send());
    
 }
 
 
/**
 * 导出excel
 * @param type $headArr 表头
 * @param type $data 要导出的数据
 * 
 */
function getExcel($headArr, $data) {
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
    import("Org.Util.PHPExcel.PHPExcel");
    import("Org.Util.PHPExcel.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.PHPExcel.IOFactory.php");

    $date = date("Y_m_d", time());
    $fileName = "excel_{$date}.xls";

    //创建PHPExcel对象，注意，不能少了\
    $objPHPExcel = new \PHPExcel();
    $objProps = $objPHPExcel->getProperties();

    //设置表头
    $key = ord("A");
    $key2 = ord("@");
    //print_r($headArr);exit;
    foreach ($headArr as $v) {
        if ($key > ord("Z")) {
            $key2 += 1;
            $key = ord("A");
            $colum = chr($key2) . chr($key); //超过26个字母时才会启用  
        } else {
            if ($key2 >= ord("A")) {
                $colum = chr($key2) . chr($key); //超过26个字母时才会启用  
            } else {
                $colum = chr($key);
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;
    }
    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();

    foreach ($data as $key => $rows) { //行写入
        $key = ord("A");
        $key2 = ord("@");
        foreach ($rows as $keyName => $value) {// 列写入
            if ($key > ord("Z")) {
                $key2 += 1;
                $key = ord("A");
                $colum = chr($key2) . chr($key); //超过26个字母时才会启用  
            } else {
                if ($key2 >= ord("A")) {
                    $colum = chr($key2) . chr($key); //超过26个字母时才会启用  
                } else {
                    $colum = chr($key);
                }
            }
//            $objPHPExcel->getActiveSheet()->setCellValueExplicit($colum . $column, $value, PHPExcel_Cell_DataType::TYPE_STRING);
//            $objPHPExcel->getActiveSheet()->getStyle($colum . $column)->getNumberFormat()->setFormatCode("@");
            $objActSheet->setCellValue($colum . $column, $value);
            $key++;
        }
        $column++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName);
    //重命名表
    //$objPHPExcel->getActiveSheet()->setTitle('test');
    //设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0);
    ob_end_clean();
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); //文件通过浏览器下载
    exit;
}

