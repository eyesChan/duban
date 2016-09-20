<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller\CommonApi;

/**
 * Description of MeetingUpload
 *
 * @author lsj
 */
class MeetingUpload extends \Think\Controller {

    /**
     *  普通文件上传
     * @param file_size 文件大小
     * @param file_path 文件路径
     * @param allow_file 允许格式
     * @author lishuaijie
     * @return array
     * @date 16/09/20

     */
    public function normalUpload($param = array()) {
        if (empty($param)) {
            $result = C('COMMON.PARAMTER_ERROR');
            return $result;
        }
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = $param['FILE_SIZE']; // 设置附件上传大小
        $upload->exts = $param['ALLOW_FILE']; // 设置附件上传类型
        $upload->rootPath = C('FILE_ROOT_PATH'); // 设置附件上传根目录
        $upload->savePath = $param['FILE_PATH']; // 设置附件上传（子）目录
        if (file_exists($upload->rootPath)) {
            chmod($upload->rootPath, '0777');
        }
        // 上传文件 
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
            return C('COMMON.UPLOAD_ERROR');
        } else {// 上传成功 获取上传文件信息
            $result = C('COMMON.UPLOAD_SUCCESS');
            $result['path'] = $upload->rootPath . '' . $info['file']['savepath'] . $info['file']['savename'];
            return $result;
        }
    }

    /**
     *  ftp文件上传
     * @param file_size 文件大小
     * @param file_path 文件路径
     * @param allow_file 允许格式
     * @author lishuaijie
     * @return array
     * @date 16/09/20
     */
    public function ftpUpload($param = array()) {
        if (empty($param)) {
            $result = C('COMMON.PARAMTER_ERROR');
            return $result;
        }
        $ftp_option = C('FTP_OPTION');
        $result = $this->normalUpload($param);
        $src_path = $result['path'];
        $file_info = pathinfo($src_path);//获取文件详细信息 
        $this->conn_id = @ftp_connect($ftp_option['FTP_HOST'], $ftp_option['FTP_PORT']);
        @ftp_login($this->conn_id, $ftp_option['FTP_USER'], $ftp_option['FTP_PWD']);
        @ftp_pasv($this->conn_id, 1); // 打开被动模拟
        $upload_flag =  @ftp_put($this->conn_id,$file_info['filename'].'.'.$file_info['extension'],$src_path,FTP_BINARY);
        if($upload_flag){
            //删除临时文件
            unlink($src_path);
            rmdir($file_info['dirname']);
            $result_info = C('COMMON.UPLOAD_SUCCESS');
            $result_info['path'] = '';
            return $result_info;
            
        }else{
            return C('COMMON.UPLOAD_ERROR');
        }
    }
    /**
     *  ftp创建文件夹
     * @param path 创建路径
     * @param $conn 资源
     * @return true/false 
     */
    public function ftp_mkdir($conn,$path){
        
    }

}
