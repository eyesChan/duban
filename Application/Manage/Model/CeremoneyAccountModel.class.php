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
        for ($i = 0; $i < $count; $i++) {
            $data['ca_time'] = $data[$i][0];
            $data['ca_address'] = $data[$i][1];
            $data['ca_name'] = $data[$i][2];
            $data['ca_double'] = $data[$i][3];
            $data['ca_host'] = $data[$i][4];
            $data['ca_say_leader'] = $data[$i][5];
            $data['ca_zq_leader'] = $data[$i][6];
            $data['ca_qy_leader'] = $data[$i][7];
            $data['ca_participants'] = $data[$i][8];
            $data['ca_security_level'] = $data[$i][9];
            $data['ca_dress'] = $data[$i][10];
            $data['ca_security_person'] = $data[$i][11];
            $data['ca_security_time'] = $data[$i][12];
            $data['ca_yc_rpperson'] = $data[$i][13];
            $data['ca_yc_apstatus'] = $data[$i][14];
            $data['ca_xy_rpperson'] = $data[$i][15];
            $data['ca_xy_apstatus'] = $data[$i][16];
            $data['ca_notice_send_meeting'] = $data[$i][17];
            $data['ca_notice_material_prepara'] = $data[$i][18];
            $data['ca_notice_service_unit_security'] = $data[$i][19];
            $data['ca_ready_agenda'] = $data[$i][20];
            $data['ca_ready_leader_saydoc'] = $data[$i][21];
            $data['ca_ready_visitor_resume'] = $data[$i][22];
            $data['ca_ready_host_doc'] = $data[$i][23];
            $data['ca_ready_sign_book'] = $data[$i][24];
            $data['ca_ready_computer'] = $data[$i][25];
            $data['ca_ready_recorder'] = $data[$i][26];
            $data['ca_ready_microphone'] = $data[$i][27];
            $data['ca_ready_pub_material'] = $data[$i][28];
            $data['ca_ready_sign_boop'] = $data[$i][29];
            $data['ca_ready_sign_pen'] = $data[$i][30];
            $data['ca_ready_sign_music'] = $data[$i][31];
            $data['ca_ready_champagne'] = $data[$i][32];
            $data['ca_ready_gift'] = $data[$i][33];
            $data['ca_ready_table_flower'] = $data[$i][34];
            $data['ca_ready_bg_plate'] = $data[$i][35];
            $data['ca_make_sign_table_card'] = $data[$i][36];
            $data['ca_make_kt_plate'] = $data[$i][37];
            $data['ca_ready_table_card'] = $data[$i][38];
            $data['ca_ready_frame_picture'] = $data[$i][39];
            $data['ca_ready_role_screen'] = $data[$i][40];
            $data['ca_do_site_layout'] = $data[$i][41];
            $data['ca_do_bg_plate'] = $data[$i][42];
            $data['ca_test_microphone'] = $data[$i][43];
            $data['ca_test_audio'] = $data[$i][44];
            $data['ca_notice_jdbz'] = $data[$i][45];
            $data['ca_recep_person'] = $data[$i][46];
            $data['ca_scene_security_person'] = $data[$i][47];
            $data['ca_translation_person'] = $data[$i][48];
            $data['ca_error'] = $data[$i][49];
            $data['ca_error_resaon'] = $data[$i][50];
            $data['ca_make_today_hh'] = $data[$i][51];
            $data['ca_pub_date'] = $data[$i][52];
            $data['ca_store_doc_person'] = $data[$i][53];
            $data['ca_transfer_unit'] = $data[$i][54];
            $data['ca_store_doc_address'] = $data[$i][55];
            $data['ca_improve_suggestion'] = $data[$i][56];
            $data['ca_status'] = 1; //正常状态
            $this->add($data);
        }
    }

}
