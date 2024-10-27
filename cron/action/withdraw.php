<?php
require('../../core/database.php');
require('../../core/function.php');
require('../../core/apiSend.php');

// ==================== ĐƠN TREO - CHỜ KẾT QUẢ ====================
$withdraw_hold = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'hold' AND `wd_cron` = 1");
foreach ($withdraw_hold as $withdraw) {
    $order_code = $withdraw['wd_api_order_code'];

    // Call API kiểm tra trạng thái rút tiền
    $response = checkWithdraw($order_code);

    if (is_array($response) && !empty($response)) {
        if (isset($response['data']['status'])) {
            if ($response['data']['status'] == 'completed') {
                pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron
                pdo_execute(
                    "UPDATE `withdraw` SET `wd_status` = 'success', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
                    [
                        $response['data']['status'],
                        $response['message'],
                        getDateTimeNow(),
                        $withdraw['wd_id']
                    ]
                );
            }

            if ($response['data']['status'] == 'canceled' || $response['data']['status'] == 'refunded') {
                pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron
                pdo_execute(
                    "UPDATE `withdraw` SET `wd_status` = 'fail', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
                    [
                        $response['data']['status'],
                        $response['data']['message'],
                        getDateTimeNow(),
                        $withdraw['wd_id']
                    ]
                );
                userCash('add', $withdraw['wd_cash'], $withdraw['user_id'], "Hoàn tiền đơn rút thất bại, mã giao dịch: " . $withdraw['wd_code']);
            }
        }
    }
}


// ==================== ĐƠN CHỜ KẾT QUẢ ====================
$withdraw_pending = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'pending' AND `wd_cron` = 1");
foreach ($withdraw_pending as $withdraw) {
    $order_code = $withdraw['wd_api_order_code'];

    // Call API kiểm tra trạng thái rút tiền
    $response = checkWithdraw($order_code);

    if (is_array($response) && !empty($response)) {
        if (isset($response['data']['status'])) {
            if ($response['data']['status'] == 'completed') {
                pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron
                pdo_execute(
                    "UPDATE `withdraw` SET `wd_status` = 'success', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
                    [
                        $response['data']['status'],
                        $response['message'],
                        getDateTimeNow(),
                        $withdraw['wd_id']
                    ]
                );
            }

            // Đơn treo, duyệt chậm trong 24h | Trạng thái 'hold'
            if ($response['data']['status'] == 'failed') {
                // Chuyển trạng thái không tăng cron.
                $message = "Sẽ tự động duyệt trong 24h tính từ lúc tạo đơn!.";
                pdo_execute(
                    "UPDATE `withdraw` SET `wd_status` = 'hold', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
                    [
                        $response['data']['status'],
                        $message,
                        getDateTimeNow(),
                        $withdraw['wd_id']
                    ]
                );
            }

            if ($response['data']['status'] == 'canceled' || $response['data']['status'] == 'refunded') {
                pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron
                pdo_execute(
                    "UPDATE `withdraw` SET `wd_status` = 'fail', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
                    [
                        $response['data']['status'],
                        $response['data']['message'],
                        getDateTimeNow(),
                        $withdraw['wd_id']
                    ]
                );
                userCash('add', $withdraw['wd_cash'], $withdraw['user_id'], "Hoàn tiền đơn rút thất bại, mã giao dịch: " . $withdraw['wd_code']);
            }
        }
    }
}

// ==================== ĐƠN CHƯA XỬ LÝ ====================
$withdraw_new = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'wait' AND `wd_cron` = 0");
foreach ($withdraw_new as $withdraw) {
    pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron

    $bank_name              = showCodeBank($withdraw['wd_bank_name']);   // Mã ngân hàng (do bên nhà cung cấp API cung cấp)
    $bank_number_account    = $withdraw['wd_number_account'];            // Số tài khoản
    $bank_owner             = $withdraw['wd_bank_owner'];                // Chủ tài khoản
    $bank_description       = $withdraw['wd_description'];               // Nội dung chuyển
    $bank_cash              = $withdraw['wd_cash'];                      // Số tiền rút

    // Call API rút tiền
    $response = sendWithdraw($bank_name, $bank_number_account, $bank_owner, $bank_cash, $bank_description);

    if ($response['status'] == 1) {
        pdo_execute(
            "UPDATE `withdraw` SET `wd_status` = 'pending', `wd_api_order_code` = ?, `wd_api_status` = ?, `wd_api_message` = ? WHERE `wd_id` = ?",
            [
                $response['data']['order_code'],
                $response['data']['status'],
                checkErrorStatus($response['status']),
                $withdraw['wd_id']
            ]
        );
    }
}

// NOTE DEV: NẾU NHÀ MẠNG KHÔNG HỖ TRỢ RÚT MOMO THÌ MỞ TẤT CẢ COMMENT DƯỚI ĐÂY
// // ==================== ĐƠN CHỜ KẾT QUẢ ====================
// $withdraw_pending = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'pending' AND `wd_bank_name` != 'MOMO' AND `wd_cron` = 1");
// foreach ($withdraw_pending as $withdraw) {
//     $order_code = $withdraw['wd_api_order_code'];

//     // Call API kiểm tra trạng thái rút tiền
//     $response = checkWithdraw($order_code);

//     if ($response['data']['status'] == 'completed') {
//         pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron
//         pdo_execute(
//             "UPDATE `withdraw` SET `wd_status` = 'success', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
//             [
//                 $response['data']['status'],
//                 $response['message'],
//                 getDateTimeNow(),
//                 $withdraw['wd_id']
//             ]
//         );
//     }

