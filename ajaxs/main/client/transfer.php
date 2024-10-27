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
        $cash        = $purifier->purify(trim($data['transfer_cash']));
        $description = $purifier->purify(trim($data['transfer_description']));
        // Kiểm tra rỗng
        if (isEmptyOrNull($email) || isEmptyOrNull($cash)) {
            response(false, 'Vui lòng nhập đầy đủ thông tin');
        }

        // Chống Spam lệnh
        $user_info = getInfoUser(getIdUser());
        if ($user_info['user_last_transfer'] > (time() - 10)) {
            response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
        } else {
            pdo_execute("UPDATE `user` SET `user_last_transfer` = ? WHERE `user_id` = ?", [time(), getIdUser()]);
        }

        // Kiểm tra số tiền rút tối thiểu
        $min_transfer = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "min_transfer"');
        if ($cash < $min_transfer) {
            response(false, "Chuyển tối thiểu " . number_format($min_transfer) . " VND");
        }
        // response(false, 'Run here');

        // Kiểm tra số tiền rút tối đa
        $max_transfer = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "max_transfer"');
        if ($cash > $max_transfer) {
            response(false, "Chuyển tối đa " . number_format($max_transfer) . " VND");
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

        // Kiểm tra số dư
        $balance = pdo_query_value('SELECT `user_cash` FROM `user` WHERE `user_id` = ?', [getIdUser()]);
        if ($cash > $balance) {
            response(false, 'Số dư không đủ');
        }

        // Ghi nhận chuyển tiền
        $user_from     = getIdUser();                                  // ID người dùng
        $request_id    = 'TF' . rand_string();                         // Mã giao dịch
        $user_to       = $checkUser;                                   // ID người nhận
        $sql = "INSERT INTO `transfer` SET
            `transfer_code`       = ?,
            `transfer_user_from`  = ?,
            `transfer_cash`       = ?,
            `transfer_user_to`    = ?,
            `transfer_description`= ?
        ";
        pdo_execute($sql, [$request_id, $user_from, $cash, $user_to, $description]);

        // Trừ tiền người gửi
        userCash('sub', $cash, $user_from, "Chuyển tiền, mã giao dịch: $request_id");

        // Cộng tiền người nhận
        userCash('add', $cash, $user_to, "Chuyển tiền, mã giao dịch: $request_id");

        response(true, "Chuyển tiền thành công. [$email] đã nhận được tiền", null, true);

        // Gửi thông báo discord
        $user_info = getInfoUser($user_to);
        $checkTime = $user_info['expire_noti_transfer'];
        if ($checkTime != 0 && $checkTime >= time() && $checkTime != null) {
            $webhook = $user_info['webhook_transfer'];
            if (!empty($webhook)) {
                $description = empty($description) ? "Không có nội dung" : $description;
                $disc_message = createShopTransfer($cash, $description, getEmailUser($user_from));
                sendDiscord($webhook, $disc_message);
            }
        }
        exit;
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
