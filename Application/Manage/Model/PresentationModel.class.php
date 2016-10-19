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
 *
 * @author huanggang
 * @Date    2016/09/26
 */
class PresentationModel extends Model {

    protected $trueTableName = 'db_led_presentation';

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
     * 文稿台账创建
     * @Date    2016/09/27
     * @ahthor huanggang
     * @return object 添加成功或失败
     */

    public function addPresent($param) {
        $param = array_filter($param);
        $led_presentation = M('led_presentation');
        $res = $led_presentation->add($param);
        if ($res) {
            writeOperationLog('添加“' . $param['db_pre_name'] . '”文稿台账', 1);
            return C('COMMON.SUCCESS_EDIT');
        } else {
            writeOperationLog('添加“' . $param['db_pre_name'] . '”文稿台账', 0);
            return C('COMMON.ERROR_EDIT');
        }
    }

    /*
     * 查询用户表
     * @ahthor huanggang
     * @Date    2016/09/27
     * @return array $user 用户名称 id
     */

    public function getUser() {
        $user = M('member')
                ->where("status=1")
                ->field('uid,name')
                ->select();
        return $user;
    }

    /*
     * 统计数量
     * @ahthor huanggang
     * @Date    2016/09/26
     * @return object 数据数量
     */

    public function getPresentCount($where) {
        $count = M('led_presentation')
                ->where($where)
                ->count();
        return $count;
    }

    /**
     * 分页查询操作
     * @Date    2016/09/27
     * @author huanggang
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getPresentList($where, $first_rows, $list_rows) {
        $led_presentation = M('led_presentation');
        $list = $led_presentation
                ->field('db_pre_id,db_pre_name,db_assign_name,db_assign_date,db_draft_person,db_despatch_date')
                ->where($where)
                ->limit($first_rows, $list_rows)
                ->order('db_pre_id desc')
                ->select();
        foreach ($list as $k => $v) {
            $list[$k]['db_draft_person'] = $this->getWhereUser($v['db_draft_person']);
        }
        return $list;
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

    /*
     * 根据条件查询用户表
     * @ahthor huanggang
     * @Date    2016/09/27
     * @param array $where 查询条件
     * @return 返回查询的用户名
     */

    public function getWhereUser($where) {
        $user = M('member')
                ->where("uid=$where")
                ->getField('name');
        return $user;
    }

    /*
     * 页面详情及编辑查询
     * @Date    2016/09/27
     * @author  huanggang
     * @param $pre_id 查询条件
     * @return 返回处理数据
     */

    public function detailsPresent($pre_id) {
        $led_presentation = M('led_presentation');
        $list = $led_presentation
                ->where("db_pre_id= $pre_id")
                ->find();
        //文稿类型
        $list['db_pre_type'] = $this->getRootView($list['db_pre_type'], 'doc_pre_type');
        //工作状态
        $list['db_pre_status'] = $this->getRootView($list['db_pre_status'], 'doc_work_status');
        //文稿形式
        $list['db_pre_form'] = $this->getRootView($list['db_pre_form'], 'doc_pre_form');
        //责任人
        $list['db_pre_person'] = $this->getWhereUser($list['db_pre_person']);
        //拟稿人
        $list['db_draft_person'] = $this->getWhereUser($list['db_draft_person']);
        //核稿人1
        $list['db_orgin_person'] = $this->getWhereUser($list['db_orgin_person']);
        //核稿人2
        $list['db_orgin2_person'] = $this->getWhereUser($list['db_orgin2_person']);
        //核稿人3
        $list['db_orgin3_person'] = $this->getWhereUser($list['db_orgin3_person']);
        //发文方式
        $list['db_despatch_mode'] = $this->getRootView($list['db_despatch_mode'], 'doc_dis_mode');
        //审批方式
        $list['db_examin_mode'] = $this->getRootView($list['db_examin_mode'], 'doc_exa_mode');
        //审批进展
        $list['db_examin_progress'] = $this->getRootView($list['db_examin_progress'], 'doc_exa_mode1');
        //优先级
        $list['db_pre_first'] = $this->getRootView($list['db_pre_first'], 'pro_level');
        //存档情况
        $list['db_file_status'] = $this->getRootView($list['db_file_status'], 'doc_can_type');
        //核稿评价1
        $list['db_orgin_eval'] = $this->goodsInDiff1($list['db_orgin_eval']);
        //核稿评价2
        $list['db_orgin2_eval'] = $this->goodsInDiff1($list['db_orgin2_eval']);
        //核稿评价3
        $list['db_orgin3_eval'] = $this->goodsInDiff1($list['db_orgin3_eval']);
        //中心评价
        $list['db_evaluate'] = $this->goodsInDiff1($list['db_evaluate']);
        return $list;
    }

