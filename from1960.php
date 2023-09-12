<?php
function printMonthsFrom1960() {
    $startYear = 1960;
    $currentYear = date('Y');
    $i = 0;
    for ($year = $startYear; $year <= $currentYear; $year++) {
        for ($month = 1; $month <= 12; $month++) {
            $i++;
            $formattedMonth = sprintf("%02d", $month);            
            echo "$i$year$formattedMonth\r";
        }
    }
}
printMonthsFrom1960();
?>