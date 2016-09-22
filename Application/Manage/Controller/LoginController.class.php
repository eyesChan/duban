<?php

namespace Manage\Controller;

use Think\Controller;
use Manage\Controller\CommonApi\UserController;

/**
 * 用户登录类
 * 
 * @author Qiang Liu <qiang.liu17@pactera.com>
 * @version 1.0
 * @createTime 2016-07-08
 * @lastUpdateTime 2016-07-08
 */
class LoginController extends Controller {

    /**
     * 登录
     * 
     * @param string $nickname
     * @param string $password
     * @param string $verify_code
     * @return object 跳转或显示页面
     */
    public function index() {
        $param = I('post.');
        if (!empty($param)) {
            $nickname = $param['nickname'];
            $password = $param['password'];
            $verify_code = $param['verify_code'];
            $member_api = new UserController();
            $login_result = $member_api->login($nickname, $password, $verify_code);
            $this->ajaxReturn($login_result);
        } else {
            if (is_login()) {
                $this->redirect('Index/index');
            } else {
                $this->display();
            }
        }
    }

    /* 退出登录 */

    public function logout() {
        //如果退出并且没有缓存，说明已经退出，无须加日志
        $uid = session('S_USER_INFO.UID');
        if (!empty($uid)) {
            writeOperationLog('“' . session('S_USER_INFO.UNAME').'”退出登录',1);
        }
        //addLoginLog(2,"退出成功");//清除缓存之前,添加登陆日志
        session('[destroy]');
        S('DB_MENU_DATA', null); //清除缓存
        
        $this->success('退出成功！', U('index'));
    }

    /**
     * 获取验证码
     * 
     * @return array 验证码图像对象
     */

    public function verify() {
        ob_clean();
        $verify = new \Think\Verify();
        $verify->entry(1);
    }
    
   
}
