<?php
require('../../core/database.php');
require('../../core/function.php');
require('../../core/apiSend.php');

$partner_id            = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['partner_id_topup']);
$partner_key           = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['partner_key_topup']);
$partner_server_name   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['partner_server_name_topup']);
$wallet                = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['wallet_topup']);


// ==================== ĐƠN CHỜ KẾT QUẢ ====================
$topupOrder_pending = pdo_query("SELECT * FROM `topup-order` WHERE `topup-order_status` = 'pending' AND `topup-order_cron` = 1");
foreach ($topupOrder_pending as $topupOrder) {
    $order_code = $topupOrder['topup-order_order_code'];
    $request_id = $topupOrder['topup-order_request_id'];

    // Call API 
    $response = checkStatusTopup($partner_id, $partner_key, $partner_server_name, $order_code, $request_id);

    if (is_array($response) && !empty($response)) {
        if (isset($response['data']['status'])) {
            if ($response['data']['status'] == 'completed') {
                pdo_execute("UPDATE `topup-order` SET `topup-order_cron` = `topup-order_cron` + 1 WHERE `topup-order_id` = ?", [$topupOrder['topup-order_id']]);
                pdo_execute("UPDATE `topup-order` SET `topup-order_status` = ? WHERE `topup-order_id` = ?", [$response['data']['status'], $topupOrder['topup-order_id']]);

                // Gửi thông báo thành công
                $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['webhook_topup']);
                if (!empty($webhook)) {
                    $role    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['role_topup']);
                    $message = createTopupSuccessMessage($role, "Đơn nạp thành công. Mã giao dịch: **$request_id** - Mã đơn: **$order_code**");
                    sendDiscord($webhook, $message);
                }
            }

            if ($response['data']['status'] == 'canceled') {
                pdo_execute("UPDATE `topup-order` SET `topup-order_cron` = `topup-order_cron` + 1 WHERE `topup-order_id` = ?", [$topupOrder['topup-order_id']]);
                pdo_execute("UPDATE `topup-order` SET `topup-order_status` = ? WHERE `topup-order_id` = ?", [$response['data']['status'], $topupOrder['topup-order_id']]);

                // Gửi thông báo thành công
                $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['webhook_topup']);
                if (!empty($webhook)) {
                    $role    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['role_topup']);
                    $message = createTopupErrorMessage($role, "Đơn đã bị hủy và hoàn tiền. Mã giao dịch: **$request_id** - Mã đơn: **$order_code**");
                    sendDiscord($webhook, $message);
                }

                // Hoàn tiền 
                userCash('add', $topupOrder['topup-order_pay_amount'], $topupOrder['user_id'], "Hoàn tiền đơn nạp thẻ thất bại, mã giao dịch: " . $topupOrder['topup-order_request_id']);
            }
        }
    }
}

// ==================== ĐƠN CHƯA XỬ LÝ ====================
$topupOrder_new = pdo_query("SELECT * FROM `topup-order` WHERE `topup-order_status` = 'wait' AND `topup-order_cron` = 0");
foreach ($topupOrder_new as $topupOrder) {
    pdo_execute("UPDATE `topup-order` SET `topup-order_cron` = `topup-order_cron` + 1 WHERE `topup-order_id` = ?", [$topupOrder['topup-order_id']]); // Tăng 1 lần cron

    $service_code          = getTopupCodeFromId($topupOrder['topup_id']);
    $amount                = $topupOrder['topup-order_amount'];
    $quantity              = 1;
    $request_id            = $topupOrder['topup-order_request_id'];
    $account_info          = $topupOrder['topup-order_account'];

    // Call API 
    $response = orderTopup($partner_id, $partner_key, $partner_server_name, $service_code, $amount, $quantity, $request_id, $account_info);

    if (is_array($response) && !empty($response)) {
        if (isset($response['status'])) {
            if ($response['status'] == 'success') {
                pdo_execute(
                    "UPDATE `topup-order` SET `topup-order_order_code` = ?, `topup-order_status` = ? WHERE `topup-order_id` = ?",
                    [
                        $response['data']['order_code'],
                        'pending',
                        $topupOrder['topup-order_id']
                    ]
                );

                $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['webhook_topup']);
                if (!empty($webhook)) {
                    $role    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['role_topup']);
                    $message = form_discord_topup_success(
                        $response['data']['time'],
                        $response['data']['request_id'],
                        $response['data']['account']['phone'],
                        $response['data']['order_code'],
                        $response['data']['pay_amount'],
                        $response['data']['discount'],
                        $response['data']['service_code'],
                        $response['data']['amount'],
                        $response['data']['status']
                    );
                    sendDiscord($webhook, $message);
                }
            } else if ($response['status'] == 'payment_fail') {
                pdo_execute("UPDATE `topup-order` SET `topup-order_status` = 'delayed' WHERE `topup-order_id` = ?", [$topupOrder['topup-order_id']]);

                // HẾT TIỀN TRONG TÀI KHOẢN
                $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['webhook_topup']);
                if (!empty($webhook)) {
                    $role    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['role_topup']);
                    $message = "Hết tiền trong tài khoản nạp tiền điện thoại. Vui lòng nạp thêm. Và reset lệnh cho đơn nạp thẻ. Mã giao dịch: **$request_id**";
                    $message = createTopupErrorMessage($role, $message);
                    sendDiscord($webhook, $message);
                }
            } else {
                // ĐƠN LỖI
                $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['webhook_topup']);
                if (!empty($webhook)) {
                    $role    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = ?", ['role_topup']);
                    $message = "Đơn nạp thất bại. Mã giao dịch: **$request_id**\nDữ liệu JSON: " . json_encode($response, JSON_PRETTY_PRINT);
                    $message = createTopupErrorMessage($role, $message);
                    sendDiscord($webhook, $message);
                }
            }
        }
    }
}
