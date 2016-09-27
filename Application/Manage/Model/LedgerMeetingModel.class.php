<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;

use Think\Model;

/**
 * Description of LedgerMeetingModel
 *
 * @author huanggang
 * @Date    2016/09/26
 */
class LedgerMeetingModel  extends Model{
    
    protected $trueTableName = 'db_led_meeting';
 /*
  * 会谈会见创建
  * 
  * @ahthor huanggang
  * @return object 添加成功或失败
  */
     public function addLedger($param){
            $led_meeting = M('led_meeting');
            $res = $led_meeting->add($param);
            if($res){
                return C('COMMON.SUCCESS_EDIT');
            }else{
                return C('COMMON.ERROR_EDIT');
            } 
    }
    
     /*
     * 统计数量
     */
    public function getLedgerCount($where) {
        $count = M('led_meeting')
                ->where($where)
                ->count();
        return $count;
    }
    /**
     * 分页查询操作
     * 
     * @author huanggang
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getLedgerList($where, $first_rows, $list_rows) {
      $led_meeting = M('led_meeting');
      $list = $led_meeting
              ->field('led_meeting_id,led_meeting_name,led_meeting_host,led_meeting_date,led_meeting_place')
              ->where($where) 
              ->limit($first_rows, $list_rows)
              ->select();
      return $list;
    }
    
    
    /*
     * 
     * 页面详情及编辑查询
     */
    public function detailsLedger($led_meeting_id){
        $led_meeting = M('led_meeting');
        $list = $led_meeting
                ->where("led_meeting_id= $led_meeting_id") 
                ->find();
      return $list; 
    }
    
    /*
     * 编辑操作
     * 
     */
    public function saveLedger($data,$led_meeting_id){
        $led_meeting = M('led_meeting');
        $res = $led_meeting->where("led_meeting_id =".$led_meeting_id)->save($data);
       if($res){
            return C('COMMON.SUCCESS_EDIT');
        }else{
            return C('COMMON.ERROR_EDIT');
        }      
    }
    
    /*
     * 
     * 删除操作
     */
    public function delLedger($led_meeting_id){
        $led_meeting = M('led_meeting');
        $res = $led_meeting->where("led_meeting_id =".$led_meeting_id)->setField('led_status','1');
       if($res){
            return C('COMMON.SUCCESS_DEL');
        }else{
            return C('COMMON.ERROR_DEL');
        }      
    }
}
