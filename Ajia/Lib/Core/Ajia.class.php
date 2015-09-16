<?php
/**
 * Ajia Portal类 核心文件
 * Author: youxi
 * Date:   2015/8/18 9:27
 *  
 */

class Ajia{

    static public function start(){

        //php进程注销前回调注册函数：保存日志，显示错误
        register_shutdown_function( array( 'Ajia', 'shutdown' ) );

        //注册异常处理
        set_exception_handler( array( 'Ajia', 'exceptionHandler' ) );

        //注册错误处理
        set_error_handler( array( 'Ajia', 'errorHandler' ) );

        //注册自动加载类文件
        spl_autoload_register( array( 'Ajia', 'autoload' ) );

        Ajia::buildApp();  //项目编译 加载各种需要的文件

        App::run();

    }

    static public function buildApp(){

        //保存惯例配置
        C( include AJIA_PATH . 'Conf/Config.php' );

        //加载项目的配置
        if( is_file( CONF_PATH.'config.php' ) ){
            C( include( CONF_PATH.'config.php' ) );
        }

    }

    /**
     * 自定义异常处理
     * @param obj $e
     */
    static public function exceptionHandler( $e ){
        $msg  = $e->getMessage();
        $line = $e->getLine();
        $file = $e->getFile();
        //$trace  =   $e->getTrace();

        $theMsg = "{$msg} {$file} in {$line} line";
        Log::record( $theMsg );

    }

    static public function shutdown(){

        Log::save();

        if( $e = error_get_last()){
            switch( $e['type'] ){
                case E_ERROR :
                case E_PARSE :
                case E_CORE_ERROR :
                case E_COMPILE_ERROR :
                case E_USER_ERROR :{
                    ob_end_clean();
                    print_r($e);
                    Log::write("ERROR: ".$e['message']." in ".$e['file']." on line ".$e['line']."\r");
                    break;
                }
            }
        }

    }

    /**
     * 自定义错误处理    ps: Fatal error类型的不经过错误处理，而最后进入进程回调函数
     * @access public
     * @param int    $errno   错误类型
     * @param string $errstr  错误信息
     * @param string $errfile 错误文件
     * @param int    $errline 错误行数
     * @return void
     */
    static public function errorHandler( $errno, $errstr, $errfile, $errline ){

        $errorStr = "$errstr ".$errfile." 第 $errline 行.\r";
        Log::record("[$errno] ".$errorStr);

        /*switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                //ob_end_clean();
                $errorStr = "$errstr ".$errfile." 第 $errline 行.";
                Log::write("[$errno] ".$errorStr);
                echo $errorStr;
                //function_exists('halt')?halt($errorStr):exit('ERROR:'.$errorStr);
                break;
            case E_STRICT:
            case E_USER_WARNING:
            case E_USER_NOTICE:
            default:

                break;
        }*/
    }

    /**
     * 自动加载
     * @param $class 类名
     */
    static public function autoload( $class ){

        //别名的自动加载
        if( true === import_alias( $class ) ){
            return;
        }

        $fileName = $class.".class.php";

        //缓存类自动加载
        if( substr( $class, 0, 5 ) === 'Cache' ){
            $cacheFile = array(
                AJIA_LIB_PATH . 'Driver/Cache/'.$fileName,
                AJIA_EXTEND_PATH . 'Driver/Cache/'.$fileName,
            );

            foreach( $cacheFile as $cache ){
                require_cache( $cache );
            }

            return;

            // 加载控制器
        }elseif(substr($class,-6)=='Action'){

            $actionFile = array(
                LIB_PATH.'Action/'.$fileName
            );

            foreach( $actionFile as $cache ){
                require_cache( $cache );
            }

            return;

        }elseif(substr($class,-5)=='Model'){
            $actionFile = array(
                LIB_PATH.'Model/'.$fileName
            );

            foreach( $actionFile as $cache ){
                require_cache( $cache );
            }

            return;

        }elseif(substr($class,0, 2)=='Db'){
            $actionFile = array(
                AJIA_LIB_PATH.'Driver/Db/'.$fileName
            );

            foreach( $actionFile as $cache ){
                require_cache( $cache );
            }

            return;
        }

    }

}

