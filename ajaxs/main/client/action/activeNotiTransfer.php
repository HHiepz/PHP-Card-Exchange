<?php
require('../../../../core/database.php');
require('../../../../core/function.php');
require('../../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request")) {
        // Lọc dữ liệu đầu vào
        $user_id = getIdUser();

        // Kiểm tra thời hạn
        $checkTime = pdo_query_value("SELECT `expire_noti_transfer` FROM `user` WHERE `user_id` = ?", [$user_id]);
        if ($checkTime == 0 || $checkTime < time() || $checkTime == null) {
            define('FEE', 1000);
            define('DATE', 30);
            define('NOT_ENOUGH', 'Số dư của bạn không đủ để kích hoạt tính năng này <br> Vui lòng nạp thêm tiền vào tài khoản');
            define('SUCCESS', 'Kích hoạt tính năng thành công. Bạn có thể sử dụng tính năng này trong vòng 30 ngày');


            // Kiểm tra số dư
            $balance = pdo_query_value("SELECT `user_cash` FROM `user` WHERE `user_id` = ?", [$user_id]);
            if ($balance < FEE) {
                response(false, NOT_ENOUGH);
            }

            // Trừ tiền
            userCash("sub", 1000, $user_id, "Kích hoạt tính năng thông báo nhận tiền discord", -1);

            // Cập nhật thời hạn
            $time = time() + DATE * 24 * 60 * 60;
            pdo_execute("UPDATE `user` SET `expire_noti_transfer` = ? WHERE `user_id` = ?", [$time, $user_id]);
            response(true, SUCCESS);
        } else {
            response(false, "Bạn vẫn còn hạn sử dụng tính năng này <br> Vui lòng quay lại sau khi hết hạn sử dụng");
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
}