    /*
     * 获取所需的数据
     * @author huanggang
     * @Date    2016/10/11
     * @param  $config_id 查询条件
     * @return 返回查询的数据
     */

    public function getRootView($config_value, $config_key) {
        $work = M('config_system')
                ->where(array('config_value' => $config_value, 'config_key' => $config_key))
                ->getField('config_descripion');
        return $work;
    }

    /*
     * 判断 优 中 差
     * @Date    2016/11/12
     * @author huanggang
     * @param $param 查询条件
     * @return 入库的数据
     */

    public function goodsInDiff1($param) {
        if ($param == '1') {
            return '优';
        } else if ($param == '2') {
            return '中';
        } else {
            return '差';
        }
    }

    /*
     * 编辑操作
     * @Date    2016/09/27
     * @author huanggang
     * @param array $data 修改后的数据
     * @param $pre_id 修改条件
     * @return object 修改成功或失败 
     */

    public function savePresent($data, $pre_id) {
        $data = array_filter($data);
        $led_presentation = M('led_presentation');
        $res = $led_presentation->where("db_pre_id =" . $pre_id)->save($data);
        if (FALSE === $res) {
            writeOperationLog('修改“' . $data['db_pre_name'] . '”文稿台账', 0);
            return C('COMMON.ERROR_EDIT');
        } else {
            writeOperationLog('修改“' . $data['db_pre_name'] . '”文稿台账', 1);
            return C('COMMON.SUCCESS_EDIT');
        }
    }

    /*
     * 
     * 删除操作
     * @Date    2016/09/27
     * @author  huanggang
     * @param $pre_id 删除条件
     * @return object 删除成功或失败
     */

    public function delPresent($pre_id) {
        $led_presentation = M('led_presentation');
        $res = $led_presentation->where("db_pre_id =" . $pre_id)->setField('pre_status', '0');
        $db_pre_name = $led_presentation->where("db_pre_id =" . $pre_id)->getField('db_pre_name');
        if ($res) {
            writeOperationLog('删除“' . $db_pre_name . '”文稿台账', 1);
            return C('COMMON.SUCCESS_DEL');
        } else {
            writeOperationLog('删除“' . $db_pre_name . '”文稿台账', 0);
            return C('COMMON.ERROR_DEL');
        }
    }

    /*
     * 导出集团execl 查询
     * @Date    2016/09/27
     * @author huanggang
     * @author array data 组合要到导出的数据
     */

