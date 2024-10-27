<?php
session_start();
require('../../../../core/database.php');
require('../../../../core/function.php');
require('../../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

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

        // Kiểm tra có bật 2FA Google Authenticator không?
        $user = getInfoUser(getIdUser());
        if ($user['user_is_verify_2fa'] == 1) {
            response(false, 'Vui lòng tắt 2FA Google Authenticator!');
        }

        // Xác thực Email
        if (isset($data['otpCheck'])) {
            // Lấy dữ liệu
            $otpEmail = $purifier->purify(trim($data['otp']));

            // Kiểm tra rỗng
            if (isEmptyOrNull($otpEmail)) {
                response(false, 'Vui lòng nhập mã xác thực');
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
                    response(false, 'Mã OTP không chính xác');
                }

                // Xóa session
                unset($_SESSION['otp2FAEmail']);

                // Cập nhật reset 2FA
                pdo_execute("UPDATE `user` SET `user_is_verify_email` = 1, `user_is_verify` = 1 WHERE `user_id` = ?", [getIdUser()]);

                response(true, 'Kích hoạt Email thành công');
            } else {
                response(false, 'Mã OTP không tồn tại');
            }
        }

        // Gữi Mã OTP
        if (isset($data['otpSend'])) {
            // Chống Spam lệnh
            $user = getInfoUser(getIdUser());
            if ($user['user_last_email'] > (time() - 5 * 60)) {
                response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 phút');
            } else {
                pdo_execute("UPDATE `user` SET `user_last_email` = ? WHERE `user_id` = ?", [time(), $user['user_id']]);
            }
            response(false, "Chức năng này đang được phát triển, vui lòng thử lại sau");


            // Gữi email
            $email   = $user['user_email'];
            $otp     = rand(100000, 999999);                          // Mã OTP
            $otpHash = password_hash($otp, PASSWORD_DEFAULT);  // Mã hóa mã OTP
            $subject = "[CARD2K.COM] Mã OTP (2FA) tài khoản: $otp";
            $type    = 'otp2FAEmail';
            $dataJson = json_encode([
                'name' => $email,
                'otp'  => $otp,
                'year' => date("Y")
            ]);

            // Thêm vào hàng đợi gửi email
            pdo_execute("INSERT INTO `email_queue` (`recipient_email`, `subject`, `type`, `dataJson`) VALUES (?, ?, ?, ?)", [$email, $subject, $type, $dataJson]);

            // Tạo session chứa mã OTP tồn tại trong 5 phút
            $_SESSION['otp2FAEmail'] = [
                'otp'   => $otpHash,
                'time'  => time()
            ];

            response(true, 'Gửi mã OTP thành công, vui lòng kiểm tra Email của bạn');
        }
    } else {
        response(false, 'Bạn chưa đăng nhập hoặc đã xác thực thành công rồi! Vui lòng tải lại trang');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
