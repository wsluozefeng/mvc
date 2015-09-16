<?php
/**
 * Ajia 入口文件
 * Author: youxi
 * Date:   2015/8/18 9:08
 *  
 */

define( "SEPARATOR", "/" );
define( 'AJIA_PATH', dirname( __FILE__ ).SEPARATOR );                           // Ajia目录   php5.3之后，新增了__dir__，这里为了兼容
define( 'BASE_PATH', dirname( $_SERVER['SCRIPT_FILENAME'] ).SEPARATOR );        // 根目录

require AJIA_PATH . 'Common/runtime.php';