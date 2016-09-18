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
        $this->display();
    }
    
    public function edit(){
        
    }
}
