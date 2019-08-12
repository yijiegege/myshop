<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/js/jquery.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>购物车订单</title>
</head>
<body>


<table border="1">
    <tr>
        <td>商品图片</td>
        <td>商品名称</td>
        <td>商品描述</td>
        <td>商品价格</td>
        <td>商品库存</td>
        <td>操作1</td>
        <td>购买数量</td>
        <td>修改购买数量</td>
    </tr>
    @foreach($res as $k=>$v)
      <tr>
            <td><img src="/goodsImg/{{$v['goods_img']}}" width="100px" height="100px"></td>
            <td goods_id="{{$v['goods_id']}}" id="goods_id">{{$v['goods_name']}}</td>
            <td id="goods_num">{{$v['goods_num']}}件</td>
            <td id="self_price">{{$v['self_price']}}</td>
            <td>{{$v['goods_desc']}}</td>
            <td><a href="/cart/deletecartgoods?goods_id={{$v['goods_id']}}">删除</a></td>
            <td>{{$cartinfo[$k]['buy_number']}}</td>
            <td><input type="text" id="add_num"><button id="btn">修改</button></td>
     </tr>
    @endforeach
</table>
        总价：<b id="count_price">1</b>￥
        <button id="accounts">去结算</button>
</body>
</html>
<script>
    $(function(){
        // var self_price=parseFloat($("#self_price").html());
        // var goods_num=parseInt($("#goods_num").html());
        // var count_price=self_price*goods_num;
    $("count_price").append("<b>count_price</b>");
        var falg=false;
        $("#btn").click(function(){
            var add_num=$("#add_num").val();
            var goods_num=$("#goods_num").html();
            var goods_id=$("#goods_id").attr('goods_id');
            if(add_num=='' || add_num==0){
                confirm("请输入购买数量");
                return falg;
            };
            if(add_num>goods_num){
                confirm("购买数量大于库存");
                return falg;
            }
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.post(
                "/cart/updatenum",
                {add_num:add_num,goods_id:goods_id},
                function(res){
                    if(res.errorcode==0) {
                        confirm(res.errormsg);
                        location.href='/cart/cartdetail';
                    }else{
                        confirm(res.errormsg);
                        return false;
                    }

                },'json'
            )
        });
        //去结算
        $("#accounts").click(function () {
            var count_price=$("#count_price").html();
            $.post(
                "/order/order",
                {count_price:count_price},
                function(res){
                    if(res.errorcode==0) {
                        confirm(res.errormsg);
                        location.href='/order/orderdetail';
                    }else{
                        confirm(res.errormsg);
                        return false;
                    }
                    // console.log(res);
                },'json'
            )
        })
    })
</script>
