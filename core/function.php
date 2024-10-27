<?php
function getVerifyType($user_info)
{
    if ($user_info['user_is_verify_email'] == 1) {
        return 'Email';
    } else if ($user_info['user_is_verify_2fa'] == 1) {
        return 'Google Authenticator';
    } else {
        return "";
    }
}

// Get Google Authenticator Secret User
function getGoogleAuthSecret($user_id)
{
    return pdo_query_value("SELECT `user_2fa_code` FROM `user` WHERE `user_id` = ?", [$user_id]);
}

// Get time rank user
function getTimeRank()
{
    if (checkToken('request')) {
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $time = pdo_query_value("SELECT `user_rank_expire` FROM `user` WHERE `user_token` = ?", [$token]);
        if ($time == 0) {
            return 'Vĩnh Viễn';
        } else {
            return date('d/m/Y H:i:s', $time);
        }
    } else {
        return false;
    }
}

function insertLogs($user_id, $title, $content)
{
    pdo_execute("INSERT INTO `logs`(`user_id`, `title`, `content`) VALUES (?, ?, ?)", [$user_id, $title, $content]);
}

// Kiểm tra 
function checkFullname($fullname)
{
    return preg_match('/^[a-zA-Z]+$/u', $fullname);
}


// Lấy User Agent người dùng
function getUserAgent()
{
    return $_SERVER['HTTP_USER_AGENT'];
}


// Hàm nếu là Null thì hiển thị - còn là số thì number_format 
function formatNumber($number)
{
    if ((is_numeric($number) || is_float($number)) && $number !== null) {
        return number_format($number);
    } else {
        return '0';
    }
}


function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $data = curl_exec($ch);

    if ($data === false) {
        $error = curl_error($ch);
        error_log("cURL Error: " . $error);
    }

    curl_close($ch);

    return $data;
}

// Hàm nén file .zip giãm dung lượng file
function zipData($source, $destination)
{
    // Tăng giới hạn bộ nhớ cho script PHP
    ini_set('memory_limit', '256M');

    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZipArchive::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/') + 1), ['.', '..'])) {
                continue;
            }

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents_curl($file));
                $zip->setCompressionName(str_replace($source . '/', '', $file), ZipArchive::CM_DEFLATE, 9);
            }
        }
    } elseif (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents_curl($source));
        $zip->setCompressionName(basename($source), ZipArchive::CM_DEFLATE, 9);
    }

    return $zip->close();
}

// Hàm quy đổi kích thước file
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

// Chạy PHP CLI
function runPhpCli($filePath)
{
    $output = shell_exec("php " . escapeshellarg($filePath));
    return $output;
}

// Xác minh reCAPCHA
// True: Không phải là robot
// False: Là robot
function verifyRecaptcha($token, $threshold = 0.5)
{
    if (!isset($token)) {
        return false;
    }

    $google_secret_key = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'ggReCaptcha_secret_key'");
    if (!empty($google_secret_key)) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $dataRecaptcha = [
            'secret' => $google_secret_key,
            'response' => $token
        ];

        // Khởi tạo cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataRecaptcha));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Thực hiện yêu cầu
        $response = curl_exec($ch);

        // Đóng cURL
        curl_close($ch);

        $result = json_decode($response);

        // Kiểm tra kết quả xác minh reCAPTCHA
        if ($result !== null && $result->success && isset($result->score) && $result->score >= $threshold) {
            return true; // Không phải robot
        } else {
            return false; // Có khả năng là robot
        }
    } else {
        // Tắt reCAPTCHA
        pdo_execute("UPDATE `setting` SET `value` = 0 WHERE `name` = 'status_ggReCaptcha'");
        return true;
    }
}


// Lấy email từ mã mời 
function getEmailByInviteCode($invite_code)
{
    return pdo_query_value("SELECT `user_email` FROM `user` WHERE `user_invite_code` = ?", [$invite_code]);
}

// Kiểm tra xem có phải VN không
function getInfoFromIP($ip)
{
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return "Địa chỉ IP không hợp lệ";
    }

    $api_url = "http://ip-api.com/json/" . $ip . "?fields=8609795";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $api_url,
        CURLOPT_TIMEOUT => 10,
    ));
    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        curl_close($curl);
        return false;
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if ($data && $data['status'] == 'success') {
        return $data;
    } else {
        return false;
    }
}

// proxy - vpn - tor - bot - abuse - cloudflare
function checkIfProxyVpnTor($ip)
{
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return "Địa chỉ IP không hợp lệ";
    }

    $api_key = 'ascb8u7tYoWXjWY5YbDscsFyvKOPXFj6'; // Thay thế bằng API key thực của bạn
    $api_url = "https://www.ipqualityscore.com/api/json/ip/" . $api_key . "/" . $ip;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $api_url,
        CURLOPT_TIMEOUT => 10,
    ));
    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        curl_close($curl);
        return false;
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if ($data && $data['success'] == true) {
        return $data;
    } else {
        return false;
    }
}

// Thống Kê Top Nạp
function addTopNapThe($user_id, $money)
{
    // Lấy giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $topNapThe = pdo_query_value("SELECT * FROM `top` WHERE `user_id` = ? AND `month` = ? AND `year` = ?", [$user_id, date('m'), date('Y')]);
    if ($topNapThe == null) {
        pdo_execute("INSERT INTO `top` (`user_id`, `top_cash`, `month`, `year`) VALUES (?, ?, ?, ?)", [$user_id, $money, date('m'), date('Y')]);
    } else {
        pdo_execute("UPDATE `top` SET `top_cash` = `top_cash` + ? WHERE `user_id` = ?", [$money, $user_id]);
    }
}


// Hàm lấy thời gian ở Việt Nam
function getDateTimeNow()
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    return date('Y-m-d H:i:s');
}


// Kiểm tra định dạng URL
function checkUrl($url)
{
    return filter_var($url, FILTER_VALIDATE_URL);
}


// Định dạng PARTNER API
function formatPartner($type, $key = 'name')
{
    $types = [
        'Charging'  => ['name' => 'Đổi thẻ'],
        'BuyCard'   => ['name' => 'Mua Thẻ'],
        'Withdraw'  => ['name' => 'Rút Tiền'],
        'Transfer'  => ['name' => 'Chuyển Tiền']
    ];

    return $types[$type][$key] ?? false;
}


// Định dạng PHƯƠNG THỨC PARTNER API
function formatPartnerAction($action, $key = 'name')
{
    $actions = [
        'GET'  => ['name' => 'GET', 'color' => 'success'],
        'POST' => ['name' => 'POST', 'color' => 'info']
    ];

    return $actions[$action][$key] ?? false;
}


// Định dạng TRẠNG THÁI PARTNER API
function formatPartnerStatus($status, $key = 'name')
{
    $statuses = [
        'non-active'     => ['name' => 'Chưa kích hoạt', 'color' => 'secondary'],
        'active' => ['name' => 'Đang hoạt động', 'color' => 'info'],
    ];

    return $statuses[$status][$key] ?? false;
}


// Các trạng thái rút tiền. Ví dụ: wait, pending, success, fail, cancel
function statusBank($status, $key = 'name')
{
    $statuses = [
        'wait'    => ['name' => 'Chờ Duyệt', 'color' => 'warning'],
        'pending' => ['name' => 'Đang Xử Lý', 'color' => 'warning'],
        'hold'    => ['name' => 'Đơn Treo', 'color' => 'warning'],
        'success' => ['name' => 'Thành Công', 'color' => 'info'],
        'fail'    => ['name' => 'Lỗi', 'color' => 'danger'],
        'cancel'  => ['name' => 'Hủy', 'color' => 'secondary']
    ];

    return $statuses[$status][$key] ?? false;
}


// Các trạng thái thẻ. Ví dụ: wait, success, wrong_amount, fail
function statusCard($status, $key = 'name')
{
    $statuses = [
        'wait'         => ['name' => 'Chờ Duyệt', 'color' => 'warning'],
        'success'      => ['name' => 'Thành Công', 'color' => 'success'],
        'wrong_amount' => ['name' => 'Sai Mệnh Giá', 'color' => 'info'],
        'fail'         => ['name' => 'Lỗi', 'color' => 'danger']
    ];

    return $statuses[$status][$key] ?? false;
}


