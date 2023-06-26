@extends('layouts.admin_app') 

@section('content')
<h2>產品列表</h2>
<span>產品總數： {{ $productCount }}</span>
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach( $errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- 製作一個table讓訂單資料顯示出來 -->
<table>
    <thead>
        <tr>
            <td>編號</td>
            <td>標題</td>
            <td>內容</td>
            <td>價格</td>
            <td>數量</td>
            <td>圖片</td>
            <td>功能</td>
        </tr>
    </thead>
    <tbody>
        <!--此處任何$product底下的東西，都可以參考Model/Order是否設有與當下的東西有關聯，例：user和productItems-->
        <!-- 防呆，isset函式可以協助偵測某變數是否存在值 -->
        <!-- isset($product->$productItems) ? $product->$productItems->sum('price') : 0 ， $product->$productItems若有值則加總price欄位的值-->
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td> 
                <td>{{ $product->content }}</td> 
                <td>{{ $product->price }}</td> 
                <td>{{ $product->quantity }}</td> 
                <td>
                    <a href="{{ $product->image_url }}">圖片連結</a>
                </td>
                <td>
                    <!-- <input type="button" class="upload-image" data-id="{{$product->id}}" value="上傳圖片"  data-bs-toggle="modal" data-bs-target="#upload-image"> -->
                    <input type="button" class="upload-image" data-id="{{$product->id}}" value="上傳圖片">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- 新增一個分頁欄(可點選下一頁的功能) -->
<div>
    <!--$productPages為此次訂單共分為幾個頁面  -->
    <!-- a的部分在href使用"?"使得後端可接收資料 -->
    @for ($i = 1; $i <= $productPages; $i++)
        <a href="/admin/products?page={{ $i }}">第 {{$i}} 頁</a> &nbsp;
    @endfor
</div>
<script>
    const myModal = new bootstrap.Modal('#upload-image', {
            keyboard: false
        })
    const modalToggle = document.getElementById('toggleMyModal'); 
    $('.upload-image').click(function(){
        $('#product_id').val($(this).data('id'));
        myModal.show(modalToggle);
    });
    
        
</script>
@endsection











