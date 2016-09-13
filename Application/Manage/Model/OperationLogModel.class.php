<?php

namespace Manage\Model;

use Think\Model;
use Manage\Model\UserModel;

/**
 * 操作日志模型
 * 
 * @author zhangjiawang
 * @createTime 2016-07-06
 * @lastUpdateTime 2016-07-13
 */
class OperationLogModel extends Model {

    /**
     * 分页查询操作日志列表
     * 
     * @author zhangjiawang
     * @param array $where 查询条件
     * @param int 查询开始位置
     * @param int 查询条数
     * @return array 成功返回列表
     */
    public function getList($where, $first_rows, $list_rows) {
        $model = new UserModel();
        $list = $this->alias('opl')
                ->join("left join __MEMBER__ AS member on opl.operation_user_id = member.uid")
                ->where($where)
                ->limit($first_rows, $list_rows)
                ->order('id desc')
                ->select();
        return $list;
    }

    /**
     * 根据搜索条件查询有效的操作日志总数量
     * 
     * @author zhangjiawang
     * @param array $where 查询条件数组
     * @return int 返回有效的操作日志总数量
     */
    public function getOperationLogCount($where) {
        $where['opl.status'] = 1;
        $count = $this->alias('opl')
                        ->join("left join __MEMBER__ AS member on opl.operation_user_id = member.uid")
                        ->where($where)->count();
        return $count;
    }

}
