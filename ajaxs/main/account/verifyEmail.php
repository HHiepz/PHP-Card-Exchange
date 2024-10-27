<?php
session_start();
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

        // Xác thực Email
        if (isset($data['verifyEmail'])) {
            $password    = $purifier->purify($data['password']);
            $otpEmail    = $purifier->purify($data['otpEmail']);

            // Kiểm tra rỗng
            if (isEmptyOrNull($password) || isEmptyOrNull($otpEmail)) {
                response(false, 'Vui lòng nhập đầy đủ thông tin');
            }

            // Kiểm tra mã OTP xác thực email
            if (isset($_SESSION['sendOtpVerifyEmail'])) {
                $getOtpEmail = $_SESSION['sendOtpVerifyEmail']['otp'];
                $otpTime = $_SESSION['sendOtpVerifyEmail']['time'];

                // Kiểm tra xem OTP có hết hạn không
                if (time() > $otpTime + 5 * 60) {
                    response(false, 'Mã OTP đã hết hạn');
                }

                if (password_verify($otpEmail, $getOtpEmail) == false) {
                    response(false, 'Mã OTP không hợp lệ');
                }

                // Xóa session
                unset($_SESSION['sendOtpVerifyEmail']);
            } else {
                response(false, 'Bạn chưa nhận mã OTP xác thực email');
            }

            // Kiểm tra mật khẩu 
            $user_id     = getIdUser();
            $passwordSQL = getInfoUser($user_id)['user_password'];
            if (password_verify($password, $passwordSQL) == false) {
                response(false, 'Mật khẩu không chính xác');
            }

            // Cập nhật trạng thái xác thực email
            pdo_execute("UPDATE `user` SET `user_verify_email` = 1 WHERE `user_id` = ?", [$user_id]);

            // Xóa cookie
            setcookie('sendOtpVerifyEmail', '', time() - 3600, '/', '', false, true);

            response(true, 'Xác thực Email thành công, cảm ơn bạn đã sử dụng dịch vụ của chúng tôi');
        }

        // Gữi mã xác thực
        if (isset($data['sendOtp'])) {
            if (empty($_COOKIE['sendOtpVerifyEmail'])) {
                $email = getEmailUser(getIdUser());

                // Chống Spam lệnh
                $user_id       = getIdUser();
                $user_info     = getInfoUser($user_id);
                $timeLastEmail = $user_info['user_last_email'];


                if (time() - $timeLastEmail < 60) {
                    // Cộng thêm 1 lần cảnh báo
                    pdo_execute("UPDATE `user` SET `user_warning` = `user_warning` + 1 WHERE `user_email` = ?", [$user_info['user_email']]);
                    // Kiểm tra số lần đăng nhập sai
                    $warning = pdo_query_value('SELECT `user_warning` FROM `user` WHERE `user_email` = ?', [$user_info['user_email']]);
                    if ($warning >= 5) {
                        pdo_execute("UPDATE `user` SET `user_banned` = 1 WHERE `user_email` = ?", [$user_info['user_email']]);
                        pdo_execute("UPDATE `user` SET `user_banned_reason` = 'Vào lúc " . getDateTimeNow() . " Spam lệnh verify email' WHERE `user_email` = ?", [$email]);
                        response(false, 'Tài khoản đã bị khóa do spam verify email quá nhiều lần');
                    }

                    response(false, 'Vui lòng chờ 1 phút để gữi lại mã OTP, spam lệnh quá 5 lần sẽ bị khóa tài khoản');
                }

                // Gữi email
                $otp     = rand(100000, 999999);                   // Mã OTP
                $otpHash = password_hash($otp, PASSWORD_DEFAULT);  // Mã hóa mã OTP
                $subject = "[CARD2K.COM] Mã OTP xác thực tài khoản: $otp";
                $type    = 'otpVerifyEmail';
                $dataJson = json_encode([
                    'name' => $email,
                    'otp'  => $otp,
                    'year' => date("Y")
                ]);

                // Thêm vào hàng đợi gửi email
                pdo_execute("INSERT INTO `email_queue` (`recipient_email`, `subject`, `type`, `dataJson`) VALUES (?, ?, ?, ?)", [$email, $subject, $type, $dataJson]);

                // Cập nhật thời gian gửi mã OTP
                pdo_execute("UPDATE `user` SET `user_last_email` = ? WHERE `user_id` = ?", [time(), getIdUser()]);

                // Tạo session chứa mã OTP tồn tại trong 5 phút
                $_SESSION['sendOtpVerifyEmail'] = [
                    'otp' => $otpHash,
                    'time' => time()
                ];

                response(true, 'Gửi mã OTP thành công, Vui lòng check Email');
            } else {
                response(false, 'Mã OTP đã được gữi, vui lòng check Email');
            }
        }
    } else {
        response(false, 'Bạn chưa đăng nhập, vui lòng tải lại trang và đăng nhập lại');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
