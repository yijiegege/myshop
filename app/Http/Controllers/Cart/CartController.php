<?php

namespace App\Http\Controllers\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Goodsinfo;
use App\Model\CartModel;
class CartController extends Controller
{
    public function Auth(){
        if(Auth::id()==''){
            echo '请去登录';
            header("Refresh:1;url=/login");die;
        }
    }
    public function Cartlist(Request $request){
            $this->Auth();
           $add_num=$_POST['add_num'];
           $goods_id=$_POST['goods_id'];
           $res1=CartModel::where(['goods_id'=>$goods_id,'cart_status'=>1])->first();
           $goods_info=Goodsinfo::where(['goods_id'=>$goods_id])->first();
        //        var_dump($res1);die;
            if($res1){ //这个购物车里有这个商品id
                            $res2=CartModel::where(['goods_id'=>$goods_id,'user_id'=>Auth::id()])->first();
                            if($res2!=NUll){ //这个购物车里有加过这个商品的用户
                                $buy_number=$res2['buy_number'];
                                $add_goods_num=[
                                    'buy_number'=> $buy_number+$add_num
                                ];
                                $res3=CartModel::where(['goods_id'=>$goods_id,'user_id'=>Auth::id()])->update($add_goods_num);
//                                var_dump($res3);die;
                                if($res3!=''){
                                    $response=[
                                        'errorcode'=>0,
                                        'errormsg'=>'加入购物车成功'
                                    ];
                                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                                }else{
                                    $response=[
                                        'errorcode'=>1,
                                        'errormsg'=>'加入购物车失败'
                                    ];
                                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                $cart_info=[
                                    'goods_id'=>$goods_info['goods_id'],
                                    'buy_number'=>$add_num,
                                    'user_id'=>Auth::id(),
                                    'create_time'=>time()
                                ];
                                $res=CartModel::insert($cart_info);
                                if($res==true){
                                    $response=[
                                        'errorcode'=>0,
                                        'errormsg'=>'加入购物车成功'
                                    ];
                                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                                }else{
                                    $response=[
                                        'errorcode'=>1,
                                        'errormsg'=>'加入购物车失败'
                                    ];
                                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                                }
                            }
            }else{ ///这个购物车里没有有这个商品id
                            $cart_info=[
                                'goods_id'=>$goods_info['goods_id'],
                                'buy_number'=>$add_num,
                                'user_id'=>Auth::id(),
                                'create_time'=>time()
                            ];
                            $res=CartModel::insert($cart_info);
                            if($res==true){
                                $response=[
                                    'errorcode'=>0,
                                    'errormsg'=>'加入购物车成功'
                                ];
                                die(json_encode($response,JSON_UNESCAPED_UNICODE));
                            }else{
                                $response=[
                                    'errorcode'=>1,
                                    'errormsg'=>'加入购物车失败'
                                ];
                                die(json_encode($response,JSON_UNESCAPED_UNICODE));
                            }

        }

    }
    public function Cartdetail(){
        $this->Auth();
        $res=CartModel::where(['cart_status'=>1,'user_id'=>Auth::id()])
            ->leftJoin("shop_goods","shop_goods.goods_id","=",'shop_cart.goods_id')
            ->get()
            ->toArray();
//        var_dump($res);die;
        $cartinfo=CartModel::where(['cart_status'=>1,'user_id'=>Auth::id()])
            ->get()
            ->toArray();
        return view("cart.cartdetail",compact('res','cartinfo'));
    }
    public function deletecartgoods(){
        $this->Auth();
        $goods_id=$_GET['goods_id'];
        $update_info=[
            'cart_status'=>2
        ];
        $res=CartModel::where(['goods_id'=>$goods_id,'user_id'=>Auth::id()])->update($update_info);
        if($res!='' ){
            echo "删除成功";
            header("Refresh:1;url=/cart/cartdetail");
        }

    }
    public function updatenum(){
        $this->Auth();
        $add_num=$_POST['add_num'];
        $goods_id=$_POST['goods_id'];
        $res=CartModel::where(['goods_id'=>$goods_id])->first()->toArray();
        $add_goods_num=[
            'buy_number'=>$res['buy_number']+$add_num
        ];
        $res3=CartModel::where(['goods_id'=>$goods_id,'user_id'=>Auth::id()])->update($add_goods_num);
        if($res3==1){
            $response=[
                'errorcode'=>0,
                'errormsg'=>'修改购买数量成功'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }else{
            $response=[
                'errorcode'=>1,
                'errormsg'=>'修改购买数量失败'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }
}
