<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\OrderByShippedSheet;


class OrderMultipleExport implements WithMultipleSheets
// 不需要FromCollection, WithHeadings，是因為資料在活頁簿裡組合而成的，不是在Excel組合而成的
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets():array
    {
        $sheets = [];
        foreach ([true,false] as $isShipped ){
            $sheets[] = new OrderByShippedSheet($isShipped);
        }
        return $sheets;
    }
}
