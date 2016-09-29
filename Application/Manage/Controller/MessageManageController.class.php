<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;

/**
 * 系统消息：列表展示、按条件查询、添加、编辑、修改状态、删除（修改状态）
 *
 * @author chengyayu
 */
class MessageManageController extends AdminController {

    private $mod_message_manage;

    public function __construct() {
        parent::__construct();
        $this->mod_message_manage = D('MessageManage');
    }

    /**
     * 查询列表展示
     * 
     * @param string $msg_sys_status
     * @param string $msg_sys_title
     * @param string $p
     * @return object 跳转或显示页面
     */
    public function index() {

        $params = I('param.');
        $data_for_search = $this->mod_message_manage->getDataForSearch();
        $data_for_list = $this->mod_message_manage->getDataForList($params);
        $this->assign('msg_sys_type', $data_for_search['msg_sys_type']);
        $this->assign('list', $data_for_list['msg_sys']);
        $this->assign('page', $data_for_list['page_show']);
        $this->assign('remember_search', $params);
        $this->assign('s_number', 1); //初始序号
        $this->display();
    }

    /**
     * 添加
     */
    public function add() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_add = $this->mod_message_manage->doAdd($params);
            $this->ajaxReturn($res_info_add);
        } else {
            $this->display('add');
        }
    }

    /**
     * 编辑
     */
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

    /**
     * 修改状态
     * 
     * @param string $msg_sys_id
     * @param string $msg_sys_status 修改后状态值
     * @return object json对象
     */
    public function changeStatus() {

        $params = I('param.');
        $res_info_edit = $this->mod_message_manage->doEdit($params);
        $this->ajaxReturn($res_info_edit);
    }

    /**
     * 删除（修改状态为４）
     */
    public function delete() {

        $msg_sys_id = I('msg_sys_id');
        $res_info_delete = $this->mod_message_manage->doDelete($msg_sys_id);
        $this->ajaxReturn($res_info_delete);
    }

    /**
     * 系统消息前台显示列表
     */
    public function showList() {
        
        $data = $this->mod_message_manage->getDataForShowList();
        $this->assign('list', $data);
        $this->display('showlist');
    }

    /**
     * 系统消息前台显示详情
     */
    public function showDetail() {
        
        $msg_sys_id = I('param.msg_sys_id');
        $data = $this->mod_message_manage->getDataById($msg_sys_id);
        $this->assign('info', $data);
        $this->display('showdetail');
    }

}
