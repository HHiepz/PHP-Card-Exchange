<?php
require('../../../../core/database.php');
require('../../../../core/function.php');
require('../../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request")) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra rỗng
        if (isEmptyOrNull($data['bank_name']) || isEmptyOrNull($data['number_account']) || isEmptyOrNull($data['owner'])) {
            response(false, 'Không được để trống thông tin');
        }

        // Lọc dữ liệu đầu vào
        $bank_name    = $purifier->purify(trim($data['bank_name']));
        $bank_number  = $purifier->purify(trim($data['number_account']));
        $bank_account = $purifier->purify(trim($data['owner']));


        // Kiểm tra định dạng $bank_name
        if (checkBankName($bank_account) == false) {
            response(false, 'Chủ tài khoản hãy ghi đúng định dạng <br> <strong>(In hoa & không dấu)</strong>');
        }


        // Kiểm tra $bank_number có phải là số không?
        if (checkBankNumber($bank_number) == false) {
            response(false, 'Số tài khoản không hợp lệ <br> <strong>(Chỉ được nhập số)</strong>');
        }


        // Kiểm tra $bank_name có nằm trong danh sách ngân hàng không?
        if (!in_array($bank_name, list_bank("codeDB"))) {
            response(false, 'Tên ngân hàng không hợp lệ');
        }
        
        
        // Kiểm tra bank có bảo trì không?
        if (checkBankStatus($bank_name) == 0) {
            response(false, 'Ngân hàng đang bảo trì, vui lòng chọn ngân hàng khác');
        }


        // Kiểm tra giới hạn số tài khoản
        $accountCount = pdo_query_value('SELECT COUNT(`bank-user_id`) FROM `bank-user` WHERE `user_id` = ?', [getIdUser()]);
        if ($accountCount >= 10) {
            response(false, 'Bạn đã đạt giới hạn số tài khoản');
        }


        // Thêm tài khoản ngân hàng
        $bank_user_key = md5(uniqid());        // Key tài khoản ngân hàng
        $user_id       = getIdUser();          // ID người dùng


        $sql = "INSERT INTO `bank-user` SET
            `user_id`                    = ?,
            `bank-user_key`              = ?,
            `bank-user_number_account`   = ?,
            `bank-user_owner`            = ?,
            `bank-user_name`             = ?
        ";
        pdo_execute($sql, [$user_id, $bank_user_key, $bank_number, strtoupper($bank_account), $bank_name]);

        response(true, 'Thêm tài khoản ngân hàng thành công');
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
