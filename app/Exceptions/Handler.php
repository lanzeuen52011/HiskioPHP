<?php

namespace App\Exceptions;

use App\Models\LogError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void // 紀錄發生錯誤訊息時，應當執行哪個函式，或者該顯示何種錯誤頁面
    {
        $this->reportable(function (Throwable $exception) {
            //使用reportable函式，可使用各種report功能
            $user = auth()->user(); //取得目前執行到發生錯誤的user是誰(user_id)
            LogError::create([
                'user_id' => $user ? $user->id : 0,
                'message' => $exception->getMessage(), 
                // 因發生錯誤時，資料都被包在$exception這個物件內，因此使用getMessage()取得錯誤訊息
                'exception' => get_class($exception), // 可得知錯誤訊息屬於哪個類別
                'line' => $exception->getline(),  // 取得錯誤訊息在第幾行
                'trace' => array_map(function($trace){
                    unset($trace['args']); //先將多餘的參數移除掉，以防止資料肥大，再回傳乾淨的$trace
                    return $trace; // 回傳乾淨的$trace
                },$exception->getTrace()), 
                // 直接使用$exception->getTrace()會造成它將所有資料調入，也造成無意義的資料也一起被撈入，導致資料庫肥大，因此需array_map
                'method' => request()->getMethod(),//取得前端使用的method
                'params' => request()->all(),//取得user回傳的參數
                'uri' => request()->getPathInfo(),//取得user的網址資訊
                'user_agent' => request()->userAgent(),//取得user的瀏覽器
                'header' => request()->headers->all(),//取得user的屬性
            ]);
        });
        $this->renderable(function(Throwable $exception){
            return response()->view('error');
        });
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // 這是handler.php原先就有的函式，但在此處是為了要將原本的函式覆寫
        // 當發現錯誤時，會來執行這裡的程式
        return response('授權失敗',401);
    }
}
