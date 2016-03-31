<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'mcwcms',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'mc_',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',      // 数据库编码
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    //'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查
    //'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存

    'DEFAULT_THEME' => 'default',
    'DEFAULT_MODULE' =>  'Admin',
    'DEFAULT_CONTROLLER' => 'Public',

    /* 提示信息 */
    'ALERT_MSG' => array(
        'NO_AUTH' => '您没有操作权限！',
        'SELECT_DATA' => '请选择要操作的数据！',
        'USERS_GROUPF' => '更新用户成功，用户组没有更新!',
        'EXECUTE_SUCCESS' => '操作成功！',
        'EXECUTE_FAILED' => '操作失败，请重试！',
        'SAVE_SUCCESS' => '保存成功！',
        'SAVE_FAILED' => '保存失败或数据没有被修改！',
        'DELETE_SUCCESS' => '删除成功！',
        'DELETE_FAILED' => '删除失败！',
        'DELETE_SUBMENU'=>'你要删除的菜单包含子菜单，请先删除子菜单!',
        'RECORD_EXIST' => '已存在该记录！',
        'RECORD_NOT_EXIST' => '不存在该记录！',
        'REQUIRED' => ' 必填字段不能为空！',
    ),
);