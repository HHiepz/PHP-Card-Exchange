<?php
// NOTE DEV: Cập nhật phí đổi thẻ tự động
require('../../core/function.php');
require('../../core/database.php');
require('../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'");
$partner_id  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id'");
$partner_key = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key'");

$dataCurl = curlGet("https://$partner_server_name/chargingws/v2/getfee?partner_id=$partner_id&partner_key=$partner_key");
$dataCurl = json_decode($dataCurl, true);

// Kiểm tra dữ liệu trả về
if (empty($dataCurl)) {
    jsonReturn(false, 'Không thể lấy dữ liệu từ server đối tác');
}

// Reset phí về 0
$reset_telco = pdo_query("SELECT * FROM `telco-rare`");
foreach ($reset_telco as $telco) {
    $telco_code = $telco['telco-rare_code'];
    $sql_member = pdo_execute("UPDATE `telco-rare` SET `member_10000` = 0, `member_20000` = 0, `member_30000` = 0, `member_50000` = 0, `member_100000` = 0, `member_200000` = 0, `member_300000` = 0, `member_500000` = 0, `member_1000000` = 0, `member_2000000` = 0, `member_5000000` = 0, `member_10000000` = 0 WHERE `telco-rare_code` = ?", [$telco_code]);
    $sql_vip    = pdo_execute("UPDATE `telco-rare` SET `vip_10000` = 0, `vip_20000` = 0, `vip_30000` = 0, `vip_50000` = 0, `vip_100000` = 0, `vip_200000` = 0, `vip_300000` = 0, `vip_500000` = 0, `vip_1000000` = 0, `vip_2000000` = 0, `vip_5000000` = 0, `vip_10000000` = 0 WHERE `telco-rare_code` = ?", [$telco_code]);
    $sql_agency = pdo_execute("UPDATE `telco-rare` SET `agency_10000` = 0, `agency_20000` = 0, `agency_30000` = 0, `agency_50000` = 0, `agency_100000` = 0, `agency_200000` = 0, `agency_300000` = 0, `agency_500000` = 0, `agency_1000000` = 0, `agency_2000000` = 0, `agency_5000000` = 0, `agency_10000000` = 0 WHERE `telco-rare_code` = ?", [$telco_code]);
}


// Cập nhật phí mới
foreach ($dataCurl as $data) {
    // Lọc dữ liệu đầu vào
    $value = $purifier->purify($data['value']);              // Mệnh giá thẻ
    $fee   = floatval($purifier->purify($data['fees']));     // Phí web mẹ
    $telco = strtoupper($purifier->purify($data['telco']));  // Nhà mạng

    // Kiểm tra nhà mạng không hoạt động
    if (!telco_status($telco)) {
        continue;
    }

    // Tính phí các rank
    $rare_member = $fee + floatval(pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_member'"));
    $rare_vip    = $fee + floatval(pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_vip'"));
    $rare_agency = $fee + floatval(pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_agency'"));

    // Cập nhật phí
    $sql_member = pdo_execute("UPDATE `telco-rare` SET `member_" . $value . "` = ? WHERE `telco-rare_code` = ?", [$rare_member, $telco]);
    $sql_vip    = pdo_execute("UPDATE `telco-rare` SET `vip_" . $value . "` = ? WHERE `telco-rare_code` = ?", [$rare_vip, $telco]);
    $sql_agency = pdo_execute("UPDATE `telco-rare` SET `agency_" . $value . "` = ? WHERE `telco-rare_code` = ?", [$rare_agency, $telco]);
}


// Cập nhật telco-rare_status = 0 nếu tất cả mệnh giá = 0
$check_status = pdo_query("SELECT * FROM `telco-rare`");
foreach ($check_status as $telco) {
    $telco_10000 = $telco['member_10000'] + $telco['vip_10000'] + $telco['agency_10000'];
    $telco_20000 = $telco['member_20000'] + $telco['vip_20000'] + $telco['agency_20000'];
    $telco_30000 = $telco['member_30000'] + $telco['vip_30000'] + $telco['agency_30000'];
    $telco_50000 = $telco['member_50000'] + $telco['vip_50000'] + $telco['agency_50000'];
    $telco_100000 = $telco['member_100000'] + $telco['vip_100000'] + $telco['agency_100000'];
    $telco_200000 = $telco['member_200000'] + $telco['vip_200000'] + $telco['agency_200000'];
    $telco_300000 = $telco['member_300000'] + $telco['vip_300000'] + $telco['agency_300000'];
    $telco_500000 = $telco['member_500000'] + $telco['vip_500000'] + $telco['agency_500000'];
    $telco_1000000 = $telco['member_1000000'] + $telco['vip_1000000'] + $telco['agency_1000000'];
    $telco_2000000 = $telco['member_2000000'] + $telco['vip_2000000'] + $telco['agency_2000000'];
    $telco_5000000 = $telco['member_5000000'] + $telco['vip_5000000'] + $telco['agency_5000000'];
    $telco_10000000 = $telco['member_10000000'] + $telco['vip_10000000'] + $telco['agency_10000000'];

    if ($telco_10000 == 0 && $telco_20000 == 0 && $telco_30000 == 0 && $telco_50000 == 0 && $telco_100000 == 0 && $telco_200000 == 0 && $telco_300000 == 0 && $telco_500000 == 0 && $telco_1000000 == 0 && $telco_2000000 == 0 && $telco_5000000 == 0 && $telco_10000000 == 0) {
        $sql_status = pdo_execute("UPDATE `telco-rare` SET `telco-rare_status` = 0 WHERE `telco-rare_code` = ?", [$telco['telco-rare_code']]);
    } else {
        $sql_status = pdo_execute("UPDATE `telco-rare` SET `telco-rare_status` = 1 WHERE `telco-rare_code` = ?", [$telco['telco-rare_code']]);
    }
}


jsonReturn(true, 'Cập nhật phí đổi thẻ thành công');
