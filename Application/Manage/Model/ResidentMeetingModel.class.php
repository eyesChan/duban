<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manage\Model;

use Think\Model;

/**
 * Description of LedgerMeetingModel
 * 驻各地发展情况台账管理
 * @author huanggang
 * @Date    2016/09/29
 */
class ResidentMeetingModel extends Model {

    protected $tableName = 'resident_meeting';
    /**
     * 对数组进行转义
     * @param array 需要转义的数组
     * @return array 返回转义后的数组
     */
    public function escape($param) {
        foreach ($param as $k => $val) {
            $param[$k] = str_replace("_", "\_", $val);
        }

        return $param;
    }

    /*
     * 驻各地发展情况创建
     * @Date    2016/09/29
     * @ahthor huanggang
     * @return object 添加成功或失败
     */

    public function addResident($param) {
        if (empty($param['resident_collect_time'])) {
            unset($param['resident_collect_time']);
        }
        if (empty($param['resident_notice_time'])) {
            unset($param['resident_notice_time']);
        }
        if (empty($param['resident_file_time'])) {
            unset($param['resident_file_time']);
        }
        $resident_meeting = M('resident_meeting');
        $res = $resident_meeting->add($param);
        if ($res) {
            writeOperationLog('添加驻“' . $param['resident_region'] . '”发展情况台账', 1);
            return C('COMMON.SUCCESS_EDIT');
        } else {
            writeOperationLog('添加驻“' . $param['resident_region'] . '”发展情况台账', 0);
            return C('COMMON.ERROR_EDIT');
        }
    }

    /*
     * 统计数量
     * @ahthor huanggang
     * @Date    2016/09/29
     * @return object 数据数量
     */

    public function getResidentCount($where) {
        $count = M('resident_meeting')
                ->where($where)
                ->count();
        return $count;
    }

    /**
     * 处理搜索条件
     * @Date    2016/09/27
     * @author  huanggang
     * @return array 成功查询条件数组
     */
    public function getSelectWhere($param) {
        //处理查询条件：国家、责任人、收集时间
        $param['resident_country'] != '' ? $where['resident_country'] = array('like', '%' . $param['resident_country'] . '%') : '';
        $param['resident_person'] != '' ? $where['resident_person'] = array('like', '%' . $param['resident_person'] . '%') : '';
        if (!empty($param['resident_collect_time'])) {
            $where['resident_collect_time'] = array('EQ', $param['resident_collect_time']);
        }
        $where['resident_status'] = array('EQ', '1');
        $where = $this->escape($where);
        return $where;
    }

    /**
     * 分页查询操作
     * @Date    2016/09/29
     * @author huanggang
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getResidentList($where, $first_rows, $list_rows) {
        $resident_meeting = M('resident_meeting');
        $list = $resident_meeting
                ->field('resident_id,resident_country,resident_person,resident_collect_time,resident_province,resident_file_time')
                ->where($where)
                ->limit($first_rows, $list_rows)
                ->order('resident_id desc')
                ->select();
        return $list;
    }

    /*
     * 页面详情及编辑查询
     * @Date    2016/09/29
     * @author  huanggang
     * @param $resident_id 查询条件
     * @return 返回处理数据
     */

    public function detailsResident($resident_id) {
        $resident_meeting = M('resident_meeting');
        $list = $resident_meeting
                ->where("resident_id= $resident_id")
                ->find();
        return $list;
    }

    /*
     * 编辑操作
     * @Date    2016/09/29
     * @author huanggang
     * @param array $data 修改后的数据
     * @param $resident_id 修改条件
     * @return object 修改成功或失败 
     */

    public function saveResident($data, $resident_id) {
        if (empty($data['resident_collect_time'])) {
            unset($data['resident_collect_time']);
        }
        if (empty($data['resident_notice_time'])) {
            unset($data['resident_notice_time']);
        }
        if (empty($data['resident_file_time'])) {
            unset($data['resident_file_time']);
        }
        $resident_meeting = M('resident_meeting');
        $res = $resident_meeting->where("resident_id =" . $resident_id)->save($data);
        if (FALSE === $res) {
            writeOperationLog('修改驻“' . $data['resident_region'] . '”发展情况台账', 0);
            return C('COMMON.ERROR_EDIT');
        } else {
            writeOperationLog('修改驻“' . $data['resident_region'] . '”发展情况台账', 1);
            return C('COMMON.SUCCESS_EDIT');
        }
    }

    /*
     * 
     * 删除操作
     * @Date    2016/09/29
     * @author  huanggang
     * @param $resident_id 删除条件
     * @return object 删除成功或失败
     */

    public function delResident($resident_id) {
        $resident_meeting = M('resident_meeting');
        $res = $resident_meeting->where("resident_id =" . $resident_id)->setField('resident_status', '0');
        $resident_region = $resident_meeting->where("resident_id =" . $resident_id)->getField('resident_region');
        if ($res) {
            writeOperationLog('删除驻“' . $resident_region . '”发展情况台账', 1);
            return C('COMMON.SUCCESS_DEL');
        } else {
            writeOperationLog('删除驻“' . $resident_region . '”发展情况台账', 0);
            return C('COMMON.ERROR_DEL');
        }
    }

    /*
     * 导出execl 查询
     * @Date    2016/09/29
     * @author huanggang
     * @author array data 组合要到导出的数据
     */

    public function getExecl($param) {
        //处理查询条件：会谈名称、主持人、日期
        $where = $this->getSelectWhere($param);
        $resident_meeting = M('resident_meeting');
        $data = $resident_meeting
                ->where($where)
                ->order('resident_id desc')
                ->select();
        //去除不需要的键值
        foreach ($data as $k => $v) {
            unset($data[$k]['resident_status']);
            unset($data[$k]['resident_id']);
            unset($data[$k]['resident_add_time']);
            unset($data[$k]['resident_save_time']);
        }
        return $data;
    }

    /*
     * 驻各地发展情况批量创建
     * @Date    2016/09/29
     * @ahthor huanggang
     * @return object 添加成功或失败
     */

    public function addsResident($param) {
        $resident_meeting = M('resident_meeting');
        $data = array();
        $flag = 0;
        $model = new Model();
        $model->startTrans();
        $res = array(
            'resident_region',
            'resident_country',
            'resident_province',
            'resident_update_time',
            'resident_collect_time',
            'resident_date_time',
            'resident_person',
            'resident_summary',
            'resident_collect_person',
            'resident_approve',
            'resident_examine_person',
            'resident_contact_person',
            'resident_notice_time',
            'resident_whether_submit',
            'resident_node',
            'resident_whether_file',
            'resident_file_time',
            'resident_file_address',
        );
        foreach ($param as $key => $v) {
            foreach ($v as $k => $v1) {
                $data[$res[$k]] = $v1;
            }
            $param[$key] = $data;
        }
        foreach ($param as $key => $v) {
            $res = $resident_meeting->add($v);
            if ($res == FALSE) {
                $flag = $flag - 1;
                break;
            }
        }
        if ($flag < 0) {
            $model->rollback();
            writeOperationLog('导入“' . 'excel表格' . '”', 0);
            return C('COMMON.IMPORT_ERROR');
        } else {
            $model->commit();
            writeOperationLog('导入“' . 'excel表格' . '”', 1);
            return C('COMMON.IMPORT_SUCCESS');
        }
    }

}
