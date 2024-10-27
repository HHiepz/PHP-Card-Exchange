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
        $user_id    = $purifier->purify($data['user_id']);       // ID người dùng


        // Kiểm tra rỗng
        if (isEmptyOrNull($user_id)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra user có tồn tại không
        $checkUser = pdo_query_one("SELECT * FROM `user` WHERE `user_id` = ?", [$user_id]);
        if (empty($checkUser)) {
            response(false, 'Người dùng không tồn tại');
        }

        // Kiểm tra user có phải là admin không
        if ($checkUser['user_admin'] == 1) {
            response(false, 'Không thể khóa tài khoản admin');
        }

        // Kiểm tra user có đang bị khóa không
        if ($checkUser['user_banned'] == 1) {
            response(false, 'Tài khoản này đã bị khóa');
        }

        // Kiểm tra có phải là chính mình không
        if ($checkUser['user_id'] == getIdUser()) {
            response(false, 'Không thể khóa chính mình');
        }

        // Khóa tài khoản
        $reason = "Khóa tài khoản vào lúc " . getDateTimeNow() . " bởi " . getEmailUser(getIdUser());
        pdo_execute("UPDATE `user` SET `user_banned` = 1, `user_banned_reason` = ? WHERE `user_id` = ?", [$reason, $user_id]);

        response(true, 'Khóa tài khoản thành công');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