//     if ($response['data']['status'] == 'canceled') {
//         pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron
//         pdo_execute(
//             "UPDATE `withdraw` SET `wd_status` = 'fail', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
//             [
//                 $response['data']['status'],
//                 $response['data']['message'],
//                 getDateTimeNow(),
//                 $withdraw['wd_id']
//             ]
//         );
//         userCash('add', $withdraw['wd_cash'], $withdraw['user_id'], "Hoàn tiền đơn rút thất bại, mã giao dịch: " . $withdraw['wd_code']);
//     }
// }

// // ==================== ĐƠN CHƯA XỬ LÝ ====================
// $withdraw_new = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'wait' AND `wd_bank_name` != 'MOMO' AND `wd_cron` = 0");
// foreach ($withdraw_new as $withdraw) {
//     pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$withdraw['wd_id']]); // Tăng 1 lần cron

//     $bank_name              = showCodeBank($withdraw['wd_bank_name']);   // Mã ngân hàng (do bên nhà cung cấp API cung cấp)
//     $bank_number_account    = $withdraw['wd_number_account'];            // Số tài khoản
//     $bank_owner             = $withdraw['wd_bank_owner'];                // Chủ tài khoản
//     $bank_description       = "CARD2K " . $withdraw['wd_code'];          // Nội dung chuyển
//     $bank_cash              = $withdraw['wd_cash'];                      // Số tiền rút

//     // Call API rút tiền
//     $response = sendWithdraw($bank_name, $bank_number_account, $bank_owner, $bank_cash, $bank_description);

//     if ($response['status'] == 1) {
//         pdo_execute(
//             "UPDATE `withdraw` SET `wd_status` = 'pending', `wd_api_order_code` = ?, `wd_api_status` = ?, `wd_api_message` = ? WHERE `wd_id` = ?",
//             [
//                 $response['data']['order_code'],
//                 $response['data']['status'],
//                 checkErrorStatus($response['status']),
//                 $withdraw['wd_id']
//             ]
//         );
//     }
// }

// // ==================== MOMO - ĐƠN CHỜ KẾT QUẢ ====================
// $momo_pending = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'pending' AND `wd_bank_name` = 'MOMO' AND `wd_cron` = 1");
// foreach ($momo_pending as $momo) {
//     $order_code = $momo['wd_api_order_code'];

//     // Call API kiểm tra trạng thái rút tiền
//     $response = checkWithdraw($order_code);

//     if ($response['data']['status'] == 'completed') {
//         pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$momo['wd_id']]); // Tăng 1 lần cron
//         pdo_execute(
//             "UPDATE `withdraw` SET `wd_status` = 'pending', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
//             [
//                 $response['data']['status'],
//                 $response['message'],
//                 getDateTimeNow(),
//                 $momo['wd_id']
//             ]
//         );

//         // Gửi thông báo discord
//         $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_withdraw'");
//         if (!empty($webhook)) {
//             $message = createDiscordMessage_success(number_format($momo['wd_cash']), $momo['wd_code']);
//             sendDiscord($webhook, $message);
//         }
//     }

//     if ($response['data']['status'] == 'canceled') {
//         pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$momo['wd_id']]); // Tăng 1 lần cron
//         pdo_execute(
//             "UPDATE `withdraw` SET `wd_status` = 'pending', `wd_api_status` = ?, `wd_api_message` = ?, `wd_updated_api` = ? WHERE `wd_id` = ?",
//             [
//                 $response['data']['status'],
//                 $response['data']['message'],
//                 getDateTimeNow(),
//                 $momo['wd_id']
//             ]
//         );

//         // Gửi thông báo discord
//         $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_withdraw'");
//         if (!empty($webhook)) {
//             $message = createDiscordMessage_fail(number_format($momo['wd_cash']), $momo['wd_code']);
//             sendDiscord($webhook, $message);
//         }
//         userCash('add', $momo['wd_cash'], $momo['user_id'], "Hoàn tiền đơn rút thất bại, mã giao dịch: " . $momo['wd_code']);
//     }
// }

// // ==================== MOMO - ĐƠN CHƯA XỬ LÝ ====================
// $momo_new = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'wait' AND `wd_bank_name` = 'MOMO' AND `wd_cron` = 0");
// foreach ($momo_new as $momo) {
//     pdo_execute("UPDATE `withdraw` SET `wd_cron` = `wd_cron` + 1 WHERE `wd_id` = ?", [$momo['wd_id']]); // Tăng 1 lần cron

//     $momo_bank       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'momo_bank_code'");
//     $momo_number     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'momo_number'");
//     $momo_owner      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'momo_owner'");

//     $bank_name              = showCodeBank($momo_bank);                  // Mã ngân hàng (do bên nhà cung cấp API cung cấp)
//     $bank_number_account    = $momo_number;                              // Số tài khoản
//     $bank_owner             = $momo_owner;                               // Chủ tài khoản
//     $bank_description       = "CARD2K " . $momo['wd_code'];              // Nội dung chuyển
//     $bank_cash              = $momo['wd_cash'];                          // Số tiền rút

//     // Call API rút tiền
//     $response = sendWithdraw($bank_name, $bank_number_account, $bank_owner, $bank_cash, $bank_description);

//     if ($response['status'] == 1) {
//         pdo_execute(
//             "UPDATE `withdraw` SET `wd_status` = 'pending', `wd_api_order_code` = ?, `wd_api_status` = ?, `wd_api_message` = ? WHERE `wd_id` = ?",
//             [
//                 $response['data']['order_code'],
//                 $response['data']['status'],
//                 checkErrorStatus($response['status']),
//                 $momo['wd_id']
//             ]
//         );
//     }
// }
