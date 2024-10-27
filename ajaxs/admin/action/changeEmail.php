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
        $emailNew   = $purifier->purify($data['emailNew']);   // Mật khẩu mới
        $user_id    = $purifier->purify($data['user_id']);       // ID người dùng

        // Kiểm tra rỗng
        if (isEmptyOrNull($emailNew) || isEmptyOrNull($user_id)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra định dạng email mới
        if (checkEmail($emailNew) == false) {
            response(false, 'Email không hợp lệ');
        }

        // Kiểm tra email mới có tồn tại không
        $checkEmail = pdo_query_one("SELECT * FROM `user` WHERE `user_email` = ?", [$emailNew]);
        if (!empty($checkEmail)) {
            response(false, 'Email đã tồn tại');
        }

        // Cập nhật Email mới
        pdo_execute("UPDATE `user` SET `user_email` = ? WHERE `user_id` = ?", [$emailNew, $user_id]);

        // Cập nhật token mới
        $user_email = getEmailUser($user_id);
        $tokenNew   = createToken($user_email);
        pdo_execute("UPDATE `user` SET `user_token` = ? WHERE `user_id` = ?", [$tokenNew, $user_id]);

        response(true, 'Cập nhật Email thành công');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
