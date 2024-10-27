<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Kiểm tra quyền
checkToken('admin');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

// Kiểm tra có tồn tại email không
if (!isset($_GET['user_email'])) {
    header("Location:" . getDomain() . "/admin/list/member");
    die;
}

// Kiểm tra email có tồn tại không
$user_email   = $purifier->purify($_GET['user_email']);
$memberDetail = pdo_query_one("SELECT * FROM `user` WHERE `user_email` = ?", [$user_email]);
if (empty($memberDetail)) {
    header("Location:" . getDomain() . "/admin/list/member");
    die;
}

// Lấy thông tin
$user_id          = $memberDetail['user_id'];
$history_cardData = pdo_query("SELECT * FROM `card-data` WHERE `user_id` = ?", [$user_id]);                                                        // Lịch sử đổi thẻ
$history_withdraw = pdo_query("SELECT * FROM `withdraw` WHERE `user_id` = ?", [$user_id]);                                                         // Lịch sử rút tiền
$history_buyCard  = pdo_query("SELECT * FROM `buy-card-order` WHERE `user_id` = ?", [$user_id]);                                                   // Lịch sử mua thẻ
$history_money    = pdo_query("SELECT * FROM `money` WHERE `user_id` = ?", [$user_id]);                                                            // Lịch sử dòng tiền
$history_rank     = pdo_query("SELECT * FROM `rank` WHERE `user_id` = ?", [$user_id]);                                                             // Lịch sử rank
$history_transfer = pdo_query("SELECT * FROM `transfer` WHERE `transfer_user_from` = ? OR `transfer_user_to` = ?", [$user_id, $user_id]);          // Lịch sử chuyển tiền
$history_invite   = pdo_query("SELECT * FROM `user` WHERE `user_invite_by` = ?", [$memberDetail['user_invite_code']]);                             // Lịch sử mời người dùng
$user_profit      = pdo_query_value("SELECT SUM(`card-data_profit`) AS total_profit FROM `card-data` WHERE `user_id` = ?", [$user_id]);            // Lợi nhuận

// Hàm lấy tổng số rút tiền
function getTotalAmountWithdraw($user_id, $days, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = $type === 'd' ? date('Y-m-d', strtotime("-$days days")) : date('Y-m-01', strtotime("-$days months"));
    return pdo_query_value("SELECT SUM(`wd_cash`) AS total_withdraw_today FROM `withdraw` WHERE DATE(`created_at`) >= ? AND `wd_status` = 'success' AND `user_id` = ?", [$date, $user_id]);
}

// Hàm lấy tổng số thực nhận
function getTotalAmountCard($user_id, $days, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = $type === 'd' ? date('Y-m-d', strtotime("-$days days")) : date('Y-m-01', strtotime("-$days months"));
    return pdo_query_value("SELECT SUM(`card-data_amount_recieve`) AS total_received_today FROM `card-data` WHERE DATE(`card-data_created_at`) >= ? AND `card-data_status` != 'wait' AND `user_id` = ?", [$date, $user_id]);
}

// Hàm lấy tổng số đổi thẻ
function getTotalAmountCardChange($user_id, $days, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = $type === 'd' ? date('Y-m-d', strtotime("-$days days")) : date('Y-m-01', strtotime("-$days months"));
    return pdo_query_value("SELECT SUM(`card-data_amount_real`) AS total_change_today FROM `card-data` WHERE DATE(`card-data_created_at`) >= ? AND `card-data_status` != 'wait' AND `user_id` = ?", [$date, $user_id]);
}

// Hàm lấy tổng số thẻ theo trạng thái
function getTotalAmountCardByStatus($user_id, $status)
{
    return pdo_query_value("SELECT COUNT(`card-data_id`) AS total_card FROM `card-data` WHERE `card-data_status` = ? AND `user_id` = ?", [$status, $user_id]);
}

