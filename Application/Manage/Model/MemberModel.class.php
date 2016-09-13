<?php

namespace Manage\Model;

use Think\Model;

/**
 * 用户操作模型
 * 
 * @author Qiang Liu <qiang.liu17@pactera.com>
 * @version 1.0
 * @createTime 2016-07-08
 * @lastUpdateTime 2016-07-08
 */
class MemberModel extends Model {

    /**
     * 通过nickname获取用户信息
     * 
     * @param string $nickname
     * @return array 用户信息
     */
    public function getUserinfoByNickname($nickname) {
        return $this->where(array('nickname' => $nickname))->find();
    }

}
