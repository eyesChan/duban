<?php
return array(
    'FTP_OPTION' => array(
        'FTP_HOST' => '192.168.5.66',
        'FTP_PORT' => 21,
        'FTP_USER' => 'duban',
        'FTP_PWD' => '123456',
        'FTP_SYSTEM' => '1', //1 linux 2 windows 
        'FTP_ROOT_PATH' => '/home/ftp/',
    ),
    'FTP_VISIT_PATH' => 'http://www.ftp.com/', //ftp浏览器访问路径
    'FTP_WRITE_PATH' => 'http://192.168.5.66:8888/',
    'FTP_MEETING' => array(//会议相关
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'src/',
        'ALLOW_FILE' => array('xlsx', 'xls', 'png'),
        'PATH' => 'meeting/',
        'ROOT_PATH' => 'duban/', //web server 指定项目路径 注意'/'
    ),
    'FIP_DOC' => array(//文档附件文件上传
        'FILE_SIZE' => 20971520,
        'FILE_PATH' => 'src/', //临时存储路径
        'ALLOW_FILE' => array('xlsx', 'xls', 'doc', 'pdf', 'jpeg'),
    ),
    'FTP_COVER' => array(//文档附件封面文件上传
        'FILE_SIZE' => 524288,
        'FILE_PATH' => 'src/', //临时存储路径
        'ALLOW_FILE' => array('jpg', 'png'),
    ),
    'FTP_MEETING_EXCEL' => array(//会议记录导入
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'src/',
        'ALLOW_FILE' => array('xlsx', 'xls'),
        'PATH' => 'meeting_import/',
        'ROOT_PATH' => 'duban/', //web server 指定项目路径 注意'/'
    ),
    'FIP_PUB_DOC' => array(//文档上传的类型
        'FILE_SIZE' => 20971520,
        'FILE_PATH' => 'src/', //临时存储路径
        'PATH' => 'filedoc/',
        'ALLOW_FILE' => array('xlsx', 'xls', 'doc', 'pdf', 'jpeg','jpg','png'),
    ),
);


