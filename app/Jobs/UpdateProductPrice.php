<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $product;
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //這個UpdateProductPrice執行時，就要來執行這裡的程式碼
        sleep(5); // 模擬大程式，所以增加此五秒
        $this->product->update(['price'=>$this->product->price * random_int(2,5)]); // 更新價格
        // random_int(2,5)，隨機產生2~5的int
        // 那麼這些工作(UpdataeProductPrice.php)要存在哪裡，通常分為兩種
        // 1.存在資料庫內，類似推播(Notification)的A-第2.與第3.
        // 2.使用Redis，將資料存在電腦本機的快取等等的方式，比較輕量
        // 而此處示範是第1.種方式，因比較視覺化且好確認
    }
}
