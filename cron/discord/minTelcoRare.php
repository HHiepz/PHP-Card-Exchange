<?php
require('../../core/function.php');
require('../../core/database.php');

const SUCCESS_HAVE_CHANGE = "Đã có sự thay đổi về bản giá phí thấp nhất";
const SUCCESS_NO_CHANGE = "Không có gì thay đổi và không có lỗi trong quá trình thực thi";
const ERROR_WEBHOOK = "Có lỗi trong quá trình gữi tin nhắn webhook";
const ERROR_GET_DATA = "Có lỗi trong quá trình lấy dữ liệu phí";

function hhiepz_getMinDiscountNow($sortDescending = true)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_buyCard'");
    $partner_id = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_buyCard'");
    $partner_key = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_buyCard'");

    $dataCurl = curlGet("https://$partner_server_name/chargingws/v2/getfee?partner_id=$partner_id&partner_key=$partner_key");
    if ($dataCurl == false) {
        return false;
    }
    $dataCurl = json_decode($dataCurl, true);

    // Kiểm tra nếu $dataCurl là mảng hợp lệ
    if (!is_array($dataCurl)) {
        return false;
    }

    $feeVIP = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_vip'");

    $result = [
        ['telco-rare_code' => 'ZING', 'min_value' => 99.0],
        ['telco-rare_code' => 'GARENA', 'min_value' => 99.0],
        ['telco-rare_code' => 'VIETTEL', 'min_value' => 99.0],
        ['telco-rare_code' => 'MOBIFONE', 'min_value' => 99.0],
        ['telco-rare_code' => 'VCOIN', 'min_value' => 99.0],
        ['telco-rare_code' => 'GATE', 'min_value' => 99.0],
        ['telco-rare_code' => 'VINAPHONE', 'min_value' => 99.0],
        ['telco-rare_code' => 'VNMOBI', 'min_value' => 99.0]
    ];

    // Xử lý dữ liệu trả về để cập nhật giá trị phí tối thiểu cho mỗi nhà mạng
    foreach ($dataCurl as $data) {
        foreach ($result as $index => $telco) {
            if ($data['telco'] === $telco['telco-rare_code'] && $data['fees'] < $telco['min_value']) {
                $result[$index]['min_value'] = number_format(($data['fees'] + $feeVIP), 1, '.', '');
                break;
            }
        }
    }

    // Sắp xếp
    usort($result, function ($a, $b) use ($sortDescending) {
        if ($a['min_value'] == $b['min_value']) {
            return 0;
        }

        if ($sortDescending) {
            // Sắp xếp giảm dần
            return ($a['min_value'] < $b['min_value']) ? 1 : -1;
        } else {
            // Sắp xếp tăng dần
            return ($a['min_value'] < $b['min_value']) ? -1 : 1;
        }
    });

    return $result;
}

function templateTele_discord_noti_user($nhaMang, $chietKhau, $danhSach, $rolePing = "<@&1198521180718637127>")
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $timeSend = date('d/m/Y H:i');
    $demoRare = number_format(10000 - (10000 * ($chietKhau / 100)));

    $tempString = "";
    foreach ($danhSach as $item) {
        $min_value = $item['min_value'];
        $telco = $item['telco-rare_code'];
        $tempString .= "\n> ▫️` $min_value% ` $telco ";
    }

    $jsonMessage = [
        "content" => "|| $rolePing ||",
        "embeds" => [
            [
                "description" => "[$timeSend]\n\n**__Thông tin chính:__**\n> Nhà mạng phí **thấp nhất [$nhaMang](https://card2k.com)** \n> Chiết khấu **[$chietKhau%](https://card2k.com/)**\n> vd: *10.000 viettel = $demoRare vnđ*",
                "color" => 16765268
            ],
            [
                "description" => "**__Các nhà mạng còn lại:__**$tempString\n\n**__Dịch vụ do 2K9X quản lý:__**\n> Đổi thẻ cào sang tiền mặt **[card2k.com](https://card2k.com/)**",
                "color" => 4349810,
                "image" => [
                    "url" => "https://i.ibb.co/Vvc0pVQ/login-light-theme-01.png"
                ]
            ]
        ],
        "username" => "CARD2K - THÔNG BÁO CHIẾT KHẤU [RANK VIP]",
        "avatar_url" => "https://i.ibb.co/Ht04Jx2/CARD2-K-COM-2.png",
        "attachments" => []
    ];

    return json_encode($jsonMessage);
}

$link_webhook_discord = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_min_telco_rare'");

if (!empty($link_webhook_discord)) {
    $listMinDiscountNow = hhiepz_getMinDiscountNow(false);
    if ($listMinDiscountNow !== false) {
        $minRareDiscountNow = floatval($listMinDiscountNow[0]['min_value']);
        $minNameDiscountNow = $listMinDiscountNow[0]['telco-rare_code'];

        $last_min_discount = floatval(pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'last_min_telco_rare'"));
        $last_min_telco = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'last_min_telco'");

        if ($minRareDiscountNow != $last_min_discount || $minNameDiscountNow != $last_min_telco) {
            pdo_execute("UPDATE setting SET `value` = $minRareDiscountNow WHERE `name` = 'last_min_telco_rare'");
            pdo_execute("UPDATE setting SET `value` = '$minNameDiscountNow' WHERE `name` = 'last_min_telco'");
            $content = templateTele_discord_noti_user($minNameDiscountNow, $minRareDiscountNow, $listMinDiscountNow);
            $sendDiscord = sendDiscord($link_webhook_discord, $content);
            if ($sendDiscord == false) {
                jsonReturn(false, ERROR_WEBHOOK);
            }
            jsonReturn(true, SUCCESS_HAVE_CHANGE);
        }

        jsonReturn(true, SUCCESS_NO_CHANGE);
    } else {
        jsonReturn(false, ERROR_GET_DATA);
    }
}
