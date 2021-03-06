<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

/**
 *  Description   驻各地发展情况台账管理
 *  @author       huanggang <gang.huang2@pactera.com>
 *  Date          2016/09/20
 */
class ResidentMeetingController extends AdminController {

    private $resident;

    public function __construct() {
        parent::__construct();

        $this->resident = D('ResidentMeeting');
    }

    /**
     * 显示驻各地发展情况台账列表
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */
    public function index() {
        $param = I();
        if ($param['hiddenform'] == 1) {
            $this->exportResident($param);
        }
        //处理查询条件
        $where = $this->resident->getSelectWhere($param);
        $count = $this->resident->getResidentCount($where);
        $page = new \Think\Page($count, 10);
        $list = $this->resident->getResidentList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
        $this->display();
    }

    /**
     * 驻各地发展情况台账创建
     * @author huang gang
     * @date 2016/09/28
     * @return 跳转页面 Description
     */
    public function addResident() {
        $data = I();
        if (!empty($data)) {
            $result = $this->resident->addResident($data);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('ResidentMeeting/index'));
            } else {
                $this->error($result['status'], U('ResidentMeeting/addResident'));
            }
            return true;
        }
        $this->display();
    }

    /*
     * 驻各地发展情况台账详情
     * @author huang gang
     * @date 2016/09/28
     * @return 跳转页面 Description
     */

    public function detailsResident() {
        $resident_id = I('resident_id');
        $result = $this->resident->detailsResident($resident_id);
        $this->assign('list', $result);
        $this->display();
    }

    /*
     * 
     * 驻各地发展情况台账编辑
     * @author huang gang
     * @date 2016/09/29
     * @return 跳转页面 Description
     */

    public function saveResident() {
        if (IS_POST) {
            $data = I();
            $result = $this->resident->saveResident($data, $data['resident_id']);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('ResidentMeeting/index'));
            } else {
                $this->error($result['status'], U('ResidentMeeting/saveResident', array('resident_id' => $data['resident_id'])));
            }
            return true;
        }
        $resident_id = I('resident_id');
        $result = $this->resident->detailsResident($resident_id);
        $this->assign('list', $result);
        $this->display();
    }

    /*
     * 驻各地发展情况台账删除
     * @author huang gang
     * @date 2016/09/29
     * @return 跳转页面 Description
     */

    public function delResident() {
        $resident_id = I('resident_id');
        $result = $this->resident->delResident($resident_id);
        if ($result['code'] == 200) {
            $this->success($result['status'], U('ResidentMeeting/index'));
        } else {
            $this->error($result['status'], U('ResidentMeeting/index'));
        }
    }

    /**
     * 驻各地发展情况台账导出
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     *  
     */
    public function exportResident($param) {
        $work = $this->resident->getExecl($param);
        $headArr = array('地区',
            '国家',
            '省份/城市',
            '更新周期',
            '收集时间',
            '数据时间跨度',
            '责任人',
            '汇总单位',
            '收集通知拟写人',
            '通知是否审批',
            '通知审核人',
            '汇总联系人',
            '通知下发日期',
            '是否提交审核',
            '当前节点',
            '是否存档',
            '存档日期',
            '存档地址',
        );
        getExcel($headArr, $work);
    }

    /*
     * 导入数据
     * @author huang gang
     * @date 2016/09/29
     * @return 跳转页面 Description
     */

    public function importResident() {
        if (!empty($_FILES['filename']['tmp_name'])) {
            $config_info = C();
            $file_config = $config_info['FILE_RESIDENT_EXCEL'];
            $upload_obj = new MeetingUplod();
            $files = $upload_obj->normalUpload($file_config);
            $fileName = $files['rootPath'] . $files['info']['filename']['savepath'] . $files['info']['filename']['savename'];
            $resute = importExcel($fileName, 'R');
            //删除临时文件
            unlink($fileName);
            if (!empty($resute) && $resute['code'] != 100) {
                $result = $this->resident->addsResident($resute);
            } else {
                writeOperationLog('导入“' . 'excel表格模板错误' . '”', 0);
                $this->error($resute['msg'], U('ResidentMeeting/importResident'));
            }
            if ($result['code'] == 200) {
                $this->success($result['status'], U('ResidentMeeting/index'));
            } else {
                $this->error($result['status'], U('ResidentMeeting/importResident'));
            }
            return true;
        }
        $this->display('importResident');
    }

}
