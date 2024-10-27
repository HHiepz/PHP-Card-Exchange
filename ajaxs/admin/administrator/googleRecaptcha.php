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

        // Kiểm tra rỗng 
        if (isEmptyOrNull($data['ggRecaptcha_siteKey']) || isEmptyOrNull($data['ggRecaptcha_secretKey'])) {
            response(false, 'Vui lòng nhập đầy đủ thông tin');
        }

        $ggRecaptcha_status    = $purifier->purify($data['ggRecaptcha_status']);
        $ggRecaptcha_siteKey   = $purifier->purify($data['ggRecaptcha_siteKey']);
        $ggRecaptcha_secretKey = $purifier->purify($data['ggRecaptcha_secretKey']);

        (isEmptyOrNull($ggRecaptcha_status)) ? $ggRecaptcha_status = 0 : $ggRecaptcha_status = 1;


        // Cập nhật thông thông tin Google Recaptcha
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'status_ggRecaptcha'", [$ggRecaptcha_status]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'ggRecaptcha_site_key'", [$ggRecaptcha_siteKey]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'ggRecaptcha_secret_key'", [$ggRecaptcha_secretKey]);

        response(true, 'Cập nhật thông tin thành công');
    }
}
