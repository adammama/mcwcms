<?php
return array(

    'ADMIN_AUTH_KEY'=>  'admin',
    'USER_AUTH_KEY'=>  'uid',
    'URL_HTML_SUFFIX'=>'',
    //'URL_CASE_INSENSITIVE' =>true, //表示URL访问不区分大小写
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'USER_AUTH_GATEWAY' => '/index.php/Admin/Public/index', // 默认认证网关
    //权限验证设置
    'AUTH_CONFIG'=>array(
        'AUTH_ON' => true,
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => C('DB_PREFIX') . 'auth_group',
        'AUTH_GROUP_ACCESS' => C('DB_PREFIX') . 'auth_group_access',
        'AUTH_RULE' => C('DB_PREFIX') . 'auth_rule',
        'AUTH_USER' => C('DB_PREFIX') . 'user'
    ),
);