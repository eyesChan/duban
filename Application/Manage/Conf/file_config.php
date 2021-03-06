﻿<?php
return array(
    'FILE_VISIT_PATH' => 'http://www.haihang.com/', //浏览器访问路径 例如:www.duban.com/XXXX/XXX/a.jpg
    'FILE_ROOT_PATH' => 'Public/',
    'FILE_WRITE_PATH'=>'http://www.haihang.com/',
    'FILE_MEETING' => array(//会议相关文件上传
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'meeting/', //临时存储路径
        'ALLOW_FILE' => array('xlsx', 'xls','png'),
    ),
    'FILE_DOC' => array(//文档附件文件上传
        'FILE_SIZE' => 20971520,
        'FILE_PATH' => 'filedoc/', //临时存储路径
        'ALLOW_FILE' => array('xlsx', 'xls', 'doc', 'pdf', 'jpeg','docx'),
    ),
    'FILE_COVER' => array(//文档附件封面文件上传
        'FILE_SIZE' => 524288,
        'FILE_PATH' => 'filecover/', //临时存储路径
        'ALLOW_FILE' => array('jpg', 'png'),
    ),
    'FILE_INTERNALMEETING' => array(//内部会议相关文件上传
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'internalMeeting/', //临时存储路径
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_CA_MEETING' => array(//内部会议相关文件上传
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'caMeeting/', //临时存储路径
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_IMPORT_EXCEL' => array(//模板导入
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'meeting_import/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_RESIDENT_EXCEL' => array(//驻各地 发展情况模板导入
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'resident_import/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_LEDGER_EXCEL' => array(//会谈会见模板导入
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'ledger_import/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_PRESENT_EXCEL' => array(//文稿模板导入
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'present_import/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_INTERNALMEETING_EXCEL' => array(//模板导入
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'internalmeeting_import/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
    ),
    'FILE_PUB_DOC' => array(//文档上传的类型
        'FILE_SIZE' => 20971520,
        'FILE_PATH' => 'filedoc/', //临时存储路径
        'ALLOW_FILE' => array('xlsx', 'xls', 'doc', 'pdf', 'jpeg','jpg','png','docx'),
    ),
);


