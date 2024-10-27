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

        $processedData = [];

        // Kiểm tra trạng thá có bảo trì không
        $checkStatusExchangeCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_exchange_card'");
        $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
        if ($checkStatusExchangeCard != 1 || $checkStatusServer != 1) {
            response(false, 'Chức năng đang tạm thời bảo trì!');
        }

        // Kiểm tra dữ liệu trước khi xử lý
        foreach ($data as $row) {
            if (empty($row['telco']) || empty($row['code']) || empty($row['seri']) || empty($row['amount'])) {
                response(false, 'Không được để trống thông tin');
            }

            $telco  = $purifier->purify(trim($row['telco']));
            $code   = $purifier->purify(trim($row['code']));
            $seri   = $purifier->purify(trim($row['seri']));
            $amount = $purifier->purify(trim($row['amount']));

            // Kiểm tra đúng định dạng thẻ
            if (format_card($telco, $seri, $code) === false) {
                response(false, "Định dạng thẻ seri <strong>#$seri</strong> không hợp lệ");
            }

            // Kiểm tra nhà mạng không hoạt động
            if (!telco_status($telco)) {
                response(false, "Nhà mạng <strong>$telco</strong> đang bảo trì, vui lòng gỡ thẻ ra và chạy lại!");
            }
        }

        $hasCardFlase = true; // Mặc định không có thẻ sai
        $listSeriFlase = [];  // Danh sách seri sai

        // Xử lý dữ liệu
        foreach ($data as $row) {

            $telco  = $purifier->purify(trim($row['telco']));
            $code   = $purifier->purify(trim($row['code']));
            $seri   = $purifier->purify(trim($row['seri']));
            $amount = $purifier->purify(trim($row['amount']));

            $request_id = rand_string(11);

            // Kiểm tra telco có trong hệ thống không
            $telco_list = list_telco();
            if (!array_key_exists($telco, $telco_list)) {
                response(false, "Nhà mạng không hợp lệ");
            }

            // Kiểm tra mệnh giá 
            $checkAmount = list_fee_exchange();
            if (!isset($checkAmount[$telco]['member'][$amount])) {
                response(false, "Mệnh giá không hợp lệ");
            }

            // Kiểm tra đúng định dạng thẻ
            if (format_card($telco, $seri, $code) === false) {
                response(false, "Định dạng thẻ không hợp lệ");
            }

            // Kiểm tra thẻ đã tồn tại trong hệ thống chưa
            $check_card_sql = "SELECT * FROM `card-data` WHERE `card-data_code` = ? AND `card-data_seri` = ?";
            if (pdo_query($check_card_sql, [$code, $seri])) {
                response(false, "Thẻ đã tồn tại trong hệ thống
                <br> Mã: <strong>$code</strong> - Seri: <strong>$seri</strong>");
            }

            $user_id        = getIdUser();                                      // Lấy từ TOKEN sau này
            $amount_recieve = getAmountRecieveUser($user_id, $telco, $amount);  // Thực nhận của người dùng (Mệnh giá - Phí chiết khấu theo rank của user)
            $fee            = getFeeExchange($user_id, $telco, $amount);        // Phí đổi thẻ của người dùng (Phí chiết khấu theo rank của user)
            $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác

            // Gửi card lên web mẹ  
            $sendCard = sendCard($telco, $code, $seri, $amount, $request_id);

            if ($sendCard['status'] == 99) {
                // Thêm dữ liệu vào database
                $sql = "INSERT INTO `card-data` SET 
                    `card-data_telco`          = ?,
                    `card-data_code`           = ?,
                    `card-data_seri`           = ?,
                    `card-data_amount`         = ?,
                    `card-data_fee`            = ?,
                    `card-data_amount_recieve` = ?,
                    `user_id`                  = ?,
                    `card-data_server`         = ?,
                    `card-data_request_id`     = ?,
                    `card-data_api_message`    = ?,
                    `card-data_status`         = 'wait'";
                pdo_execute($sql, [$telco, $code, $seri, $amount, $fee, $amount_recieve, $user_id, $partner_server_name, $request_id, $sendCard['message']]);
            } else {
                $hasCardFlase = false;
                $listSeriFlase[] = "Mã: <strong>$code</strong> - Seri: <strong>$seri</strong>";
            }
        }

        if ($hasCardFlase === true) {
            response(true, 'Hệ thống đang xử lý thẻ, hãy tải lại trang');
        } else {
            $listSeriFlase = implode('<br>', $listSeriFlase);
            response(false, "Danh sách thẻ lỗi. <br> $listSeriFlase");
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
