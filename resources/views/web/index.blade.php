@extends('layouts.app') 
@section('content')
    <h2>商品列表</h2>
    <table>
        <thead>
            <tr>
                <td>標題</td>
                <td>內容</td>
                <td>價格</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product) <!-- 此處的$products為WebController.php中的'products'-->
            <tr>
                @if( $product->id == 1 )
                    <td class="special-text">{{$product->title}}</td>
                @else
                    <td>{{$product->title}}</td>
                @endif
                <td>{{$product->content}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->quantity}}</td>
                <td>
                    <input class="check_product" type="button" value="確認商品數量" data-id="{{$product->id}}">
                    <input class="check_shared_url" type="button" value="分享商品" data-id="{{$product->id}}">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script
        src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
        crossorigin="anonymous">
    </script>
    <script>
        $('.check_product').on('click',function(){
            $.ajax({
                method: "POST",
                url:'/product/check-product', // 此處不須加"."，或者"http://127.0.0.1:8000"，因此處為相對位置
                data:{id:$(this).data('id')}
            })
            .done(function(response){
                if(response){
                    alert('商品數量充足');
                }else{
                    alert('商品數量不夠');
                }
            })
        })
        $('.check_shared_url').on('click',function(){
            let id = $(this).data('id');
            $.ajax({
                method: "GET",
                url:`/product/${id}/shared-url`,
            })
            .done(function(msg){
                alert('請分享此縮網址' + msg.url);
            })
        })
    </script>
@endsection
