<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        $curl = new CurlHandle();
        $curl->setHttpHeader( array("Host:www.tp.com") );
        //$data = $curl->call( 'http://192.168.159.156/index.php/Pay/refundQuery', array( 'out_trade_no' =>'530438499181' ) );

        //$data = $curl->call( 'http://192.168.159.156/index.php/Pay/shellDealWxRefundOrder' );  //退款订单处理

        //curl批处理实现
        $arr = array( 'http://192.168.159.156/index.php/Pay/curlMulti?out_trade_no=122', 'http://192.168.159.156/index.php/Pay/curlMulti?out_trade_no=555', 'http://192.168.159.156/index.php/Pay/curlMulti?out_trade_no=2000' );
        $curl->rollingCurl($arr);

        /*$startTime = time();
        for( $i=1; $i < 3; $i++ ){
            $arr[$i] = 'http://192.168.159.156/index.php/Pay/curlMulti?out_trade_no='.$i;
            $data = $curl->call( $arr[$i] );
        }
        //$curl->rollingCurl($arr);

        $endTime = time();

        $diffTime = $endTime - $startTime;
        echo $diffTime;*/
        exit;
    }
}