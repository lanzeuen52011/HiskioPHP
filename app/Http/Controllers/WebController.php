<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\Jobs\DatabaseJob;

class WebController extends Controller
{
    public $notifications = [];
    public function __construct()
    {
        $user = User::find(2);
        $this->notifications = $user->notifications ?? []; // 此關聯是在Model/User中的use Notifiable中建立的，因此可直接使用
        // $user->notifications ?? [] 的意思是指，如果$user->notifications存在就使用$user->notifications，如果不存在就[]
    }
    public function index(){
        $products = Product::all();
        
        return view('web.index',['products'=> $products,'notifications'=>$this->notifications]);
    }
    public function contactUs(){
        return view('web.contact_us',['notifications'=>$this->notifications]);
    }
    public function readNotification(Request $request){
        $id = $request->all()['id'];
        DatabaseNotification::find($id)->markAsRead(); // 會幫忙押上資料表notifications中的欄位read_at的值

        return response(['result'=>true]);
    }
}
