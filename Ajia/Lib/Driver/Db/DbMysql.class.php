<?php

/**
 *
 * Author: youxi
 * Date:   2015/9/10 15:43
 *
 */
class DbMysql extends Db
{

    protected $dbConfig = array();

    public function __construct( $config )
    {
        $this->dbConfig = $config;
    }

    public function connect( $config = '', $linkNum = 0 )
    {

        if ( !isset( $this->linkId[$linkNum] ) ) {
            $host     = isset( $config['DB_HOST'] ) ? $config['DB_HOST'] : $this->dbConfig['host'];
            $port     = isset( $config['DB_PORT'] ) ? $config['DB_PORT'] : $this->dbConfig['port'];
            $user     = isset( $config['DB_USER'] ) ? $config['DB_USER'] : $this->dbConfig['username'];
            $pwd      = isset( $config['DB_PWD'] ) ? $config['DB_PWD'] : $this->dbConfig['password'];
            $db       = isset( $config['DB_NAME'] ) ? $config['DB_NAME'] : $this->dbConfig['database'];
            $pconnect = isset( $config['DB_PCONNECT'] ) ? $config['DB_PCONNECT'] : $this->dbConfig['pconnect'];

            //todo 端口处理
            $hostPort = $host . ( empty( $port ) ? '' : ( ':' . $port ) );

            if ( true === $pconnect ) {
                $this->linkId[$linkNum] = mysql_pconnect( $hostPort, $user, $pwd );
            } else {
                $this->linkId[$linkNum] = mysql_connect( $hostPort, $user, $pwd, true );
            }

            if ( !$this->linkId[$linkNum] || ( !empty( $db ) && !mysql_select_db( $db, $this->linkId[$linkNum] ) ) ) {
                $err = mysql_error();
                throw new Exception( $err );
            }

        }

        return $this->linkId[$linkNum];

    }

    public function getFields( $tableName )
    {

        if ( empty( $tableName ) ) {
            throw new Exception( "表名为空" );
        }

        $sql      = "SHOW COLUMNS FROM {$tableName}";
        $fieldTem = $this->query( $sql );

        return $fieldTem;

    }

    public function query( $sql )
    {
        //todo 创建db连接
        $this->iniConnect();

        if ( !$this->currLinkId || empty( $sql ) ) {
            return false;
        }

        //todo 当前查询语句
        $this->currQueryStr = $sql;

        //todo 是否上次查询结果
        if ( $this->currQueryId ) {
            $this->free();
        }

        $this->currQueryId = mysql_query( $sql, $this->currLinkId );

        //todo 错误处理
        if ( false === $this->currQueryId ) {
            $this->error();
            return false;

        } else {
            $this->numRows = mysql_num_rows( $this->currQueryId );
            $rel           = $this->getAll();

            return $rel;
        }
    }

    /**
     * 获取查询结果
     * @return array
     */
    public function getAll()
    {
        $rel = array();

        if ( $this->numRows > 0 ) {
            while ( $row = mysql_fetch_assoc( $this->currQueryId ) ) {
                $rel[] = $row;
            }

            //指向开头的结果集
            mysql_data_seek( $this->currQueryId, 0 );
        }

        return $rel;
    }

    /**
     * 释放查询结果
     */
    public function free()
    {
        mysql_free_result( $this->currQueryId );
        $this->currQueryId = null;
    }

    /**
     * 错误处理
     * @return string
     * @throws Exception
     */
    public function error()
    {
        $err = "[" . mysql_errno() . "]：" . mysql_error() . ", \n [SQL语句]：" . $this->currQueryStr;
        throw new Exception( $err );
        return $err;
    }

    public function demo()
    {
        //echo __FILE__;
    }
}