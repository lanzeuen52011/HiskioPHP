<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDirtyWord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dirtyWords  = [ //可能的髒字(?)
            'apple',
            'orange'
        ];
        $parameters  = $request->all(); // 前端傳來的資料
        foreach($parameters as $key => $value){
            if($key == 'content'){
                // 每個是content的內容就判斷
                foreach($dirtyWords as $dirtyWord){
                    // 一個一個字判斷
                    if(strpos($value,$dirtyWord) !== false){ //切記不可用!=，必須得用!==，因若是返回值為Index 0，會被判斷成false，因此必須為!==。
                    //使用strpos來判斷$value有沒有包含$dirtyWords，回傳包含$dirtyWords的第幾個引數開始，沒有則反為false
                        return response('dirty',400);
                    }
                }
            }
        }
        return $next($request);
    }
}
