<?php

// +----------------------------------------------------------------------
// | 幼儿园系统
// +----------------------------------------------------------------------
// | Copyright (c) 2016 WenSiHaiHui All rights reserved.
// +----------------------------------------------------------------------
// | Author: lizuofeng 
// +----------------------------------------------------------------------

namespace Manage\Model;

use Think\Model;

/**
 * 模块管理
 * @author lizuofeng
 */
class AuthRuleModel extends Model {

    protected $tableName = 'auth_rule';

    /**
     * 模块添加处理
     * @author lizuofeng
     * @return 11 路径已经存在  12 模块编码已经存在 1 添加成功 0 添加失败
     */
    public function addDo($param) {
        //判断是否已经存在该方法
        if (I('post.menu_url') != '#') {
            $where2 = array('status' => 1, 'menu_num' => trim(I('post.menu_num')));
            if (D('AuthRule')->where($where2)->select()) {
                return 12;
            }
        }
        $view_premission = implode(',', $param['see']); //数组转化字符串
        $level = explode('-', $param['level']); //数组转化字符串

        $arr['menu_num'] = $param['menu_num']; //模块标识
        $arr['title'] = $param['menu_name']; //模块名称
        $arr['level'] = $level[0]; //模块等级
        $arr['pid'] = $level[1]==1?0:$level[1]; //父模块ID
        $arr['name'] = $param['menu_url']; //文件路径
        $arr['tip'] = $param['mark']; //备注
        $arr['view_premission'] = $view_premission; //文件路径
        $arr['hide'] = $param['hide']; //显示/隐藏

        $res = $this->add($arr);
        if ($res) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 模块修改处理
     * @author lizuofeng
     * @return 11 路径已经存在  12 模块编码已经存在 1 添加成功 0 添加失败
     */
    public function editDo($param) {
        //判断是否已经存在该方法
        if (I('post.menu_url') != '#') {
            $where['status'] = 1;
            $where['menu_num'] = trim(I('post.menu_num'));
            $where['id'] = array('neq', $param['id']);
            if (D('AuthRule')->where($where)->select()) {
                return 12;
            }
        }
        //$param['level']代表pid字段
        if ($param['level'] == 1) {
            $level_p = 0;
        } else {
            $map['id'] = $param['level'];
            $level_p = D('AuthRule')->where($map)->getField('level');
        }

        $arr['level'] = intval($level_p) + 1;
        $arr['menu_num'] = $param['menu_num']; //模块标识
        $arr['title'] = $param['menu_name']; //模块名称
        $arr['pid'] = $param['level'] == 1 ? 0 : $param['level']; //父模块ID
        $arr['name'] = $param['menu_url']; //文件路径
        $arr['tip'] = $param['mark']; //备注
        $arr['view_premission'] = $param['view_premission']; //文件路径
        $arr['hide'] = $param['hide']; //显示/隐藏
        $res = $this->where('id = ' . I('post.id'))->save($arr);
        if ($res === FALSE) {
            return 0;
        }
        return 1;
    }

    /**
     * 模块菜单获取
     * @author lizuofeng
     * @return array 模块菜单数组
     */
    public function getMoudel($level) {
        //条件
        $where['status'] = 1;
        $where['id'] = array('neq', 1);
        $where['level'] = $level;

        //获取节点
        $module_arr = $this->where($where)->order('sort asc')->select();
        return $module_arr;
    }

    /**
     * 模块菜单获取
     * @author lizuofeng
     * @return array 子节点数组
     */
    public function getChild($pid) {
        //条件
        $where['status'] = 1;
        $where['pid'] = $pid;

        //获取节点
        $module_arr = $this->where($where)->select();
        return $module_arr;
    }

}
