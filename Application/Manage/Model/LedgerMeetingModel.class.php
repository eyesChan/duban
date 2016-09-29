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
  * @Date    2016/09/26
  * @ahthor huanggang
  * @return object 添加成功或失败
  */
     public function addLedger($param){
        $led_meeting = M('led_meeting');
        if(in_array('',$param)){
            writeOperationLog('添加的数据为空', 0);
            return C('COMMON.ERROR_EDIT');
        }
        $res = $led_meeting->add($param);
        if($res){
            writeOperationLog('添加“' . $param['led_meeting_name'] . '”会谈会见台账', 1);
            return C('COMMON.SUCCESS_EDIT');
        }else{
            writeOperationLog('添加“' . $param['led_meeting_name'] . '”会谈会见台账', 0);
            return C('COMMON.ERROR_EDIT');
        } 
    }
    
     /*
     * 统计数量
     * @ahthor huanggang
     * @Date    2016/09/26
     * @return object 数据数量
     */
    public function getLedgerCount($where){
        $count = M('led_meeting')
                ->where($where)
                ->count();
        return $count;
    }
    /**
     * 分页查询操作
     * @Date    2016/09/26
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
              ->order('led_meeting_id desc')
              ->select();
        return $list;
    }
    
    
    /*
     * 页面详情及编辑查询
     * @Date    2016/09/26
     * @author  huanggang
     * @param $led_meeting_id 撤回条件
     * @return 返回处理数据
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
     * @Date    2016/09/26
     * @author huanggang
     * @param array $data 修改后的数据
     * @param $led_meeting_id 修改条件
     * @return object 修改成功或失败 
     */
    public function saveLedger($data,$led_meeting_id){
        $led_meeting = M('led_meeting');
        $res = $led_meeting->where("led_meeting_id =".$led_meeting_id)->save($data);
        if($res){
            writeOperationLog('修改“' . $data['led_meeting_name'] . '”会谈会见台账', 1);
            return C('COMMON.SUCCESS_EDIT');
        }else{
            writeOperationLog('修改“' . $data['led_meeting_name'] . '”会谈会见台账', 0);
            return C('COMMON.ERROR_EDIT');
        }      
    }
    
    /*
     * 
     * 删除操作
     * @Date    2016/09/26
     * @author  huanggang
     * @param $led_meeting_id 删除条件
     * @return object 删除成功或失败
     */
    public function delLedger($led_meeting_id){
        $led_meeting = M('led_meeting');
        $res = $led_meeting->where("led_meeting_id =".$led_meeting_id)->setField('led_status','1');
        $led_meeting_name=$led_meeting->where("led_meeting_id =".$led_meeting_id)->getField('led_meeting_name');
        if($res){
            writeOperationLog('删除“' . $led_meeting_name. '”会谈会见台账', 1);
            return C('COMMON.SUCCESS_DEL');
        }else{
            writeOperationLog('删除“' . $led_meeting_name . '”会谈会见台账', 0);
            return C('COMMON.ERROR_DEL');
        }      
    }
    
      /*
     * 导出execl 查询
     * @Date    2016/09/27
     * @author huanggang
     * @author array data 组合要到导出的数据
     */
    public function getExecl($param){
         //处理查询条件：会谈名称、主持人、日期
        $param['led_meeting_name'] != '' ? $where['led_meeting_name'] = array('like', '%' . $param['led_meeting_name'] . '%') : '';
        $param['led_meeting_host'] != '' ? $where['led_meeting_host'] = array('like', '%' . $param['led_meeting_host'] . '%') : '';
        if (!empty($param['led_meeting_date'])) {
            $where['led_meeting_date'] = array('EQ', $param['led_meeting_date']);
        }
        $where['led_status'] = array('EQ', '0');
        $led_meeting = M('led_meeting');
        $data = $led_meeting
              ->where($where)
              ->order('led_meeting_id desc')
              ->select();
        //去除不需要的键值
        foreach($data as $k => $v){
            unset($data[$k]['led_meeting_id']);
            unset($data[$k]['led_status']);
        }
        return $data;
    }
   
  /*
  * 会谈会见批量创建
  * @Date    2016/09/27
  * @ahthor huanggang
  * @return object 添加成功或失败
  */
     public function addsLedger($param){
        $led_meeting = M('led_meeting');
        $data=array();
        $res=array('led_meeting_date','led_meeting_name','led_meeting_place','led_meeting_guest',
                    'led_meeting_leader','led_meeting_host','led_meeting_dense','led_meeting_cloth',
                    'led_meeting_person','led_meeting_length','led_argenda_name','led_argenda_status',
                    'led_notice','led_notice_ready','led_notice_service','led_men_ready','led_men_name',
                    'led_men_visiter','led_mater_com','led_mater_pen','led_mater_phone','led_mater_propa',
                    'led_gift_date','led_matter_card','led_matter_trail','led_matter_frame','led_venue_layout',
                    'led_venue_test','led_venue_audio','led_rece_guarantee','led_rece_name','led_scene_name',
                    'led_scene_inter','led_scene_error','led_scene_fenxi','led_end_name','led_end_summary',
                    'led_end_date','led_file_name','led_file_danwei','led_file_address','led_meeting_proposal',
         );

        foreach($param as $key => $v){
            foreach ($v as $k => $v1){
                $data[$res[$k]]=$v1;
            }       
            $param[$key]=$data;
        }  
        foreach($param as $key => $v){
            $res = $led_meeting->add($v);
        } 
        if($res){  
            return C('COMMON.SUCCESS_EDIT');
        }else{        
            return C('COMMON.ERROR_EDIT');
        } 
    }
}
