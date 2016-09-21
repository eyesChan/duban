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
    /**
     *  显示文档发布列表
     */
    public function index() {
        
         //文档发布类型 
          $file_type=getConfigInfo('doc_pub_type');     
          $this->assign('file_type',$file_type);     
          $this->display();
    }
    
    /**
     *  显示会议创建页面
     */
    public function addFile(){
       $data=I();
      
       if(!empty($data['filedoc'])){
         //   print_r($data);die;
          //  $fliedoc = D('doc');
           $data['filedoc']['doc_pub_date']=date('Y-m-d');
           $data['filedoc']['doc_pub_person']=1;
           //print_r($data);die;
           if (!empty($_FILES)) {
                $upload_obj = new MeetingUplod();
                $config_info = C();
                //判断上传方式
                if($config_info['OPEN_FTP'] == '1'){ //开启ftp上传
                    $file_config = $config_info['FTP_DOC'];
                    $result = $upload_obj->ftpUpload($file_config);
                    
                }else{ //普通上传
                    $file_config = $config_info['FILE_DOC'];
                    $result = $upload_obj->normalUpload($file_config);
                }
                if($result['code'] == 100){
                    $this->error('{:U(index)}',$result['status']);
                }
                print_r($result);
            }
            die;
       }
        //文档发布类型 
       $file_type=getConfigInfo('doc_pub_type');     
       $this->assign('file_type',$file_type);
       //文档发布部门
       $file_depart=getConfigInfo('doc_pub_depart');     
       $this->assign('file_depart',$file_depart);
       //文档可见范围
       $file_range=getConfigInfo('doc_pub_range');     
       $this->assign('file_range',$file_range);
       //文档权限设定
       $file_authority=getConfigInfo('doc_pub_authority');     
       $this->assign('file_authority',$file_authority);
       $this->display();
    }
    
    
    /**
     *  显示文档查询页面
     */
    public function selectFile(){
        $this->display();
    }
}