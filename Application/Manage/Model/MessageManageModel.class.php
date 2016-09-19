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

    //获取单条数据信息
    public function getDataById($msg_sys_id) {

        $where['msg_sys_id'] = $msg_sys_id;
        $res = $this->where($where)->find();
        $res['msg_sys_starttime'] = date("Y-m-d", strtotime($res['msg_sys_starttime']));
        $res['msg_sys_endtime'] = date("Y-m-d", strtotime($res['msg_sys_endtime']));
        $res['msg_sys_content'] = htmlspecialchars_decode($res['msg_sys_content']);
        return $res;
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

    //添加
    public function doAdd($data) {

        //完善待插入数据
        $data['user_id'] = UID;
        $data['msg_sys_creattime'] = date('Y-m-d H:i:s');
        $data['msg_sys_updatetime'] = date('Y-m-d H:i:s');
        //后台数据验证
        $msg_sys_data = $this->create($data);
        //入库
        if ($msg_sys_data) {
            $res_add = $this->add($msg_sys_data);
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

    //编辑
    public function doEdit($data) {

        //完善待插入数据
        $data['msg_sys_updatetime'] = date('Y-m-d H:i:s');
        //后台数据验证
        $msg_sys_data = $this->create($data);
        //入库
        if ($msg_sys_data) {
            $where['msg_sys_id'] = $msg_sys_data['msg_sys_id'];
            $res_update = $this->where($where)->save($msg_sys_data);
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
