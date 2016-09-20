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
class WorkSheetModel  extends Model{
    
    protected $trueTableName = 'db_worksheet';

    /*
    * 工作单添加
    * 
    * @ahthor xiaohui
    * @param string $param['workname']; 工作单名称
    * @param string $param['association']; 关联会议id
    * @param string $param['start_time']; 开始时间
    * @param string $param['stop_time']; 结束时间
    * @param string $param['personliable']; 负责人
    * @param string $param['email']; 负责人邮箱
    * @return object 添加成功或失败
    */
    
    public function addWork($param){
       //验证邮箱
        if(preg_match("/^[0-9a-zA-Z]+(?:[_-][a-z0-9-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*.[a-zA-Z]+$/i",$param['email'])){
            
            $order = M('worksheet');
            $data['worksheet_name'] = $param['workname'];
            $data['worksheet_relate_meeting'] = 1;//$param['association'];
            $data['worksheet_start_date'] = $param['start_time'];
            $data['worksheet_end_date'] = $param['stop_time'];
            $data['worksheet_creat_person'] = session('S_USER_INFO.UID');
            $data['worksheet_rule_person'] = $param['personliable'];
            $data['worksheet_describe'] = $param['worksheet_describe'];
            $data['worksheet_done_persent'] = 0;
            $data['worksheet_state'] = 0;
            $data['worksheet_detele'] = 0;
            $res = $order->add($data);
            if($res){
                return C('COMMON.SUCCESS_EDIT');
            }else{
                return C('COMMON.ERROR_EDIT');
            } 
        }
        else{
            return C('COMMON.ERROR_EDIT');
        }
        
        
    }
    /*
     * 统计数量
     */
     public function getWorkOrderCount($where) {
        $count = D('worksheet')
                ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
                ->where($where)->count();
        return $count;
    }
    
    /*
     * 查询会议名称
     * @author xiaohui
     * @return array 会议名称
     */
    public function listMeeting(){
        $meeting = M('meeting')
                ->field('meeting_id,meeting_name')
                ->where('meeting_delete = 1')
                ->order('meeting_id desc')
                ->select();
        return $meeting;
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
      $order = M('worksheet');
      $list = $order
              ->join('db_meeting on db_worksheet.worksheet_relate_meeting = db_meeting.meeting_id')
              ->where($where)
              ->limit($first_rows, $list_rows)
              ->order('worksheet_id desc')
              ->select();
      return $list;
    }
    /*
     * 查询单个工作单
     * @author xiaohui
     * @param int 工作单号
     * @return array 成功返回列表
     */
    public function selectWork($param){
        $work = D('worksheet')
              ->field('worksheet_id,worksheet_name,worksheet_rule_person,worksheet_done_persent,worksheet_state')
              ->where("worksheet_id = $param")
              ->find();
        return $work;
    }
    /*
     * 修改工单状态
     * @author xiaohui
     * @param int 工作单号
     * @return array 成功返回
     */
    public function saveWork($param){
        $order = M('worksheet');
        $data['worksheet_creat_person'] = session('S_USER_INFO.UID');
        $data['worksheet_rule_person'] = $param['personliable'];
        $data['worksheet_done_persent'] = $param['worksheet_done_persent'];
        $data['worksheet_state'] = $param['worksheet_state'];
        $work_id = $param['worksheet_id'];
        $res = $order->where("worksheet_id = $work_id")->save($data);
        if($res){
            return C('COMMON.SUCCESS_EDIT');
        }else{
            return C('COMMON.ERROR_EDIT');
        } 
    }
    /*
     * 工作单废弃修改
     * @author xiaohui
     * @param int 工作单号
     * @param string 废弃原因
     * @return array 成功返回
     */
    public function abandonedWork($param){
        $order = M('worksheet');
        $data['worksheet_abandoned_reason'] = $param['abandoned_reason'];
        $data['worksheet_state'] = 3;
        $work_id = $param['worksheet_id'];
        $res = $order->where("worksheet_id = $work_id")->save($data);
        if($res){
            return C('COMMON.SUCCESS_EDIT');
        }else{
            return C('COMMON.ERROR_EDIT');
        } 
    }
}

