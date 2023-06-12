<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class APIRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    // 覆蓋掉FormRequest中的函式，並使用Illuminate\Contracts\Validation\Validator;來幫助檢查
    {
        throw new HttpResponseException(response(['errors'=>$validator->errors(),400])); //回傳錯誤
    } 
}