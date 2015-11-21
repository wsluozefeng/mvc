<?php

/**
 *
 * Author: youxi
 * Date:   2015/10/8 9:44
 *
 */
class CurlHandle
{

    protected $curlHandle;

    protected $timeOut = 30;

    protected $httpHeader = array(); //http头部设置数组

    protected $error = '';

    public function setHttpHeader( $httpHeader )
    {
        $this->httpHeader = $httpHeader;
    }

    public function call( $url, $param = array(), $method = 'get' )
    {

        if ( empty( $url ) ) {
            $this->error = 'url不能为空';

            //todo 写入日志
            return false;
        }

        //初始化
        $this->curlHandle = curl_init();

        if ( $method == 'get' ) {
            $realUrl = $this->get( $url, $param );
        } else {
            $realUrl = $this->post( $url, $param );
        }

        //设置抓取的url
        curl_setopt( $this->curlHandle, CURLOPT_URL, $realUrl );

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt( $this->curlHandle, CURLOPT_RETURNTRANSFER, 1 );

        //设置host（用于局域网访问）
        if ( !empty( $this->httpHeader ) && is_array( $this->httpHeader ) ) {
            curl_setopt( $this->curlHandle, CURLOPT_HTTPHEADER, $this->httpHeader );
        }

        //设置超时时间
        curl_setopt( $this->curlHandle, CURLOPT_TIMEOUT, $this->timeOut );

        //执行命令
        $rel = curl_exec( $this->curlHandle );  //执行成功的结果是 array( 'code'=>XXX, 'msg'=>'XXX', 'data'=>array(XXX) )形式且json格式化后的数据

        //错误处理
        if ( false === $rel ) {
            $err         = curl_error( $this->curlHandle );
            $this->error = $err;

            //todo 写入日志
            return false;
        }

        //关闭URL请求
        curl_close( $this->curlHandle );

        return $this->getResult( $rel );

    }

    public function getError()
    {
        return $this->error;
    }

    protected function getResult( $data )
    {
        return json_decode( $data, true );
    }

