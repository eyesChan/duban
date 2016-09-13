<?php

// +----------------------------------------------------------------------
// | 幼儿园系统
// +----------------------------------------------------------------------
// | Copyright (c) 2016 WenSiHaiHui All rights reserved.
// +----------------------------------------------------------------------
// | Author: lizuofeng <892565767@qq.com> 
// +----------------------------------------------------------------------

namespace Manage\Model;

use Think\Model;

/**
 * 菜单模型
 * @author lizuofeng
 */
class AdminModel extends Model {

    protected $tableName = 'auth_rule';

    public function index() {
	//该用户所有的菜单权限
	if (in_array(UID, C("ADMINISTRATOR"))) {
	    $info = D('AuthRule')->field('id')->where(array('status' => 1))->select();
	    $ids = '';
	    $user_rule = array();
	    foreach($info as $k => $v ){
		$ids  .= $v['id'].',';
	    }
	    $ids = rtrim($ids,',');
	    $user_rule['rules'] = $ids;
	} else {
	    $user_rule = D('auth_group_access')->alias("a")->join(" LEFT JOIN __AUTH_GROUP__ as g  ON a.group_id=g.id")->where("a.uid=" . session('S_USER_INFO.UID'))->field('rules')->select();
	}
        if(empty($user_rule)){
            return array();
        }
        $role_id = '';
        foreach($user_rule as $val){
            $role_id .= $val['rules'].',';
        }
        $role_id = rtrim($role_id,',');
	//查询所有菜单
	$where = array();
	$where['status'] = 1; //状态：1 可用 0 不可用
	$where['hide'] = 0; //是否显示 0 显示  1 隐藏  
        if(!in_array(UID,C('ADMINISTRATOR'))){
            $where['id'] = array('in', $role_id);
        }
	$menu = D('auth_rule')->where($where)->select();
	return $this->getMenus($menu);
    }

    /*
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */

    function getMenus($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0) {
	// 创建Tree
	$tree = array();
	if (is_array($list)) {
	    // 创建基于主键的数组引用
	    $refer = array();
	    foreach ($list as $key => $data) {
		$refer[$data[$pk]] = & $list[$key];
	    }
	    foreach ($list as $key => $data) {
		// 判断是否存在parent
		$parentId = $data[$pid];
		if ($root == $parentId) {
		    $tree[] = & $list[$key];
		} else {
		    if (isset($refer[$parentId])) {
			$parent = & $refer[$parentId];
			$parent[$child][] = & $list[$key];
		    }
		}
	    }
	}
	return $tree;
    }

}
