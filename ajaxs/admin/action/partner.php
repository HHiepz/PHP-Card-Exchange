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

        // XÓA PARTNER
        if (isset($data['partner_delete'])) {
            // Lọc dữ liệu đầu vào
            $partner_id = $purifier->purify($data['partner_id']);

            // Kiểm tra đối tác có tồn tại
            $checkPartner = pdo_query_one("SELECT * FROM `partner` WHERE `partner_id` = ?", [$partner_id]);
            if (empty($checkPartner)) {
                response(false, "Đối tác không tồn tại!");
            }

            // Xóa đối tác
            pdo_execute("DELETE FROM `partner` WHERE `partner_id` = ?", [$partner_id]);
            response(true, "Xóa đối tác thành công!");
        }

        // KÍCH HOẠT PARTNER
        if (isset($data['partner_active'])) {
            // Lọc dữ liệu đầu vào
            $partner_id = $purifier->purify($data['partner_id']);

            // Kiểm tra đối tác có tồn tại
            $checkPartner = pdo_query_one("SELECT * FROM `partner` WHERE `partner_id` = ?", [$partner_id]);
            if (empty($checkPartner)) {
                response(false, "Đối tác không tồn tại!");
            }

            // Kiểm tra đối tác đã kích hoạt chưa
            if ($checkPartner['partner_status'] == 'active') {
                response(false, "Đối tác đã kích hoạt rồi!");
            }

            // Kích hoạt đối tác
            pdo_execute("UPDATE `partner` SET `partner_status` = 'active' WHERE `partner_id` = ?", [$partner_id]);
            response(true, "Kích hoạt đối tác thành công!");
        }

        // HỦY KÍCH HOẠT PARTNER
        if (isset($data['partner_cancel'])) {
            // Lọc dữ liệu đầu vào
            $partner_id = $purifier->purify($data['partner_id']);

            // Kiểm tra đối tác có tồn tại
            $checkPartner = pdo_query_one("SELECT * FROM `partner` WHERE `partner_id` = ?", [$partner_id]);
            if (empty($checkPartner)) {
                response(false, "Đối tác không tồn tại!");
            }

            // Kiểm tra đối tác đã kích hoạt chưa
            if ($checkPartner['partner_status'] == 'cancel') {
                response(false, "Đối tác đã hủy kích hoạt rồi!");
            }

            // Hủy kích hoạt đối tác
            pdo_execute("UPDATE `partner` SET `partner_status` = 'cancel' WHERE `partner_id` = ?", [$partner_id]);
            response(true, "Hủy kích hoạt đối tác thành công!");
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
