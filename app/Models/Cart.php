<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = ['']; // 此處為黑名單
    private $rate = 1; // 費率
    public function cartItems(){
        return $this->hasMany(CartItem::class); //設定好關聯Cart.php底下有CartItem
    }
    public function user(){
        return $this->belongsTo(User::class); //設定好關聯Cart.php是User的附屬
    }
    public function order(){
        return $this->hasOne(Order::class); //設定好關聯Cart.php是底下只會有一個Order
    }
    public function checkout(){
        DB::beginTransaction();
        try{
            //檢查要在創造前
            foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                $product = $cartItem->product;
                if(!$product->checkQuantity($cartItem->quantity)){
                    return $product->title.'數量不足'; //執行到此會直接結束foreach，並回傳此
                }
            }
            $order = $this->order()->create([
                'user_id'=> $this->user_id, // 直接指向正被使用的購物的user_id
            ]);
            if($this->user->level ==2){
                $this->rate = 0.8; //如果是vip(使用者等級2)，就打八折
            }
            foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                $order->orderItems()->create([
                    'product_id'=>$cartItem->product_id,
                    'price' => $cartItem->product->price *$this->rate
                ]);
                $cartItem->product->update(['quantity'=>$cartItem->product->quantity - $cartItem->quantity]);
                // 購買後將產品減少
            }

            $this->update(['checkouted'=>true]);
            $order->orderItems;
            DB::commit();
            return $order; // 回傳訂單長甚麼樣子
        } catch(\Throwable $th){
            DB::rollBack();
            return 'somethings error';
        }
    }
}
