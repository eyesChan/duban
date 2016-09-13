<?php

/*
 * DepartmentModel.class.php
 * 部门管理模型类文件
 */

namespace Manage\Model;

use Think\Model;

/**
 * DepartmentModel主要完成对于‘部门管理’部分的增、删、改、查等数据操作的封装
 *
 * @author chengyayu
 */
class DepartmentModel extends Model {

    /**
     * 获取添加页面中‘上级部门’下拉框的数据集
     *
     * @param   array   $arr    部门信息数据集
     * @param   int     $pid    父级部门ID
     * @return  array           返回下拉框所需要的树形数据集
     */
    public function getDownTreeData($arr, $pid = 0) {
        $res = array();
        foreach ($arr as $v) {
            if ($v['dept_pid'] == $pid) {
                $res[] = $v;
                $res = array_merge($res, $this->getDownTreeData($arr, $v['dept_id']));
            }
        }
        return $res;
    }
}
