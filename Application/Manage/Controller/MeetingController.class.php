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
     * @author lishuaijie
     * @date 2016/09/24
     * @return 跳转页面 Description
     */
    public function selectMeeting() {
        $searchInfo = I();
        $config_mod = D('config_system');
        $count = $this->meeting_model->selectMeetingCount($searchInfo);
        $page = new \Think\Page($count, 3); // 实例化分页类 传入总记录数和每页显示的记录数
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
        $meeting_info = I('meeting');
        $meeting_id = I('meeting_id');
        $user_mod = D('member');
        if ($meeting_id && empty($meeting_info)) {
            $upload_obj = new MeetingUplod();
            $meeting_info = $this->meeting_model->where(array('meeting_id' => $meeting_id, 'meeting_state' => 1))->find();
            $user_info = $upload_obj->getUserInfo();
            $this->assign('user_info', $user_info);
           
                if (!empty($meeting_info['meeting_callman'])) {
                    //召集人
                    $meeting_callman = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_callman'])))->getField('uid,name', true);
                    $meeting_info['meeting_callman'] = $meeting_callman;
                    //召集人手写
                    $meeting_info['meeting_callman_value'] = explode(',', $meeting_info['meeting_callman_value']);
                }

                if (!empty($meeting_info['meeting_moderator'])) {
                    //会议主持人 
                    $meeting_moderator = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_moderator'])))->getField('uid,name', true);
                    $meeting_info['meeting_moderator'] = $meeting_moderator;
                    //会议主持人 手输 meeting_moderator
                    $meeting_info['meeting_moderator_value'] = explode(',', $meeting_info['meeting_moderator_value']);
                }
                if (!empty($meeting_info['meeting_participants'])) {
                    //参会人员 
                    $meeting_participants = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_participants'])))->getField('uid,name', true);
                    $meeting_info['meeting_participants'] = $meeting_participants;
                    //参会人员 手输 meeting_participants_value
                    $meeting_info['meeting_participants_value'] = explode(',', $meeting_info['meeting_participants_value']);
                }

                if (!empty($meeting_info['meeting_writeperson'])) {
                    //会议通知撰写人 meeting_writeperson 
                    $meeting_writeperson = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_writeperson'])))->getField('uid,name', true);
                    $meeting_info['meeting_writeperson'] = $meeting_writeperson;
                    //会议通知 撰写人 手输 meeting_writeperson
                    $meeting_info['meeting_writeperson_value'] = explode(',', $meeting_info['meeting_writeperson_value']);
                }

                if (!empty($meeting_info['meeting_material_madeperson'])) {
                    //物料准备人 meeting_material_madeperson 
                    $meeting_material_madeperson = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_material_madeperson'])))->getField('uid,name', true);
                    $meeting_info['meeting_material_madeperson'] = $meeting_material_madeperson;
                    //物料准备人 手输 meeting_material_madeperson
                    $meeting_info['meeting_material_madeperson_value'] = explode(',', $meeting_info['meeting_material_madeperson_value']);
                }

                if (!empty($meeting_info['meeting_venue_arrangeperson'])) {
                    //会场调试布置人 meeting_venue_arrangeperson 
                    $meeting_venue_arrangeperson = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_venue_arrangeperson'])))->getField('uid,name', true);
                    $meeting_info['meeting_venue_arrangeperson'] = $meeting_venue_arrangeperson;
                    //会场调试布置人 手输 meeting_material_madeperson
                    $meeting_info['meeting_venue_arrangeperson_value'] = explode(',', $meeting_info['meeting_venue_arrangeperson_value']);
                }
                if (!empty($meeting_info['meeting_vedio'])) {
                    //会议摄影摄像 meeting_vedio 
                    $meeting_vedio = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_vedio'])))->getField('uid,name', true);
                    $meeting_info['meeting_vedio'] = $meeting_vedio;
                    //会场调试布置人 手输 meeting_vedio
                    $meeting_info['meeting_vedio_value'] = explode(',', $meeting_info['meeting_vedio_value']);
                }
//                if (!empty($meeting_info['meeting_food_drink'])) {
//                    //餐饮安排 meeting_food_drink 
//                    $meeting_food_drink = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_food_drink'])))->getField('uid,name', true);
//                    $meeting_info['meeting_food_drink'] = $meeting_food_drink;
//                    //餐饮安排 手输 meeting_food_drink
//                    $meeting_info['meeting_food_drink_value'] = explode(',', $meeting_info['meeting_food_drink_value']);
//                }

                if (!empty($meeting_info['meeting_clean_person'])) {
                    //会场整理人 meeting_clean_person 
                    $meeting_clean_person = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_clean_person'])))->getField('uid,name', true);
                    $meeting_info['meeting_clean_person'] = $meeting_clean_person;
                    //会场整理人 手输 meeting_clean_person
                    $meeting_info['meeting_clean_person_value'] = explode(',', $meeting_info['meeting_clean_person_value']);
                }
                if (!empty($meeting_info['meeting_record_person'])) {
                    //记录整理人 meeting_record_person 
                    $meeting_record_person = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_record_person'])))->getField('uid,name', true);
                    $meeting_info['meeting_record_person'] = $meeting_record_person;
                    //会场整理人 手输 meeting_clean_person
                    $meeting_info['meeting_record_person_value'] = explode(',', $meeting_info['meeting_record_person_value']);
                }
            //会议类型
            $meeting_type_info = getConfigInfo('meeting_type');
            $this->assign('type_info', $meeting_type_info);
            //会议级别
            $meeting_level_info = getConfigInfo('meeting_level');
            $this->assign('level_info', $meeting_level_info);
            //会议形式
            $meeting_form_info = getConfigInfo('meeting_form');
            $this->assign('form_info', $meeting_form_info);
            $this->assign('meeting_info', $meeting_info);

            $this->display('addMeeting');
        }
    }

}
