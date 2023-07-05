<?php

namespace App\Console\Commands;

use App\Exports\OrderExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:orders';
    // 找出Model並執行命令
    // 在php artisan中的指令名稱，"php artisan ($signature)" => 正確的輸入方式會變成"php artisan export:orders"
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now()->toDateTimeString(); // 幫助把時間轉成字串，而且是時分秒
        // dd($now);
        $try = str_split($now);
        $collect = "";
        foreach($try as $item){
            if($item != ":"){
                $collect = $collect.$item;
            }
        }
        Excel::store(new OrderExport, "excels/{$collect}訂單清單.xlsx");
        return 0;
    }
}
