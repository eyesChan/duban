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
class ScheduleController extends AdminController {
    /**
     *  显示个人日程会议记录
     */
    public function index(){
        $this->display();
    }
    /*
     *  添加会议
     */
    public function addMeeting(){
        $this->display();
    }
}
