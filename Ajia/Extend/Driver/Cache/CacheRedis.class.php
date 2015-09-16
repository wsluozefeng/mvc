<?php
/**
 * redis缓存驱动类 支持多实例
 * Author: youxi
 * Date:   2015/8/18 19:53
 *  
 */

class CacheRedis extends Cache{

    protected static $instance = array();

    public function __construct( $config )
    {
        if ( !extension_loaded( 'redis' ) ) {
            throw new Exception( 'php-redis扩展不存在' );
        }

        $config      = empty( $config ) ? 'default_redis' : $config;
        $redisConfig = C( $config );

        $host = isset( $redisConfig['host'] ) ? $redisConfig['host'] : '127.0.0.1';
        $port = isset( $redisConfig['port'] ) ? $redisConfig['port'] : '6379';
        $pwd  = isset( $redisConfig['auth'] ) ? $redisConfig['auth'] : '';

        try {

            $this->_handler = new Redis();
            $this->_handler->connect( $host, $port );
            $this->_handler->ping();                  //todo redis的connect函数即使连接失败也不会报错，需要ping来验证是否连接成功

        } catch ( Exception $e ) {
            throw new Exception( "redis server connect failed " . json_encode( $redisConfig ) );
        }

        //todo 设置了密码访问
        if ( empty( $pwd ) ) {
            $this->_handler->auth( $pwd );
        }

        return $this->_handler;

    }

}