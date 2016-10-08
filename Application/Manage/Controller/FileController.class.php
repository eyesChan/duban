<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

/**
 *  Description   文档管理
 *  @author       huanggang <gang.huang2@pactera.com>
 *  Date          2016/09/20
 */
class FileController extends AdminController {

    private $filedoc;
    public function __construct() {
        parent::__construct();

        $this->filedoc = D('File');
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
     * 显示文档发布列表
     * @author huang gang
     * @date 2016/09/21
     * @return 跳转页面 Description
     *  
     */
    public function index() {
        $param = I();
        //处理查询条件：文档名称、发布人、发布日期、文档类型.发布与撤回区分的状态
        $param['doc_name'] != '' ? $where['doc_name'] = array('like', '%' . $param['doc_name'] . '%') : '';
        $param['name'] != '' ? $where['name'] = array('like', '%' . $param['name'] . '%') : '';
        if (!empty($param['doc_pub_date'])) {
            $where['doc_pub_date'] = array('EQ', $param['doc_pub_date']);
        }
        if (!empty($param['doc_pub_type'])) {
            $where['doc_pub_type'] = array('EQ', $param['doc_pub_type']);
        }
        $where['doc_status'] = array('EQ', '1');
        $where = $this->escape($where);
        $count = $this->filedoc->getFileDocCount($where);
        $page = new \Think\Page($count, 10);
        $list = $this->filedoc->getList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        //数组转换为字符串
        $export_file=  json_encode($list);
        $export_files= base64_encode($export_file);
        $this->assign('export_file',$export_files);
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
        $this->assign('time', date('Y-m-d'));
        //文档发布类型 
        $file_type = getConfigInfo('doc_pub_type');
        $this->assign('file_type', $file_type);
        $this->display();
    }

    /**
     * 文档添加
     * @author huang gang
     * @date 2016/09/22
     * @return 跳转页面 Description
     *  
     */
    public function addFile() {
        $data = I();
        if (!empty($data)) {
            if (!empty($_FILES['file']['tmp_name'])&&!empty($_FILES['file1']['tmp_name'])) {
                $size = $this->filedoc->fileSize($_FILES,0);
                if (!empty($size)) {
                    $res['file_type']='FILE_PUB_DOC';
                    $res['ftp_type']='FIP_PUB_DOC';
                    $res['mark']=0;
                    $result=$this->filedoc->saveUploadNull($res);
                    $data['doc_upload_file_url']=$result[0];
                    $data['doc_upload_img_url']=$result[1];
                    $result = $this->filedoc->addFile($data);
                    if ($result['code'] == 200) {
                        $this->success($result['status'], U('File/index'));
                    } else {
                        $this->error($result['status'], U('File/addFile'));
                    }
                    
                }else{
                    $this->error(C('DOCFILE.SZIE_TYPE'), U('File/addFile'));
                }
            }else{
                 $this->error(C('DOCFILE.FILE_DOC'), U('File/addFile'));
            }
        return true;
        }
        //文档发布类型 
        $file_type = getConfigInfo('doc_pub_type');
        $this->assign('file_type', $file_type);
        //文档发布部门
        $file_depart = getConfigInfo('doc_pub_depart');
        $this->assign('file_depart', $file_depart);
        //文档可见范围
        $file_range = getConfigInfo('doc_pub_range');
        $this->assign('file_range', $file_range);
        //文档权限设定
        $file_authority = getConfigInfo('doc_pub_authority');
        $this->assign('file_authority', $file_authority);
        $this->display();
    }

    /**
     * 文档撤回
     * @author huang gang
     * @date 2016/09/22
     * @return 跳转页面 Description
     *  
     */
    public function delFile() {
        $doc_id = I('doc_id');
        $result = $this->filedoc->delFileDoc($doc_id);
        if ($result['code'] == 200) {
            $this->success($result['status'],U('File/index'));
        }else {
            $this->success($result['status'], U('File/index'));
        }
    }
    
   /**
     *  文档编辑
     * @author huang gang
     * @date 2016/09/22
     * @return 跳转页面 Description
     *  
     */
    
    public function  saveFile(){
        if(IS_POST){
            $data = I();
            if (!empty($_FILES['file']['tmp_name'])&&!empty($_FILES['file1']['tmp_name'])) {
                $size = $this->filedoc->fileSize($_FILES,0);
                if($size){
                    $res['file_type']='FILE_PUB_DOC';
                    $res['ftp_type']='FIP_PUB_DOC';
                    $res['mark']=0;
                    $result=$this->filedoc->saveUploadNull($res);
                    $data['doc_upload_file_url']=$result[0];
                    $data['doc_upload_img_url']=$result[1];
                    }else{
                        $this->error(C('DOCFILE.SZIE_TYPE'),U('File/saveFile',array('doc_id'=>$data['doc_id'])));       
                }    
            }
            $fileName = $this->filedoc->fileSize($_FILES,1);
            $result=$this->filedoc->saveUploadNull($fileName);
            if($fileName['mark']=='file'){
                $data['doc_upload_file_url']=$result[0];
            }else{
                $data['doc_upload_file_url']=$result[1];
            }
            $result = $this->filedoc->updateFileDoc($data,$data['doc_id']);
                if ($result['code'] == 200) {
                    $this->success($result['status'],U('File/index'));
                }else {
                    $this->error($result['status'], U('File/saveFile',array('doc_id'=>$data['doc_id'])));
                }
            return true;
        } 
        $doc_id=I('doc_id');
        $result = $this->filedoc->saveFileDoc($doc_id);
        $result['doc_upload_file_name'] = pathinfo($result['doc_upload_file_url'])['filename'];
        $result['doc_upload_img_name'] = pathinfo($result['doc_upload_img_url'])['filename'];
        $this->assign('list', $result);
        $this->assign('file_info', $file_info);
         //文档发布类型 
        $file_type = getConfigInfo('doc_pub_type');
        $this->assign('file_type', $file_type);
        //文档发布部门
        $file_depart = getConfigInfo('doc_pub_depart');
        $this->assign('file_depart', $file_depart);
        //文档可见范围
        $file_range = getConfigInfo('doc_pub_range');
        $this->assign('file_range', $file_range);
        //文档权限设定
        $file_authority = getConfigInfo('doc_pub_authority');
        $this->assign('file_authority', $file_authority);
        $this->display();
    }
    
    /**
     * 文档导出
     * @author huang gang
     * @date 2016/09/22
     * @return 跳转页面 Description
     *  
     */
    public function exportFile(){
        $export_file =I('export_file');
        $export_file=  base64_decode($export_file, true);
        $data=json_decode($export_file,true);    
        $work = $this->filedoc->getExecl($data);    
        $headArr = array('文档名称',
                        '文档类型',
                        '发布人',
                        '发布时间',
                        '生效时间',
                        '失效时间',
                        '可见范围',
                        '权限设定',
                        '备注'
                );
        getExcel($headArr, $work);
   
    }
}