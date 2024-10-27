<?php
require('../../core/function.php');
require('../../core/database.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');
$twoMonthsAgo = strtotime('-1 months');
$twoMonthsAgoFormatted = date('Y-m-d H:i:s', $twoMonthsAgo);

$countDelete = 0;

$doithe = pdo_query("SELECT * FROM `card-data` WHERE `card-data_created_at` < ?", [$twoMonthsAgoFormatted]);
foreach ($doithe as $card) {
    pdo_execute("DELETE FROM `card-data` WHERE `card-data_id` = ?", [$card['card-data_id']]);
    $countDelete++;
}

$muathe = pdo_query("SELECT * FROM `buy-card-order` WHERE `created_at` < ?", [$twoMonthsAgoFormatted]);
foreach ($muathe as $card) {
    pdo_execute("DELETE FROM `buy-card-data`  WHERE `buy-card-order_code` = ?", [$card['buy-card-order_code']]);
    pdo_execute("DELETE FROM `buy-card-order` WHERE `buy-card-order_code` = ?", [$card['buy-card-order_code']]);
    $countDelete++;
}

$money = pdo_query("SELECT * FROM `money` WHERE `created_at` < ?", [$twoMonthsAgoFormatted]);
foreach ($money as $card) {
    pdo_execute("DELETE FROM `money` WHERE `money_id` = ?", [$card['money_id']]);
    $countDelete++;
}

$transfer = pdo_query("SELECT * FROM `transfer` WHERE `created_at` < ?", [$twoMonthsAgoFormatted]);
foreach ($transfer as $card) {
    pdo_execute("DELETE FROM `transfer` WHERE `transfer_id` = ?", [$card['transfer_id']]);
    $countDelete++;
}

$withdraw = pdo_query("SELECT * FROM `withdraw` WHERE `created_at` < ?", [$twoMonthsAgoFormatted]);
foreach ($withdraw as $card) {
    pdo_execute("DELETE FROM `withdraw` WHERE `wd_id` = ?", [$card['wd_id']]);
    $countDelete++;
}

jsonReturn(true, "Xóa log thành công, đã xóa $countDelete log trong 2 tháng qua");
