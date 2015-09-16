<?php

/*define( "SEPARATOR", "/" );
define( "__ROOT__",  dirname(__FILE__) );
//$fileName = basename(__FILE__ , ".php");  //第二个参数是过滤作用
//$dir = dirname(__FILE__);

$appDir = __ROOT__.SEPARATOR."ajia";

if( true !== is_dir($appDir) ){
    $rel = mkdir( $appDir, 0455, true );       //默认创建的目录权限是小于等于系统umask权限
    //chmod( $appDir, 0777 );                  //chmod不会受umask的限制
}

$tem = is_writeable($appDir);
var_dump($tem);*/

define( 'APP_PATH', './App/' );
require './Ajia/Ajia.php';