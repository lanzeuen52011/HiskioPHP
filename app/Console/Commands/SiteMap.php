<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;
use Spatie\Sitemap\SitemapGenerator;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Route;

class SiteMap extends Command
{
    public $data=[];
    public $path='';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Will Generate Site Map';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $fileName = 'sitemap.xml';
        // SitemapGenerator::create('https://example.com')->writeToFile($this->path.$fileName);
        try{
            $fileName = 'sitemap.xml';
            $this->path = public_path( path:'\sitemap\\' );
            $routes = Route::getRoutes();
            // 發布到路徑中
            dd($this->path );
            // ini_set(varname:"memory_limit", newvalue:"-1");
            // set_time_limit( seconds:0);
            // ini_set(varname:"max_execution_time", newvalue:"0");
            // ignore_user_abort(value:true);
            // 執行時的基礎設置，如執行記憶體不限、執行時間不限等

            $filePathAndName = $this->path . $fileName;

            if(file_exists($filePathAndName)){
                // 這段判斷的意思是，如果有舊的sitemap，就將其命名為'sitemap-old-' . date(format:'D-d-M-Y h-s') . 'xml'
                chmod($this->path, 0777);
                chmod($filePathAndName, 0777);
                rename($filePathAndName, $this->path . 'sitemap-old-' . date(format:'Y-M-d-D H-s') . 'xml');
            }

            $sitemap = SitemapGenerator::create('https://localhost:8000/')
                ->getSitemap();
            dd(Carbon::yesterday());

            foreach ($routes as $route) {
                // 取得路由的相關資訊
                $uri = $route->uri();
                $methods = $route->methods();
                $action = $route->getAction();

                // 處理你的邏輯...
                $sitemap->add(Url::create($uri)
                    ->setLastModificationDate(Carbon::yesterday())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            }

            $sitemap->writeToFile($filePathAndName);
           
            

            // Location To Sitemap File
            $sitemapUrl = 'http://localhost/public/sitmap'.$fileName;
            // cUrl handler to ping the Sitemap submission
            function myCurl($url){
                $ch = curl_init($url);
                curl_setopt($ch, option:CURLOPT_HEADER, value:0);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch,opt:CURLOPT_HTTP_CODE);
                curl_close($ch);
                return $httpCode;
            }
            // Sitemap For Google
            // $url = "https://www.google.com/webmaster/sitemaps/ping?sitemap=".$sitemapUrl;
            // $returnCode = myCurl($url);
            // echo "<p>Google Sitemaps has been pinged ( return code : $returnCode ).</p>";
        }
        catch(Throwable $e)
        {
            Log::error($e);
        }
    }
}
