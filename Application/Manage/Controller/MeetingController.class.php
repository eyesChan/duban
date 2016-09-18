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
