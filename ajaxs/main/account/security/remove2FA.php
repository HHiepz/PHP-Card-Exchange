<?php
session_start();
require('../../../../core/database.php');
require('../../../../core/function.php');
require('../../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');
require('../../../../vendor/autoload.php');

$googleAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request")) {
        $data = json_decode($_POST['data'], true);

        // Kiểm tra dữ liệu data phải là kiểu mảng
        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra rỗng
        if (isEmptyOrNull($data['otp'])) {
            response(false, 'Vui lòng nhập mã xác thực');
        }

        // Kiểm tra có bật 2FA Email không?
        $user = getInfoUser(getIdUser());
        if ($user['user_is_verify_email'] == 1) {
            response(false, 'Vui lòng tắt 2FA Email!');
        }

        // Lấy dữ liệu
        $otp               = $purifier->purify(trim($data['otp']));
        $ggAuth_secret_SQL = getGoogleAuthSecret(getIdUser());

        if (!empty($ggAuth_secret_SQL)) {

            $resultCheck = $googleAuth->checkCode($ggAuth_secret_SQL, $otp);
            if ($resultCheck) {
                pdo_execute("UPDATE `user` SET `user_2fa_code` = NULL, `user_is_verify_2fa` = 0, `user_is_verify` = 0 WHERE `user_id` = ?", [getIdUser()]);
                response(true, 'Hủy 2FA Google Authenticator thành công!');
            } else {
                response(false, 'Mã xác thực không hợp lệ');
            }
        } else {
            response(false, "Bạn chưa kích hoạt 2FA!");
        }
    } else {
        response(false, 'Bạn chưa đăng nhập hoặc đã xác thực thành công rồi! Vui lòng tải lại trang');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
