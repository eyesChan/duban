<?php

namespace Manage\Controller;

use Manage\Model\UserModel;
use Manage\Controller\CommonApi\UserController as UserApi;

/**
 *  Description   后台用户的管理操作
 *  @author       lishuaijie <shuaijie.li@pactera.com>
 *  Date          2016/07/05
 */
class UserController extends AdminController {

    public $page = null;
    public $model;

    public function __construct() {
        parent::__construct();
        $this->model = new UserModel();
    }

    /**
     *  显示后台用户列表
     * 
     * @param $page 当前页码
     * @param $page_num 每页显示条数
     * @param $serach_data 搜索条件
     * @return 显示页面
     */
    public function index() {
        $serach_data = I();
        if(!empty($serach_data['nickname']) && intval(I('p')) != 0)
            $serach_data['class_name'] = urldecode ($serach_data['nickname']);
        //带条件查询
        $count = $this->model->count($serach_data);
        $Page = new \Think\Page($count, 10);
        $list = $this->model->getData($Page->firstRow, $Page->listRows, $serach_data);
        foreach ($serach_data as $key => $val) {
            $Page->parameter[$key] = urlencode($val);
        }
        $show = $Page->show();
        //获取用户角色
        $user_id = array();
        foreach ($list as $val) {
            $user_id[] = $val['uid'];
        }
        $role_info = $this->model->getRole(implode(',', $user_id));
        $this->assign('page', $show);
        $this->assign('count', $count);
        $this->assign('info', $serach_data);
        $this->assign('role', $role_info);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 添加用户
     * @param $insert_data 添加数据
     * @return 显示或跳转页面
     */
    public function addUser() {
        $insert_data = I('data');
        if (!empty($insert_data)) {
            $member_api = new UserApi();
            $user_name = $insert_data['nickname'];
            $result = $member_api->saveUser($insert_data);
            if ($result['code'] == 200) {
                writeOperationLog('添加“' . $user_name . '”用户', 1);
                $this->success($result['status'], '/Manage/User/index');
            }
            if ($result['code'] == 100) {
                writeOperationLog('添加“' . $user_name . '”用户', 0);
                $this->error($result['status'], '/Manage/User/index');
            }
            return true;
        }
        $this->assign('submit', '/Manage/User/addUser');
        $this->display('User/saveUser');
    }

    /**
     * 编辑用户
     * @param $insert_data 编辑数据
     * @param $insert_id 用户id
     * @return 显示或跳转页面
     */
    public function saveUser() {
        $insert_data = I('data');
        $insert_id = I('uid');
        //编辑
        if ($insert_id && empty($insert_data)) {
            $data = $this->model->getUser($insert_id);
            $this->assign('data', $data);
            $this->assign('submit', '/Manage/User/saveUser');
            $this->display();
            return true;
        }
        if ($insert_id && $insert_data) {
            $member_api = new UserApi();
            $user_name = $insert_data['nickname'];
            $result = $member_api->saveUser($insert_data, $insert_id);
            if ($result['code'] == 200) {
                writeOperationLog('修改“' . $user_name . '”用户信息', 1);
                $this->success($result['status'], '/Manage/User/index');
            }
            if ($result['code'] == 100) {
                writeOperationLog('修改“' . $user_name . '”用户信息', 0);
                $this->error($result['status'], '/Manage/User/index');
            }
            return true;
        }
    }

    /**
     * 用户分配角色
     * @param $data 分配的权限
     * @param $uid 用户id
     * @return 跳转页面
     */
    public function allocationRole() {
        $data = I();
        $uid = I('uid');
        $user_data = $this->model->getUser($uid);
        $user_name = $user_data['nickname'];
        if (intval($uid) <= 0) {
            $this->ERROR(C('USER.ERROR_ALLOCATIONROLE'), '/Manage/User/index');
        }
        $submit = I('submit');
        if (empty($submit)) {
            $user_info = $this->model->field('nickname,uid')->find($uid);
            //获取所属于的用户组
            $user_group_info = $this->model->getUserGroup($uid);
            $group_info = $this->model->getGroup();
            $this->assign('page', $show);
            $this->assign('group_info', $group_info);
            $this->assign('user_group_info', $user_group_info);
            $this->assign('info', $user_info);
            $this->display();
            return true;
        }
        if ($this->model->userRole($data['childrenBox'], $uid)) {
            writeOperationLog('给“' . $user_name . '”用户分配角色', 1);
            $this->success(C('USER.SUCCESS_ALLOCATIONROLE'), '/Manage/User/index');
            return true;
        }
        writeOperationLog('给“' . $user_name . '”用户分配角色', 0);
        $this->ERROR(C('USER.ERROR_ALLOCATIONROLE'), '/Manage/User/index');
    }

    /**
     * 删除用户
     * @param $user_info 删除用户的id
     * @return 跳转页面
     */
    public function delUser() {
        $user_info = I('uid');
        $user_data = $this->model->getUser($user_info);
        $user_name = $user_data['nickname'];
        if (empty($user_info)) {
            $this->error(C('USER.ERROR_DELETE'), '/Manage/User/index');
        }
        $result = $this->model->delUser($user_info);
        if (200 == $result['code']) {
            if (strpos($user_info, ',')) {
                $result = C('COMMON.SUCCESS_DELS');
                writeOperationLog('批量删除用户', 1);
                $this->success($result['status'], '/Manage/User/index');
                return true;
            }
            writeOperationLog('删除“' . $user_name . '”用户', 1);
            $this->success(C('USER.SUCCESS_DELETE'), '/Manage/User/index');
            return true;
        }
        if(100 == $result['code']){
            writeOperationLog('删除“' . $user_name . '”用户', 0);
            $this->error($result['status'], '/Manage/User/index');
        }
        
    }

    /**
     * 禁用用户
     * @param $user_id 用户id
     * @return 跳转页面
     */
    public function disableUser() {
        $user_id = I('uid');
        if (intval($user_id) >= 0) {
            $user_info = $this->model->find($user_id);
            $user_name = $user_info['nickname'];
            if ($user_info['status'] == 1) {
                //做禁用操作 1为启用 2 为禁用
                $status = 2;
                $handle = '禁用 ';
                $message = C('USER.SUCCESS_DISABLE');
            }
            if ($user_info['status'] == 2) {
                //做启用操作 1为启用 2 为禁用
                $status = 1;
                $handle = '启用 ';
                $message = C('USER.SUCCESS_OPEN');
            }
            $data = array('status' => $status, 'uid' => $user_id);
            if ($this->model->updateUser($data)) {
                writeOperationLog($handle . "“".$user_name . '”用户', 1);
                $this->success($message, '/Manage/User/index');
                return true;
            }
            writeOperationLog($handle . "“".$user_name . '”用户', 0);
            $this->error(C('USER.ERROR_DISABLE'), '/Manage/User/index');
            return true;
        }
        $this->error(C("USER.PARAMTER_ERROR"), '/Manage/User/index');
    }

    /**
     * 重置密码
     * @param $user_id 用户id
     * @return 跳转页面
     */
    public function resetPwd() {
        $user_id = I('uid');

        if ($this->model->resetPwd($user_id) && !empty($user_id)) {
            $result = array(
                'status' => C('USER.SUCCESS_RESET'),
                'code' => '200',
            );
            if (strpos($user_id, ',')) {
                $result = array(
                    'status' => C('USER.SUCCESS_RESETS'),
                    'code' => '200',
                );
            }
            echo json_encode($result);
            return true;
        } else {

            $result = array(
                'status' => C('USER.ERROR_RESET'),
                'code' => '100',
            );
        }
        echo json_decode($result);
    }

    /**
     * 查看用户信息
     * @return 显示页面
     */
    public function showUser() {
        $uid = I('uid');
        $user_info = $this->model->getUser($uid);
        $role_info = D('member')
                ->alias('M')
                ->join('__AUTH_GROUP_ACCESS__ as A on A.uid = M.uid ')
                ->join('__AUTH_GROUP__  as G on G.id = A.group_id')
                ->where('G.status =1 and   M.uid='.$uid)
                ->field('G.title')
                ->select();
        $this->assign('role_info',$role_info);
        $this->assign('user_info', $user_info);
        $this->display();
    }

    /**
     * 验证用户 名唯一
     * 
     * @param json $data 用户id和nickname 
     * @return json 状态码和信息
     */
    public  function checkNickName(){
        $data['uid'] = I('uid');
        $data['nickname'] = I('nickname');
        $result = array();
        if (empty($data)) {
            $result = C('USER.LASK_PARAMTER');
            echo json_encode($result);
            return;
        }
        if (!$this->model->checkNickName($data)) {
            $result = C('USER.NICKNAME_EXISTENCE');
        } else {
            $result = C('COMMON.CHECK_SUCCESS');
        }

        echo json_encode($result);
        return;
    }

}
