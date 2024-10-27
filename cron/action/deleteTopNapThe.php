<?php
require('../../core/function.php');
require('../../core/database.php');

// Đặt thời gian mặc định là Asia/Ho_Chi_Minh
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Xóa dữ liệu trong bản top tháng trước
$month = date('m') - 1;
if ($month == 0) {
    $month = 12;
}
pdo_execute("DELETE FROM `top` WHERE `month` = ?", [$month]);
