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
    // 找出Model去執行命令

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
        $new = now()->toDateTimeString(); // 幫助把時間轉成字串，而且是時分秒
        Excel::store(new OrderExport, 'excels/'.$new.'訂單清單.xlsx');
    }
}
