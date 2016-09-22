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
    'FTP_MEETING' => array(//会议相关
        'FILE_SIZE' => 3145728,
        'FILE_PATH' => 'src/',
        'ALLOW_FILE' => array('xlsx', 'xls','png'),
        'PATH' => 'meeting/',
        'ROOT_PATH'=>'duban/', //web server 指定项目路径 注意'/'
    ),
);


