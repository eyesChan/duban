<?php

namespace Manage\Model;

use Think\Model;

/**
 * 系统参数管理模型类。
 * 查询列表数据、根据消息ID获取单条数据、生成查询条件、添加入库、编辑入库、删除动作
 * 获取某个参数类别下最大序号、获取参数类别数据集
 *
 * @author chengyayu
 */
class ConfigManageModel extends Model {

    protected $trueTableName = 'db_config_system';
    protected $_validate = array();

    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->_validate = array(
            array('dept_id', 'require', C('EXPENSERULECHECK.DEPT_ID_REQUIRE')),
        );
    }

    /**
     * 获取查询条件所需数据
     * 
     * @param array $params
     * @return array
     */
    public function getDataForSearch() {

        $where['config_key'] = 'config_type';
        $arr_for_search['config'] = $this->where($where)->select();

        return $arr_for_search;
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
        $arr_for_list['config_items'] = $this->where($where)
                ->order('config_key asc,config_sort')
                ->page($page, 10)
                ->getField('config_id,config_key,config_name,config_descripion,config_value,config_status,config_change_time', TRUE);
        foreach ($arr_for_list['config_items'] as $key => $value) {
            if ($value['config_status'] == 0) {
                $arr_for_list['config_items'][$key]['config_status_name'] = '停用';
            } elseif ($value['config_status'] == 1) {
                $arr_for_list['config_items'][$key]['config_status_name'] = '启用';
            }
        }
        $count = $this->where($where)->count();
        $Page = new \Think\Page($count, 10);
        foreach ($params as $k => $v) {
            $Page->parameter[$k] = $v;
        }
        $arr_for_list['page_show'] = $Page->show();

        return $arr_for_list;
    }

    /**
     * 获取查询条件数组
     * 
     * @param array $params
     * @return array
     */
    public function makeWhereForSearch($params) {

        $where = array();
        $where['config_key'] = array('NEQ', 'config_type');
        $where['config_status'] = array('NEQ', 3);
        if (isset($params['config_key']) && ($params['config_key'] != '')) {
            $where['config_key'] = $params['config_key'];
        }
        if (!empty($params['config_descripion'])) {
            $where['config_descripion'] = array('LIKE', "%" . $params['config_descripion'] . "%");
        }

        return $where;
    }

    /**
     * 获取单条数据
     * 
     * @param int config_id
     * @return array
     */
    public function getDataById($config_id) {

        $where['config_id'] = $config_id;
        $res = $this->where($where)->find();
        
        return $res;
    }

    /**
     * 获取'参数类别'数据集
     * 
     * @return array
     */
    public function getConfigTypes() {

        $where['config_key'] = 'config_type';
        $arr_config_types = $this->where($where)->select();

        return $arr_config_types;
    }

    /**
     * 根据参数类型标记（config_key）查出目前该类别下最大的序号
     * 
     * @param string $config_key
     * @return int
     */
    public function getBigestSort($config_key) {

        $where['config_key'] = $config_key;
        $arr_sort = $this->where($where)->order('config_sort desc')->getField('config_sort', TRUE);
        $bigest_sort = $arr_sort[0];
        return $bigest_sort;
    }

    /**
     * 添加入库
     * 
     * @param array $data
     * @return array
     */
    public function doAdd($data) {

        //完善待插入数据
        $data['config_system']['config_name'] = $this->where(array('config_key' => $data['config_system']['config_key']))->getField('config_name');
        $now_bigest_sort = $this->getBigestSort($data['config_system']['config_key']);
        $data['config_system']['config_sort'] = $now_bigest_sort + 1;
        $data['config_system']['config_change_time'] = date('Y-m-d H:i:s');
        //后台数据验证
        $config_sys_data = $this->create($data['config_system']);
        //入库
        if ($config_sys_data) {
            $res_add = $this->add($config_sys_data);
            if (FALSE === $res_add) {
                return C('COMMON.ERROR_ADD');
            } else {
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

        //完善待插入数据
        $data['config_name'] = $this->where(array('config_key' => $data['config_key']))->getField('config_name');
        $data['config_change_time'] = date('Y-m-d H:i:s');
        //后台数据验证
        $config_sys_data = $this->create($data);
        //入库
        if ($config_sys_data) {
            $where['config_id'] = $config_sys_data['config_id'];
            $res_update = $this->where($where)->save($config_sys_data);
            if (FALSE === $res_update) {
                return C('COMMON.ERROR_EDIT');
            } else {
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
     * @param int $config_id
     * @return array
     */
    public function doDelete($config_id) {

        $config_data = array();
        $config_data['config_id'] = $config_id;
        $config_data['config_status'] = 3; //删除状态
        $res_delete = $this->save($config_data);
        if ($res_delete == 1) {
            return C('COMMON.DEL_SUCCESS');
        } else {
            return C('COMMON.DEL_ERROR');
        }
    }
    
    /**
     * 修改系统参数状态
     * 
     * @param array $data
     * @return array
     */
    public function changeStatus($data){
        
        //完善待插入数据
        $data['config_change_time'] = date('Y-m-d H:i:s');
        //后台数据验证
        $config_sys_data = $this->create($data);
        //入库
        if ($config_sys_data) {
            $where['config_id'] = $config_sys_data['config_id'];
            $res_update = $this->where($where)->save($config_sys_data);
            if (FALSE === $res_update) {
                return C('COMMON.ERROR_EDIT');
            } else {
                return C('COMMON.SUCCESS_EDIT');
            }
        } else {
            $err_check_update = $this->getError();
            return $err_check_update;
        }
    }

}
