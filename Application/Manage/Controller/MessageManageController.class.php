<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;

/**
 * Description of MessageManageController
 *
 * @author chengyayu
 */
class MessageManageController extends AdminController {

    private $mod_message_manage;

    public function __construct() {
        parent::__construct();
        $this->mod_message_manage = D('MessageManage');
    }

    public function index() {

        $params = I('param.');
        $data_for_list = $this->mod_message_manage->getDataForList($params);

        $this->assign('list', $data_for_list['msg_sys']);
        $this->assign('page', $data_for_list['page_show']);
        $this->assign('remember_search', $params);
        $this->display();
    }

    public function add() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_add = $this->mod_message_manage->doAdd($params);
            $this->ajaxReturn($res_info_add);
        } else {
            $this->display('add');
        }
    }

    public function edit() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_edit = $this->mod_message_manage->doEdit($params);
            $this->ajaxReturn($res_info_edit);
        } else {
            $msg_sys_id = I('param.msg_sys_id');
            $data_for_edit = $this->mod_message_manage->getDataById($msg_sys_id);
            $this->assign('msg_sys', $data_for_edit);
            $this->display('edit');
        }
    }
    
    public function changeStatus(){
        
        $params = I('param.');
        $res_info_edit = $this->mod_message_manage->doEdit($params);
        $this->ajaxReturn($res_info_edit);
    }

}
