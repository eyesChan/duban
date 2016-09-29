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
            if (!empty($data['meeting_callman'])) {
                $user_name_info = $user_mod->where("uid in ($data[meeting_callman]) and status = 1")->getField('name', true);
                $callman_name = implode(',', $user_name_info);
                $data['meeting_callman_name'] = $callman_name;
                //关联表信息
                $callman_info = explode(',', $data['meeting_callman']);
            }
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
            //关联表信息
            $meeting_participants_info = $data['meeting_participants'];
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

            //现场保证人-手输
            if ($data['meeting_site_protection']['value']) {
                $data['meeting_site_protection_value'] = implode($data['meeting_site_protection']['value'], ',');
                unset($data['meeting_site_protection']['value']);
            }

            //现场保证人
            $data['meeting_site_protection'] = implode($data['meeting_site_protection'], ',');

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
                $this->meetingCallman($meeting_id, $callman_info);
                $this->meetingParticipants($meeting_id, $meeting_participants_info);
            } else {
                $add_flag = D('meeting')->add($data);
                $this->meetingCallman($add_flag, $callman_info);
                $this->meetingParticipants($add_flag, $meeting_participants_info);
            }
            if ($add_flag !== false) {
                return $add_flag;
            }
        }
        return false;
    }

    /**
     * 添加会议 召集人关联表
     * @param meeting_id 会议id
     * @param callman_info 召集人id
     * @return true/false Description
     * @anthor lishuaijie
     * @date 2016/09/27
     */
    public function meetingCallman($meeting_id, $callman_info) {
        $meeting_callman_mod = D('meeting_callman');
        //更新之前先删除
        $meeting_callman_mod->where(array('meeting_id' => $meeting_id))->delete();
        if (!empty($callman_info)) {
            foreach ($callman_info as $val) {
                $meeting_callman_mod->add(array('meeting_id' => $meeting_id, 'uid' => $val));
            }
        }
        return true;
    }

    /**
     * 添加会议 参会人员关联表
     * @param meeting_id 会议id
     * @param $meeting_participants_info 参会人id
     * @return true/false Description
     * @anthor lishuaijie
     * @date 2016/09/27
     */
    public function meetingParticipants($meeting_id, $meeting_participants_info) {
        $meeting_participants_mod = D('meeting_participants');
        //更新之前先删除
        $meeting_participants_mod->where(array('meeting_id' => $meeting_id))->delete();
        if (!empty($meeting_participants_info)) {
            foreach ($meeting_participants_info as $val) {
                $meeting_participants_mod->add(array('meeting_id' => $meeting_id, 'meeting_participants' => $val));
            }
        }
        return true;
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
        if (empty($sql)) {
            $sql = '1=1';
        }
        $meeting_info = D('meeting')
                ->where($where)
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
        if (empty($sql)) {
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
        }
        //召集人手写
        if (!empty($meeting_info['meeting_callman_value']))
            $meeting_info['meeting_callman_value'] = explode(',', $meeting_info['meeting_callman_value']);

        if (!empty($meeting_info['meeting_moderator'])) {
            //会议主持人 
            $meeting_moderator = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_moderator'])))->getField('uid,name', true);
            $meeting_info['meeting_moderator'] = $meeting_moderator;
        }
        //会议主持人 手输 meeting_moderator
        if (!empty($meeting_info['meeting_moderator_value']))
            $meeting_info['meeting_moderator_value'] = explode(',', $meeting_info['meeting_moderator_value']);
        if (!empty($meeting_info['meeting_participants'])) {
            //参会人员 
            $meeting_participants = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_participants'])))->getField('uid,name', true);
            $meeting_info['meeting_participants'] = $meeting_participants;
        }
        //参会人员 手输 meeting_participants_value
        if (!empty($meeting_info['meeting_participants_value']))
            $meeting_info['meeting_participants_value'] = explode(',', $meeting_info['meeting_participants_value']);

        if (!empty($meeting_info['meeting_writeperson'])) {
            //会议通知撰写人 meeting_writeperson 
            $meeting_writeperson = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_writeperson'])))->getField('uid,name', true);
            $meeting_info['meeting_writeperson'] = $meeting_writeperson;
        }
        //会议通知 撰写人 手输 meeting_writeperson
        if (!empty($meeting_info['meeting_writeperson_value']))
            $meeting_info['meeting_writeperson_value'] = explode(',', $meeting_info['meeting_writeperson_value']);

        if (!empty($meeting_info['meeting_material_madeperson'])) {
            //物料准备人 meeting_material_madeperson 
            $meeting_material_madeperson = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_material_madeperson'])))->getField('uid,name', true);
            $meeting_info['meeting_material_madeperson'] = $meeting_material_madeperson;
        }
        //物料准备人 手输 meeting_material_madeperson
        if (!empty($meeting_info['meeting_material_madeperson_value']))
            $meeting_info['meeting_material_madeperson_value'] = explode(',', $meeting_info['meeting_material_madeperson_value']);

        if (!empty($meeting_info['meeting_venue_arrangeperson'])) {
            //会场调试布置人 meeting_venue_arrangeperson 
            $meeting_venue_arrangeperson = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_venue_arrangeperson'])))->getField('uid,name', true);
            $meeting_info['meeting_venue_arrangeperson'] = $meeting_venue_arrangeperson;
        }
        //会场调试布置人 手输 meeting_material_madeperson
        if (!empty($meeting_info['meeting_venue_arrangeperson_value']))
            $meeting_info['meeting_venue_arrangeperson_value'] = explode(',', $meeting_info['meeting_venue_arrangeperson_value']);
        if (!empty($meeting_info['meeting_vedio'])) {
            //会议摄影摄像 meeting_vedio 
            $meeting_vedio = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_vedio'])))->getField('uid,name', true);
            $meeting_info['meeting_vedio'] = $meeting_vedio;
        }
        //会场调试布置人 手输 meeting_vedio
        if (!empty($meeting_info['meeting_vedio_value']))
            $meeting_info['meeting_vedio_value'] = explode(',', $meeting_info['meeting_vedio_value']);
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
        }
        //会场整理人 手输 meeting_clean_person
        if (!empty($meeting_info['meeting_clean_person_value']))
            $meeting_info['meeting_clean_person_value'] = explode(',', $meeting_info['meeting_clean_person_value']);
        if (!empty($meeting_info['meeting_record_person'])) {
            //记录整理人 meeting_record_person 
            $meeting_record_person = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_record_person'])))->getField('uid,name', true);
            $meeting_info['meeting_record_person'] = $meeting_record_person;
        }
        //会场整理人 手输 meeting_clean_person
        if (!empty($meeting_info['meeting_record_person_value']))
            $meeting_info['meeting_record_person_value'] = explode(',', $meeting_info['meeting_record_person_value']);
        //现场保证人

        if (!empty($meeting_info['meeting_site_protection'])) {
            //记录整理人 meeting_site_protection 
            $meeting_record_person = $user_mod->where(array('uid' => array('in', $meeting_info['meeting_site_protection'])))->getField('uid,name', true);
            $meeting_info['meeting_site_protection'] = $meeting_record_person;
        }
        //会场整理人 手输 meeting_site_protection
        if (!empty($meeting_info['meeting_site_protection_value']))
            $meeting_info['meeting_site_protection_value'] = explode(',', $meeting_info['meeting_site_protection_value']);

        if (!empty($meeting_info['meeting_annexes_url'])) {
            $config_info = C();
            if ($config_info['OPEN_FTP'] == 1) {
                $url = C('FTP_VISIT_PATH');
            } else {
                $url = C('FTP_VISIT_PATH');
            }
            $meeting_info['meeting_annexes_url'] = $url . $meeting_info['meeting_annexes_url'];
        }
        return $meeting_info;
    }

    /**
     * 计算当前月天数 和日期
     * @author lishuaijie
     * @return array 当前月时间
     * @date 2016/09/27
     */
    public function getDate($start_time, $end_time) {
        $dt_start = strtotime($start_time);
        $dt_end = strtotime($end_time);
        $days = round(($dt_end - $dt_start) / 3600 / 24);
        $first_day = date('w', $dt_start) == 0 ? 7 : date('w', $dt_start);
        do {
            $date[] = date('Y-m-d', $dt_start);
        } while (($dt_start += 86400) <= $dt_end);
        $temp_days = $first_day + $days;
        $weeksInMonth = ceil($temp_days / 7);
        $counter = 0;
        $week = array();
        for ($j = 0; $j < $weeksInMonth; $j++) {
            for ($i = 1; $i <= 7; $i++) {
                $w = date('w', strtotime($date[$counter])) == 0 ? 7 : date('w', strtotime($date[$counter]));
                if ($i == $w) {
                    $week [$j] [$i] = $date[$counter];
                    $counter ++;
                } else {
                    $week [$j] [$i] = '';
                }
            }
        }
        return $week;
    }

    /**
     *  获取当前系统用户当前月的会议
     * @author lishuaijie
     * @return array 当前月会议列表 Description
     * @date 2016/09/27
     */
    public function getMeetingByMonth($start_time, $end_time) {
        $meeting_mod = D('meeting');
        //获取当前用户近期的会议
        $uid = session('S_USER_INFO.UID');
        $meeting_info = $meeting_mod->alias('meeting')
                ->join('__MEETING_PARTICIPANTS__ callman on callman.meeting_id = meeting.meeting_id')
                ->where(array(
                    'meeting.meeting_date' => array('between', array($start_time, $end_time,)),
                    'callman.meeting_participants' => $uid,
                    'meeting_state' => 1
                ))
                ->select();
//        echo $meeting_mod->getLastSql();die;
        return $meeting_info;
    }

    /**
     *  获取会议添加中的 台帐管理人列表
     * @author lishuaijie
     * @return array  拥有内部 台帐管理权限的用户列表
     * @date 2016/09/27
     */
    public function getAccount() {
        //103
        $auth_mod = D('auth_group');
        //获取所有的角色
        $auth_info = $auth_mod->where(array('status' => 1))->select();
        $group_info = array();
        if (empty($auth_info)) {
            return array();
        }
        foreach ($auth_info as $val) {
            $rules_info = explode(',', $val['rules']);
            if (in_array('103', $rules_info)) {
                $group_info[] = $val['id'];
            }
        }
        if (empty($group_info)) {
            return array();
        }
        $group_id = implode(',', $group_info);
        $user_group_mod = D('auth_group_access');
        $user_info = $user_group_mod->where(array('group_id' => array('in', $group_id)))->getField('uid', true);
        if (empty($user_info)) {
            return array();
        }
        //根据角色获取用户id
        $user_id = implode(',', $user_info);
        $user_list = D('member')->where(array('state' => 1, 'uid' => array('in', $user_id)))->select();
        return $user_list;
    }

    /**
     *  给台帐管理人发送邮件
     * @param $meeting_id 会议id $name Description
     * @author lishuaijie
     * @return true/false Description
     */
    public function sendMeetingEmail($meeting_id) {
        $meeting_info = D('meeting')->alias('meet')
                ->join('__MEMBER__ member ON meet.meeting_ledger_re_person = member.uid')
                ->where(array('meet.meeting_id', $meeting_id))
                ->find();
        $str = $meeting_info['name'] . " ，您好：" . $meeting_info['meeting_name'] . "会议于" . $meeting_info['meeting_date'] . "已经创建，请尽快登录协同办公管理系统进行会议台账创建，谢谢；";
        return sendMail($meeting_info['email'], '台帐通知', $str);
    }

    /**
     * 处理到导出数据
     * @param $key $name Description
     * @author lishuaijie
     * @date 2016/09/27
     * @return array Description
     */
    public function dealtData($val, $key) {
        $user_mod = D('member');
        if (!empty($val[$key])) {
            $meeting_moderator_name = $user_mod->where('uid in (' . $val[$key] . ')')->getField('name', true);
        }
        $key1 = $key . '_value';
        if (!empty($val[$key1])) {
            $meeting_moderator_name1 = explode(',', $val[$key1]);
        }
        if (!empty($meeting_moderator_name)) {
            $$meeting_moderator_info = $meeting_moderator_name;
        }
        if (!empty($meeting_moderator_name1)) {
            $meeting_moderator_info = $meeting_moderator_name1;
        }
        if (!empty($meeting_moderator_name) && !empty($meeting_moderator_name1)) {
            $$meeting_moderator_info = array_merge($$meeting_moderator_name, $meeting_moderator_name1);
        }
        if (!empty($$meeting_moderator_info)) {
            return implode(',', $$meeting_moderator_info);
        }
        return '';
    }

    /**
     *  会议导入
     * @param $data excel导入内容
     * @author lishuaijei
     * @return true/false Description
     * @date 2016/09/28
     */
    public function meetingImport($data) {
        $config_mod = D('config_system');
        $meeting_mod = D('meeting');
        $user_mod = D('member');
        $meeting_mod->startTrans();
        $meeting_flag = 1;
        foreach ($data as $key => $val) {
            if (!empty($val[1])) {
                $info['meeting_name'] = $val[1]; //会议名称
            }
            if (!empty($val[2])) {
                $type = $config_mod->where(array('config_key' => 'meeting_type', 'config_descripion' => trim($val[2])))->find();
                $info['meeting_type'] = '';
                if (!empty($type)) {
                    $info['meeting_type'] = $type['config_value']; //会议类型
                }
            }
            if (!empty($val[3])) {
                $level = $config_mod->where(array('config_key' => 'meeting_level', 'config_descripion' => trim($val[3])))->find();
                $info['meeting_leve'] = '';
                if (!empty($level)) {
                    $info['meeting_leve'] = $level['config_value']; //会议级别
                }
            }
            //召集人
            if (!empty($val[4])) {
                $info['meeting_callman_name'] = $val[4];
                $info['meeting_callman_value'] = $val[4];
            }
            //主持人
            if (!empty($val[5])) {
                $info['meeting_moderator_value'] = $val[5];
            }

            //参会人员
            if (!empty($val[6])) {
                $info['meeting_participants_value'] = $val[6];
            }
            //会议形式
            if (!empty($val[7])) {
                $form = $config_mod->where(array('config_key' => 'meeting_form', 'config_descripion' => trim($val[7])))->find();
                if (!empty($form)) {
                    $info['meeting_form'] = $form['config_value'];
                }
            }
            //参会规模
            if (!empty($val[8])) {
                $info['meeting_scale'] = $val[8];
            }
            //会议日期
            if (!empty($val[9])) {
                $info['meeting_date'] = $val[9];
            }
            //会议时刻
            if (!empty($val[10])) {
                $info['meeting_time'] = $val[10];
            }
            //会议地点
            if (!empty($val[11])) {
                $info['meeting_place'] = $val[11];
            }
            //交办日期
            if (!empty($val[12])) {
                $info['meeting_assigned_date'] = $val[12];
            }
            //会议时长
            if (!empty($val[13])) {
                $info['meeting_timelong'] = $val[13];
            }
            //会议撰写人
            if (!empty($val[14])) {
                $info['meeting_writeperson_value'] = $val[14];
            }
            //通知发出日期
            if (!empty($val[15])) {
                $info['meeting_sendnotice_date'] = $val[15];
            }
            //通知发出时刻
            if (!empty($val[16])) {
                $info['meeting_notice_date'] = $val[16];
            }
            //会议材料收集
            if (!empty($val[17])) {
                $info['meeting_material_collect_person'] = $val[17];
            }
            //收集时间
            if (!empty($val[18])) {
                $info['meeting_material_collect_person'] = $val[18];
            }
            //材料提交时间
            if (!empty($val[19])) {
                $info['meeting_material_send_date'] = $val[19];
            }
            //物料准备
            if (!empty($val[20])) {
                $info['meeting_material_madeperson_value'] = $val[20];
            }
            //会场布置与调试
            if (!empty($val[21])) {
                $info['meeting_venue_arrangeperson_value'] = $val[21];
            }
            //测试日期
            if (!empty($val[22])) {
                $info['meeting_try_date'] = $val[22];
            }
            //测试时间
            if (!empty($val[23])) {
                $info['meeting_try_time'] = $val[23];
            }
            //问题明细
            if (!empty($val[24])) {
                $info['meeting_qusetion_detail'] = $val[24];
            }
            //是否解决
            if (!empty($val[25])) {
                if ($val[25] == '解决') {
                    $fix_state = 1;
                }
                if ($val[25] == '未解决') {
                    $fix_state = 2;
                }
                if ($val[25] == '处理中') {
                    $fix_state = 3;
                }
                $info['meeting_fix_state'] = $fix_state;
            }
            //现场保障
            if (!empty($val[26])) {
                $info['meeting_site_protection_value'] = $val[26];
            }
            //问题明细
            if (!empty($val[27])) {
                $info['meeting_site_qusetion_detail'] = $val[27];
            }
            //是否解决
            if (!empty($val[28])) {
                if ($val[28] == '解决') {
                    $site_state = 1;
                }
                if ($val[28] == '未解决') {
                    $site_state = 2;
                }
                if ($val[28] == '处理中') {
                    $site_state = 3;
                }
                $info['meeting_site_state'] = $fix_state;
            }
            //会议摄影摄像
            if (!empty($val[29])) {
                $info['meeting_vedio_value'] = $val[29];
            }
            //会议结束日期
            if (!empty($val[30])) {
                $info['meeting_end_date'] = $val[30];
            }
            //会议结束时刻
            if (!empty($val[31])) {
                $info['meeting_end_time'] = $val[31];
            }
            //餐饮安排
            if (!empty($val[32])) {
                $info['meeting_food_drink_value'] = $val[32];
            }
            //会场整理人
            if (!empty($val[33])) {
                $info['meeting_clean_person_value'] = $val[33];
            }
            //记录整理人
            if (!empty($val[35])) {
                $info['meeting_record_person'] = $val[35];
            }
            //相关文字
            if (!empty($val[34])) {
                $info['meeting_content'] = $val[34];
            }
            //台帐管理人
            if (!empty($val[36])) {
                $user_info = $user_mod->where(array('name' => trim($val[36])))->find();
                if (!empty($user_info)) {
                    $info['meeting_ledger_re_person'] = $user_info['uid'];
                } else {
                    $meeting_flag = 0;
                    break;
                }
            }
            $info['meeting_state'] = 1;
            $sava_flag = $meeting_mod->add($info);
            if (!$sava_flag) {
                $meeting_flag = 0;
                break;
            }
        }
        if (!$meeting_flag) {
            $error_info = C('COMMON.IMPORT_ERROR');
            $meeting_mod->rollback();
            return $error_info;
        } else {
            $success_info = C('COMMON.IMPORT_SUCCESS');
            $meeting_mod->commit();
            return $success_info;
        }
    }

}
