<?php

namespace Manage\Controller;
use Manage\Controller\AdminController;

/**
 * 会议类型：按条件查询、列表展示、添加、编辑、修改状态、删除（修改状态）
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
     * @param string 
     * @param string 
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
        $this->display();
    }
}
