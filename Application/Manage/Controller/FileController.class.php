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
        $export_file= base64_encode($export_file);
        $this->assign('export_file',$export_file);
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
            if (!empty($_FILES)) {
                $size = $this->filedoc->fileSize($_FILES);
                if (!empty($size)) {
                    $upload_obj = new MeetingUplod();
                    $config_info = C();
                    //判断上传方式
                    if ($config_info['OPEN_FTP'] == '1') { //开启ftp上传
                        $file_config = $config_info['FTP_DOC'];
                        $result = $upload_obj->ftpUpload($file_config);
                    } else { //普通上传
                        $file_config = $config_info['FILE_DOC'];
                        $result = $upload_obj->normalUpload($file_config);
                    }
                    $data['doc_upload_file_url'] = $result['info']['file']['savepath'] . $result['info']['file']['savename'];
                    $data['doc_upload_img_url'] = $result['info']['file1']['savepath'] . $result['info']['file1']['savename'];
                    if ($result['code'] == 100) {
                        $this->error($result['status'],U('File/index'));
                    }
                    $result = $this->filedoc->addFile($data);
                    if ($result['code'] == 200) {
                        $this->success($result['status'], U('File/index'));
                    } else {
                        $this->error($result['status'], U('File/addFile'));
                    }
                } else {
                    $this->error(C('DOCFILE.SZIE_TYPE'), U('File/addFile'));
                }
            }
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
        if(IS_GET){
        $doc_id=I('doc_id');
        $result = $this->filedoc->saveFileDoc($doc_id);
        $this->assign('list', $result);
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
        if(IS_POST){
            $data = I();
             if (!empty($data)) {
                if (!empty($_FILES)) {
                    $size = $this->filedoc->fileSize($_FILES);
                    if (!empty($size)) {
                        $upload_obj = new MeetingUplod();
                        $config_info = C();
                        //判断上传方式
                        if ($config_info['OPEN_FTP'] == '1') { //开启ftp上传
                            $file_config = $config_info['FTP_DOC'];
                            $result = $upload_obj->ftpUpload($file_config);
                        } else { //普通上传
                            $file_config = $config_info['FILE_DOC'];
                            $result = $upload_obj->normalUpload($file_config);
                        }
                        $data['doc_upload_file_url'] = $result['info']['file']['savepath'] . $result['info']['file']['savename'];
                        $data['doc_upload_img_url'] = $result['info']['file1']['savepath'] . $result['info']['file1']['savename'];
                        if ($result['code'] == 100) {
                            $this->error($result['status'],U('File/index'));
                        }
                        $result = $this->filedoc->updateFileDoc($data,$data['doc_id']);
                        if ($result['code'] == 200) {
                            $this->success($result['status'],U('File/index'));
                        }else {
                            $this->success($result['status'], U('File/saveFile?doc_id='.$data['doc_id']));
                        }
                    }else {
                        $this->error(C('DOCFILE.SZIE_TYPE'), U('File/saveFile?doc_id='.$data['doc_id']));
                    }
                }
            }
        }       
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