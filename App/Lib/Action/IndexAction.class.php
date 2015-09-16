<?php
/**
 *
 * Author: youxi
 * Date:   2015/9/9 15:33
 *  
 */

class IndexAction{

    public function index(){
        //$ajia = new IndexModel();
        //$ajia->getOrderList();


        $cache = Cache::getInstance('redis', 'act_redis');
        $cache->set('where', 99, array( 'nx', 'ex'=>50 ));
        $rel = $cache->get('where');
        echo $cache->ttl('where');
        echo "<hr>";
        echo($rel);

        exit;

    }




}