<?php
/**
 * 缓存中间层
 * Author: youxi
 * Date:   2015/8/18 19:32
 *  
 */

class Cache{

    protected static $_instance;

    protected $_handler; //todo 点1 驱动类的实例化，在对应驱动类中被赋值

    /**
     * @param string $type     缓存类型
     * @param string $params   配置项
     * @return mixed
     * @throws Exception
     */
    public static function getInstance( $type = '', $params = '' ){

        $cacheType = ( '' === $type ) ? C('CACHE_TYPE') : $type;
        $className = "Cache".ucfirst($cacheType);

        if( !isset( self::$_instance[$className] ) ){

            try{
                self::$_instance[$className] = new $className( $params );
            }catch( Exception $e ){
                $err = $e->getMessage();
                throw new Exception( $err );
            }

        }

        return self::$_instance[$className];
    }

    /**
     * 调用缓存驱动类本身的方法
     * @param $method
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public function __call( $method, $args ){
        if( method_exists( $this->_handler, $method ) ){
            return call_user_func_array( array( $this->_handler, $method ), $args );  //todo 点2 用缓存驱动类本身的方法
        }else{
            throw new Exception( '不存在的函数' );
        }
    }

}