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
        if (isEmptyOrNull($data['partnerID'])) {
            response(false, 'Không được để trống thông tin');
        }

        // Lọc dữ liệu đầu vào
        $partner_id = $purifier->purify($data['partnerID']);   // Partner ID
        $user_id    = getIdUser();                             // ID người dùng

        // Kiểm tra $partner_id có tồn tại trên hệ thống không?
        $partnerInfo = pdo_query_one('SELECT `partner_id`, `partner_key`, `partner_callback` FROM `partner` WHERE `partner_id` = ? AND `user_id` = ?', [$partner_id, $user_id]);
        if (empty($partnerInfo)) {
            response(false, 'Partner không tồn tại');
        }

        // Trả thông tin.
        response(true, "Lấy dữ liệu thành công", $partnerInfo);
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức yêu cầu');
}
