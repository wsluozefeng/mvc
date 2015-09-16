<?php
/**
 * Ajia    公用函数
 * Author: youxi
 * Date:   2015/8/18 10:09
 *
 */

/**
 * 实例化，也可执行函数
 * @param string /object $class     类名/对象
 * @param string $function 方法名
 * @param array $args 参数
 * @throws Exception
 */
function get_instance_of( $class, $function = '', $args = array() )
{
    static $_instance = array();

    $className = is_object( $class ) ? get_class( $class ) : $class;

    $classObj = new ReflectionClass( $class );

    if ( $classObj->isAbstract() || $classObj->isInterface() ) {
        throw new Exception( "{$className} is can not be objected" );
    }

    if ( !empty( $function ) && !$classObj->hasMethod( $function ) ) {
        throw new Exception( "{$className} has no {$function} function" );
    }

    $flag = empty( $args ) ? $className . $function : $className . $function . ( md5( serialize( $args ) ) );

    if ( isset( $_instance[$flag] ) ) {
        return $_instance[$flag];
    }

    if ( !empty( $function ) && $classObj->hasMethod( $function ) ) {
        $methodObj = new ReflectionMethod( $class, $function );

        if ( $methodObj->isPublic() ) {

            if ( $methodObj->isStatic() ) {
                $rel              = $methodObj->invokeArgs( null, $args ); //todo 静态方法调用方式 null
                $_instance[$flag] = $rel;

            } else {
                if ( !is_object( $class ) ) {
                    $class = new $class;
                }
                $rel              = $methodObj->invokeArgs( $class, $args );  //todo 非静态方法需要用类的 实例化
                $_instance[$flag] = $rel;
            }

        } else {
            throw new Exception( "{$function} function is not public" );
        }

    } else {
        $_instance[$flag] = is_object( $class ) ? $class : ( new $class( $args ) );
    }

    return $_instance[$flag];
}