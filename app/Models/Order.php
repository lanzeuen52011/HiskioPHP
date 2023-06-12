<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['']; // 此處為黑名單
    public function orderItems(){
        return $this->hasMany(OrderItem::class); //設定好關聯Order.php的底下有OrderItem
    }
    public function user(){
        return $this->belongsTo(User::class); //設定好關聯Order.php是User的附屬
    }
    public function cart(){
        return $this->belongsTo(Cart::class); //設定好關聯Order.php是Cart的附屬
    }
}
