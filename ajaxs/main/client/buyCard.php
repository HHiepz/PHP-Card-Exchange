<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../core/apiSend.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    if (checkToken('request')) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra trạng thá có bảo trì không
        $checkBuyCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_buyCard'");
        $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
        if ($checkBuyCard != 1 || $checkStatusServer != 1) {
            response(false, 'Chức năng đang tạm thời bảo trì');
        }

        // Lọc dữ liệu đầu vào
        $buyCard_type     = $purifier->purify(strtoupper(trim($data['buyCard_type'])));
        $buyCard_price    = $purifier->purify(trim($data['buyCard_price']));
        $buyCard_quantity = $purifier->purify(trim($data['buyCard_quantity']));

        $user_id = getIdUser(); // ID người dùng

        // Kiểm tra rỗng
        if (isEmptyOrNull($buyCard_type) || isEmptyOrNull($buyCard_price) || isEmptyOrNull($buyCard_quantity)) {
            response(false, 'Không được để trống thông tin');
        }

        // Chống Spam lệnh
        $user_info = getInfoUser($user_id);
        if ($user_info['user_last_buyCard'] > (time() - 10)) {
            response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
        } else {
            pdo_execute("UPDATE `user` SET `user_last_buyCard` = ? WHERE `user_id` = ?", [time(), $user_id]);
        }

        // Kiểm tra số lượng thẻ cào
        if (!is_numeric($buyCard_quantity) || $buyCard_quantity < 1) {
            response(false, 'Số lượng thẻ cào không hợp lệ');
        }

        // Kiểm tra số lượng tối đa
        $max_buyCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'max_buyCard'");
        if ($buyCard_quantity > $max_buyCard) {
            response(false, 'Số lượng thẻ cào không được vượt quá ' . number_format($max_buyCard, 0, ',', '.'));
        }

        // Kiểm tra số lượng tối thiểu
        $min_buyCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'min_buyCard'");
        if ($buyCard_quantity < $min_buyCard) {
            response(false, 'Số lượng thẻ cào không được dưới ' . number_format($min_buyCard, 0, ',', '.'));
        }

        // Kiểm tra loại thẻ cào
        $telco_list = list_telco_buyCard();
        if (!array_key_exists($buyCard_type, $telco_list)) {
            response(false, "Nhà mạng không hợp lệ");
        }

        // Kiểm tra mệnh giá thẻ
        $list_amount = list_amount_buyCard();
        if (!array_key_exists($buyCard_price, $list_amount[$buyCard_type])) {
            response(false, "Nhà mạng không hỗ trợ mệnh giá này");
        }

        // Tính tổng giá trị thẻ cào cần thanh toán (Số lượng nhân mệnh giá trừ đi phí giảm giá thẻ cào)
        $initialValue = $buyCard_price * $buyCard_quantity;                 // Tổng giá trị thẻ cào
        $discountRate = $list_amount[$buyCard_type][$buyCard_price] / 100;  // Phần trăm giảm giá
        $buyCard_value = $initialValue - ($initialValue * $discountRate);   // Giá trị thực tế cần thanh toán

        // Kiểm tra số dư
        if ($user_info['user_cash'] < $buyCard_value) {
            response(false, 'Số dư không đủ');
        }

        $buyCard_code = 'BC' . rand_string(9);

        // Gửi yêu cầu web mẹ
        $APIBuyCard = buyCard($buyCard_type, $buyCard_price, $buyCard_quantity, $buyCard_code);

        // Thanh toán thành công
        if ($APIBuyCard['status'] == 1) {
            // Dữ liệu card[] rỗng thì chuyển thành đơn chờ vì hệ thống thứ 3 lỗi
            if (empty($APIBuyCard['data']['cards'])) {
                // Tạo hóa đơn
                $sqlOrderCard = "INSERT INTO `buy-card-order` SET
                `user_id`                    = ?,
                `buy-card-order_code`        = ?,
                `buy-card-order_telco`       = ?,
                `buy-card-order_price`       = ?,
                `buy-card-order_quantity`    = ?,
                `buy-card-order_total_pay`   = ?,
                `buy-card-order_api_status`  = ?,
                `buy-card-order_api_message` = ?,
                `updated_api`                = ?,
                `buy-card-order_status`      = 'wait'";
                pdo_execute($sqlOrderCard, [$user_id, $buyCard_code, $buyCard_type, $buyCard_price, $buyCard_quantity, $buyCard_value, $APIBuyCard['status'], $APIBuyCard['message'], getDateTimeNow()]);

                // Trừ tiền
                userCash('sub', $buyCard_value, $user_id, "Muathecao: $buyCard_type - $buyCard_price - $buyCard_quantity - $buyCard_code");

                response(true, 'Đã tạo đơn hàng mua thẻ cào thành công, nhưng lấy thẻ thất bại, vui lòng tải lại sau 2 - 10 phút');
            }

            // Tạo hóa đơn
            $sqlOrderCard = "INSERT INTO `buy-card-order` SET
            `user_id`                    = ?,
            `buy-card-order_code`        = ?,
            `buy-card-order_telco`       = ?,
            `buy-card-order_price`       = ?,
            `buy-card-order_quantity`    = ?,
            `buy-card-order_total_pay`   = ?,
            `buy-card-order_api_status`  = ?,
            `buy-card-order_api_message` = ?,
            `buy-card-order_api_data`    = ?,
            `buy-card-order_order_code`  = ?,
            `updated_api`                = ?,
            `buy-card-order_status`      = 'success'";
            pdo_execute($sqlOrderCard, [$user_id, $buyCard_code, $buyCard_type, $buyCard_price, $buyCard_quantity, $buyCard_value, $APIBuyCard['status'], $APIBuyCard['message'], json_encode($APIBuyCard['data']), $APIBuyCard['data']['order_code'], getDateTimeNow()]);

            foreach ($APIBuyCard['data']['cards'] as $card) {
                $serial   = $card['serial'];
                $pin      = $card['code'];

                $sqlOrderCardData = "INSERT INTO `buy-card-data` SET
                `buy-card-order_code` = ?,
                `buy-card-data_price` = ?,
                `buy-card-data_telco` = ?,
                `buy-card-data_pin`   = ?,
                `buy-card-data_serial` = ?";
                pdo_execute($sqlOrderCardData, [$buyCard_code, $buyCard_price, $buyCard_type,  $pin, $serial]);
            }

            // Trừ tiền
            userCash('sub', $buyCard_value, $user_id, "Muathecao: $buyCard_type - $buyCard_price - $buyCard_quantity - $buyCard_code");

            response(true, 'Đã tạo đơn hàng mua thẻ cào thành công');
        }

        // Thanh toán thành công, Lay the that bai, vui long redownload sau it phut
        if ($APIBuyCard['status'] == 2) {
            // Tạo hóa đơn
            $sqlOrderCard = "INSERT INTO `buy-card-order` SET
            `user_id`                    = ?,
            `buy-card-order_code`        = ?,
            `buy-card-order_telco`       = ?,
            `buy-card-order_price`       = ?,
            `buy-card-order_quantity`    = ?,
            `buy-card-order_total_pay`   = ?,
            `buy-card-order_api_status`  = ?,
            `buy-card-order_api_message` = ?,
            `updated_api`                = ?,
            `buy-card-order_status`      = 'wait'";
            pdo_execute($sqlOrderCard, [$user_id, $buyCard_code, $buyCard_type, $buyCard_price, $buyCard_quantity, $buyCard_value, $APIBuyCard['status'], $APIBuyCard['message'], getDateTimeNow()]);

            // Trừ tiền
            userCash('sub', $buyCard_value, $user_id, "Muathecao: $buyCard_type - $buyCard_price - $buyCard_quantity - $buyCard_code");

            response(true, 'Đã tạo đơn hàng mua thẻ cào thành công, nhưng lấy thẻ thất bại, vui lòng tải lại sau ít phút');
        }

        // Hết hàng
        if ($APIBuyCard['status'] == 127) {
            response(false, "Nhà mạng đang hết hàng, vui lòng chọn mệnh giá khác hoặc thử lại sau ít phút");
        }

        if ($APIBuyCard['status'] != 1 && $APIBuyCard['status'] != 2 && $APIBuyCard['status'] != 127) {
            response(false, $APIBuyCard['message']);
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
