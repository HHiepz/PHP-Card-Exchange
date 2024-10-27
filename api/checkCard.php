<?php
require('../core/database.php');
require('../core/function.php');
require('../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra rỗng
    if (isEmptyOrNull($_POST['telco']) || isEmptyOrNull($_POST['code']) || isEmptyOrNull($_POST['serial']) || isEmptyOrNull($_POST['amount']) || isEmptyOrNull($_POST['request_id']) || isEmptyOrNull($_POST['partner_id']) || isEmptyOrNull($_POST['sign'])) {
        jsonReturn("fail", "Thiếu dữ liệu");
    }

    $telco                = $purifier->purify($_POST['telco']);       // Nhà mạng
    $code                 = $purifier->purify($_POST['code']);        // Mã thẻ
    $seri                 = $purifier->purify($_POST['serial']);      // Seri thẻ
    $amount               = $purifier->purify($_POST['amount']);      // Mệnh giá thẻ
    $request_id_partner   = $purifier->purify($_POST['request_id']);  // Mã giao dịch của đối tác
    $partner_id           = $purifier->purify($_POST['partner_id']);  // Mã đối tác
    $sign                 = $purifier->purify($_POST['sign']);        // Chữ ký bảo mật của khách hàng

    $partner_key  = pdo_query_value("SELECT `partner_key` FROM `partner` WHERE `partner_id` = ? AND `partner_type` = 'Charging'", [$partner_id]);

    // Kiểm tra chữ ký
    if ($sign !== md5($partner_key . $code . $seri)) {
        jsonReturn("fail", 'Sai chữ ký');
    }

    // Kiểm tra partner có được kích hoạt không
    $partner = pdo_query_one("SELECT * FROM `partner` WHERE `partner_id` = ? AND `partner_status` = 'active'", [$partner_id]);
    if (empty($partner)) {
        jsonReturn("fail", 'Đối tác chưa được kích hoạt, vui lòng liên hệ ADMIN');
    }

    // Kiểm tra trạng thá có bảo trì không
    $checkStatusExchangeCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_exchange_card'");
    $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
    if ($checkStatusExchangeCard != 1 || $checkStatusServer != 1) {
        jsonReturn("fail", 'Hệ thống đang bảo trì, vui lòng quay lại sau');
    }

    // Kiểm tra thẻ có trong hệ thống không
    $sqlCheck = "SELECT * FROM `card-data` 
        WHERE `card-data_telco` = ?
        AND `card-data_code` = ? 
        AND `card-data_seri` = ? 
        AND `card-data_amount` = ?
        AND `card-data_partner_request_id` = ?";
    $checkCard = pdo_query_one($sqlCheck, [$telco, $code, $seri, $amount, $request_id_partner]);
    if (empty($checkCard)) {
        jsonReturn("fail", 'Thẻ không tồn tại');
    }

    // Trả ra thông tin thẻ
    $result = [
        'trans_id'       => $checkCard['card-data_id'],
        'request_id'     => $checkCard['card-data_partner_request_id'],
        'status'         => $checkCard['card-data_status'],
        'message'        => $checkCard['card-data_api_message'],
        'telco'          => $checkCard['card-data_telco'],
        'code'           => $checkCard['card-data_code'],
        'serial'         => $checkCard['card-data_seri'],
        'declared_value' => $checkCard['card-data_amount'],                   // Mệnh giá khai báo
        'value'          => $checkCard['card-data_amount_real'],              // Mệnh giá thực tế
        'amount'         => $checkCard['card-data_amount_recieve'],           // Số tiền nhận được
    ];

    jsonReturn("success", "Thành công", $result);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Kiểm tra rỗng
    if (isEmptyOrNull($_GET['telco']) || isEmptyOrNull($_GET['code']) || isEmptyOrNull($_GET['serial']) || isEmptyOrNull($_GET['amount']) || isEmptyOrNull($_GET['request_id']) || isEmptyOrNull($_GET['partner_id']) || isEmptyOrNull($_GET['sign'])) {
        jsonReturn("fail", "Thiếu dữ liệu");
    }

    $telco                = $purifier->purify($_GET['telco']);       // Nhà mạng
    $code                 = $purifier->purify($_GET['code']);        // Mã thẻ
    $seri                 = $purifier->purify($_GET['serial']);      // Seri thẻ
    $amount               = $purifier->purify($_GET['amount']);      // Mệnh giá thẻ
    $request_id_partner   = $purifier->purify($_GET['request_id']);  // Mã giao dịch của đối tác
    $partner_id           = $purifier->purify($_GET['partner_id']);  // Mã đối tác
    $sign                 = $purifier->purify($_GET['sign']);        // Chữ ký bảo mật của khách hàng

    $partner_key  = pdo_query_value("SELECT `partner_key` FROM `partner` WHERE `partner_id` = ? AND `partner_type` = 'Charging'", [$partner_id]);

    // Kiểm tra chữ ký
    if ($sign !== md5($partner_key . $code . $seri)) {
        jsonReturn("fail", 'Sai chữ ký');
    }

    // Kiểm tra partner có được kích hoạt không
    $partner = pdo_query_one("SELECT * FROM `partner` WHERE `partner_id` = ? AND `partner_status` = 'active'", [$partner_id]);
    if (empty($partner)) {
        jsonReturn("fail", 'Đối tác chưa được kích hoạt, vui lòng liên hệ ADMIN');
    }

    // Kiểm tra trạng thá có bảo trì không
    $checkStatusExchangeCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_exchange_card'");
    $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
    if ($checkStatusExchangeCard != 1 || $checkStatusServer != 1) {
        jsonReturn("fail", 'Hệ thống đang bảo trì, vui lòng quay lại sau');
    }

    // Kiểm tra thẻ có trong hệ thống không
    $sqlCheck = "SELECT * FROM `card-data` 
            WHERE `card-data_telco` = ?
            AND `card-data_code` = ? 
            AND `card-data_seri` = ? 
            AND `card-data_amount` = ?
            AND `card-data_partner_request_id` = ?";
    $checkCard = pdo_query_one($sqlCheck, [$telco, $code, $seri, $amount, $request_id_partner]);
    if (empty($checkCard)) {
        jsonReturn("fail", 'Thẻ không tồn tại');
    }

    // Trả ra thông tin thẻ
    $result = [
        'trans_id'       => $checkCard['card-data_id'],
        'request_id'     => $checkCard['card-data_partner_request_id'],
        'status'         => $checkCard['card-data_status'],
        'message'        => $checkCard['card-data_api_message'],
        'telco'          => $checkCard['card-data_telco'],
        'code'           => $checkCard['card-data_code'],
        'serial'         => $checkCard['card-data_seri'],
        'declared_value' => $checkCard['card-data_amount'],                   // Mệnh giá khai báo
        'value'          => $checkCard['card-data_amount_real'],              // Mệnh giá thực tế
        'amount'         => $checkCard['card-data_amount_recieve'],           // Số tiền nhận được
    ];

    jsonReturn("success", "Thành công", $result);
}

if($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonReturn("fail", "Không tìm thấy phương thức");
}