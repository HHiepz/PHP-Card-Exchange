<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request_admin")) {
        $data = json_decode($_POST['data'], true);

        // =================== HISTORY WITHDRAW ===================
        // SET TRẠNG THÁI THÀNH CÔNG - DUYỆT ĐƠN RÚT
        if (isset($data['history_withdraw_duyet'])) {
            // Lọc dữ liệu đầu vào
            $withdraw_code = $purifier->purify($data['history_withdraw_code']);

            // Kiểm tra đơn rút tiền có tồn tại không
            $withdraw = pdo_query_one("SELECT * FROM `withdraw` WHERE `wd_code` = ? AND `wd_status` != 'success'", [$withdraw_code]);

            // Thay đổi trạng thái đơn rút tiền
            if (!empty($withdraw)) {
                pdo_execute("UPDATE `withdraw` SET `wd_status` = 'success', `wd_cron` = 2, `wd_updated_api` = ? WHERE `wd_code` = ?", [getDateTimeNow(), $withdraw_code]);
                response(true, "Thay đổi trạng thái đơn rút tiền thành công");
            } else {
                response(false, "Đơn đã được duyệt hoặc không tồn tại!");
            }
        }

        // HỦY ĐƠN & HOÀN TIỀN
        if (isset($data['history_withdraw_cancel_Refurn'])) {
            // Lọc dữ liệu đầu vào
            $withdraw_code = $purifier->purify($data['history_withdraw_code']);

            // Kiểm tra đơn rút tiền có tồn tại không
            $withdraw = pdo_query_one("SELECT * FROM `withdraw` WHERE `wd_code` = ? AND `wd_status` != 'success'", [$withdraw_code]);
            // Thay đổi trạng thái đơn rút tiền
            if (!empty($withdraw)) {
                pdo_execute("UPDATE `withdraw` SET `wd_status` = 'cancel', `wd_updated_api` = ? WHERE `wd_code` = ?", [getDateTimeNow(), $withdraw_code]);

                // Hoàn tiền
                $user_id = $withdraw['user_id'];
                $withdraw_amount = $withdraw['wd_cash'];
                userCash('add', $withdraw_amount, $user_id, "Hủy đơn rút tiền " . $withdraw_code . " & hoàn tiền", getIdUser());
                response(true, "Hủy đơn và hoàn tiền đơn rut thành công");
            } else {
                response(false, "Đơn đã được duyệt hoặc không tồn tại!");
            }
        }

        // HỦY ĐƠN & KHÔNG HOÀN TIỀN
        if (isset($data['history_withdraw_cancel_nonRefurn'])) {
            // Lọc dữ liệu đầu vào
            $withdraw_code = $purifier->purify($data['history_withdraw_code']);

            // Kiểm tra đơn rút tiền có tồn tại không
            $withdraw = pdo_query_one("SELECT * FROM `withdraw` WHERE `wd_code` = ? AND `wd_status` != 'success'", [$withdraw_code]);

            // Thay đổi trạng thái đơn rút tiền
            if (!empty($withdraw)) {
                pdo_execute("UPDATE `withdraw` SET `wd_status` = 'cancel', `wd_updated_api` = ? WHERE `wd_code` = ?", [getDateTimeNow(), $withdraw_code]);

                response(true, "Hủy đơn rút tiền thành công");
            } else {
                response(false, "Đơn đã được duyệt hoặc không tồn tại!");
            }
        }


        // RESET LỆNH RÚT TIỀN
        if (isset($data['history_withdraw_reload'])) {
            // Lọc dữ liệu đầu vào
            $withdraw_code = $purifier->purify($data['history_withdraw_code']);

            // Kiểm tra đơn rút tiền có tồn tại không
            $withdraw = pdo_query_one("SELECT * FROM `withdraw` WHERE `wd_code` = ? AND `wd_status` != 'pending' AND `wd_status` != 'success'", [$withdraw_code]);
            if (empty($withdraw)) {
                response(false, "Đơn đã được xử lý trước đó hoặc không tôn tại!");
            }

            // Reset lệnh rút tiền
            pdo_execute("UPDATE `withdraw` SET `wd_status` = 'wait', `wd_updated_api` = NULL WHERE `wd_code` = ?", [$withdraw_code]);
            response(true, "Reset lệnh rút tiền thành công");
        }

        // =================== HISTORY EXCHANGE CARD ===================
        // GỬI LỆNH GỬI CARD TỚI WEB MẸ
        if (isset($data['history_card_send'])) {
            // Lọc dữ liệu đầu vào
            $exchange_code = $purifier->purify($data['history_card_code']);

            response(false, "Chức năng này đang được phát triển");
        }


        // GỬI LỆNH CALLBACK VỀ USER
        if (isset($data['history_card_callback_user'])) {
            // Lọc dữ liệu đầu vào
            $exchange_code = $purifier->purify($data['history_card_code']);

            // Kiểm tra đơn đổi thẻ có tồn tại không
            $cardData = pdo_query_one("SELECT * FROM `card-data` WHERE `card-data_request_id` = ?", [$exchange_code]);

            if (!empty($cardData)) {
                // Kiểm tra có tồn tại link callback không?
                if (empty($cardData['card-data_callback'])) {
                    response(false, "Đơn đổi thẻ không có link callback!");
                }

                if ($cardData['card-data_status'] == 'success') {
                    $callback_card_status = 1;
                } else if ($cardData['card-data_status'] == 'wrong_amount') {
                    $callback_card_status = 2;
                } else {
                    $callback_card_status = 3;
                }

                $dataCallback = [
                    'trans_id'          => $cardData['card-data_id'],
                    'telco'             => $cardData['card-data_telco'],
                    'code'              => $cardData['card-data_code'],
                    'serial'            => $cardData['card-data_seri'],
                    'status'            => $callback_card_status,
                    'message'           => $cardData['card-data_api_message'],
                    'request_id'        => $cardData['card-data_partner_request_id'],
                    'declared_value'    => $cardData['card-data_amount'],
                    'value'             => $cardData['card-data_amount_real'],
                    'amount'            => $cardData['card-data_amount_recieve'],
                    'callback_sign'     => $cardData['card-data_partner_sign']
                ];
                curlGet($cardData['card-data_callback'] . '?' . http_build_query($dataCallback));

                response(true, "Gửi lệnh callback về user thành công");
            } else {
                response(false, "Đơn đổi thẻ không tồn tại!");
            }
        }


        // DUYỆT THẺ & REFURN 
        if (isset($data['history_card_duyet_refurn'])) {
            // Lọc dữ liệu đầu vào
            $exchange_code = $purifier->purify($data['history_card_code']);

            // Kiểm tra đơn đổi thẻ có tồn tại không
            $cardData = pdo_query_one("SELECT * FROM `card-data` WHERE `card-data_request_id` = ?", [$exchange_code]);

            if (!empty($cardData)) {
                // Kiểm tra đơn đổi thẻ đã được duyệt chưa
                if ($cardData['card-data_status'] == 'wait') {
                    pdo_execute("UPDATE `card-data` SET `card-data_status` = 'success', `card-data_updated_api` = ? WHERE `card-data_request_id` = ?", [getDateTimeNow(), $exchange_code]);

                    // Hoàn tiền
                    $user_id = $cardData['user_id'];
                    $exchange_amount = $cardData['card-data_amount'];
                    userCash('add', $exchange_amount, $user_id, "Duyệt đơn đổi thẻ " . $exchange_code . " & hoàn tiền", getIdUser());
                    response(true, "Duyệt đơn đổi thẻ và hoàn tiền thành công");
                } else {
                    response(false, "Đơn đổi thẻ đã được duyệt hoặc không tồn tại!");
                }
            } else {
                response(false, "Đơn đổi thẻ không tồn tại!");
            }
        }


        // DUYỆT THẺ & NON-REFURN
        if (isset($data['history_card_duyet_nonRefurn'])) {
            // Lọc dữ liệu đầu vào
            $exchange_code = $purifier->purify($data['history_card_code']);

            // Kiểm tra đơn đổi thẻ có tồn tại không
            $cardData = pdo_query_one("SELECT * FROM `card-data` WHERE `card-data_request_id` = ?", [$exchange_code]);

            if (!empty($cardData)) {
                // Kiểm tra đơn đổi thẻ đã được duyệt chưa
                if ($cardData['card-data_status'] == 'wait') {
                    pdo_execute("UPDATE `card-data` SET `card-data_status` = 'success', `card-data_updated_api` = ? WHERE `card-data_request_id` = ?", [getDateTimeNow(), $exchange_code]);

                    response(true, "Duyệt đơn đổi thẻ thành công");
                } else {
                    response(false, "Đơn đổi thẻ đã được duyệt hoặc không tồn tại!");
                }
            } else {
                response(false, "Đơn đổi thẻ không tồn tại!");
            }
        }


        // DUYỆT THẺ SAI MỆNH GIÁ & REFURN 50% 
        if (isset($data['history_card_duyet_saiMenhGia'])) {
            // Lọc dữ liệu đầu vào
            $exchange_code = $purifier->purify($data['history_card_code']);

            response(false, "Chức năng này đang được phát triển");
        }


        // HỦY THẺ & NON-REFURN
        if (isset($data['history_card_cancel_nonRefurn'])) {
            // Lọc dữ liệu đầu vào
            $exchange_code = $purifier->purify($data['history_card_code']);


            // Kiểm tra đơn đổi thẻ có tồn tại không
            $cardData = pdo_query_one("SELECT * FROM `card-data` WHERE `card-data_request_id` = ?", [$exchange_code]);

            if (!empty($cardData)) {
                // Kiểm tra đơn đổi thẻ đã được duyệt chưa
                if ($cardData['card-data_status'] == 'wait') {
                    pdo_execute("UPDATE `card-data` SET `card-data_status` = 'fail', `card-data_updated_api` = ? WHERE `card-data_request_id` = ?", [getDateTimeNow(), $exchange_code]);

                    response(true, "Hủy đơn đổi thẻ thành công");
                } else {
                    response(false, "Đơn đổi thẻ đã được duyệt hoặc không tồn tại!");
                }
            } else {
                response(false, "Đơn đổi thẻ không tồn tại!");
            }
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