// Các trạng thái thẻ. Ví dụ: wait, success, wrong_amount, fail
function statusBuyCard($status, $key = 'name')
{
    $statuses = [
        'wait'         => ['name' => 'Chờ Duyệt', 'color' => 'warning'],
        'success'      => ['name' => 'Thành Công', 'color' => 'success'],
        'fail'         => ['name' => 'Lỗi', 'color' => 'danger']
    ];

    return $statuses[$status][$key] ?? false;
}


// Kiểm tra dữ liệu rỗng hoặc null
function isEmptyOrNull($variable)
{
    return (!isset($variable) || empty($variable));
}


function maskEmail($email)
{
    // Tách phần username và domain
    list($username, $domain) = explode('@', $email);

    // Lấy 5 ký tự đầu và cuối của phần username
    $maskedUsername = substr($username, 0, 5) . '****';

    // Trả về kết quả đã được xử lý
    return $maskedUsername . '@' . $domain;
}


// Nhận value, giới hạn hiển thị. Ví dụ: tranhuuhie2004@gmail.com thì hiển thị tran...il.com. Có tham số giới hạn
function limitShow($value, $limit = 3)
{
    $email = explode('@', $value);
    $domain = array_pop($email);
    $name = implode('@', $email);

    if ($domain == 'gmail.com') {
        $domain = '';
    }

    if (strlen($name) <= $limit * 2) {
        return $name . $domain;
    }

    return substr($name, 0, $limit) . '...' . substr($name, -$limit) . $domain;
}


// Format Rank User
function formatRank($rank)
{
    switch ($rank) {
        case 'member':
            return [
                'name' => 'Thành Viên',
                'color' => 'info'
            ];
            break;
        case 'vip':
            return [
                'name' => 'VIP',
                'color' => 'warning'
            ];
            break;
        case 'agency':
            return [
                'name' => 'Đại Lý/API',
                'color' => 'danger'
            ];
            break;
        default:
            return false;
            break;
    }
}


// curlGet : Hàm gửi yêu cầu HTTP GET
function curlGet($url, $timeout = 10)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // Thiết lập thời gian chờ

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        // Nếu xảy ra lỗi (ví dụ như timeout), trả về false
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    return $output;
}


// Lấy tên miền có đuôi
function getDomain()
{
    if (isset($_SERVER['HTTP_HOST'])) {
        // Trả về tên miền kèm theo http:// hoặc https://
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
    } else {
        return "domain_not_available";
    }
}


// Kiểm tra đinh dạng email (phải có đuôi @gmail.com)
function checkEmail($email)
{
    return preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email);
}


// Lọc dữ liệu số điện thoại
function filterPhone($phone)
{
    return preg_replace("/[^0-9]/", "", $phone);
}


// Kiểm tra định dạng số điện thoại (10 - 11 số, bắt đầu bằng 0)
function checkPhone($phone)
{
    $filtered_phone = preg_replace("/[^0-9]/", "", $phone);
    return preg_match('/^0[0-9]{9,10}$/', $filtered_phone);
}


// Kiểm tra định dạng mật khẩu (ít nhất 6 ký tự dài nhất 32 ký tự)
function checkPassword($password)
{
    return preg_match('/^.{6,32}$/', $password);
}