    protected function post( $url, $param )
    {

        curl_setopt( $this->curlHandle, CURLOPT_POST, 1 );
        curl_setopt( $this->curlHandle, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        return $url;

        /*$url = 'http://192.168.159.156/index.php/Pay/orderQuery';
        //$url = 'http://www.juanpi.com';

        //初始化
        $curl = curl_init();

        //设置抓取的url
        curl_setopt( $curl, CURLOPT_URL, $url );

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt( $curl,CURLOPT_RETURNTRANSFER, 1 );

        $host = array("Host:www.tp.com");
        curl_setopt($curl,CURLOPT_HTTPHEADER,$host);

        $post = array( 'out_trade_no' => '176549928349');
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl,CURLOPT_POSTFIELDS, http_build_query( $post ) );
        //curl_setopt( $curl,CURLOPT_POSTFIELDS, $post );  //数组形式的情况下，Content-Type头将会被设置成 multipart/form-data

        curl_setopt( $curl, CURLOPT_TIMEOUT, $this->timeOut );

        //执行命令
        $data = curl_exec( $curl );

        //关闭URL请求
        curl_close( $curl );

        //print_r($data);
        print_r( json_decode($data, true));*/

    }

    protected function get( $url, $param )
    {

        if ( !empty( $param ) ) {
            $url .= "?" . http_build_query( $param );
        }

        return $url;

        /**
         * 设置host的步骤有2步：
         * 第1步：CURLOPT_URL的值为ip形式的网址，例如：http://192.168.159.156/index.php
         * 第2步：CURLOPT_HTTPHEADER 的值为对应ip绑定的host 的数组，例如：array("Host:www.tp.com");
         */

        /*$url = 'http://192.168.159.156/index.php/Pay/orderQuery?out_trade_no=176549928349';

        //初始化
        $curl = curl_init();

        //设置抓取的url
        curl_setopt( $curl, CURLOPT_URL, $url );

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt( $curl,CURLOPT_RETURNTRANSFER, 1 );

        $host = array("Host:www.tp.com");
        curl_setopt($curl,CURLOPT_HTTPHEADER,$host);

        curl_setopt( $curl, CURLOPT_TIMEOUT, $this->timeOut );

        //执行命令
        $data = curl_exec( $curl );

        //关闭URL请求
        curl_close( $curl );

        print_r( json_decode($data, true));*/


    }

    /**
     * 批处理， 高并发使用
     * @param $urls
     */
    function rollingCurl( $urls )
    {

        $queue = curl_multi_init();  //todo 新增批处理句柄

        foreach ( $urls as $url ) {
            $ch = curl_init();

            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_TIMEOUT, 1 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

            $host = array( "Host:www.tp.com" );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $host );

            curl_multi_add_handle( $queue, $ch ); //todo 将curl句柄添加到 批处理句柄中
        }

        $active = array();

        //初始化，并且执行
        do {
            $mrc = curl_multi_exec( $queue, $active );   //初始化的$active为添加到批处理句柄的curl句柄总数
        } while ( $mrc == CURLM_CALL_MULTI_PERFORM );

        //对执行结果进行处理
        while ( $active && $mrc == CURLM_OK ) {

            //等待所有cURL批处理中的活动连接,失败返回 -1
            if ( curl_multi_select( $queue ) != -1 ) {

                do {
                    $mrc = curl_multi_exec( $queue, $active );  //再次执行批处理中的每个资源句柄，让后"依次"处理

                    //获取每个资源的数据
                    $info   = curl_multi_info_read( $queue );
                    $head   = curl_getinfo( $info['handle'] );  //$info['handle']为资源对象（等价于通过curl_init获取的资源句柄），需要通过curl_getinfo来获取内容
                    $result = curl_multi_getcontent( $info['handle'] );
                    $error  = curl_error( $info['handle'] );
                    $data   = compact( 'head', 'result', 'error' );
                    Log::write( "======" . json_encode($data) . "======" );

                    curl_multi_remove_handle( $queue, $info['handle'] );  //移处批处理句柄
                    curl_close( $info['handle'] );                        //关闭该资源链接

                } while ( $mrc == CURLM_CALL_MULTI_PERFORM );
            }
        }

        //关闭批处理句柄
        curl_multi_close( $queue );

    }


    /*function rollingCurl( $urls, $delay = 1 )
    {
        $queue = curl_multi_init();  //todo 新增批处理句柄
        $map   = array();

        foreach ( $urls as $url ) {
            $ch = curl_init();

            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_TIMEOUT, 1 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );


            $host = array( "Host:www.tp.com" );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $host );

            curl_multi_add_handle( $queue, $ch ); //todo 将curl句柄添加到 批处理句柄中

            $map[(string)$ch] = $url;
        }

        $responses = array();

        do {
            while ( ( $code = curl_multi_exec( $queue, $active ) ) == CURLM_CALL_MULTI_PERFORM ) ;

            if ( $code != CURLM_OK ) {
                break;
            }

            // a request was just completed -- find out which one
            while ( $done = curl_multi_info_read( $queue ) ) {

                // get the info and content returned on the request
                $info  = curl_getinfo( $done['handle'] );
                $error = curl_error( $done['handle'] );

                $results                                  = curl_multi_getcontent( $done['handle'] );
                $responses[$map[(string)$done['handle']]] = compact( 'info', 'error', 'results' );
                //Log::write( "======".json_encode($responses[$map[(string) $done['handle']]])."======" );
                Log::write( "======" . $active . "======" );

                // remove the curl handle that just completed
                curl_multi_remove_handle( $queue, $done['handle'] );
                curl_close( $done['handle'] );
            }

            // Block for data in / output; error handling is done by curl_multi_exec
            if ( $active > 0 ) {
                curl_multi_select( $queue, 0.5 );
            }

        } while ( $active );

        curl_multi_close( $queue );

        return $responses;
    }*/

}