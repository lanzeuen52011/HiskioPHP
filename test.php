<?php
$minBatchSize = 100; // 最小批次處理數量
$maxBatchSize = 200000; // 最大批次處理數量
// $maxBatchSize = PrimaryCompany::count(); //假設最大批次處理數量為資料總數，但通常不建議，建議從200,000開始
$batchSize = ($minBatchSize + $maxBatchSize) / 2; // 中間批次處理數量
$i  = 0; // 處理次數
$satisfyTime =  0.0001; // 滿意時間

function testEfficiency($batchSize,$satisfyTime) {
    // 記錄處理開始時間
    $startTime = microtime(true);
    // 這裡是實際的處理程式碼，例如處理資料庫查詢等任務
    // 處理的程式碼放這裡，包刮調物件也能在這裡測試
    PrimaryCompany::chunk($batchSize, function ($companies) {
        foreach ($companies as $company) {
            // 在這裡處理每一個 $company 物件
            // 例如：echo $company->name;
            // dump($company);
        }
    });
    // 記錄處理結束時間
    $endTime = microtime(true);
    // 計算處理時間
    $executionTime = $endTime - $startTime;
    // 假設這裡是測試效率的判斷標準，你可以根據實際需求來定義
    if ($executionTime < $satisfyTime) {
        echo '滿意'.$executionTime."batchSize:".$batchSize."\r\n";
        return '滿意'; // 假設處理時間小於1秒，表示效率滿意
    } else {
        echo '不滿意'.$executionTime."batchSize:".$batchSize."\r\n";
        return '不滿意'; // 假設處理時間大於等於1秒，表示效率不滿意
    }
    }

while ($maxBatchSize - $minBatchSize > 1) {
    $i++;
    echo "這是第".$i."次"."\r\n";
    // 測試使用 $batchSize 處理的效率
    // 如果效率滿意，將 $batchSize 視為新的 $minBatchSize
    // 否則，將 $batchSize 視為新的 $maxBatchSize
    // 假設測試效率的方法為 testEfficiency($batchSize)
    $efficiency = $this->testEfficiency($batchSize,$satisfyTime);
    if ($efficiency === '滿意') {
        $minBatchSize = $batchSize;
        break;
    } else {
        $maxBatchSize = $batchSize;
    }
    $batchSize = ($minBatchSize + $maxBatchSize) / 2;
}
// 最終的 $batchSize 即為最佳處理筆數
dd('測試結束');