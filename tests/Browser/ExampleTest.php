<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PhpParser\Node\Expr\Eval_;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations; // 拿Schema跑Migration，讓Schema Rollback
    protected function setup():void
    {
        parent::setUp(); // 使用TestCase的setUp
        User::factory()->create([ // 因網站的通知是針對user去通知的，因此先建立user
            'email' => 'john@gmail.com',
        ]);
        // Product::factory()->create(); // 這種方法也可以，要記得去Product設定HasFactory就好
        // 但此處範例不使用上方註解之方法，而使用以下
        Artisan::call('db:seed', ['--class' => 'ProductSeeder']); // 使用Artisan::call呼叫db:seed指令，並指定Class為ProductSeeder
    }

    protected function driver(): RemoteWebDriver // 將headless拿掉
    {
        $options = (new ChromeOptions)->addArguments([
            // '--disable-gpu'
            // '--headless'
        ]);
        return RemoteWebDriver::create(
           'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    public function testBasicExample():void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->with('.special-text',function($text){ 
                        // .special-text是指 index.blade.php有個class叫做special-text
                        // $text那一個物件文字
                        $text->assertSee('固定資料');
                    });

            $browser->click('.check_product') // 點擊index.blade.php有個class叫做check_product
                    ->waitForDialog(5) // 5秒內網頁應該要有回應或者動作，如果沒有就報錯 
                    ->assertDialogOpened('商品數量充足') // 檢查Dialog內的文字，應該等於'商品數量充足'
                    ->acceptDialog() // 對跳出的視窗按下確定
                    ;
        });
    }


    public function testFillForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('contact-us')
                    ->value('[name="name"]', 'cool') // 把name="name"的值設定成'cool'，就是在name="name"的欄位輸入cool
                    ->select('[name="product"]', '食物') // 把name="product"的值設定成'食物'，就是在name="product"的欄位選擇食物
                    ->press('送出') // 點擊送出
                    ->assertQueryStringHas('product','食物')
                    ;
                eval(\Psy\sh());
        });
    }
}
