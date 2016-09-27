<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;

use Think\Model;

/**
 * Description of WorkOrderModel
 *
 * @author xiaohui
 * @Date    2016/09/18
 */
class InternalMeetingModel  extends Model{
    
    protected $trueTableName = 'db_internalmeeting';
    
    public function addInternal($param){
    $internal = M('internalmeeting');
    $data['internal_name'] = $param['internal_name'];
    $data['internal_assigned_person'] = $param['internal_assigned_person'];
    $data['internal_assigned_date'] =$param['internal_assigned_date'];
    $data['internal_responsible_person'] = $param['internal_responsible_person'];
    $data['internal_meeting_name'] =$param['internal_meeting_name'];
    $data['internal_meeting_date'] = $param['internal_meeting_date'];
    $data['internal_meeting_place'] = $param['internal_meeting_place'];
    $data['internal_meeting_form'] =$param['internal_meeting_form'];
    $data['internal_meeting_type'] =$param['internal_meeting_type'];
    $data['internal_meeting_level'] =$param['internal_meeting_level'];
    $data['internal_meeting_dense'] =$param['internal_meeting_dense'];
    $data['internal_call_man'] =$param['internal_call_man'];
    $data['internal_host'] =$param['internal_host'];
    $data['internal_participants'] = $param['internal_participants'];
    $data['internal_work_ren'] =$param['internal_work_ren'];
    $data['internal_meeting_arrange'] = $param['internal_meeting_arrange'];
    $data['internal_meeting_start_date'] = $param['internal_meeting_start_date'];
    $data['internal_meeting_stop_date'] = $param['internal_meeting_stop_date'];
    $data['internal_meeting_responsible_person'] = $param['internal_meeting_responsible_person'];
    $data['internal_reserve_name'] =$param['internal_reserve_name'];
    $data['internal_meeting_bao_state'] =$param['internal_meeting_bao_state'];
    $data['internal_send_meeting_notice'] = $param['internal_send_meeting_notice'];
    $data['internal_notice_ready'] =$param['internal_notice_ready'];
    $data['internal_notice_company'] = $param['internal_notice_company'];
    $data['internal_material_person'] = $param['internal_material_person'];
    $data['internal_notice_start_date'] = $param['internal_notice_start_date'];
    $data['internal_notice_start_time'] = $param['internal_notice_start_time'];
    $data['internal_notice_stop_date'] = $param['internal_notice_stop_date'];
    $data['internal_notice_stop_time'] = $param['internal_notice_stop_time'];
    $data['internal_misdeed'] = $param['internal_misdeed'];
    $data['internal_misdeed_type'] =$param['internal_misdeed_type'];
    $data['internal_get_meeting_person'] = $param['internal_get_meeting_person'];
    $data['internal_print_person'] =$param['internal_print_person'];
    $data['internal_bai_person'] = $param['internal_bai_person'];
    $data['internal_bei_person'] = $param['internal_bei_person'];
    $data['internal_beiban'] = $param['internal_beiban'];
    $data['internal_hui_person'] = $param['internal_hui_person'];
    $data['internal_cehua_person'] = $param['internal_cehua_person'];
    $data['internal_cevideo_person'] = $param['internal_cevideo_person'];
    $data['internal_cemp_person'] =$param['internal_cemp_person'];
    $data['internal_cefan_person'] = $param['internal_cefan_person'];
    $data['internal_recorder_person'] = $param['internal_recorder_person'];
    $data['internal_ready_person'] =$param['internal_ready_person'];
    $data['internal_whether_problem'] =$param['internal_whether_problem'];
    $data['internal_problem_type'] =$param['internal_problem_type'] ;
    $data['internal_summary_person'] = $param['internal_summary_person'];
    $data['internal_summary_speed'] = $param['internal_summary_speed'];
    $data['internal_whether_issued'] = $param['internal_whether_issued'];
    $data['internal_meeting_speed'] =$param['internal_meeting_speed'];
    $data['internal_file_person'] =$param['internal_file_person'];
    $data['internal_proposal'] =$param['internal_proposal'];
    $data['internal_file_time'] = $param['internal_notice_stop_date'];
    $data['internal_username'] = 1;
    $internal->add($data);
    }
     /*
     * 统计数量
     */
     public function getInternalCount($where) {
        
        $count = D('internalmeeting')
                
                ->where($where)->count();
        return $count;
    }
  
    
    /**
     * 分页查询操作
     * 
     * @author xiaohui
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getList($where, $first_rows, $list_rows) {
      $order = M('internalmeeting');
      
      $list = $order
              ->where($where)
              ->limit($first_rows, $list_rows)
              ->order('internal_id desc')
              ->select();
      return $list;
    }
    
    /*
     * 查询单个内部会议
     * @author xiaohui
     * @return array 成功返回列表
     */
    
    public function getOneInternalMeeting($id){
        
        $internal = D('internalmeeting')
              ->where("internal_id = $id")
              ->find();
        return $internal;
    }
    /*
     * 导出公司execl
     */
    public function getExecl(){
        $internal = D('internalmeeting')->select();
        $count = count($internal);
        for($i=0; $i<=$count; $i++){
            unset($internal[$i]['internal_username']);
        }
        return $internal;
    }
    /*
     * 导出execl
     */
    public function groupExecl(){
        $internal = D('internalmeeting')->select();
        $count = count($internal);
        for($i=0; $i<=$count; $i++){
            unset($internal[$i]['internal_username']);
            unset($internal[$i]['internal_meeting_level']);
            unset($internal[$i]['internal_notice_start_time']);
        }
        return $internal;
    }
    
    /**
     *  普通文件上传
     * @param file_size 文件大小
     * @param file_path 文件路径
     * @param allow_file 允许格式
     * @author xiaohui
     * @return array
     * @date 16/09/20

     */
    public function normalUpload($param = array()) {
        if (empty($param)) {
            $result = C('COMMON.PARAMTER_ERROR');
            return $result;
        }
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = $param['FILE_SIZE']; // 设置附件上传大小
        $upload->exts = $param['ALLOW_FILE']; // 设置附件上传类型
        $upload->rootPath = C('FILE_ROOT_PATH'); // 设置附件上传根目录
        $upload->savePath = $param['FILE_PATH']; // 设置附件上传（子）目录
        if (file_exists($upload->rootPath)) {
            chmod($upload->rootPath, '0777');
        }
        // 上传文件 
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
            return C('COMMON.UPLOAD_ERROR');
        } else {// 上传成功 获取上传文件信息
            $result = C('COMMON.UPLOAD_SUCCESS');
            $result['info'] = $info; 
            $result['rootPath'] = $upload->rootPath;
            return $result;
        }
    }
}
