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
        $partner_id = $purifier->purify($data['partner_id']);       // Tên ngân hàng
        $user_id    = getIdUser();                                  // ID người dùng

        // Kiểm tra rỗng
        if (isEmptyOrNull($partner_id)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra $partner_id có tồn tại trên hệ thống không?
        $checkPartnerId = pdo_query_one('SELECT `partner_id` FROM `partner` WHERE `partner_id` = ? AND `user_id` = ?', [$partner_id, $user_id]);
        if (empty($checkPartnerId)) {
            response(false, 'Partner không tồn tại');
        }

        // Xóa partner
        $sql = "DELETE FROM `partner` WHERE `partner_id` = ? AND `user_id` = ?";
        pdo_execute($sql, [$partner_id, $user_id]);
        response(true, "Xóa API $partner_id thành công");
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
