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

    /**
     *  显示个人日程会议记录
     */
    public function index() {

        $this->display();
    }

    /*
     *  添加会议
     */

    public function addMeeting() {
        $upload_obj = new MeetingUplod();
        $data = I('meeting');
        if (!empty($data)) {
            $meeting_model = new MeetingModel();
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

            $add_flag = $meeting_model->addMeeting($data);
            $lang_info = C('COMMON');
            if ($add_flag) {
                writeOperationLog('添加“' . $data['meeting_name'] . '”会议', 1);
                $this->success($lang_info['SUCCESS_ADD']['status'], U('selectMeeting'));
            }
            writeOperationLog('添加“' . $data['meeting_name'] . '”会议', 0);

            $this->error($lang_info['ERROR_ADD']['status'], U('selectMeeting'));
        }
        $user_info = $upload_obj->getUserInfo();
        $this->assign('user_info', $user_info);

        //会议类型
        $meeting_type_info = getConfigInfo('meeting_type');
        $this->assign('type_info', $meeting_type_info);
        //会议级别
        $meeting_level_info = getConfigInfo('meeting_level');
        $this->assign('level_info', $meeting_level_info);
        //会议形式
        $meeting_form_info = getConfigInfo('meeting_form');
        $this->assign('form_info', $meeting_form_info);
        $this->display();
    }

    /**
     *  会议查询
     */
    public function selectMeeting() {
        $searchInfo = I('seach');
        $config_mod = D('config_system');
        $count = $meeting_info = D('meeting')
                ->where(array('meeting_state' => 1))
                ->count();
        $page = new \Think\Page($count, 2, $param); // 实例化分页类 传入总记录数和每页显示的记录数
        $meeting_info = D('meeting')
                ->where(array('meeting_state' => 1))
                ->field('meeting_id,meeting_name,meeting_type,meeting_date,meeting_place,meeting_moderator,meeting_moderator_value')
                ->order('meeting_id desc')
                ->limit($page->firstRow, $page->listRows)
                ->select();
        //会议主持人 重新赋值
        foreach ($meeting_info as $key => $val) {
            if (!empty($val['meeting_moderator'])) {
                $user_name_info = getUserField($val['meeting_moderator'], 'name');
                $meeting_info[$key]['meeting_moderator'] = implode($user_name_info, ',');
            }
            $meeting_type = $config_mod->where(array('config_value' => $val['meeting_type'], 'config_key' => 'meeting_type', 'config_status' => 1))->getField('config_descripion');
            if (!$meeting_type) {
                $meeting_info[$key]['meeting_type'] = '其他';
            }
            $meeting_info[$key]['meeting_type'] = $meeting_type;
        }
        $this->assign('page', $page->show());
        $this->assign('meeting_info', $meeting_info);
        $this->display();
    }

}
