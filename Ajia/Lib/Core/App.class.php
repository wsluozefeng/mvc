<?php
/**
 * Ajia 应用程序控制类
 * Author: youxi
 * Date:   2015/8/24 15:55
 *  
 */

class App{

    static public function init(){

        date_default_timezone_set('PRC');

        //最主要是路由
        Dispatcher::init();

    }

    static public function exec(){

        if ( defined( '__MODEL__' ) ) {
            $action = A( __MODEL__ );
        }

        if ( is_object( $action ) && defined( '__ACTION__' ) ) {

            $classObj = new ReflectionClass( $action );

            if ( $classObj->hasMethod( __ACTION__ ) ) {
                $methodObj = new ReflectionMethod( $action, __ACTION__ );
                if ( $methodObj->isPublic() ) {
                    $methodObj->invoke( $action );
                }

            } else {
                $msg = "function " . __ACTION__ . "不存在";
                //exit('test');
                throw new Exception( $msg );
            }


        }

    }

    static public function run(){

        self::init();
        self::exec();

    }

}