// Lấy IP người dùng
function getIpUser()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Kiểm tra xem có nhiều IP không
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        } else {
            if (filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


// Quản lý dòng tiền user
// $type : add, sub
// $amount : số tiền
// $user_id : ID người dùng
// $note : Ghi chú
// $change_by : ID người thay đổi (mặc định là -1)
function userCash($type, $amount, $user_id, $note, $change_by = -1)
{
    // Validate input parameters
    if (!in_array($type, ['add', 'sub'])) {
        throw new InvalidArgumentException('Invalid type parameter');
    }

    if (!is_numeric($amount) || $amount <= 0) {
        throw new InvalidArgumentException('Invalid amount parameter');
    }

    if (!is_numeric($user_id) || $user_id <= 0) {
        throw new InvalidArgumentException('Invalid user_id parameter');
    }

    // Tiền user trước khi thay đổi
    $user_cash_before = pdo_query_value("SELECT `user_cash` FROM `user` WHERE `user_id` = ?", [$user_id]);

    // Cọng tiền
    if ($type == 'add') {
        pdo_execute("UPDATE `user` SET `user_cash` = `user_cash` + ? WHERE `user_id` = ?", [$amount, $user_id]);
    }

    // Trừ tiền
    if ($type == 'sub') {
        pdo_execute("UPDATE `user` SET `user_cash` = `user_cash` - ? WHERE `user_id` = ?", [$amount, $user_id]);
    }

    // Tiền user sau khi thay đổi
    $user_cash_after = pdo_query_value("SELECT `user_cash` FROM `user` WHERE `user_id` = ?", [$user_id]);

    // Gửi thông báo discord
    $webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_money'");
    if (!empty($webhook)) {
        sendDiscord($webhook, form_discord_money($type, $user_id, getEmailUser($user_id), $change_by, $user_cash_before, $amount, $user_cash_after, $note));
    }

    // Insert the cash transaction into the history
    pdo_execute(
        "INSERT INTO `money` (`user_id`, `money_before`, `money_change`, `money_after`, `money_note`, `money_user_change`) VALUES (?, ?, ?, ?, ?, ?)",
        [$user_id, $user_cash_before, $amount, $user_cash_after, $note, $change_by]
    );
}


function userRank($newRank, $dateRank, $user_id, $reason, $change_by = -1)
{
    // Validate input parameters
    if (!in_array($newRank, ['member', 'vip', 'agency'])) {
        throw new InvalidArgumentException('Invalid newRank parameter');
    }

    if (!is_numeric($dateRank) || $dateRank < -1) {
        throw new InvalidArgumentException('Invalid dateRank parameter');
    }

    if (!is_numeric($user_id) || $user_id <= 0) {
        throw new InvalidArgumentException('Invalid user_id parameter');
    }

    // Rank vĩnh viển
    if ($dateRank == -1) {
        $dateRank = 0;
        pdo_execute("UPDATE `user` SET `user_backup_rank_date` = ? WHERE `user_id` = ?", [0, $user_id]);
    } else {
        $dateRank = time() + $dateRank * 24 * 60 * 60;
        $oldTimeRank = pdo_query_value("SELECT `user_rank_expire` FROM `user` WHERE `user_id` = ?", [$user_id]);
        pdo_execute("UPDATE `user` SET `user_backup_rank_date` = ? WHERE `user_id` = ?", [$oldTimeRank, $user_id]);
    }

    // Rank user trước khi thay đổi
    $user_rank_before = pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_id` = ?", [$user_id]);

    // Thay đổi rank
    pdo_execute("UPDATE `user` SET `user_rank` = ?, `user_rank_expire` = ? WHERE `user_id` = ?", [$newRank, $dateRank, $user_id]);

    // Rank user sau khi thay đổi
    $user_rank_after = pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_id` = ?", [$user_id]);

    // Insert the rank transaction into the history
    pdo_execute(
        "INSERT INTO `rank` (`user_id`, `rank_before`, `rank_change`, `rank_after`, `rank_note`, `rank_user_change`) VALUES (?, ?, ?, ?, ?, ?)",
        [$user_id, $user_rank_before, $newRank, $user_rank_after, $reason, $change_by]
    );
}


// Lấy thông tin user từ token
function getUserInfo($token)
{
    if (checkToken('request')) {
        return pdo_query_one("SELECT `user_id`, `user_email`, `user_rank`, `user_cash`, `user_banned` FROM `user` WHERE `user_token` = ?", [$token]);
    } else {
        return false;
    }
}


// Lấy rank của user
function getUserRank_Token($user_token)
{
    if (checkToken("request")) {
        return pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_token` = ?", [$user_token]);
    }
    return false;
}


// Lấy số tiền thực nhận của thẻ : Request ID, Mã thẻ, Seri thẻ
function getAmountRecieveCard($request_id, $code, $seri)
{
    pdo_query_value("SELECT `card-data_amount_recieve` FROM `card-data` WHERE `card-data_request_id` = ? AND `card-data_code` = ? AND `card-data_seri` = ?", [$request_id, $code, $seri]);
}


// Lấy tổng thực nhận của người dùng : ID, nhà mạng, mệnh giá
function getAmountRecieveUser($user_id, $telco, $amount)
{
    $user_rank       = pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_id` = ?", [$user_id]);                    // Lấy rank của user - member
    $user_name_telco = $user_rank . '_' . $amount;                                                                           // Tên cột trong database telco-rate - member_10000
    $user_fee_telco  = pdo_query_value("SELECT `$user_name_telco` FROM `telco-rare` WHERE `telco-rare_code` = ?", [$telco]); // Lấy phí của user - 10.1
    $amount_recieve  = $amount - ($amount * $user_fee_telco / 100);

    return $amount_recieve;
}


// Lấy phí đổi card của người dùng : ID, nhà mạng, mệnh giá
function getFeeExchange($user_id, $telco, $amount)
{
    $user_rank       = pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_id` = ?", [$user_id]);                    // Lấy rank của user - member
    $user_name_telco = $user_rank . '_' . $amount;                                                                           // Tên cột trong database telco-rate - member_10000
    $user_fee_telco  = pdo_query_value("SELECT `$user_name_telco` FROM `telco-rare` WHERE `telco-rare_code` = ?", [$telco]); // Lấy phí của user - 10.1

    return $user_fee_telco;
}


// Hàm trả về thông báo người dùng trong web
function response($success, $message, $data = null, $continue = false)
{
    $response = array(
        'success' => $success,
        'message' => $message,
    );
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    if (!$continue) {
        exit;
    }
}

// Hàm trả về thông báo API - callback
function jsonReturn($status, $message, $data = null)
{
    $response = array(
        'status' => $status,
        'message' => $message,
    );
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}


// Danh sách nhà mạng [Mã - Tên hiển thị]
function list_telco()
{
    $telco_list = pdo_query("SELECT `telco-rare_code`, `telco-rare_name` FROM `telco-rare` WHERE `telco-rare_status` = 1");

    $result = [];
    foreach ($telco_list as $item) {
        $result[$item['telco-rare_code']] = '✔️ ' . $item['telco-rare_name'];
    }

    return $result;
}


// Danh sách phí đổi thẻ [Nhà mạng - [Mệnh giá - Phí]]
function list_fee_exchange()
{
    // Truy vấn để lấy tất cả các mệnh giá của tất cả các nhà mạng
    $result = pdo_query("SELECT * FROM `telco-rare` WHERE `telco-rare_status` = 1");

    $availableDenominations = [];

    // Duyệt qua tất cả các nhà mạng
    foreach ($result as $row) {
        $telco = $row['telco-rare_code'];
        $availableDenominations[$telco] = [
            'member' => [],
            'vip' => [],
            'agency' => []
        ];

        // Duyệt qua tất cả các mệnh giá
        foreach ($row as $denomination => $value) {
            // Nếu mệnh giá khác null, thêm nó vào danh sách
            if ($denomination !== 'telco-rare_id' && $denomination !== 'telco-rare_code' && $denomination !== 'telco-rare_status' && $value != 0) {
                // Xác định loại mệnh giá (MEMBER, VIP, AGENCY) và số tiền tương ứng
                $type = '';
                if (strpos($denomination, 'member_') === 0) {
                    $type = 'member';
                } elseif (strpos($denomination, 'vip_') === 0) {
                    $type = 'vip';
                } elseif (strpos($denomination, 'agency_') === 0) {
                    $type = 'agency';
                }

                // Lấy số tiền từ mệnh giá
                $amount = substr($denomination, strpos($denomination, '_') + 1);

                // Thêm mệnh giá vào danh sách tương ứng
                $availableDenominations[$telco][$type][$amount] = $value;
            }
        }
    }

    return $availableDenominations;
}



// Danh sách thẻ cào bán ra [Mã - Tên hiển thị]
function list_telco_buyCard()
{
    $card_list = pdo_query("SELECT `card-rare_code`, `card-rare_name` FROM `card-rare` WHERE `card-rare_status` = 1");

    $result = [];
    foreach ($card_list as $item) {
        $result[$item['card-rare_code']] = $item['card-rare_name'];
    }

    return $result;
}

// Kiểm tra nhà mạng có bảo trì không?
function telco_status($telcoRareCode)
{
    $telcoRareCodeClean = strtoupper($telcoRareCode);
    $status = pdo_query_value("SELECT `telco-rare_status` FROM `telco-rare` WHERE `telco-rare_code` = ?", [$telcoRareCodeClean]);
    return ($status == 1) ? true : false;
}


// Danh sách các mệnh giá thẻ cào bán [Loại thẻ - [Danhs sách mệnh giá]]
function list_amount_buyCard()
{
    // Truy vấn để lấy tất cả các mệnh giá của tất cả các nhà mạng
    $result = pdo_query("SELECT * FROM `card-rare`");

    $availableDenominations = [];

    // Duyệt qua tất cả các nhà mạng
    foreach ($result as $row) {
        $telco = $row['card-rare_code'];
        $availableDenominations[$telco] = [];

        // Duyệt qua tất cả các mệnh giá
        foreach ($row as $denomination => $value) {
            // Nếu mệnh giá khác null, thêm nó vào danh sách
            if ($denomination !== 'card-rare_code' && $denomination !== 'card-rare_name' && $value != null) {
                $availableDenominations[$telco][$denomination] = $value;
            }
        }
    }

    return $availableDenominations;
}


// Danh sách định dạng thẻ
function format_card($telco, $seri, $pin)
{
    $telco       = strtoupper($telco);
    $length_seri = strlen($seri);
    $length_pin  = strlen($pin);
    $format = [
        'VIETTEL'      => ['seri' => [11, 14], 'pin' => [13, 15]],
        'MOBIFONE'     => ['seri' => [15], 'pin' => [12]],
        'VINAPHONE'    => ['seri' => [14], 'pin' => [14]],
        'VNMOBI'       => ['seri' => [16], 'pin' => [12]],
        'GARENA'       => ['seri' => [9], 'pin' => [16]],
        'ZING'         => ['seri' => [12], 'pin' => [9]],
        'VCOIN'        => ['seri' => [12], 'pin' => [12]],
        'GATE'         => ['seri' => [10], 'pin' => [10]],
        'APPOTA'       => ['seri' => [12], 'pin' => [12]]
    ];

    if (!array_key_exists($telco, $format)) {
        return false;
    }

    if (!ctype_alnum($seri) || !ctype_alnum($pin)) {
        return false;
    }

    if (!in_array($length_seri, $format[$telco]['seri']) || !in_array($length_pin, $format[$telco]['pin'])) {
        return false;
    }

    return true;
}


// Random string - request_id
function rand_string($length = 11)
{
    // Tạo một chuỗi ngẫu nhiên từ thời gian hiện tại
    $currentTime = microtime();
    $md5Hash = md5($currentTime);

    // Lấy 11 ký tự từ mã MD5
    $randomString = substr($md5Hash, 0, $length);

    // Chuyển đổi thành dạng in hoa
    $randomString = strtoupper($randomString);

    return $randomString;
}


// Random number 
function rand_number($length = 6)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// ==================== USER ====================
// Lấy SỐ DƯ người dùng
function getCashUser()
{
    if (checkToken('request')) {
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        return pdo_query_value("SELECT `user_cash` FROM `user` WHERE `user_token` = ?", [$token]);
    }
}


// Lấy IP người dùng
function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}


// Tạo Token người dùng
function createToken($email, $length = 250)
{
    // Mã hóa email
    $hashedEmail = hash('sha256', $email);

    // Lấy thời gian hiện tại dưới dạng số thực
    $currentTime = microtime(true);

    // Chuyển đổi thời gian hiện tại thành một chuỗi có độ chính xác cao
    $formattedTime = sprintf("%.20F", $currentTime);

    // Tính số lần lặp cần thiết để tạo ra token có độ dài mong muốn
    $iterations = ceil($length / 64);

    $token = '';
    for ($i = 0; $i < $iterations; $i++) {
        // Tạo một chuỗi ngẫu nhiên với entropy cao
        $randomBytes = random_bytes(32); // Độ dài của chuỗi bytes là một nửa số ký tự

        // Chuyển đổi chuỗi bytes sang dạng hex để có chuỗi ký tự
        $randomString = bin2hex($randomBytes);

        // Kết hợp email đã mã hóa, thời gian và chuỗi ngẫu nhiên
        $combinedString = $hashedEmail . $formattedTime . $randomString;

        // Băm chuỗi kết hợp để tạo token
        $token .= hash('sha256', $combinedString);
    }

    // Cắt chuỗi token xuống đến độ dài mong muốn
    $token = substr($token, 0, $length);

    // Trả về token đã băm
    return $token;
}


// Kiểm tra token
function checkToken($type)
{
    $time_expire = 1440; // 24 hours (1440 minutes)

    if ($type == 'verify') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì chuyển hướng về trang đăng nhập
        if ((empty($cookie_token) && empty($sql_token['user_token']))) {
            $domain = getDomain();
            header("Location: $domain/account/login");
            exit;
        } else {
            if (($sql_token['user_is_verify_email'] == 0 && $sql_token['user_is_verify_2fa'] == 0) || $sql_token['user_is_verify'] == 1) {
                $domain = getDomain();
                header("Location: $domain");
                exit;
            }

            // Nếu token đã quá hạn 30 phút tính từ thời gian hiện tại thì chuyển hướng về trang đăng nhập
            $last_login_time = pdo_query_value("SELECT `user_expire_time` FROM `user` WHERE `user_token` = ?", [$cookie_token]);
            if (time() - $last_login_time > $time_expire * 60) {
                // Xóa cookie token
                setcookie('token', '', time() - 3600, '/');

                // Cập nhật token mới và thời gian hết hạn trong database
                $new_token = createToken($sql_token['user_email']);
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_token` = ?", [$new_token, time(), $cookie_token]);

                $domain = getDomain();
                header("Location: $domain/account/login");
                exit;
            }

            $status_server = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
            // Nếu server đang bảo trì thì chuyển hướng về trang bảo trì
            if ($status_server == 0) {
                if ($sql_token['user_admin'] == 0) {
                    $domain = getDomain();
                    header("Location: $domain");
                    exit;
                }
            }
        }
    }

    if ($type == 'goto_verify') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì chuyển hướng về trang đăng nhập
        if ((!empty($cookie_token) && !empty($sql_token['user_token']))) {
            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 1) {
                    return true;
                }
            }
        }
        return false;
    }

    // Đăng nhập, Đăng ký
    if ($type == 'auth') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1", [$cookie_token]); // Lấy token từ database

        // Nếu tồn tại token trong cookie và token trong database khớp với nhau thì chuyển hướng về trang chủ (tức là đã đăng nhập hoặc đăng ký thành công)
        if ((!empty($cookie_token) && !empty($sql_token['user_token']))) {
            $domain = getDomain();

            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 1) {
                    header("Location: $domain/account/verify");
                    exit;
                }
            }
            header("Location: $domain");
            exit;
        }
    }

    // Người dùng
    if ($type == 'client') {
        $cookie_token  = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token     = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì chuyển hướng về trang đăng nhập
        if (empty($cookie_token) || empty($sql_token['user_token'])) {
            $domain = getDomain();
            header("Location: $domain/account/login");
            exit;
        } else {
            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 1) {
                    $domain = getDomain();
                    header("Location: $domain/account/verify");
                    exit;
                }
            }

            // Nếu token đã quá hạn 30 phút tính từ thời gian hiện tại thì chuyển hướng về trang đăng nhập
            if (time() - $sql_token['user_expire_time'] > $time_expire * 60) {
                // Xóa cookie token
                setcookie('token', '', time() - 3600, '/');

                // Cập nhật token mới và thời gian hết hạn trong database
                $new_token = createToken($sql_token['user_email']);
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_token` = ?", [$new_token, time(), $cookie_token]);

                $domain = getDomain();
                header("Location: $domain/account/login");
                exit;
            }
        }

        $status_server = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
        // Nếu server đang bảo trì thì chuyển hướng về trang bảo trì
        if ($status_server == 0) {
            if ($sql_token['user_admin'] == 0) {
                $domain = getDomain();
                header("Location: $domain");
                exit;
            }
        }
    }

    // Quản trị viên
    if ($type == 'admin') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1 AND `user_admin` = 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì chuyển hướng về trang đăng nhập
        if (empty($cookie_token) || empty($sql_token['user_token'])) {
            $domain = getDomain();
            header("Location: $domain/account/login");
            exit;
        } else {
            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 1) {
                    $domain = getDomain();
                    header("Location: $domain/account/verify");
                    exit;
                }
            }

            // Nếu token đã quá hạn 30 phút tính từ thời gian hiện tại thì chuyển hướng về trang đăng nhập
            if (time() - $sql_token['user_expire_time'] > $time_expire * 60) {
                // Cập nhật token mới và thời gian hết hạn trong database
                $new_token = createToken($sql_token['user_email']);
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_token` = ?", [$new_token, time(), $cookie_token]);

                $domain = getDomain();
                header("Location: $domain/account/login");
                exit;
            }
        }
    }

    // Request 
    if ($type == 'request') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì trả về thông báo lỗi
        if (empty($cookie_token) || empty($sql_token['user_token'])) {
            return false;
        } else {
            // Nếu token đã quá hạn 30 phút tính từ thời gian hiện tại thì chuyển hướng về trang đăng nhập
            if (time() - $sql_token['user_expire_time'] > $time_expire * 60) {
                // Cập nhật token mới và thời gian hết hạn trong database
                $new_token = createToken($sql_token['user_email']);
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_token` = ?", [$new_token, time(), $cookie_token]);

                return false;
            }

            $status_server = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
            // Nếu server đang bảo trì thì trả về thông báo lỗi
            if ($status_server == 0) {
                if ($sql_token['user_admin'] == 0) {
                    return false;
                }
            }

            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 1) {
                    return false;
                }
            }
            return true;
        }
    }

    // Request Verify
    if ($type == 'request_verify') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì trả về thông báo lỗi
        if (empty($cookie_token) || empty($sql_token['user_token'])) {
            return false;
        } else {
            // Nếu token đã quá hạn 30 phút tính từ thời gian hiện tại thì chuyển hướng về trang đăng nhập
            if (time() - $sql_token['user_expire_time'] > $time_expire * 60) {
                // Cập nhật token mới và thời gian hết hạn trong database
                $new_token = createToken($sql_token['user_email']);
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_token` = ?", [$new_token, time(), $cookie_token]);

                return false;
            }

            $status_server = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");
            // Nếu server đang bảo trì thì trả về thông báo lỗi
            if ($status_server == 0) {
                if ($sql_token['user_admin'] == 0) {
                    return false;
                }
            }

            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 0) {
                    return false;
                }
            }
            return true;
        }
    }

    // Request Admin
    if ($type == 'request_admin') {
        $cookie_token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
        $sql_token    = pdo_query_one("SELECT * FROM `user` WHERE `user_token` = ? AND `user_banned` != 1 AND `user_admin` = 1", [$cookie_token]); // Lấy token từ database

        // Nếu không tồn tại token trong cookie hoặc token trong database không khớp với nhau thì trả về thông báo lỗi
        if (empty($cookie_token) || empty($sql_token['user_token'])) {
            return false;
        } else {
            // Nếu token đã quá hạn 30 phút tính từ thời gian hiện tại thì chuyển hướng về trang đăng nhập
            if (time() - $sql_token['user_expire_time'] > $time_expire * 60) {
                // Cập nhật token mới và thời gian hết hạn trong database
                $new_token = createToken($sql_token['user_email']);
                pdo_execute("UPDATE `user` SET `user_token` = ?, `user_expire_time` = ? WHERE `user_token` = ?", [$new_token, time(), $cookie_token]);

                return false;
            }

            // Nếu bật xác thực 2 bước thì chuyển hướng về trang xác thực 2 bước
            if ($sql_token['user_is_verify_email'] == 1 || $sql_token['user_is_verify_2fa'] == 1) {
                if ($sql_token['user_is_verify'] != 1) {
                    return false;
                }
            }
            return true;
        }
    }
}


