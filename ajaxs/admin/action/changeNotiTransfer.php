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
        $date      = $purifier->purify($data['date']);
        $user_id   = $purifier->purify($data['user_id']);

        // Kiểm tra rỗng
        if (isEmptyOrNull($date)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra người dùng có tồn tại không
        $checkUser = pdo_query_one("SELECT * FROM `user` WHERE `user_id` = ?", [$user_id]);
        if (empty($checkUser)) {
            response(false, 'Người dùng không tồn tại');
        }

        // Kiểm tra ngày rank có hợp lệ không
        if ($date < -1 || $date > 365) {
            response(false, 'Ngày rank không hợp lệ, tối thiểu 1 ngày và tối đa 365 ngày (-1 là tắt)');
        }

        // Thực hiện gia hạn tính năng
        if ($date == -1) {
            pdo_execute("UPDATE `user` SET `expire_noti_transfer` = 0 WHERE `user_id` = ?", [$user_id]);
            response(true, 'Tắt chức năng thông báo discord thành công');
        } else {
            $dateNoti = time() + $date * 24 * 60 * 60;
            pdo_execute("UPDATE `user` SET `expire_noti_transfer` = ? WHERE `user_id` = ?", [$dateNoti, $user_id]);
            response(true, 'Gia hạn thành công');
        }

        response(false, 'Đã có lỗi xảy ra');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
