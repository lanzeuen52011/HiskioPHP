<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends APIRequest // 使用APIRequest來檢查
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //先改成true，讓它通過即可，因現在還用不到此功能
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        return [
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users', 
            //uniqle:users，使用者單一性，此處指每個在users資料表中的使用者的email必須具備唯一性，僅限單對單
            'password'=>'required|string|confirmed', 
            // confirmed，此處會使得使用者做重複確認，就是註冊時"確認密碼"的欄位，因此前端回傳的password與password_confirmation必須一致
        ];
    }
}
