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
            $result['info'] = $info; 
            $result['rootPath'] = $upload->rootPath;
            return $result;
        }
    }

    /**
     *  ftp文件上传登录
     * @param array ftp相关配置
     * @author lishuaijie
     * @return source
     * @date 16/09/20
     */
    public function ftpLogin($param = array()) {
        if (!empty($param)) {
            $ftp_option = $param;
        } else {
            return false;
        }
        $this->conn_id = @ftp_connect($ftp_option['FTP_HOST'], $ftp_option['FTP_PORT']);
        @ftp_login($this->conn_id, $ftp_option['FTP_USER'], $ftp_option['FTP_PWD']);
        @ftp_pasv($this->conn_id, 1); // 打开被动模拟
        return $this->conn_id;
    }

    /**
     *  ftp移动文件上传
     * @param $conn_Id 资源
     * @param $src 源文件路径
     * @param $newPath 上传文件路径
     * @author lishuaijie
     * @return true/false
     * @date 16/09/20
     */
    public function ftpPut($conn_id, $src, $newPath) {
        $upload_flag = @ftp_put($this->conn_id, $newPath, $src, FTP_BINARY);
        return $upload_flag;
    }

    /**
     *  ftp操作文件
     * @param param 文件上传的限制信息
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
        //连接ftp
        $this->conn_id = $this->ftpLogin($ftp_option);
        //文件上传到临时目录
        $result = $this->normalUpload($param);
        $return_info = array();
        foreach ($result['info'] as $key => $val) {
            @ftp_chdir($this->conn_id, $ftp_option['FTP_ROOT_PATH']);
            $src_path = $result['rootPath'] . $val['savepath'] . $val['savename'];
            //获取文件详细信息 
            $file_info = pathinfo($src_path); 
            $new_name = $file_info['filename'] . '.' . $file_info['extension'];
            //创建文件夹
            $path = $param['PATH'] . date('Y-m-d');
            $this->ftpMkdir($this->conn_id, $param['ROOT_PATH'] . $path);
            //上传文件
            $upload_flag = $this->ftpPut($this->conn_id, $src_path, $new_name);
            if($upload_flag){
                $return_info[$key]['status'] = C('COMMON.UPLOAD_SUCCESS');
            }else{
                $return_info[$key]['status'] = C('COMMON.UPLOAD_ERROR');
            }
            $return_info[$key]['path'] = $path .'/'. $new_name;
            //上传成功或失败 都删除 临时文件
            //删除临时文件
            unlink($src_path);
        }
        //删除临时文件夹
        rmdir($file_info['dirname']);
        return $return_info;
    }

    /**
     *  ftp创建文件夹
     * @param path 创建路径
     * @param $conn 资源
     * @return true/false 
     */
    public function ftpMkdir($conn, $path) {
        //每切换一次的目录 $conn 将自动改变 
        $path_arr = explode('/', $path); // 取目录数组
        $path_div = count($path_arr); // 取层数
        foreach ($path_arr as $val) { // 创建目录
            if (@ftp_chdir($conn, $val) == FALSE) {
                $tmp = @ftp_mkdir($conn, $val);
                @ftp_chdir($conn, $val);
            }
        }
        return true;
    }

    /**
     *  获取所有用户
     * @author lishuaijie
     * @return array
     * @date 16/09/21
     */
    public function getUserInfo() {
        $user_info = D('member')
                ->where(array('status' => 1))
                ->field('uid,name')
                ->order('uid desc')
                ->select();
        if (empty($user_info)) {
            return array();
        } else {
            $info = array('uid' => -100, 'name' => '其他');
            array_push($user_info, $info);
            return $user_info;
        }
    }

}
