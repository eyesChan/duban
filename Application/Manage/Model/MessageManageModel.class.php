<?php

namespace Manage\Model;

use Think\Model;

/**
 * 系统消息模型类，负责查询列表数据、根据消息ID获取单条数据、生成查询条件、添加入库、编辑入库、删除动作
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

    /**
     * 获取查询条件所需数据
     * 
     * @param array $params
     * @return array
     */
    public function getDataForSearch() {

        $mod_config_system = M('config_system');
        $where['config_key'] = 'msg_sys_status';
        $where['config_value'] = array('NEQ', 4); //排除‘已删除’状态
        $arr_for_search['msg_sys_type'] = $mod_config_system->where($where)->select();

        return $arr_for_search;
    }

    /**
     * 获取列表所需数据
     * 
     * @param array $params
     * @return array
     */
    public function getDataForList($params) {

        $this->checkAndResetStatus();
        $arr_for_list = array();

        $where = $this->makeWhereForSearch($params);
        $page = $params['p'];
        $arr_for_list['msg_sys'] = $this->where($where)
                ->join('db_member as member ON db_message_sys.user_id = member.uid')
                ->order('msg_sys_creattime desc')
                ->page($page, 10)
                ->getField('msg_sys_id,msg_sys_title,msg_sys_starttime,msg_sys_endtime,msg_sys_status,member.name as creatname,msg_sys_creattime', TRUE);
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
     * @param int $msg_sys_id
     * @return array
     */
    public function getDataById($msg_sys_id) {

        $where['msg_sys_id'] = $msg_sys_id;
        $res = $this->where($where)->find();
        $res['msg_sys_starttime'] = date("Y-m-d", strtotime($res['msg_sys_starttime']));
        $res['msg_sys_endtime'] = date("Y-m-d", strtotime($res['msg_sys_endtime']));
        $res['msg_sys_content'] = htmlspecialchars_decode($res['msg_sys_content']);
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

        if (isset($params['msg_sys_status']) && ($params['msg_sys_status'] != '')) {
            $where['msg_sys_status'] = $params['msg_sys_status'];
        } else {
            $where['msg_sys_status'] = array('NEQ', 4);
        }
        if (!empty($params['msg_sys_title'])) {
            $where['msg_sys_title'] = array('LIKE', "%" . $params['msg_sys_title'] . "%");
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
                writeOperationLog('添加“' . $data['msg_sys_title'] . '”系统消息', 0);
                return C('COMMON.ERROR_ADD');
            } else {
                writeOperationLog('添加“' . $data['msg_sys_title'] . '”系统消息', 1);
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
        $data['msg_sys_updatetime'] = date('Y-m-d H:i:s');
        //后台数据验证
        $msg_sys_data = $this->create($data);
        //入库
        if ($msg_sys_data) {
            $where['msg_sys_id'] = $msg_sys_data['msg_sys_id'];
            $res_update = $this->where($where)->save($msg_sys_data);
            if (FALSE === $res_update) {
                writeOperationLog('修改“' . $data['msg_sys_title'] . '”系统消息', 0);
                return C('COMMON.ERROR_EDIT');
            } else {
                writeOperationLog('修改“' . $data['msg_sys_title'] . '”系统消息', 1);
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
     * @param int $msg_sys_id
     * @return array
     */
    public function doDelete($msg_sys_id) {

        $msg_sys_data = array();
        $msg_sys_data['msg_sys_id'] = $msg_sys_id;
        $msg_sys_data['msg_sys_status'] = 4; //删除状态
        $msg_sys_data['msg_sys_title'] = $this->where("msg_sys_id = $msg_sys_id")->getField('msg_sys_title');
        $res_delete = $this->save($msg_sys_data);
        if ($res_delete == 1) {
            writeOperationLog('删除“' . $msg_sys_data['msg_sys_title'] . '”系统消息', 1);
            return C('COMMON.DEL_SUCCESS');
        } else {
            writeOperationLog('删除“' . $msg_sys_data['msg_sys_title'] . '”系统消息', 0);
            return C('COMMON.DEL_ERROR');
        }
    }

    /**
     * 每次查询前，批量验证并修改失效状态
     */
    public function checkAndResetStatus() {
        //3=>已失效状态;4=>已删除状态
        $today = date('Y-m-d');
        $arr_id = $this->where("(msg_sys_status != 4 ) AND (msg_sys_status != 3 ) AND (msg_sys_endtime < '$today')")->getField('msg_sys_id', TRUE);
        if (count($arr_id) > 0) {
            $str_id = implode(',', $arr_id);
            $map['msg_sys_id'] = array('IN', $str_id);
            $this->where($map)->setField('msg_sys_status', 3);
        }
    }

    /**
     * 获取前台显示系统消息列表所需数据
     * 
     * @return array
     */
    public function getDataForShowList() {
        
        $this->checkAndResetStatus();
        $where['msg_sys_status'] = 1;//已发布状态
        $data = $this->where($where)->getField('msg_sys_id,msg_sys_title,msg_sys_creattime',TRUE);
        
        return $data;
    }

    /**
     * 获取前台显示系统消息详情所需数据
     * 
     * @return array
     */
    public function getDataForShowDetail($msg_sys_id) {
        
        $where['msg_sys_id'] = $msg_sys_id;
        $data = $this->where($where)->find();
        
        return $data;
    }
}
