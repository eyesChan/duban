<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Controller;

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
        $data = I();
        if (!empty($data)) {
            $meetingMod = D('meeting');
            if (!empty($_FILES)) {
                $config_info = (C('COMMON_PARAM'));
                $upload = new \Think\Upload(); // 实例化上传类
                $upload->maxSize = $config_info['FILE_SIZE']; // 设置附件上传大小
                $upload->exts = array('xls', 'xlsx'); // 设置附件上传类型
                $upload->rootPath = $config_info['FILE_PATH']; // 设置附件上传根目录
                $upload->savePath = 'meeting'; // 设置附件上传（子）目录
                if(file_exists($upload->rootPath)){
                    chmod($upload->rootPath, '0777');
                }
                // 上传文件 
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功 获取上传文件信息
                    $orderInfo['ESTIMATED_TIME'] = '';
                    $orderInfo['PAYMENT_DOC_PATH'] = $upload->rootPath .'' . $info['file']['savepath'] . $info['file']['savename'];
                }
            }die;   
            var_dump($meetingMod->add($data['meeting']));
            die;
        }

        //会议类型
        $meeting_type_info = getMeetType();
        $this->assign('type_info', $meeting_type_info);
        //会议级别
        $meeting_level_info = getMeetLevel();
        $this->assign('level_info', $meeting_level_info);
        //会议形式
        $meeting_form_info = getMeetForm();
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
