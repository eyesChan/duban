<?php

namespace Manage\Controller;

/**
 * 后台首页
 *
 * @author Wu Yang <wu.yang@pactera.com>
 * @createTime 2016-07-26
 * @lastUpdateTime 2016-07-27
 * 
 */
class IndexController extends AdminController {

    private $mod_index;

    public function __construct() {
        parent::__construct();
        $this->mod_index = D('Index');
    }

    /**
     * 首页展示
     * @param array $info 公告集合
     * @param int $length 标题字符串长度限制
     * @param return object 公告信息
     */
    public function index() {

        $list = $this->mod_index->getDataForList();
        $this->assign('list', $list);
        $this->display();
    }

}
