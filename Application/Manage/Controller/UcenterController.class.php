<?php

namespace Manage\Controller;

use Manage\Model\UserModel;

/**
 *  Description   用户中心后台用户的管理操作
 *  @author       liuqiang <qiang.liu17@pactera.com>
 *  Date          2016/07/20
 */
class UcenterController extends AdminController {

    public $model;

    public function __construct() {
        parent::__construct();
        $this->model = new UserModel();
    }


    /**
     * 修改密码
     * @param array $param 接受的数据
     * @param array $info 用户信息
     * @return array $res 返回信息
     */
    public function modifyPsd() {
        $param = I('get.');
        if (empty($param['old_password'])) {
            $this->display();
                        exit();
        }
        $old_password = md5(md5($param['old_password']));
        $info = $this->model->field('uid,password')->find(UID);
        //匹配原始密码
        if ($old_password !== $info['password']) {
            $this->ajaxReturn(C('USER.OLD_PWD_ERROR'));
        }
        //二次新密码比对
        if ($param['new_password'] !== $param['confirm_password']) {
            $this->ajaxReturn(C('USER.PWD_DIFFERENT'));
        }
        $param = $this->model->create($param);
        if (!$param) {
            $this->ajaxReturn(C('USER.PWD_MODIFY_ERROR'));
        }
        $data['uid'] = UID;
        $data['password'] = md5(md5($param['password']));
        $result = $this->model->save($data);
        $user_info = $this->model->field('status')->find(UID);
        $user_name = $user_info['nickname'];
        if ($result > 0) {
            writeOperationLog('修改“' . $user_name . '”用户密码', 1);
            session('S_USER_INFO.UID', null);
            $this->ajaxReturn(C('USER.PWD_MODIFY_SUCCESS'));
        } else {
            writeOperationLog('修改“' . $user_name . '”用户密码', 0);
        }
    }

}