    public function getExecl($param) {
        //处理查询条件：文字台账、交办人、时间
        $where = $this->getSelectWhere($param);
        $led_presentation = M('led_presentation');
        $data = $led_presentation
                ->field('db_pre_id,db_pre_name,db_pre_work,db_assign_date,db_complete_time,db_pre_type,db_pre_form,db_pre_first,db_pre_diff,db_pre_person,db_pre_status,db_draft_person,db_draft_length,db_draft_num,db_orgin_person,db_orgin_length,db_orgin_num,db_orgin_eval,db_orgin2_person,db_orgin2_length,db_orgin2_num,db_orgin2_eval,db_orgin3_person,db_orgin3_length,db_orgin3_num,db_orgin3_eval,db_evaluate,db_overtime_num,db_overtime_num,db_mishap_num,db_examin_date,db_examin_mode,db_examin_progress,db_despatch_mode,db_despatch_date,db_file_status,db_file_date,db_file_address')
                ->where($where)
                ->order('db_pre_id desc')
                ->select();
        //去除及修改键值
        foreach ($data as $k => $v) {
            $data[$k]['db_pre_id'] = $k + 1;
            //文稿类型
            $data[$k]['db_pre_type'] = $this->getRootView($v['db_pre_type'], 'doc_pre_type');
            //文稿形式
            $data[$k]['db_pre_form'] = $this->getRootView($v['db_pre_form'], 'doc_pre_form');
            //工作状态
            $data[$k]['db_pre_status'] = $this->getRootView($v['db_pre_status'], 'doc_work_status');
            //责任人
            $data[$k]['db_pre_person'] = $this->getWhereUser($v['db_pre_person']);
            //拟稿人
            $data[$k]['db_draft_person'] = $this->getWhereUser($v['db_draft_person']);
            //核稿人1
            $data[$k]['db_orgin_person'] = $this->getWhereUser($v['db_orgin_person']);
            //核稿人2
            $data[$k]['db_orgin2_person'] = $this->getWhereUser($v['db_orgin2_person']);
            //核稿人3
            $data[$k]['db_orgin3_person'] = $this->getWhereUser($v['db_orgin3_person']);
            //发文方式
            $data[$k]['db_despatch_mode'] = $this->getRootView($v['db_despatch_mode'], 'doc_dis_mode');
            //审批方式
            $data[$k]['db_examin_mode'] = $this->getRootView($v['db_examin_mode'], 'doc_exa_mode');
            //审批进展
            $data[$k]['db_examin_progress'] = $this->getRootView($v['db_examin_progress'], 'doc_exa_mode1');
            //优先级
            $data[$k]['db_pre_first'] = $this->getRootView($v['db_pre_first'], 'pro_level');
            //存档情况
            $data[$k]['db_file_status'] = $this->getRootView($v['db_file_status'], 'doc_can_type');
            //核稿评价1
            $data[$k]['db_orgin_eval'] = $this->goodsInDiff1($v['db_orgin_eval']);
            //核稿评价2
            $data[$k]['db_orgin2_eval'] = $this->goodsInDiff1($v['db_orgin2_eval']);
            //核稿评价3
            $data[$k]['db_orgin3_eval'] = $this->goodsInDiff1($v['db_orgin3_eval']);
            //中心评价
            $data[$k]['db_evaluate'] = $this->goodsInDiff1($v['db_evaluate']);
            unset($data[$k]['db_add_time']);
            unset($data[$k]['db_update_time']);
        }
        return $data;
    }

    /*
     * 导出公司execl 查询
     * @Date    2016/09/27
     * @author huanggang
     * @author array data 组合要到导出的数据
     */

    public function getExecls($param) {
        //处理查询条件：文字台账、交办人、时间
        $where = $this->getSelectWhere($param);
        $led_presentation = M('led_presentation');
        $data = $led_presentation
                ->where($where)
                ->order('db_pre_id desc')
                ->select();
        //去除及修改键值
        foreach ($data as $k => $v) {
            unset($data[$k]['pre_status']);
            $data[$k]['db_pre_id'] = $k + 1;
            //文稿类型
            $data[$k]['db_pre_type'] = $this->getRootView($v['db_pre_type'], 'doc_pre_type');
            //工作状态
            $data[$k]['db_pre_status'] = $this->getRootView($v['db_pre_status'], 'doc_work_status');
            //文稿形式
            $data[$k]['db_pre_form'] = $this->getRootView($v['db_pre_form'], 'doc_pre_form');
            //责任人
            $data[$k]['db_pre_person'] = $this->getWhereUser($v['db_pre_person']);
            //拟稿人
            $data[$k]['db_draft_person'] = $this->getWhereUser($v['db_draft_person']);
            //核稿人1
            $data[$k]['db_orgin_person'] = $this->getWhereUser($v['db_orgin_person']);
            //核稿人2
            $data[$k]['db_orgin2_person'] = $this->getWhereUser($v['db_orgin2_person']);
            //核稿人3
            $data[$k]['db_orgin3_person'] = $this->getWhereUser($v['db_orgin3_person']);
            //发文方式
            $data[$k]['db_despatch_mode'] = $this->getRootView($v['db_despatch_mode'], 'doc_dis_mode');
            //审批方式
            $data[$k]['db_examin_mode'] = $this->getRootView($v['db_examin_mode'], 'doc_exa_mode');
            //审批进展
            $data[$k]['db_examin_progress'] = $this->getRootView($v['db_examin_progress'], 'doc_exa_mode1');
            //优先级
            $data[$k]['db_pre_first'] = $this->getRootView($v['db_pre_first'], 'pro_level');
            //存档情况
            $data[$k]['db_file_status'] = $this->getRootView($v['db_file_status'], 'doc_can_type');
            //核稿评价1
            $data[$k]['db_orgin_eval'] = $this->goodsInDiff1($v['db_orgin_eval']);
            //核稿评价2
            $data[$k]['db_orgin2_eval'] = $this->goodsInDiff1($v['db_orgin2_eval']);
            //核稿评价3
            $data[$k]['db_orgin3_eval'] = $this->goodsInDiff1($v['db_orgin3_eval']);
            //中心评价
            $data[$k]['db_evaluate'] = $this->goodsInDiff1($v['db_evaluate']);
            unset($data[$k]['db_add_time']);
            unset($data[$k]['db_update_time']);
        }
        return $data;
    }

