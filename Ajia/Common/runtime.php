<?php
/**
 * Ajia 运行文件
 * Author: youxi
 * Date:   2015/8/18 9:26
 *  
 */

function load_file(){

    //Ajia常量
    define( 'AJIA_LIB_PATH', AJIA_PATH . "Lib/" );         //Ajia的核心类目录
    define( 'AJIA_COMMON_PATH', AJIA_PATH . "Common/" );   //Ajia的Common目录
    define( 'AJIA_EXTEND_PATH', AJIA_PATH . "Extend/" );   //Ajia的Extend目录

    //项目常量
    define( 'LIB_PATH',     APP_PATH . "Lib/" );
    define( 'CONF_PATH',    APP_PATH . "Conf/" );
    define( 'RUNTIME_PATH', BASE_PATH . "Runtime/" );
    define( 'LOG_PATH',     RUNTIME_PATH . "Log/" );
    define( 'CACHE_PATH',   RUNTIME_PATH . "cache/" );

    //加载基础函数库
    require_once AJIA_COMMON_PATH . 'common.php';

    //加载Ajia入口文件
    $baseFile = array(
        AJIA_LIB_PATH . 'Core/Ajia.class.php',
        AJIA_COMMON_PATH . 'function.php',
    );
    foreach( $baseFile as $file ){
        if( is_file( $file ) ){
            require_cache( $file );
        }
    }

    //保存别名,以便在自动加载中加载
    import_alias( require_once( AJIA_COMMON_PATH . "alias.php" ) );

    //建立应用项目目录
    if( defined( 'APP_PATH' ) ){

        if( !is_dir( APP_PATH . 'Lib' ) ){
            create_app_dir();
        }
    }else{
        //header('Content-Type:text/html, charset=utf-8');
        exit( '请先定义项目目录路径,例如："./app/" ');
    }

    //建立缓存目录
    if( !is_dir( RUNTIME_PATH ) ){
        create_runtime_dir();
    }

}

function create_app_dir(){

    if( false === is_dir( APP_PATH ) ){
        mkdir( APP_PATH, 0755, true );
        ob_end_clean();

        if( is_writeable( APP_PATH ) ){
            $dirArr = array(
                LIB_PATH,
                CONF_PATH,
                LIB_PATH."Action",
                LIB_PATH."Model",
            );

            foreach( $dirArr as $dir ){
                mkdir( $dir, 0755, true );
            }
        }else{
            //header('Content-Type:text/html, charset=utf-8');
            exit('项目目录不可写，目录无法自动生成！<BR>请使用项目生成器或者手动生成项目目录~');
        }
    }

}

function create_runtime_dir(){

    mkdir( RUNTIME_PATH, 0755, true );
    ob_end_clean();

    if( is_writeable( RUNTIME_PATH ) ){
        $dirArr = array(
            LOG_PATH,
            CACHE_PATH,
        );

        foreach( $dirArr as $dir ){
            mkdir( $dir, 0755, true );
        }

    }else{
        //header('Content-Type:text/html, charset=utf-8');
        exit( '缓存日志目录['.RUNTIME_PATH.']不可写，目录无法自动生成！<BR>请使用项目生成器或者手动生成该目录~' );
    }

}

load_file();
Ajia::start();

