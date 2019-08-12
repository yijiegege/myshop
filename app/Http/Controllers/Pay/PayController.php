<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\OrderModel;
class PayController extends Controller
{
    public $app_id; //支付宝APPid
    public $gate_way;   //支付宝网关
    public $notify_url; //异步回调地址
    public $return_url;     //同步回调地址
    public $rsaPrivateKeyFilePath; //应用私钥
    public $aliPubKey;      //支付宝公钥

    public function __construct()
    {
        $this->app_id=env('APP_ID');
        $this->gate_way='https://openapi.alipaydev.com/gateway.do';
        $this->notify_url=env('APPNOTIFY_URL');
        $this->return_url=env('APPRETURN_URL');
        $this->aliPubKey=storage_path('app/alipay/alipay_public.key');//支付宝公钥
        $this->rsaPrivateKeyFilePath=storage_path('app/alipay/private.key');//应用私钥
    }
    public function test(){
        echo $this->aliPubKey;echo '<br>';
        echo $this->rsaPrivateKeyFilePath;echo '<br>';

    }
    //去支付
    public function pay(Request $request){
        //接受订单ID
        $order_id=$request->input('order_id');
        //根据订单ID查询数据
        $orderInfo=OrderModel::where(['order_id'=>$order_id])->first();
//        var_dump($orderInfo);die;
        if(empty($orderInfo)){
            die('订单不存在，亲');
        }
        //业务参数
        $bizcont = [
            'subject'           => 'Lening-Order: ' .$order_id,
            'out_trade_no'      => $orderInfo->order_no,
            'total_amount'      => $orderInfo->order_amount,
            'product_code'      => 'QUICK_WAP_WAY',
        ];


        //公共参数
        $data = [
            'app_id'   => $this->app_id,
            'method'   => 'alipay.trade.wap.pay',
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'notify_url'   => $this->notify_url,        //异步通知地址
            'return_url'   => $this->return_url,        // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        //签名
        $sign = $this->rsaSign($data);
        $data['sign'] = $sign;
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $url = rtrim($param_str,'&');
        $url = $this->gate_way . $url;
        header("Location:".$url);       // 重定向到支付宝支付页面

    }
    public function rsaSign($params) {
        return $this->sign($this->getSignContent($params));
    }

    protected function sign($data) {

        $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
        $res = openssl_get_privatekey($priKey);
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);

        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }


    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }


    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {

        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }


    /**
     * 支付宝异步通知
     */
    public function notify()
    {
        $p = json_encode($_POST);
        $log_str = "\n>>>>>> " .date('Y-m-d H:i:s') . ' '.$p . " \n";
        file_put_contents('logs/alipay_notify',$log_str,FILE_APPEND);

        $json=json_decode($p,true);
        $where=[
            'status'=>2
        ];
        $orderInfo=OrderModel::where(['order_no'=>$json['out_trade_no']])->update($where);

        echo 'success';


        //TODO 验签 更新订单状态
    }

    /**
     * 支付宝同步通知
     */
    public function Alireturn(Request $request)
    {
        header("refresh:3;url='/'");
        echo '支付成功,3秒之后到达首页';


    }
    public function server(){
        if(strpos($server=$_SERVER['HTTP_USER_AGENT'],"Mobile")){
            echo "移动端";
        }else{
            echo"pc端";
        }

        var_dump($server);
    }

}
