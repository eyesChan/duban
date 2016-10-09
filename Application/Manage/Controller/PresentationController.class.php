<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

/**
 *  Description   文稿台账管理
 *  @author       huanggang <gang.huang2@pactera.com>
 *  Date          2016/09/20
 */
class PresentationController extends AdminController {

    private $presentation;
    public function __construct() {
        parent::__construct();

        $this->presentation = D('Presentation');
    }

    /**
     * 对数组进行转义
     * @param array 需要转义的数组
     * @return array 返回转义后的数组
     */
    public function escape($param) {
        foreach ($param as $k => $val) {
            $param[$k] = str_replace("_", "\_", $val);
        }

        return $param;
    }
    /**
     * 显示文稿台账列表
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */
    public function index() {   
        $param = I();
        //处理查询条件：文字台账、交办人、交办时间
        $param['db_pre_name'] != '' ? $where['db_pre_name'] = array('like', '%' . $param['db_pre_name'] . '%') : '';
        $param['db_assign_name'] != '' ? $where['db_assign_name'] = array('like', '%' . $param['db_assign_name'] . '%') : '';
        if (!empty($param['db_assign_date'])) {
            $where['db_assign_date'] = array('EQ', $param['db_assign_date']);
        }
        $where['pre_status'] = array('EQ', '0');
        $where = $this->escape($where);
        $count = $this->presentation->getPresentCount($where);
        $page = new \Think\Page($count, 10);
        $list = $this->presentation->getPresentList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
       
        $this->display();
    }
    
     /**
     * 文稿台账创建
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */
    public function addPresent() {
        $data = I();
        if(!empty($data)){
            $result = $this->presentation->addPresent($data);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('Presentation/index'));
            }else{
                $this->error($result['status'],U('Presentation/addPresent'));
            }
        return true;
        }
         //文稿类型 
        $pre_type = getConfigInfo('doc_pre_type');
        $this->assign('pre_type', $pre_type);
        //发布方式
        $dis_mode = getConfigInfo('doc_dis_mode');
        $this->assign('dis_mode', $dis_mode);
         //文稿形式
        $pre_form = getConfigInfo('doc_pre_form');
        $this->assign('pre_form', $pre_form);
         //工作状态
        $work_status = getConfigInfo('doc_work_status');
        $this->assign('work_status', $work_status);
         //审批方式
        $exa_mode = getConfigInfo('doc_exa_mode');
        $this->assign('exa_mode', $exa_mode);
         //用户信息
        $user_name = $this->presentation->getUser();
        $this->assign('user_name', $user_name);
        $this->display();
    }
    
    /*
     * 文稿台账详情
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */
    public function detailsPresent(){
        $pre_id = I('db_pre_id');
        $result = $this->presentation->detailsPresent($pre_id);
        $this->assign('list', $result);
        $this->display();
    }
    
    /*
     * 
     * 文稿台账编辑
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */
    public function savePresent(){
        if(IS_POST){
            $data=I();
            if(!empty($data)){
                $result = $this->presentation->savePresent($data,$data['db_pre_id']);
                if ($result['code'] == 200) {
                    $this->success($result['status'], U('Presentation/index'));
                }else{
                    $this->error($result['status'],U('Presentation/savePresent',array('db_pre_id'=>$data['db_pre_id'])));
                }
            }
        return true;
        }
        $pre_id = I('db_pre_id');
        $result = $this->presentation->detailsPresent($pre_id);
        $this->assign('list', $result);
             //文稿类型 
        $pre_type = getConfigInfo('doc_pre_type');
        $this->assign('pre_type', $pre_type);
        //发布方式
        $dis_mode = getConfigInfo('doc_dis_mode');
        $this->assign('dis_mode', $dis_mode);
         //文稿形式
        $pre_form = getConfigInfo('doc_pre_form');
        $this->assign('pre_form', $pre_form);
         //工作状态
        $work_status = getConfigInfo('doc_work_status');
        $this->assign('work_status', $work_status);
         //审批方式
        $exa_mode = getConfigInfo('doc_exa_mode');
        $this->assign('exa_mode', $exa_mode);
         //用户信息
        $user_name = $this->presentation->getUser();
        $this->assign('user_name', $user_name);
        $this->display();
        
    }
    
    /*
     * 文稿台账删除
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */
    public function delPresent(){
        $db_pre_id = I('db_pre_id');
        $result = $this->presentation->delPresent($db_pre_id);
        if ($result['code'] == 200) {
            $this->success($result['status'], U('Presentation/index'));
        }else{
            $this->error($result['status'], U('Presentation/index'));
        }
    
    }
     /**
     * 集团模块文档导出
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     *  
     */
    public function exportPresent(){
        $data=I();
        $work = $this->presentation->getExecl($data); 
        $headArr = array('序号', '文稿规范名称', '工作来源',  '交办日期',  '完成日期','文稿类型	', '文稿形式', 
                        '优先级别	', '工作难度', '责任人','工作状态','拟稿人','拟稿时长','拟稿字数',  
                        '核稿人', '核稿时长', '修改字数', '核稿评价', '核稿人','核稿时长','修改字数','核稿评价',
                        '核稿人','核稿时长	','修改字数','核稿评价','中心评价','超时次数	','硬伤次数','呈报日期', 
                        '审批方式	', '审批进展', '发文方式', '发文日期', '存档情况', '存档时间', '存档地址', 
                );
        getExcel($headArr, $work);
                            
      }
      
      /**
     * 公司模块文档导出
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     *  
     */
    public function exportPresents(){
        $data=I();
        $work = $this->presentation->getExecls($data); 
        $headArr = array('序号', '文稿规范名称','工作来源','交办人','部门','职务','交办日期','交办时间',
            '完成日期','完成时间','文稿类型','文稿形式','优先级别','工作难度','责任人','工作状态',
            '拟稿人','完成日期','完成时间','拟稿时长','拟稿字数','核稿人','完成日期','完成时间','核稿时长',
            '核稿字数','修改字数','核稿评价','核稿人','完成日期','完成时间','核稿时长','核稿字数','修改字数',
            '核稿评价','核稿人','完成日期','完成时间','核稿时长','核稿字数','修改字数','核稿评价','中心评价',
            '超时次数','硬伤次数','呈报日期','呈报时间','审批方式','审批进展','发文方式','发布人','发文日期','发文时间',
            '存档人','存档情况','存档时间','存档地址','印章全称','用印份数','备注'
                );
        getExcel($headArr, $work);
                            
      }
     /*
      * 导入数据
      * @author huang gang
      * @date 2016/09/27
      * @return 跳转页面 Description
      */
    public function importPresent(){
        if (!empty($_FILES['filename'])) {
            $param = $_FILES['filename'];
            $upload_obj = new MeetingUplod();
            $files = $upload_obj->normalUpload($param);
            $fileName = $files['rootPath'] . $files['info']['filename']['savepath'] . $files['info']['filename']['savename'];
            $resute = importExcel($fileName,'BH');
            if (!empty($resute) && $resute['code'] != 100) {
                $result = $this->presentation->addsPresent($resute);
            } else {
                writeOperationLog('导入“' . 'excel表格模板错误' . '”', 0);
                $this->error($resute['msg'], U('Presentation/importPresent'));
            } 
            if($result['code'] == 200) {
                $this->success($result['status'], U('Presentation/index'));
            }else{
                $this->error($result['status'], U('Presentation/importPresent'));
            }
            return true;
        }
        $this->display('importPresent');
    }
}         