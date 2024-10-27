<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

checkToken("client");

// Kiểm tra có tồn tại mã đơn hàng không
if (!isset($_GET['wd_code'])) {
    header("Location:" . getDomain() . "/withdraw");
    die;
}

// Kiểm tra đơn hàng có tồn tại & thuộc về nguời dùng không
$user_id           = getIdUser();
$wd_code           = $purifier->purify($_GET['wd_code']);
$checkOrder        = pdo_query_one("SELECT * FROM `withdraw` WHERE `wd_code` = ? AND `user_id` = ?", [$wd_code, $user_id]);
if (empty($checkOrder)) {
    header("Location:" . getDomain() . "/withdraw");
    die;
}

// Lấy thông tin đơn hàng
$wd_cash           = $checkOrder['wd_cash'];
$wd_status         = $checkOrder['wd_status'];
$wd_created        = $checkOrder['created_at'];
$wd_owner          = $checkOrder['wd_bank_owner'];
$wd_owner_number   = $checkOrder['wd_number_account'];
$wd_banking        = $checkOrder['wd_bank_name'];
$wd_description    = empty($checkOrder['wd_description']) ? ' - ' : $checkOrder['wd_description'];

// Header
$title_website = 'Chi tiết đơn hàng';
require('../../../layout/client/header.php');
?>


<div class="hp-main-layout-content">
    <div class="row">
        <div class="col-12 mb-4">
            <h3 class="text-uppercase">Thông tin hóa đơn:</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Thông tin đơn hàng</h5>
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" class="w-50">Mã đơn #</th>
                                <td><?= $wd_code ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Số tiền</th>
                                <td>
                                    <span class="text-danger fw-bold"><?= number_format($wd_cash) ?>đ</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Trạng thái</th>
                                <td>
                                    <span class="badge badge-<?= statusBank($wd_status, "color") ?>"><?= statusBank($wd_status) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Ngày tạo</th>
                                <td><?= $wd_created ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Chi tiết giao dịch</h5>
                    <table class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <td class="border w-50">Số tiền rút:</td>
                                <td class="border">
                                    <span class="text-danger fw-bold"><?= number_format($wd_cash) ?>đ</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Nội dung giao dịch:</td>
                                <td class="border">CARD2K <?= $wd_code ?></td>
                            </tr>
                            <tr>
                                <td class="border">Rút tiền qua:</td>
                                <td class="border">
                                    <span class="fw-bold"><?= showNameBank($wd_banking) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Chủ tài khoản:</td>
                                <td class="border">
                                    <span class="fw-bold"><?= $wd_owner ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Số tài khoản:</td>
                                <td class="border">
                                    <span class="fw-bold"><?= $wd_owner_number ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Nội dung:</td>
                                <td class="border">
                                    <span class="fw-bold"><?= $wd_description ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Trạng thái đơn hàng:</td>
                                <td class="border">
                                    <span class="badge badge-<?= statusBank($wd_status, "color") ?>"><?= statusBank($wd_status) ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Note từ admin</h5>
                    <p class="card-text">Vui lòng chú ý đến các chi tiết trong đơn hàng này.</p>
                </div>
            </div>
        </div>
    </div> -->
</div>

<?php
// Footer
require('../../../layout/client/footer.php');
?>