// Lấy ID người dùng từ TOKEN
function getIdUser()
{
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
    return pdo_query_value("SELECT `user_id` FROM `user` WHERE `user_token` = ?", [$token]);
}


// Lấy THÔNG TIN người dùng từ ID
function getInfoUser($user_id)
{
    return pdo_query_one("SELECT * FROM `user` WHERE `user_id` = ?", [$user_id]);
}


// Lấy EMAIL người dùng từ ID
function getEmailUser($user_id)
{
    return pdo_query_value("SELECT `user_email` FROM `user` WHERE `user_id` = ?", [$user_id]);
}


// Lấy RANK người dùng từ ID
function getRankUser($user_id)
{
    return pdo_query_value("SELECT `user_rank` FROM `user` WHERE `user_id` = ?", [$user_id]);
}


// Lấy TÊN người dùng từ ID
function getNameUser($user_id)
{
    return pdo_query_value("SELECT `user_fullname` FROM `user` WHERE `user_id` = ?", [$user_id]);
}

function extractRelevantParams($getParams)
{
    $relevantParams = [];
    $expectedParams = ['param1', 'param2', 'param3']; // Liệt kê các tham số cần thiết

    foreach ($expectedParams as $param) {
        if (isset($getParams[$param])) {
            // Lọc và xác thực giá trị của tham số
            $relevantParams[$param] = filter_var($getParams[$param], FILTER_SANITIZE_STRING);
        }
    }
    return $relevantParams;
}


