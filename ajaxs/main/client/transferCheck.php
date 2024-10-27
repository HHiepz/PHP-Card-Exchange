<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    if (checkToken('request')) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra trạng thái bảo trì
        $checkStatusTransfer = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "status_transfer"');
        $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
        if ($checkStatusTransfer != 1 || $checkStatusServer != 1) {
            response(false, 'Chức năng đang tạm thời bảo trì');
        }

        // Lọc dữ liệu đầu vào
        $email       = $purifier->purify(trim($data['transfer_email']));

        // Kiểm tra rỗng
        if (isEmptyOrNull($email)) {
            response(false, 'Vui lòng nhập đầy đủ thông tin');
        }

        // Chống Spam lệnh
        $user_info = getInfoUser(getIdUser());
        if ($user_info['user_last_transfer'] > (time() - 10)) {
            response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
        } else {
            pdo_execute("UPDATE `user` SET `user_last_transfer` = ? WHERE `user_id` = ?", [time(), getIdUser()]);
        }

        // Kiểm tra email or Phone nhận
        $checkUser = pdo_query_value("SELECT `user_id` FROM `user` WHERE `user_email` = ?", [$email]);
        if (empty($checkUser)) {
            $checkUser = pdo_query_value("SELECT `user_id` FROM `user` WHERE `user_phone` = ?", [$email]);
            if (empty($checkUser)) {
                $checkUser = pdo_query_value("SELECT `user_id` FROM `user` WHERE `user_fullname` = ?", [$email]);
                if (empty($checkUser)) {
                    response(false, 'Email nhận hoặc số điện thoại không tồn tại');
                }
            }
        }
        if ($checkUser == getIdUser()) {
            response(false, 'Không thể chuyển tiền cho chính mình');
        }

        $domain = getDomain();
        response(true, 'Chuyển hướng', "$domain/details/transferUser/$email");
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
