<?php
require('../core/database.php');
require('../core/function.php');
require('../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Kiểm tra rỗng
    if (empty($_GET['partner_key'])) {
        jsonReturn("fail", "Không được để trống thông tin");
    }

    $partner_key  = $purifier->purify($_GET['partner_key']);  // Mã đối tác

    // Kiểm tra partner có được kích hoạt không
    $partner_user_id = pdo_query_value("SELECT `user_id` FROM `partner` WHERE `partner_key` = ? AND `partner_status` = 'active'", [$partner_key]);
    if (empty($partner_user_id)) {
        jsonReturn("fail", 'Đối tác chưa được kích hoạt, vui lòng liên hệ ADMIN');
    }

    // Lấy rank user
    $getRankUser = pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_id` = ?", [$partner_user_id]);
    if (empty($getRankUser)) {
        jsonReturn("fail", 'Không tìm thấy thông tin người dùng');
    }

    // Lấy thông tin phí đổi thẻ
    $list_fee_exchange = pdo_query("SELECT * FROM `telco-rare` WHERE `telco-rare_status` != 0 ORDER BY `telco-rare_id` DESC");
    $result = [];
    foreach ($list_fee_exchange as $key => $telco) {
        $telco_code = $telco['telco-rare_code'];

        $result[$telco_code] = [
            [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 10000,
                "fees"     => floatval($telco[$getRankUser . '_10000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 20000,
                "fees"     => floatval($telco[$getRankUser . '_20000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 30000,
                "fees"     => floatval($telco[$getRankUser . '_30000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 50000,
                "fees"     => floatval($telco[$getRankUser . '_50000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 100000,
                "fees"     => floatval($telco[$getRankUser . '_100000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 200000,
                "fees"     => floatval($telco[$getRankUser . '_200000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 300000,
                "fees"     => floatval($telco[$getRankUser . '_300000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 500000,
                "fees"     => floatval($telco[$getRankUser . '_500000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 1000000,
                "fees"     => floatval($telco[$getRankUser . '_1000000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 2000000,
                "fees"     => floatval($telco[$getRankUser . '_2000000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 5000000,
                "fees"     => floatval($telco[$getRankUser . '_5000000']),
                "penalty"  => 50
            ], [
                "telco"    => $telco['telco-rare_code'],
                "value"    => 10000000,
                "fees"     => floatval($telco[$getRankUser . '_10000000']),
                "penalty"  => 50
            ]
        ];
    }

    jsonReturn("success", "Lấy thông tin thành công", $result);
}
