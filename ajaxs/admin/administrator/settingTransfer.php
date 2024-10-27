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
        $transfer_max    = $purifier->purify($data['transfer_max']);       // Rút tối đa
        $transfer_min    = $purifier->purify($data['transfer_min']);       // Rút tối thiểu

        // Kiểm tra rỗng
        if (isEmptyOrNull($transfer_max) || isEmptyOrNull($transfer_min)) {
            response(false, 'Vui lòng nhập đầy đủ thông tin');
        }

        // Fortmat lại số
        $transfer_max = str_replace(',', '', $transfer_max);
        $transfer_min = str_replace(',', '', $transfer_min);

        // Cập nhật cài đặt
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'max_transfer'", [$transfer_max]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'min_transfer'", [$transfer_min]);

        response(true, 'Cập nhật cài đặt chuyển tiền thành công!');
    }
}
