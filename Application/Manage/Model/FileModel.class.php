<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Model;

use Think\Model;
use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

/**
 * Description of FileModel
 * @author huanggang
 * @Date    2016/09/22
 */
class FileModel extends Model {

    protected $trueTableName = 'db_doc';

    /*
     * 文档添加
     * @ahthor huanggang
     * @param string $param['doc_pub_person']; 发布人id
     * @param string $param['doc_pub_date']; 发布日期
     * @param string $param['doc_status']; 是否撤回的状态 默认1 撤回 0
     * @return object 添加成功或失败
     */

    public function addFile($param) {
        $docfile = M('doc');
        $param['doc_pub_person'] = session('S_USER_INFO.UID'); //$param['doc_pub_person'];
        $param['doc_pub_date'] = date('Y-m-d'); //$param['doc_pub_date'];
        $param['doc_status'] = 1; //$param['doc_status'];
        if (in_array('', $param)) {
            writeOperationLog('添加的数据为空', 0);
            return C('COMMON.ERROR_EDIT');
        }
        $res = $docfile->add($param);
        if ($res) {
            writeOperationLog('添加“' . $param['doc_name'] . '”文档', 1);
            return C('COMMON.SUCCESS_EDIT');
        } else {
            writeOperationLog('添加“' . $param['doc_name'] . '”文档', 0);
            return C('COMMON.ERROR_EDIT');
        }
    }

    /*
     * 对文件大小及类型进行判断
     * @ahthor huanggang
     * @param $mark  标示文件是否都为空,0为都不能为空 1为任意一个为空, 
     * @Date    2016/09/22
     * @return object 返回true或false
     */

    public function fileSize($size) {
        if (!empty($size['file']['tmp_name']) && !empty($size['file1']['tmp_name'])) {
            //文档限制上传大小及类型
            $file_size = C('FILE_DOC.FILE_SIZE');
            $file_type = C('FILE_DOC.ALLOW_FILE');
            //文档附件上传大小及类型
            $img_size = C('FTP_COVER.FILE_SIZE');
            $img_type = C('FILE_COVER.ALLOW_FILE');
            //获取文档及附件的后缀名
            $doc_upload_file = end(explode('.', $size['file']['name']));
            $doc_upload_img = end(explode('.', $size['file1']['name']));
            if ($size['file']['size'] <= $file_size && $size['file']['size'] <= $img_size && in_array($doc_upload_file, $file_type) && in_array($doc_upload_img, $img_type)) {
                 //上传文件都不为空情况下的数据类型参数
                $data['file_type'] = 'FILE_PUB_DOC';
                $data['ftp_type'] = 'FIP_PUB_DOC';
                $data['mark'] = 0;
            }else{
                $data=C('DOCFILE.SZIE_TYPE');
            } 
            return $data;
        }else{
            return C('DOCFILE.FILE_DOC');
        }
        
        
    }
    
    public function fileSaveSize($size){
         //用于编辑 判断文档上传是否为空 
        if (!empty($size['file']['tmp_name']) ) {
            $data['file_type'] = 'FILE_DOC';
            $data['ftp_type'] = 'FIP_DOC';
            $data['mark'] = 'file';
        }
        //用于编辑 判断文档附件上传是否为空
        if (!empty($size['file1']['tmp_name'])) {
            $data['file_type'] = 'FILE_COVER';
            $data['ftp_type'] = 'FTP_COVER';
            $data['mark'] = 'file1'; 
        }
         return $data;
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
                ->where($where)
                ->limit($first_rows, $list_rows)
                ->order('doc_id desc')
                ->select();
        foreach ($list as $k => $v) {
            $list[$k]['doc_pub_type'] = $this->getRootView($v['doc_pub_type'], 'doc_pub_type');
        }
        return $list;
    }

    /*
     * 撤回
     * @Date    2016/09/22
     * @author  huanggang
     * @param $doc_id 撤回条件
     * @return 返回处理数据
     */

