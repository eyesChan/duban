<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;
use Manage\Model\MeetingModel as MeetingModel;

/**
 *  Description   督办后台用日程会议 
 *  @author       lishuaijie <shuaijie.li@pactera.com>
 *  Date          2016/07/05
 */
class MeetingController extends AdminController {

    public $meeting_model;

    public function __construct() {
        parent::__construct();
        $this->meeting_model = new MeetingModel();
    }

    /**
     *  显示个人日程会议记录
     */
    public function index() {
        //根据开始、结束日期计算日历信息生成数组
        $start_time = date('Y-m-01');
        $year = date('Y');
        $month = date('m');
        $m_info = array(1, 3, 5, 7, 8, 10, 12);
        if (in_array($start_time, $m_info)) {
            $end_time = date('Y-m-31');
        } else {
            $end_time = date('Y-m-30');
        }
        if ($month == 2) {
            if ($year % 4 == 0 && $year % 100 !== 0 || $year % 400 == 0) {
                $end_time = date('Y-m-29');
            } else {
                $end_time = date('Y-m-28');
            }
        }
        $week = $this->meeting_model->getDate($start_time, $end_time);
        $meeting_info = $this->meeting_model->getMeetingByMonth($start_time, $end_time);
        $this->assign('meeting_info', $meeting_info);
        $this->assign('week', $week);
        $this->display();
    }

    /*
     *  添加会议
     * @author llishuaijei
     * @date 2016/09/24
     * @return 跳转页面
     */

    public function addMeeting() {
        $upload_obj = new MeetingUplod();
        $data = I('meeting');
        if (!empty($data)) {
            if (!empty($_FILES['file']['name'])) {
                $config_info = C();
                //判断上传方式
                if ($config_info['OPEN_FTP'] == '1') { //开启ftp上传
                    $file_config = $config_info['FTP_MEETING'];
                    $result = $upload_obj->ftpUpload($file_config);
                    $path = $result['file']['path'];
                } else { //普通上传
                    $file_config = $config_info['FILE_MEETING'];
                    $result = $upload_obj->normalUpload($file_config);
                    $path = $result['rootPath'] . $result['info']['file']['savepath'] . $result['info']['file']['savename'];
                }
                $data['meeting_annexes_url'] = $path;
            }

            $add_flag = $this->meeting_model->addMeeting($data);
            $lang_info = C('COMMON');
            if ($add_flag) {
                $this->meeting_model->sendMeetingEmail($add_flag);

                writeOperationLog('添加“' . $data['meeting_name'] . '”会议', 1);
                $this->success($lang_info['SUCCESS_ADD']['status'], U('selectMeeting'));
            }
            writeOperationLog('添加“' . $data['meeting_name'] . '”会议', 0);

            $this->error($lang_info['ERROR_ADD']['status'], U('selectMeeting'));
        }
        $user_info = $upload_obj->getUserInfo();
        $this->assign('user_info', $user_info);
        //获取台帐管理员
        $account_info = $this->meeting_model->getAccount();
        //会议类型
        $meeting_type_info = getConfigInfo('meeting_type');
        $this->assign('type_info', $meeting_type_info);
        //会议级别
        $meeting_level_info = getConfigInfo('meeting_level');
        $this->assign('level_info', $meeting_level_info);
        //会议形式
        $meeting_form_info = getConfigInfo('meeting_form');
        $this->assign('form_info', $meeting_form_info);
        //台帐管理员
        $this->assign('account_info', $account_info);
        $this->display();
    }

