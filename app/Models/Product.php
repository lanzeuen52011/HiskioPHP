<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function checkQuantity($quantity){
        if($this->quantity < $quantity){
            return false; // 資料庫內的數量小於要被訂購的數量，就回傳false
        }   
        return true; // 沒事就true
    }
}
