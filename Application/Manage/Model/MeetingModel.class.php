<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Model;

use Think\Model;

/**
 *  Description   会议的管理操作
 *  @author       lishuaijie <shuaijie.li@pactera.com>
 *  Date          2016/09/23
 */
class MeetingModel extends Model {

    /**
     *  会议添加
     * @param $data 会议相关参数
     * @author lishuajie
     * @param meeting_id 会议id
     * @return 最后一条添加id Description
     */
    public function addMeeting($data, $meeting_id = false) {
        $user_mod = D('member');
        if (!empty($data)) {
            //召集人-手输
            if ($data['meeting_callman']['value']) {
                $data['meeting_callman_value'] = implode(',', $data['meeting_callman']['value']);
                unset($data['meeting_callman']['value']);
            }
            //召集人
            $data['meeting_callman'] = implode($data['meeting_callman'], ',');
            //召集人系统用户名称
            $user_name_info = $user_mod->where("uid in ($data[meeting_callman]) and status = 1")->getField('name', true);
            $callman_name = implode(',', $user_name_info);
            $data['meeting_callman_name'] = $callman_name;

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

//            //餐饮安排负责人-手输
//            if ($data['meeting_food_drink']['value']) {
//                $data['meeting_food_drink_value'] = implode($data['meeting_food_drink']['value'], ',');
//                unset($data['meeting_food_drink']['value']);
//            }
//            //餐饮安排负责人
//            $data['meeting_food_drink'] = implode($data['meeting_food_drink'], ',');

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

            //会议摄影摄像-手输
            if ($data['meeting_vedio']['value']) {
                $data['meeting_vedio_value'] = implode($data['meeting_vedio']['value'], ',');
                unset($data['meeting_vedio']['value']);
            }
            //会议摄影摄像
            $data['meeting_vedio'] = implode($data['meeting_vedio'], ',');

            //会议记录
            $data['meeting_content'] = trim($data['meeting_content']);
            //会议状态
            $data['meeting_state'] = 1;
            foreach ($data as $key => $val) {
                if (empty($val)) {
                    unset($data[$key]);
                }
            }
            if (!empty($meeting_id)) {
                $add_flag = D('meeting')->where(array('meeting_id' => $meeting_id))->save($data);
            } else {
                $add_flag = D('meeting')->add($data);
            }
            if ($add_flag !== false) {
                return $add_flag;
            }
        }
        return false;
    }

    /**
     *  会议列表查询
     * @param $data 搜索条件
     * @param $start 开始页数
     * @param $length 每页条数
     * @author lishuaijie
     * @return 查询数据 Description
     */
    public function selectMeeting($searchInfo, $start, $length) {
        $where = array('meeting_state' => 1);
        if (!empty($searchInfo)) {
            //会议名称
            if (!empty($searchInfo['meeting_name'])) {
                $where['meeting_name'] = array('like', '%' . $searchInfo['meeting_name'] . '%');
            }
            //会议日期
            if (!empty($searchInfo['meeting_date'])) {
                $where['meeting_date'] = array('eq', $searchInfo['meeting_date']);
            }
            if (!empty($searchInfo['meeting_callman'])) {
                $sql = ' meeting_callman_value like "' . $searchInfo['meeting_callman'] . '" or  meeting_callman_name like "%' . $searchInfo['meeting_callman'] . '%"';
            }
        }
        if(empty($sql)){
            $sql = '1=1';
        }
        $meeting_info = D('meeting')
                ->where($where)
                ->field('meeting_id,meeting_name,meeting_type,meeting_date,meeting_place,meeting_moderator,meeting_moderator_value')
                ->where($sql)
                ->order('meeting_id desc')
                ->limit($start, $length)
                ->select();
            return $meeting_info;
    }

    /**
     *  会议列表条数
     * @param $data 搜索条件
     *  
     * @author lishuaijie
     * @return 条数 Description
     */
    public function selectMeetingCount($searchInfo) {
        $where = array('meeting_state' => 1);
        if (!empty($searchInfo)) {
            //会议名称
            if (!empty($searchInfo['meeting_name'])) {
                $where['meeting_name'] = array('like', '%' . $searchInfo['meeting_name'] . '%');
            }
            //会议日期
            if (!empty($searchInfo['meeting_date'])) {
                $where['meeting_date'] = array('eq', $searchInfo['meeting_date']);
            }
            //召集人
            if (!empty($searchInfo['meeting_callman'])) {
                $sql = ' meeting_callman_value like "' . $searchInfo['meeting_callman'] . '" or  meeting_callman_name like "%' . $searchInfo['meeting_callman'] . '%"';
            }
        }
        if(empty($sql)){
            $sql = '1=1';
        }
        $meeting_count = D('meeting')
                ->where($where)
                ->where($sql)
                ->count();
        return $meeting_count;
    }

    /**
     *  编辑页面数据处理
     * @param $meeting_info 单挑会议信息
     * @author lishuaijie
     * @date 2016/09/26
     * @return array 处理后的数据
     */
    public function editData($meeting_info) {
        $user_mod = D('member');

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
        if(!empty($meeting_info['meeting_annexes_url'])){
            $config_info = C();
            if($config_info['OPEN_FTP'] ==1 ){
                $url = C('FTP_VISIT_PATH');
            }else{
                $url = C('FTP_VISIT_PATH');
            }
            $meeting_info['meeting_annexes_url'] = $url . $meeting_info['meeting_annexes_url'];
        }
        return $meeting_info;
    }

}