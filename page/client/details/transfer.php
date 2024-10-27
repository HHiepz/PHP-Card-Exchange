<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

checkToken("client");

// Kiểm tra có tồn tại mã đơn hàng không
if (!isset($_GET['transfer_id'])) {
    header("Location:" . getDomain() . "/transfer");
    die;
}

// Kiểm tra đơn hàng có tồn tại & thuộc về nguời dùng không
$user_id           = getIdUser();
$transfer_code       = $purifier->purify($_GET['transfer_id']);
$checkOrder        = pdo_query_one("SELECT * FROM `transfer` WHERE `transfer_code` = ? AND (`transfer_user_from` = ? OR `transfer_user_to` = ?)", [$transfer_code, $user_id, $user_id]);
if (empty($checkOrder)) {
    header("Location:" . getDomain() . "/transfer");
    die;
}

// Lấy thông tin đơn hàng
$transfer_cash      = $checkOrder['transfer_cash'];
$transfer_status    = 'success';
$transfer_created   = $checkOrder['created_at'];
$transfer_user_from = $checkOrder['transfer_user_from'];
$transfer_user_to   = $checkOrder['transfer_user_to'];
$transfer_description = $checkOrder['transfer_description'];

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
                                <td><?= $transfer_code ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Số tiền</th>
                                <td>
                                    <span class="text-danger fw-bold"><?= number_format($transfer_cash) ?>đ</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Trạng thái</th>
                                <td>
                                    <span class="badge badge-<?= statusBank($transfer_status, "color") ?>"><?= statusBank($transfer_status) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Ngày tạo</th>
                                <td><?= $transfer_created ?></td>
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
                                    <span class="text-danger fw-bold"><?= number_format($transfer_cash) ?>đ</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Nội dung giao dịch:</td>
                                <td class="border"><?= $transfer_description ?></td>
                            </tr>
                            <tr>
                                <td class="border">
                                    Người chuyển:
                                </td>
                                <td class="border">
                                    <span class="fw-bold"><?= getEmailUser($transfer_user_from) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">
                                    <i class="fas fa-hand-holding-usd text-success"></i>
                                    Người nhận:
                                </td>
                                <td class="border">
                                    <span class="fw-bold"><?= getEmailUser($transfer_user_to) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border">Trạng thái đơn hàng:</td>
                                <td class="border">
                                    <span class="badge badge-<?= statusBank($transfer_status, "color") ?>"><?= statusBank($transfer_status) ?></span>
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