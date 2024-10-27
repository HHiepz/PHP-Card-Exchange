<?php
require('../../core/function.php');
require('../../core/database.php');

// Webhook gửi thông báo
$webhook = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_tongKet'");

if (!empty($webhook)) {
    function tongKet($doanhThu, $tongTheGui, $tongThucNhan, $tongRutTien, $theDung, $theSai, $theSaiMenhGia, $theCho, $muaTheDung, $muaTheCho, $donRut, $donRutCho, $donRutDangChuyen, $donRutThanhCong, $donRutThatBai, $tongThanhVien, $tongAPI)
    {
        $data = [
            "content" => null,
            "embeds" => [
                [
                    "description" => "**__Tổng kết cuối ngày__**\n\n> Doanh thu: **$doanhThu**\n> Tổng thẻ gữi: **$tongTheGui**\n> Tổng thực nhận: **$tongThucNhan**\n> Tổng tiền rút: **$tongRutTien**\n\n▫️ Thẻ đúng: $theDung\n▫️ Thẻ sai mệnh giá: $theSaiMenhGia\n▫️ Thẻ sai: $theSai\n▫️ Thẻ chờ: $theCho\n\n▫️ Mua thẻ thành công: $muaTheDung\n▫️ Mua thẻ chờ: $muaTheCho\n\n▫️ Đơn rút: $donRut\n▫️ Rút chờ: $donRutCho\n▫️ Rút đang chuyển: $donRutDangChuyen\n▫️ Rút thành công: $donRutThanhCong\n▫️ Rút thất bại: $donRutThatBai\n\n**__Thông tin phụ:__**\n** * ** Thành viên: $tongThanhVien\n** * ** API đối tác: $tongAPI",
                    "color" => 648925,
                    "timestamp" => "2024-03-21T11:13:00.000Z",
                    "image" => [
                        "url" => "https://i.ibb.co/TMjVnXX/N-i-dung-o-n-v-n-b-n-c-a-b-n-1.png"
                    ]
                ]
            ],
            "username" => "CARD2K.COM | KẾ TOÁN",
            "avatar_url" => "https://sgv.edu.vn/uploads/images/info/doraemon-trong-tieng-trung-la-gi.png",
            "attachments" => []
        ];

        return json_encode($data);
    }
    
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = date('Y-m-d', strtotime('-1 day'));

    $doanhThu         = pdo_query_value("SELECT SUM(`card-data_profit`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'success'", [$date]);
    $tongTheGui       = pdo_query_value("SELECT SUM(`card-data_amount`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'success'", [$date]);
    $tongThucNhan     = pdo_query_value("SELECT SUM(`card-data_amount_recieve`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'success'", [$date]);
    $tongRutTien      = pdo_query_value("SELECT SUM(`wd_cash`) FROM `withdraw` WHERE DATE(`created_at`) = ? AND `wd_status` = 'success'", [$date]);

    $theDung          = pdo_query_value("SELECT COUNT(`card-data_id`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'success'", [$date]);
    $theSai           = pdo_query_value("SELECT COUNT(`card-data_id`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'fail'", [$date]);
    $theSaiMenhGia    = pdo_query_value("SELECT COUNT(`card-data_id`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'wrong_amount'", [$date]);
    $theCho           = pdo_query_value("SELECT COUNT(`card-data_id`) FROM `card-data` WHERE DATE(`card-data_created_at`) = ? AND `card-data_status` = 'wait'", [$date]);

    $muaTheDung       = pdo_query_value("SELECT COUNT(`buy-card-order_id`) FROM `buy-card-order` WHERE DATE(`created_at`) = ? AND `buy-card-order_status` = 'success'", [$date]);
    $muaTheCho        = pdo_query_value("SELECT COUNT(`buy-card-order_id`) FROM `buy-card-order` WHERE DATE(`created_at`) = ? AND `buy-card-order_status` = 'wait'", [$date]);

    $donRut           = pdo_query_value("SELECT COUNT(`wd_id`) FROM `withdraw` WHERE DATE(`created_at`) = ?", [$date]);
    $donRutCho        = pdo_query_value("SELECT COUNT(`wd_id`) FROM `withdraw` WHERE DATE(`created_at`) = ? AND `wd_status` = 'wait'", [$date]);
    $donRutDangChuyen = pdo_query_value("SELECT COUNT(`wd_id`) FROM `withdraw` WHERE DATE(`created_at`) = ? AND `wd_status` = 'pending'", [$date]);
    $donRutThanhCong  = pdo_query_value("SELECT COUNT(`wd_id`) FROM `withdraw` WHERE DATE(`created_at`) = ? AND `wd_status` = 'success'", [$date]);
    $donRutThatBai    = pdo_query_value("SELECT COUNT(`wd_id`) FROM `withdraw` WHERE DATE(`created_at`) = ? AND `wd_status` = 'fail'", [$date]);

    $tongThanhVien    = pdo_query_value("SELECT COUNT(`user_id`) FROM `user`");
    $tongAPI          = pdo_query_value("SELECT COUNT(`partner_id`) FROM `partner`");

    sendDiscord($webhook, tongKet(formatNumber($doanhThu), formatNumber($tongTheGui), formatNumber($tongThucNhan), formatNumber($tongRutTien),  $theDung, $theSai, $theSaiMenhGia, $theCho, $muaTheDung, $muaTheCho, $donRut, $donRutCho, $donRutDangChuyen, $donRutThanhCong, $donRutThatBai, $tongThanhVien, $tongAPI));
}
