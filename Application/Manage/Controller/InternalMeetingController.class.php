<?php

namespace Manage\Controller;

use Manage\Controller\AdminController;
 
class InternalMeetingController extends AdminController {
    
    private $mod_internalmeeting;
    /*
     * 添加工作单
     * @author Hui Xiao
     * @param string $wordname 工作单名称
     * @param string $password
     * @param string $verify_code
     * @return object 跳转或显示页面
     */
    public function __construct() {
        parent::__construct();
    
        $this->mod_worksheet = D('InternalMeeting');
    }
         /**
     * 对数组进行转义
     * 
     * @param array 需要转义的数组
     * @return array 返回转义后的数组
     */
    public function escape($param) {
        foreach ($param as $k => $val) {
            $param[$k] = str_replace("_", "\_", $val);
        }
        
        return $param;
    }
    public function index(){
        $param = I();
        if($param['hiddenform'] == '1'){
            $this->exportExecl($param);
        }else{
        //处理查询条件：操作人姓名、IP地址、模块名称、操作内容、开始时间 结束时间 
        $param['worksheet_name'] != '' ? $where['worksheet_name'] = array('like', '%' . $param['worksheet_name'] . '%') : '';
        $param['meeting_name'] != '' ? $where['meeting_name'] = array('like', '%' . $param['meeting_name'] . '%') : '';
        $param['name'] != '' ? $where['name'] = array('like', '%' . $param['name'] . '%') : '';
        $param['worksheet_start_date'] != '' ? $where['worksheet_start_date'] = array('like', '%' . $param['worksheet_start_date'] . '%') : '';
        
        if (!empty($param['begin_time']) && empty($param['end_time'])) {
            $where['time'] = array('EGT', $param['begin_time'] . ' 00:00:00');
        }
        if (empty($param['begin_time']) && !empty($param['end_time'])) {
            $where['time'] = array('ELT', $param['end_time'] . ' 23:59:59');
        }
        if (!empty($param['begin_time']) && !empty($param['end_time'])) {
            $where['time'] = array('BETWEEN', array($param['begin_time'] . ' 00:00:00', $param['end_time'] . ' 23:59:59'));
        }
         
        
        $where = $this->escape($where);
     
        //$count = $this->mod_worksheet->getWorkOrderCount($where);
        
        $page = new \Think\Page($count, 10);
        //$list = $this->mod_worksheet->getList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
        $this->display();
        }
    }
    public function add(){
        if(IS_POST){
            p($_POST);
            die;
            $param = I('post.');
            $this->mod_worksheet->addInternal($param);
        }
        $this->display();  
    }
}

