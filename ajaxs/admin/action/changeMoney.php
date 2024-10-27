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

        // Kiểm tra dữ liệu data phải là kiểu mảng
        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Lọc dữ liệu đầu vào
        $money      = $purifier->purify($data['money']);
        $reason     = $purifier->purify($data['reason']);
        $user_id    = $purifier->purify($data['user_id']);
        $action     = $purifier->purify($data['action']);

        // Kiểm tra rỗng
        if (isEmptyOrNull($money) || isEmptyOrNull($user_id) || isEmptyOrNull($action)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra số tiền có phải là số không
        if (!is_numeric($money)) {
            response(false, 'Số tiền phải là kiểu số');
        }

        // Kiểm tra số tiền có phải là số không
        if ($money <= 0) {
            response(false, 'Số tiền phải lớn hơn 0');
        }

        // Kiểm tra hành động
        if ($action != "add" && $action != "sub") {
            response(false, 'Hành động không hợp lệ');
        }

        // Kiểm tra người dùng có tồn tại không
        $checkUser = pdo_query_one("SELECT * FROM `user` WHERE `user_id` = ?", [$user_id]);
        if (empty($checkUser)) {
            response(false, 'Người dùng không tồn tại');
        }

        // Thực hiện thay đổi tiền
        userCash($action, $money, $user_id, $reason, getIdUser());

        response(true, 'Thay đổi tiền thành công');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
