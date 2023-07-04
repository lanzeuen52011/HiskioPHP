<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase; // 使用此測試程式時，會協助把資料庫全部清空，因確保獨立性，意旨每次測試時，都不會被任何資料給左右
    
    private $fakeUser; // 因CartItem預設是已登入狀態才可使用，因此須建立此變數

    protected function setUp(): void // void的意思就是不回傳
    {
        // setUp就是跑測試時會預設執行的函式
        parent::setUp(); // 使程式執行該執行的程式，來自TestCase該執行的程式
        // 建立一個fakeuser的假帳號密碼
        $this->fakeUser = User::create(['name'=> 'john',
                                        'email'=>'john@gmail.com',
                                        'password'=>123456789,]);
        // 因為此專案是使用Passport來驗證，才引入Passport，如其他專案驗證套件非Passport，則需額外引入
        Passport::actingAs($this->fakeUser); // actingAs代表表現的像$this->fakeUser，到此就有辦法辨識你是$fakeuser了(登入的意思)
    }

    public function testStore(): void
    {
        // 重複性假資料 --- 開頭
        $cart = Cart::factory()->create([
            'user_id' => $this->fakeUser->id
        ]);
        $product = Product::factory()->create();
        // 重複性假資料 --- 結尾
        // A.預測'quantity' => 2的資料會回傳200，如果正確的話，就會是true(測試通過)
        $response = $this->call(
            'POST', // 使用POST方法
            'cart-items', // 打到這個網址
            ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 2] // 要傳送的data
        );
        $response->assertOK(); // 執行是成功的就會回傳true，代表測試通過

        // 測試less
        $product = Product::factory()->less()->create(); // 使用less使量少
        // A.預測'quantity' => 2的資料會回傳200，如果正確的話，就會是true(測試通過)
        $response = $this->call(
            'POST', // 使用POST方法
            'cart-items', // 打到這個網址
            ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 10] // 要傳送的data
        );
        $this->assertEquals($product->title.'數量不足', $response->getContent());

        // B.預測'quantity' => 99999999的資料會回傳400，如果正確的話，就會是true(測試通過)
        $response = $this->call(
            'POST', // 使用POST方法
            'cart-items', // 打到這個網址
            ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 99999999] // 要傳送的data
        );
        $response->assertStatus(400); // 預測回傳連線狀態400，預測正確則true(測試通過)
    }
    public function testUpdate()
    {   
        // 重複性假資料 --- 開頭
        $cartItem = CartItem::factory()->create();
        // 重複性假資料 --- 結尾
        $response = $this->call(
            'PUT', // 使用PUT方法
            'cart-items/'.$cartItem->id, // 打到這個網址
            ['quantity' => 1] // 要傳送的data
        );
        $this->assertEquals('true',$response->getContent()); 
        // 期待$response->getContent()會是回傳true，因資料沒有故意打錯，且CartItemController.php會在update正確時回傳true

        // 確認資料是否有被正確執行
        $cartItem->refresh(); // 使CartItems資料更新，因沒此行的話，資料即使修改了，讀取到資料庫的資料，仍會是修改前的資料
        $this->assertEquals(1,$cartItem->quantity); // 確認此cartItem的數量是1
    }
    public function testDestroy(){
        // 重複性假資料 --- 開頭
        $cart = Cart::factory()->create([
            'user_id' => $this->fakeUser->id
        ]);
        $product = Product::factory()->make();
        $cartItem = $cart->cartItems()->create(['product_id' => $product->id, 'quantity' => 10]);
        // 重複性假資料 --- 結尾
        $response = $this->call(
            'DELETE', // 使用DELETE方法
            'cart-items/'.$cartItem->id, // 打到這個網址
            ['quantity' => 1] // 要傳送的data
        );
        $response->assertOK(); 

        //確認資料有無真的被砍掉了
        $cartItem = CartItem::find($cartItem->id); // 搜尋剛剛砍掉的CartItem資料
        $this->assertNull($cartItem); // $cartItem的值必須是Null
    }
}
