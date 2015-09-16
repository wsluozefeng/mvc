<?php
/**
 * 数据库中间层
 * Author: youxi
 * Date:   2015/9/8 18:16
 *  
 */

class Db{

    protected $db = null;

    //db连接id
    protected $linkId = null;

    //当前的db连接id
    protected $currLinkId = null;

    //当前的查询sql语句
    protected $currQueryStr = '';

    //当前的查询Id
    protected $currQueryId  = null;

    //结果记录数
    protected $numRows      = 0;

    public static function getInstance(){
        $args = func_get_args();
        return get_instance_of( __CLASS__, 'factory', $args );   //todo 返回具体的数据库驱动类 实例
    }

    public function factory( $config = array() ){
        $_config = !empty( $config ) ? $config : $this->_getDbConfig();
        $dbms    = isset( $_config['dbms'] ) && !empty( $_config['dbms'] ) ? ucfirst( $_config['dbms'] ) : ucfirst( C('DEFAULT_DB_TYPE') );
        $dbClass = 'Db'.$dbms;

        if( class_exists( $dbClass ) ){
            $this->db = new $dbClass( $_config );
        }else{
            throw new Exception( "{$dbms}的DB驱动类不存在" );
        }

        return $this->db;
    }

    protected function iniConnect(){
        $this->currLinkId = $this->connect();  //todo 调用子类的函数
    }

    protected function _getDbConfig(){

        $rel['dbms']     = C( 'DB_TYPE' );
        $rel['host']     = C( 'DB_HOST' );
        $rel['port']     = C( 'DB_PORT' );
        $rel['username'] = C( 'DB_USER' );
        $rel['password'] = C( 'DB_PWD' );
        $rel['database'] = C( 'DB_NAME' );
        $rel['pconnect'] = C( 'DB_PCONNECT' );

        return $rel;
    }

}