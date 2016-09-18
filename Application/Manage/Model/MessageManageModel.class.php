<?php

namespace Manage\Model;

use Think\Model;

/**
 * Description of MessageManageModel
 *
 * @author chengyayu
 */
class MessageManageModel extends Model{
    
    protected $trueTableName = 'db_message_sys';
    protected $_validate = array();

    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->_validate = array(
            array('dept_id', 'require', C('EXPENSERULECHECK.DEPT_ID_REQUIRE')),
        );
    }
    
    
}
