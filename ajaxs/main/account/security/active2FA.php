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
        if (isEmptyOrNull($data['otp']) || isEmptyOrNull($data['ggAuth_secret'])) {
            response(false, 'Vui lòng nhập mã xác thực');
        }

        // Kiểm tra có bật 2FA Email không?
        $user = getInfoUser(getIdUser());
        if ($user['user_is_verify_email'] == 1) {
            response(false, 'Vui lòng tắt 2FA Email trước khi kích hoạt!');
        }

        // Lấy dữ liệu
        $otp           = $purifier->purify(trim($data['otp']));
        $ggAuth_secret = $purifier->purify(trim($data['ggAuth_secret']));

        $ggAuth_secret_SQL = getGoogleAuthSecret(getIdUser());
        if (empty($ggAuth_secret_SQL)) {

            $resultCheck = $googleAuth->checkCode($ggAuth_secret, $otp);
            if ($resultCheck) {
                pdo_execute("UPDATE `user` SET `user_2fa_code` = ?, `user_is_verify_2fa` = 1, `user_is_verify` = 1 WHERE `user_id` = ?", [$ggAuth_secret, getIdUser()]);
                response(true, 'Kích hoạt 2FA thành công');
            } else {
                response(false, 'Mã xác thực không hợp lệ');
            }
        } else {
            response(false, "Bạn đã kích hoạt 2FA rồi");
        }
    } else {
        response(false, 'Bạn chưa đăng nhập hoặc đã xác thực thành công rồi! Vui lòng tải lại trang');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
