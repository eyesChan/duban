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

            $docfile = M('doc');
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
            $res = $docfile->add($data);
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
      $docfile = M('doc');
      $list = $docfile
              ->join('db_member on db_doc.doc_pub_person = db_member.uid')
              ->join('db_config_system on db_doc.doc_pub_type = db_config_system.config_id')
              ->where($where) 
              ->limit($first_rows, $list_rows)
              ->order('doc_id desc')
              ->select();
      return $list;
    }
    
    /*
     * 撤回
     */
    public function delFiledoc($doc_id){
        $docfile = M('doc');
        $res_delete=$docfile-> where('doc_id='.$doc_id)->setField('doc_status','0');
        return $res_delete;
      
    }
    
   /*
    * 编辑状态查询
    */
    public function saveFiledoc($doc_id){
        $docfile = M('doc');
        $list = $docfile
              ->join('db_member on db_doc.doc_pub_person = db_member.uid')
              ->join('db_config_system on db_doc.doc_pub_type = db_config_system.config_id')
              ->where('doc_id='.$doc_id)       
              ->find();
        return $list;
    }
    
 /*
  * 编辑文档
  */
    public function updateFiledoc($data,$doc_id){
        $docfile = M('doc');
        $res = $docfile->where("doc_id =".$doc_id)->save($data);
        if($res){
            return C('COMMON.SUCCESS_EDIT');
        }else{
            return C('COMMON.ERROR_EDIT');
        } 
    }
    
}



