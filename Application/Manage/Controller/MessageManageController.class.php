<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;
/**
 * Description of MessageManageController
 *
 * @author chengyayu
 */
class MessageManageController extends AdminController{
    
    private $mod_message_manage;
    public function __construct() {
        parent::__construct();
        $this->mod_message_manage = D('MessageManage');
    }
    
    public function index(){
        
        $params = I('param.');
        $data_for_list = $this->mod_message_manage->getDataForList($params);
        
        $this->assign('list', $data_for_list['msg_sys']);
        $this->assign('page', $data_for_list['page_show']);
        $this->assign('remember_search', $params);
        $this->display();
    }
    
    public function edit(){
        
    }
}
