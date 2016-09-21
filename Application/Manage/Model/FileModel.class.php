<?php

namespace Manage\Model;

use Think\Model;

/**
 * 文档发布类型
 * 
 * @author huanggang <gang.huang2@pactera.com>
 * @version 1.0
 * @createTime 2016-09-21
 * @lastUpdateTime 2016-09-21
 */
class FileModel extends Model {

    protected $trueTableName = 'db_config_system';
    protected $_validate = array();

    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->_validate = array(
            array('dept_id', 'require', C('EXPENSERULECHECK.DEPT_ID_REQUIRE')),
        );
    }
    /**
     * 
     * 
     * 
     * @return array 文档发布类型
     */
    public function getFileDocType() {
        return $this->where(array('config_key' => 'doc_pub_type'))->select();
    }
    
   

}

