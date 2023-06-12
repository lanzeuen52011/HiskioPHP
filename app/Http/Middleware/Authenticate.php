<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string // redirectTo，為登入失敗時會訊息來自於此
    // {
    //     // return $request->expectsJson() ? null : route('login'); // 導流至login，但今天不需要(but not today，這句英文沒啥意函，只是想這樣打而已)
    // }
}
