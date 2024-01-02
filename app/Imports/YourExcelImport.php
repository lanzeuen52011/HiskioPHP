<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class YourExcelImport implements ToCollection,ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // return $rows->map(function ($row) {
        //     $colorData = [
        //         'color1' => [
        //             'name' => $row['color1Name'],
        //             'hex' => $row['color1'],
        //             'src' => $row['color1Src'],
        //         ],
        //         // ... 其他顏色的處理
        //     ];

        //     $row['color'] = $colorData;
        //     unset($row['color1'], $row['color1Src'], $row['color1Name'], $row['color2'], $row['color2Src'], $row['color2Name'], $row['color3'], $row['color3Src'], $row['color3Name']);

        //     return $row;
        // });
    }
    public function model(array $row)
    {
        // $row 是一個包含 Excel 行數據的關聯數組
        // 關鍵是 Excel 文件的列標題，值是該行該列的數據
        // 例如，如果你的 Excel 文件有一列標題為 "name"，你可以使用 $row['name'] 來獲取該列的數據

        // 這裡你可以處理 $row 數據，例如保存到數據庫，或者進行其他操作
        // 以下是一個示例，展示了如何打印出 $row 數據
        // dump($row);
        // return $row;
    }
    // public $data;

    // public function collection(Collection $rows)
    // {
    //     $this->data = $rows->map(function ($row) {
    //         // 這裡是你的數據轉換邏輯
    //         // ...
    //         return $row;
    //     });
    // }
}
