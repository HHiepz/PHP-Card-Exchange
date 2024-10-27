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


        // Lọc dữ liệu đầu vào
        $bank_key    = $purifier->purify($data['bank_key']);       // Tên ngân hàng


        // Kiểm tra rỗng
        if (isEmptyOrNull($bank_key)) {
            response(false, 'Không được để trống thông tin');
        }


        // Kiểm tra $bank_key có tồn tại trên hệ thống không?
        $checkBank = pdo_query_one("SELECT * FROM `bank-user` WHERE `bank-user_key` = ? AND `user_id` = ?", [$bank_key, getIdUser()]);
        if (empty($checkBank)) {
            response(false, 'Tài khoản ngân hàng không tồn tại');
        }
        

        // Xóa tài khoản ngân hàng
        $sql = "DELETE FROM `bank-user` WHERE `bank-user_key` = ? AND `user_id` = ?";
        pdo_execute($sql, [$bank_key, getIdUser()]);
        response(true, 'Xóa tài khoản ngân hàng thành công');
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
