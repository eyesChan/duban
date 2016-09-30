<?php

namespace Manage\Model;

use Think\Model;

/**
 * 角色管理模型
 *
 * @author Wu Yang <wu.yang@pactera.com>
 * @reateTime 2016-07-05
 * @lastUpdateTime 2016-07-08
 */
class AuthGroupModel extends Model {

    protected $trueTableName = 'db_auth_group';

    /**
     *
     * 自动验证
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct();
        $this->_validate = array(
            array('title', 'require', C('ROLE.ROLE_FORMAT_ERROR')),
            array('title', '1,30', C('ROLE.ROLE_FORMAT_ERROR'), 0, length),
            array('describe', '0,100', C('ROLE.ROLE_DESC_ERROR'), 0, length),
        );
    }

    /**
     * 角色删除
     * @param  $id 角色id
     * @param array $group_access 角色关键信息
     * @return boolean 返回结果 false表示删除失败,0表示没有可删除的数据,非0删除的条数
     */
    public function delRole($id) {
        if (!strpos($id, ',')) {
            $role_name = $this->where(array('id' => $id))->getField('title');
        }
        //查询关联表数据
        $group_access = D('auth_group_access')
                ->where(array('group_id' => array('in', $id)))
                ->select();
        if ($group_access) {
            if (strpos($id, ',')) {
                writeOperationLog('批量删除角色', 0);
            } else {
                writeOperationLog('删除“' . $role_name . '”角色', 0);
            }
            return 2;
        }
        $data['status'] = 0;
        $role_mod = D('auth_group');
        //改变删除状态
        $result = $role_mod
                ->where(array('id' => array('in', $id)))
                ->save($data);
        if ($result) {
            if (strpos($id, ',')) {
                writeOperationLog('批量删除角色', 1);
            } else {
                writeOperationLog('删除“' . $role_name . '”角色', 1);
            }
            return 3;
        } else {
            if (strpos($id, ',')) {
                writeOperationLog('批量删除角色', 0);
            } else {
                writeOperationLog('删除“' . $role_name . '”角色', 0);
            }
            return 1;
        }
    }

    /**
     * 角色授权数据处理
     * @param array $data
     * @return boolean 成功返回true 失败返回false
     */
    public function updateData($data) {
        if (empty($data['id'])) {
            return FALSE;
        }
        $result = D('auth_group')->save($data);
        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 角色添加
     * @param array $data
     * @return array
     */
    public function addRole($data) {
        $role_name = $data['title'];
        $flag = $this->checkRoleName($role_name);
        if ($flag) {
            writeOperationLog('添加“' . $role_name . '”角色', 0);
            return C('ROLE.ROLE_ADD_ISHAVE');
        } else {
            $res_add = $this->add($data);
            if ($res_add === FALSE) {
                writeOperationLog('添加“' . $role_name . '”角色', 0);
                return C('COMMON.ERROR_ADD');
            } else {
                writeOperationLog('添加“' . $role_name . '”角色', 1);
                return C('COMMON.SUCCESS_ADD');
            }
        }
    }

    /**
     * 检查‘角色名称’是否已存在，true:存在;false:不存在
     * @param int $role_name
     * @return boolean
     */
    public function checkRoleName($role_name) {

        $flag = TRUE;
        $where['title'] = $role_name;
        $where['status'] = 1; //未删除
        $arr_res = $this->where($where)->select();
        if (count($arr_res) > 0) {
            return $flag;
        } else {
            $flag = FALSE;
            return $flag;
        }
    }

}
