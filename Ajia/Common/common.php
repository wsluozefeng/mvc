<?php
/**
 * Ajia    基础函数
 * Author: youxi
 * Date:   2015/8/18 10:08
 *  
 */

/**
 * 设置/获取 配置
 * @param string/array $name
 * @param string       $value
 * @return array|null|void
 */
function C( $name = '', $value = '' ){

    static $_config = array();

    if( '' === $name ){
        return $_config;
    }

    //$name 数组：设置值
    if( is_array( $name ) ){
        $_config = array_merge( $_config, array_change_key_case( $name ) );
        return;
    }

    //$name 字符串且不为空：$value为空时候是获取值，不为空为设置值
    if( is_string( $name ) && !empty( $name ) ){

        $theName = strtolower($name);
        //取值
        if( ''=== $value ){
            return ( isset( $_config[$theName] ) ? $_config[$theName] : null );
        }else{
            //赋值
            $_config[$theName] = $value;
            return;
        }

    }
}

function A( $actionName ){

    if( empty($actionName) ){
        return false;
    }

    static $action = array();

    if( !isset( $action[$actionName] ) ){
        $baseName = $actionName.C('DEFAULT_C_LAYER');
        /*$a = class_exists( $baseName, true );
        var_dump($a);exit;*/
        $action[$actionName] = new $baseName;
    }

    return $action[$actionName];
}

/**
 * 自定义的require_once
 * @param string $file 带路径的文件
 */
function require_cache( $file ){

    static $_requireFile = array();

    if( !isset( $_requireFile[ $file ] ) ){
        if( is_file($file) ){
            require $file;
            $_requireFile[ $file ] = true;
        }
    }

    return;
}

/**
 * 保存和包含别名文件
 * @param $alias 别名
 * @return bool
 */
function import_alias( $alias ){

    static $_alias = array();

    if( empty( $alias ) ){
        return;
    }

    //保存别名
    if( is_array( $alias ) ){
        $_alias = array_merge( $_alias, $alias );
        return;

    }elseif( is_string( $alias ) ){

        //包含别名对应的文件
        if( isset( $_alias[$alias] ) ){
            require_cache( $_alias[$alias] );
            return true;

        }else{
            //保存别名
            $_alias[$alias] = $alias;
            return;
        }

    }
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type=0) {
    if ($type) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

