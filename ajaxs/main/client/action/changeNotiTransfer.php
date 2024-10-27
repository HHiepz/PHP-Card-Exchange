<?php
require('../../../../core/database.php');
require('../../../../core/function.php');
require('../../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

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
        $webhook = $purifier->purify(trim($data['webhookTransfer']));
        $user_id = getIdUser();

        // Kiểm tra thời hạn
        $checkTime = pdo_query_value("SELECT `expire_noti_transfer` FROM `user` WHERE `user_id` = ?", [$user_id]);
        if ($checkTime == 0 || $checkTime < time() || $checkTime == null) {
            response(false, 'Hết thời hạn sử dụng tính năng này <br> Vui lòng liên hệ thanh toán để sử dụng tiếp tính năng này');
        }

        // Kiểm tra rỗng
        if (isEmptyOrNull($webhook)) {
            pdo_execute("UPDATE `user` SET `webhook_transfer` = NULL WHERE `user_id` = ?", [$user_id]);
            response(true, 'Đã tắt thông báo nhận tiền');
        }

        // Kiểm tra định dạng $webhook
        if (checkUrl($webhook) == false || (strpos($webhook, 'https://discord.com/api/webhooks/') === false)) {
            response(false, 'Đường dẫn không hợp lệ');
        }

        // Cập nhật thông báo nhận tiền
        pdo_execute("UPDATE `user` SET `webhook_transfer` = ? WHERE `user_id` = ?", [$webhook, $user_id]);
        response(true, 'Cập nhật thông báo nhận tiền thành công');
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
}
