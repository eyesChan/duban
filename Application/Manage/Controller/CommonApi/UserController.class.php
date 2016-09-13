<?php

namespace Manage\Controller\CommonApi;

use Manage\Model\UserModel;

/**
 * 用户相关操作公共API
 * 
 * @author Qiang Liu <qiang.liu17@pactera.com>
 * @version 1.0
 * @createTime 2016-07-08
 * @lastUpdateTime 2016-07-08
 */
class UserController {

    /**
     * 用户登录操作
     * 
     * @param string $nickname 用户名
     * @param string $password 密码
     * @param string $verify_code 验证码
     * @return array 正确返回用户信息，错误返回错误信息
     */
    public function login($nickname, $password, $verify_code) {
        if (!check_verify($verify_code)) {
            return C('USER.ERROR_VERIFY_CODE');
        }
        $member_model = D('Member');
        $user_info = $member_model->getUserinfoByNickname($nickname);
        if (empty($user_info)) {
            $result = C('USER.ERROR_USERNAME');
            writeOperationLog('“' . $nickname . '”' . $result['status'], 0);
            return $result;
        }else if($user_info['status'] == 2){
            $result = C('USER.DISABLE_USER');
            writeOperationLog('“' . $nickname . '”' . $result['status'], 0);
            return $result;
        }
        if ($user_info['password'] !== strtolower(md5(md5($password)))) {
            $result = C('USER.ERROR_PASSWORD');
            writeOperationLog('“' . $nickname . '”' . $result['status'], 0);
            return $result;
        }
        session('S_USER_INFO', array('UID' => $user_info['uid'], 'UNAME' => $nickname));
        writeOperationLog('“' . $nickname . '”' . '登录成功', 1);
        session('S_USER_SIGN', encrypt_data(array('UID' => $user_info['uid'], 'UNAME' => $nickname)));
        return C('USER.LOGIN_SUCCESS');
    }

    /**
     * 用户添加
     * 
     * @param string $insert_id 用户id
     * @param array $insert_data 用户数据 
     * @param data 用户数据
     * @return null
     */
    public function saveUser($insert_data, $insert_id = '') {
        $model = new UserModel();
        // 编辑后入库
        if ($insert_id && $insert_data) {
            $insert_data['update_time'] = date('Y-m-d H:i:s');
            //验证nickname唯一  
            if (!$model->checkNickName(array('nickname' => $insert_data['nickname'], 'uid' => $insert_id))) {
                return C('USER.NICKNAME_EXISTENCE');
            }
            //验证手机号
            if (!empty($insert_data['phone']) && !$model->isMobile($insert_data['phone']) && !ctype_space($insert_data['phone'])) {
                return C('USER.LASK_PARAMTER');
            }
            $insert_data['uid'] = $insert_id;
            //执行修改操作
            $uid = $model->saveUser($insert_data);
            if ($uid) {
                return C('USER.SAVE_SUCCESS');
            }
            return C('USER.SAVE_ERROR');
        }
        //新增入库
        if (empty($insert_id) && $insert_data) {
            $insert_data['reg_time'] = date('Y-m-d H:i:s');
            $insert_data['reg_ip'] = \get_client_ip();
            $insert_data['update_time'] = date('Y-m-d H:i:s');
            $insert_data['role_id'] = 1;
            $insert_data['status'] = 1;
            $insert_data['password'] = md5(md5(C('local_pwd')));
            //验证手机号
            if (!empty($insert_data['phone']) && !$model->isMobile($insert_data['phone'])&& !ctype_space($insert_data['phone'])) {
                return C('USER.LASK_PARAMTER');
            }
            if (!$model->create($insert_data)) {
                return C('USER.PARAMTER_ERROR');
            }
            $user_id = $model->insertData($insert_data);
            if ($user_id) {
                return C('USER.ADD_SUCCESS');
            }
        }
        return C('USER.ADD_ERROR');
    }

}
