<?php

function doLog( $err ){
    $logDir = dirname(__FILE__).'/err.log';  //文件在linux下，一般用绝对路径
    $ip     = $_SERVER['REMOTE_ADDR'];
    $time   = @date("Y-m-d H:i:s");
    $msg    = "\r\n[$time $ip]: ".$err['message']." in ".$err['file']." ".$err['line']." line ";

    //检测日志文件大小，超过配置大小则备份日志文件重新生成
    if( is_file($logDir) && ( filesize($logDir) > 5000000 ) ){
        rename( $logDir, dirname($logDir)."/".time()."-".basename($logDir) );
    }

    error_log( $msg, 3, $logDir );
}

class ajia{

    static function showError(){
        $err = error_get_last();    //获取最后一个错误
        if( $err ){
            if ( in_array($err['type'], array( 
                                                E_ERROR,
                                                E_PARSE,          //php 编译时解析错误
                                                E_CORE_ERROR,     //php 启动时初始化致命性错误
                                                E_COMPILE_ERROR,  //php 编译时致命性错误
                                                E_USER_ERROR )
            ) ) {
                ob_end_clean();    //清除缓冲区中的内容
                doLog( $err );
            }
        }else{
            echo "egg static";
        }

    }

}

register_shutdown_function( array('ajia', 'showError') );
ajia::dd();
exit;
