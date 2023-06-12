<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    // 為甚麼protected，單純是官方設定的，記得就對了
    // protected $fillable = ['quantity','product_id'];// 此處為白名單功能，若有修改過要重啟"composer dump-autoload"
    protected $guarded = ['']; // 此處為黑名單
    // protected $hidden = ['updated_at']; // 此處為不會被回傳(response)的資料
    protected $appends = ['current_price']; // 自訂屬性
    public function getCurrentPriceAttribute(){ //命名方式為固定的"get屬性Attribute"，如若沒有正確輸入，會跑出null
        return $this->quantity * 10; // 被撈到的資料的欄位quantity*10
    }
    // 如需更多的設定方式，可至class CartItem extends Model的Model中找，例如：$connection

    public function product(){// 使用單數是因為他是一對多，所以product是單數
        // 此函數執行時，會執行將此檔案屬於Product，去尋找資料表Product有無對應的product_id
        return $this->belongsTo(Product::class);
    }

    public function cart(){// 使用單數是因為他是一對多，所以cart是單數
        // 此函數執行時，會執行將此檔案屬於cart，去尋找資料表cart有無對應的cart_id
        return $this->belongsTo(Cart::class);
    }
}
