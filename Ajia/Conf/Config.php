<?php
/**
 * 惯例配置
 * Author: youxi
 * Date:   2015/8/18 9:07
 *  
 */

defined( 'AJIA_PATH' ) or exit();

return array(
    'LOG_TYPE'              => 3,      // 日志记录类型 0 系统 1 邮件 3 文件 4 SAPI     默认为文件方式
    'LOG_FILE_SIZE'         => 20000,  //日志单文件大小

    'CACHE_TYPE'            => 'redis',

    'URL_MODEL'             => 1,        //PATHINFO 模式
    'DEFAULT_MODEL'         => 'index',
    'DEFAULT_ACTION'        => 'index',
    'URL_MODEL_PARAM_NAME'  => 'm',
    'URL_ACTION_PARAM_NAME' => 'a',
    'URL_CASE_INSENSITIVE'  => true,   // 默认false 表示URL区分大小写 true则表示不区分大小写

    'DEFAULT_M_LAYER'       =>  'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER'       =>  'Action', // 默认的控制器层名称

    'DEFAULT_DB_TYPE'       => 'mysql' //默认的数据库驱动类


);