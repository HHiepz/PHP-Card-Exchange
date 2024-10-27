<?php
// NOTE DEV: Cập nhật phí nạp thẻ tự động
require('../../core/function.php');
require('../../core/database.php');
require('../../core/apiSend.php');
require('../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_topup'");
$partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_topup'");
$partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_topup'");

$data = checkProducts($partner_id, $partner_key, $partner_server_name);

// Debug webhook chỉ cho lỗi nghiêm trọng
$webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_topup'");
$role    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'role_topup'");

if (is_array($data) && isset($data['status']) && $data['status'] === 'success') {
    foreach ($data['data'] as $service) {
        $serviceCode = $purifier->purify($service['service_code']);

        foreach ($service['items'] as $item) {
            // Lấy dữ liệu
            $partner_value    = $purifier->purify($item['value']);
            $partner_discount = $purifier->purify($item['discount']);
            $topup_id = getTopupIdFromCode($serviceCode);

            // Kiểm tra topup_id có tồn tại
            if (empty($topup_id)) {
                continue;
            }

            // Lấy phí lãi
            $fee = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'topup_rare_" . $serviceCode . "'");
            $discount = $partner_discount - (empty($fee) ? 0 : $fee);

            // Cập nhật phí
            pdo_execute("UPDATE `topup-rare` SET `topup-rare_discount` = ? WHERE `topup_id` = ? AND `topup-rare_value` = ?", [$discount, $topup_id, $partner_value]);
        }
    }
} else {
    if (!empty($webhook)) {
        $message = createTopupErrorMessage($role, "Lỗi nghiêm trọng: Không thể cập nhật phí nạp thẻ. Lỗi: " . $data['status']);
        sendDiscord($webhook, $message);
    }
}
