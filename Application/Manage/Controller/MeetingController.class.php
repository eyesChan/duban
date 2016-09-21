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
        $data = I();
        if (!empty($data)) {
            $meetingMod = D('meeting');
            if (!empty($_FILES)) {
                $upload_obj = new MeetingUplod();
                $config_info = C();
                //判断上传方式
                if($config_info['OPEN_FTP'] == '1'){ //开启ftp上传
                    $file_config = $config_info['FTP_MEETING'];
                    $result = $upload_obj->ftpUpload($file_config);
                    
                }else{ //普通上传
                    $file_config = $config_info['FILE_MEETING'];
                    $result = $upload_obj->normalUpload($file_config);
                }
                if($result['code'] == 100){
                    $this->error('{:U(selectMeeting)}',$result['status']);
                }
            }
            var_dump($meetingMod->add($data['meeting']));
            die;
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


        $this->display();
    }

    /**
     *  会议查询
     */
    public function selectMeeting() {
        $this->display();
    }

}
