<?php

namespace Tests\Feature;

use App\Http\Services\AuthService;
use App\Http\Services\ShortUrlService;
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase; // 使用此測試程式時，會協助把資料庫全部清空，因確保獨立性，意旨每次測試時，都不會被任何資料給左右
    
    private $fakeUser; // 因CartItem預設是已登入狀態才可使用，因此須建立此變數

    protected function setUp(): void // void的意思就是不回傳
    {
        parent::setUp();

    }

    public function testSharedUrl() // 測試ProductController.php 的 sharedUrl()
    {
        $product = Product::factory()->create();
        $id = $product->id;
        // 開始使用mock
        $this->mock(ShortUrlService::class, function($mock)use($id){
        // mock ShortUrlService 這個class
            $mock->shouldReceive('makeShortUrl')
                ->with("http://localhost:8000/product/$id") // 裡面應該是這樣的一個網址
                ->andReturn('fakeUrl') // 回傳'fakeUrl'
                ;
        });

        $this->mock(AuthService::class, function($mock){
        // mock ShortUrlService 這個class
            $mock->shouldReceive('fakeReturn');
        });

        $response = $this->call(
            'GET',
            'product/'.$id.'/shared-url', // 組出web.php中的路由 /product/{id}/shared-url
        );
        $response->assertOk();
        $response = json_decode($response->getContent(), true); // 得到值為true
        // 因ProductController.php的shareUrl是回傳return response(['url'=>$url]);，是json格式
        $this->assertEquals($response['url'], 'fakeUrl'); // 確認打完API後回收到的是fakeUrl
    }
}