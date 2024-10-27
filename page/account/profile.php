<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken('client');

// Lấy thông tin tài khoản
$user_id   = getIdUser();                // Lấy ID user
$user_info = getInfoUser($user_id);      // Lấy thông tin user
$user_rank = formatRank($user_info['user_rank']);    // Lấy cấp bậc user
$list_invite = pdo_query("SELECT * FROM `user` WHERE `user_invite_by` = ?", [$user_info['user_invite_code']]);
$has_noti_transfer = ($user_info['expire_noti_transfer'] == 0 || $user_info['expire_noti_transfer'] < time() || $user_info['expire_noti_transfer'] == null) ? false : true;
$has_noti_email    = $user_info['noti_email_login'] == 1 ? true : false;
$has_verify_email  = $user_info['user_verify_email'] == 1 ? true : false;
$fee_noti_transfer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'fee_noti_transfer'");

$domain = getDomain();

// Header
$title_website = 'Thông tin tài khoản';
require('../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Thông báo!</strong> Mời 5 người dùng sử dụng dịch vụ tại Card2k để nhận rank VIP</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <?php if (!$has_verify_email) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Thông báo!</strong> Bạn chưa <h href="#" ef="#" class="temary btn-link" data-bs-toggle="modal" data-bs-target="#verifyEmailModal">xác thực email (click me)</h>, hãy xác thực để mở tính năng tăng bảo mật nhé.</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>

    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-12 col-md-6">
                                            <h3>Thông tin tài khoản</h3>
                                        </div>
                                        <div class="col-12 hp-profile-action-btn text-end">
                                            <!-- Dropdown button -->
                                            <div class="dropdown">
                                                <button class="btn btn-ghost dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Cài đặt nâng cao
                                                </button>

                                                <!-- Dropdown menu -->
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileContactEditModal">
                                                            Đổi số điện thoại
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileFullnameEditModal">
                                                            Đổi tên tài khoản
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                                            Đổi mật khẩu
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item <?= $has_noti_transfer ? "text-success" : "" ?>" data-bs-toggle="modal" data-bs-target="#notiTransferModal">
                                                            Thông báo nhận tiền
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <?php
                                                        if ($user_info['user_is_verify_2fa'] == 1) {
                                                        ?>
                                                            <a href="<?= "$domain/account/verify/remove2FA" ?>" class="dropdown-item text-success">
                                                                Bảo mật Google Auth
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="<?= "$domain/account/verify/active2FA" ?>" class="dropdown-item ">
                                                                Bảo mật Google Auth
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>

                                        <div class="divider"></div>

                                        <div class="col-12 hp-profile-content-list mt-8 pb-0 pb-sm-70">
                                            <ul>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Tài khoản</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $user_info['user_fullname'] ?></span>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Email</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $user_info['user_email'] ?></span>
                                                        <?php if (!$has_verify_email) { ?>
                                                            <span class="badge badge-danger">Chưa xác minh</span>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Số điện thoại</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <a class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0" href="tel:<?= $user_info['user_phone'] ?>">
                                                            <?= empty($user_info['user_phone']) ? "-" : $user_info['user_phone'] ?>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Cấp bậc</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0">
                                                            <span class="badge badge-pill badge-<?= $user_rank['color'] ?>"><?= $user_rank['name'] ?></span>
                                                        </span>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Thời hạn rank</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0">
                                                            <?= $user_info['user_rank_expire'] == 0 ? 'Vĩnh viễn' : date("Y-m-d", $user_info['user_rank_expire']) ?>
                                                        </span>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Ngày đăng ký</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $user_info['created_at'] ?></span>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Link mời</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-warning" id="copyText">
                                                            <?= getDomain() . "/account/register?ref=" . $user_info['user_invite_code'] ?>
                                                        </span>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Hạn nhận thông báo discord</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body <?= $has_noti_transfer == true ? "text-success" : "text-danger" ?>">
                                                            <?= $user_info['expire_noti_transfer'] == 0 ? 'Chưa kích hoạt' : date("Y-m-d", $user_info['expire_noti_transfer']) ?>
                                                        </span>
                                                    </div>
                                                </li>
                                                <li class="mt-18 row">
                                                    <div class="col-12 col-md-2">
                                                        <span class="hp-p1-body">Số dư hiện tại</span>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0 fw-bold">
                                                            <?= number_format($user_info['user_cash']) . "đ" ?>
                                                        </span>
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
            </div>
        </div>
    </div>

    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8 text-center">Danh sách mời</span>
                        <div class="divider mt-18 mb-16"></div>
                        <br />

                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-invite" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">Số thứ tự</th>
                                            <th class="text-nowrap">Email cấp dưới</th>
                                            <th class="text-nowrap">Ngày đăng ký</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $soThuTu = 1;
                                        foreach ($list_invite as $value) {
                                        ?>
                                            <tr>
                                                <td class="text-nowrap"><?= $soThuTu ?></td>
                                                <td class="text-nowrap"><?= $value['user_email'] ?></td>
                                                <td class="text-nowrap"><?= $value['created_at'] ?></td>
                                            </tr>
                                        <?php
                                            $soThuTu++;
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
    </div>
</div>


<div class="modal fade" id="profileFullnameEditModal" tabindex="-1" aria-labelledby="profileFullnameEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 416px">
        <div class="modal-content">
            <div class="modal-header py-16">
                <h5 class="modal-title" id="profileFullnameEditModalLabel">
                    Chỉnh sửa thông tin
                </h5>
            </div>

            <div class="divider my-0"></div>

            <div class="modal-body py-48">
                <div class="row g-24">
                    <div class="col-12">
                        <p class="text-danger">
                            * Tên tài khoản chỉ được phép sửa 1 lần duy nhất. Hãy chắc chắn bạn đã nhập đúng tên tài khoản.
                        </p>
                        <p class="text-warning">
                            * Tên tài khoản có thể dùng để nhận tiền từ người khác.
                        </p>
                        <p>
                            * Tên tài khoản <span class="text-danger">không chứa từ ngữ nhạy cảm</span>. Ví dụ: admin, mod, bussy,... <br>
                        </p>
                        <p>
                            <span class="text-danger">* Vi phạm quy tắc sẽ bị khóa tài khoản vĩnh viễn.</span>
                        </p>
                    </div>

                    <div class="col-12">
                        <label for="changeFullname_name" class="form-label">Tên tài khoản:</label>
                        <input type="text" class="form-control" id="changeFullname_name" placeholder="hhiepz, huutai,.." />
                    </div>

                    <div class="col-12">
                        <label for="changeFullname_password" class="form-label">Mật khẩu:</label>
                        <input type="password" class="form-control" id="changeFullname_password" />
                    </div>

                    <div class="col-6">
                        <button type="button" class="btn btn-primary w-100" id="changeFullname">
                            Tiến hành cập nhật
                        </button>
                    </div>

                    <div class="col-6">
                        <div class="btn w-100" data-bs-dismiss="modal">Hủy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileContactEditModal" tabindex="-1" aria-labelledby="profileContactEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 416px">
        <div class="modal-content">
            <div class="modal-header py-16">
                <h5 class="modal-title" id="profileContactEditModalLabel">
                    Chỉnh sửa thông tin
                </h5>
            </div>

            <div class="divider my-0"></div>

            <div class="modal-body py-48">
                <div class="row g-24">
                    <div class="col-12">
                        <p class="text-danger">
                            * Số điện thoại chỉ được phép sửa 1 lần duy nhất. Hãy chắc chắn bạn đã nhập đúng số điện thoại.
                        </p>
                        <p class="text-warning">
                            * Số điện thoại có thể dùng để nhận tiền từ người khác.
                        </p>
                    </div>
                    <div class="col-12">
                        <label for="phone" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" id="phone" placeholder="Số Việt Nam - 0389802..." />
                    </div>

                    <div class="col-12">
                        <label for="changePhone_password" class="form-label">Mật khẩu:</label>
                        <input type="password" class="form-control" id="changePhone_password" />
                    </div>

                    <div class="col-6">
                        <button type="button" class="btn btn-primary w-100" id="changeNumberPhone">
                            Tiến hành cập nhật
                        </button>
                    </div>

                    <div class="col-6">
                        <div class="btn w-100" data-bs-dismiss="modal">Hủy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($has_verify_email) { ?>
    <div class="modal fade" id="notiEmailModal" tabindex="-1" aria-labelledby="notiEmailModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 416px">
            <div class="modal-content">
                <div class="modal-header py-16">
                    <h5 class="modal-title" id="profileContactEditModalLabel">
                        Cảnh báo IP mới đăng nhập
                    </h5>
                </div>

                <div class="divider my-0"></div>

                <div class="modal-body py-48">
                    <div class="row g-24">
                        <div class="col-12">
                            <p>
                                * Khi có người đăng nhập vào tài khoản của bạn từ IP mới, hệ thống sẽ gửi thông báo đến email bạn cung cấp.
                            </p>
                            <p class="text-warning">
                                * Hãy bật tính năng này để bảo vệ tài khoản của bạn.
                            </p>
                            <?php if (!$has_verify_email) { ?>
                                <p class="text-danger">
                                    * Bạn cần xác nhận email để sử dụng tính năng này.
                                </p>
                            <?php } ?>
                        </div>

                        <div class="col-12">
                            <label for="webhookTransfer" class="form-label">Mật khẩu:</label>
                            <input type="password" class="form-control" id="newIp_password" placeholder="Password" <?= ($has_verify_email) ? "" : "disabled" ?> ?>
                        </div>

                        <?php
                        if ($has_noti_email) {
                        ?>
                            <div class="col-6">
                                <button type="submit" class="btn btn-danger w-100" id="newIp">
                                    TẮT cảnh báo
                                </button>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100" id="newIp" <?= ($has_verify_email) ? "" : "disabled" ?>>
                                    BẬT cảnh báo
                                </button>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="col-6">
                            <div class="btn w-100" data-bs-dismiss="modal">Hủy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (!$has_verify_email) { ?>
    <div class="modal fade" id="verifyEmailModal" tabindex="-1" aria-labelledby="verifyEmailModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 416px">
            <div class="modal-content">
                <div class="modal-header py-16">
                    <h5 class="modal-title" id="profileContactEditModalLabel">
                        Xác thực email
                    </h5>
                </div>

                <div class="divider my-0"></div>

                <div class="modal-body py-48">
                    <div class="row g-24">
                        <div class="col-12">
                            <p class="text-warning">
                                * Khi bấm vào nút "Gửi mã", hệ thống sẽ gửi mã xác thực đến email của bạn trong vòng <span class="fw-bold">2 phút</span>.
                            </p>
                            <p>
                                * Các bước xác thực: <br />
                                1. Bấm "Gữi mã" và kiểm tra email của bạn. <br />
                                2. Nhập mã xác thực vào ô bên dưới và bấm "Xác thực email". <br />
                            </p>
                            <p class="text-danger">
                                * Trường hợp spam liên tục sẽ tự động bị khóa tài khoản. Vui lòng kiên nhẫn.
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="verifyEmail_otp" class="form-label">Mã xác thực:</label>
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" class="form-control" id="verifyEmail_otp" placeholder="OTP">
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-warning w-100" id="verify_sendOtp">
                                        Gửi mã
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="webhookTransfer" class="form-label">Mật khẩu hiện tại:</label>
                            <input type="password" class="form-control" id="verifyEmail_password" placeholder="Password">
                        </div>

                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100" id="verifyEmail">
                                Xác thực email
                            </button>
                        </div>

                        <div class="col-6">
                            <div class="btn w-100" data-bs-dismiss="modal">Hủy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="modal fade" id="notiTransferModal" tabindex="-1" aria-labelledby="notiTransferModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 416px">
        <div class="modal-content">
            <div class="modal-header py-16">
                <h5 class="modal-title" id="profileContactEditModalLabel">
                    Thông báo khi nhận tiền
                </h5>
            </div>

            <div class="divider my-0"></div>

            <div class="modal-body py-48">
                <div class="row g-24">
                    <div class="col-12">
                        <p class="text-warning">
                            * Khi có người chuyển tiền đến tài khoản của bạn, hệ thống sẽ gửi thông báo đến link discord bạn cung cấp.
                        </p>
                        <p class="text-danger">
                            * Bỏ trống tương đương với tắt tính năng.
                        </p>
                        <p>
                            * Đây là mẫu thông báo: <a href="https://i.ibb.co/1RsMcRK/image.png">xem demo</a>
                        </p>
                        <p>
                            * Phí dịch vụ: <span class="fw-bold"><?= number_format($fee_noti_transfer) ?>đ/ 30 ngày.</span>
                        </p>
                    </div>

                    <div class="col-12">
                        <label for="webhookTransfer" class="form-label">Link webhook:</label>
                        <input type="text" class="form-control" id="webhookTransfer" value="<?= $user_info['webhook_transfer'] ?>" placeholder="Link webhook discord" <?= $has_noti_transfer ? "" : "disabled" ?> />
                    </div>

                    <?php
                    if ($has_noti_transfer) {
                    ?>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100" id="changeNotiTransfer">
                                Lưu thay đổi
                            </button>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-6">
                            <button type="submit" class="btn btn-danger w-100" id="activeNotiTransfer">
                                Gia hạn thông báo
                            </button>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="col-6">
                        <div class="btn w-100" data-bs-dismiss="modal">Hủy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 416px">
        <div class="modal-content">
            <div class="modal-header py-16">
                <h5 class="modal-title" id="profileContactEditModalLabel">
                    Đổi mật khẩu
                </h5>
            </div>

            <div class="divider my-0"></div>

            <div class="modal-body py-48">
                <div class="row g-24">
                    <div class="col-12">
                        <label for="profileOldPassword" class="form-label">Mật khẩu hiện tại:</label>
                        <input type="password" class="form-control" id="profileOldPassword" placeholder="Password" />
                    </div>

                    <div class="col-12">
                        <label for="profileNewPassword" class="form-label">Mật khẩu mới:</label>
                        <input type="password" class="form-control" id="profileNewPassword" placeholder="Password" />
                    </div>

                    <div class="col-12">
                        <label for="profileConfirmPassword" class="form-label">Xác nhận mật khẩu mới:</label>
                        <input type="password" class="form-control" id="profileConfirmPassword" placeholder="Password" />
                    </div>

                    <div class="col-6">
                        <button type="submit" class="btn btn-primary w-100" id="changePassword">
                            Đổi mật khẩu
                        </button>
                    </div>

                    <div class="col-6">
                        <div class="btn w-100" data-bs-dismiss="modal">Hủy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Footer
require('../../layout/client/footer.php');
?>

<script>
    new DataTable('#data-table_list-invite', {
        order: [
            [2, 'desc']
        ],
        columnDefs: [{
            targets: 2,
            type: 'date',
            render: function(data, type, row) {
                return moment(data).format('YYYY-MM-DD HH:mm:ss');
            }
        }]
    });

    // Thay đổi số điện thoại
    $('#changeFullname').click(function() {
        var data = {
            changeFullname_name: $('#changeFullname_name').val(),
            password: $('#changeFullname_password').val()
        };

        $.post('../../ajaxs/main/account/changeFullname.php', {
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
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
            }
        });
    });


    // Thay đổi số điện thoại
    $('#changeNumberPhone').click(function() {
        var data = {
            phone: $('#phone').val(),
            password: $('#changePhone_password').val()
        };

        $.post('../../ajaxs/main/account/changePhone.php', {
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
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
            }
        });
    });

    <?php if ($has_verify_email) { ?>
        // Thông báo email khi có IP mới đăng nhập
        $('#newIp').click(function() {
            var data = {
                password: $('#newIp_password').val()
            };

            $.post('../../ajaxs/main/account/notiEmailNewIp.php', {
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
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('', result.message + dataMessage, 'error');
                }
            });
        });
    <?php } ?>

    <?php if (!$has_verify_email) { ?>
        // Xác thực email
        $('#verifyEmail').click(function() {
            var data = {
                password: $('#verifyEmail_password').val(),
                otpEmail: $('#verifyEmail_otp').val(),
                verifyEmail: true
            };

            $.post('../../ajaxs/main/account/verifyEmail.php', {
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
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('', result.message + dataMessage, 'error');
                }
            });
        });

        // Gửi mã xác thực
        $('#verify_sendOtp').click(function() {
            var data = {
                sendOtp: true
            };

            $.post('../../ajaxs/main/account/verifyEmail.php', {
                data: JSON.stringify(data)
            }, function(response) {
                var result = JSON.parse(response);
                var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                if (result.success) {
                    Swal.fire({
                        title: '',
                        text: result.message + dataMessage,
                        icon: 'success'
                    });
                } else {
                    Swal.fire('', result.message + dataMessage, 'error');
                }
            });
        });
    <?php } ?>

    // Thay đổi mật khẩu
    $('#changePassword').click(function() {
        var data = {
            passwordOld: $('#profileOldPassword').val(),
            passwordNew: $('#profileNewPassword').val(),
            passwordAgain: $('#profileConfirmPassword').val()
        };

        $.post('../../ajaxs/main/account/changePassword.php', {
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
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
            }
        });
    });

    // Thay đổi thông tin nhận thông báo
    $('#changeNotiTransfer').click(function() {
        var data = {
            webhookTransfer: $('#webhookTransfer').val(),
        };

        $.post('../../ajaxs/main/client/action/changeNotiTransfer.php', {
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
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
            }
        });
    });

    // Gia hạn thông tin nhận thông báo
    $('#activeNotiTransfer').click(function() {
        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn kích hoạt tính năng này không? Phí dịch vụ: <?= number_format($fee_noti_transfer) ?>đ/ 30 ngày",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tôi đồng ý gia hạn!',
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

                $.post('../../ajaxs/main/client/action/activeNotiTransfer.php', {
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
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        })
    });

    document.getElementById('copyText').addEventListener('click', function() {
        var tempElement = document.createElement('textarea');

        tempElement.value = this.textContent;
        document.body.appendChild(tempElement);
        tempElement.select();
        document.execCommand('copy');
        document.body.removeChild(tempElement);
        alert('Đã copy link mời vào clipboard!');
    });
</script>