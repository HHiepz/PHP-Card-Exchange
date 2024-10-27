<?php
session_start();
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    if (checkToken('request') == false) {
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

        // Đặt lại mật khẩu
        if (isset($data['resetpass'])) {

            $email = $purifier->purify(trim($data['email']));
            $otpEmail = $purifier->purify(trim($data['otpEmail']));

            // Kiểm tra rỗng
            if (isEmptyOrNull($email) || isEmptyOrNull($otpEmail)) {
                response(false, 'Không được để trống thông tin');
            }

            // Kiểm tra định dạng email
            if (checkEmail($email) == false) {
                response(false, 'Email không hợp lệ');
            }

            // Kiểm tra mã OTP xác thực email
            if (isset($_SESSION['otpEmailResetPass'])) {
                $getOtpEmail = $_SESSION['otpEmailResetPass']['otp'];
                $otpTime = $_SESSION['otpEmailResetPass']['time'];

                // Kiểm tra xem OTP có hết hạn không
                if (time() > $otpTime + 5 * 60) {
                    unset($_SESSION['otpEmailResetPass']);
                    response(false, 'Mã OTP đã hết hạn, vui lòng tải lại trang và thử lại');
                }

                if (password_verify($otpEmail, $getOtpEmail) == false) {
                    response(false, 'Mã OTP không hợp lệ');
                }

                // Xóa session
                unset($_SESSION['otpEmailResetPass']);
            } else {
                response(false, 'Bạn chưa nhận mã OTP xác thực email');
            }

            $user = pdo_query_one('SELECT * FROM `user` WHERE `user_email` = ? AND `user_banned` != 1', [$email]);
            if (!empty($user)) {
                // Chống Spam lệnh
                if ($user['user_last_email'] > (time() - 10)) {
                    response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
                } else {
                    pdo_execute("UPDATE `user` SET `user_last_email` = ? WHERE `user_id` = ?", [time(), $user['user_id']]);
                }

                // Thông báo Email
                $passwordNew = rand_string(11); // Tạo mật khẩu mới
                $subject = "[CARD2K.COM] Mật khẩu mới: $passwordNew";
                $type    = "newPassword";
                $dataJson = json_encode([
                    'name' => $email,
                    'new_password' => $passwordNew,
                    'year' => date("Y")
                ]);

                // Thêm vào hàng đợi gửi email
                pdo_execute("INSERT INTO `email_queue` (`recipient_email`, `subject`, `type`, `dataJson`) VALUES (?, ?, ?, ?)", [$email, $subject, $type, $dataJson]);

                // Cập nhật mật khẩu mới
                pdo_execute('UPDATE `user` SET `user_password` = ? WHERE `user_id` = ?', [password_hash($passwordNew, PASSWORD_DEFAULT), $user['user_id']]);

                // Cập nhật token mới
                $token = createToken($email);
                pdo_execute('UPDATE `user` SET `user_token` = ? WHERE `user_id` = ?', [$token, $user['user_id']]);
                response(true, 'Mật khẩu mới đã đươc gửi tới email của bạn');
            } else {
                response(false, 'Email không tồn tại');
            }
        }


        // Gửi mã OTP xác thực email
        if (isset($data['sendOtpButton'])) {

            if (empty($_SESSION['otpEmailResetPass'])) {
                // Lọc dữ liệu đầu vào
                $email = $purifier->purify(trim($data['email']));

                // Kiểm tra dữ liệu data phải là kiểu mảng
                if (!is_array($data)) {
                    response(false, 'Dữ liệu không hợp lệ');
                }

                // Kiểm tra rỗng 
                if (isEmptyOrNull($email)) {
                    response(false, 'Vui lòng điền Email đăng ký');
                }

                // Kiểm tra định dạng email
                if (checkEmail($email) == false) {
                    response(false, 'Email không đúng định dạng');
                }

                // Kiểm tra email có tồn tại chưa?
                if (!pdo_query_value('SELECT `user_email` FROM `user` WHERE `user_email` = ?', [$email])) {
                    response(false, 'Email không tồn tại');
                }

                $user = pdo_query_one('SELECT * FROM `user` WHERE `user_email` = ? AND `user_banned` != 1', [$email]);
                if (!empty($user)) {
                    // Chống Spam lệnh
                    if ($user['user_last_email'] > (time() - 10)) {
                        response(false, 'Bạn đang thực hiện thao tác quá nhanh, vui lòng thử lại sau 5 giây');
                    } else {
                        pdo_execute("UPDATE `user` SET `user_last_email` = ? WHERE `user_id` = ?", [time(), $user['user_id']]);
                    }

                    // Thông báo Email
                    $otp     = rand(100000, 999999);                   // Mã OTP
                    $otpHash = password_hash($otp, PASSWORD_DEFAULT);  // Mã hóa mã OTP
                    $subject = "[CARD2K.COM] Mã OTP khôi phục mật khẩu tài khoản: $otp";
                    $type    = "otpResetPass";
                    $dataJson = json_encode([
                        'name' => $email,
                        'otp'   => $otp,
                        'year'  => date("Y")
                    ]);

                    // Thêm vào hàng đợi gửi email
                    pdo_execute("INSERT INTO `email_queue` (`recipient_email`, `subject`, `type`, `dataJson`) VALUES (?, ?, ?, ?)", [$email, $subject, $type, $dataJson]);

                    // Tạo session chứa mã OTP tồn tại trong 5 phút
                    $_SESSION['otpEmailResetPass'] = [
                        'otp' => $otpHash,
                        'time' => time(),
                    ];

                    response(true, 'Gửi mã OTP thành công, Vui lòng check Email');
                } else {
                    response(false, 'Email không tồn tại');
                }
            } else {
                response(false, 'Mã OTP đã được gửi, vui lòng check Email <br> (nếu bạn nhập sai, hay quay lại sau 5p)');
            }
        }
    } else {
        response(false, 'Bạn đã đăng nhập rồi mà!');
    }
}
