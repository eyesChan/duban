<?php

namespace Manage\Model;

use Think\Model;

/**
 * 签约仪式台账管理模型类，负责查询列表数据、根据消息ID获取单条数据、生成查询条件、添加入库、编辑入库、删除动作
 *
 * @author chengyayu
 */
class CeremoneyAccountModel extends Model {

    protected $trueTableName = 'db_ceremoney_account';
    protected $_validate = array();

    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->_validate = array(
            array('dept_id', 'require', C('EXPENSERULECHECK.DEPT_ID_REQUIRE')),
        );
    }

    /**
     * 获取列表所需数据
     * 
     * @param array $params
     * @return array
     */
    public function getDataForList($params) {

        $arr_for_list = array();

        $where = $this->makeWhereForSearch($params);
        $page = $params['p'];
        $arr_for_list['ceremoney_account'] = $this->where($where)
                ->order('ca_time desc')
                ->page($page, 10)
                ->getField('ca_id,ca_name,ca_time,ca_host,ca_address', TRUE);
        $count = $this->where($where)->count();
        $Page = new \Think\Page($count, 10);
        foreach ($params as $k => $v) {
            $Page->parameter[$k] = $v;
        }
        $arr_for_list['page_show'] = $Page->show();

        return $arr_for_list;
    }

    /**
     * 获取单条数据信息
     * 
     * @param int $ca_id
     * @return array
     */
    public function getDataById($ca_id) {

        $where['ca_id'] = $ca_id;
        $res = $this->where($where)->find();

        return $res;
    }

    /**
     * 获取查询条件数组
     * 
     * @param array $params
     * @return array
     */
    public function makeWhereForSearch($params) {

        $where = array();

        $where['ca_status'] = 1; //排除‘已删除状态’
        if (!empty($params['ca_name'])) {
            $where['ca_name'] = array('LIKE', "%" . $params['ca_name'] . "%");
        }
        if (!empty($params['ca_host'])) {
            $where['ca_host'] = array('LIKE', "%" . $params['ca_host'] . "%");
        }
        if (!empty($params['ca_time'])) {
            $where['ca_time'] = date('Y-m-d', strtotime($params['ca_time']));
        }

        return $where;
    }

    /**
     * 添加入库
     * 
     * @param array $data
     * @return array
     */
    public function doAdd($data) {

        //数据完善
        $data['ca_create_time'] = date('Y-m-d H:i:s');
        $data['ca_update_time'] = date('Y-m-d H:i:s');
        if(empty($data['ca_time'])) {
            unset($data['ca_time']);
        }
        if(empty($data['ca_pub_date'])) {
            unset($data['ca_pub_date']);
        }
        //后台数据验证
        $ceremoney_account_data = $this->create($data);
        //入库
        if ($ceremoney_account_data) {
            $res_add = $this->add($ceremoney_account_data);
            if (FALSE === $res_add) {
                writeOperationLog('添加“' . $data['ca_name'] . '”签约仪式台账', 0);
                return C('COMMON.ERROR_ADD');
            } else {
                writeOperationLog('添加“' . $data['ca_name'] . '”签约仪式台账', 1);
                return C('COMMON.SUCCESS_ADD');
            }
        } else {
            $err_check_add = $this->getError();
            return $err_check_add;
        }
    }

    /**
     * 编辑入库
     * 
     * @param array $data
     * @return array
     */
    public function doEdit($data) {

        //数据完善
        $data['ca_update_time'] = date('Y-m-d H:i:s');
        if(empty($data['ca_time'])) {
            unset($data['ca_time']);
        }
        if(empty($data['ca_pub_date'])) {
            unset($data['ca_pub_date']);
        }
        //后台数据验证
        $ceremoney_account_data = $this->create($data);
        //入库
        if ($ceremoney_account_data) {
            $where['ca_id'] = $ceremoney_account_data['ca_id'];
            $res_update = $this->where($where)->save($ceremoney_account_data);
            if (FALSE === $res_update) {
                writeOperationLog('修改“' . $data['ca_name'] . '”签约仪式台账', 0);
                return C('COMMON.ERROR_EDIT');
            } else {
                writeOperationLog('修改“' . $data['ca_name'] . '”签约仪式台账', 1);
                return C('COMMON.SUCCESS_EDIT');
            }
        } else {
            $err_check_update = $this->getError();
            return $err_check_update;
        }
    }

    /**
     * 删除（修改状态）
     * 
     * @param int $ca_id
     * @return array
     */
    public function doDelete($ca_id) {

        $ceremoney_account_data = array();
        $ceremoney_account_data['ca_id'] = $ca_id;
        $ceremoney_account_data['ca_status'] = 0; //删除状态
        $ceremoney_account_data['ca_name'] = $this->where("ca_id = $ca_id")->getField('ca_name');
        $res_delete = $this->save($ceremoney_account_data);
        if ($res_delete == 1) {
            writeOperationLog('删除“' . $ceremoney_account_data['ca_name'] . '”签约仪式台账', 1);
            return C('COMMON.SUCCESS_DEL');
        } else {
            writeOperationLog('删除“' . $ceremoney_account_data['ca_name'] . '”签约仪式台账', 0);
            return C('COMMON.ERROR_DEL');
        }
    }

    /**
     * 导出excel
     * 
     * @return array
     */
    public function getExecl() {

        $data_ca = $this->select();
        $count = count($data_ca);
        for ($i = 0; $i <= $count; $i++) {
            unset($data_ca[$i]['ca_id']);
            unset($data_ca[$i]['ca_status']);
            unset($data_ca[$i]['ca_create_time']);
            unset($data_ca[$i]['ca_update_time']);
        }
        return $data_ca;
    }

    /**
     * 导入
     * 
     * @param array $data
     */
    public function import($data) {
        $count = count($data);
        $flag = 0;
        $model = new Model();
        $model->startTrans();
        for ($i = 0; $i < $count; $i++) {
            $param['ca_time'] = $data[$i][0];
            $param['ca_address'] = $data[$i][1];
            $param['ca_name'] = $data[$i][2];
            $param['ca_double'] = $data[$i][3];
            $param['ca_host'] = $data[$i][4];
            $param['ca_say_leader'] = $data[$i][5];
            $param['ca_zq_leader'] = $data[$i][6];
            $param['ca_qy_leader'] = $data[$i][7];
            $param['ca_participants'] = $data[$i][8];
            $param['ca_security_level'] = $data[$i][9];
            $param['ca_dress'] = $data[$i][10];
            $param['ca_security_person'] = $data[$i][11];
            $param['ca_security_time'] = $data[$i][12];
            $param['ca_yc_rpperson'] = $data[$i][13];
            $param['ca_yc_apstatus'] = $data[$i][14];
            $param['ca_xy_rpperson'] = $data[$i][15];
            $param['ca_xy_apstatus'] = $data[$i][16];
            $param['ca_notice_send_meeting'] = $data[$i][17];
            $param['ca_notice_material_prepara'] = $data[$i][18];
            $param['ca_notice_service_unit_security'] = $data[$i][19];
            $param['ca_ready_agenda'] = $data[$i][20];
            $param['ca_ready_leader_saydoc'] = $data[$i][21];
            $param['ca_ready_visitor_resume'] = $data[$i][22];
            $param['ca_ready_host_doc'] = $data[$i][23];
            $param['ca_ready_sign_book'] = $data[$i][24];
            $param['ca_ready_computer'] = $data[$i][25];
            $param['ca_ready_recorder'] = $data[$i][26];
            $param['ca_ready_microphone'] = $data[$i][27];
            $param['ca_ready_pub_material'] = $data[$i][28];
            $param['ca_ready_sign_boop'] = $data[$i][29];
            $param['ca_ready_sign_pen'] = $data[$i][30];
            $param['ca_ready_sign_music'] = $data[$i][31];
            $param['ca_ready_champagne'] = $data[$i][32];
            $param['ca_ready_gift'] = $data[$i][33];
            $param['ca_ready_table_flower'] = $data[$i][34];
            $param['ca_ready_bg_plate'] = $data[$i][35];
            $param['ca_make_sign_table_card'] = $data[$i][36];
            $param['ca_make_kt_plate'] = $data[$i][37];
            $param['ca_ready_table_card'] = $data[$i][38];
            $param['ca_ready_frame_picture'] = $data[$i][39];
            $param['ca_ready_role_screen'] = $data[$i][40];
            $param['ca_do_site_layout'] = $data[$i][41];
            $param['ca_do_bg_plate'] = $data[$i][42];
            $param['ca_test_microphone'] = $data[$i][43];
            $param['ca_test_audio'] = $data[$i][44];
            $param['ca_notice_jdbz'] = $data[$i][45];
            $param['ca_recep_person'] = $data[$i][46];
            $param['ca_scene_security_person'] = $data[$i][47];
            $param['ca_translation_person'] = $data[$i][48];
            $param['ca_error'] = $data[$i][49];
            $param['ca_error_resaon'] = $data[$i][50];
            $param['ca_make_today_hh'] = $data[$i][51];
            $param['ca_pub_date'] = $data[$i][52];
            $param['ca_store_doc_person'] = $data[$i][53];
            $param['ca_transfer_unit'] = $data[$i][54];
            $param['ca_store_doc_address'] = $data[$i][55];
            $param['ca_improve_suggestion'] = $data[$i][56];
            $param['ca_status'] = 1; //正常状态
            $res_add = $this->add($param);
            if ($res_add === FALSE) {
                $flag = $flag - 1;
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