    /*
     * 文稿批量创建
     * @Date    2016/09/27
     * @ahthor huanggang
     * @return object 添加成功或失败
     */

    public function addsPresent($param) {
        import("Org.Util.PHPExcel.PHPExcel");
        //时间格式转换
        include_once 'ThinkPHP/Library/Org/Util/PHPExcel/PHPExcel/Shared/Date.php';
        $dateMod = new \PHPExcel_Shared_Date();
        $led_presentation = M('led_presentation');
        $flag = 0;
        $model = new Model();
        $model->startTrans();
        $data = array();
        $res = array('db_pre_id', 'db_pre_name', 'db_pre_work', 'db_assign_name', 'db_assign_dapart',
            'db_assign_post', 'db_assign_date', 'db_assign_time', 'db_complete_date', 'db_complete_time',
            'db_pre_type', 'db_pre_form', 'db_pre_first', 'db_pre_diff', 'db_pre_person', 'db_pre_status', 'db_draft_person',
            'db_draft_date', 'db_draft_time', 'db_draft_length', 'db_draft_num', 'db_orgin_person', 'db_orgin_date',
            'db_orgin_time', 'db_orgin_length', 'db_orgin_num', 'db_update_num', 'db_orgin_eval', 'db_orgin2_person',
            'db_orgin2_date', 'db_orgin2_time', 'db_orgin2_length', 'db_orgin2_num', 'db_update2_num', 'db_orgin2_eval',
            'db_orgin3_person', 'db_orgin3_date', 'db_orgin3_time', 'db_orgin3_length', 'db_orgin3_num', 'db_update3_num',
            'db_orgin3_eval', 'db_evaluate', 'db_overtime_num', 'db_mishap_num', 'db_examin_date', 'db_examin_time',
            'db_examin_mode', 'db_examin_progress', 'db_despatch_mode', 'db_despatch_person', 'db_despatch_date',
            'db_despatch_time', 'db_file_person', 'db_file_status', 'db_file_date',
            'db_file_address', 'db_india_name', 'db_india_num', 'db_pre_beizhu'
        );
        foreach ($param as $key => $v) {
            foreach ($v as $k => $v1) {
                $data[$res[$k]] = $v1;
            }
            //去掉不需要的值
            unset($data['db_pre_id']);
            $param[$key] = $data;
        }
        foreach ($param as $key => $v) {
            //文稿类型
            $v['db_pre_type'] = $this->getRootViews($v['db_pre_type'], 'doc_pre_type');
            //文稿形式
            $v['db_pre_form'] = $this->getRootViews($v['db_pre_form'], 'doc_pre_form');
            //工作状态
            $v['db_pre_status'] = $this->getRootViews($v['db_pre_status'], 'doc_work_status');
            //责任人
            $v['db_pre_person'] = $this->getWhereUsers($v['db_pre_person']);
            //交办时刻
            $v['db_assign_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_assign_time']) - 3600 * 8);
            //交办完成时刻
            $v['db_complete_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_complete_time']) - 3600 * 8);
            //拟稿完成时刻
            $v['db_draft_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_draft_time']) - 3600 * 8);
            //核稿完成时刻1
            $v['db_orgin_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_orgin_time']) - 3600 * 8);
            //核稿完成时刻2
            $v['db_orgin2_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_orgin2_time']) - 3600 * 8);
            //核稿完成时刻3
            $v['db_orgin3_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_orgin3_time']) - 3600 * 8);
            //审批呈报时刻
            $v['db_examin_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_examin_time']) - 3600 * 8);
            //发布时刻
            $v['db_despatch_time'] = date('H:i:s', $dateMod->ExcelToPHP($v['db_despatch_time']) - 3600 * 8);
            if ($v['db_pre_person'] == FALSE) {
                $flag = $flag - 1;
                break;
            }
            //拟稿人
            $v['db_draft_person'] = $this->getWhereUsers($v['db_draft_person']);
            if ($v['db_draft_person'] == FALSE) {
                $flag = $flag - 1;
                break;
            }
            //核稿人1
            $v['db_orgin_person'] = $this->getWhereUsers($v['db_orgin_person']);
            if ($v['db_orgin_person'] == FALSE) {
                $flag = $flag - 1;
                break;
            }
            //核稿人2
            $v['db_orgin2_person'] = $this->getWhereUsers($v['db_orgin2_person']);
            if ($v['db_orgin2_person'] == FALSE) {
                $flag = $flag - 1;
            }
            //核稿人3
            $v['db_orgin3_person'] = $this->getWhereUsers($v['db_orgin3_person']);
            if ($v['db_orgin3_person'] == FALSE) {
                $flag = $flag - 1;
                break;
            }
            //发文方式
            $v['db_despatch_mode'] = $this->getRootViews($v['db_despatch_mode'], 'doc_dis_mode');
            //审批方式
            $v['db_examin_mode'] = $this->getRootViews($v['db_examin_mode'], 'doc_exa_mode');
            //审批进展
            $v['db_examin_progress'] = $this->getRootViews($v['db_examin_progress'], 'doc_exa_mode1');
            //优先级
            $v['db_pre_first'] = $this->getRootViews($v['db_pre_first'], 'pro_level');
            //存档情况
            $v['db_file_status'] = $this->getRootViews($v['db_file_status'], 'doc_can_type');
            //核稿评价1
            $v['db_orgin_eval'] = $this->goodsInDiff($v['db_orgin_eval']);
            //核稿评价2
            $v['db_orgin2_eval'] = $this->goodsInDiff($v['db_orgin2_eval']);
            //核稿评价3
            $v['db_orgin3_eval'] = $this->goodsInDiff($v['db_orgin3_eval']);
            //中心评价
            $v['db_evaluate'] = $this->goodsInDiff($v['db_evaluate']);
            $res = $led_presentation->add($v);
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

    /*
     * 导入时获取所需的数据
     * @author huanggang
     * @Date    2016/10/11
     * @param  $config_descripion 查询条件
     * @return 返回查询的数据id
     */

    public function getRootViews($config_descripion, $type) {
        $config_value = M('config_system')
                ->where(array('config_descripion' => $config_descripion, 'config_key' => $type))
                ->getField('config_value');
        return $config_value;
    }

    /*
     * 导入时根据条件查询用户表
     * @ahthor huanggang
     * @Date    2016/10/11
     * @param array $where 查询条件
     * @return 返回查询的用户id
     */

    public function getWhereUsers($where) {
        $uid = M('member')
                ->where("name='$where'")
                ->getField('uid');
        return $uid;
    }

    /*
     * 判断导入时 优 中 差
     * @Date    2016/11/12
     * @author huanggang
     * @param $param 查询条件
     * @return 入库的数据
     */

    public function goodsInDiff($param) {
        if ($param == '优') {
            return 1;
        } else if ($param == '中') {
            return 2;
        } else {
            return 3;
        }
    }

}
