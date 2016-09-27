<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;

/**
 * 签约仪式台账信息：列表展示、按条件查询、添加、编辑、修改状态、删除（修改状态）
 *
 * @author chengyayu
 */
class CeremoneyAccountController extends AdminController {

    private $mod_ceremoney_account;

    public function __construct() {
        parent::__construct();
        $this->mod_ceremoney_account = D('CeremoneyAccount');
    }

    /**
     * 查询列表展示
     */
    public function index() {

        $params = I('param.');
        $data_for_list = $this->mod_ceremoney_account->getDataForList($params);

        $this->assign('list', $data_for_list['ceremoney_account']);
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
            $res_info_add = $this->mod_ceremoney_account->doAdd($params);
            if ($res_info_add['code'] == 200) {
                $this->success($res_info_add['status'], U('CeremoneyAccount/index'));
            } else {
                $this->error($res_info_add['status'], U('CeremoneyAccount/index'));
            }
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
            $res_info_edit = $this->mod_ceremoney_account->doEdit($params);
            if ($res_info_edit['code'] == 200) {
                $this->success($res_info_edit['status'], U('CeremoneyAccount/index'));
            } else {
                $this->error($res_info_edit['status'], U('CeremoneyAccount/index'));
            }
        } else {
            $ca_id = I('param.ca_id');
            $data_for_edit = $this->mod_ceremoney_account->getDataById($ca_id);
            $this->assign('ca_info', $data_for_edit);
            $this->display('edit');
        }
    }

    /**
     * 详情
     */
    public function detail() {
        
        $ca_id = I('param.ca_id');
        $data_for_edit = $this->mod_ceremoney_account->getDataById($ca_id);
        $this->assign('ca_info', $data_for_edit);
        $this->display('detail');
    }

    /**
     * 删除（修改状态为0）
     */
    public function delete() {

        $ca_id = I('ca_id');
        $res_info_delete = $this->mod_ceremoney_account->doDelete($ca_id);
        if ($res_info_delete['code'] == 200) {
            $this->success($res_info_delete['status'], U('CeremoneyAccount/index'));
        } else {
            $this->error($res_info_delete['status'], U('CeremoneyAccount/index'));
        }
    }

}
