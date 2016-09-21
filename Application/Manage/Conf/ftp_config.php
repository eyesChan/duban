<?php
return array(
    'FTP_OPTION' => array(
        'FTP_HOST' => '192.168.5.66',
        'FTP_PORT' => 21,
        'FTP_USER' => 'duban',
        'FTP_PWD' => '123456',
        'FTP_SYSTEM' => '1', //1 linux 2 windows 
        'FTP_ROOT_PATH' => '/home/path',
    ),
    'FTP_VISIT_PATH' => '', //ftp浏览器访问路径
    'FTP_MEETING' => array(//会议相关
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'src/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
        'PATH' => 'meeting/'
    ),
    
    'FIP_DOC' => array(//文档附件文件上传
        'FILE_SIZE' =>20971520 ,
        'FILE_PATH' => 'filedoc/', //临时存储路径
        'ALLOW_FILE'=>array('xlsx','xls','word','pdf','jpeg'),
    ),   
    
      'FTP_COVER' => array(//文档附件封面文件上传
        'FILE_SIZE' =>524288,
        'FILE_PATH' => 'filecover/', //临时存储路径
        'ALLOW_FILE'=>array('jpg','png'),
    ),   
);


