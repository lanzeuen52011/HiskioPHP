<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = ['']; // 此處為黑名單
    public function product(){
        return $this->belongsTo(Product::class); //設定好Order.php要怎麼去取得OrderItem
    }
    public function order(){
        return $this->belongsTo(Order::class); //設定好Order.php要怎麼去取得User
    }
}
