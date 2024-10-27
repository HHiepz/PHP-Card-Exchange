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
        $phone    = $purifier->purify(trim(filterPhone($data['phone'])));
        $password = $purifier->purify(trim($data['password']));

        $user_id     = getIdUser();                 // ID người dùng từ Cookie
        $user_info   = getInfoUser($user_id);       // Thông tin người dùng từ ID

        // Chống spam lệnh
        if ($user_info['user_has_changePhone'] == 1) {
            response(false, 'Bạn đã sử dụng hết lượt thay đổi số điện thoại, vui lòng liên hệ với bộ phận hỗ trợ để được hỗ trợ thêm');
        }

        // Kiểm tra rỗng 
        if (isEmptyOrNull($phone) || isEmptyOrNull($password)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra định dạng số điện thoại
        if (checkPhone($phone) == false) {
            response(false, 'Số điện thoại không hợp lệ');
        }

        // Kiểm tra số điện thoại có tồn tại chưa?
        $user = pdo_query_one("SELECT * FROM `user` WHERE `user_phone` = ?", [$phone]);
        if (empty($user)) {
            $passwordSql = $user_info['user_password']; // Mật khẩu trong database
            if (password_verify($password, $passwordSql)) {
                // Ghi logs
                insertLogs($user_id, 'changePhone', "Thay đổi số điện thoại từ " . $user_info['user_phone'] . " thành " . $phone);

                // Cập nhật số điện thoại mới
                pdo_execute("UPDATE `user` SET `user_phone` = ? WHERE `user_id` = ?", [$phone, $user_id]);
                pdo_execute("UPDATE `user` SET `user_has_changePhone` = ? WHERE `user_id` = ?", [1, $user_id]);
                response(true, 'Cập nhật số điện thoại thành công');
            } else {
                response(false, 'Mật khẩu không chính xác');
            }
        } else {
            response(false, 'Số điện thoại đã tồn tại');
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
