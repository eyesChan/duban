<?php

namespace Manage\Controller;

use Manage\Model\AuthGroupModel;

/**
 * 角色管理
 *
 * @author Wu Yang <wu.yang@pactera.com>
 * @createTime 2016-07-05
 * @lastUpdateTime 2016-07-08
 */
class RoleController extends AdminController {

    /**
     *
     * @var int $p 页数 
     */
    public $page = null;
    public $role_mod = null;

    function __construct() {
	parent::__construct();
	$this->page = I('p');
	$this->role_mod = D('auth_group');
	$this->assign('p', $this->page);
    }

    /**
     * 角色首页
     * @param array $param 查询条件
     * @param string $where 条件
     * @return array $info 角色信息
     * 
     */
    public function index() {
	//查询条件
	$param = I('get.');
	$where = array();
	$where['status'] = 1;

	if ($param['search'] != '') {
	    $this->assign('search', $param['search']);
	    $param['search'] = str_replace("_", "\_", $param['search']);
	    $where['title'] = array('like', "%" . $param['search'] . "%");
	}
	$count = $this->role_mod
		->where($where)
		->count(); // 查询满足要求的总记录数
	if ($count > 0) {
	    $page = new \Think\Page($count, 10, $param); // 实例化分页类 传入总记录数和每页显示的记录数
	    $show = $page->show();
	}
	$info = $this->role_mod
		->where($where)
		->order('id DESC')
		->limit($page->firstRow, $page->listRows)
		->select();
	$admin = implode(',', C("ADMINISTRATOR"));
	$this->assign('admin', $admin);
	$this->assign('info', $info);
	$this->assign('page', $show); // 赋值分页输出
	$this->display();
    }

    /**
     * 角色增加
     * @param array $param 接收到的参数
     * @return object 添加成功或失败
     */
    public function add() {
	$param = I('post.');
	if (empty($param['submit'])) {
	    $this->display();
	    exit;
	}
	$param = $this->role_mod->create($param);
	if (!$param) {
	    $this->error($this->role_mod->getError());
	}

	$role_name = $param['title'];
	if ($this->role_mod->add($param) === false) {
	    writeOperationLog('添加“' . $role_name . '”角色', 0);
	    $this->error(C('USER.ERROR_ALLOCATIONROLE'));
	}
	writeOperationLog('添加“' . $role_name . '”角色', 1);
	$this->success(C('USER.SUCCESS_ALLOCATIONROLE'), U('/Manage/Role/index/p/' . $this->p));
    }

    /**
     * 角色编辑
     * @param array $param 接收到的参数
     * @return object 编辑成功或失败
     */
    public function edit() {
	$param = I('');
	if (empty($param['title'])) {
	    //获取角色信息
	    $info = $this->role_mod->where(array('id' => $param['id']))->find();
	    $this->assign('info', $info);
	    $this->display();
	    exit;
	}

	$param = $this->role_mod->create($param);
	$role_info = $this->role_mod->where(array('id' => $param['id']))->find();
	$role_name = $role_info['title'];
	if (!$param) {
	    writeOperationLog('修改“' . $role_name . '”角色信息', 0);
	    $this->error($this->role_mod->getError());
	}
	if ($this->role_mod->save($param) === false) {
	    writeOperationLog('修改“' . $role_name . '”角色信息', 0);
	    $this->error(C('USER.ERROR_ALLOCATIONROLE'));
	}
	writeOperationLog('修改“' . $role_name . '”角色信息', 1);
	$this->success(C('USER.SUCCESS_ALLOCATIONROLE'), U("/Manage/Role/index/p/" . $this->p));
    }

    /**
     * 角色删除
     * @param array $id 接收到的参数
     * @param int $result 数据处理结果
     * @return object 角色删除状态
     */
    public function del() {
	$id = I('get.id');
	if (empty($id)) {
	    return FALSE;
	}
	$flag = I('get.flag');
	$result = $this->role_mod->delRole($id);
	switch ($result) {
	    case 1:
		$this->error(C('COMMON.DEL_ERROR'));
		break;
	    case 2:
		if ($flag == 1) {
		    $this->error(C('ROLE.ROLE_DELETE_ERROR'));
		} else {
		    $this->error(C('ROLE.ROLE_DELETE_ERROR2'));
		}
		break;
	    default :
		if ($flag == 1) {
		    $this->success(C('COMMON.DEL_SUCCESS'), U('/Manage/Role/index/p/' . $this->p));
		} else {
		    $this->success(C('USER.SUCCESS_DELETES'), U('/Manage/Role/index/p/' . $this->p));
		}
	}
    }

