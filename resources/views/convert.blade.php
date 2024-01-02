@extends('layouts.app')

@section('content')
<form action="/convert-excel" method="POST" enctype="multipart/form-data">
    <input type="file" id="file" name="file">
    <button type="submit" value="送出">送出</button>
</form>
@endsection
