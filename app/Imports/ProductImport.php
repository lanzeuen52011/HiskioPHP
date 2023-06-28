<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) // (array $row)，當檔案傳進來的時候，檔案被匯入時，會一行一行的匯入
    {
        return new Product([
            'title' => $row[0],
            'content' => $row[1],
            'price' => $row[2],
            'quantity' => $row[3],
        ]);
    }
}
