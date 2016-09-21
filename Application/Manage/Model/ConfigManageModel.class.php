<?php

namespace Manage\Model;
use Think\Model;

/**
 * 
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
                ->order('config_sort asc')
                ->page($page, 10)
                ->getField('config_id,config_key,config_name,config_descripion,config_value,config_status,config_change_time', TRUE);
        foreach ($arr_for_list['config_items'] as $key => $value) {
            if($value['config_status'] == 0){
                $arr_for_list['config_items'][$key]['config_status_name'] = '停用';
            }elseif($value['config_status'] == 1){
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

        if (isset($params['config_key']) && ($params['config_key'] != '')) {
            $where['config_key'] = $params['config_key'];
        }
        if (!empty($params['config_descripion'])) {
            $where['config_descripion'] = array('LIKE', "%" . $params['config_descripion'] . "%");
        }

        return $where;
    }

}
