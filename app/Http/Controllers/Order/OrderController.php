<?php

namespace App\Http\Controllers\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\OrderModel;
class OrderController extends Controller
{
    public function Auth(){
        if(Auth::id()==''){
            echo '请去登录';
            header("Refresh:1;url=/login");die;
        }
    }
    public function order(){
        $this->Auth();
        $count_price=$_POST['count_price'];
        $user_id=Auth::id();
        $order_info=[
            'order_no'=>date('Ymd').rand(11111,99999),
            'user_id'=>$user_id,
            'order_amount'=>$count_price,
            'create_time'=>time(),
        ];
        $res=OrderModel::insert($order_info);
        if($res==1){
            $response=[
                'errorcode'=>0,
                'errormsg'=>'结算成功'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }else{
            $response=[
                'errorcode'=>1,
                'errormsg'=>'结算失败'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }

    public function orderdetail(){
        $this->Auth();
       $order= OrderModel::where(['user_id'=>Auth::id()])->get()->toArray();
       return view("order/orderdetail",compact('order'));
    }

}
