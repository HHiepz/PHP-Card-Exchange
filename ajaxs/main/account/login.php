<?php
session_start();
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request") == false) {
        if (!isset($_POST['data'])) {
            response(false, 'Dữ liệu không được gửi');
        }

        $data = json_decode($_POST['data'], true);

        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // Kiểm tra rỗng 
        if (isEmptyOrNull($data['email']) || isEmptyOrNull($data['password'])) {
            response(false, 'Không được để trống thông tin');
        }

        // Lọc dữ liệu đầu vào
        $email    = $purifier->purify(trim($data['email']));
        $password = $purifier->purify(trim($data['password']));

        // Kiểm tra reCAPTCHA
        $google_reCaptcha = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "status_ggReCaptcha"');
        $token    = isset($data['token']) ? $purifier->purify(trim($data['token'])) : '';
        if ($google_reCaptcha == 1) {
            if (!verifyRecaptcha($token)) {
                response(false, 'Hệ thống phát hiện nghi ngờ bạn là robot, vui lòng thử lại sau');
            }
        }

        // Kiểm tra định dạng email
        if (checkEmail($email) == false) {
            response(false, 'Email không hợp lệ');
        }

        // Kiểm tra email có tồn tại chưa?
        $user = pdo_query_one("SELECT * FROM `user` WHERE `user_email` = ?", [$email]);
        if (!empty($user)) {
            if ($user['user_banned'] == 1) {
                response(false, 'Tài khoản đã bị khóa. <br>' . $user['user_banned_reason']);
            }

            $passwordSql = $user['user_password']; // Mật khẩu trong database

            if (password_verify($password, $passwordSql)) {
                // Kiểm tra xem có phải VN không
                $verify_ip        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'register_verify_ip'");    // Xác minh IP khi đăng ký
                if ($verify_ip == 1) {
                    $countryCode = getInfoFromIP(getUserIP());
                    if ($countryCode === false) {
                        pdo_execute("UPDATE `setting` SET `value` = 0 WHERE `name` = 'register_verify_ip'");
                        response(false, 'Lỗi xác minh IP, vui lòng thử lại');
                    }
                    if ($countryCode['countryCode'] !== "VN" || $countryCode['currency'] !== "VND") {
                        response(false, 'Chỉ cho phép đăng nhập từ Việt Nam');
                    }
                    if ($countryCode['proxy'] == true) {
                        response(false, 'Không cho phép sử dụng Proxy - VPN - Tor');
                    }
                }

                // Tạo token, luu vào cookie và database
                $token = createToken($email);
                setcookie('token', $token, time() + 86400, '/', '', false, true); // Cookie lasts for 24 hours
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_email` = ?", [$token, time(), $email]);

                // Thông báo Discord
                $webhook = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "webhook_login"');
                if (!empty($webhook)) {
                    $discord_user_id       = $user['user_id'];
                    $discord_user_fullname = $user['user_fullname'];
                    $discord_user_rank     = $user['user_rank'];
                    $discord_created_at    = $user['created_at'];
                    $discord_user_ip       = getUserIP();
                    $discord_user_warning  = $user['user_warning'];
                    sendDiscord($webhook, form_discord_login($discord_user_id, $discord_user_fullname, $discord_user_rank, $discord_created_at, $discord_user_ip, $discord_user_warning));
                }

                $checkIpUser = pdo_query_one("SELECT * FROM `user-log-ip` WHERE `user_ip` = ? AND `user_id` = ?", [getUserIP(), $user['user_id']]);
                if ($user['noti_email_login'] == 1) {
                    // Thông báo Email
                    if (empty($checkIpUser)) {
                        $subject  = "[CARD2K.COM] Cảnh báo đăng nhập IP mới: " . getUserIP();
                        $type     = "loginNewIp";
                        $dataJson = json_encode([
                            'name' => $user['user_fullname'],
                            'email' => $user['user_email'],
                            'ip' => getUserIP(),
                            'time' => getDateTimeNow(),
                            'year' => date("Y")
                        ]);

                        // Thêm công việc vào bảng hàng đợi
                        pdo_execute("INSERT INTO `email_queue` (`recipient_email`, `subject`, `type`, `dataJson`) VALUES (?, ?, ?, ?)", [$user['user_email'], $subject, $type, $dataJson]);
                        pdo_execute("INSERT INTO `user-log-ip` (`user_id`, `user_ip`, `user_agent`, `created_at`) VALUES (?, ?, ?, ?)", [$user['user_id'], getUserIP(), getUserAgent(), getDateTimeNow()]);
                    }
                } else {
                    if (empty($checkIpUser)) {
                        pdo_execute("INSERT INTO `user-log-ip` (`user_id`, `user_ip`, `user_agent`, `created_at`) VALUES (?, ?, ?, ?)", [$user['user_id'], getUserIP(), getUserAgent(), getDateTimeNow()]);
                    }
                }
                // Reset số lần đăng nhập sai
                pdo_execute("UPDATE `user` SET `user_warning` = 0 WHERE `user_email` = ?", [$email]);

                // Cập nhật user_ip
                pdo_execute("UPDATE `user` SET `user_ip` = ? WHERE `user_email` = ?", [getUserIP(), $email]);

                // Bảo mật cấp 2
                if ($user['user_is_verify_email'] == 1 || $user['user_is_verify_2fa'] == 1) {
                    // Reset verify về 0
                    pdo_execute("UPDATE `user` SET `user_is_verify` = 0 WHERE `user_email` = ?", [$email]);

                    // Bảo mật cấp 2 (2FA) EMAIL
                    if ($user['user_is_verify_email'] == 1) {
                        // Gữi email
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
                    }
                }

                response(true, 'Đăng nhập thành công');
            } else {
                // Tăng số lần đăng nhập sai
                pdo_execute("UPDATE `user` SET `user_warning` = `user_warning` + 1 WHERE `user_email` = ?", [$email]);

                // Kiểm tra số lần đăng nhập sai
                $warning = pdo_query_value('SELECT `user_warning` FROM `user` WHERE `user_email` = ?', [$email]);
                if ($warning >= 5) {
                    pdo_execute("UPDATE `user` SET `user_banned` = 1 WHERE `user_email` = ?", [$email]);
                    pdo_execute("UPDATE `user` SET `user_banned_reason` = 'Vào lúc " . getDateTimeNow() . " Sai mật khẩu quá nhiều lần' WHERE `user_email` = ?", [$email]);
                    response(false, 'Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần');
                }

                response(false, 'Sai thông tin đăng nhập <br> (Sai quá 5 lần sẽ bị khóa tài khoản)');
            }
        } else {
            response(false, 'Tài khoản không tồn tại');
        }
    } else {
        response(false, 'Bạn đã đăng nhập rồi mà!');
    }
} else {
    response(false, 'Không tìm thấy phương thức');
}
