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
    
    
   private $file_doc_type;
    public function __construct() {
        parent::__construct();
        $this->file_doc_type = D('File');
    }
    
    /**
     *  显示文档发布列表
     */
    public function index() {
        
         //文档发布类型 
          $file_type=$this->file_doc_type->getFileDocType();
          $this->assign('file_type',$file_type);
          $this->display();
    }
    
    /**
     *  显示会议创建页面
     */
    public function addFile(){
        
        //文档发布类型 
       $file_type=$this->file_doc_type->getFileDocType();
       $this->assign('file_type',$file_type);
       $data=I();
       if(!empty($data)){
           print_r($data);
            $fliedoc = D('doc');
            if (!empty($_FILES)) {
                $upload_obj = new MeetingUplod();
                $config_info = C();
                //判断上传方式
                if($config_info['OPEN_FTP'] == '1'){ //开启ftp上传
                    $file_config = $config_info['FTP_MEETING'];
                    var_dump($file_config);
                }else{ //普通上传
                    $file_config = $config_info['FILE_MEETING'];
                    $result = $upload_obj->normalUpload($file_config);
                    var_dump($result);die;
                }die;
                $upload = new \Think\Upload(); // 实例化上传类
                $upload->maxSize = $config_info['FILE_SIZE']; // 设置附件上传大小
                $upload->exts = array('xls', 'xlsx'); // 设置附件上传类型
                $upload->rootPath = $config_info['FILE_PATH']; // 设置附件上传根目录
                $upload->savePath = 'meeting'; // 设置附件上传（子）目录
                if(file_exists($upload->rootPath)){
                    chmod($upload->rootPath, '0777');
                }
                // 上传文件 
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功 获取上传文件信息
                    $orderInfo['ESTIMATED_TIME'] = '';
                    $orderInfo['PAYMENT_DOC_PATH'] = $upload->rootPath .'' . $info['file']['savepath'] . $info['file']['savename'];
                }
            }die;   
            var_dump($meetingMod->add($data['meeting']));
            die;
       }
       $this->display();
    }
    
    /**
     *  显示文档查询页面
     */
    public function selectFile(){
        $this->display();
    }
}