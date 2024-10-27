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
        if (isEmptyOrNull($data['topup-order_request_id'])) {
            response(false, 'Mã đơn hàng không được để trống');
        }

        $request_id = $purifier->purify($data['topup-order_request_id']);

        // Kiểm tra ID có tồn tại
        $checkID = pdo_query_one("SELECT * FROM `topup-order` WHERE `topup-order_request_id` = ?", [$request_id]);
        if (empty($checkID)) {
            response(false, 'Mã đơn không tồn tại');
        }

        // Cập nhật trạng thái đơn hàng
        pdo_execute("UPDATE `topup-order` SET `topup-order_status` = 'completed', `topup-order_cron` = 2 WHERE `topup-order_request_id` = ?", [$request_id]);

        // Refurn tiền
        userCash('add', $checkID['topup-order_pay_amount'], $checkID['user_id'], "Refund đơn nạp tiền điền thoại, mã đơn: $request_id", getIdUser());

        response(true, "Hoàn thành đơn $request_id thành công");
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
