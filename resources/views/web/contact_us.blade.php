@extends('layouts.app')

@section('content')
<h3>聯絡我們</h3>
<form class="w-50" action="">
    <div class="form-group">
        <label for="exampleInputPassword1">請問你是：</label>
        <input name="name" type="text" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">請問你的消費時間：</label>
        <input name="date" type="date" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">你消費的商品種類：</label>
        <select name="product" class="form-control"  id="">
            <option value="物品">物品</option>
            <option value="食物">食物</option>
        </select>
        <br>
    </div>
    <button class="btn btn-success">送出</button>
</form>
@endsection