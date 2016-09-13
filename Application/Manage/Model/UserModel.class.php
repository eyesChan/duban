<?php

namespace Manage\Model;

use Think\Model;

/**
 *  Description   后台用户的管理操作
 *  @author       lishuaijie <shuaijie.li@pactera.com>
 *  Date          2016/07/05
 */
class UserModel extends Model {

    protected $tableName = 'member';
    protected $_validate = array();
    protected $_map = array(
        'new_password' => 'password',
    );

    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct();
        $this->_validate = array(
            array('sex', array(0, 1), C('FORMCHECK.SEX_ERROR'), 0, 'in'),
            array('email', 'email', C('FORMCHEKC.EMAIL_ERROR'), 2),
            array('description', '1,100', C('FORMCHECK.LENGTH_ERROR'), 2, 'length'),
            array('password', 'require', C('FORMCHECK.PWD_REQUIRE')),
            array('password', '6,32', C('FORMCHECK.PWD_LENGTH'), 0, 'length'),
        );
    }

    /**
     * 用户入库
     * @access public
     * @param array $data 添加用户所需信息
     * @return int/boole 成功返回插入ID 失败返回false 
     */
    public function insertData($data) {
        $model = D('member');
        $model->startTrans();
        if (empty($data)) {
            return 0;
        }
        $insert_id = $model->add($data);
        if ($insert_id) {
            //添加关联表
            $auth_id = D('auth_group_access')->add(array('uid' => $insert_id, 'group_id' => 0));
            if ($auth_id) {
                $model->commit();
                return $insert_id;
            }
            $model->rollback();
            return false;
        }
        return false;
    }

    /**
     * 修改用户
     * @access public
     * @param  $data 添加用户所需信息
     * @return int/boole 成功返回影响行数 失败返回false 
     */
    public function saveUser($data) {
        if (false !== D('member')->save($data)) {
            return true;
        }
        return false;
    }

    /**
     * 验证nickname唯一
     * @access public
     * @param array $data nicname 值
     * @return boole 成功返回true 失败返回false
     */
    public function checkNickName($data) {
        if ($data['uid']) {
            $where['nickname'] = $data['nickname'];
            $where['uid'] = array('NEQ',$data['uid']);
        } else {
            $where['nickname'] = $data['nickname'];
        }
        $where['status'] = array('LT',3);
        $info = D('member')->where($where)->find();
        if (empty($info)) {
            return true;
        }
        return false;
    }

    /**
     * 验证手机合法
     * @access public
     * @param  $mobile 手机号
     * @return boole 成功返回true 失败返回false
     * 
     */
    public function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    /**
     * 用户分配角色
     * @access public
     * @param  $data 角色ID和权限ID
     * @return boole  成功 true 失败false  
     * 
     */
    public function userRole($data, $uid) {
        D("auth_group_access")->where(array('uid' => $uid))->delete();
        foreach ($data as $val) {
            $info = array('uid' => $uid, 'group_id' => $val);
            $id = D('auth_group_access')->add($info);
        }
        if ($id) {
            return $id;
        }
        if(empty($data)){
            return true;
        }
        return false;
    }

    /**
     * 删除用户（同时删除关联表数据）
     * @access public
     * @param  $data 删除的用户ID
     * @return boole  成功true 失败false 
     */
    public function delUser($data) {
        $model = new UserModel();
        $del_id = $data;
        $uid_now = session('S_USER_INFO.UID');
        $uid_info = explode( ',',$del_id);
        if(in_array($uid_now,$uid_info)){
            return C('USER.USER_ERROR');
        }
        $model->startTrans();
        $where = array('uid'=>array('in',$del_id));
        $mem_id = $model->where($where)->save(array('status'=>3));;
        //判断用户时候以分配角色
        $selec_data = D('auth_group_access')
                ->where(array('uid' => array('in', $del_id)))
                ->select();
        if ($selec_data) {
            $group_id = D('auth_group_access')->where(array('uid' => array('in', $del_id)))->delete();
        } else {
            //未分配角色 默认删除关联表成功
            $group_id = true;
        }
        if (false !== $mem_id && false !== $group_id) {
            $model->commit();
            return C("COMMON.SUCCESS_DEL");
        }
        $model->rollback();
        return C('CONNON.ERROR_DEL');
    }

    /**
     * 修改用户状态（启用/禁用）
     * @access public
     * @param  $data 删除的用户ID
     * @return boole  成功true 失败false 
     */
    public function updateUser($data) {
        $model = D('Member');
        if (false !== $model->save($data)) {
            return true;
        }
        return false;
    }

    /**
     * 重置密码
     * @access public
     * @param $data 重置密码用户id 值
     * @return boole 成功返回true 失败返回false
     */
    public function resetPwd($data) {
        $model = new UserModel();
        $local_pwd = md5(md5(C('RESET_PWD')));
        $user_id = $data;
        $update_status = $model->where(array('uid' => array('in', $user_id)))->save(array('password' => $local_pwd));
        if (false !== $update_status) {
            if (strpos($user_id, ',')) {
                writeOperationLog('批量重置用户密码', 1);
            } else {
                $user_name = $this->where("uid = $user_id")->getField('nickname');
                writeOperationLog('重置“' . $user_name . '”用户密码', 1);
            }
            return true;
        }
        writeOperationLog('重置“' . $user_name . '”用户密码', 0);
        return false;
    }

    /**
     * 获取用户角色
     * @access public
     * @param  $data 用户id
     * @return $role_info 用户角色列表
     */
    public function getRole($data) {
        $model = new UserModel();
        $role_info = $model->alias('M')
                ->join('__AUTH_GROUP_ACCESS__ A ON M.uid = A.uid')
                ->join('__AUTH_GROUP__ G ON A.group_id = G.id')
                ->field('M.uid,G.id,G.title,G.describe,M.nickname')
                ->where(array('M.uid' => array('in', $data),'G.status'=>1))
                ->order('M.uid desc')
                ->select();
        return $role_info;
    }

    /**
     * 获取用户信息
     * @access public
     * @param  $id    用户id
     * @return $info 用户信息
     */
    public function getUser($id) {
        $info = D('member')->alias('M')
                ->find($id);
        return $info;
    }

    /**
     * 用户搜索分页
     * @access public
     * @param $page 当前页码
     * @param $page_num 每页显示条数
     * @param $data 搜索数据
     * @return $list  搜索结果
     */
    public function getData($first_rows, $list_rows, $data = '') {
        $where['status'] = array('LT',3);
        if (!empty($data)) {
            if (0 != $data['status'])
                $where['status'] = $data['status'];
            if (0 != $data['department_id'])
            $where['department_id'] = $data['department_id'];
            if (!empty($data['name']))
            $where['name'] = array('like',"%$data[name]%");
            if (!empty($data['nickname']) || $data['nickname'] == 0){
                $data['nickname'] = str_replace("_", "\_", $data['nickname']);
                $where['nickname'] = array('like',"%$data[nickname]%");
            }
                
        }
        $user = \D('Member');
        $list = $user->alias('M')
                ->where($where)
                ->limit($first_rows, $list_rows)
                ->order('M.uid desc')
                ->select();
        return $list;
        
    }

    /**
     * 获取搜索后的条数
     * @access public
     * @param $data 搜搜条件
     * @return $list 获取搜索后的条数
     */
    public function count($data) {
        $where = array();
            if (0 != $data['status'])
                $where['status'] = $data[status];
            if (0 != $data['department_id'])
                $where['department_id'] = $data[department_id];
            if (!empty($data['name']))
                $where['nickname'] = array('like',"%$data[name]%");
            if (!empty($data['nickname'])  || $data['nickname'] == 0){
                $data['nickname'] = str_replace("_", "\_", $data['nickname']);
                $where['nickname'] = array('like',"%$data[nickname]%");
            }
            $where['status'] = array('LT',3);
        $user = \D('Member');
        $list = $user->alias('M')
                ->where($where)
                ->count();
        return $list;
    }

    /**
     * 获取所有角色
     * @access public
     * @return $list 返回所有角色
     */
    public function getGroup() {
        $model = M('auth_group');
        $list = $model->where(array('status'=>1))->order('id desc')->select();
        return $list;
    }

    /**
     * 获取用户所属角色
     * @access public
     * @param  $uid  当前用户ID
     * @return $list 返回用户所有角色
     */
    public function getUserGroup($uid) {
        $list = D('auth_group_access')->where(array('uid' => $uid))->select();
        return $list;
    }

}
