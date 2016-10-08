<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;

use Think\Model;

/**
 * Description of FileModel
 * @author huanggang
 * @Date    2016/09/22
 */
class FileModel  extends Model{
    
    protected $trueTableName = 'db_doc';

    /*
    * 文档添加
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
            
            if(in_array('',$data)){
                writeOperationLog('添加的数据为空', 0);
                return C('COMMON.ERROR_EDIT');
            }
            $res = $docfile->add($data);
            if($res){
                writeOperationLog('添加' . $param['doc_name'] . '”文档', 1);
                return C('COMMON.SUCCESS_EDIT');
            }else{
                writeOperationLog('添加' . $param['doc_name'] . '”文档', 0);
                return C('COMMON.ERROR_EDIT');
            } 
    }
    
    /*
     * 对文件大小及类型进行判断
     * @ahthor huanggang
     * @Date    2016/09/22
     * @return object 返回true或false
     */

    public function fileSize($size) {
        if ($size['file']['size'] <= C('FILE_DOC.FILE_SIZE') && $size['file']['size'] <= C('FTP_COVER.FILE_SIZE')) {
            $doc_upload_file = end(explode('.', $size['file']['name']));
            $doc_upload_img = end(explode('.', $size['file1']['name']));
            if (in_array($doc_upload_file, C('FILE_DOC.ALLOW_FILE')) && in_array($doc_upload_img, C('FILE_COVER.ALLOW_FILE'))) {
                writeOperationLog('上传文件成功',1);
                return true;
            } else {
                writeOperationLog('上传文件失败',0);
                return false;
            }
        } else {
            writeOperationLog('上传文件失败',0);
            return false;
        }
    }
    /*
     * 统计数量
     * @ahthor huanggang
     * @Date    2016/09/22
     * @return object 数据数量
     * 
     */
    public function getFileDocCount($where) {
        $count = M('doc')
                ->join('__MEMBER__ on __DOC__.doc_pub_person = __MEMBER__.uid')
                ->join('__CONFIG_SYSTEM__ on __DOC__.doc_pub_type = __CONFIG_SYSTEM__.config_id')
                ->where($where)
                ->count();
        return $count;
    }
    /**
     * 分页查询操作
     * @Date    2016/09/23
     * @author  huanggang
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getList($where, $first_rows, $list_rows) {
      $docfile = M('doc');
      $list = $docfile
              ->join('__MEMBER__ on __DOC__.doc_pub_person = __MEMBER__.uid')
              ->join('__CONFIG_SYSTEM__ on __DOC__.doc_pub_type = __CONFIG_SYSTEM__.config_id')
              ->where($where) 
              ->limit($first_rows, $list_rows)
              ->order('doc_id desc')
              ->select();
      return $list;
    }
    
    /*
     * 撤回
     * @Date    2016/09/22
     * @author  huanggang
     * @param $doc_id 撤回条件
     * @return 返回处理数据
     */
    public function delFileDoc($doc_id){
        $docfile = M('doc');
        $res_delete=$docfile-> where('doc_id='.$doc_id)->setField('doc_status','0');
        $doc_name=$docfile-> where('doc_id='.$doc_id)->getField('doc_name');
         if($res_delete){
             writeOperationLog('撤回' . $doc_name . '”文档', 1); 
            return C('COMMON.DOCDEL_SUCCESS');
        }else{
            writeOperationLog('撤回' . $doc_name . '”文档', 0); 
            return C('COMMON.DOCDEL_ERROR');
        }    
    }
    
    /*
     * 编辑状态查询
     * @Date    2016/09/23
     * @author huanggang
     * @param $doc_id 查询条件
     * @return 返回查询的数据
    */
    public function saveFileDoc($doc_id){
        $docfile = M('doc');
        $list = $docfile
              ->join('__MEMBER__ on __DOC__.doc_pub_person = __MEMBER__.uid')
              ->join('__CONFIG_SYSTEM__ on __DOC__.doc_pub_type = __CONFIG_SYSTEM__.config_id')
              ->where('doc_id='.$doc_id)       
              ->find();
        return $list;
    }
    
    /*
     * 编辑文档
     * @Date    2016/09/22
     * @author huanggang
     * @param array $data 修改后的数据
     * @param $doc_id 修改条件
     * @return object 修改成功或失败
     */
    public function updateFileDoc($data,$doc_id){
        $docfile = M('doc');
        $res = $docfile->where("doc_id =".$doc_id)->save($data);
        if($res){
            writeOperationLog('编辑' . $data['doc_name'] . '”文档', 1);
            return C('COMMON.SUCCESS_EDIT');
        }else{
            writeOperationLog('编辑' . $data['doc_name'] . '”文档', 0);
            return C('COMMON.ERROR_EDIT');
        } 
    }
    
     /*
     * 导出execl 查询
     * @Date    2016/09/23
     * @author huanggang
     * @return array data 组合要到导出的数据
     */
    public function getExecl($param){
        foreach($param as $k => $v){
            $data[$k]['doc_name']=$v['doc_name'];
            $data[$k]['config_descripion']=$v['config_descripion'];
            $data[$k]['name']=$v['name'];
            $data[$k]['doc_pub_date']=$v['doc_pub_date'];
            $data[$k]['doc_start_date']=$v['doc_start_date'];
            $data[$k]['doc_end_date']=$v['doc_end_date'];
            $data[$k]['doc_root_view']=$this->getRootView($v['doc_root_view']);
            $data[$k]['doc_root_do']=$this->getRootView($v['doc_root_do']);
            $data[$k]['doc_beizhu']=$v['doc_beizhu'];
        }
         return $data;
    }
  /*
   * 获取导出数据的可见范围及权限
   * @author huanggang
   * @Date    2016/09/23
   * @param  $config_id 查询条件
   * @return 返回查询的数据
   */
    public function  getRootView($config_id){
         $work = M('config_system')
              ->field('config_descripion')
              ->where("config_id = $config_id")
              ->find();   
        return $work['config_descripion'];
    }
}



