<?php
/**
 * Ajia 路由类
 * Author: youxi
 * Date:   2015/8/24 16:07
 *  
 */

class Dispatcher{

    static public function init(){

        if( C("URL_MODEL") === 1 ){

            if( isset( $_SERVER['PATH_INFO'] ) && !empty($_SERVER['PATH_INFO']) ){
                $pathInfo = $_SERVER['PATH_INFO'];

                if( $pathInfo !== "/" ){

                    $paths = explode( '/', trim($_SERVER['PATH_INFO'], '/') );

                    $_GET[C('URL_MODEL_PARAM_NAME')] = ( $mTem = array_shift( $paths )) ? $mTem : C('DEFAULT_MODEL');
                    $_GET[C('URL_ACTION_PARAM_NAME')] = ( $aTem = array_shift( $paths )) ? $aTem : C('DEFAULT_ACTION');

                }else{
                    $_GET[C('URL_MODEL_PARAM_NAME')] = C('DEFAULT_MODEL');
                    $_GET[C('URL_ACTION_PARAM_NAME')] = C('DEFAULT_ACTION');
                }

                //参数构建
                if( !empty($paths) ){
                    $limit = round( count($paths)/2 );
                    $len   = 2;

                    for( $i = 0; $i < $limit; $i++ ){
                        $offset = $i * $len;
                        $tem    = array_slice( $paths, $offset, $len );

                        $paramName        = array_shift( $tem );
                        $paramValue       = array_shift( $tem );
                        $_GET[$paramName] = $paramValue;
                    }
                }
            }
        }

        define("__MODEL__", self::getModel());
        define("__ACTION__", self::getAction());

        return;

    }

    static private function getModel(){

        $module = isset( $_GET[C('URL_MODEL_PARAM_NAME')] ) ? $_GET[C('URL_MODEL_PARAM_NAME')] :  C('DEFAULT_MODEL');

        if( C('URL_CASE_INSENSITIVE') ){

            $mTmp = explode("_", $module);
            if( count($mTmp) > 1 ){
                foreach( $mTmp as $row ){
                    $mData[] = ucfirst($row);
                }
                $module = implode("",$mData);
            }

        }

        return $module;

    }

    static private function getAction(){
        $action = isset( $_GET[C('URL_ACTION_PARAM_NAME')] ) ? $_GET[C('URL_ACTION_PARAM_NAME')] :  C('DEFAULT_ACTION');

        if( C('URL_CASE_INSENSITIVE') ){
            $action = strtolower($action);
        }

        return $action;
    }

}