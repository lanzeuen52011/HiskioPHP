<?php
// $startTime = microtime(true);
        $toDayDate =now()->toDateString();
        $path = 'sitemap/';
        $publicPath = public_path($path);
        $fileName = 'Sitemap';
        $ext = '.xml';
        $filePath = $publicPath . $fileName . $ext;
        
    // A.舊檔案備份與改名區
        // 先將舊檔案改名，才不會使舊檔案被刪掉
        if(file_exists($filePath)){
            chmod($filePath, 0777); // 將檔案狀態改成可修改模式
            rename($filePath, $publicPath.$toDayDate . '.xml');
        }

    // B.過舊檔案刪除區
        // 將兩天前的檔案刪除，假設今天7/17，那就會刪除7/15的檔案
        $files = scandir($publicPath);
        $files = array_diff($files, ['.', '..']);
        foreach ($files as $file) {
            $now = now()->subDays(2); //兩天前的時間
            $fileTime = strtotime(pathinfo($file, PATHINFO_FILENAME));
            if($fileTime<=$now->timestamp){ 
                echo '刪除檔案：'.$file . "\r\n";
                // \File::delete($publicPath.$file);
            }
        }
        
        // \File::delete(public_path('latest_set_companies_sitemap.xml'));// 測試用
        // create new sitemap object
        // $sitemap = \App::make("sitemap");
        

        // dd(now()->subDays(14)->startOfDay());

        // Examples (add items to the sitemap (url, date, priority, freq))
        

    // C.端口處理區
        // 未開放區
            // 篩選器filter
                // $sitemap->add(route('filter.index'), now());
        // 沒做的端口
            // 1.站內圖片子網域
            // 2.name('ogimageCreater')
            // 3.電商網站認證標章（這個沒做的原因是因為連不到，不懂在ImageController@certifiedBadge中的$request->t）
            // 4.Authentication Routes...
            // 5.Registration Routes...
            // 6.Password Reset Routes...
            // 7.['prefix' => 'oauth'
            // 8.驗證相關
            // 9.我的公司列表
            // 10.公司認證用
            // 11.公司各種內頁: 防私人爬蟲
            // 12.Landing page
            // 13.有串 ECPay & Paypal(英文版)，目前英文版銷售已下架，所以 Paypal 已沒有在使用
            // 14.上傳圖片&截圖
            // 15.FC Company Owner 後台管理相關
            // 16.FC Admin 後台管理相關
            // 17.for process data...
            // 18.前台管理按鈕
            // 19.公司內頁：此路由需要在最後

        // 棄用區
            // // 1.將後台"廣告管理"->"廣告關鍵字轉換設定"的關鍵字搜尋結果放置sitemap中 這個可能不用
                // dump("1.將後台\"廣告管理\"->\"廣告關鍵字轉換設定\"的關鍵字搜尋結果放置sitemap中-開始\r\n");
                //     // 相關檔案：app/Http/Controllers/Management/AdManageController.php 、 resources/views/backstage/ad_manage/index.blade.php
                //     // $adKeywordChannels = AdKeywordChannel::orderBy('id', 'desc')->select('keyword')->get(); // 取出設定好的關鍵字
                //     $adKeywordChannels = AdKeywordChannel::select('keyword')->get(); // 取出設定好的關鍵字

                //     foreach ($adKeywordChannels as $adKeywordChannel)
                //     {
                //         $sitemap->add(route('search_by_zh', $adKeywordChannel->keyword), now());
                //     }

            // // 2.形象官網與形象官網子網域，將各公司已啟用的形象官網，放到sitemap中 不用
                // dump("2.形象官網與形象官網子網域，將各公司已啟用的形象官網，放到sitemap中-開始\r\n");
                //     // $companies = PrimaryCompany::whereRaw('plan_id = 1')->get();
                //     $companies = PrimaryCompany::where('plan_id',1)->get();
                //     foreach ($companies as $company) {
                //         if($company->enabledTemplate){
                //             $hasTemplateCompany = $company->enabledTemplate->pivot->toArray();
                //             // 形象官網功能 Start
                //                 $sitemap->add(route('web_theme_page', $hasTemplateCompany['tax_number']), $hasTemplateCompany['updated_at']);
                //             // 形象官網子網域
                //                 $sitemap->add(route('company_web_theme', $hasTemplateCompany['tax_number']), $hasTemplateCompany['updated_at']);
                //         }   
                //     }
            // // 3.月設立/解散的公司/商行列表 不用
                // dump("3.月設立/解散的公司/商行列表\r\n");
                //     $startYear = 1960;
                //     $currentYear = date('Y');
                //     $types = ['company','business'];
                //     $actions = ['disband','set'];
                //     for ($year = $startYear; $year <= $currentYear; $year++) {
                //         for ($month = 1; $month <= 12; $month++) {
                //             foreach($types as $type){
                //                 foreach($actions as $action){
                //                     $sitemap->add(route('monthlyList', ['action' => $action, 'type' => $type, 'month' => $year.sprintf("%02d", $month)]), now());
                //                 }
                //             }
                //         }
                //     }

            // // 4.形象官網範例頁 不用
                // dump("4.形象官網範例頁表\r\n");
                //     $templates = Template::where('is_published',1)->select('name','updated_at')->get();
                //     foreach($templates as $template){
                //         $templateTime = $template->updated_at;
                //         if($templateTime!=true){
                //             $templateTime = now();
                //         }
                //         $sitemap->add(route('web_theme_example', $template->name), $templateTime);
                //     }

        // 測試區域
        
        
        // $this->testing(0,1500000); // 測試用程式碼要放在testProgram
        // dd($path.$siteMapFolderCompanyPage.$siteMapFileName);
        $startTime = microtime(true);
        // $sitemap = \App::make("sitemap");
        $siteMapFolderCompanyPage = 'companyPage/';
        $siteMapFileName = 'companyPage_'.$fileName;
        $batchSize = 100;
        // $all = PrimaryCompany::count();
        // PrimaryCompany::select('tax_number', 'name')
        //                 ->withCount('sameNames') // @必要，假設五間公司相同名字，不帶這個會變成只給出一間的資訊，這裡會抓出tax_number來讓各間公司作出區分，並給出相對應的route
        //                 ->chunk($batchSize,function($companies)use($sitemap,$all,$batchSize){
        //                     foreach ($companies as $company) {
        //                         static $i = 0;
        //                         $i++;
        //                         // 處理每個資料
        //                         $sitemap->add(route('detail_by_zh', $company->getRouteParams()), $company->updated_at);
        //                     }
        //                     dump(number_format($i*$batchSize  / $all, 2),'%');
        //                 });
        //                 // @chunk 100筆，不要設定太高，以限制效能，先不要用cursor怕SQL會崩潰
        // // 使用foreach迴圈處理每個資料項
        
        // $sitemap->store('xml', $path.$siteMapFolderCompanyPage.$siteMapFileName);
        dump("檔案已儲存到$path $siteMapFolderCompanyPage $siteMapFileName");
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        dump("總花費秒數：",$executionTime);

        // dd('end');

            
        // 完成區
            // 絕對端口
            dump("絕對端口\r\n");
            $startTime = microtime(true);
            // $sitemap = \App::make("sitemap");
            $siteMapFolderNormalRoute = 'normalRoute/';
            $siteMapFileName = 'normalRoute_'.$fileName;
            //     /* 1.首頁 */ $sitemap->add(route('index_by_zh'), now()); 
            //     /* 2.搜尋 */ $sitemap->add(route('searchPage_by_zh'), now()); 
            //     /* 3.近期變更登記資料的公司列表 */ $sitemap->add(route('recent_change_by_zh'), now()); 
            //     /* 4.資本額排行 */ $sitemap->add(route('rank_by_zh'), now()); 
            //     /* 5.公司資本額區間統計 */ $sitemap->add(route('capital_by_zh'), now()); 
            //     /* 6.收藏公司列表 */ $sitemap->add(route('collection_by_zh'), now()); 
            //     /* 7.每月設立&解散公司走勢 */ $sitemap->add(route('monthly'), now()); 
            //     /* 8.FindCompany 的聯絡我們 */ $sitemap->add(route('contact_by_zh'), now()); 
            //     /* 8.商標查詢子網域 */ $sitemap->add(route('trademark_index_by_zh'), now()); 
            // $sitemap->store('xml', $path.$siteMapFolderNormalRoute.$siteMapFileName);
            dump("檔案已儲存到$path $siteMapFolderNormalRoute $siteMapFileName");
            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;
            dump("總花費秒數：",$executionTime);


            // 相對端口
            dump("@相對端口\r\n");
                // 1.將各家公司的網頁端口放到sitemap中 狀態改成全部並分裝，不要只有核准登記與設立，包含公司頁面跟聯絡（必須要好）
                dump("1.將各家公司的網頁端口放到sitemap中-開始\r\n");
                $startTime = microtime(true);
                // $sitemap = \App::make("sitemap");
                $siteMapFolderCompanyPage = 'companyPage/';
                $siteMapFileName = 'companyPage_'.$fileName;
                $batchSize = 100;
                // $all = PrimaryCompany::count();
                // PrimaryCompany::select('tax_number', 'name')
                //                 ->withCount('sameNames') // @必要，假設五間公司相同名字，不帶這個會變成只給出一間的資訊，這裡會抓出tax_number來讓各間公司作出區分，並給出相對應的route
                //                 ->chunk($batchSize,function($companies)use($sitemap,$all,$batchSize){
                //                     foreach ($companies as $company) {
                //                         static $i = 0;
                //                         $i++;
                //                         // 處理每個資料
                //                         $sitemap->add(route('detail_by_zh', $company->getRouteParams()), $company->updated_at);
                //                     }
                //                     dump(number_format($i*$batchSize  / $all, 2),'%');
                //                 });
                //                 // @chunk 100筆，不要設定太高，以限制效能，先不要用cursor怕SQL會崩潰
                // // 使用foreach迴圈處理每個資料項
                
                // $sitemap->store('xml', $path.$siteMapFolderCompanyPage.$siteMapFileName);
                dump("檔案已儲存到$path $siteMapFolderCompanyPage $siteMapFileName");
                $endTime = microtime(true);
                $executionTime = $endTime - $startTime;
                dump("總花費秒數：",$executionTime);

                // 2.各商標放入sitemap
                dump("2.各商標放入sitemap-開始\r\n");
                $startTime = microtime(true);
                // $sitemap = \App::make("sitemap");
                $siteMapFolderMark = 'mark/';
                $siteMapFileName = 'mark_'.$fileName;
                // $all = Mark::whereRaw('LENGTH(tax_number) = 8')->select('exam_no','appl_no','updated_at')->count();
                // Mark::whereRaw('LENGTH(tax_number) = 8')->select('exam_no','appl_no','updated_at')->chunk($batchSize, function ($marks)use($all,$batchSize,$sitemap){
                //     static $i=0;
                //     $i++;
                //     foreach ($marks as $mark)
                //     {
                //     $sitemap->add(route('mark_detail', $mark->exam_no."_".$mark->appl_no), $mark->updated_at);
                //     }
                //     dump(number_format(($i*$batchSize)/$all,2));
                // });
                // $sitemap->store('xml', $path.$siteMapFolderMark.$siteMapFileName);
                dump("檔案已儲存到$path $siteMapFolderMark $siteMapFileName");
                $endTime = microtime(true);
                $executionTime = $endTime - $startTime;
                dump("總花費秒數：",$executionTime);
                


                
                // 3.提供『專業服務』的公司
                dump("3.提供『專業服務』的公司-開始\r\n");
                $startTime = microtime(true);
                // $sitemap = \App::make("sitemap");
                $siteMapFolderClassified = 'classifiedCategort/';
                $siteMapFileName = 'classifiedCategort_'.$fileName;
                //     $classifiedCategory = ClassifiedCategory::select('name','updated_at')->chunk($batchSize,function($classifiedCategory)use($sitemap){
                        
                //         foreach($classifiedCategory as $classified){
                //             $sitemap->add(route('companyListByClassifiedCategory', $classified->name), $classified->updated_at);
                //         }
                //     });
                // $sitemap->store('xml', $path.$siteMapFolderClassified.$siteMapFileName);
                dump("檔案已儲存到$path $siteMapFolderClassified $siteMapFileName");
                $endTime = microtime(true);
                $executionTime = $endTime - $startTime;
                dump("總花費秒數：",$executionTime);



            dd("結束");