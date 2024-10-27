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

        // Kiểm tra reCAPTCHA
        $google_reCaptcha = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "status_ggReCaptcha"');
        $token    = isset($data['token']) ? $purifier->purify(trim($data['token'])) : '';
        if ($google_reCaptcha == 1) {
            if (!verifyRecaptcha($token)) {
                response(false, 'Hệ thống phát hiện nghi ngờ bạn là robot, vui lòng thử lại sau');
            }
        }

        // Đăng ký tài khoản
        if (isset($data['registerButton'])) {
            $verify_email     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'register_verify_email'"); // Xác minh email khi đăng ký
            $verify_ip        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'register_verify_ip'");    // Xác minh IP khi đăng ký

            if ($verify_email == 1) {
                // Kiểm tra rỗng 
                if (isEmptyOrNull($data['email']) || isEmptyOrNull($data['password']) || isEmptyOrNull($data['repassword']) || isEmptyOrNull($data['otpEmail'])) {
                    response(false, 'Không được để trống thông tin');
                }

                // Lọc dữ liệu đầu vào
                $email      = $purifier->purify(trim($data['email']));               // Email
                $otpEmail   = $purifier->purify(trim($data['otpEmail']));            // Mã OTP xác thực email
                $phone      = $purifier->purify(trim(filterPhone($data['phone'])));  // Số điện thoại
                $repassword = $purifier->purify(trim($data['repassword']));          // Nhập lại mật khẩu
                $password   = $purifier->purify(trim($data['password']));            // Mật khẩu

                // Kiểm tra email có tồn tại chưa?
                if (pdo_query_value('SELECT `user_email` FROM `user` WHERE `user_email` = ?', [$email])) {
                    response(false, 'Email đã tồn tại');
                }


                // Kiểm tra định dạng email
                if (checkEmail($email) == false) {
                    response(false, 'Email không hợp lệ');
                }


                // (nếu có) Kiểm tra phone có tồn tại chưa?
                if (!empty($phone) && pdo_query_value('SELECT `user_phone` FROM `user` WHERE `user_phone` = ?', [$phone])) {
                    response(false, 'Số điện thoại đã tồn tại');
                }


                // Kiểm tra định dạng số điện thoại
                if (!empty($phone) && checkPhone($phone) == false) {
                    response(false, 'Số điện thoại không hợp lệ', $phone);
                }


                // Kiểm tra mật khẩu có trùng khớp không
                if ($password !== $repassword) {
                    response(false, 'Mật khẩu không trùng khớp');
                }


                // Kiểm tra độ dài mật khẩu
                if (checkPassword($password) == false) {
                    response(false, 'Mật khẩu phải từ 6 đến 32 ký tự');
                }


                // Kiểm tra mã OTP xác thực email
                if (isset($_SESSION['otpEmail'])) {
                    $getOtpEmail = $_SESSION['otpEmail']['otp'];
                    $otpTime = $_SESSION['otpEmail']['time'];

                    // Kiểm tra xem OTP có hết hạn không
                    if (time() > $otpTime + 5 * 60) {
                        response(false, 'Mã OTP đã hết hạn');
                    }

                    if (password_verify($otpEmail, $getOtpEmail) == false) {
                        response(false, 'Mã OTP không hợp lệ');
                    }

                    // Xóa session
                    unset($_SESSION['otpEmail']);
                } else {
                    response(false, 'Bạn chưa nhận mã OTP xác thực email');
                }

                // Kiểm tra xem có phải VN không
                if ($verify_ip == 1) {
                    $countryCode = getInfoFromIP(getUserIP());
                    if ($countryCode === false) {
                        pdo_execute("UPDATE `setting` SET `value` = 0 WHERE `name` = 'register_verify_ip'");
                        response(false, 'Lỗi xác minh IP, vui lòng thử lại');
                    }
                    if ($countryCode['countryCode'] !== "VN" || $countryCode['currency'] !== "VND") {
                        response(false, 'Chỉ cho phép đăng ký từ Việt Nam');
                    }
                    if ($countryCode['proxy'] == true) {
                        response(false, 'Không cho phép đăng ký từ Proxy - VPN - Tor');
                    }
                }

                // Kiểm tra giới hạn đăng ký theo IP
                $countUsers = pdo_query_value("SELECT COUNT(*) FROM `user` WHERE `user_ip` = ?", [getUserIP()]);
                if ($countUsers >= 3) {
                    response(false, 'Bạn đã đạt tối đa số lượng tài khoản cho phép');
                }
            } else {
                // Kiểm tra rỗng 
                if (isEmptyOrNull($data['email']) || isEmptyOrNull($data['password']) || isEmptyOrNull($data['repassword'])) {
                    response(false, 'Không được để trống thông tin');
                }

                // Lọc dữ liệu đầu vào
                $email      = $purifier->purify(trim($data['email']));               // Email
                $phone      = $purifier->purify(trim(filterPhone($data['phone'])));  // Số điện thoại
                $repassword = $purifier->purify(trim($data['repassword']));          // Nhập lại mật khẩu
                $password   = $purifier->purify(trim($data['password']));            // Mật khẩu

                // Kiểm tra email có tồn tại chưa?
                if (pdo_query_value('SELECT `user_email` FROM `user` WHERE `user_email` = ?', [$email])) {
                    response(false, 'Email đã tồn tại');
                }


                // Kiểm tra định dạng email
                if (checkEmail($email) == false) {
                    response(false, 'Email không hợp lệ');
                }


                // (nếu có) Kiểm tra phone có tồn tại chưa?
                if (!empty($phone) && pdo_query_value('SELECT `user_phone` FROM `user` WHERE `user_phone` = ?', [$phone])) {
                    response(false, 'Số điện thoại đã tồn tại');
                }


                // Kiểm tra định dạng số điện thoại
                if (!empty($phone) && checkPhone($phone) == false) {
                    response(false, 'Số điện thoại không hợp lệ', $phone);
                }


                // Kiểm tra mật khẩu có trùng khớp không
                if ($password !== $repassword) {
                    response(false, 'Mật khẩu không trùng khớp');
                }


                // Kiểm tra độ dài mật khẩu
                if (checkPassword($password) == false) {
                    response(false, 'Mật khẩu phải từ 6 đến 32 ký tự');
                }


                // Kiểm tra xem có phải VN không
                if ($verify_ip == 1) {
                    $countryCode = getInfoFromIP(getUserIP());
                    if ($countryCode === false) {
                        pdo_execute("UPDATE `setting` SET `value` = 0 WHERE `name` = 'register_verify_ip'");
                        response(false, 'Lỗi xác minh IP, vui lòng thử lại');
                    }
                    if ($countryCode['countryCode'] !== "VN" || $countryCode['currency'] !== "VND") {
                        response(false, 'Chỉ cho phép đăng ký từ Việt Nam');
                    }
                    if ($countryCode['proxy'] == true) {
                        response(false, 'Không cho phép đăng ký từ Proxy - VPN - Tor');
                    }
                }

                // Kiểm tra giới hạn đăng ký theo IP
                $countUsers = pdo_query_value("SELECT COUNT(*) FROM `user` WHERE `user_ip` = ?", [getUserIP()]);
                if ($countUsers >= 3) {
                    response(false, 'Bạn đã đạt tối đa số lượng tài khoản cho phép');
                }
            }

            // Tạo tài khoản
            $password   = password_hash($password, PASSWORD_DEFAULT);    // Mã hóa mật khẩu
            $user_ip    = getUserIP();                                   // Lấy IP người dùng
            $user_token = createToken($email);                           // Tạo token
            $user_invite_code = rand_string(8);                          // Mã giới thiệu
            $ref = isset($data['ref']) ? $purifier->purify(trim($data['ref'])) : null;  // Mời bởi ai?
            if ($verify_email == 1) {
                pdo_execute("INSERT INTO `user` SET `user_login` = ?, `user_fullname` = ?,`user_email` = ?, `user_phone` = ?, `user_token` = ?,`user_password` = ?, `user_ip` = ?, `user_invite_code` = ?, `user_invite_by` = ?, `user_expire_time` = ?, `user_verify_email` = ?", [$email, $email, $email, $phone, $user_token, $password, $user_ip, $user_invite_code, $ref, time(), 1]);
            } else {
                pdo_execute("INSERT INTO `user` SET `user_login` = ?, `user_fullname` = ?,`user_email` = ?, `user_phone` = ?, `user_token` = ?,`user_password` = ?, `user_ip` = ?, `user_invite_code` = ?, `user_invite_by` = ?, `user_expire_time` = ?", [$email, $email, $email, $phone, $user_token, $password, $user_ip, $user_invite_code, $ref, time()]);
            }
            response(true, 'Đăng ký tài khoản thành công');
        }

        // Gửi mã OTP xác thực email
        if (isset($data['sendOtpButton'])) {

            if (empty($_SESSION['otpEmail'])) {
                // Lọc dữ liệu đầu vào
                $email = $purifier->purify(trim($data['email']));

                // Kiểm tra rỗng 
                if (isEmptyOrNull($email)) {
                    response(false, 'Vui lòng điền Email đăng ký');
                }

                // Kiểm tra định dạng email
                if (checkEmail($email) == false) {
                    response(false, 'Email không đúng định dạng');
                }

                // Kiểm tra email có tồn tại chưa?
                if (pdo_query_value('SELECT `user_email` FROM `user` WHERE `user_email` = ?', [$email])) {
                    response(false, 'Email đã tồn tại');
                }

                // Gữi email
                $otp     = rand(100000, 999999);                          // Mã OTP
                $otpHash = password_hash($otp, PASSWORD_DEFAULT);  // Mã hóa mã OTP
                $subject = "[CARD2K.COM] Mã OTP đăng ký tài khoản: $otp";
                $type    = 'otpRegister';
                $dataJson = json_encode([
                    'name' => $email,
                    'otp'  => $otp,
                    'year' => date("Y")
                ]);

                // Thêm vào hàng đợi gửi email
                pdo_execute("INSERT INTO `email_queue` (`recipient_email`, `subject`, `type`, `dataJson`) VALUES (?, ?, ?, ?)", [$email, $subject, $type, $dataJson]);

                // Tạo session chứa mã OTP tồn tại trong 5 phút
                $_SESSION['otpEmail'] = [
                    'otp'   => $otpHash,
                    'time'  => time()
                ];

                response(true, 'Gửi mã OTP thành công, Vui lòng check Email');
            } else {
                response(false, 'Mã OTP đã được gửi, vui lòng check Email <br> (nếu bạn nhập sai, hay quay lại sau 5p)');
            }
        }
    } else {
        response(false, 'Bạn đã đăng nhập rồi mà!');
    }
}
