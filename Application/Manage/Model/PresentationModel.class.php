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
class PresentationModel  extends Model{
    
    protected $trueTableName = 'db_led_presentation';
 /*
  * 文稿台账创建
  * @Date    2016/09/27
  * @ahthor huanggang
  * @return object 添加成功或失败
  */
     public function addPresent($param){
            $led_presentation = M('led_presentation');
            $res = $led_presentation->add($param);
            if($res){
                return C('COMMON.SUCCESS_EDIT');
            }else{
                return C('COMMON.ERROR_EDIT');
            } 
    }
    
     /*
     * 查询用户表
     * @ahthor huanggang
     * @Date    2016/09/27
     * @return array 用户名称 id
     */
    public function getUser() {
        $user = M('member')
                ->field('uid,name')  
                ->select();
        return $user;
    }
    
    /*
     * 统计数量
     * @ahthor huanggang
     * @Date    2016/09/26
     * @return object 数据数量
     */
    public function getPresentCount($where) {
        $count = M('led_presentation')
                ->where($where)
                ->count();
        return $count;
    }
    /**
     * 分页查询操作
     * @Date    2016/09/27
     * @author huanggang
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getPresentList($where, $first_rows, $list_rows) {
      $led_presentation = M('led_presentation');
      $list = $led_presentation
              ->join('db_member on db_led_presentation.db_draft_person = db_member.uid')
              ->field('db_pre_id,db_pre_name,db_assign_name,db_assign_date,name,db_despatch_date')
              ->where($where) 
              ->limit($first_rows, $list_rows)
              ->select();
      return $list;
    }
    
    
    /*
     * 页面详情及编辑查询
     * @Date    2016/09/27
     * @author  huanggang
     * @param $pre_id 查询条件
     * @return 返回处理数据
     */
    public function detailsPresent($pre_id){
        $led_presentation = M('led_presentation');
        $list = $led_presentation
                ->where("db_pre_id= $pre_id") 
                ->find();
      return $list; 
    }
    
    /*
     * 编辑操作
     * @Date    2016/09/27
     * @author huanggang
     * @param array $data 修改后的数据
     * @param $pre_id 修改条件
     * @return object 修改成功或失败 
     */
    public function savePresent($data,$pre_id){
        $led_presentation = M('led_presentation');
        $res = $led_presentation->where("db_pre_id =".$pre_id)->save($data);
       if($res){
            return C('COMMON.SUCCESS_EDIT');
        }else{
            return C('COMMON.ERROR_EDIT');
        }      
    }
    
    /*
     * 
     * 删除操作
     * @Date    2016/09/27
     * @author  huanggang
     * @param $pre_id 删除条件
     * @return object 删除成功或失败
     */
    public function delPresent($pre_id){
        $led_presentation = M('led_presentation');
        $res = $led_presentation->where("db_pre_id =".$pre_id)->setField('pre_status','1');
       if($res){
            return C('COMMON.SUCCESS_DEL');
        }else{
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
        $param['db_pre_name'] != '' ? $where['db_pre_name'] = array('like', '%' . $param['db_pre_name'] . '%') : '';
        $param['db_assign_name'] != '' ? $where['db_assign_name'] = array('like', '%' . $param['db_assign_name'] . '%') : '';
        if (!empty($param['led_meeting_date'])) {
            $where['db_assign_date'] = array('EQ', $param['db_assign_date']);
        }
        $where['pre_status'] = array('EQ', '0');
        $led_presentation = M('led_presentation');
        $data = $led_presentation
               ->field('db_pre_id,db_pre_name,db_pre_work,db_assign_date,db_complete_time,db_pre_type,db_pre_form,db_pre_first,db_pre_diff,db_pre_person,db_pre_status,db_draft_person,db_draft_length,db_draft_num,db_orgin_person,db_orgin_length,db_orgin_num,db_orgin_eval,db_orgin2_person,db_orgin2_length,db_orgin2_num,db_orgin2_eval,db_orgin3_person,db_orgin3_length,db_orgin3_num,db_orgin3_eval,db_overtime_num,db_overtime_num,db_mishap_num,db_examin_date,db_examin_mode,db_examin_progress,db_despatch_mode,db_despatch_date,db_file_status,db_file_date,db_file_address')
              ->where($where) 
              ->select();
         return $data;
    }
}
//                                    db_pre_id
//                                    db_pre_name
//                                    db_pre_work
//                                    db_assign_date
//                                    db_complete_time
//                                    db_pre_type
//                                    db_pre_form
//                                    db_pre_first
//                                    db_pre_diff
//                                    db_pre_person
//                                    db_pre_status
//                                    db_draft_person
//                                    db_draft_length
//                                    db_draft_num
//                                    db_orgin_person
//                                    db_orgin_length
//                                    db_orgin_num
//                                    db_orgin_eval
//                                    db_orgin2_person
//                                    db_orgin2_length
//                                    db_orgin2_num
//                                    db_orgin2_eval
//                                    db_orgin3_person
//                                    db_orgin3_length
//                                    db_orgin3_num
//                                    db_orgin3_eval
//                                    db_overtime_num
//                                    db_overtime_num
//                                    db_mishap_num
//                                    db_examin_date
//                                    db_examin_mode
//                                    db_examin_progress
//                                    db_despatch_mode
//                                    db_despatch_date
//                                    db_file_status
//                                    db_file_date
//                                    db_file_address