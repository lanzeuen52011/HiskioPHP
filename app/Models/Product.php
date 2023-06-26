<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $guarded =[''];
    public function cartItems(){
        return $this->hasMany(CartItem::class); //設定好Product.php要怎麼去取得CartItem
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class); //設定好Product.php要怎麼去取得OrderItems
    }
    public function favorite_users()
    {
        return $this->belongsToMany(User::class,'favorites');// 跟User的class有關係，且針對favorites的資料表去查詢
    }

    public function checkQuantity($quantity){
        if($this->quantity < $quantity){
            return false; // 資料庫內的數量小於要被訂購的數量，就回傳false
        }   
        return true; // 沒事就true
    }
    public function image()
    {
        return $this->morphMany(Image::class,'attachable'); // 一對多的關聯函式
    }
    public function getImageUrlAttribute()
    {
        $images = $this->image;
        if($images->isNotEmpty()){
            return Storage::url($images->last()->path);
        }
    }
}
