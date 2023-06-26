<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderDelivery;

class OrderController extends Controller
{
    public function index(Request $request)
    {  
        $orderCount = Order::whereHas('orderItems')->count(); // 執行SQL裡面的count函數，如果要sum就改成sum('欄位名稱')
        $dataPerpage = 2; // 設定成一頁兩筆資料
        $orderPages = ceil($orderCount / $dataPerpage); 
        // 會有幾頁的資料，會使用ceil函式(無條件進位)是因為假如最後一頁剩一筆資料或者沒填滿，還是必須顯示出來，會多一頁。
        
        $currentPage = isset($request->all()['page']) ? $request->all()['page'] : 1 ; // 當前頁數為幾，如果沒有回傳頁數則為1
        // 此處的orderBy，可參考：SQL 特殊參數 - order
        // 因此處在get以前，都只是單純的SQL函式，處理完之後沒有下get，它會不知道要把資料送過來
        $orders = Order::with(['user','orderItems.product'])->orderBy('created_at','desc') // 使用with，在撈資料前，先撈出關聯的Model資料，可使前端執行時SQL語法減少
                            // 此處的orderItems.product可以將orderItem與product的關聯都拉進來
                        ->offset($dataPerpage * ($currentPage - 1)) // 假如第1頁，就從第1筆資料(引數為[0])開始搜尋，第2頁就是2*(2-1)，從第三筆資料(引數為[2])開始搜尋
                        ->limit($dataPerpage) // 限制每頁兩個資料
                        ->whereHas('orderItems') // 去找是否有orderItem這個關聯Model，此為Model/Order.php底下的orderItems，只顯示有orderItems的資料
                        // ->dd(); //可查看到dd為止的所有SQL語法
                        ->get();

        return view('admin.orders.index',['orders'=>$orders,
                                          'orderCount'=>$orderCount,
                                          'orderPages'=>$orderPages,
                                        ]);
    }
    public function delivery($id){ // 收到前端回傳的$id
        $order = Order::find($id); 
        if($order->is_shipped){ // 如果貨物已被送出
            return response(['result'=>false]);
        }else{
            $order->update(['is_shipped'=>true]); // 把貨物被送出的狀態改成true
            $order->user->notify(new OrderDelivery); 
            // 在(Model/User.php)有use Notifiable，所以可以直接指令進去就行，此檔案按下儲存後會自動新增 "use App\Notifications\OrderDelivery;"
            return response(['result'=>true]);
        }
    }
}
