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
     * @param $led_meeting_id 修改条件
     * @return object 修改成功或失败 
     */
//    public function savePresent($data,$led_meeting_id){
//        $led_meeting = M('led_meeting');
//        $res = $led_meeting->where("led_meeting_id =".$led_meeting_id)->save($data);
//       if($res){
//            return C('COMMON.SUCCESS_EDIT');
//        }else{
//            return C('COMMON.ERROR_EDIT');
//        }      
//    }
    
    /*
     * 
     * 删除操作
     * @Date    2016/09/27
     * @author  huanggang
     * @param $led_meeting_id 删除条件
     * @return object 删除成功或失败
     */
//    public function delPresent($led_meeting_id){
//        $led_meeting = M('led_meeting');
//        $res = $led_meeting->where("led_meeting_id =".$led_meeting_id)->setField('led_status','1');
//       if($res){
//            return C('COMMON.SUCCESS_DEL');
//        }else{
//            return C('COMMON.ERROR_DEL');
//        }      
//    }
    
      /*
     * 导出execl 查询
     * @Date    2016/09/27
     * @author huanggang
     * @author array data 组合要到导出的数据
     */
//    public function getExecl($param){
//         //处理查询条件：会谈名称、主持人、日期
//        $param['led_meeting_name'] != '' ? $where['led_meeting_name'] = array('like', '%' . $param['led_meeting_name'] . '%') : '';
//        $param['led_meeting_host'] != '' ? $where['led_meeting_host'] = array('like', '%' . $param['led_meeting_host'] . '%') : '';
//        if (!empty($param['led_meeting_date'])) {
//            $where['led_meeting_date'] = array('EQ', $param['led_meeting_date']);
//        }
//        $where['led_status'] = array('EQ', '0');
//        $led_meeting = M('led_meeting');
//        $data = $led_meeting
//              ->where($where) 
//              ->select();
//        //去除不需要的键值
//         foreach($data as $k => $v){
//             unset($data[$k]['led_meeting_id']);
//             unset($data[$k]['led_status']);
//         }
//         return $data;
//    }
}
