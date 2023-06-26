<?php

namespace App\Observers;

use App\Models\Product;
use App\Notifications\ProductReplenish;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $changes = $product->getChanges();
        $originals = $product->getOriginal();
        if(isset($changes['quantity']) && $product->quantity > 0 && $originals['quantity'] == 0){ 
        // 如果貨物有被改變且貨物改變後數量大於0且原本的數量是0時
            // 使用['']方法取得代表，前一個值是Array，例如：$changes['quantity']，就代表$changes是Array
            foreach( $product->favorite_users as $user){ 
            //此處favorite_users會直接執行get，如果是favorite_users()，則要變成favorite_users()->get();才有辦法讀取
                $user->notify(new ProductReplenish($product)); // 此處執行後會直接到ProductReplenish的__construct再到toArray
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
