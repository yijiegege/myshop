<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Goodsinfo;
use Illuminate\Support\Facades\Auth;
class GoodsController extends Controller
{
    public function Auth(){
        if(Auth::id()==''){
            echo '请去登录';
            header("Refresh:1;url=/login");die;
        }
    }
    //商品列表
    public function  Goodslist(Request $request){
        $this->Auth();
        $goods_id=$request->input('goods_id');
        $res=Goodsinfo::where(['goods_id'=>$goods_id])->first()->toArray();
//        var_dump($res);die;
        return view("goods.goodslist",compact('res'));
    }
    public function  Good(){
        $this->Auth();
        $res=Goodsinfo::get()->toArray();
        return view("goods.goodslist",compact('res'));
    }
}
