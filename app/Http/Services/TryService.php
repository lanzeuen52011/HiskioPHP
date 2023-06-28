<?php

namespace App\Http\Services;

use App\Http\Services\ShortUrlInterfaceService;

class TryService
{

    public $shortUrlService;
    public function __construct(ShortUrlInterfaceService $service)
    {
        $this->shortUrlService = $service;
    }

    public function callTry(){
        $service = app()->make('ShortUrlService'); // 建立一個service物件
        dd($service->version);
    }

    public $name = 'matt';
    public function __set($name, $value)
    {
        // $name呼叫的屬性名稱，$value所屬屬性所設定的值
        if(isset($this->$name)){
            // 如果$name是有這個屬性的話，就return $this->name = $value;
            return $this->name = $value;
        }else{
            return null;
        }
    }
    public function __get($name)
    {
        //若呼叫了不存在的參數，則回傳$name
        return $name;
    }
    public function __call($method, $args)
    {
        dump('一般方法');
        dump($method);
        dump($args);
    }
}