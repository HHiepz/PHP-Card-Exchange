<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request_admin")) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra rỗng
        if (isEmptyOrNull($data['user_id'])) {
            response(false, 'Không được để trống thông tin');
        }

        // Lấy thông tin
        $user_id = $purifier->purify($data['user_id']);

        // Kiểm tra user có tồn tại không
        $checkUser = pdo_query_one("SELECT * FROM `user` WHERE `user_id` = ?", [$user_id]);
        if (empty($checkUser)) {
            response(false, 'Người dùng không tồn tại');
        }

        // Xóa toàn bộ banking của người dùng 
        pdo_execute("DELETE FROM `bank-user` WHERE `user_id` = ?", [$user_id]);

        response(true, 'Xóa tài khoản ngân hàng thành công');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
} else {
    response(false, "Sai phương thức request");
}
