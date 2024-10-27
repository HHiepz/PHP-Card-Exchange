<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request_admin")) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra rỗng
        if (isEmptyOrNull($data['withdraw_max']) || isEmptyOrNull($data['withdraw_min']) || isEmptyOrNull($data['withdraw_fee']) || isEmptyOrNull($data['withdraw_api']) || isEmptyOrNull($data['withdraw_momo_limit'])) {
            response(false, 'Vui lòng nhập đầy đủ thông tin');
        }

        // Lọc dữ liệu đầu vào
        $withdraw_max    = $purifier->purify($data['withdraw_max']);                     // Rút tối đa
        $withdraw_min    = $purifier->purify($data['withdraw_min']);                     // Rút tối thiểu
        $withdraw_fee    = $purifier->purify($data['withdraw_fee']);                     // Phí rút
        $withdraw_api    = $purifier->purify($data['withdraw_api']);                     // API-KEY-BANK
        $withdraw_momo_limit    = $purifier->purify($data['withdraw_momo_limit']);       // Giới hạn rút momo

        // Fortmat lại số
        $withdraw_max = str_replace(',', '', $withdraw_max);
        $withdraw_min = str_replace(',', '', $withdraw_min);
        $withdraw_fee = str_replace(',', '', $withdraw_fee);
        $withdraw_momo_limit = str_replace(',', '', $withdraw_momo_limit);

        // Cập nhật cài đặt
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'max_withdraw'", [$withdraw_max]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'min_withdraw'", [$withdraw_min]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'fee_withdraw'", [$withdraw_fee]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'partner_key_withdraw'", [$withdraw_api]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'momo_limit'", [$withdraw_momo_limit]);

        response(true, 'Cập nhật cài đặt rút tiền thành công!');
    }
}