    public function delFileDoc($doc_id) {
        $docfile = M('doc');
        $res_delete = $docfile->where('doc_id=' . $doc_id)->setField('doc_status', '0');
        $doc_name = $docfile->where('doc_id=' . $doc_id)->getField('doc_name');
        if ($res_delete) {
            writeOperationLog('撤回“' . $doc_name . '”文档', 1);
            return C('COMMON.DOCDEL_SUCCESS');
        } else {
            writeOperationLog('撤回“' . $doc_name . '”文档', 0);
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

    public function saveFileDoc($doc_id) {
        $docfile = M('doc');
        $list = $docfile
                ->join('__MEMBER__ on __DOC__.doc_pub_person = __MEMBER__.uid')
                ->where('doc_id=' . $doc_id)
                ->find();
        $config_info = C();
        if ($config_info['OPEN_FTP'] == 1) {
            $url = C('FTP_VISIT_PATH');
        } else {
            $url = C('FILE_VISIT_PATH');
        }
        $list['doc_upload_file_url'] = $url . $list['doc_upload_file_url'];
        $list['doc_upload_img_url'] = $url . $list['doc_upload_img_url'];
        return $list;
    }

    /*
     * 编辑文件,对文件进行判断上传
     * @Date    2016/09/27
     * @author huanggang
     * @param arrary $param 上传的数据
     * @param $mark 上传的标示
     * @return array $data 返回文件存储路径的数据
     * 
     */

    public function saveUploadNull($param) {
        $upload_obj = new MeetingUplod();
        $config_info = C();
        //判断上传方式
        if ($config_info['OPEN_FTP'] == '1') { //开启ftp上传
            $file_config = $config_info[$param['ftp_type']];
            $result = $upload_obj->ftpUpload($file_config);
            if ($param['mark'] == 0) {
                $data[] = $result['file']['path'];
                $data[] = $result['file1']['path'];
            } else {
                $data = $result[$param['mark']]['path'];
            }
        } else { //普通上传
            $file_config = $config_info[$param['file_type']];
            $result = $upload_obj->normalUpload($file_config);
            if ($param['mark'] == 0) {
                $data[] = $result['rootPath'] . $result['info']['file']['savepath'] . $result['info']['file']['savename'];
                $data[] = $result['rootPath'] . $result['info']['file1']['savepath'] . $result['info']['file1']['savename'];
            } else {
                $data = $result['rootPath'] . $result['info'][$param['mark']]['savepath'] . $result['info'][$param['mark']]['savename'];
            }
        }
        return $data;
    }

    /*
     * 编辑文档
     * @Date    2016/09/22
     * @author huanggang
     * @param array $data 修改后的数据
     * @param $doc_id 修改条件
     * @return object 修改成功或失败
     */

    public function updateFileDoc($data, $doc_id) {
        $docfile = M('doc');
        $res = $docfile->where("doc_id =" . $doc_id)->save($data);
        if (FALSE === $res) {
            writeOperationLog('编辑“' . $data['doc_name'] . '”文档', 0);
            return C('COMMON.ERROR_EDIT');
        } else {
            writeOperationLog('编辑“' . $data['doc_name'] . '”文档', 1);
            return C('COMMON.SUCCESS_EDIT');
        }
    }

    /*
     * 导出execl 查询
     * @Date    2016/09/23
     * @author huanggang
     * @return array data 组合要到导出的数据
     */

    public function getExecl($param) {
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
        $docfile = M('doc');
        $data = $docfile
                ->join('__MEMBER__ on __DOC__.doc_pub_person = __MEMBER__.uid')
                ->where($where)
                ->order('doc_id desc')
                ->field('doc_name,doc_pub_type,name,doc_pub_date,doc_start_date,doc_end_date,doc_root_view,doc_root_do,doc_beizhu')
                ->select();
        foreach ($data as $k => $v) {
            $data[$k]['doc_pub_type'] = $this->getRootView($v['doc_pub_type'], 'doc_pub_type');
            $data[$k]['doc_root_view'] = $this->getRootView($v['doc_root_view'], 'doc_pub_range');
            $data[$k]['doc_root_do'] = $this->getRootView($v['doc_root_do'], 'doc_pub_authority');
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

    public function getRootView($config_vlaue, $config_key) {
        $work = M('config_system')
                ->where(array('config_value' => $config_vlaue, 'config_key' => $config_key))
                ->getField('config_descripion');
        return $work;
    }

}
