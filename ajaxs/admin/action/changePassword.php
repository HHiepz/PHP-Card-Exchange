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
        $passwordNew   = $purifier->purify($data['passwordNew']);   // Mật khẩu mới
        $user_id       = $purifier->purify($data['user_id']);       // ID người dùng

        // Kiểm tra rỗng
        if (isEmptyOrNull($passwordNew) || isEmptyOrNull($user_id)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra định dạng mật khẩu mới
        if (checkPassword($passwordNew) == false) {
            response(false, 'Mật khẩu mới phải từ 6 đến 32 ký tự');
        }


        // Cập nhật mật khẩu mới
        pdo_execute("UPDATE `user` SET `user_password` = ? WHERE `user_id` = ?", [password_hash($passwordNew, PASSWORD_DEFAULT), $user_id]);

        // Cập nhật token mới
        $user_email = getEmailUser($user_id);
        $tokenNew   = createToken($user_email);
        pdo_execute("UPDATE `user` SET `user_token` = ? WHERE `user_id` = ?", [$tokenNew, $user_id]);

        response(true, 'Cập nhật mật khẩu thành công');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
