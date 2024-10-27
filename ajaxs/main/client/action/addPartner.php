<?php
require('../../../../core/database.php');
require('../../../../core/function.php');
require('../../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request")) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }


        // Lọc dữ liệu đầu vào
        $partner_type      = $purifier->purify(trim($data['api_type']));
        $partner_action    = $purifier->purify(trim($data['api_action']));
        $partner_callback  = $purifier->purify(trim($data['api_callback']));
        $partner_ip        = $purifier->purify(trim($data['api_ip']));
        $user_id           = getIdUser();                               // ID người dùng

        // Kiểm tra rỗng
        if (isEmptyOrNull($partner_type) || isEmptyOrNull($partner_action)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra định dạng $partner_callback
        if (!empty($partner_callback) && checkUrl($partner_callback) == false) {
            response(false, 'Đường dẫn nhận dữ liệu không hợp lệ');
        }

        // Kiểm tra định dạng partner_type
        if (!in_array($partner_type, ['Charging', 'BuyCard', 'Withdraw', 'Transfer'])) {
            response(false, 'Loại API không hợp lệ');
        }

        // Kiểm tra định dạng partner_action
        if (!in_array($partner_action, ['GET', 'POST'])) {
            response(false, 'Phương thức API không hợp lệ');
        }

        // Kiểm tra giới hạn số API
        $apiCount = pdo_query_value('SELECT COUNT(`user_id`) FROM `partner` WHERE `user_id` = ?', [$user_id]);  // Đếm số API của người dùng
        $max_api_partner = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "max_api_partner"');   // Số API tối đa hệ thống cho phép
        if ($apiCount >= 10) {
            response(false, 'Bạn đã đạt giới hạn số API');
        }

        // Thêm API
        $partner_key = rand_string(32);
        $partner_id  = rand_number(10);

        $sql = "INSERT INTO `partner` SET
            `user_id`           = ?,
            `partner_id`        = ?,
            `partner_key`       = ?,
            `partner_type`      = ?,
            `partner_callback`  = ?,
            `partner_action`    = ?,
            `partner_ip`        = ?
        ";
        pdo_execute($sql, [$user_id, $partner_id, $partner_key, $partner_type, $partner_callback, $partner_action, $partner_ip]);

        response(true, 'Thêm API thành công');
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
