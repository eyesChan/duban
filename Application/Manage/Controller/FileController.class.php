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
        if ($param['hiddenform'] == 1) {
            $this->exportFile($param);
        }
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
            $res = $this->filedoc->fileSize($_FILES);
            if ($res['status'] != 100 && $res['status'] != 101) {
                $file_doc = $this->filedoc->saveUploadNull($res);
                $data['doc_upload_file_url'] = $file_doc[0];
                $data['doc_upload_img_url'] = $file_doc[1];
                $result = $this->filedoc->addFile($data);
                if ($result['code'] == 200) {
                    $this->success($result['status'], U('File/index'));
                } else {
                    $this->error($result['status'], U('File/addFile'));
                }
                return true;
            }
            $this->error($res['msg'], U('File/addFile'));
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
        //上传文件提示
        $config_info = C();
        if ($config_info['OPEN_FTP'] == '1') {
            $file_config = $config_info['FIP_DOC'];
        } else {
            $file_config = $config_info['FILE_DOC'];
        }
        $allow_file = $file_config['ALLOW_FILE'];
        $this->assign('doc_type', implode(' , ', $allow_file));
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
            $this->success($result['status'], U('File/index'));
        } else {
            $this->error($result['status'], U('File/index'));
        }
    }

    /*
     * 文档详情
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */

    public function detailsFile() {
        $doc_id = I('doc_id');
        $result = $this->filedoc->saveFileDoc($doc_id);
        $result['doc_upload_file_name'] = pathinfo($result['doc_upload_file_url'])['filename'];
        $result['doc_upload_img_name'] = pathinfo($result['doc_upload_img_url'])['filename'];
        //文档发布类型 
        $result['doc_pub_type'] = $this->filedoc->getRootView($result['doc_pub_type'], 'doc_pub_type');
        //文档发布部门
        $result['doc_dept_id'] = $this->filedoc->getRootView($result['doc_dept_id'], 'doc_pub_depart');
        //文档可见范围
        $result['doc_root_view'] = $this->filedoc->getRootView($result['doc_root_view'], 'doc_pub_range');
        //文档权限设定
        $result['doc_root_do'] = $this->filedoc->getRootView($result['doc_root_do'], 'doc_pub_authority');
        $this->assign('list', $result);
        $this->display();
    }

    /**
     *  文档编辑
     * @author huang gang
     * @date 2016/09/22
     * @return 跳转页面 Description
     *  
     */
    public function saveFile() {
        if (IS_POST) {
            $data = I('post.');
            if (!empty($_FILES['file']['tmp_name']) && !empty($_FILES['file1']['tmp_name'])) {
                $res = $this->filedoc->fileSize($_FILES);
                if ($res['status'] != 100) {
                    $file_doc = $this->filedoc->saveUploadNull($res);
                    $data['doc_upload_file_url'] = $file_doc[0];
                    $data['doc_upload_img_url'] = $file_doc[1];
                } else {
                    $this->error($res['msg'], U('File/saveFile', array('doc_id' => $data['doc_id'])));
                }
            } else {
                $fileName = $this->filedoc->fileSaveSize($_FILES);
                $result = $this->filedoc->saveUploadNull($fileName);
                if ($fileName['mark'] == 'file') {
                    $data['doc_upload_file_url'] = $result[0];
                } elseif ($fileName['mark'] == 'file1') {
                    $data['doc_upload_img_url'] = $result[1];
                }
            }
            $result = $this->filedoc->updateFileDoc($data, $data['doc_id']);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('File/index'));
            } else {
                $this->error($result['status'], U('File/saveFile', array('doc_id' => $data['doc_id'])));
            }
            return true;
        }
        $doc_id = I('doc_id');
        $result = $this->filedoc->saveFileDoc($doc_id);
        //展示文件名
        $result['doc_upload_file_name'] = pathinfo($result['doc_upload_file_url'])['filename'];
        $result['doc_upload_img_name'] = pathinfo($result['doc_upload_img_url'])['filename'];
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
        //上传文件提示
        $config_info = C();
        if ($config_info['OPEN_FTP'] == '1') {
            $file_config = $config_info['FIP_DOC'];
        } else {
            $file_config = $config_info['FILE_DOC'];
        }
        $allow_file = $file_config['ALLOW_FILE'];
        $this->assign('doc_type', implode(' , ', $allow_file));
        $this->display();
    }

    /**
     * 文档导出
     * @author huang gang
     * @date 2016/09/22
     * @return 跳转页面 Description
     *  
     */
    public function exportFile($param) {
        $work = $this->filedoc->getExecl($param);
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
