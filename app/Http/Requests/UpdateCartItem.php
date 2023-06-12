<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\FlareClient\Api;

class UpdateCartItem extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'quantity'=>'required|integer|between:1,10'
        ];
    }
    public function messages(): array //這裡不須額外寫甚麼時候執行，因為Laravel會自己來找
    {
        return [
            'quantity.between'=>'數量必須小於10' //between驗證到錯誤時，回傳的messages
        ];
    }
}
