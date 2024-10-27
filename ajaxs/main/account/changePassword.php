<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

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
        $passwordOld = $purifier->purify(trim($data['passwordOld']));
        $passwordNew = $purifier->purify(trim($data['passwordNew']));
        $passwordAgain = $purifier->purify(trim($data['passwordAgain']));

        // Kiểm tra rỗng
        if (isEmptyOrNull($passwordOld) || isEmptyOrNull($passwordNew) || isEmptyOrNull($passwordAgain)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra mật khẩu mới có trùng khớp không
        if ($passwordNew !== $passwordAgain) {
            response(false, 'Mật khẩu mới không trùng khớp');
        }

        // Kiểm tra định dạng mật khẩu mới
        if (checkPassword($passwordNew) == false) {
            response(false, 'Mật khẩu mới phải từ 6 đến 32 ký tự');
        }

        // Kiểm tra mật khẩu cũ có chính xác không
        $user_id     = getIdUser();                 // ID người dùng từ Cookie
        $user_info   = getInfoUser($user_id);       // Thông tin người dùng từ ID
        $passwordSql = $user_info['user_password']; // Mật khẩu trong database

        if (password_verify($passwordOld, $passwordSql)) {
            // Ghi logs
            insertLogs($user_id, 'changePassword', "Thay đổi mật khẩu");

            // Cập nhật mật khẩu mới
            pdo_execute("UPDATE `user` SET `user_password` = ? WHERE `user_id` = ?", [password_hash($passwordNew, PASSWORD_DEFAULT), $user_id]);
            response(true, 'Cập nhật mật khẩu thành công');
        } else {
            response(false, 'Mật khẩu cũ không chính xác');
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
