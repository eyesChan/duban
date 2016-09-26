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
     * 工作单添加
     */
    public function add(){
        $meeting = $this->mod_worksheet->listMeeting();
        $user = $this->mod_worksheet->getUser();
        if(IS_POST){
            $param = I('post.');
            if(empty($param)){
                $this->success($result['status'], '/Manage/WorkOrder/add');
            }else{
                $result = $this->mod_worksheet->addWork($param);
                if($result['code'] == 200){
                    $this->success($result['status'], '/Manage/WorkOrder/index');
                }else{
                    $this->success($result['status'], '/Manage/WorkOrder/add');
                }
            }
        }
        $this->assign('meeting',$meeting);
        $this->assign('user',$user);
        $this->display();
    }
  
    /*
     * 工作单列表
     * @author Hui Xiao
     * @return object 跳转或显示页面
     */
    
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
    }
    
    /*
     * 工作单状态调整
     * @author Hui Xiao
     * @param string $condition
     * @return object 跳转或显示页面
     */
    public function save(){
        if(IS_GET){
            $param = I('get.id');
            if(!empty($param)){
                $workorder = $this->mod_worksheet->selectWork($param);
                $this->assign('workorder',$workorder);
                $this->display('save');
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
                    $this->success($result['status'], '/Manage/WorkOrder/index');
                }else{
                    $this->success($result['status'], '/Manage/WorkOrder/index');
                }
            }
        }
    }
   
    /*
     * 工作单废弃
     * @author Hui Xiao
     * @param string $condition
     * @return object 跳转或显示页面

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
*/
    
    /*
     * 督办发送邮件
     * @author xiaohui
     */
    public function sendEmail(){
        
        $param = I('get.id');
        $email = $this->mod_worksheet->userPerson($param);
        $title = "工作单督办";
        $content = array_pop($email);
        foreach ($email as $key=>$val){
            sendMail($val['email'],$title,$content);
        }
    } 
    /*
     * 查看工作单
     * @author xiaohui
     * @param int id
     */
    public function details(){
        $param = I('get.id');
        $work = $this->mod_worksheet->selectwork($param);
        $this->assign('item',$work);
        $this->display();
    }
    
    /*
     * 工作单导出execl
     */
    public function exportExecl($param){
        
       
        $work = $this->mod_worksheet->getExecl($param);
        $headArr = array('工作单名称',
                        '关联会议名称',
                        '工作单创建人',
                        '工作单负责人',
                        '开始时间',
                        '结束时间',
                        '工作单状态',
                        '挂起/废弃原因',
                        '工作单描述'
            );
        getExcel($headArr, $work);

        
    }
}