    /**
     *  会议查询
     * @author lishuaijie
     * @date 2016/09/24
     * @return 跳转页面 Description
     */
    public function selectMeeting() {
        $searchInfo = I();
        if (I('hiddenform') == 1) {
            $this->getExcel($searchInfo);
        }
        $config_mod = D('config_system');
        $count = $this->meeting_model->selectMeetingCount($searchInfo);
        $page = new \Think\Page($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $meeting_info = $this->meeting_model->selectMeeting($searchInfo, $page->firstRow, $page->listRows);
        //会议主持人 重新赋值
        foreach ($meeting_info as $key => $val) {
            if (!empty($val['meeting_moderator'])) {
                $user_name_info = getUserField($val['meeting_moderator'], 'name');
                $meeting_info[$key]['meeting_moderator'] = implode($user_name_info, ',');
            }
            $meeting_type = $config_mod->where(array('config_value' => $val['meeting_type'], 'config_key' => 'meeting_type', 'config_status' => 1))->getField('config_descripion');
            $meeting_info[$key]['meeting_type'] = $meeting_type;
        }
        foreach ($searchInfo as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $this->assign('search', $searchInfo);
        $this->assign('page', $page->show());
        $this->assign('meeting_info', $meeting_info);
        $this->display();
    }

    /**
     *  会议编辑
     * @param meeting_id 会议id
     * @date 2016/09/26
     * @return 跳转页面 Description
     */
    public function edit() {
        $upload_obj = new MeetingUplod();

        $meeting_info = I('meeting');
        $meeting_id = I('meeting_id');
        if ($meeting_id && empty($meeting_info)) {
            $upload_obj = new MeetingUplod();
            $meeting_info = $this->meeting_model->where(array('meeting_id' => $meeting_id, 'meeting_state' => 1))->find();
            $user_info = $upload_obj->getUserInfo();
            $this->assign('user_info', $user_info);
            $meeting_info = $this->meeting_model->editData($meeting_info);
            //会议类型
            $meeting_type_info = getConfigInfo('meeting_type');
            $this->assign('type_info', $meeting_type_info);
            //会议级别
            $meeting_level_info = getConfigInfo('meeting_level');
            $this->assign('level_info', $meeting_level_info);
            //会议形式
            $meeting_form_info = getConfigInfo('meeting_form');
            $this->assign('form_info', $meeting_form_info);
            $meeting_info['file_name'] = pathinfo($meeting_info['meeting_annexes_url'])['filename'];
            $this->assign('meeting_info', $meeting_info);
            //获取台帐管理员
            $account_info = $this->meeting_model->getAccount();
            $this->assign('account_info', $account_info);
            $this->display('addMeeting');
        } else {
            if (!empty($_FILES['file']['name'])) {
                $config_info = C();
                //判断上传方式
                if ($config_info['OPEN_FTP'] == '1') { //开启ftp上传
                    $file_config = $config_info['FTP_MEETING'];
                    $result = $upload_obj->ftpUpload($file_config);
                    $path = $result['file']['path'];
                } else { //普通上传
                    $file_config = $config_info['FILE_MEETING'];
                    $result = $upload_obj->normalUpload($file_config);
                    $path = $result['rootPath'] . $result['info']['file']['savepath'] . $result['info']['file']['savename'];
                }
                $meeting_info['meeting_annexes_url'] = $path;
            }
            $save_flag = $this->meeting_model->addMeeting($meeting_info, $meeting_id);
            if ($save_flag !== false) {
                $this->meeting_model->sendMeetingEmail($meeting_id);
                writeOperationLog('修改“' . $meeting_info['meeting_name'] . '”会议', 1);
                $this->success(C('COMMON.SUCCESS_EDIT')['status'], U('/Manage/Meeting/selectMeeting'));
            } else {
                writeOperationLog('修改“' . $meeting_info['meeting_name'] . '”会议', 0);
                $this->error(C('COMMON.ERROR_EDIT.status')['status'], U('/Manage/Meeting/selectMeeting'));
            }
        }
    }

    /**
     *  会议详情
     * @param meeting_id 会议id
     * @author lishuaijie
     * @return 显示页面 Description
     * @date  2016/09/26
     */
    public function showMeeting() {
        $meeting_id = I('meeting_id');
        $config_mod = D('config_system');
        $meeting_info = $this->meeting_model->where(array('meeting_id' => $meeting_id, 'meeting_state' => 1))->find();
        $meeting_info = $this->meeting_model->editData($meeting_info);
        $meeting_info['file_name'] = pathinfo($meeting_info['meeting_annexes_url'])['filename'];
        //会议类型
        $meeting_info['meeting_type'] = $config_mod
                ->where(array('config_key' => 'meeting_type', 'config_value' => $meeting_info['meeting_type']))
                ->getField('config_descripion');
        //会议级别
        $meeting_info['meeting_level'] = $config_mod
                ->where(array('config_key' => 'meeting_level', 'config_value' => $meeting_info['meeting_level']))
                ->getField('config_descripion');
        //会议形式
        $meeting_info['meeting_form'] = $config_mod
                ->where(array('config_key' => 'meeting_form', 'config_value' => $meeting_info['meeting_form']))
                ->getField('config_descripion');
        $meeting_info['meeting_ledger_re_person'] = D('member')->where(array('uid' => $meeting_info['meeting_ledger_re_person']))->getField('name');
        $this->assign('meeting_info', $meeting_info);
        $this->display();
    }

    /**
     * 删除会议
     * @param meeting_id 会议id 
     * @author lishuaijie
     * @return true/false Description
     * @date 2016/09/26
     */
    public function delMeeting() {
        $meeting_id = I('meeting_id');
        $work_mod = D('worksheet');
        $this->meeting_model->startTrans();
        $meeting_save_flag = $this->meeting_model->where(array('meeting_id' => $meeting_id))->save(array('meeting_state' => 0));
        $work_order_info = $work_mod->where(array('worksheet_relate_meeting' => $meeting_id, 'worksheet_detele' => 1))->getField('worksheet_id', true);
        if (empty($work_order_info)) {
            $work_save_flag = true;
        } else {
            $work_order_id = implode(',', $work_order_info);
            $work_save_flag = $work_mod->where(array('worksheet_id' => array('in', $work_order_id)))->save(array('worksheet_detele' => 0));
        }
        if ($meeting_save_flag !== false && $work_save_flag !== false) {
            $this->meeting_model->commit();
            writeOperationLog('删除“' . $meeting_info['meeting_name'] . '”会议', 1);

            $this->ajaxReturn(C('COMMON.SUCCESS_EDIT'));
        }
        $this->meeting_model->rollback();
        writeOperationLog('删除“' . $meeting_info['meeting_name'] . '”会议', 0);

        $this->ajaxReturn(C('COMMON.ERROR_EDIT'));
    }

    /**
     * 导出excel
     * @param 查询参数 $name Description
     * @author lishuaijie
     * @return true/false Description
     * @date 2016/09/17
     */
    public function getExcel($info) {
        $user_mod = D('member');

        $meeting_info = $this->meeting_model->selectMeeting($info, 0, 10000);
        $header = array(
            '序号', '会议名称', '会议类型', '会议级别', '召集人', '主持人', '参会人员', '会议形式', '参会规模',
            '会议日期', '会议时刻', '会议地点', '交办日期', '会议时长', '会议通知拟写', '通知发出日期', '通知时刻', '会议材料收集',
            '收集时间', '材料提交时间', '物料准备', '会场布置与调试', '测试日期', '测试时间', '问题明细', '是否解决', '现场保障',
            '会议摄影摄像', '会议结束日期', '会议结束时刻', '餐饮安排', '会场整理人', '相关文字', '整理人',
            '台账整理人',
        );
        $config_mod = D('config_system');
        $list = array();
        foreach ($meeting_info as $key => $val) {
            $data['序号'] = $key + 1;
            $data['会议名称'] = $val['meeting_name'];
            $data['会议类型'] = $config_mod->where(array('config_key' => 'meeting_type', 'config_value' => $val['meeting_type']))->getField('config_descripion');
            $data['会议级别'] = $config_mod->where(array('config_key' => 'meeting_level', 'config_value' => $val['meeting_level']))->getField('config_descripion');

            //召集人
            if (!empty($val['meeting_callman_name'])) {
                $callman = explode(',', $val['meeting_callman_name']);
            }
            if (!empty($val['meeting_callman_value'])) {
                $callman1 = explode(',', $val['meeting_callman_value']);
            }
            if (!empty($callman)) {
                $callman_info = $callman;
            }
            if (!empty($callman1)) {
                $callman_info = $callman1;
            }
            if (!empty($callman) && !empty($callman1)) {
                $callman_info = array_merge($callman, $callman1);
            }

            $data['召集人'] = implode(',', $callman_info);
            //会议主持人
            $meeting_moderator_name = $this->meeting_model->dealtData($val, 'meeting_moderator');
            $data['主持人'] = $meeting_moderator_name;
            //参会人员
            $meeting_participants_name = $this->meeting_model->dealtData($val, 'meeting_participants');
            $data['参会人员'] = $meeting_participants_name;
            $data['会议形式'] = $config_mod->where(array('config_key' => 'meeting_form', 'config_value' => $val['meeting_form']))->getField('config_descripion');
            $data['参会规模'] = $val['meeting_scale'];
            $data['会议日期'] = $val['meeting_date'];
            $data['会议时刻'] = $val['meeting_time'];
            $data['会议地点'] = $val['meeting_place'];
            $data['交办日期'] = $val['meeting_assigned_date'];
            $data['会议时长'] = $val['meeting_timelong'];
            $data['会议通知拟写'] = $this->meeting_model->dealtData($val, 'meeting_writeperson');
            $data['通知发出日期'] = $val['meeting_sendnotice_date'];
            $data['通知时刻'] = $val['meeting_notice_date'];
            $data['会议材料收集'] = $val['meeting_material_collect_person'];
            $data['收集时间'] = $val['meeting_material_collect_date'];
            $data['材料提交时间'] = $val['meeting_material_send_date'];
            $data['物料准备'] = $this->meeting_model->dealtData($val, 'meeting_material_madeperson');
            $data['会场布置与调试'] = $this->meeting_model->dealtData($val, 'meeting_venue_arrangeperson');
            $data['测试日期'] = $val['meeting_try_date'];
            $data['测试时间'] = $val['meeting_try_time'];
            $data['问题明细'] = $val['meeting_qusetion_detail'];
            if ($val['meeting_fix_state'] == 1) {
                $state = '解决';
            }
            if ($val['meeting_fix_state'] == 2) {
                $state = '未解决';
            }
            if ($val['meeting_fix_state'] == 3) {
                $state = '处理中';
            }
            $data['是否解决'] = $state;
            $data['现场保障'] = '';
            $data['会议摄影摄像'] = $this->meeting_model->dealtData($val, 'meeting_vedio');
            $data['会议结束日期'] = $val['meeting_end_date'];
            $data['会议结束时刻'] = $val['meeting_end_time'];
            $data['餐饮安排'] = $this->meeting_model->dealtData($val, 'meeting_food_drink');
            $data['会场整理人'] = $this->meeting_model->dealtData($val, 'meeting_clean_person');
            $data['相关文字'] = $val['meeting_content'];
            $data['整理人'] = $this->meeting_model->dealtData($val, 'meeting_record_person');
            $data['台账整理人'] = $user_mod->where('uid = ' . $val['meeting_ledger_re_person'])->getField('name');
            $list[] = $data;
        }
        getExcel($header, $list);
    }

    /**
     * 导入会议记录 excel
     * @author lishuaijie
     * @return true/false Description
     * @date 2016/09/28
     */
    public function importExcel() {
        if (!empty($_FILES['file']['name'])) {
            $upload_obj = new MeetingUplod();
            $config_info = C();
            $file_config = $config_info['FILE_IMPORT_EXCEL'];
            $info = $upload_obj->importExcel($file_config);
            $path = $info['rootPath'] . $info['info']['file']['savepath'] . $info['info']['file']['savename'];
            echo $path;
            die;
            $list = importExcel($path, null);
            P($list);
        }


        $this->display();
    }

}
