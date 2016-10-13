<?php

namespace Manage\Controller;

/**
 * 模块管理
 *
 * @author lizuofeng
 * @createTime 2016-07-05
 * @lastUpdateTime 2016-07-08
 */
class ModuleController extends AdminController {

    /**
     * 模块首页
     * @return array $module 节点数组
     */
    public function index() {
        $module_info = D('auth_rule');
        $where['status'] = 1;
        $where['id'] = array('neq', 1);

        //获取节点
        $module_arr = $module_info->where($where)->order('sort asc')->select();
        $module = getTree($module_arr);

        $this->assign('module_list', $module);
        $this->display();
    }

    /**
     * 增加模块
     * @param string $menu_name 模块名称
     * @return int  返回状态值
     */
    public function add() {
        $module_info = D('auth_rule');
        $where['status'] = 1;
        $where['id'] = array('neq', 1);

        //获取节点
        $module_arr = $module_info->where($where)->order('sort asc')->select();
        $module = getTree($module_arr);

        $this->assign('module_list', $module);
        if (IS_POST) {
            //参数过滤
            $param = I('post.', '', htmlspecialchars);
            $menu_name = $param['menu_name'];
            $auth_rule = D('Authrule');
            $result = $auth_rule->addDo($param);
            switch ($result) {
                case 12:
                    $this->success(C('MOUDEL.MOUDEL_EXIST'));
                    break;
                case 1:
                    writeOperationLog('添加“' . $menu_name . '”模块', 1);
                    $this->success(C('COMMON.ADD_SUCCESS'));
                    break;
                default:
                    writeOperationLog('添加“' . $menu_name . '”模块', 0);
                    $this->error(C('COMMON.ADD_ERROR'));
            }
            return true;
        }
        $this->display();
    }

    /**
     * 子模块
     * @param int $status 状态值
     * @param int $id 模块id
     * @return string 模块数组
     */
    public function child() {
        $module_info = D('auth_rule');
        $where['status'] = 1;
        $where['id'] = array('neq', 1);

        //获取节点
        $module_arr = $module_info->where($where)->order('sort asc')->select();
        $auth_rule = getTree($module_arr); //获取无限级菜单数组

        $this->assign('auth_list', $auth_rule);
        $res = $module_info->getChild(I('get.pid'));
        $this->assign('module_list', $res);
        $this->display();
    }

    /**
     * 模块删除
     * @param int $status 状态值
     * @param int $id id值
     * @return string 状态值
     * 
     */
    public function delete() {
        $bool = $this->delCheck(I('id'));
        if ($bool == FALSE) {
            $this->error(C('MOUDEL.CHILD_EXIST'));
        }
        $auth_rule = D('AuthRule');
        $data['status'] = 0; //删除节点 状态值改为0
        $menu_id = I('id');
        $menu_name = $auth_rule->where("id = $menu_id")->getField('title');
        if ($auth_rule->where('id = ' . I('id'))->delete()) {
            writeOperationLog('删除“' . $menu_name . '”模块', 1);
            if (!empty(I('pid'))) {
                $this->success(C('COMMON.DEL_SUCCESS'), U('Module/child/', array('pid'=>I('pid'))));
            } else {
                $this->success(C('COMMON.DEL_SUCCESS'), U('Module/index'));
            }
        } else {
            writeOperationLog('删除“' . $menu_name . '”模块', 0);
            $this->error(C('COMMON.DEL_ERROR'));
        }
    }

    /**
     * 模块删除
     * @param int $status 状态值
     * @param int $id id值
     * @return bool 成功失败
     * 
     */
    public function delCheck($id) {
        $auth_rule = D('AuthRule');
        $where['pid'] = $id;
        $res = $auth_rule->where($where)->find();
        if (empty($res)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 模块修改
     * @return int 成功返回状态值
     */
    public function save() {
        $auth_rule = D('AuthRule');
        if (IS_POST) {
            //参数过滤
            $param = I('post.', '', htmlspecialchars);
            $menu_name = $param['menu_name'];
            $auth_rule = D('Authrule');
            $result = $auth_rule->editDo($param);
            $back_url = $this->getBackUrl($param['id']);
            switch ($result) {
                case 11:
                    $this->error(C('MOUDEL.MENU_URL_EXIST'), $back_url);
                    break;
                case 12:
                    $this->success(C('MOUDEL.MENU_NUM_EXIST'), $back_url);
                    break;
                case 1:
                    writeOperationLog('修改“' . $menu_name . '”模块信息', 1);
                    $this->success(C('COMMON.EDIT_SUCCESS'), $back_url);
                    break;
                default:
                    writeOperationLog('修改“' . $menu_name . '”模块信息', 0);
                    $this->error(C('COMMON.EDIT_ERROR'), $back_url);
            }
            exit;
        }
        //条件
        $where['status'] = 1;
        $where['id'] = array('neq', 1);

        //获取节点
        $module_arr = $auth_rule->where($where)->order('sort asc')->select();
        $module = getTree($module_arr); //获取无限级菜单数组
        $this->assign('module_list', $module);

        $result = $auth_rule->where('id = ' . I('get.id'))->find();
        $this->assign('auth', $result);
        $this->display();
    }

    /**
     * 通过模块ID获取父ID
     * 
     * @param int $id 模块ID
     * @return string url
     */
    public function getBackUrl($id) {
        $rule_result = M('auth_rule')->where(array('id' => $id))->field('pid')->find();
        if ($rule_result['pid'] == 0) {
            return 'index.html';
        }
        return 'child/pid/' . $rule_result['pid'] . 'html';
    }

}
