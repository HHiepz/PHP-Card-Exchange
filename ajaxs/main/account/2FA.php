<?php
session_start();
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');
require('../../../vendor/autoload.php');

$googleAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request_verify")) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra reCAPTCHA
        $google_reCaptcha = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "status_ggReCaptcha"');
        $token    = isset($data['token']) ? $purifier->purify(trim($data['token'])) : '';
        if ($google_reCaptcha == 1) {
            if (!verifyRecaptcha($token)) {
                response(false, 'Hệ thống phát hiện nghi ngờ bạn là robot, vui lòng thử lại sau');
            }
        }

        // Xác thực Email
        if (isset($data['otpEmail'])) {

            // Lấy dữ liệu
            $otpEmail    = $purifier->purify(trim($data['otpEmail']));

            // Kiểm tra rỗng
            if (isEmptyOrNull($otpEmail)) {
                response(false, 'Vui lòng nhập mã xác thực');
            }

            // Kiểm tra có bật 2FA Google Authenticator không?
            $user = getInfoUser(getIdUser());
            if ($user['user_is_verify_2fa'] == 1) {
                response(false, 'Vui lòng tắt 2FA Google Authenticator trước khi kích hoạt!');
            }

            // Kiểm tra mã OTP xác thực email
            if (isset($_SESSION['otp2FAEmail'])) {
                $getOtpEmail = $_SESSION['otp2FAEmail']['otp'];
                $otpTime = $_SESSION['otp2FAEmail']['time'];

                // Kiểm tra xem OTP có hết hạn không
                if (time() > $otpTime + 5 * 60) {
                    unset($_SESSION['otp2FAEmail']);
                    response(false, 'Mã OTP đã hết hạn, vui lòng tải lại trang và thử lại');
                }

                if (password_verify($otpEmail, $getOtpEmail) == false) {
                    response(false, 'Mã OTP không hợp lệ');
                }

                // Xóa session
                unset($_SESSION['otp2FAEmail']);
            } else {
                response(false, 'Bạn chưa nhận mã OTP xác thực email (2FA) vui lòng thoát ra và đăng nhập lại');
            }

            // Cập nhật trạng thái xác thực email
            $token   = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
            $user_id = pdo_query_value("SELECT `user_id` FROM `user` WHERE `user_token` = ?", [$token]);
            pdo_execute("UPDATE `user` SET `user_is_verify` = 1 WHERE `user_id` = ?", [$user_id]);

            response(true, 'Xác thực Email thành công! cảm ơn bạn đã sử dụng dịch vụ của chúng tôi');
        }

        // Xác thực Google Authenticator
        if (isset($data['otpGgAuth'])) {

            // Lấy dữ liệu
            $otpGgAuth    = $purifier->purify(trim($data['otpGgAuth']));

            // Kiểm tra rỗng
            if (isEmptyOrNull($otpGgAuth)) {
                response(false, 'Vui lòng nhập mã xác thực');
            }

            // Kiểm tra có bật 2FA Email không?
            $user = getInfoUser(getIdUser());
            if ($user['user_is_verify_email'] == 1) {
                response(false, 'Vui lòng tắt 2FA Email trước khi kích hoạt!');
            }

            // Lấy dữ liệu
            $user_id           = getIdUser();
            $ggAuth_secret_SQL = getGoogleAuthSecret($user_id);
            if (!empty($ggAuth_secret_SQL)) {
                $resultCheck = $googleAuth->checkCode($ggAuth_secret_SQL, $otpGgAuth);
                if ($resultCheck) {
                    pdo_execute("UPDATE `user` SET `user_is_verify` = 1 WHERE `user_id` = ?", [$user_id]);
                    response(true, 'Xác thực Google Authenticator thành công! cảm ơn bạn đã sử dụng dịch vụ của chúng tôi');
                } else {
                    response(false, 'Mã xác thực không hợp lệ');
                }
            } else {
                response(false, "Bạn chưa kích hoạt 2FA!");
            }
        }
    } else {
        response(false, 'Bạn chưa đăng nhập hoặc đã xác thực thành công rồi! Vui lòng tải lại trang');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
