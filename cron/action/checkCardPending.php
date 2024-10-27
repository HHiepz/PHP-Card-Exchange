<?php
// NOTE DEV: Tự động kiểm tra thẻ đang chờ xử lý.
require('../../core/function.php');
require('../../core/database.php');
require('../../core/apiSend.php');
require('../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$listCardPending = pdo_query("SELECT * FROM `card-data` WHERE `card-data_status` = 'wait'");

foreach ($listCardPending as $cardInfo) {
    $check_telco       = $cardInfo['card-data_telco'];
    $code              = $cardInfo['card-data_code'];
    $seri              = $cardInfo['card-data_seri'];
    $check_amount      = $cardInfo['card-data_amount'];
    $check_request_id  = $cardInfo['card-data_request_id'];
    
    $resultAPI  = checkCardPending($check_telco, $code, $seri, $check_amount, $check_request_id);
    
    $webhook        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_exchange_card'");  // Webhook discord

    // Kiểm tra API có trả về dữ liệu
    if (!is_array($resultAPI) || empty($resultAPI) || !isset($resultAPI['status'])) {
        continue;
    }

    $status         = $purifier->purify($resultAPI['status']);            // Trạng thái thẻ : 1: Thành công, 2: Sai mệnh giá, 3: Thẻ lỗi, 4: Hệ thống bảo trì, 99: Chờ xử lý, 100: Gửi thẻ thất bại
    $message        = $purifier->purify($resultAPI['message']);           // Nội dung thông báo

    // Thẻ không xác định - Thay đổi trạng thái thẻ (Không cộng tiền user).
    if ($status != 1 && $status != 2 && $status != 3 && $status != 4 && $status != 99 && $status != 100) {
        // Cập nhật trạng thái thẻ
        $sql = "UPDATE `card-data` SET
            `card-data_status`         = 'fail', -- Trạng thái thẻ
            `card-data_amount_real`    = ?,       -- Mệnh giá thực
            `card-data_amount_recieve` = ?,       -- Số tiền nhận được
            `card-data_profit`         = ?,       -- Lợi nhuận
            `card-data_punish`         = ?,       -- Phạt
            `card-data_updated_api`    = ?,       -- Thời gian cập nhật
            `card-data_api_message`    = ?        -- Thông báo từ API
            WHERE `card-data_request_id` = ? AND `card-data_code` = ? AND `card-data_seri` = ?
            ";
        pdo_execute($sql, [0, 0, 0, 0, getDateTimeNow(), $message, $check_request_id, $code, $seri]);

        // Callback (nếu thẻ có)
        if ($cardInfo['card-data_callback'] != null) {
            $dataCallback = [
                'trans_id'          => $cardInfo['card-data_id'],
                'telco'             => $telco,
                'code'              => $code,
                'serial'            => $seri,
                'status'            => 3,
                'message'           => $message,
                'request_id'        => $cardInfo['card-data_partner_request_id'],
                'declared_value'    => 0,
                'value'             => 0,
                'amount'            => 0,
                'callback_sign'     => md5($cardInfo['card-data_partner_key'] . $code . $seri)
            ];
            curlGet($cardInfo['card-data_callback'] . '?' . http_build_query($dataCallback));
        }

        // Thông báo discord admin
        if (!empty($webhook)) {
            $form_discord_email    = getEmailUser($cardInfo['user_id']);                                                 // Email user
            $form_discord_rank     = getRankUser($cardInfo['user_id']);                                                  // Rank user
            $form_discord_callback = empty($cardInfo['card-data_callback']) ? 'False' : $cardInfo['card-data_callback']; // Callback user (nếu có)
            sendDiscord($webhook, form_discord_exchangeCard_callback($cardInfo['user_id'], "$form_discord_email", "$form_discord_rank", "$telco", "$code", "$seri", 0, 0, 0, $check_request_id, "cancel", "$form_discord_callback"));
        }

        jsonReturn(true, 'Thẻ lỗi status đặc biệt, không cộng tiền user');
    }

    $telco          = $purifier->purify(strtoupper($resultAPI['telco'])); // Nhà mạng
    $request_id     = $purifier->purify($resultAPI['request_id']);        // Mã giao dịch
    $declared_value = $purifier->purify($resultAPI['declared_value']);    // Mệnh giá khai báo
    $card_value     = $purifier->purify($resultAPI['value']);             // Mệnh giá thực
    $amount         = $purifier->purify($resultAPI['amount']);            // Số tiền nhận được

    // Tiền user nhận được
    $amount_user = pdo_query_value("SELECT `card-data_amount_recieve` FROM `card-data` WHERE `card-data_request_id` = ? AND `card-data_code` = ? AND `card-data_seri` = ?", [$request_id, $code, $seri]);

    // Lợi nhuận [Tiền thực nhận - Tiền chia cho user]
    $profit = $amount - $amount_user;

    // Thẻ đúng - Thay đổi trạng thái thẻ, cộng tiền user.
    if ($status == 1) {
        // Cập nhật trạng thái thẻ
        $sql = "UPDATE `card-data` SET
                    `card-data_status`         = 'success', -- Trạng thái thẻ
                    `card-data_amount_real`    = ?,         -- Mệnh giá thực
                    `card-data_amount_recieve` = ?,         -- Số tiền nhận được
                    `card-data_profit`         = ?,         -- Lợi nhuận
                    `card-data_punish`         = ?,         -- Phạt
                    `card-data_updated_api`    = ?,         -- Thời gian cập nhật
                    `card-data_api_message`    = ?          -- Thông báo từ API
                    WHERE `card-data_request_id` = ? AND `card-data_code` = ? AND `card-data_seri` = ?
                ";
        pdo_execute($sql, [$card_value, $amount_user, $profit, 0, getDateTimeNow(), $message, $request_id, $code, $seri]);

        // Callback (nếu thẻ có)
        // Card-data_callback = https://card2k.com/callback/ex-card.php
        if ($cardInfo['card-data_callback'] != null) {
            $dataCallback = [
                'trans_id'          => $cardInfo['card-data_id'],
                'telco'             => $telco,
                'code'              => $code,
                'serial'            => $seri,
                'status'            => 1,
                'message'           => $message,
                'request_id'        => $cardInfo['card-data_partner_request_id'],
                'declared_value'    => $declared_value,
                'value'             => $card_value,
                'amount'            => $amount_user,
                'callback_sign'     => md5($cardInfo['card-data_partner_key'] . $code . $seri)
            ];
            curlGet($cardInfo['card-data_callback'] . '?' . http_build_query($dataCallback));
        }

        // Cộng tiền user
        userCash('add', $amount_user, $cardInfo['user_id'], "Doithecao $code - $seri");

        // Top nạp thẻ
        addTopNapThe($cardInfo['user_id'], $card_value);

        // Thông báo discord admin
        if (!empty($webhook)) {
            $form_discord_email    = getEmailUser($cardInfo['user_id']);                                                 // Email user
            $form_discord_rank     = getRankUser($cardInfo['user_id']);                                                  // Rank user
            $form_discord_callback = empty($cardInfo['card-data_callback']) ? 'False' : $cardInfo['card-data_callback']; // Callback user (nếu có)
            sendDiscord($webhook, form_discord_exchangeCard_callback($cardInfo['user_id'], "$form_discord_email", "$form_discord_rank", "$telco", "$code", "$seri", "$card_value", $amount_user, $profit, $request_id, "success", "$form_discord_callback"));
        }

        jsonReturn(true, 'Thẻ dúng mệnh giá, cộng tiền user thành công');
    }

    // Thẻ sai mệnh giá - Thay đổi trạng thái thẻ, cộng tiền user (Trừ 50% giá trị thực nhận).
    if ($status == 2) {
        // 100.000 < 50.000
        if ($declared_value < $card_value) {
            // 50% của tổng số tiền nhận được dựa trên mệnh giá khai báo.
            $amount_user = getAmountRecieveUser($cardInfo['user_id'], $telco, $declared_value) / 2;
        } else {
            // 50% của tổng số tiền nhận được dựa trên mệnh giá thẻ đúng.
            $amount_user = getAmountRecieveUser($cardInfo['user_id'], $telco, $card_value) / 2;
        }

        // Cập nhật lại: lợi nhuận
        $profit = $amount - $amount_user;

        // Cập nhật trạng thái thẻ
        $sql = "UPDATE `card-data` SET
                        `card-data_status`         = 'wrong_amount', -- Trạng thái thẻ
                        `card-data_amount_real`    = ?,       -- Mệnh giá thực
                        `card-data_amount_recieve` = ?,       -- Số tiền nhận được
                        `card-data_profit`         = ?,       -- Lợi nhuận
                        `card-data_punish`         = ?,       -- Phạt
                        `card-data_updated_api`    = ?,       -- Thời gian cập nhật
                        `card-data_api_message`    = ?        -- Thông báo từ API
                        WHERE `card-data_request_id` = ? AND `card-data_code` = ? AND `card-data_seri` = ?
                    ";
        pdo_execute($sql, [$card_value, $amount_user, $profit, $amount_user, getDateTimeNow(), $message, $request_id, $code, $seri]);

        // Callback (nếu thẻ có)
        if ($cardInfo['card-data_callback'] != null) {
            $dataCallback = [
                'trans_id'          => $cardInfo['card-data_id'],
                'telco'             => $telco,
                'code'              => $code,
                'serial'            => $seri,
                'status'            => 2,
                'message'           => $message,
                'request_id'        => $cardInfo['card-data_partner_request_id'],
                'declared_value'    => $declared_value,
                'value'             => $card_value,
                'amount'            => $amount_user,
                'callback_sign'     => md5($cardInfo['card-data_partner_key'] . $code . $seri)
            ];
            curlGet($cardInfo['card-data_callback'] . '?' . http_build_query($dataCallback));
        }

        // Cộng tiền user
        userCash('add', $amount_user, $cardInfo['user_id'], "Doithecao $code - $seri");

        // Top nạp thẻ
        addTopNapThe($cardInfo['user_id'], $card_value);

        // Thông báo discord admin
        if (!empty($webhook)) {
            $form_discord_email    = getEmailUser($cardInfo['user_id']);                                                 // Email user
            $form_discord_rank     = getRankUser($cardInfo['user_id']);                                                  // Rank user
            $form_discord_callback = empty($cardInfo['card-data_callback']) ? 'False' : $cardInfo['card-data_callback']; // Callback user (nếu có)
            sendDiscord($webhook, form_discord_exchangeCard_callback($cardInfo['user_id'], "$form_discord_email", "$form_discord_rank", "$telco", "$code", "$seri", "$card_value", $amount_user, $profit, $request_id, "wrong", "$form_discord_callback"));
        }

        jsonReturn(true, 'Thẻ sai mệnh giá, cộng tiền user thành công');
    }

    // Thẻ lỗi - Thay đổi trạng thái thẻ (Không cộng tiền user).
    if ($status == 3 || $status == 4 || $status == 100) {
        // Cập nhật trạng thái thẻ
        $sql = "UPDATE `card-data` SET
                        `card-data_status`         = 'fail', -- Trạng thái thẻ
                        `card-data_amount_real`    = ?,       -- Mệnh giá thực
                        `card-data_amount_recieve` = ?,       -- Số tiền nhận được
                        `card-data_profit`         = ?,       -- Lợi nhuận
                        `card-data_punish`         = ?,       -- Phạt
                        `card-data_updated_api`    = ?,       -- Thời gian cập nhật
                        `card-data_api_message`    = ?        -- Thông báo từ API
                        WHERE `card-data_request_id` = ? AND `card-data_code` = ? AND `card-data_seri` = ?
                    ";
        pdo_execute($sql, [$card_value, 0, 0, 0, getDateTimeNow(), $message, $request_id, $code, $seri]);

        // Callback (nếu thẻ có)
        if ($cardInfo['card-data_callback'] != null) {
            $dataCallback = [
                'trans_id'          => $cardInfo['card-data_id'],
                'telco'             => $telco,
                'code'              => $code,
                'serial'            => $seri,
                'status'            => 3,
                'message'           => $message,
                'request_id'        => $cardInfo['card-data_partner_request_id'],
                'declared_value'    => $declared_value,
                'value'             => $card_value,
                'amount'            => $amount_user,
                'callback_sign'     => md5($cardInfo['card-data_partner_key'] . $code . $seri)
            ];
            curlGet($cardInfo['card-data_callback'] . '?' . http_build_query($dataCallback));
        }

        // Thông báo discord admin
        if (!empty($webhook)) {
            $form_discord_email    = getEmailUser($cardInfo['user_id']);                                                 // Email user
            $form_discord_rank     = getRankUser($cardInfo['user_id']);                                                  // Rank user
            $form_discord_callback = empty($cardInfo['card-data_callback']) ? 'False' : $cardInfo['card-data_callback']; // Callback user (nếu có)
            sendDiscord($webhook, form_discord_exchangeCard_callback($cardInfo['user_id'], "$form_discord_email", "$form_discord_rank", "$telco", "$code", "$seri", "$card_value", 0, 0, $request_id, "cancel", "$form_discord_callback"));
        }

        jsonReturn(true, 'Thẻ lỗi, không cộng tiền user');
    }
}

jsonReturn(true, "Không có thẻ nào cần xử lý");
