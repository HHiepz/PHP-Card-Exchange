<?php
require('../../../core/database.php');
require('../../../core/function.php');

checkToken("client");

$user_id = getIdUser();

// Danh sách đơn nạp
$history_order_topup = pdo_query("SELECT * FROM `topup-order` WHERE `user_id` = ?", [$user_id]);

// Lấy thống kê tổng đơn hàng, tổng đơn thành công, tổng đơn trạng thái thất bại, tổng tiền nạp
$total_order          = pdo_query_value("SELECT COUNT(*) FROM `topup-order` WHERE `user_id` = ?", [$user_id]);
$total_order_success  = pdo_query_value("SELECT COUNT(*) FROM `topup-order` WHERE `user_id` = ? AND `topup-order_status` = 'completed'", [$user_id]);
$total_order_failed   = pdo_query_value("SELECT COUNT(*) FROM `topup-order` WHERE `user_id` = ? AND (`topup-order_status` = 'failed' OR `topup-order_status` = 'canceled')", [$user_id]);
$total_order_amount   = pdo_query_value("SELECT SUM(`topup-order_amount`) FROM `topup-order` WHERE `user_id` = ? AND `topup-order_status` = 'completed'", [$user_id]);

// Header
$title_website = 'Lịch sử nạp điện thoại';
require('../../../layout/client/header.php');
?>

<div class="hp-main-layout-content">
    <div class="row mb-32 gy-32">
        <div class="col-12">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="d-block mb-8 text-center">Lịch sử nạp Topup</h3>
                                    <p class="hp-p1-body mb-8 text-center text-black-80 hp-text-color-dark-30">
                                        Ghi chú: Dữ liệu chỉ được giữ tại web trong vòng 2 tháng gần nhất. Và sẽ được cập nhật mới vào đầu tháng
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-32 gy-32">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-primary-4 hp-bg-color-dark-primary rounded-circle">
                                <i class="fa-solid fa-chart-line text-primary hp-text-color-dark-primary-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_order) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Tổng đơn hàng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-success-4 hp-bg-color-dark-success rounded-circle">
                                <i class="fa-solid fa-check-square text-success hp-text-color-dark-success-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_order_success) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Đơn thành công</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-danger-4 hp-bg-color-dark-danger rounded-circle">
                                <i class="fa-solid fa-times-square text-danger hp-text-color-dark-danger-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_order_failed) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Đơn thất bại</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-warning-4 hp-bg-color-dark-warning rounded-circle">
                                <i class="fa-solid fa-wallet text-warning hp-text-color-dark-warning-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_order_amount) ?>đ</h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Tổng tiền nạp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-32 gy-32">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="topup-history-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Số tiền nạp</th>
                                    <th>Số lượng</th>
                                    <th>Thanh toán</th>
                                    <th>Tài khoản nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Chiết khấu</th>
                                    <th>Ngày tạo</th>
                                    <!--<th>Thao tác</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($history_order_topup as $order) {
                                ?>
                                    <tr>
                                        <td class="text-nowrap"><?= $order['topup-order_request_id'] ?></td>
                                        <td class="text-nowrap"><?= getTopupNameFromId($order['topup_id']) ?></td>
                                        <td class="text-nowrap fw-bold text-end"><?= formatNumber($order['topup-order_amount']) ?>đ</td>
                                        <td class="text-nowrap">1</td>
                                        <td class="text-nowrap fw-bold text-success text-end"><?= formatNumber($order['topup-order_pay_amount']) ?>đ</td>
                                        <td class="text-nowrap"><?= $order['topup-order_account'] ?></td>
                                        <td class="text-nowrap"><span class="badge bg-<?= getTopupStatus($order['topup-order_status'])['color'] ?>"><?= getTopupStatus($order['topup-order_status'])['name'] ?></span></td>
                                        <td class="text-nowrap"><?= formatNumber($order['topup-order_discount']) ?>%</td>
                                        <td class="text-nowrap"><?= $order['created_at'] ?></td>
                                        <!--<td><button class="btn btn-sm btn-info view-details">Chi tiết</button></td>-->
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

<!-- Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                                <img class="hp-logo hp-sidebar-visible hp-dark-none img-fluid" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                <img class="hp-logo hp-sidebar-visible hp-dark-block img-fluid" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo-dark.png" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none img-fluid" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block img-fluid" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo-dark.png" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none img-fluid" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block img-fluid" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo-dark.png" alt="logo" />
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-end text-sm-start">
                                <p class="font-weight-bold mb-1">Mã đơn hàng: <span id="orderCode" class="text-danger">TOP123456</span></p>
                                <p class="text-muted">Ngày tạo: <span id="createdAt">2023-06-15 14:30:00</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                                <h6 class="mb-2">Thông tin khách hàng:</h6>
                                <p class="mb-1" id="customerName">TRẦN HỮU HIỆP</p>
                                <p class="mb-1">Số điện thoại: <span id="phoneNumber">0389802966</span></p>
                                <p class="mb-1" id="customerEmail">tranhuuhiep2004@gmail.com</p>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-end text-sm-start">
                                <h6 class="mb-2">Thông tin công ty:</h6>
                                <p class="mb-1"><?= $_SERVER['HTTP_HOST'] ?></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nhà mạng</th>
                                        <th>Mệnh giá</th>
                                        <th>Số lượng</th>
                                        <th>Chiết khấu</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="telco">Viettel</td>
                                        <td id="amount">100,000đ</td>
                                        <td id="quantity">1</td>
                                        <td id="discount">3%</td>
                                        <td id="subtotal">97,000đ</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                                <p class="font-weight-bold mb-1">Trạng thái đơn hàng: <span id="status" class="badge bg-success">Thành công</span></p>
                                <p class="text-muted mb-1" id="statusDescription">Giao dịch đã được xử lý thành công</p>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-end text-sm-start">
                                <p class="font-weight-bold mb-1">Tổng tiền: <span id="totalAmount" class="text-danger">97,000đ</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center mt-4">
                            <p class="mb-0">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="printInvoice()">In hóa đơn</button>
            </div>
        </div>
    </div>
</div>

<script>
    function printInvoice() {
        var printContents = document.querySelector('#orderDetailsModal .modal-body').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<?php
// Footer
require('../../../layout/client/footer.php');
?>
<script>
    $(document).ready(function() {
        $('#topup-history-table').DataTable({
            responsive: true,
            language: {
                search: "Tìm kiếm",
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                infoFiltered: "(được lọc từ _MAX_ mục)",
                paginate: {
                    first: "Đầu",
                    previous: "Trước",
                    next: "Tiếp",
                    last: "Cuối"
                }
            },
            order: [
                [9, 'desc']
            ] // Sort by date created (assuming it's the 10th column)
        });
    });
</script>