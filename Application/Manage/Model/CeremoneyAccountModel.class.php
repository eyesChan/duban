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

}