// Header
$title_website = 'Chi tiết thành viên';
require('../../../layout/admin/header.php');
?>
<div class="hp-main-layout-content">
    <input type="hidden" value="<?= $memberDetail['user_id'] ?>" id="user_id">

    <div class="row mb-32 gy-32">
        <div class="col-12">
            <h1 class="display-4 fs-2">Chi tiết thành viên: <span class="text-danger"><?= $memberDetail['user_email'] ?></span></h1>
        </div>
    </div>

    <?php
    if ($memberDetail['user_banned'] == 1) {
    ?>
        <div class="col-12 mb-16">
            <div class="alert alert-danger" role="alert">
                <strong>BANNED ACCOUNT</strong> - <?= $memberDetail['user_banned_reason'] ?>
            </div>
        </div>
    <?php
    }
    ?>

    <!-- Thông tin chi tiết -->
    <div class="col-12 mb-16">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Lời lãi:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-<?= ($user_profit > 0) ? "success" : "danger" ?> fw-bold"><?= formatNumber($user_profit) ?>đ</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Tài khoản:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['user_login'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Email:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['user_email'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Địa chỉ IP:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['user_ip'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Số điện thoại:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['user_phone'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Cấp bậc:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="badge badge-<?= formatRank($memberDetail['user_rank'])['color'] ?>"><?= formatRank($memberDetail['user_rank'])['name'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Thời hạn rank:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0">
                                                    <?= $memberDetail['user_rank_expire'] == 0 ? 'Vĩnh viễn' : date("Y-m-d", $memberDetail['user_rank_expire']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Dịch vụ discord:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0">
                                                    <?= $memberDetail['expire_noti_transfer'] == 0 ? 'Chưa kích hoạt' : date("Y-m-d", $memberDetail['expire_noti_transfer']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Ngày đăng ký:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['created_at'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Lần cuối truy cập:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['updated_at'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Mã mời:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $memberDetail['user_invite_code'] ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Được mời bởi:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0">
                                                    <?= empty($memberDetail['user_invite_by']) ? '-' : getEmailByInviteCode($memberDetail['user_invite_by']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Số dư hiện tại:</span>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0 fw-bold fs-5"><?= number_format($memberDetail['user_cash']) ?>đ</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <span class="hp-p1-body">Hành động:</span>
                                            </div>
                                            <div class="col-12 col-md-10 d-flex flex-column flex-md-row">
                                                <?php
                                                if ($memberDetail['user_banned'] == 0) {
                                                ?>
                                                    <!-- Khóa tài khoản -->
                                                    <button type="button" class="btn btn-dashed text-danger border-danger hp-hover-text-color-danger-2 hp-hover-border-color-danger-2 mb-2 mb-md-0 mr-2" id="blockUser">
                                                        Khóa
                                                    </button>
                                                <?php
                                                } else {
                                                ?>
                                                    <!-- Mở khóa tài khoản -->
                                                    <button type="button" class="btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2 mb-2 mb-md-0 mr-2" id="unblockUser">
                                                        Mở khóa
                                                    </button>
                                                <?php
                                                }
                                                ?>
                                                <button type="button" class="btn btn-dashed text-warning border-warning hp-hover-text-color-warning-2 hp-hover-border-color-warning-2 mb-2 mb-md-0" id="logoutAccount">
                                                    Logout
                                                </button>
                                                <button type="button" class="btn btn-dashed text-danger border-danger hp-hover-text-color-danger-2 hp-hover-border-color-danger-2 mb-2 mb-md-0" id="deleteBankingAccount">
                                                    Xóa toàn bộ banking
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="col-12 mb-16">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng thực nhận hôm nay:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCard($user_id, 0, 'd')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng đổi thẻ hôm nay:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCardChange($user_id, 0, 'd')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng rút tiền hôm nay:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountWithdraw($user_id, 0, 'd')) ?>đ</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng thực nhận 3 ngày:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCard($user_id, 3, 'd')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng đổi thẻ 3 ngày:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCardChange($user_id, 3, 'd')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng rút tiền 3 ngày:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountWithdraw($user_id, 3, 'd')) ?>đ</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng thực nhận 7 ngày:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCard($user_id, 7, 'd')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng đổi thẻ 7 ngày:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCardChange($user_id, 7, 'd')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng rút tiền 7 ngày:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountWithdraw($user_id, 7, 'd')) ?>đ</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng thực nhận tháng <?= date('m') ?>:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCard($user_id, 0, 'm')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng đổi thẻ tháng <?= date('m') ?>:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountCardChange($user_id, 0, 'm')) ?>đ</span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Tổng rút tiền tháng <?= date('m') ?>:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= formatNumber(getTotalAmountWithdraw($user_id, 0, 'm')) ?>đ</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="col-12 mb-16">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Thẻ đúng:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= number_format(getTotalAmountCardByStatus($user_id, 'success')) ?></span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Thẻ sai:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= number_format(getTotalAmountCardByStatus($user_id, 'fail')) ?></span>
                                            </div>
                                            <div class="col-8 col-md-2">
                                                <span class="hp-p1-body">Thẻ sai mệnh giá:</span>
                                            </div>
                                            <div class="col-4 col-md-2">
                                                <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= number_format(getTotalAmountCardByStatus($user_id, 'wrong_amount')) ?></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cập nhật chỉnh sửa -->
    <div class="col-12 mb-16">
        <div class="row g-16">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title text-center">Cập nhật - chỉnh sửa</h3>
                                <div class="divider"></div>
                            </div>
                            <div class="block-content">
                                <div class="mb-16">
                                    <label for="pass">Mật khẩu mới:</label>
                                    <div class="form-group w-100 row">
                                        <div class="col-12 col-md-10">
                                            <input class="form-control form-control-alt mb-16" id="changePassword_newPass" placeholder="Nhập mật khẩu" type="text" />
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="changePassword">
                                                Đổi mật khẩu
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-16">
                                    <label for="pass">Email mới:</label>
                                    <div class="form-group w-100 row">
                                        <div class="col-12 col-md-10">
                                            <input class="form-control form-control-alt mb-16" id="changeEmail_newEmail" placeholder="Nhập mật khẩu" type="text" />
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="changeEmail">
                                                Đổi Email
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-16">
                                    <label for="pass">Số điện thoại mới:</label>
                                    <div class="form-group w-100 row">
                                        <div class="col-12 col-md-10">
                                            <input class="form-control form-control-alt mb-16" id="changePhone_newPhone" placeholder="Nhập mật khẩu" type="text" />
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="changePhone">
                                                Số điện thoại
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-16">
                                    <label for="pass">Tiền tệ:</label>
                                    <div class="form-group w-100 row">
                                        <div class="col-12 col-md-2">
                                            <!-- Select Cộng, trừ -->
                                            <select class="form-control mb-16" id="changeMoney_action">
                                                <option selected disabled>Cộng / Trừ</option>
                                                <option value="add">Cộng</option>
                                                <option value="sub">Trừ</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <input class="form-control form-control-alt mb-16" id="changeMoney_money" placeholder="Nhập số tiền" min="0" type="number" />
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input class="form-control form-control-alt mb-16" id="changeMoney_reason" placeholder="Lý do" type="text" />
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="w-100 btn btn-dashed text-warning border-warning hp-hover-text-color-warning-2 hp-hover-border-color-warning-2" id="changeMoney">
                                                Cập nhật số dư
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-16">
                                    <label for="pass">Rank:</label>
                                    <div class="form-group w-100 row">
                                        <div class="col-12 col-md-2">
                                            <select class="form-control mb-16" id="changeRank_rank">
                                                <option selected disabled>Chọn rank</option>
                                                <option value="member">Thành Viên</option>
                                                <option value="vip">VIP</option>
                                                <option value="agency">Đại lý</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <input class="form-control form-control-alt mb-16" id="changeRank_dateRank" placeholder="Nhập ngày" min="-1" type="number" />
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input class="form-control form-control-alt mb-16" id="changeRank_reason" placeholder="Lý do" type="text" />
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="w-100 btn btn-dashed text-warning border-warning hp-hover-text-color-warning-2 hp-hover-border-color-warning-2" id="changeRank">
                                                Cập nhật rank
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-16">
                                    <label for="pass">Dịch vụ thống báo nhận tiền discord:</label>
                                    <div class="form-group w-100 row">
                                        <div class="col-12 col-md-10">
                                            <input class="form-control form-control-alt mb-16" id="changeNotiTransfer_date" placeholder="Nhập ngày" min="-1" type="number" />
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button type="button" class="w-100 btn btn-dashed text-warning border-warning hp-hover-text-color-warning-2 hp-hover-border-color-warning-2" id="changeNotiTransfer">
                                                Cập nhật
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lich sử rút tiền -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử RÚT TIỀN</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-withdraw" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Số tiền</th>
                                            <th class="text-nowrap">Ngân hàng</th>
                                            <th class="text-nowrap">Tên tài khoản</th>
                                            <th class="text-nowrap">Số tài khoản</th>
                                            <th class="text-nowrap">Trạng thái API</th>
                                            <th class="text-nowrap">Message API</th>
                                            <th class="text-nowrap">Thời gian</th>
                                            <th class="text-nowrap">Thời gian duyệt</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_withdraw as $withdraw) {
                                            $wd_message_api = empty($withdraw['wd_api_message']) ? ' - ' : $withdraw['wd_api_message'];
                                            $wd_status_api  = empty($withdraw['wd_api_status']) ? ' - ' : $withdraw['wd_api_status'];
                                            $wd_updated_api = empty($withdraw['wd_updated_api']) ? ' - ' : $withdraw['wd_updated_api'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= statusBank($withdraw['wd_status'], "color") ?>"><?= statusBank($withdraw['wd_status']) ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $withdraw['wd_code'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="text-danger"><?= number_format($withdraw['wd_cash']) ?></span>
                                                </td>
                                                <td class="text-nowrap"> <?= showNameBank($withdraw['wd_bank_name']) ?> </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $withdraw['wd_bank_owner'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $withdraw['wd_number_account'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge"><?= $wd_status_api ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $wd_message_api ?></span>
                                                </td>
                                                <td class="text-nowrap"> <?= $withdraw['created_at'] ?> </td>
                                                <td class="text-nowrap"> <?= $wd_updated_api ?> </td>
                                                <td class="text-nowrap">
                                                    <!-- Reset lệnh rút -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary history_withdraw_reload" data-history-withdraw-code="<?= $withdraw['wd_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset lệnh rút">
                                                        <i class="fa-solid fa-rotate-right"></i>
                                                    </button>
                                                    <!-- Duyệt -->
                                                    <button type="button" class="btn btn-sm btn-outline-success history_withdraw_duyet" data-history-withdraw-code="<?= $withdraw['wd_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Duyệt">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <!-- Hủy không refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning history_withdraw_cancel_refurn" data-history-withdraw-code="<?= $withdraw['wd_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hủy Refurn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                    <!-- Hủy refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger history_withdraw_cancel_nonRefurn" data-history-withdraw-code="<?= $withdraw['wd_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hủy KHÔNG Refurn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>

    <!-- Lich sử đổi thẻ -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử ĐỔI THẺ</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-exCard" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Mã nạp</th>
                                            <th class="text-nowrap">Serial</th>
                                            <th class="text-nowrap">Mạng</th>
                                            <th class="text-nowrap">Tổng gửi</th>
                                            <th class="text-nowrap">Tổng thực</th>
                                            <th class="text-nowrap">Phí</th>
                                            <th class="text-nowrap">Phạt</th>
                                            <th class="text-nowrap">Nhận</th>
                                            <th class="text-nowrap">Ngày tháng</th>
                                            <th class="text-nowrap">Request ID</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_cardData as $value) {
                                            $request_partner_id = empty($value['card-data_partner_request_id']) ? ' - ' : $value['card-data_partner_request_id'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= statusCard($value['card-data_status'], "color") ?>">
                                                        <?= statusCard($value['card-data_status']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $value['card-data_request_id'] ?></span>
                                                </td>
                                                <td class="text-nowrap"><?= $value['card-data_code'] ?></td>
                                                <td class="text-nowrap"><?= $value['card-data_seri'] ?></td>
                                                <td class="text-nowrap"><?= $value['card-data_telco'] ?></td>
                                                <td class="text-nowrap"><?= number_format($value['card-data_amount']) ?></td>
                                                <td class="text-nowrap"><?= number_format($value['card-data_amount_real']) ?></td>
                                                <td class="text-nowrap"><?= $value['card-data_fee'] ?>%</td>
                                                <td class="text-nowrap"><?= number_format($value['card-data_punish']) ?></td>
                                                <td class="text-nowrap"><?= number_format($value['card-data_amount_recieve']) ?></td>
                                                <td class="text-nowrap"><?= $value['card-data_created_at'] ?></td>
                                                <td class="text-nowrap"><?= $request_partner_id ?></td>
                                                <td class="text-nowrap">
                                                    <!-- Gửi card web mẹ -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary history_card_send" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Gửi card web mẹ">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                    </button>
                                                    <!-- Callback về web member -->
                                                    <button type="button" class="btn btn-sm btn-outline-success history_card_callback_user" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-history-card-callback-user="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Callback về member">
                                                        <i class="fa-solid fa-phone"></i>
                                                    </button>
                                                    <!-- Duyệt refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning history_card_duyet_refurn" data-bs-toggle="tooltip" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-placement="top" title="Duyệt Refurn">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <!-- Duyệt không refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger history_card_duyet_nonRefurn" data-bs-toggle="tooltip" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-placement="top" title="Duyệt KHÔNG Refurn">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <!-- Duyệt sai mệnh giá -->
                                                    <button type="button" class="btn btn-sm btn-outline-info history_card_duyet_saiMenhGia" data-bs-toggle="tooltip" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-placement="top" title="Duyệt sai mệnh giá">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <!-- Hủy không refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger history_card_cancel_nonRefurn" data-bs-toggle="tooltip" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-placement="top" title="Hủy KHÔNG Refurn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>
    </div>

    <!-- Lịch sử mua thẻ -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử MUA THẺ</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-buyCard" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Nhà mạng</th>
                                            <th class="text-nowrap">Mệnh giá</th>
                                            <th class="text-nowrap">Số lượng</th>
                                            <th class="text-nowrap">Trạng thái API</th>
                                            <th class="text-nowrap">Message API</th>
                                            <th class="text-nowrap">Data API</th>
                                            <th class="text-nowrap">Tổng thanh toán</th>
                                            <th class="text-nowrap">Thời gian</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_buyCard as $buyCard) {
                                            $buyCard_status_api  = empty($buyCard['buy-card-order_api_status']) ? ' - ' : $buyCard['buy-card-order_api_status'];
                                            $buyCard_message_api = empty($buyCard['buy-card-order_api_message']) ? ' - ' : $buyCard['buy-card-order_api_message'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= statusBuyCard($buyCard['buy-card-order_status'], "color") ?>"><?= statusBuyCard($buyCard['buy-card-order_status']) ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $buyCard['buy-card-order_code'] ?></span>
                                                </td>
                                                <td class="text-nowrap"><?= $buyCard['buy-card-order_telco'] ?></td>
                                                <td class="text-nowrap"><?= number_format($buyCard['buy-card-order_price']) ?>đ</td>
                                                <td class="text-nowrap"><?= $buyCard['buy-card-order_quantity'] ?></td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge"><?= $buyCard_status_api ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $buyCard_message_api ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <!-- icon copy nội dung -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy nội dung">
                                                        <i class="fa-solid fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td class="text-nowrap">
                                                    <?= number_format($buyCard['buy-card-order_total_pay']) ?>
                                                </td>
                                                <td class="text-nowrap"> <?= $buyCard['created_at'] ?> </td>
                                                <td class="text-nowrap">
                                                    <!-- Xem chi tiết -->
                                                    <a href="<?= getDomain() . "/admin/list/buyCardDetail/" . $buyCard['buy-card-order_code'] ?>" type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <!-- Hủy không refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning buyCard_cancel_refurn" data-buycard-code="<?= $buyCard['buy-card-order_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hủy Refurn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                    <!-- Hủy refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger buyCard_cancel_non_refurn" data-buycard-code="<?= $buyCard['buy-card-order_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hủy KHÔNG Refurn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>

    <!-- Lịch sử dòng tiền -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử DÒNG TIỀN</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-money" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">User Email</th>
                                            <th class="text-nowrap">Số tiền trước</th>
                                            <th class="text-nowrap">Thay đổi</th>
                                            <th class="text-nowrap">Số tiền sau</th>
                                            <th class="text-nowrap">Người thay đổi</th>
                                            <th class="text-nowrap">Ghi chú</th>
                                            <th class="text-nowrap">Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_money as $money) {
                                            $money_user_change = ($money['money_user_change'] == -1) ? 'Hệ Thống' : getNameUser($money['money_user_change']);
                                            $color_money_change = ($money['money_before'] > $money['money_after']) ? 'text-danger' : 'text-success';
                                        ?>
                                            <tr>
                                                <td class="text-nowrap"> <span class="badge"><?= getEmailUser($money['user_id']) ?></span> </td>
                                                <td class="text-nowrap"> <span><?= number_format($money['money_before']) ?></span> </td>
                                                <td class="text-nowrap"> <span class="<?= $color_money_change ?>"><?= number_format($money['money_change']) ?></span> </td>
                                                <td class="text-nowrap"> <span><?= number_format($money['money_after']) ?></span> </td>
                                                <td class="text-nowrap"> <span class="badge"><?= $money_user_change ?></span> </td>
                                                <td class="text-nowrap"> <span><?= $money['money_note'] ?></span> </td>
                                                <td class="text-nowrap"> <span><?= $money['created_at'] ?></span> </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>

    <!-- Lịch sử nâng rank -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử RANK</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-rank" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">User Email</th>
                                            <th class="text-nowrap">Rank trước</th>
                                            <th class="text-nowrap">Thay đổi</th>
                                            <th class="text-nowrap">Người thay đổi</th>
                                            <th class="text-nowrap">Ghi chú</th>
                                            <th class="text-nowrap">Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_rank as $rank) {
                                            $rank_user_change = ($rank['rank_user_change'] == -1) ? 'Hệ Thống' : getNameUser($rank['rank_user_change']);
                                        ?>
                                            <tr>
                                                <td class="text-nowrap"> <span class="badge"><?= getEmailUser($rank['user_id']) ?></span> </td>
                                                <td class="text-nowrap"> <span class="badge badge-<?= formatRank($rank['rank_before'])['color'] ?>"><?= formatRank($rank['rank_before'])['name'] ?></span> </td>
                                                <td class="text-nowrap"> <span class="badge badge-<?= formatRank($rank['rank_change'])['color'] ?>"><?= formatRank($rank['rank_change'])['name'] ?></span> </td>
                                                <td class="text-nowrap"> <span class="badge"><?= $rank_user_change ?></span> </td>
                                                <td class="text-nowrap"> <span><?= $rank['rank_note'] ?></span> </td>
                                                <td class="text-nowrap"> <span><?= $rank['created_at'] ?></span> </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>

    <!-- Lịch sử chuyển tiền  -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử CHUYỂN TIỀN</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_history-transfer" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Số tiền</th>
                                            <th class="text-nowrap">Người nhận</th>
                                            <th class="text-nowrap">Nội dung</th>
                                            <th class="text-nowrap">Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($history_transfer as $value) {
                                            $transfer_description = empty($value['transfer_description']) ? ' - ' : $value['transfer_description'];
                                            $transfer_user_to = getEmailUser($value['transfer_user_to']);
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <span class="badge">
                                                        <?= $value['transfer_code'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="text-danger"><?= number_format($value['transfer_cash']) ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() . "/admin/list/memberDetail/$transfer_user_to"  ?>" class="badge">
                                                        <?= $transfer_user_to ?>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap"><?= $transfer_description ?></td>
                                                <td class="text-nowrap"><?= $value['created_at'] ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>

    <!-- Lịch sử mời người dùng -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử MỜI NGƯỜI DÙNG</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-invite" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">Email cấp dưới</th>
                                            <th class="text-nowrap">Ngày đăng ký</th>
                                            <th class="text-nowrap">Lần cuối</th>
                                            <th class="text-nowrap">User IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($history_invite as $value) {
                                            $invite_user_email = $value['user_email'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() . "/admin/list/memberDetail/$invite_user_email" ?>" class="badge">
                                                        <?= $invite_user_email ?>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap"><?= $value['created_at'] ?></td>
                                                <td class="text-nowrap"><?= $value['updated_at'] ?></td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $value['user_ip'] ?></span>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>

</div>
<?php
require('../../../layout/admin/footer.php');
?>
<script>
    new DataTable('#data-table_list-withdraw');
    new DataTable('#data-table_list-exCard');
    new DataTable('#data-table_list-buyCard');
    new DataTable('#data-table_list-money');
    new DataTable('#data-table_list-rank');
    new DataTable('#data-table_history-transfer');
    new DataTable('#data-table_list-invite');

    // RÚT TIỀN, ĐỔI THẺ
    function handleButtonClick(buttonClass, action, message, keyGet, valueKeyGet) {
        $(document).on('click', buttonClass, function() {
            Swal.fire({
                title: '',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tôi đã chắc chắn!',
                onOpen: () => {
                    Swal.showLoading();
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.disabled = true;
                    setTimeout(() => {
                        confirmButton.disabled = false;
                        Swal.hideLoading();
                    }, 1500);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = {};
                    data[keyGet] = $(this).data(valueKeyGet);
                    data[action] = true;

                    $.post('../../../../ajaxs/admin/action/memberDetail.php', {
                        data: JSON.stringify(data)
                    }, function(response) {
                        var result = JSON.parse(response);
                        var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                        if (result.success) {
                            Swal.fire({
                                title: '',
                                text: result.message + dataMessage,
                                icon: 'success',
                                willClose: function() {
                                    window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                                }
                            });
                        } else {
                            Swal.fire('', result.message + dataMessage, 'error');
                        }
                    });
                }
            })
        });
    }

    // MUA THẺ
    function handleButtonClickBuyCard(buttonClass, action, message, keyGet, valueKeyGet) {
        $(document).on('click', buttonClass, function() {
            Swal.fire({
                title: '',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tôi đã chắc chắn!',
                onOpen: () => {
                    Swal.showLoading();
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.disabled = true;
                    setTimeout(() => {
                        confirmButton.disabled = false;
                        Swal.hideLoading();
                    }, 1500);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = {};
                    data[keyGet] = $(this).data(valueKeyGet);
                    data[action] = true;

                    $.post('../../../../ajaxs/admin/action/buyCard.php', {
                        data: JSON.stringify(data)
                    }, function(response) {
                        var result = JSON.parse(response);
                        var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                        if (result.success) {
                            Swal.fire({
                                title: '',
                                text: result.message + dataMessage,
                                icon: 'success',
                                willClose: function() {
                                    window.location.href = "<?= "/admin/list/buyCard" ?>";
                                }
                            });
                        } else {
                            Swal.fire('', result.message + dataMessage, 'error');
                        }
                    });
                }
            })
        });
    }

    // =====================================
    // LỊCH SỬ RÚT TIỀN
    handleButtonClick('.history_withdraw_duyet', 'history_withdraw_duyet', "Đổi trạng thái đơn THÀNH CÔNG!", "history_withdraw_code", "history-withdraw-code"); // Duyệt
    handleButtonClick('.history_withdraw_cancel_refurn', 'history_withdraw_cancel_Refurn', "HỦY ĐƠN & HOÀN TIỀN!", "history_withdraw_code", "history-withdraw-code"); // Hủy refurn
    handleButtonClick('.history_withdraw_cancel_nonRefurn', 'history_withdraw_cancel_nonRefurn', "HỦY ĐƠN & KHÔNG HOÀN TIỀN!", "history_withdraw_code", "history-withdraw-code"); // Hủy không refurn
    handleButtonClick('.history_withdraw_reload', 'history_withdraw_reload', "GỬI ĐƠN RÚT NGAY LẬP TỨC!", "history_withdraw_code", "history-withdraw-code"); // Gửi lệnh rút tiền

    // LỊCH SỬ ĐỔI THẺ
    handleButtonClick('.history_card_send', 'history_card_send', "GỬI THẺ NGAY LẬP TỨC!", "history_card_code", "history-card-code"); // Gửi lệnh gửi card tới web mẹ
    handleButtonClick('.history_card_callback_user', 'history_card_callback_user', "Callback về user!", "history_card_code", "history-card-code"); // Gửi callback về user
    handleButtonClick('.history_card_duyet_refurn', 'history_card_duyet_refurn', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Duyệt thẻ & refurn
    handleButtonClick('.history_card_duyet_nonRefurn', 'history_card_duyet_nonRefurn', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Duyệt thẻ & KHÔNG refurn
    handleButtonClick('.history_card_duyet_saiMenhGia', 'history_card_duyet_saiMenhGia', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Duyệt thẻ Sai mệnh giá & refurn giá trị thấp
    handleButtonClick('.history_card_cancel_nonRefurn', 'history_card_cancel_nonRefurn', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Hủy thẻ & KHÔNG refurn

    // LỊCH SỬ MUA THẺ
    handleButtonClickBuyCard('.buyCard_cancel_refurn', 'buyCard_cancel_refurn', "HỦY THẺ & REFURN!", "buycard_code", "buycard-code");
    handleButtonClickBuyCard('.buyCard_cancel_non_refurn', 'buyCard_cancel_non_refurn', "HỦY THẺ & KHÔNG REFURN!", "buycard_code", "buycard-code");
    // =====================================

    // ĐỔI MẬT KHẨU
    $('#changePassword').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn đổi mật khẩu người dùng?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    passwordNew: $('#changePassword_newPass').val(),
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/changePassword.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    // ĐỔI EMAIL
    $('#changeEmail').click(function() {
        Swal.fire({
            title: '',
            text: "Đổi email là LỆNH NGUY HIỂM?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 5000);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    emailNew: $('#changeEmail_newEmail').val(),
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/changeEmail.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    // ĐỔI SỐ ĐIỆN THOẠI
    $('#changePhone').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn số điện thoại người dùng?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    phoneNew: $('#changePhone_newPhone').val(),
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/changePhone.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    // THAY ĐỔI TIỀN TỆ
    $('#changeMoney').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn thay đổi tiền người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    action: $('#changeMoney_action').val(),
                    money: $('#changeMoney_money').val(),
                    reason: $('#changeMoney_reason').val(),
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/changeMoney.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    // GIA HẠN RANK
    $('#changeRank').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn GIA HẠN RANK người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    newRank: $('#changeRank_rank').val(),
                    dateRank: $('#changeRank_dateRank').val(),
                    reason: $('#changeRank_reason').val(),
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/changeRank.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });


    // GIA HẠN DỊCH VỤ DISCORD
    $('#changeNotiTransfer').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn GIA HẠN DỊCH VỤ DISCORD cho người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    date: $('#changeNotiTransfer_date').val(),
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/changeNotiTransfer.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });
    // KHÓA TÀI KHOẢN
    $('#blockUser').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn KHÓA TÀI KHOẢN người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/blockUser.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    // MỞ KHÓA TÀI KHOẢN
    $('#unblockUser').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn MỞ KHÓA TÀI KHOẢN người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/unblockUser.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    // MỞ KHÓA TÀI KHOẢN
    $('#logoutAccount').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn ĐĂNG XUẤT TẤT CẢ THIẾT BỊ người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/logout.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    
    // XÓA BANKING ACCOUNT
    $('#deleteBankingAccount').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn XÓA TOÀN BỘ NGÂN HÀNG của người dùng không?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đã kiểm tra thông tin!',
            onOpen: () => {
                Swal.showLoading();
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = true;
                setTimeout(() => {
                    confirmButton.disabled = false;
                    Swal.hideLoading();
                }, 1500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    user_id: $('#user_id').val()
                };

                $.post('../../../../ajaxs/admin/action/deleteBankUser.php', {
                    data: JSON.stringify(data)
                }, function(response) {
                    var result = JSON.parse(response);
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = "<?= "/admin/list/memberDetail/$user_email" ?>";
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });
</script>