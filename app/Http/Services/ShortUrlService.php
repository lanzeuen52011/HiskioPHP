<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ShortUrlService implements ShortUrlInterfaceService
{
    protected $client;
    public  $version = 2.5;
    public function __construct()
    {
        $this->client = new Client();
        dump($this->version);
    }
    public function makeShortUrl($url)
    {   
        try{
            // 皮克看見：https://user.picsee.io/developers/
            $accesstoken = env('URL_ACCESS_TOKEN');
            $data = [
                'url'=>$url,
            ];
            Log::channel('url_shorten')->info('postData',['data'=>$data]); // 指定為info層級，前綴詞為postData
            $response = $this->client->request(
                'POST',
                "https://api.pics.ee/v1/links/?access_token=$accesstoken",
                [
                    'headers'=> ['Content-Type'=> 'application/json'],
                    'body'=>json_encode($data)
                ]
            );
            $contents = $response->getBody()->getContents();
            Log::channel('url_shorten')->info('responseData',['data'=>$contents]);
            $contents = json_decode($contents);
        }catch(\Throwable $th){
            report($th); // 發生錯誤時，會執行(app/Exceptions/Handler.php)裡面的$this->reportable
            return $url; // 假設縮網址真的有問題給不出來，至少該給出網址本身
        }
        return $contents->data->picseeUrl;
    }
}















