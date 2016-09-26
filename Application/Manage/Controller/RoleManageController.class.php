<?php

namespace Manage\Controller;

/**
 * 
 *
 * @author chengyayu
 */
class RoleManageController extends AdminController {

    private $mod_role_manage;

    public function __construct() {
        parent::__construct();
        $this->mod_role_manage = D('RoleManage');
    }

    public function index() {

        $params = I('param.');
        $data_for_list = $this->mod_role_manage->getDataForList($params);
        $this->assign('list', $data_for_list['auth_group']);
        $admin = implode(',', C("ADMINISTRATOR"));
        $this->assign('admin', $admin);
        $this->assign('s_number', 1); //初始序号
        $this->display('index');
    }

    public function add() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_add = $this->mod_role_manage->doAdd($params);
            $this->ajaxReturn($res_info_add);
        } else {
            $this->display('add');
        }
    }

}