    /**
     * 授权列表
     * @param array $id 角色id
     * @param array $role_info 当前角色信息
     * @param array $menu_info 无限级分类处理数据
     * @return object 权限信息
     */
    public function authorizeRole() {
	$id = I('get.id');
	//获取角色信息
	$role_info = $this->role_mod->field('id,title,rules,view_premission')->find($id);
	$role_info['rules'] = explode(',', $role_info['rules']);
	$role_info['view_premission'] = explode(',', $role_info['view_premission']);
	foreach ($role_info['view_premission'] as $v) {
	    $e = explode('-', $v);
	    $pre[$e[0]] = $e[1];
	}
	//获取不受控制的id
	$id_arr = D('auth_rule')->field('id')->where(array('name' => array('in', C("NO__AUTH"))))->select();
	$ids = array();
	foreach ($id_arr as $k => $v) {
	    $ids[] = $v['id'];
	}
	//获取配置值
	$arr = D('auth_rule')->field('id,title,pid')->where(array('id' => array('not in', $ids), 'title' => array('neq', '用户中心')))->select();
	$menu_info = getTree($arr);
	$result = array();
	foreach ($menu_info as $k => $v) {
	    $result[$k]['id'] = $v['id'];
	    $result[$k]['title'] = $v['title'];
	    $result[$k]['is_check'] = in_array($v['id'], $role_info['rules']) ? 1 : 0;
	    foreach ($v['children'] as $vk => $vv) {
		$result[$k]['children'][$vk]['id'] = $vv['id'];
		$result[$k]['children'][$vk]['title'] = $vv['title'];
		$result[$k]['children'][$vk]['pre'] = $pre[$vv['id']];
		$result[$k]['children'][$vk]['is_check'] = in_array($vv['id'], $role_info['rules']) ? 1 : 0;
		foreach ($vv['children'] as $vvk => $vvv) {
		    $result[$k]['children'][$vk]['children'][$vvk]['id'] = $vvv['id'];
		    $result[$k]['children'][$vk]['children'][$vvk]['title'] = $vvv['title'];
		    $result[$k]['children'][$vk]['children'][$vvk]['is_check'] = in_array($vvv['id'], $role_info['rules']) ? 1 : 0;
		}
	    }
	}
	$this->assign('role_info', $role_info);
	$this->assign('menu_info', $result);
	$this->display();
    }

    /**
     * 角色授权保存
     * @param array $param 表单信息
     * @param array $data 角色信息
     * @return object 授权保存状态
     */
    public function save() {
	$param = I('post.');
	//处理数据
	$data = array();
	$data['id'] = $param['id'];
	$data['rules'] = "1," . implode(',', array_unique(explode(',', implode(',', $param['box']))));
	//查询权限筛选
	$res = array();
	foreach ($param['view_premission'] as $k => $v) {
	    $tmp = explode('-', $v);
	    $res[] = $tmp[0];
	}
	$arr = array();
	foreach ($res as $k => $v) {
	    if (in_array($v, explode(',', $data['rules']))) {
		$arr[] = $v;
	    }
	}
	foreach ($arr as $k => $v) {
	    foreach ($param['view_premission'] as $vk => $vv) {
		$tmp2 = explode('-', $vv);
		if ($v == $tmp2[0]) {
		    $data['view_premission'][] = $vv;
		}
	    }
	}
	$data['view_premission'] = implode(',', $data['view_premission']);
	if (empty($data['view_premission'])) {
	    $data['view_premission'] = '';
	}
	$result = $this->role_mod->updateData($data);
	$role_name = $this->role_mod->where(array('id' => $param['id']))->getField('title');
	if ($result === false) {
	    writeOperationLog('对“' . $role_name . '”角色进行赋权', 0);
	    $this->error(C('ROLE.ROLE_DELETE_AUTHORIZE_ERROR'));
	} else {
	    writeOperationLog('对“' . $role_name . '”角色进行赋权', 1);
	    $this->success(C('ROLE.ROLE_DELETE_AUTHORIZE_SUCCESS'), U('/Manage/Role/index/p/' . $this->p));
	}
    }

    /**
     * 前台验证角色唯一
     * @param string $title 角色名称
     * @param array $res 查询结果
     * @return array 返回验证状态
     */
    public function checkRole() {
	$title = I('get.title');
	$id = I('get.id');
	$res = $this->role_mod
		->field('title,id')
		->where(array('title' => $title, 'status' => 1))
		->select();
	if ($res === false) {
	    return false;
	}

	if (!empty($id)) {
	    $flag = false;
	    if (count($res) == 1 && $id == $res[0]['id']) {
		$flag = true;
	    }
	}
	if (empty($res) || $flag == true) {
	    $this->ajaxReturn(C('USER.CHECK_ROLE_PASS'));
	} else {
	    $this->ajaxReturn(C('USER.CHECK_ROLE_ERROR'));
	}
    }

    /**
     * 验证是否存在关联用户
     * @param string $ids 角色id
     * @param array $res 查询结果
     * @return array 返回验证状态
     */
    public function checkRelation($id) {
	$flag = I('get.flag');
	//查询关联表数据
	$res = D('auth_group_access')
		->where(array('group_id' => array('in', $id)))
		->select();
	if ($res === false) {
	    return false;
	}
	if (!empty($res)) {
	    if ($flag) {
		$this->ajaxReturn(C('ROLE.ROLE_DELETE_RELATION1'));
	    } else {
		$this->ajaxReturn(C('ROLE.ROLE_DELETE_RELATION'));
	    }
	} else {
	    $this->ajaxReturn(C('ROLE.ROLE_DELETE_RELATION2'));
	}
    }

}
