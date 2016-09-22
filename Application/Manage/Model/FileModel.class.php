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
 * @author huanggang
 * @Date    2016/09/22
 */
class FileModel  extends Model{
    
    protected $trueTableName = 'db_doc';

    /*
    * 文档添加
    * 
    * @ahthor huanggang
    * @param string $param['doc_name']; 文档名称
    * @param string $param['doc_dept_id']; 发布部门id
    * @param string $param['doc_pub_person']; 发布人
    * @param string $param['doc_pub_date']; 发布日期
    * @param string $param['doc_pub_type']; 发布类型
    * @param string $param['doc_start_date']; 生效日期
    * @param string $param['doc_end_date']; 失效日期
    * @param string $param['doc_root_view']; 文档可见范围
    * @param string $param['doc_root_do']; 文档权限设定
    * @param string $param['doc_upload_file_url']; 上传文档url
    * @param string $param['doc_upload_img_url']; 上传图片url
    * @param string $param['doc_beizhu']; 备注
     * @param string $param['doc_status']; 是否撤回的状态 默认1 撤回 0
    * @return object 添加成功或失败
    */
    
    public function addFile($param){

            $order = M('doc');
            $data['doc_name'] = $param['doc_name'];
            $data['doc_dept_id'] = $param['doc_dept_id'];
            $data['doc_pub_person'] = session('S_USER_INFO.UID'); //$param['doc_pub_person'];
            $data['doc_pub_date'] = date('Y-m-d'); //$param['doc_pub_date'];
            $data['doc_pub_type'] = $param['doc_pub_type'];
            $data['doc_start_date'] = $param['doc_start_date'];
            $data['doc_end_date'] = $param['doc_end_date'];
            $data['doc_root_view'] = $param['doc_root_view'];
            $data['doc_root_do'] = $param['doc_root_do'];
            $data['doc_upload_file_url'] = $param['doc_upload_file_url'];
            $data['doc_upload_img_url'] =$param['doc_upload_img_url'];
            $data['doc_beizhu'] = $param['doc_beizhu'];
            $data['doc_status'] = 1 ; //$param['doc_status'];
            $res = $order->add($data);
            if($res){
                return C('COMMON.SUCCESS_EDIT');
            }else{
                return C('COMMON.ERROR_EDIT');
            } 
    }
    /*
     * 统计数量
     */
    public function getFileDocCount($where) {
        $count = M('doc')
                ->join('db_member on db_doc.doc_pub_person = db_member.uid')
                ->join('db_config_system on db_doc.doc_pub_type = db_config_system.config_id')
                ->where($where)
                ->count();
       // return M("doc")->getLastSql();
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
    public function getList($where, $first_rows, $list_rows) {
      $order = M('doc');
      $list = $order
              ->join('db_member on db_doc.doc_pub_person = db_member.uid')
              ->join('db_config_system on db_doc.doc_pub_type = db_config_system.config_id')
              ->where($where) 
              ->limit($first_rows, $list_rows)
              ->order('doc_id desc')
              ->select();
      return $list;
    }
    /*
     * 查询单个工作单
     * @author xiaohui
     * @param int 工作单号
     * @return array 成功返回列表
     */
   /* public function selectWork($param){
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
   /* public function saveWork($param){
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
   /* public function abandonedWork($param){
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
    }*/
}



