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

        // =================== CARD ORDER ===================
        // DUYỆT THẺ THÀNH CÔNG
        if (isset($data['buyCard_success'])) {
            // Lọc dữ liệu đầu vào
            $buyCard_code = $purifier->purify($data['buycard_code']);

            // Kiểm tra thẻ có tồn tại
            $checkCard = pdo_query_one("SELECT * FROM `buy-card-order` WHERE `buy-card-order_code` = ?", [$buyCard_code]);
            if (empty($checkCard)) {
                response(false, "Mã đơn hàng không tồn tại!");
            }

            // Kiểm tra đơn hàng đã hoàn thành chưa
            if ($checkCard['buy-card-order_status'] == 'success') {
                response(false, "Đơn hàng đã hoàn thành không thể hủy!");
            }

            // Cập nhật trạng thái đơn hàng
            pdo_execute("UPDATE `buy-card-order` SET `buy-card-order_status` = 'success', `updated_api` = ? WHERE `buy-card-order_code` = ?", [getDateTimeNow(), $buyCard_code]);

            response(true, "Duyệt đơn hàng mua thẻ cào thành công!");
        }
        
        // HỦY & REFURN
        if (isset($data['buyCard_cancel_refurn'])) {
            // Lọc dữ liệu đầu vào
            $buyCard_code = $purifier->purify($data['buycard_code']);

            // Kiểm tra thẻ có tồn tại
            $checkCard = pdo_query_one("SELECT * FROM `buy-card-order` WHERE `buy-card-order_code` = ?", [$buyCard_code]);
            if (empty($checkCard)) {
                response(false, "Mã đơn hàng không tồn tại!");
            }

            // Kiểm tra đơn hàng đã hoàn thành chưa
            if ($checkCard['buy-card-order_status'] == 'success') {
                response(false, "Đơn hàng đã hoàn thành không thể hủy!");
            }

            // Trả lại tiền
            userCash('add', $checkCard['buy-card-order_total_pay'], $checkCard['user_id'], "Hủy đơn hàng mua thẻ cào: $buyCard_code & Hoàn tiền", getIdUser());

            // Cập nhật trạng thái đơn hàng
            pdo_execute("UPDATE `buy-card-order` SET `buy-card-order_status` = 'cancel' WHERE `buy-card-order_code` = ?", [$buyCard_code]);

            response(true, "Hủy đơn hàng mua thẻ cào thành công!");
        }

        // HỦY & KHÔNG REFURN
        if (isset($data['buyCard_cancel_non_refurn'])) {
            // Lọc dữ liệu đầu vào
            $buyCard_code = $purifier->purify($data['buycard_code']);

            // Kiểm tra thẻ có tồn tại
            $checkCard = pdo_query_one("SELECT * FROM `buy-card-order` WHERE `buy-card-order_code` = ?", [$buyCard_code]);
            if (empty($checkCard)) {
                response(false, "Mã đơn hàng không tồn tại!");
            }

            // Kiểm tra đơn hàng đã hoàn thành chưa
            if ($checkCard['buy-card-order_status'] == 'success') {
                response(false, "Đơn hàng đã hoàn thành không thể hủy!");
            }

            // Cập nhật trạng thái đơn hàng
            pdo_execute("UPDATE `buy-card-order` SET `buy-card-order_status` = 'cancel' WHERE `buy-card-order_code` = ?", [$buyCard_code]);

            response(true, "Hủy đơn hàng mua thẻ cào thành công!");
        }

        // =================== CARD DATA ===================
        // XÓA THẺ DATA
        if (isset($data['buyCardData_delete'])) {
            // Lọc dữ liệu đầu vào
            $buyCard_id = $purifier->purify($data['buyCardData_id']);

            // Kiểm tra thẻ có tồn tại
            $checkCard = pdo_query_one("SELECT * FROM `buy-card-data` WHERE `buy-card-data_id` = ?", [$buyCard_id]);
            if (empty($checkCard)) {
                response(false, "Thẻ data không tồn tại!");
            }

            // Xóa thẻ data
            pdo_execute("DELETE FROM `buy-card-data` WHERE `buy-card-data_id` = ?", [$buyCard_id]);

            response(true, "Xóa thẻ data thành công!");
        }

        // THÊM THẺ DATA
        if (isset($data['buyCardData_add'])) {
            $buyCard_order_code  = $purifier->purify($data['card_data_order_code']);
            $buyCard_data_telco  = $purifier->purify($data['buyCardData_telco']);
            $buyCard_data_price  = $purifier->purify($data['buyCardData_price']);
            $buyCard_data_code   = $purifier->purify($data['buyCardData_code']);
            $buyCard_data_seri   = $purifier->purify($data['buyCardData_seri']);

            // Kiểm tra rỗng
            if (empty($buyCard_order_code) || empty($buyCard_data_telco) || empty($buyCard_data_price) || empty($buyCard_data_code) || empty($buyCard_data_seri)) {
                response(false, "Vui lòng nhập đầy đủ thông tin!");
            }

            // Kiểm tra đơn hàng có tồn tại
            $checkOrder = pdo_query_one("SELECT * FROM `buy-card-order` WHERE `buy-card-order_code` = ?", [$buyCard_order_code]);
            if (empty($checkOrder)) {
                response(false, "Mã đơn hàng không tồn tại!");
            }

            // Kiểm tra loại thẻ cào
            $telco_list = list_telco_buyCard();
            if (!array_key_exists($buyCard_data_telco, $telco_list)) {
                response(false, "Nhà mạng không hợp lệ");
            }

            // kiểm tra serial và mã thẻ có tồn tại 
            $checkCard = pdo_query_one("SELECT * FROM `buy-card-data` WHERE `buy-card-data_pin` = ? AND `buy-card-data_serial` = ?", [$buyCard_data_code, $buyCard_data_seri]);
            if (!empty($checkCard)) {
                response(false, "Thẻ data đã tồn tại trên hệ thống!");
            }

            // THÊM THẺ DATA
            $sql = "INSERT INTO `buy-card-data` SET 
                `buy-card-order_code`  = ?,
                `buy-card-data_price`  = ?,
                `buy-card-data_telco`  = ?,
                `buy-card-data_pin`    = ?,
                `buy-card-data_serial` = ?";
            pdo_execute($sql, [$buyCard_order_code, $buyCard_data_price, $buyCard_data_telco, $buyCard_data_code, $buyCard_data_seri]);

            response(true, "Thêm thẻ data thành công!");
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