// ==================== FORM DISCORD ====================
// Báo cáo Bug
function form_discord_bugMoney($request_id, $code, $serial, $amount, $pay, $email, $data)
{
    $color = 14680064;
    $dataStr = json_encode($data, JSON_PRETTY_PRINT);

    $discordMessage = [
        "content" => "<@!690826310737461278>",
        "embeds" => [
            [
                "description" => "**AUTO BAN** Ghi nhận trường hợp BUG (có thể lỗi hệ thống)\n> Trường hợp khớp: ` không phải số ` hoặc ` tiền < 0 `\n\nThông tin\nRequest: `{$request_id}`\nCode: `{$code}`\nSerial: `{$serial}`\nAmount: `{$amount}`\nPay: `{$pay}`\n\nDữ liệu đến\n```{$dataStr}```",
                "color" => $color,
                "footer" => [
                    "text" => "Email: {$email}"
                ]
            ]
        ],
        "attachments" => []
    ];

    return json_encode($discordMessage, JSON_PRETTY_PRINT);
}




// Gửi tin nhắn discord
function sendDiscord($webhook, $message)
{

    $ch = curl_init($webhook);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Thiết lập thời gian chờ
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        // Nếu xảy ra lỗi (ví dụ như timeout), trả về false
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    return false;
}

// Đăng nhập
function form_discord_login($id, $username, $rank, $create_date, $address, $warning_count)
{
    $timestamp = date('Y-m-d H:i:s');

    $data = [
        "content" => null,
        "embeds" => [
            [
                "color" => 16711680,
                "fields" => [
                    [
                        "name" => "ID",
                        "value" => $id,
                        "inline" => true
                    ],
                    [
                        "name" => "Tên Đăng Nhập",
                        "value" => $username,
                        "inline" => true
                    ],
                    [
                        "name" => "Rank",
                        "value" => $rank,
                        "inline" => true
                    ],
                    [
                        "name" => "Ngày Tạo",
                        "value" => $create_date,
                        "inline" => true
                    ],
                    [
                        "name" => "Địa Chỉ",
                        "value" => $address,
                        "inline" => true
                    ],
                    [
                        "name" => "Số Lần Cảnh Cáo",
                        "value" => $warning_count,
                        "inline" => true
                    ]
                ],
                "timestamp" => $timestamp
            ]
        ],
        "username" => "LOGS LOGIN",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];


    return json_encode($data, JSON_PRETTY_PRINT);
}

