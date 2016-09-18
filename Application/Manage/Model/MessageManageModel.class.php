<?php

namespace Manage\Model;

use Think\Model;

/**
 * Description of MessageManageModel
 *
 * @author chengyayu
 */
class MessageManageModel extends Model {

    protected $trueTableName = 'db_message_sys';
    protected $_validate = array();

    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->_validate = array(
            array('dept_id', 'require', C('EXPENSERULECHECK.DEPT_ID_REQUIRE')),
        );
    }

    //获取列表所需数据
    public function getDataForList($params) {

        $arr_for_list = array();
        $mod_msg_sys = M('message_sys');
        
        $where = $this->makeWhereForSearch($params);
        $page = $params['p'];
        $arr_for_list['msg_sys'] = $mod_msg_sys->where($where)->order('msg_sys_creattime desc')->page($page, 10)->select();

        $count = $mod_msg_sys->where($where)->count();
        $Page = new \Think\Page($count, 10);
        foreach ($params as $k => $v) {
            $Page->parameter[$k] = urlencode($v);
        }
        $arr_for_list['page_show'] = $Page->show();

        return $arr_for_list;
    }

    //获取查询条件
    public function makeWhereForSearch($params) {

        $where = array();

        if (isset($params['msg_sys_status']) && ($params['msg_sys_status'] != '')) {
            $where['msg_sys_status'] = $params['msg_sys_status'];
        }
        if (!empty($params['msg_sys_title'])) {
            $where['msg_sys_title'] = array('LIKE', "%" . $params['msg_sys_title'] . "%");
        }

        return $where;
    }

}
