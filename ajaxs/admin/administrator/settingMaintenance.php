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

        // Cập nhật thông báo ĐỔI THẺ discord
        if (isset($data['doithe_muathe'])) {
            $doithe = $purifier->purify($data['baotri_doithe']);
            $muathe = $purifier->purify($data['baotri_muathe']);
            $ruttien = $purifier->purify($data['baotri_ruttien']);
            $chuyentien = $purifier->purify($data['baotri_chuyentien']);


            (isEmptyOrNull($doithe)) ? $doithe = 1 : $doithe = 0;
            (isEmptyOrNull($muathe)) ? $muathe = 1 : $muathe = 0;
            (isEmptyOrNull($ruttien)) ? $ruttien = 1 : $ruttien = 0;
            (isEmptyOrNull($chuyentien)) ? $chuyentien = 1 : $chuyentien = 0;

            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'status_exchange_card'", [$doithe]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'status_buyCard'", [$muathe]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'status_withdraw'", [$ruttien]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'status_transfer'", [$chuyentien]);

            response(true, 'Cập nhật thông báo thành công');
        }

        if (isset($data['server'])) {
            $server = $purifier->purify($data['baotri_server']);

            (isEmptyOrNull($server)) ? $server = 1 : $server = 0;

            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'status_server'", [$server]);
            response(true, 'Cập nhật thông báo thành công');
        }

        if(isset($data['chucnang'])) {
            $registerVerifyEmail = $purifier->purify($data['chucnang_registerVerifyEmail']);
            $registerVerifyIp    = $purifier->purify($data['chucnang_registerVerifyIp']);

            (isEmptyOrNull($registerVerifyEmail)) ? $registerVerifyEmail = 0 : $registerVerifyEmail = 1;
            (isEmptyOrNull($registerVerifyIp)) ? $registerVerifyIp = 0 : $registerVerifyIp = 1;

            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'register_verify_email'", [$registerVerifyEmail]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'register_verify_ip'", [$registerVerifyIp]);
            response(true, 'Cập nhật chức năng thành công');
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