// Đăng ký
function form_discord_register($id, $username, $rank, $create_date, $address, $email)
{
    $timestamp = date('Y-m-d H:i:s');

    $data = [
        "content" => null,
        "embeds" => [
            [
                "color" => 16711680,
                "fields" => [
                    [
                        "name" => "ID",
                        "value" => $id,
                        "inline" => true
                    ],
                    [
                        "name" => "Tên Đăng Nhập",
                        "value" => $username,
                        "inline" => true
                    ],
                    [
                        "name" => "Rank",
                        "value" => $rank,
                        "inline" => true
                    ],
                    [
                        "name" => "Ngày Tạo",
                        "value" => $create_date,
                        "inline" => true
                    ],
                    [
                        "name" => "Địa Chỉ",
                        "value" => $address,
                        "inline" => true
                    ],
                    [
                        "name" => "Email",
                        "value" => $email,
                        "inline" => true
                    ]
                ],
                "timestamp" => $timestamp
            ]
        ],
        "username" => "LOGS REGISTER",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($data, JSON_PRETTY_PRINT);
}

// Gửi thẻ callback
function form_discord_exchangeCard_callback($id, $email, $rank, $telco, $code, $serial, $value, $amount_user, $profit, $request_id, $status, $callback = false)
{
    $timestamp = date('Y-m-d H:i:s');

    // Nếu wait thì màu vàng, success màu xanh, wrong màu xanh biển, cancel màu đỏ, toán tử 3 ngôi
    $color = $status == 'wait' ? 16776960 : ($status == 'success' ? 65280 : ($status == 'wrong' ? 255 : 16711680));

    $data = [
        "content" => null,
        "embeds" => [
            [
                "color" => $color,
                "fields" => [
                    [
                        "name" => "ID",
                        "value" => $id,
                        "inline" => true
                    ],
                    [
                        "name" => "Email",
                        "value" => $email,
                        "inline" => true
                    ],
                    [
                        "name" => "Rank",
                        "value" => $rank,
                        "inline" => true
                    ],
                    [
                        "name" => "Nhà Mạng",
                        "value" => $telco,
                        "inline" => true
                    ],
                    [
                        "name" => "Code",
                        "value" => $code,
                        "inline" => true
                    ],
                    [
                        "name" => "Serial",
                        "value" => $serial,
                        "inline" => true
                    ],
                    [
                        "name" => "Mệnh Giá",
                        "value" => number_format($value),
                        "inline" => true
                    ],
                    [
                        "name" => "User nhận",
                        "value" => number_format($amount_user),
                        "inline" => true
                    ],
                    [
                        "name" => "Lãi",
                        "value" => number_format($profit),
                        "inline" => true
                    ],
                    [
                        "name" => "Request ID",
                        "value" => $request_id,
                        "inline" => true
                    ],
                    [
                        "name" => "Trạng Thái",
                        "value" => $status,
                        "inline" => true
                    ],
                    [
                        "name" => "Callback?",
                        "value" => $callback,
                        "inline" => true
                    ]
                ],
                "timestamp" => $timestamp
            ]
        ],
        "username" => "CALLBACK EXCHANGE CARD",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($data, JSON_PRETTY_PRINT);
}

// Rút tiền - [ID_User, Email, Rank, Số Tiền Trước, Số Tiền Sau, Thay Đổi, ID Rút, Yêu Cầu, Trạng Thái, Ngân Hàng, Chủ Thẻ, Thời Gian, Tổng Gửi Hôm Nay, Tổng Rút Hôm Nay]
function form_discord_withdraw($id, $email, $rank, $before_amount, $after_amount, $change, $withdrawal_id, $request, $status, $bank, $card_holder, $time, $total_sent_today, $total_withdrawal_today)
{
    $timestamp = date('Y-m-d H:i:s');

    // Nếu wait thì màu vàng, success màu xanh, cancel màu đỏ, toán tử 3 ngôi
    $color = $status == 'wait' ? 16776960 : ($status == 'success' ? 65280 : 16711680);

    $data = [
        "content" => null,
        "embeds" => [
            [
                "color" => $color,
                "fields" => [
                    [
                        "name" => "ID",
                        "value" => $id,
                        "inline" => true
                    ],
                    [
                        "name" => "Email",
                        "value" => $email,
                        "inline" => true
                    ],
                    [
                        "name" => "Rank",
                        "value" => $rank,
                        "inline" => true
                    ],
                    [
                        "name" => "Số Tiền Trước",
                        "value" => number_format($before_amount),
                        "inline" => true
                    ],
                    [
                        "name" => "Số Tiền Sau",
                        "value" => number_format($after_amount),
                        "inline" => true
                    ],
                    [
                        "name" => "Thay Đổi",
                        "value" => number_format($change),
                        "inline" => true
                    ],
                    [
                        "name" => "ID Rút",
                        "value" => $withdrawal_id,
                        "inline" => true
                    ],
                    [
                        "name" => "Yêu Cầu",
                        "value" => number_format($request),
                        "inline" => true
                    ],
                    [
                        "name" => "Trạng Thái",
                        "value" => $status,
                        "inline" => true
                    ],
                    [
                        "name" => "Ngân Hàng",
                        "value" => $bank,
                        "inline" => true
                    ],
                    [
                        "name" => "Chủ Thẻ",
                        "value" => $card_holder,
                        "inline" => true
                    ],
                    [
                        "name" => "Thời Gian",
                        "value" => $time,
                        "inline" => true
                    ],
                    [
                        "name" => "Tổng Gửi Hôm Nay",
                        "value" => number_format($total_sent_today),
                        "inline" => true
                    ],
                    [
                        "name" => "Tổng Rút Hôm Nay",
                        "value" => number_format($total_withdrawal_today),
                        "inline" => true
                    ]
                ],
                "timestamp" => $timestamp
            ]
        ],
        "username" => "RUT TIEN",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($data, JSON_PRETTY_PRINT);
}

// Lịch sử giao dịch - [ID, Email, Người Thay Đổi, Số Tiền Trước, Thay Đổi, Số Tiền Sau, Ghi Chú]
function form_discord_money($type, $id, $email, $changer, $before, $change, $after, $note)
{
    $timestamp = date('Y-m-d H:i:s');

    // Nếu $change = -1 thì "Hệ thống" còn ngược lại thì dùng hàm getNameUser($changer)
    $changer = $changer == -1 ? "Hệ Thống" : getNameUser($changer);

    // Nếu type = add thì màu xanh, sub màu đỏ, toán tử 3 ngôi
    $color = $type == 'add' ? 65280 : 16711680;

    $data = [
        "content" => null,
        "embeds" => [
            [
                "color" => $color,
                "fields" => [
                    [
                        "name" => "ID",
                        "value" => $id,
                        "inline" => true
                    ],
                    [
                        "name" => "Email",
                        "value" => $email,
                        "inline" => true
                    ],
                    [
                        "name" => "Người thay đổi",
                        "value" => $changer,
                        "inline" => true
                    ],
                    [
                        "name" => "Trước",
                        "value" => number_format($before),
                        "inline" => true
                    ],
                    [
                        "name" => "Thay đổi",
                        "value" => number_format($change),
                        "inline" => true
                    ],
                    [
                        "name" => "Sau",
                        "value" => number_format($after),
                        "inline" => true
                    ]
                ],
                "footer" => [
                    "text" => "$note - $timestamp"
                ]
            ]
        ],
        "username" => "LỊCH SỬ DÒNG TIỀN",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($data, JSON_PRETTY_PRINT);
}


// ==================== API WEB MẸ ====================

// Danh sách ngân hàng cho phép rút tiền
function list_bank($key = null)
{

    // CodeDB - Ký tự lưu Database (vì mỗi nhà cung cấp sẽ có code khác nhau)
    // Code   - Ký tự tên bank nhà cung cấp

    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'");

    switch ($partner_server_name) {
        case 'doithecao.vn':
            $banks = [
                [
                    'id'      => 01,
                    'code'    => 'MOMO',
                    'codeDB'  => 'MOMO',
                    'payGate' => 'VIDIENTU',
                    'name'    => 'Ví điện tử MOMO (rút chậm 8 - 24 tiếng)',
                    'status'  => 1
                ],
                [
                    'id'      => 67,
                    'code'    => 'Localbank_TCB',
                    'codeDB'  => 'TECHCOMBANK',
                    'payGate' => 'Localbank',
                    'name'    => 'Ngân hàng Techcombank',
                    'status'  => 1
                ],
                [
                    'id'      => 68,
                    'code'    => 'Localbank_VCB',
                    'codeDB'  => 'VIETCOMBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Vietcombank',
                    'status'  => 1
                ],
                [
                    'id'      => 69,
                    'code'    => 'Localbank_MB',
                    'codeDB'  => 'MBBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng MB Bank',
                    'status'  => 1
                ],
                [
                    'id'      => 71,
                    'code'    => 'Localbank_VIETINBANK',
                    'codeDB'  => 'VIETINBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VietinBank',
                    'status'  => 1
                ],
                [
                    'id'      => 72,
                    'code'    => 'Localbank_BIDV',
                    'codeDB'  => 'BIDV',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng BIDV',
                    'status'  => 1
                ],
                [
                    'id'      => 73,
                    'code'    => 'Localbank_AGRIBANK',
                    'codeDB'  => 'AGRIBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Agribank',
                    'status'  => 1
                ],
                [
                    'id'      => 74,
                    'code'    => 'Localbank_SHB',
                    'codeDB'  => 'SHB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng SHB',
                    'status'  => 1
                ],
                [
                    'id'      => 76,
                    'code'    => 'Localbank_SACOMBANK',
                    'codeDB'  => 'SACOMBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Sacombank',
                    'status'  => 1
                ],
                [
                    'id'      => 77,
                    'code'    => 'Localbank_VPBANK',
                    'codeDB'  => 'VPBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VPBank',
                    'status'  => 1
                ],
                [
                    'id'      => 78,
                    'code'    => 'Localbank_PVCOMBANK',
                    'codeDB'  => 'PVCOMBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng PVcomBank',
                    'status'  => 1
                ],
                [
                    'id'      => 79,
                    'code'    => 'Localbank_VIB',
                    'codeDB'  => 'VIB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VIB',
                    'status'  => 1
                ],
                [
                    'id'      => 88,
                    'code'    => 'Localbank_ACB',
                    'codeDB'  => 'ACB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng ACB',
                    'status'  => 1
                ],
                [
                    'id'      => 93,
                    'code'    => 'Localbank_TPBANK',
                    'codeDB'  => 'TPBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng TPBank',
                    'status'  => 1
                ],
                [
                    'id'      => 95,
                    'code'    => 'Localbank_HDBANK',
                    'codeDB'  => 'HDBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng HDBank',
                    'status'  => 1
                ],
                [
                    'id'      => 105,
                    'code'    => 'Localbank_OJB',
                    'codeDB'  => 'OJB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng OceanBank',
                    'status'  => 1
                ],
                [
                    'id'      => 109,
                    'code'    => 'Localbank_DONGA',
                    'codeDB'  => 'DONGABANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng DongABank',
                    'status'  => 1
                ],
                [
                    'id'      => 110,
                    'code'    => 'Localbank_VAB',
                    'codeDB'  => 'VAB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VietABank',
                    'status'  => 1
                ],
                [
                    'id'      => 114,
                    'code'    => 'Localbank_NAMABA',
                    'codeDB'  => 'NAMABANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Nam A Bank',
                    'status'  => 1
                ],
                [
                    'id'      => 119,
                    'code'    => 'Localbank_BVB',
                    'codeDB'  => 'BVB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Bảo Việt Bank',
                    'status'  => 1
                ],
                [
                    'id'      => 125,
                    'code'    => 'Localbank_SGB',
                    'codeDB'  => 'SGB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng SaiGonBank',
                    'status'  => 1
                ],
                [
                    'id'      => 133,
                    'code'    => 'Localbank_MSB',
                    'codeDB'  => 'MSB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng MSB',
                    'status'  => 1
                ],
                [
                    'id'      => 135,
                    'code'    => 'Localbank_OCB',
                    'codeDB'  => 'OCB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng OCB',
                    'status'  => 1
                ],
            ];
            break;
        case 'thevip247.com':
            $banks = [
                [
                    'id'      => 99,
                    'code'    => 'Momo',
                    'codeDB'  => 'MOMO',
                    'payGate' => 'Momo',
                    'name'    => 'Ví điện tử MOMO',
                    'status'  => 1
                ],
                [
                    'id'      => 13,
                    'code'    => 'Localbank_TCB',
                    'codeDB'  => 'TECHCOMBANK',
                    'payGate' => 'Localbank',
                    'name'    => 'Ngân hàng Techcombank',
                    'status'  => 1
                ],
                [
                    'id'      => 1,
                    'code'    => 'Localbank_VCB',
                    'codeDB'  => 'VIETCOMBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Vietcombank',
                    'status'  => 1
                ],
                [
                    'id'      => 9,
                    'code'    => 'Localbank_MB',
                    'codeDB'  => 'MBBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng MB Bank',
                    'status'  => 1
                ],
                [
                    'id'      => 3,
                    'code'    => 'Localbank_VTB',
                    'codeDB'  => 'VIETINBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VietinBank',
                    'status'  => 1
                ],
                [
                    'id'      => 2,
                    'code'    => 'Localbank_BIDV',
                    'codeDB'  => 'BIDV',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng BIDV',
                    'status'  => 1
                ],
                [
                    'id'      => 4,
                    'code'    => 'Localbank_AGR',
                    'codeDB'  => 'AGRIBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Agribank',
                    'status'  => 0
                ],
                [
                    'id'      => 5,
                    'code'    => 'Localbank_SAC',
                    'codeDB'  => 'SACOMBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Sacombank',
                    'status'  => 1
                ],
                [
                    'id'      => 7,
                    'code'    => 'Localbank_VPBANK',
                    'codeDB'  => 'VPBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VPBank',
                    'status'  => 1
                ],
                [
                    'id'      => 29,
                    'code'    => 'Localbank_PVCOM',
                    'codeDB'  => 'PVCOMBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng PVcomBank',
                    'status'  => 1
                ],
                [
                    'id'      => 20,
                    'code'    => 'Localbank_VIB',
                    'codeDB'  => 'VIB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VIB',
                    'status'  => 1
                ],
                [
                    'id'      => 96,
                    'code'    => 'Localbank_ACB',
                    'codeDB'  => 'ACB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng ACB',
                    'status'  => 1
                ],
                [
                    'id'      => 8,
                    'code'    => 'Localbank_TPBANK',
                    'codeDB'  => 'TPBANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng TPBank',
                    'status'  => 1
                ],
                [
                    'id'      => 30,
                    'code'    => 'Localbank_OCEAN',
                    'codeDB'  => 'OJB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng OceanBank',
                    'status'  => 1
                ],
                [
                    'id'      => 6,
                    'code'    => 'Localbank_DAB',
                    'codeDB'  => 'DONGABANK',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng DongABank',
                    'status'  => 1
                ],
                [
                    'id'      => 19,
                    'code'    => 'Localbank_VAB',
                    'codeDB'  => 'VAB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng VietABank',
                    'status'  => 1
                ],
                [
                    'id'      => 27,
                    'code'    => 'Localbank_BVB',
                    'codeDB'  => 'BVB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng Bảo Việt Bank',
                    'status'  => 1
                ],
                [
                    'id'      => 15,
                    'code'    => 'Localbank_SAIGON',
                    'codeDB'  => 'SGB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng SaiGonBank',
                    'status'  => 1
                ],
                [
                    'id'      => 17,
                    'code'    => 'Localbank_MSB',
                    'codeDB'  => 'MSB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng MSB',
                    'status'  => 1
                ],
                [
                    'id'      => 34,
                    'code'    => 'Localbank_OCB',
                    'codeDB'  => 'OCB',
                    'paygate' => 'Localbank',
                    'name'    => 'Ngân hàng OCB',
                    'status'  => 1
                ]
            ];
            break;
        default:
            $banks = [];
            break;
    }


    if ($key === null) {
        return $banks;
    }

    $values = [];
    foreach ($banks as $bank) {
        if (array_key_exists($key, $bank)) {
            $values[] = $bank[$key];
        }
    }

    return $values;
}

// Trả về trạng thái ngân hàng
function checkBankStatus($codeDB)
{
    $banks = list_bank();

    foreach ($banks as $bank) {
        if ($bank['codeDB'] === $codeDB) {
            return $bank['status'];
        }
    }

    return null;
}


// Lấy tên hiển thị ngân hàng từ codeDB
function showNameBank($codeDB)
{
    $banks = list_bank();

    foreach ($banks as $bank) {
        if ($bank['codeDB'] === $codeDB) {
            return $bank['name'];
        }
    }

    return null;
}


// Lấy tên code ngân hàng từ codeDB
function showCodeBank($codeDB)
{
    $banks = list_bank();

    foreach ($banks as $bank) {
        if ($bank['codeDB'] === $codeDB) {
            return $bank['code'];
        }
    }

    return null;
}


// Lấy nội dung lỗi rút tiền từ status
function checkErrorStatus($status)
{
    $error = [
        '1'    => 'Gửi đơn rút thành công',
        '2'    => 'Không tim thấy người dùng',
        '3'    => 'Dữ liệu không hợp lệ',
        '4'    => 'Số tiền rút không hợp lệ',
        '5'    => 'Ngân hàng không được hỗ trợ',
        '6'    => 'Không tim thấy ví',
        '7'    => 'Số dư không đủ',
        '8'    => 'Hạn mức rút tiền không hợp lệ tại API',
        '9'    => 'Rút không thành công, lỗi không xác định',
        '10'   => 'Lỗi khi tạo đơn rút tiền',
        '11'   => 'Ngân hàng bị tắt tại API',
        '12'   => 'Không hỗ trợ loại tiền này'
    ];

    return $error[$status];
}


// Kiểm tra xem $bank_account có chứa chỉ chữ cái không dấu và dấu cách và có độ dài không vượt quá 100 ký tự hay không
function checkBankName($bank_account)
{
    if (preg_match('/^[a-zA-Z ]{1,36}$/', $bank_account)) {
        return true;
    } else {
        return false;
    }
}



// Kiểm tra xem $bank_number có chứa chỉ số và có độ dài không vượt quá 20 ký tự hay không
function checkBankNumber($bank_number)
{
    if (preg_match('/^[0-9]{1,20}$/', $bank_number)) {
        return true;
    } else {
        return false;
    }
}


// FORM gửi thông báo RÚT THÀNH CÔNG duyệt MOMO
function createDiscordMessage_success($money, $wd_code)
{
    $message = array(
        "content" => "||<@!690826310737461278>||",
        "embeds" => array(
            array(
                "description" => "**DUYET RUT MOMO:**\n```--------------------------------------------------```\n\n▫️ **[Go Go Go!](https://card2k.com/admin/list/withdraw)**\n\n```--------------------------------------------------```\n**__Thông Tin:__**\n>  Số tiền: **{$money}**\n> Mã đơn **{$wd_code}**",
                "color" => 16765268
            )
        ),
        "username" => "-",
        "avatar_url" => "https://i.ibb.co/4N4hQMv/Thi-t-k-ch-a-c-t-n.png",
        "attachments" => array()
    );

    return json_encode($message);
}


// FORM gửi thông báo RÚT THẤT BẠI duyệt MOMO
function createDiscordMessage_fail($money, $wd_code)
{
    $message = array(
        "content" => "||<@!690826310737461278>||",
        "embeds" => array(
            array(
                "description" => "**RUT DUYET MOMO THAT BAI:**\n```--------------------------------------------------```\n\n▫️ **[Go Go Go!](https://card2k.com/admin/list/withdraw)**\n\n```--------------------------------------------------```\n**__Thông Tin:__**\n>  Số tiền: **{$money}**\n> Mã đơn **{$wd_code}**",
                "color" => 16580608
            )
        ),
        "username" => "-",
        "avatar_url" => "https://i.ibb.co/4N4hQMv/Thi-t-k-ch-a-c-t-n.png",
        "attachments" => array()
    );

    return json_encode($message);
}


// FORM gửi thông báo NHẬN TIỀN SHOP
function createShopTransfer($cash, $description, $email)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $timeSend = date('d/m/Y H:i');
    $cash = number_format($cash) . ' đ';
    $message = [
        "content" => null,
        "embeds" => [
            [
                "description" => "[$timeSend]\n\n**__Biến động số dư:__**\n>  Hình thức chuyển tiền nội bộ.\n> Số tiền: **+$cash**\n> Nội dung: `$description`.\n> Người chuyển `$email`.",
                "color" => 16711792,
                "footer" => [
                    "text" => "Nội dung được gữi từ hệ thống CARD2K.COM"
                ]
            ]
        ],
        "username" => "CARD2K.COM | BIẾN ĐỘNG SỐ DƯ",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($message);
}

function createBackup($fileSize, $fileLink)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $timeSend = time();
    $message = [
        "content" => null,
        "embeds" => [
            [
                "title" => "Quá trình sao lưu dữ liệu đã hoàn tất",
                "description" => "Dự liệu của bạn đã được sao lưu thành công vào lúc <t:$timeSend:R>. Vui lòng truy cập liên kết bên dưới để tải file sao lưu về máy tính của bạn.",
                "color" => 15105570,
                "fields" => [
                    [
                        "name" => "Kích thước file",
                        "value" => "$fileSize"
                    ],
                    [
                        "name" => "Liên kết tải xuống",
                        "value" => "[Click vào đây để tải file]($fileLink)"
                    ]
                ],
                "footer" => [
                    "text" => "Vui lòng liên hệ quản trị viên nếu bạn gặp bất kỳ vấn đề nào."
                ]
            ]
        ],
        "attachments" => []
    ];
    return json_encode($message);
}


function createWithdrawProfitErrorMessage($role, $message)
{
    $message = [
        "content" => "<@&$role>",
        "embeds" => [
            [
                "description" => "**❌ ERROR**\n\n> $message",
                "color" => 16580608
            ]
        ],
        "attachments" => []
    ];
    return json_encode($message);
}

function createWithdrawProfitSuccessMessage($role, $message)
{
    $message = [
        "content" => "<@&$role>",
        "embeds" => [
            [
                "description" => "**✅ SUCCESS**\n\n> $message\n\n Đơn rút tiền đã được tạo thành công. Vui lòng đợi trong khi chúng tôi xử lý yêu cầu của bạn.",
                "color" => 5763719
            ]
        ],
        "attachments" => []
    ];
    return json_encode($message);
}


// Hàm xử lý dữ liệu nạp tiền
function processTopupData($list_topup, $list_topup_rare)
{
    $topup_data = [];
    foreach ($list_topup as $topup) {
        if ($topup['topup_status'] == 1) {
            $topup_data[$topup['topup_code']] = [
                'name' => $topup['topup_name'],
                'type' => $topup['topup_type'],
                'rates' => []
            ];
        }
    }

    foreach ($list_topup_rare as $rare) {
        $topup_id = $rare['topup_id'];
        $matching_topups = array_filter($list_topup, function ($t) use ($topup_id) {
            return $t['topup_id'] == $topup_id && $t['topup_status'] == 1;
        });

        if (!empty($matching_topups)) {
            $topup = reset($matching_topups);
            $topup_code = $topup['topup_code'];

            $discounted_price = $rare['topup-rare_value'] - ($rare['topup-rare_value'] * $rare['topup-rare_discount'] / 100);

            $topup_data[$topup_code]['rates'][] = [
                'name' => $rare['topup-rare_name'],
                'value' => $rare['topup-rare_value'],
                'price' => $discounted_price,
                'discount' => $rare['topup-rare_discount']
            ];
        }
    }

    return $topup_data;
}

// Hàm lấy topup_id từ topup_code
function getTopupIdFromCode($code)
{
    $topup = pdo_query_value("SELECT `topup_id` FROM `topup` WHERE `topup_code` = ?", [$code]);
    return $topup;
}

// Hàm lấy topup_name từ topup_id
function getTopupNameFromId($id)
{
    $topup = pdo_query_value("SELECT `topup_name` FROM `topup` WHERE `topup_id` = ?", [$id]);
    return $topup;
}

// Hàm lấy topup_code từ topup_id
function getTopupCodeFromId($id)
{
    $topup = pdo_query_value("SELECT `topup_code` FROM `topup` WHERE `topup_id` = ?", [$id]);
    return $topup;
}

// Hàm lấy discount từ topup_id và topup_rare_value
function getDiscount($topup_id, $topup_rare_value)
{
    $discount = pdo_query_value("SELECT `topup-rare_discount` FROM `topup-rare` WHERE `topup_id` = ? AND `topup-rare_value` = ?", [$topup_id, $topup_rare_value]);
    return $discount;
}

// Trạng thái nạp topup
function getTopupStatus($status)
{
    $statusList = [
        'wait' => [
            'name' => 'Chờ duyệt',
            'color' => 'warning'
        ],
        'pending' => [
            'name' => 'Đang xử lý',
            'color' => 'warning'
        ],
        'completed' => [
            'name' => 'Thành công',
            'color' => 'success'
        ],
        'canceled' => [
            'name' => 'Hủy',
            'color' => 'secondary'
        ],
        'failed' => [
            'name' => 'Thất bại',
            'color' => 'danger'
        ],
        'delayed' => [
            'name' => 'Đang xử lý',
            'color' => 'warning'
        ]
    ];
    return isset($statusList[$status]) ? $statusList[$status] : null;
}

function createTopupErrorMessage($role, $message)
{
    $message = [
        "content" => "<@&$role>",
        "embeds" => [
            [
                "description" => "**❌ LỖI NẠP TOPUP**\n\n> $message",
                "color" => 16711680
            ]
        ],
        "attachments" => []
    ];
    return json_encode($message);
}

function createTopupSuccessMessage($role, $message)
{
    $message = [
        "content" => "<@&$role>",
        "embeds" => [
            [
                "description" => "**✅ NẠP TOPUP THÀNH CÔNG**\n\n> $message",
                "color" => 5763719
            ]
        ],
        "attachments" => []
    ];
    return json_encode($message);
}

// Tạo đơn nạp TOPUP thành công
function form_discord_topup_success($time, $request_id, $phone, $transaction_id, $payment_amount, $discount, $service_code, $amount, $status)
{
    $timestamp = date('Y-m-d H:i:s');

    $color = $status == 'completed' ? 65280 : ($status == 'pending' ? 16776960 : 16711680);

    $data = [
        "content" => null,
        "embeds" => [
            [
                "title" => "✅ TẠO ĐƠN NẠP TOPUP THÀNH CÔNG",
                "color" => $color,
                "fields" => [
                    [
                        "name" => "Thời gian",
                        "value" => $time,
                        "inline" => true
                    ],
                    [
                        "name" => "Mã yêu cầu",
                        "value" => $request_id,
                        "inline" => true
                    ],
                    [
                        "name" => "Số điện thoại",
                        "value" => $phone,
                        "inline" => true
                    ],
                    [
                        "name" => "Mã giao dịch",
                        "value" => $transaction_id,
                        "inline" => true
                    ],
                    [
                        "name" => "Số tiền thanh toán",
                        "value" => number_format($payment_amount) . "đ",
                        "inline" => true
                    ],
                    [
                        "name" => "Chiết khấu",
                        "value" => $discount . "%",
                        "inline" => true
                    ],
                    [
                        "name" => "Mã dịch vụ",
                        "value" => $service_code,
                        "inline" => true
                    ],
                    [
                        "name" => "Mệnh giá",
                        "value" => number_format($amount) . "đ",
                        "inline" => true
                    ],
                    [
                        "name" => "Trạng thái",
                        "value" => $status,
                        "inline" => true
                    ]
                ],
                "timestamp" => $timestamp
            ]
        ],
        "username" => "TOPUP SUCCESS",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($data, JSON_PRETTY_PRINT);
}
