<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\YourExcelImport;

class ExcelController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index()
    {
        return view('convert');
    }
    // public function convertExcel(Request $request)
    // {
    //     $file = $request->file('file');
    //     dd($file);
    //     // $data = Excel::toArray(new YourExcelImport, $file);

    //     $transformedData = collect($data[0])->map(function ($row) {
    //         $colorData = [
    //             'color1' => [
    //                 'name' => $row['color1Name'],
    //                 'hex' => $row['color1'],
    //                 'src' => $row['color1Src'],
    //             ],
    //             'color2' => [
    //                 'name' => $row['color2Name'],
    //                 'hex' => $row['color2'],
    //                 'src' => $row['color2Src'],
    //             ],
    //             'color3' => [
    //                 'name' => $row['color3Name'],
    //                 'hex' => $row['color3'],
    //                 'src' => $row['color3Src'],
    //             ],
    //         ];

    //         $row['color'] = $colorData;
    //         unset($row['color1'], $row['color1Src'], $row['color1Name'], $row['color2'], $row['color2Src'], $row['color2Name'], $row['color3'], $row['color3Src'], $row['color3Name']);

    //         return $row;
    //     });

    //     // 保存轉換後的數據為JSON文件
    //     file_put_contents('output.json', $transformedData->toJson(JSON_PRETTY_PRINT));

    //     return response()->json($transformedData);
    // }
    // public function convertExcel(Request $request)
    // {
    //     // 驗證上傳的文件
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls,csv',
    //     ]);

    //     // 處理文件上傳和數據導入
    //     $this->excel->import(new YourExcelImport, $request->file('file'));

    //     dd('test');

    //     // 返回一個響應，例如重定向或顯示一個消息
    //     return back()->with('success', 'Excel 文件已成功處理！');
    // }
    public function convertExcel(Request $request)
    {
        // 驗證上傳的文件
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // 處理文件上傳和數據導入
        $data = Excel::toArray(new YourExcelImport, $request->file('file'));



        $transformedData = collect($data[0])->map(function ($row) {

            $row = array_filter($row, function($value) {
                return $value !== null;
            });

            // 去除亂碼與空字串
            foreach($row as $keys=>$singleRow){
                $row[$keys] = str_replace("\u{A0}", "", $singleRow);
                $row[$keys] = str_replace('"', '', $singleRow);
                if( $row[$keys]  == "null")
                {
                    $row[$keys] = null;
                }
            }

            // Base區塊(各車基本資料) -- start
            $baseblock = [
                'brand',
                'type',
                'specifictype',
                'model',
                'specificmodel',
                'price',
                'battery',
                'batterypurchase',
                'testride',
                'testrideornot',
                'testrideimg',
                'officialurl'
            ];
            foreach($baseblock as $base){
                if(isset($row[$base])){
                    $row['base'][$base] = $row[$base];
                    unset($row[$base]);
                }
            }

            if(isset($row["color1"])){
                $colorData = [];
                for($i = 1; $i <=6;$i++){
                    if(isset($row["color$i"]))
                    {
                        $colorData["color$i"] = [
                            'name' => $row["color{$i}name"],
                            'hex' => $row["color$i"],
                            'src' => $row["color{$i}src"],
                        ];
                    }
                    unset($row["color$i"], $row["color{$i}src"], $row["color{$i}name"]);
                }
                $row['base']['colors'] = $colorData;
                unset($row["color"]);
            }

            if(isset($row["feature1"])){
                $featureData = [];
                if(isset($row['base']))
                {
                    for($i = 1; $i <=4;$i++){
                        if(isset($row["feature$i"]))
                        {
                            $featureData["$i"] = $row["feature$i"];
                        }
                        unset($row["feature$i"]);
                    }

                    $row['base']['features'] = $featureData;
                }
                else
                {
                    for($i = 1; $i <=4;$i++){
                        if(isset($row["feature$i"]) && $row["feature$i"] != null)
                        {
                            array_push($featureData,$row["feature$i"]);
                        }
                        unset($row["feature$i"]);
                    }

                    $row['features'] = $featureData;
                }
            }



            if(isset($row["athome"]))
            {
                $chargeMethod = [];
                $chargeMethod['在家充電'] = $row['athome'];
                $chargeMethod['交換電池'] = $row['changebattery'];
                $chargeMethod['快充站'] = $row['fastcharge'];
                unset($row['athome'],$row['changebattery'],$row['fastcharge']);
                $row['base']['chargemethod'] =$chargeMethod;
            }

            if(isset($row['src'])){
                if($row['src'] == 'color1'){
                    $row['src']='colors';
                    $row['zh_tw']='顏色';
                }

                for($i = 1; $i <=6;$i++){
                    if($row['src'] == "color$i" || $row['src'] == "color{$i}src" || $row['src'] == "color{$i}name")
                    return null;
                }

                if($row['src'] == 'feature1'){
                    $row['src']='features';
                    $row['zh_tw']='功能特性';
                }
    
                if($row['src'] == 'feature2' || $row['src'] == 'feature3' || $row['src'] == 'feature4'){
                    return null;
                }

                if($row['src'] == 'athome'){
                    $row['src']='chargemethod';
                    $row['zh_tw']='充電方式';
                }
    
                if($row['src'] == 'changebattery' || $row['src'] == 'fastcharge'){
                    return null;
                }
            }

            // Base(各車基本資料)區塊 -- end
            // extension(升級配備)區塊 -- start

            $extensionblock = [
                'accessories',
                'storage',
                'boutique',
                'tech',
            ];
            foreach($extensionblock as $extension){
                if(isset($row[$extension])){
                    $row['extension'][$extension] = $row[$extension];
                    unset($row[$extension]);
                }
            }

            // extension(升級配備)區塊 -- end
            // userinterface (使用者介面)區塊 -- start

            $userinterfaceblock = [
                'dashboard',
                'doublekickstand',
                'kickstand',
                'footpedal',
                'fronthook',
                'functionbutton',
                'lock',
                'appsupport',
            ];
            foreach($userinterfaceblock as $userinterface){
                if(isset($row[$userinterface])){
                    $row['userinterface'][$userinterface] = $row[$userinterface];
                    unset($row[$userinterface]);
                }
            }

            // userinterface (使用者介面)區塊 -- end
            // lightsystem (燈光系統)區塊 -- start

            $lightsystemblock = [
                'headlight',
                'frontturnsignal',
                'taillight',
                'hazardlight',
                'ess_aebs',
            ];
            foreach($lightsystemblock as $lightsystem){
                if(isset($row[$lightsystem])){
                    $row['lightsystem'][$lightsystem] = $row[$lightsystem];
                    unset($row[$lightsystem]);
                }
            }

            // lightsystem (燈光系統)區塊 -- end
            // performance(性能表現)區塊 -- start

            $performanceblock = [
                'maximumpower',
                'maximumtorque',
                'climbingability',
                'rakeangle',
                'endurance',
            ];
            foreach($performanceblock as $performance){
                if(isset($row[$performance])){
                    $row['performance'][$performance] = $row[$performance];
                    unset($row[$performance]);
                }
            }

            // performance(性能表現)區塊 -- end
            // powersystem(動力系統)區塊 -- start

            $powersystemblock = [
                'backingup',
                'powermode',
                'motor',
                'motortype',
                'motorcontroller',
                'coolingsystem',
                'gearreductionunit',
                'transmissionmode',
            ];
            foreach($powersystemblock as $powersystem){
                if(isset($row[$powersystem])){
                    $row['powersystem'][$powersystem] = $row[$powersystem];
                    unset($row[$powersystem]);
                }
            }

            // powersystem(動力系統)區塊 -- end
            // structure(車體結構)區塊 -- start

            $structureblock = [
                'frame',
                'framename',
                'frontsuspension',
                'suspensionname',
                'backsuspension',
                'backshockabsorber',
                'seat',
                'seatpost',
                'handle',
                'shiftlevers',
                'brakelevers',
                'brake',
                'brakesystem',
                'brakesupportsystem',
                'brakehose',
                'calipertype',
                'headset',
                'steerer',
                'bottombracket',
                'crankset',
                'chain',
                'cassette',
                'wheelset',
                'hubs',
                'frontshifter',
                'backshifter',
                'disc',
                'tirespecific',
                'steelwire',
                'steelname',
                'tire',
                'suggesttirepressure',
                'sensor',
            ];
            foreach($structureblock as $structure){
                if(isset($row[$structure])){
                    $row['structure'][$structure] = $row[$structure];
                    unset($row[$structure]);
                }
            }

            // structure(車體結構)區塊 -- end
            // size(尺寸規格)區塊 -- start

            $sizeblock = [
                'fullsize',
                'wheelbase',
                'heightofseat',
                'weight',
                'storagespace',
                'framesize',
            ];
            foreach($sizeblock as $size){
                if(isset($row[$size])){
                    $row['size'][$size] = $row[$size];
                    unset($row[$size]);
                }
            }

            // size(尺寸規格)區塊 -- end
            // techfunction(尺寸規格)區塊 -- start

            $techfunctionblock = [
                'themelight',
                'senseturnsignal',
                'dashboarddimming',
                'changedashboardcolor',
                'safetyreminder',
                'soundtheme',
                'autolock',
                'lightoutdelay',
                'timingsilentmode',
                'compartmentsetting',
                'performancecontrol',
                'chargingsetting',
                'speedingreminder',
                'lowspeedreminder',
                'senseunlock',
                'breathinglight',
                'doublelock',
                'autofontlock',
                'rainmode',
                'hbdsong',
                'carradar',
                'kicktoon',
                'racestopwatch',
                'tcs_standard',
                'tcs_advance',
                'cruisecontrol',
                'lte',
                'walkmode',
                'lightoutafteroff',
                'applewalletkey',
                'findmy'
            ];
            foreach($techfunctionblock as $techfunction){
                if(isset($row[$techfunction])){
                    $row['techfunction'][$techfunction] = $row[$techfunction];
                    unset($row[$techfunction]);
                }
            }

            // techfunction(尺寸規格)區塊 -- end




            // $testRide = [];
            // $testRide['boolean'] = $row['testride'];
            // $testRide['zhtw'] = $row['testrideornot'];
            // $testRide['img'] = $row['testrideimg'];
            // unset($row['testride'],$row['testrideornot'],$row['testrideimg']);
            // $row['testride'] =$testRide;

            if(isset($row["tag1"])){
                $tagData = [];
                for($i = 1; $i <=999;$i++){
                    if(isset($row["tag$i"]))
                    {
                        array_push($tagData,$row["tag$i"]);
                        unset($row["tag$i"]);
                    }else{
                        break;
                    }
                }
                $row['tags'] = $tagData;
            }

            // dump($row);
            return $row;
        })->filter()->values();

        // 保存轉換後的數據為JSON文件
        file_put_contents('output.json', $transformedData->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return response()->json($transformedData);
    }
    // public function convertExcel(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls,csv',
    //     ]);

    //     $import = new YourExcelImport;
    //     Excel::import($import, $request->file('file'));

    //     $transformedData = $import->collection($rows);

    //     // 保存轉換後的數據為JSON文件
    //     file_put_contents('output.json', $transformedData->toJson(JSON_PRETTY_PRINT));

    //     return response()->json($transformedData);
    // }
    // public function convertExcel(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls,csv',
    //     ]);

    //     $import = new YourExcelImport;
    //     Excel::import($import, $request->file('file'));

    //     $transformedData = $import->data;

    //     // 保存轉換後的數據為JSON文件
    //     file_put_contents('output.json', $transformedData->toJson(JSON_PRETTY_PRINT));

    //     return response()->json($transformedData);
    // }
}