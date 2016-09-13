<?php

namespace Manage\Controller;

/**
 * 操作日志类
 *
 * @author zhangjiawang
 * @createTime 2016-07-06
 * @lastUpdateTime 2016-07-13
 */
class OperationLogController extends AdminController {

    //操作日志列表
    public function index() {
        $mod = D('operationLog');
        $param = I();
        //处理查询条件：操作人姓名、IP地址、模块名称、操作内容、开始时间 结束时间 
        $param['nickname'] != '' ? $where['nickname'] = array('like', '%' . $param['nickname'] . '%') : '';
        $param['ip_address'] != '' ? $where['ip_address'] = array('like', '%' . $param['ip_address'] . '%') : '';
        $param['moudle_name'] != '' ? $where['moudle_name'] = array('like', '%' . $param['moudle_name'] . '%') : '';
        $param['log_content'] != '' ? $where['log_content'] = array('like', '%' . $param['log_content'] . '%') : '';
        if (!empty($param['begin_time']) && empty($param['end_time'])) {
            $where['time'] = array('EGT', $param['begin_time'] . ' 00:00:00');
        }
        if (empty($param['begin_time']) && !empty($param['end_time'])) {
            $where['time'] = array('ELT', $param['end_time'] . ' 23:59:59');
        }
        if (!empty($param['begin_time']) && !empty($param['end_time'])) {
            $where['time'] = array('BETWEEN', array($param['begin_time'] . ' 00:00:00', $param['end_time'] . ' 23:59:59'));
        }
        $where = $this->escape($where);

        $count = $mod->getOperationLogCount($where);
        $page = new \Think\Page($count, 10);
        $list = $mod->getList($where, $page->firstRow, $page->listRows);
        foreach ($param as $key => $val) {
            $page->parameter[$key] = $val;
        }
        $show = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('param', $param);
        $this->display();
    }

    /**
     * 对数组进行转义
     * 
     * @param array 需要转义的数组
     * @return array 返回转义后的数组
     */
    public function escape($param) {
        foreach ($param as $k => $val) {
            $param[$k] = str_replace("_", "\_", $val);
        }
        
        return $param;
    }

}
