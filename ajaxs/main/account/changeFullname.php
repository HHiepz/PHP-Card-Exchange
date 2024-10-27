<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

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
        $fullname = $purifier->purify(trim((strtolower($data['changeFullname_name']))));
        $password = $purifier->purify(trim($data['password']));

        $user_id     = getIdUser();                 // ID người dùng từ Cookie
        $user_info   = getInfoUser($user_id);       // Thông tin người dùng từ ID

        // Chống spam lệnh
        if ($user_info['user_has_changeFullname'] == 1) {
            response(false, 'Bạn đã sử dụng hết lượt thay đổi số điện thoại, vui lòng liên hệ với bộ phận hỗ trợ để được hỗ trợ thêm');
        }

        // Kiểm tra rỗng 
        if (isEmptyOrNull($fullname) || isEmptyOrNull($password)) {
            response(false, 'Không được để trống thông tin');
        }

        // Kiểm tra định dạng fullname
        if (checkFullname($fullname) == false) {
            response(false, 'Tên người dùng không hợp lệ (Tên người dùng không chứa ký tự đặc biệt)');
        }

        // Kiểm tra số điện thoại có tồn tại chưa?
        $user = pdo_query_one("SELECT * FROM `user` WHERE `user_fullname` = ?", [$fullname]);
        if (empty($user)) {

            $passwordSql = $user_info['user_password']; // Mật khẩu trong database
            if (password_verify($password, $passwordSql)) {
                // Ghi logs
                insertLogs($user_id, 'changeFullname', "Thay đổi tên người dùng từ " . $user_info['user_fullname'] . " thành " . $fullname);

                // Cập nhật số điện thoại mới
                pdo_execute("UPDATE `user` SET `user_fullname` = ? WHERE `user_id` = ?", [$fullname, $user_id]);
                pdo_execute("UPDATE `user` SET `user_has_changeFullname` = ? WHERE `user_id` = ?", [1, $user_id]);
                response(true, 'Cập nhật số điện thoại thành công');
            } else {
                response(false, 'Mật khẩu không chính xác');
            }
        } else {
            response(false, 'Tên người dùng đã tồn tại');
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
