<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Manage\Controller;

use Manage\Controller\AdminController;
   /**
    * 工作单管理类
    * 
    * @author Hui Xiao 
    * @version 1.0
    * @createTime 2016-09-14
    * @lastUpdateTime 2016-09-14
    */
class WorkOrderController extends AdminController {
    
    private $mod_worksheet;
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
    
        $this->mod_worksheet = D('WorkSheet');
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
    
    /*
     * 添加
     */
    public function add(){
        $meeting = $this->mod_worksheet->listMeeting();
        $this->assign('meeting',$meeting);
        $this->display();
    }

    /*
     * 工作单列表
     * @author Hui Xiao
     * @return object 跳转或显示页面
     */
    
    
    public function index(){
        $param = I();
       
        //处理查询条件：操作人姓名、IP地址、模块名称、操作内容、开始时间 结束时间 
        $param['worksheet_name'] != '' ? $where['worksheet_name'] = array('like', '%' . $param['worksheet_name'] . '%') : '';
        $param['worksheet_creat_person'] != '' ? $where['worksheet_creat_person'] = array('like', '%' . $param['worksheet_creat_person'] . '%') : '';
        $param['worksheet_rule_person'] != '' ? $where['worksheet_rule_person'] = array('like', '%' . $param['worksheet_rule_person'] . '%') : '';
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
     
        $count = $this->mod_worksheet->getWorkOrderCount($where);
        
        $page = new \Think\Page($count, 10);
        $list = $this->mod_worksheet->getList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
        $this->display('index');
    }
    
    /*
     * 工作单添加
     */
    public function save(){
        $param = I('post.');
        if(empty($param)){
            echo json_encode(C('COMMON.ERROR_EDIT'));
        }else{
            $result = $this->mod_worksheet->addWork($param);
            if($result['code'] == 200){
                $this->success($result['status'], '/Manage/WorkOrder/listWork');
            }else{
                $this->success($result['status'], '/Manage/WorkOrder/addWork');
            }
        }
    }
       
    /*
     * 工作单状态调整
     * @author Hui Xiao
     * @param string $condition
     * @return object 跳转或显示页面
     */
    public function savea(){
        if(IS_GET){
            $param = I('get.id');
            if(!empty($param)){
                $workorder = $this->mod_worksheet->selectWork($param);
                $this->assign('workorder',$workorder);
                $this->display();
            }else{
                echo json_encode(C('COMMON.ERROR_EDIT'));
            }
        }
        if(IS_POST){
            $param = I('post.');
            if(empty($param)){
                echo json_encode(C('COMMON.ERROR_EDIT'));
            }else{
                $result = $this->mod_worksheet->saveWork($param);
                if($result['code'] == 200){
                    $this->success($result['status'], '/Manage/WorkOrder/listWork');
                }else{
                    $this->success($result['status'], '/Manage/WorkOrder/addWork');
                }
            }
        }
    }
   
    /*
     * 工作单废弃
     * @author Hui Xiao
     * @param string $condition
     * @return object 跳转或显示页面
     */
    public function voidWork(){
        if(IS_GET){
            $param = I('get.id');
            if(!empty($param)){
                $workorder = $this->mod_worksheet->selectWork($param);
                $this->assign('workorder',$workorder);
                $this->display('void');
            }else{
                $result['status'] = C('COMMON.ERROR_EDIT');
                $this->success($result['status']);
            }
        }
        if(IS_POST){
            $param = I('post.');
            if(empty($param['worksheet_abandoned_reason'])){
                $result['status'] = C('COMMON.ERROR_EDIT');
                $this->success($result['status']);
            }else{
                $result = $this->mod_worksheet->abandonedWork($param);
                if($result['code'] == 200){
                        $this->success($result['status'], '/Manage/WorkOrder/listWork');
                   }else{
                        $this->error($result['status'], '/Manage/WorkOrder/addWork');
                   }
            }
        }
    }
    /*
     * 督办发送邮件
     * @author xiaohui
     */
    public function sendEmail(){
        $to = "wyxiaohui@163.com";
        $title = "测试";
        $content = "success";
        if(sendMail($to,$title,$content)){
            $this->success('发送成功！');
        }
        else{
            $this->error('发送失败');
        }
    }
    /*
     * 查看工作单
     * @author xiaohui
     * @
     */
    
}