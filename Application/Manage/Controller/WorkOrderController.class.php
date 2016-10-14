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
     * 添加工作单
     * @author Hui Xiao
     * @return object 跳转或显示页面
     */
    public function add(){
        $meeting = $this->mod_worksheet->listMeeting();//获取会议
        $user = $this->mod_worksheet->getUser();//获取用户
        if(IS_POST){
            $param = I('post.');
            
            if(empty($param)){
                $this->error($result['status'], '/Manage/WorkOrder/add');
            }
            elseif(strtotime($param['start_time']) > strtotime($param['stop_time'])){
                $result['status'] = "保存失败";
                $this->error($result['status'], '/Manage/WorkOrder/add');
            }else{
                $result = $this->mod_worksheet->addWork($param);
                if($result['code'] == 200){
                    $this->success($result['status'], '/Manage/WorkOrder/index');
                }else{
                    $this->error($result['status'], '/Manage/WorkOrder/add');
                }
            }
            return true;
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
        $param['worksheet_start_date'] != '' ? $where['worksheet_start_date'] = array('eq', $param['worksheet_start_date']) : '';
        
        $where = $this->escape($where);
        $count = $this->mod_worksheet->getWorkOrderCount($where);
        
        $page = new \Think\Page($count, 10);
        $list = $this->mod_worksheet->getList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $uuid = session('S_USER_INFO.UID');
        $this->assign('uuid', $uuid);
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
                $user = $this->mod_worksheet->getUser();
                if($workorder['worksheet_creat_person'] == session('S_USER_INFO.UID')){
                    $state = 100;
                    $this->assign('state',$state);
                }
                $this->assign('workorder',$workorder);
                $this->assign('user',$user);
                //$this->assign('uid',)
                $this->display('save');
            }
        }
        if(IS_POST){
            $param = I('post.');
            if(empty($param)){
                $this->error($result['status'], '/Manage/WorkOrder/index');
            }else{
                $result = $this->mod_worksheet->saveWork($param);
                if($result['code'] == 200){
                    $this->success($result['status'], '/Manage/WorkOrder/index');
                    
                }else{
                    $this->error($result['status'], '/Manage/WorkOrder/index');
                }
            }
            return true;
        }
    }

    
    /*
     * 督办发送邮件
     * @author xiaohui
     */
    public function sendEmail(){
        
        $param = I('get.id');
        $email = $this->mod_worksheet->userPerson($param);
        $title = "工作单督办";
        $content = array_pop($email);
        $result = array('code'=>200,'status'=>'发送成功');
        foreach ($email as $key=>$val){
            sendMail($val['email'],$title,$content);
            if($result['code'] == 200){
                $this->success($result['status'], '/Manage/WorkOrder/index');
                return true;
            }else{
                $this->error($result['status'], '/Manage/WorkOrder/index');
            }       
        }
    } 
    /*
     * 查看工作单详情
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
        $work = $this->mod_worksheet->getOrderExcel($param);
        $headArr = array('工作单名称',
                        '关联会议',
                        '负责人',
                        '最后时间',
                        '工作单描述',
                        '状态',
                        '原因',
                        '进度'
        );
        getExcel($headArr, $work);
  
    }
}