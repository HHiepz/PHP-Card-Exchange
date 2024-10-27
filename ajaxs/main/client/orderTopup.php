<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../core/apiSend.php');
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
        $checkStatusTopup = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "status_topup"');
        $checkStatusServer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
        if ($checkStatusTopup != 1 || $checkStatusServer != 1) {
            response(false, 'Chức năng đang tạm thời bảo trì');
        }

        // Kiểm tra và lọc dữ liệu đầu vào
        if (isEmptyOrNull($data['telco']) || isEmptyOrNull($data['phone']) || isEmptyOrNull($data['amount'])) {
            response(false, 'Vui lòng nhập đầy đủ thông tin');
        }
        $telco  = $purifier->purify(trim($data['telco']));
        $phone  = $purifier->purify(trim(filterPhone($data['phone'])));
        $amount = $purifier->purify(trim($data['amount']));

        $telco_id = getTopupIdFromCode($telco);
        $user_id  = getIdUser();

        // Chống Spam lệnh
        $user_info = getInfoUser($user_id);
        if ($user_info['user_last_topup'] > (time() - 10)) {
            response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
        }
        pdo_execute("UPDATE `user` SET `user_last_topup` = ? WHERE `user_id` = ?", [time(), $user_id]);

        // Kiểm tra nhà mạng và mệnh giá
        $checkTopup = pdo_query_value("SELECT `topup_status` FROM `topup` WHERE `topup_code` = ?", [$telco]);
        $checkTopupRare = pdo_query_value("SELECT `topup-rare_status` FROM `topup-rare` WHERE `topup-rare_value` = ?", [$amount]);
        if ($checkTopup == 0) {
            response(false, 'Nhà mạng bạn chọn đang bảo trì, vui lòng chọn nhà mạng khác');
        }
        if ($checkTopupRare == 0) {
            response(false, 'Mệnh giá bạn chọn đang hết hàng sản phẩm');
        }

        // Xử lý đơn hàng
        $request_id = "TP" . rand_string();
        $discount   = getDiscount($telco_id, $amount);
        $pay_amount = round(($amount - ($amount * $discount / 100)), 2);

        // Kiểm tra số dư
        $user_cash = pdo_query_value('SELECT `user_cash` FROM `user` WHERE `user_id` = ?', [$user_id]);
        if ($user_cash < $pay_amount) {
            response(false, 'Số dư không đủ');
        }

        userCash('sub', $pay_amount, $user_id, "Nạp tiền điện thoại: $telco - $phone - $amount - $request_id");

        // Lưu lịch sử nạp tiền
        $sql = "INSERT INTO `topup-order` SET
            `user_id` = ?, 
            `topup_id` = ?, 
            `topup-order_request_id` = ?,
            `topup-order_pay_amount` = ?, 
            `topup-order_discount` = ?,
            `topup-order_amount` = ?, 
            `topup-order_account` = ?,
            `topup-order_status` = 'wait'";
        pdo_execute($sql, [$user_id, $telco_id, $request_id, $pay_amount, $discount, $amount, $phone]);


        response(true, 'Đã tạo lệnh nạp tiền thành công. Vui lòng chờ hệ thống xử lý.');
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
