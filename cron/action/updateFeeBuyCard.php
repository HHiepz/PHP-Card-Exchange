<?php
// NOTE DEV: Cập nhật phí mua thẻ tự động
require('../../core/function.php');
require('../../core/database.php');
require('../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_buyCard'");
$partner_id  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_buyCard'");
$partner_key = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_buyCard'");

$dataCurl = curlGet("https://$partner_server_name/api/cardws/products?partner_id=$partner_id");
$dataCurl = json_decode($dataCurl, true);

// Kiểm tra dữ liệu trả về
if (empty($dataCurl)) {
    jsonReturn(false, 'Không thể lấy dữ liệu từ server đối tác');
}

// Reset phí về NULL
$reset_telco = pdo_query("SELECT * FROM `card-rare`");
foreach ($reset_telco as $telco) {
    $telco_code = $telco['card-rare_code'];
    $sql_card = pdo_execute("UPDATE `card-rare` SET 
    `10000` = NULL, 
    `20000` = NULL, 
    `30000` = NULL, 
    `50000` = NULL, 
    `100000` = NULL, 
    `200000` = NULL, 
    `300000` = NULL, 
    `405000` = NULL,
    `500000` = NULL, 
    `650000` = NULL, 
    `810000` = NULL,
    `1000000` = NULL,
    `1500000` = NULL,
    `2000000` = NULL,
    `3000000` = NULL,
    `5000000` = NULL
    WHERE `card-rare_code` = ?", [$telco_code]);
}

// Cập nhật phí mới
foreach ($dataCurl as $data) {
    // Lọc dữ liệu đầu vào
    $name  = $purifier->purify($data['name']);
    $telco = strtoupper($purifier->purify($data['service_code']));

    // Lấy phí giảm
    $buyCardFee = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_" . strtolower($telco) . "'");

    foreach ($data['cardvalue'] as $value) {
        // Cập nhật phí
        $sql_card = pdo_execute("UPDATE `card-rare` SET `" . $value['value'] . "` = ? WHERE `card-rare_code` = ?", [$buyCardFee, $telco]);
    }
}

// Cập nhật card-rare_status = 0 nếu tất cả mệnh giá = NULL
$check_status = pdo_query("SELECT * FROM `card-rare`");
foreach ($check_status as $card) {
    $card_10000 = $card['10000'];
    $card_20000 = $card['20000'];
    $card_30000 = $card['30000'];
    $card_50000 = $card['50000'];
    $card_100000 = $card['100000'];
    $card_200000 = $card['200000'];
    $card_300000 = $card['300000'];
    $card_405000 = $card['405000'];
    $card_500000 = $card['500000'];
    $card_650000 = $card['650000'];
    $card_810000 = $card['810000'];
    $card_1000000 = $card['1000000'];
    $card_1500000 = $card['1500000'];
    $card_2000000 = $card['2000000'];
    $card_3000000 = $card['3000000'];
    $card_5000000 = $card['5000000'];

    if ($card_10000 == NULL && $card_20000 == NULL && $card_30000 == NULL && $card_50000 == NULL && $card_100000 == NULL && $card_200000 == NULL && $card_300000 == NULL && $card_405000 == NULL && $card_500000 == NULL && $card_650000 == NULL && $card_810000 == NULL && $card_1000000 == NULL && $card_1500000 == NULL && $card_2000000 == NULL && $card_3000000 == NULL && $card_5000000 == NULL) {
        $sql_card = pdo_execute("UPDATE `card-rare` SET `card-rare_status` = 0 WHERE `card-rare_code` = ?", [$card['card-rare_code']]);
    } else {
        $sql_card = pdo_execute("UPDATE `card-rare` SET `card-rare_status` = 1 WHERE `card-rare_code` = ?", [$card['card-rare_code']]);
    }
}

jsonReturn(true, 'Cập nhật phí mua thẻ thành công');
