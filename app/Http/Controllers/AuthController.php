<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //
    public function signup(CreateUser $request){
        // 這裡是拿來建立帳號密碼的，前端傳資料過來，建立會員的帳號密碼
        $validatedData = $request->validated();  // 使用validate來呼叫資料，因為驗證通過所以可以呼叫了
        
        $user = new User([
            'name'=>$validatedData['name'],
            'email'=>$validatedData['email'],
            'password'=>bcrypt($validatedData['password']), 
            // 'password'=>$validatedData['password']會直接顯示密碼，因此須加入bcrypt('123456')來保護密碼
            // 沒bcrypt的話，打123456，會直接跑出123456
            // 使用加密函式，bcrypt('123456')，會跑出$2y$10$AKwOUXmULly7Uc2Nsrdwqul8j.GjK6xKrXfYI2McJqiHh1cMb7/dm，以此來保護密碼
        ]);
        
        $user->save(); // 將資料儲存
        return response('success',201);
    }
    public function login(Request $request){ // 此處指單純接使用者回傳的資料，並沒有要做格式驗證
        $validatedData = $request->validate([ // 基礎資料驗證，此處的用意為表格email、password欄位不得為空
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);
        if(!Auth::attempt($validatedData)){ // attempt為，直接將回傳的帳號密碼拿去登入，因此此處為"如果登入失敗"
            return response('授權失敗',401);
        }
        // 授權通過後，user的資料會被放進$request，因此使用$request->user()來撈取user的資料。
        $user = $request->user();
        $tokenResult = $user->createToken('Token'); 
        //此處的createToken函式是來自於app/Http/Model/User內的use Laravel\Passport\HasApiTokens;
        $tokenResult->token->save();
        // 此處將token儲存，以利後續操作與驗證授權
        return response(['token' => $tokenResult->accessToken]);
    }
    public function logout(Request $request){
        $request->user()->token()->revoke(); // revoke，讓通行證(Token)失效
        return response(
            ['message'=>'成功登出'] // 回傳登出成功
        );
    }
    public function user(Request $request){
        // 受保護的API端點
        return response(
            $request->user() // user的資料
        );
    }
}
