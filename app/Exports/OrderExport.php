<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    // FromCollection(collection) ：資料會以Collection格式傳送
    // WithHeadings(headings) ：Excel中會產生標頭
    // WithColumnFormatting(columnFormats) ：Excel匯出的資料格式更改，如：時間戳->西元日期
    // WithEvents(registerEvents) 當Excel被做出來後才去上色
    /**
    * @return \Illuminate\Support\Collection
    */
    public $dataCount;
    public function collection()
    {
        $orders = Order::with(['user','cart.cartItems.product'])->get();
        $orders = $orders->map(function($order){
            // 使$order重組陣列
            return [
                $order->id, // 訂單id
                $order->user->name, // 訂單買家
                $order->is_shipped, // 訂單是否運送
                $order->cart->cartItems->sum(function($cartItems){
                    return $cartItems->product->price * $cartItems->quantity;
                }),
                Date::dateTimeToExcel($order->created_at)
            ];
        });
        $this->dataCount = $orders->count()+1; // 紀錄總共有幾筆資料，因為有可能只需要其中的某些資料做格式設定而已
        // +1是為了讓數字正確
        return $orders;
    }
    public function headings() : array
    {
        return ['編號','購買者','是否運送','總價','建立時間'];
    }
    public function columnFormats():array
    {
        return[
            'B' => NumberFormat::FORMAT_TEXT, //B欄所使用的格式
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                // 使用AfterSheet，來指定當資料表被製作出來時，需執行的程式碼
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50); // 設定A欄的寬度為300
                // 使用getDelegate()來取用PhpSpreadsheet中的函式，老師極度推薦讀完PhpSpreadsheet官方文件的Recipes，getActiveSheet()=getDelegate()
                // 此處是針對單頁所設計的程式碼，因此如果是多頁的，須將此程式碼放到Exports/Sheets中
                for($i=0 ; $i < $this->dataCount ; $i++){
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50); // 設定$i列的高度為50
                }
                $event->sheet->getDelegate()->getStyle('A1:B'.$this->dataCount)->getAlignment()->setVertical('center');
                // 從A1到B ${$this->dataCount} 的表格都置中

                // 批次格式調整，其他的幾乎都是針對單一特性或格式去調整
                $event->sheet->getDelegate()->getStyle('A1:A'.$this->dataCount)->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true,
                        'italic' => true,
                        'color' => [
                            'rgb' => 'FF0000'
                        ]
                        ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '000000'
                        ],
                        'endColor' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]);
                // 合併儲存格，合併G1到H1的儲存格
                $event->sheet->getDelegate()->mergeCells('G1:H1');
            }
        ];
    }
}
