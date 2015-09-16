<?php
/**
 *
 * Author: youxi
 * Date:   2015/8/18 14:24
 *  
 */

class Log{

    static private $_log = array();

    static public function record( $msg ){

        if( empty( $msg ) ){
            return;
        }

        self::$_log[] = $msg."\r\n";

        return true;
    }

    static public function write( $msg ){
        if( empty( $msg ) ){
            return;
        }

        $logFile = LOG_PATH . date( 'Y_m_d' ) . ".log";
        error_log( $msg, 3, $logFile );
    }

    static public function save(){

        if( empty( self::$_log ) ){
            return;
        }

        $logFile = LOG_PATH . date( 'Y_m_d' ) . ".log";

        //检测日志文件大小，超过限制备份该文件
        if( is_file($logFile) && filesize( $logFile ) > 200000 ){
            rename( $logFile, dirname($logFile)."/".date( 'Y_m_d_H_i_s' ).".log" );
        }

        $msg = implode(" ", self::$_log);
        error_log( $msg, 3, $logFile );

    }

}