<?php
/**
 *
 * Author: youxi
 * Date:   2015/9/9 16:09
 *  
 */

class IndexModel extends Model{

    public function getOrderList(){
        //echo __FILE__;
        /*$a = C();
        var_dump($a);*/

        //get_instance_of( $this, 'kk' );
        /*$a = get_instance_of( __CLASS__, 'kk2', array(99,56) );
        $b = get_instance_of( __CLASS__, 'kk2', array(99,56) );
        echo $a;
        echo $b;*/

        $this->select();
    }

    public static function kk($a, $b){
        echo ' is static function kk ';
        return $a.'------------'.$b;
    }

    public function kk2( $a, $b ){
        echo ' is function kk ';
        return $a.'*/*/*/*/*/*/'.$b;
    }

}