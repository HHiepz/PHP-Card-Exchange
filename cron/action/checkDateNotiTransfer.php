<?php
// NOTE DEV: Tự động tắt tính năng thông báo nhận tiền khi hết hạn
require('../../core/function.php');
require('../../core/database.php');

$listUser = pdo_query("SELECT * FROM `user` WHERE `expire_noti_transfer` != 0");
$total = 0;

foreach ($listUser as $user) {
    // Nếu thời gian hiện tại lớn hơn thời gian hết hạn
    if (time() > $user['expire_noti_transfer']) {
        // Thực hiện set expire_noti_transfer = 0 để không thể sử dụng tính năng này
        pdo_execute("UPDATE `user` SET `expire_noti_transfer` = 0 WHERE `user_id` = ?", [$user['user_id']]);
        $total++;
    }
}

jsonReturn(true, "Tổng cộng đã tắt {$total} thông báo nhận tiền khi hết hạn thành công");
