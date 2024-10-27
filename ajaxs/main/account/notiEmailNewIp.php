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
        $password = $purifier->purify(trim($data['password']));

        // Kiểm tra rỗng
        if (isEmptyOrNull($password)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra mật khẩu cũ có chính xác không
        $user_id     = getIdUser();                 // ID người dùng từ Cookie
        $user_info   = getInfoUser($user_id);       // Thông tin người dùng từ ID
        $passwordSql = $user_info['user_password']; // Mật khẩu trong database
        if (password_verify($password, $passwordSql)) {

            // Kiểm tra xác minh email chưa
            if ($user_info['user_verify_email'] == 0) {
                response(false, 'Bạn chưa xác minh email!');
            }

            if ($user_info['noti_email_login'] == 1) {
                pdo_execute("UPDATE `user` SET `noti_email_login` = 0 WHERE `user_id` = ?", [$user_id]);
                response(true, 'Tắt thông báo thành công! -1 bảo mật');
            } else {
                pdo_execute("UPDATE `user` SET `noti_email_login` = 1 WHERE `user_id` = ?", [$user_id]);
                response(true, 'Bật thông báo thành công! +1 bảo mật');
            }

            response(false, 'Lỗi không xác định');
        } else {
            response(false, 'Mật khẩu không chính xác');
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
