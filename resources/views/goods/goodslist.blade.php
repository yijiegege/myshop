<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="/js/jquery.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <table border="1" width="800px" height="200px">
        <tr>
            <td>商品图片</td>
            <td>商品名称</td>
            <td>商品描述</td>
            <td>商品价格</td>
            <td>商品添加数量</td>
            <td>操作</td>
        </tr>
        <tr>
            <td id="goods_id" class="a" goods_id="{{$res['goods_id']}}"><img src="/goodsImg/{{$res['goods_img']}}" width="100px" height="100px"></td>
            <td>{{$res['goods_name']}}</td>
            <td>{{$res['goods_desc']}}</td>
            <td>{{$res['self_price']}}</td>
            <td><input type="text" style="width:50px;height:50px" id="add_num"></td>
            <td><button id="btn">添加到购物车</button>库存:<b id="goods_num">{{$res['goods_num']}}</b></td>
        </tr>
    </table>
</body>
</html>
<script>
    $(function(){
        //加入购物车
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
                    "/cart/cartlist",
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

    })
</script>