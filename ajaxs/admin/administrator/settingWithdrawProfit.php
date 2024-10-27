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
        if (isEmptyOrNull($data['webhook_withdraw_profit']) || isEmptyOrNull($data['bank_code_withdraw_profit']) || isEmptyOrNull($data['account_number_withdraw_profit']) || isEmptyOrNull($data['account_owner_withdraw_profit']) || isEmptyOrNull($data['role_withdraw_profit'])) {
            response(false, 'Không được để trống thông tin');
        }

        // Lọc dữ liệu đầu vào
        $webhook_withdraw_profit = $purifier->purify($data['webhook_withdraw_profit']);
        $bank_code_withdraw_profit = $purifier->purify($data['bank_code_withdraw_profit']);
        $account_number_withdraw_profit = $purifier->purify($data['account_number_withdraw_profit']);
        $account_owner_withdraw_profit = $purifier->purify($data['account_owner_withdraw_profit']);
        $role_withdraw_profit = $purifier->purify($data['role_withdraw_profit']);

        // Kiểm tra định dạng $bank_name
        if (checkBankName($account_owner_withdraw_profit) == false) {
            response(false, 'Chủ tài khoản hãy ghi đúng định dạng <br> <strong>(In hoa & không dấu)</strong>');
        }


        // Kiểm tra $bank_number có phải là số không?
        if (checkBankNumber($account_number_withdraw_profit) == false) {
            response(false, 'Số tài khoản không hợp lệ <br> <strong>(Chỉ được nhập số)</strong>');
        }


        // Kiểm tra $bank_name có nằm trong danh sách ngân hàng không?
        if (!in_array($bank_code_withdraw_profit, list_bank("codeDB"))) {
            response(false, 'Tên ngân hàng không hợp lệ');
        }



        // Cập nhật cài đặt
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_withdraw_profit'", [$webhook_withdraw_profit]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'bank_code_withdraw_profit'", [$bank_code_withdraw_profit]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'account_number_withdraw_profit'", [$account_number_withdraw_profit]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'account_owner_withdraw_profit'", [$account_owner_withdraw_profit]);
        pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'role_withdraw_profit'", [$role_withdraw_profit]);

        response(true, 'Cập nhật cài đặt thành công');
    }
} else {
    response(false, 'Phương thức không hợp lệ');
}
