@extends('layouts.admin_app') 

@section('content')

<h2>訂單列表</h2>
<span>訂單總數： {{ $orderCount }}</span>
<!-- 製作一個table讓訂單資料顯示出來 -->
<table>
    <thead>
        <tr>
            <td>購買時間</td>
            <td>購買者</td>
            <td>商品清單</td>
            <td>訂單總額</td>
            <td>是否運送</td>
        </tr>
    </thead>
    <tbody>
        <!--此處任何$order底下的東西，都可以參考Model/Order是否設有與當下的東西有關聯，例：user和orderItems-->
        <!-- 防呆，isset函式可以協助偵測某變數是否存在值 -->
        <!-- isset($order->$orderItems) ? $order->$orderItems->sum('price') : 0 ， $order->$orderItems若有值則加總price欄位的值-->
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->user->name }}</td> 
                <td>
                    @foreach ($order->orderItems as $orderItem)
                        {{ $orderItem->product->title }} &nbsp;
                    @endforeach
                </td>
                <td>
                    {{ 
                        isset($order->orderItems) ? $order->orderItems->sum('price') : 0
                    }}
                </td>
                <td>{{ $order->is_shipped }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- 新增一個分頁欄(可點選下一頁的功能) -->
<div>
    <!--$orderPages為此次訂單共分為幾個頁面  -->
    <!-- a的部分在href使用"?"使得後端可接收資料 -->
    @for ($i = 1; $i <= $orderPages; $i++)
        <a href="/admin/orders?page={{ $i }}">第 {{$i}} 頁</a> &nbsp;
    @endfor
</div>

@endsection









