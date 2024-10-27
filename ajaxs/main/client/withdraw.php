<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    if (checkToken('request')) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra trạng thái bảo trì
        $checkStatusWithdraw = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "status_withdraw"');
        $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
        if ($checkStatusWithdraw != 1 || $checkStatusServer != 1) {
            response(false, 'Chức năng đang tạm thời bảo trì');
        }


        // Kiểm tra rỗng
        if (isEmptyOrNull($data['withdraw_bank_key']) || isEmptyOrNull($data['withdraw_cash'])) {
            response(false, 'Không được để trống thông tin');
        }

        // Lọc dữ liệu đầu vào
        $bank_key     = $purifier->purify(trim($data['withdraw_bank_key']));
        $cash         = $purifier->purify(trim($data['withdraw_cash']));


        // Chống Spam lệnh
        $user_info = getInfoUser(getIdUser());
        if ($user_info['user_last_withdraw'] > (time() - 10)) {
            response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
        } else {
            pdo_execute("UPDATE `user` SET `user_last_withdraw` = ? WHERE `user_id` = ?", [time(), getIdUser()]);
        }

        // Kiểm tra số tiền rút tối thiểu
        $min_withdraw = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "min_withdraw"');
        if ($cash < $min_withdraw) {
            response(false, "Rút tối thiểu " . number_format($min_withdraw) . " VND");
        }

        // Kiểm tra số tiền rút tối đa
        $max_withdraw = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "max_withdraw"');
        if ($cash > $max_withdraw) {
            response(false, "Rút tối đa " . number_format($max_withdraw) . " VND");
        }

        // Kiểm tra số dư
        $fee_withdraw = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "fee_withdraw"');
        $balance = pdo_query_value('SELECT `user_cash` FROM `user` WHERE `user_id` = ?', [getIdUser()]);
        if (($cash + $fee_withdraw) > $balance) {
            response(false, 'Số dư không đủ');
        }

        // Kiểm tra key tài khoản ngân hàng
        $checkBankKey = pdo_query_one('SELECT * FROM `bank-user` WHERE `bank-user_key` = ? AND `user_id` = ?', [$bank_key, getIdUser()]);
        if (empty($checkBankKey)) {
            response(false, 'Key tài khoản ngân hàng không hợp lệ');
        }


        // Kiểm tra trạng thái ngân hàng có báo trì không?
        if (checkBankStatus($checkBankKey['bank-user_name']) == 0) {
            response(false, 'Ngân hàng đang bảo trì, vui lòng chọn ngân hàng khác');
        }


        // Kiểm tra hạn mức rút momo
        if ($checkBankKey['bank-user_name'] == 'MOMO') {
            // Set the default timezone to Vietnam
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $momo_limit = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "momo_limit"');

            // Kiểm tra hạn mức rút tiền qua MOMO
            if ($cash > $momo_limit) {
                response(false, 'Hạn mức rút tiền qua MOMO là ' . number_format($momo_limit) . ' VND');
            }

            // Kiểm tra toàn bộ đơn trong ngày đã rút bao nhiêu có vượt quá hạn mức không?
            $total_withdraw = pdo_query_value('SELECT SUM(`wd_cash`) FROM `withdraw` WHERE `user_id` = ? AND UNIX_TIMESTAMP(`created_at`) >= ? AND `wd_bank_name` = "MOMO" AND `wd_status` IN ("success", "hold", "wait", "pending")', [getIdUser(), strtotime('today midnight')]);
            if (($total_withdraw + $cash) > $momo_limit) {
                response(false, 'Hạn mức rút tiền qua MOMO là ' . number_format($momo_limit) . ' VND');
            }
        }



        // Ghi nhận rút tiền
        $user_id          = getIdUser();                                                   // ID người dùng
        $request_id       = 'WD' . rand_string();                                          // Mã giao dịch
        $bank_name        = $checkBankKey['bank-user_name'];                               // Tên ngân hàng
        $bank_owner       = $checkBankKey['bank-user_owner'];                              // Chủ tài khoản
        $bank_description = "CARD2K $request_id";                                          // Nội dung
        $bank_number      = $checkBankKey['bank-user_number_account'];                     // Số tài khoản
        $sql = "INSERT INTO `withdraw` SET
            `user_id`           = ?,
            `wd_code`           = ?,
            `wd_bank_name`      = ?,
            `wd_bank_owner`     = ?,
            `wd_number_account` = ?,
            `wd_cash`           = ?,
            `wd_description`    = ?,
            `wd_status`         = 'wait'
        ";
        pdo_execute($sql, [$user_id, $request_id, $bank_name, $bank_owner, $bank_number, $cash, $bank_description]);

        // Trừ tiền
        userCash('sub', ($cash + $fee_withdraw), $user_id, "Rút tiền, mã giao dịch: $request_id");

        response(true, 'Yêu cầu rút tiền thành công');
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
