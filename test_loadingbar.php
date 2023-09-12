<?php
// composer require symfony/console
require 'vendor/autoload.php';

use Symfony\Component\Console\Helper\ProgressBar;

$totalSteps = 100; // 總進度步數

// 創建 ProgressBar 實例
$progressBar = new ProgressBar($totalSteps);

for ($i = 1; $i <= $totalSteps; $i++) {
    // 計算當前進度百分比
    $progress = ($i / $totalSteps) * 100;
    
    // 更新進度條
    $progressBar->display($i);
    
    // 可以調整這個時間來控制進度條更新速度
    usleep(100000);
}

// 結束進度條
$progressBar->finish();