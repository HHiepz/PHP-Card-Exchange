<?php
    require('../../../core/database.php');
    require('../../../core/function.php');

    // Check admin permissions
    checkToken('admin');

    // Fetch topup orders
    $topup_orders = pdo_query("SELECT * FROM `topup-order` ORDER BY created_at DESC");

    // Calculate statistics
    $total_orders    = count($topup_orders);
    $total_amount    = 0;
    $total_completed = 0;
    $total_failed    = 0;
    $total_pending   = 0;
    $total_wait      = 0;
    $total_amount_completed = 0;
    $total_amount_failed    = 0;
    $total_amount_pending   = 0;
    $total_amount_wait      = 0;

    foreach ($topup_orders as $order) {
        $total_amount += $order['topup-order_amount'];
        if ($order['topup-order_status'] == 'completed') {
            $total_completed++;
            $total_amount_completed += $order['topup-order_pay_amount'];
        }
        if ($order['topup-order_status'] == 'failed' || $order['topup-order_status'] == 'canceled') {
            $total_failed++;
            $total_amount_failed += $order['topup-order_pay_amount'];
        }
        if ($order['topup-order_status'] == 'pending') {
            $total_pending++;
            $total_amount_pending += $order['topup-order_pay_amount'];
        }
        if ($order['topup-order_status'] == 'wait') {
            $total_wait++;
            $total_amount_wait += $order['topup-order_pay_amount'];
        }
    }

    // Header
    $title_website = 'Quản lý đơn nạp tiền';
    require('../../../layout/admin/header.php');
    ?>

<div class="hp-main-layout-content">
    <div class="row mb-32 gy-32">
        <div class="col-12">
            <h1 class="hp-mb-0 text-4xl font-bold"><?= $title_website ?></h1>
        </div>
    </div>

    <div class="row mb-32 gy-32">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-primary-4 hp-bg-color-dark-primary rounded-circle">
                                <i class="fas fa-chart-line text-primary hp-text-color-dark-primary-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_orders) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Tổng đơn hàng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-warning-4 hp-bg-color-dark-warning rounded-circle">
                                <i class="fas fa-wallet text-warning hp-text-color-dark-warning-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_amount) ?>đ</h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Tổng tiền nạp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-success-4 hp-bg-color-dark-success rounded-circle">
                                <i class="fas fa-check-square text-success hp-text-color-dark-success-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_completed) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Đơn thành công</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-danger-4 hp-bg-color-dark-danger rounded-circle">
                                <i class="fas fa-times-circle text-danger hp-text-color-dark-danger-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_failed) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Đơn thất bại</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-info-4 hp-bg-color-dark-info rounded-circle">
                                <i class="fas fa-clock text-info hp-text-color-dark-info-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_wait) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Đơn chờ xử lý</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-secondary-4 hp-bg-color-dark-secondary rounded-circle">
                                <i class="fas fa-cogs text-secondary hp-text-color-dark-secondary-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_pending) ?></h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Đơn đang xử lý</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-success-4 hp-bg-color-dark-success rounded-circle">
                                <i class="fas fa-chart-bar text-success hp-text-color-dark-success-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_amount_completed) ?>đ</h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Tổng tiền thành công</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-16">
                        <div class="col-6 hp-flex-none w-auto">
                            <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-danger-4 hp-bg-color-dark-danger rounded-circle">
                                <i class="fas fa-chart-line text-danger hp-text-color-dark-danger-2" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-4 mt-8"><?= formatNumber($total_amount_failed) ?>đ</h3>
                            <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">Tổng tiền thất bại</p>
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
                        <table id="topup-orders-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Người dùng</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Số tiền nạp</th>
                                    <th>Thanh toán</th>
                                    <th>Tài khoản nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Chiết khấu</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topup_orders as $order): ?>
                                    <tr>
                                        <td><?= $order['topup-order_request_id'] ?></td>
                                        <td><?= getEmailUser($order['user_id']) ?></td>
                                        <td><?= getTopupNameFromId($order['topup_id']) ?></td>
                                        <td class="fw-bold text-end"><?= formatNumber($order['topup-order_amount']) ?>đ</td>
                                        <td class="fw-bold text-success text-end"><?= formatNumber($order['topup-order_pay_amount']) ?>đ</td>
                                        <td><?= $order['topup-order_account'] ?></td>
                                        <td><span class="badge bg-<?= getTopupStatus($order['topup-order_status'])['color'] ?>"><?= getTopupStatus($order['topup-order_status'])['name'] ?></span></td>
                                        <td><?= formatNumber($order['topup-order_discount']) ?>%</td>
                                        <td><?= $order['created_at'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if ($order['topup-order_status'] !== 'completed' && $order['topup-order_status'] !== 'canceled'): ?>
                                                    <?php if ($order['topup-order_status'] !== 'wait'): ?>
                                                    <button class="btn btn-icon btn-warning btn-sm reset-order" data-id="<?= $order['topup-order_id'] ?>" onclick="handleButtonClick('reset', '<?= $order['topup-order_request_id'] ?>')" title="Reset đơn">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-icon btn-success btn-sm complete-order" data-id="<?= $order['topup-order_id'] ?>" onclick="handleButtonClick('completeNoRefund', '<?= $order['topup-order_request_id'] ?>')" title="Hoàn thành & không Refund">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-icon btn-info btn-sm complete-order-refund" data-id="<?= $order['topup-order_id'] ?>" onclick="handleButtonClick('completeRefund', '<?= $order['topup-order_request_id'] ?>')" title="Hoàn thành & Refund">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                    <button class="btn btn-icon btn-danger btn-sm cancel-order" data-id="<?= $order['topup-order_id'] ?>" onclick="handleButtonClick('cancelNoRefund', '<?= $order['topup-order_request_id'] ?>')" title="Hủy & không Refund">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-icon btn-dark btn-sm cancel-order-refund" data-id="<?= $order['topup-order_id'] ?>" onclick="handleButtonClick('cancelRefund', '<?= $order['topup-order_request_id'] ?>')" title="Hủy & Refund">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for order details -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <!-- Modal content here (similar to the one in historyOrderTopup.php) -->
</div>

<?php
// Footer
require('../../../layout/admin/footer.php');
?>

<script>
    $(document).ready(function() {
        $('#topup-orders-table').DataTable({
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
                [8, 'desc']
            ] // Sort by date created (assuming it's the 9th column)
        });
    });

    function handleButtonClick(action, orderId) {
        let postUrl;
        switch (action) {
            case 'reset':
                postUrl = '../../../ajaxs/admin/action/resetTopup.php';
                break;
            case 'cancelNoRefund':
                postUrl = '../../../ajaxs/admin/action/cancelTopupNoRefund.php';
                break;
            case 'cancelRefund':
                postUrl = '../../../ajaxs/admin/action/cancelTopupRefund.php';
                break;
            case 'completeNoRefund':
                postUrl = '../../../ajaxs/admin/action/completeTopupNoRefund.php';
                break;
            case 'completeRefund':
                postUrl = '../../../ajaxs/admin/action/completeTopupRefund.php';
                break;
            default:
                console.error('Invalid action');
                return;
        }

        Swal.fire({
            title: '',
            text: 'Are you sure you want to perform this action?',
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
                let data = {
                    'topup-order_request_id': orderId
                };
                $.post(postUrl, {
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
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                });
            }
        });
    }
</script>