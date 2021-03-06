<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

/**
 *  Description   会谈会见台账管理
 *  @author       huanggang <gang.huang2@pactera.com>
 *  Date          2016/09/26
 */
class LedgerMeetingController extends AdminController {

    private $ledger_meeting;

    public function __construct() {
        parent::__construct();
        $this->ledger_meeting = D('LedgerMeeting');
    }

    /**
     * 显示会谈会见台账列表
     * @author huang gang
     * @date 2016/09/26
     * @return 跳转页面 Description
     */
    public function index() {
        $param = I();
        if ($param['hiddenform'] == 1) {
            $this->exportLedgerMeeting($param);
        }
        //处理查询条件
        $where = $this->ledger_meeting->getSelectWhere($param);
        $count = $this->ledger_meeting->getLedgerCount($where);
        $page = new \Think\Page($count, 10);
        $list = $this->ledger_meeting->getLedgerList($where, $page->firstRow, $page->listRows);
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
     * 会谈会见台账创建
     * @author huang gang
     * @date 2016/09/26
     * @return 跳转页面 Description
     */
    public function addLedger() {
        $data = I();
        if (!empty($data)) {
            $result = $this->ledger_meeting->addLedger($data);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('LedgerMeeting/index'));
            } else {
                $this->error($result['status'], U('LedgerMeeting/addLedger'));
            }
            return true;
        }
        //会谈会见台账密级 
        $led_dense = getConfigInfo('led_dense');
        $this->assign('led_dense', $led_dense);
        $this->display();
    }

    /*
     * 会谈会见台账详情
     * @author huang gang
     * @date 2016/09/26
     * @return 跳转页面 Description
     */

    public function detailsLedger() {
        $led_meeting_id = I('led_meeting_id');
        $result = $this->ledger_meeting->detailsLedger($led_meeting_id);
        $this->assign('list', $result);
        $this->display();
    }

    /*
     * 
     * 会谈会见台账编辑
     * @author huang gang
     * @date 2016/09/26
     * @return 跳转页面 Description
     */

    public function saveLedger() {
        if (IS_POST) {
            $data = I();
            $result = $this->ledger_meeting->saveLedger($data, $data['led_meeting_id']);
            if ($result['code'] == 200) {
                $this->success($result['status'], U('LedgerMeeting/index'));
            } else {
                $this->error($result['status'], U('LedgerMeeting/saveLedger', array('led_meeting_id' => $data['led_meeting_id'])));
            }
            return true;
        }
        //会谈会见台账密级 
        $led_dense = getConfigInfo('led_dense');
        $this->assign('led_dense', $led_dense);
        $led_meeting_id = I('led_meeting_id');
        $result = $this->ledger_meeting->detailsLedger($led_meeting_id);
        $this->assign('list', $result);
        $this->display();
    }

    /*
     * 会谈会见删除
     * @author huang gang
     * @date 2016/09/26
     * @return 跳转页面 Description
     */

    public function delLedger() {
        $led_meeting_id = I('led_meeting_id');
        $result = $this->ledger_meeting->delLedger($led_meeting_id);
        if ($result['code'] == 200) {
            $this->success($result['status'], U('LedgerMeeting/index'));
        } else {
            $this->error($result['status'], U('LedgerMeeting/index'));
        }
    }

    /**
     * 文档导出
     * @author huang gang
     * @date 2016/09/26
     * @return 跳转页面 Description
     *  
     */
    public function exportLedgerMeeting($param) {
        $work = $this->ledger_meeting->getExecl($param);
        $headArr = array('时间', '地点', '会谈名称', '宾客', '出席领导', '主持人', '密级', '着装', '保障人员', '保障时长',
            '议程责任人', '议程报批情况', '发送会议通知', '通知材料准备', '通知服务单位保障', '准备会议议程',
            '准备背景材料', '准备来访人简历', '准备电脑', '准备录音笔', '准备麦克风', '准备宣传材料', '礼品',
            '准备桌牌', '准备宣传片', '准备静帧画面', '完成场地布置', '测试话筒', '测试音频', '通知接待保障',
            '接待责任人', '保障人员', '传译人员', '差错', '原因分析', '拟写今日海航', '拟写会谈纪要', '发文时间',
            '责任人', '交接单位', '存档地址', '改进建议',
        );
        getExcel($headArr, $work);
    }

    /*
     * 导入
     * @author huang gang
     * @date 2016/09/27
     * @return 跳转页面 Description
     */

    public function importLedgerMeeting() {
        if (!empty($_FILES['filename']['tmp_name'])) {
            $config_info = C();
            $file_config = $config_info['FILE_LEDGER_EXCEL'];
            $upload_obj = new MeetingUplod();
            $files = $upload_obj->normalUpload($file_config);
            $fileName = $files['rootPath'] . $files['info']['filename']['savepath'] . $files['info']['filename']['savename'];
            $resute = importExcel($fileName, 'AP');
            //删除临时文件
            unlink($fileName);
            if (!empty($resute) && $resute['code'] != 100) {
                $result = $this->ledger_meeting->addsLedger($resute);
            } else {
                writeOperationLog('导入“' . 'excel表格模板错误' . '”', 0);
                $this->error($resute['msg'], U('LedgerMeeting/importLedgerMeeting'));
            }
            if ($result['code'] == 200) {
                $this->success($result['status'], U('LedgerMeeting/index'));
            } else {
                $this->error($result['status'], U('LedgerMeeting/importLedgerMeeting'));
            }
            return true;
        }
        $this->display('importLedger');
    }

}
