<?php

namespace Manage\Controller;
use Manage\Controller\AdminController;

/**
 * 系统参数：按条件查询、列表展示、添加、编辑、修改状态、删除（修改状态）
 *
 * @author chengyayu
 */
class ConfigManageController extends AdminController{
    
    private $mod_config_system;

    public function __construct() {
        parent::__construct();
        $this->mod_config_system = D('ConfigManage');
    }
    
    /**
     * 查询列表展示
     * 
     * @param string $config_key
     * @param string $config_descripion
     * @param string $p
     * @return object 跳转或显示页面
     */
    public function index() {
            
        $params = I('param.');
        
        $data_for_search = $this->mod_config_system->getDataForSearch($params);
        $data_for_list = $this->mod_config_system->getDataForList($params);

        $this->assign('configs', $data_for_search['config']);
        $this->assign('list', $data_for_list['config_items']);
        $this->assign('page', $data_for_list['page_show']);
        $this->assign('remember_search', $params);
        $this->assign('s_number',1);//初始序号
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_add = $this->mod_config_system->doAdd($params);
            $this->ajaxReturn($res_info_add);
        } else {
            $data_for_add = $this->mod_config_system->getConfigTypes();
            $this->assign('config_types', $data_for_add);
            $this->display('add');
        }
    }

    /**
     * 编辑
     */
    public function edit() {

        if (IS_POST) {
            $params = I('param.');
            $res_info_edit = $this->mod_config_system->doEdit($params);
            $this->ajaxReturn($res_info_edit);
        } else {
            $config_id = I('param.config_id');
            $config_types = $this->mod_config_system->getConfigTypes();
            $data_for_edit = $this->mod_config_system->getDataById($config_id);
            $this->assign('config_types', $config_types);
            $this->assign('config_system', $data_for_edit);
            $this->display('edit');
        }
    }
    
    /**
     * 修改状态
     */
    public function changeStatus(){
        
        $params = I('param.');
        $res_info_edit = $this->mod_config_system->changeStatus($params);
        $this->ajaxReturn($res_info_edit);
    }
    
    /**
     * 删除（修改状态为3）
     */
    public function delete(){
        
        $config_id = I('param.config_id');
        $res_info_delete = $this->mod_config_system->doDelete($config_id);
        $this->ajaxReturn($res_info_delete);
    }
}
