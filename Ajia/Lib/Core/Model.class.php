<?php

/**
 * Ajia 模型类
 * Author: youxi
 * Date:   2015/9/9 15:17
 *
 */
class Model
{

    //模块名
    protected $modelName = '';

    //表前缀
    protected $tablePreFix = '';

    //表名，不包括前缀
    protected $tableName = '';

    //表名 包括前缀
    protected $trueTableName = '';

    //字段
    protected $fields = "";

    //sql组装数据
    protected $option = array();

    protected $db = null;

    public function __construct( $modelName = '', $tablePreFix = '', $config = array() )
    {

        $this->modelName   = !empty( $modelName ) ? $modelName : $this->getModelName();
        $this->tablePreFix = !empty( $tablePreFix ) ? $tablePreFix : '';

        $this->dbInstance( 0, $config );
    }

    public function dbInstance( $linkNum = 0, $config = '' )
    {

        static $_db = array();

        if ( !isset( $_db[$linkNum] ) ) {
            $_db[$linkNum] = Db::getInstance( $config );  //todo 实例化db驱动类
        }

        $this->db = $_db[$linkNum];

        $this->checkTableInfo();

    }

    public function where( $where )
    {
        if( empty( $where ) ){
            return;
        }

        if ( is_string( $where ) ) {
            $whereString['_string'] = $where;
            $where                  = $whereString;
        }

        if ( isset( $this->option['where'] ) ) {
            $this->option['where'] = array_merge( $this->option['where'], $where );
        } else {
            $this->option['where'] = $where;
        }

        return $this->option['where'];
    }

    public function select()
    {
        $this->db->demo();
    }

    protected function getModelName()
    {
        $className = get_class( $this );  //todo 获取类名，由于__construct是由IndexModel来触发的，所以....., 而__CLASS__则表示当前的类名
        $rel       = str_replace( 'Model', '', $className );
        return $rel;
    }

    protected function getTableName()
    {

        if ( empty( $this->trueTableName ) ) {
            $tableName = parse_name( $this->modelName );
        }

        $this->trueTableName = $this->tablePreFix . $tableName;

        return $this->trueTableName;

    }

    protected function checkTableInfo()
    {

        //获取字段并且缓存字段
        $this->fields = $this->db->getFields( $this->getTableName() );

    }
}