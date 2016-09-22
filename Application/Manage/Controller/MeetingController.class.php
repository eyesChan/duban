<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller;

use Manage\Controller\CommonApi\MeetingUpload as MeetingUplod;

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
            $meetingMod = D('meeting');
            if (!empty($_FILES['file']['name'])) {

                $config_info = C();
                //判断上传方式
                if ($config_info['OPEN_FTP'] == '1') { //开启ftp上传
                    $file_config = $config_info['FTP_MEETING'];
                    $result = $upload_obj->ftpUpload($file_config);
                } else { //普通上传
                    $file_config = $config_info['FILE_MEETING'];
                    $result = $upload_obj->normalUpload($file_config);
                }
            }

            //召集人-手输
            if ($data['meeting_callman']['value']) {
                $data['meeting_callman_value'] = implode(',',$data['meeting_callman']['value']);
                unset($data['meeting_callman']['value']);
            }
            //召集人
            $data['meeting_callman'] = implode($data['meeting_callman'], ',');

            //主持人-手输
            if ($data['meeting_moderator']['value']) {
                $data['meeting_moderator_value'] = implode($data['meeting_moderator']['value'], ',');
                unset($data['meeting_moderator']['value']);
            }
            //主持人
            $data['meeting_moderator'] = implode($data['meeting_moderator'], ',');

            //参会人员-手输
            if ($data['meeting_participants']['value']) {
                $data['meeting_participants_value'] = implode($data['meeting_participants']['value'], ',');
                unset($data['meeting_participants']['value']);
            }
            //参会人员
            $data['meeting_participants'] = implode($data['meeting_participants'], ',');

            //会议撰写人-手输
            if ($data['meeting_writeperson']['value']) {
                $data['meeting_writeperson_value'] = implode($data['meeting_writeperson']['value'], ',');
                unset($data['meeting_writeperson']['value']);
            }
            //会议撰写人
            $data['meeting_writeperson'] = implode($data['meeting_writeperson'], ',');

            //物料准备人-手输
            if ($data['meeting_material_madeperson']['value']) {
                $data['meeting_material_madeperson_value'] = implode($data['meeting_material_madeperson']['value'], ',');
                unset($data['meeting_material_madeperson']['value']);
            }
            //物料准备人
            $data['meeting_material_madeperson'] = implode($data['meeting_material_madeperson'], ',');

            //会场布置测试人-手输
            if ($data['meeting_venue_arrangeperson']['value']) {
                $data['meeting_venue_arrangeperson_value'] = implode($data['meeting_venue_arrangeperson']['value'], ',');
                unset($data['meeting_venue_arrangeperson']['value']);
            }
            //会场布置测试人
            $data['meeting_venue_arrangeperson'] = implode($data['meeting_venue_arrangeperson'], ',');

            //餐饮安排负责人-手输
            if ($data['meeting_food_drink']['value']) {
                $data['meeting_food_drink_value'] = implode($data['meeting_food_drink']['value'], ',');
                unset($data['meeting_food_drink']['value']);
            }
            //餐饮安排负责人
            $data['meeting_food_drink'] = implode($data['meeting_food_drink'], ',');

            //会场整理人-手输
            if ($data['meeting_clean_person']['value']) {
                $data['meeting_clean_person_value'] = implode($data['meeting_clean_person']['value'], ',');
                unset($data['meeting_clean_person']['value']);
            }
            //会场整理人
            $data['meeting_clean_person'] = implode($data['meeting_clean_person'], ',');

            //记录整理人-手输
            if ($data['meeting_record_person']['value']) {
                $data['meeting_record_person_value'] = implode($data['meeting_record_person']['value'], ',');
                unset($data['meeting_record_person']['value']);
            }
            //记录整理人
            $data['meeting_record_person'] = implode($data['meeting_record_person'], ',');

            //记录整理人-手输
            if ($data['meeting_record_person']['value']) {
                $data['meeting_record_person_value'] = implode($data['meeting_record_person']['value'], ',');
                unset($data['meeting_record_person']['value']);
            }
            //记录整理人
            $data['meeting_record_person'] = implode($data['meeting_record_person'], ',');
            //会议记录
            $data['meeting_content'] = trim($data['meeting_content']);
//            P($data);die;
            var_dump($meetingMod->add($data));
            echo $meetingMod->getLastSql();
            die;
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
        $this->display();
    }

}
