<?php

namespace Manage\Controller;

use Think\Controller;
use Manage\Model\UserModel as User;

class AdminController extends Controller {

    public function _initialize() {
        header("Content-type: text/html; charset=utf-8");
        // 获取当前用户ID
        define('UID', session('S_USER_INFO.UID'));
        if (!UID) {// 还没登录 跳转到登录页面
            $this->redirect('Login/index');
        }
        $url = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $count = D('auth_rule')->where(array('name' => $url))->count();

        if (in_array(UID, C("ADMINISTRATOR")) || in_array($url, C("NO__AUTH")) || $count == 0) {
            return true;
        } else {
            $AUTH = new \Think\Auth();
            //类库位置应该位于ThinkPHP\Library\Think\
            if (!$AUTH->check($url, session('S_USER_INFO.UID'))) {
                $user_model = new User();
                $user_info = $user_model->getUser(UID);
                if ($user_info['status'] != 1) {
                    session('[destroy]');
                    S('DB_MENU_DATA', null); //清除缓存
                    $this->redirect('login/index');
                }
                if (IS_AJAX) {
                    $this->ajaxReturn(C('COMMON.AUTH_ERROR'));
                } else {
                    $this->error('没有权限');
                }
            }
        }
        $user_info = session('S_USER_INFO');

        //面包屑
        $crumbs_str = $this->getCrumbs();
        //左侧菜单
        $admin = new \Manage\Model\AdminModel();
        $main = $admin->index();
        $menuInfo = $this->checkAccess();
        foreach ($menuInfo as $v) {
            $selectmenu[] = $v['id'];
        }
        $this->assign('crumbs_str', $crumbs_str);
        $this->assign('selectmenu', $selectmenu);
        $this->assign('admin_info', $user_info);
        $this->assign('main_menu', $main);
    }

    public function index() {
        $this->display();
    }

    /**
     * 查询当前页面的所在模块id信息
     * 
     * @author zhangjiawang 
     * @return array 返回当前页面的模块id信息
     */
    private function checkAccess() {
        $url = ltrim(__ACTION__, '/');
        $menu = M('auth_rule')->where("name = '$url'")->find();
        $id = $menu['id'];
        $data = $this->getMenu($id);
        return $data;
    }

    /**
     * 查询当前页面的面包屑
     * 
     * @author zhangjiawang 
     * @return string 返回当前页面的面包屑
     */
    private function getCrumbs() {
        $url = ltrim(__ACTION__, '/');
        $menu = M('auth_rule')->where("name = '$url'")->find();
        $id = $menu['id'];
        $crumbs = $this->getMenu($id);
        if (!$crumbs) {
            return null;
        }
        krsort($crumbs);
        foreach ($crumbs as $v) {
            if ($v['level'] == 2) {
                $str = '<a href="/' . $v['name'] . '.html">';
                $crumbs_str .= $str . $v['title'] . '</a>' . ' &gt; ';
            } else {
                $crumbs_str .= $v['title'] . ' &gt; ';
            }
        }

        return rtrim($crumbs_str, ' &gt; ');
    }

    /**
     * 根据模块id，递归查询当前模块及上级模块信息
     * 
     * @author zhangjiawang
     * @param int  $id  模块id
     * @param array $newInfo 递归赋值传参
     * @return array
     */
    public function getMenu($id, $newInfo = array()) {
        if (empty($id)) {
            return FALSE;
        }
        $info = M('auth_rule')->where("id = $id")->find();
        $data['title'] = $info['title'];
        $data['level'] = $info['level'];
        $data['id'] = $info['id'];
        $data['name'] = $info['name'];
        $newInfo[] = $data;
        if ($info['pid'] != 0) {
            return $this->getMenu($info['pid'], $newInfo);
        } else {
            return $newInfo;
        }
    }

}
