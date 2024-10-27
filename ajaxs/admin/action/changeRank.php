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

        // Lọc dữ liệu đầu vào
        $newRank   = $purifier->purify($data['newRank']);
        $dateRank  = $purifier->purify($data['dateRank']);
        $reason    = $purifier->purify($data['reason']);
        $user_id   = $purifier->purify($data['user_id']);

        // Kiểm tra rỗng
        if (isEmptyOrNull($newRank) || isEmptyOrNull($user_id) || isEmptyOrNull($dateRank) || isEmptyOrNull($reason)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra người dùng có tồn tại không
        $checkUser = pdo_query_one("SELECT * FROM `user` WHERE `user_id` = ?", [$user_id]);
        if (empty($checkUser)) {
            response(false, 'Người dùng không tồn tại');
        }

        // Kiểm tra rank có tồn tại không
        if ($newRank != "member" && $newRank != "vip" && $newRank != "agency") {
            response(false, 'Rank không hợp lệ');
        }

        // Kiểm tra rank có giống rank hiện tại không
        if ($checkUser['user_rank'] == $newRank) {
            response(false, 'Rank hiện tại đã là rank bạn chọn');
        }

        // Kiểm tra ngày rank có hợp lệ không
        if ($dateRank < -1 || $dateRank > 365) {
            response(false, 'Ngày rank không hợp lệ, tối thiểu 1 ngày và tối đa 365 ngày (0 là vĩnh viễn)');
        }

        if ($dateRank == -1) {
            $note = "$reason - Vĩnh viễn";
        } else {
            $note = "$reason - $dateRank ngày";
        }

        // Thực hiện thay đổi rank
        userRank($newRank, $dateRank, $user_id, $note, getIdUser());

        response(true, 'Thay đổi rank thành công');
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
