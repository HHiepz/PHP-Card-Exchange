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
        if (isset($data['exchange_noti_disc'])) {
            $value = $purifier->purify($data['exchange_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_exchange_card'", [$value]);
            response(true, 'Cập nhật thông báo ĐỔI THẺ discord thành công!');
        }

        // Cập nhật thông báo DÒNG TIỀN discord
        if (isset($data['money_noti_disc'])) {
            $value = $purifier->purify($data['money_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_money'", [$value]);
            response(true, 'Cập nhật thông báo DÒNG TIỀN discord thành công!');
        }

        // Cập nhật thông báo ĐĂNG NHẬP discord
        if (isset($data['login_noti_disc'])) {
            $value = $purifier->purify($data['login_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_login'", [$value]);
            response(true, 'Cập nhật thông báo ĐĂNG NHẬP discord thành công!');
        }

        // Cập nhật thông báo ĐĂNG KÝ discord
        if (isset($data['register_noti_disc'])) {
            $value = $purifier->purify($data['register_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_register'", [$value]);
            response(true, 'Cập nhật thông báo ĐĂNG KÝ discord thành công!', $value);
        }

        // Cập nhật thông báo RÚT TIỀN discord
        if (isset($data['withdraw_noti_disc'])) {
            $value = $purifier->purify($data['withdraw_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_withdraw'", [$value]);
            response(true, 'Cập nhật thông báo RÚT TIỀN discord thành công!');
        }

        // Cập nhật thông báo CHIẾT KHẤU THẤP NHẤT discord
        if (isset($data['min_telco_rare_noti_disc'])) {
            $value = $purifier->purify($data['min_telco_rare_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_min_telco_rare'", [$value]);
            response(true, 'Cập nhật thông báo CHIẾT KHẤU THẤP NHẤT discord thành công!');
        }

        // Cập nhật thông báo TỔNG KẾT discord
        if (isset($data['tongKet_noti_disc'])) {
            $value = $purifier->purify($data['tongKet_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_tongKet'", [$value]);
            response(true, 'Cập nhật thông báo TỔNG KẾT discord thành công!');
        }

        // Cập nhật thông báo BACKUP discord 
        if (isset($data['backup_noti_disc'])) {
            $value = $purifier->purify($data['backup_noti_disc_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'webhook_backup'", [$value]);
            response(true, 'Cập nhật thông báo BACKUP discord thành công!');
        }

        // Cập nhật thông báo EMAIL
        if (isset($data['email_noti'])) {
            $email_name = $purifier->purify($data['email_name']);
            $email_pass = $purifier->purify($data['email_pass']);

            if (isEmptyOrNull($email_name) || isEmptyOrNull($email_pass)) {
                response(false, 'Vui lòng nhập đầy đủ thông tin');
            }

            if (checkEmail($email_name) == false) {
                response(false, 'Email không hợp lệ');
            }

            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'email'", [$email_name]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'email_password'", [$email_pass]);
            response(true, 'Cập nhật thông báo EMAIL thành công!');
        }

        // Cập nhật thông báo HIỂN THỊ - INDEX
        if (isset($data['index_noti'])) {
            $value = $purifier->purify($data['index_noti_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'noti_index'", [$value]);
            response(true, 'Cập nhật thông báo HIỂN THỊ - INDEX thành công!');
        }

        // Cập nhật thông báo HIỂN THỊ - WITHDRAW
        if (isset($data['withdraw_noti'])) {
            $value = $purifier->purify($data['withdraw_noti_value']);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'noti_withdraw'", [$value]);
            response(true, 'Cập nhật thông báo HIỂN THỊ - WITHDRAW thành công!');
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
