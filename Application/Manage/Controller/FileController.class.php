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

    /*
     * 添加文档
     * @author huang gang
     * @param string $wordname 工作单名称
     * @param string $password
     * @param string $verify_code
     * @return object 跳转或显示页面
     */

    public function __construct() {
        parent::__construct();

        $this->filedoc = D('File');
    }

    /**
     * 对数组进行转义
     * 
     * @param array 需要转义的数组
     * @return array 返回转义后的数组
     */
    public function escape($param) {
        foreach ($param as $k => $val) {
            $param[$k] = str_replace("_", "\_", $val);
        }

        return $param;
    }

    /*
     * 对文件大小及类型进行判断
     */

    public function filesize($size) {
        if ($size['file']['size'] <= C('FILE_DOC.FILE_SIZE') && $size['file']['size'] <= C('FTP_COVER.FILE_SIZE')) {
            $doc_upload_file = end(explode('.', $size['file']['name']));
            $doc_upload_img = end(explode('.', $size['file1']['name']));
            if (in_array($doc_upload_file, C('FILE_DOC.ALLOW_FILE')) && in_array($doc_upload_img, C('FILE_COVER.ALLOW_FILE'))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *  显示文档发布列表
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
        $page = new \Think\Page($count, 5);
        $list = $this->filedoc->getList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
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
     *  文档添加
     */
    public function addFile() {
        $data = I();
        if (!empty($data)) {
            if (!empty($_FILES)) {
                $size = $this->filesize($_FILES);
                // P($size);die;
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
                        $this->error('/Manage/File/index/', $result['status']);
                    }
                    $result = $this->filedoc->addFile($data);
                    if ($result['code'] == 200) {
                        $this->success($result['status'], 'index');
                    } else {
                        $this->success($result['status'], 'addFile');
                    }
                } else {
                    $this->error(C('DOCFILE.SZIE_TYPE'), 'addFile');
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

     /*
     * 文档撤回
     */

    public function delFile() {
        $doc_id = I('doc_id');
        $result = $this->filedoc->delFiledoc($doc_id);
        $this->ajaxReturn($result);
    }
    
    /*
     * 文档编辑
     */
    public function  saveFile(){
        if(IS_GET){
        $doc_id=I('doc_id');
        $result = $this->filedoc->saveFiledoc($doc_id);
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
                    $size = $this->filesize($_FILES);
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
                            $this->error('/Manage/File/index/', $result['status']);
                        }
                        $result = $this->filedoc->updateFiledoc($data,$data['doc_id']);
                        if ($result['code'] == 200) {
                            $this->success($result['status'], 'index');
                        }else {
                            $this->success($result['status'], "saveFile?doc_id=".$data['doc_id']);
                        }
                    }else {
                        $this->error(C('DOCFILE.SZIE_TYPE'), "saveFile?doc_id=".$data['doc_id']);
                    }
                }
            }
        }       
    }
}
