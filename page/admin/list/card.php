<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách thành viên
$list_card = pdo_query("SELECT * FROM `card-data` ORDER BY `card-data_id` DESC");

// Header
$title_website = 'Danh sách đổi thẻ';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">DANH SÁCH ĐỔI THẺ</h3>
                        <div class="table-responsive">
                            <table id="data-table_list-exCard" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Trạng thái</th>
                                        <th>Mã đơn</th>
                                        <th>Thông tin thẻ</th>
                                        <th>Giá trị</th>
                                        <th>Thời gian</th>
                                        <th>Callback</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_card as $value): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= getEmailUser($value['user_id']) ?>" class="text-primary font-weight-medium">
                                                    <?= limitShow(getEmailUser($value['user_id'])) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= statusCard($value['card-data_status'], "color") ?>">
                                                    <?= statusCard($value['card-data_status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $value['card-data_request_id'] ?></td>
                                            <td>
                                                <strong>Mã nạp:</strong> <?= $value['card-data_code'] ?><br>
                                                <strong>Serial:</strong> <?= $value['card-data_seri'] ?><br>
                                                <strong>Mạng:</strong> <?= $value['card-data_telco'] ?>
                                            </td>
                                            <td>
                                                <strong>Gửi:</strong> <?= number_format($value['card-data_amount']) ?><br>
                                                <strong>Thực:</strong> <?= number_format($value['card-data_amount_real']) ?><br>
                                                <strong>Nhận:</strong> <?= number_format($value['card-data_amount_recieve']) ?>
                                            </td>
                                            <td>
                                                <?= $value['card-data_created_at'] ?><br>
                                                <small class="text-success"><?= $value['card-data_updated_api'] ?></small>
                                            </td>
                                            <td>
                                                <?php if (!empty($value['card-data_callback'])): ?>
                                                    <span class="badge bg-success">Có</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Không</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($value['card-data_status'] === 'wait'): ?>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary history_card_send" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Gửi card web mẹ">
                                                            <i class="fa-solid fa-paper-plane"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-success history_card_callback_user" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-history-card-callback-user="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Callback về member">
                                                            <i class="fa-solid fa-phone"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning history_card_duyet_refurn" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Duyệt Refurn">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger history_card_duyet_nonRefurn" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Duyệt KHÔNG Refurn">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info history_card_duyet_saiMenhGia" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Duyệt sai mệnh giá">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger history_card_cancel_nonRefurn" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Hủy KHÔNG Refurn">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                <?php endif; ?>
                                                <?php if (!empty($value['card-data_callback'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success history_card_callback_user" data-history-card-code="<?= $value['card-data_request_id'] ?>" data-history-card-callback-user="<?= $value['card-data_request_id'] ?>" data-bs-toggle="tooltip" title="Callback về member">
                                                        <i class="fa-solid fa-phone"></i>
                                                    </button>
                                                <?php endif; ?>
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

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

    </div>
</div>

<?php
require('../../../layout/admin/footer.php');
?>
<script>
    new DataTable('#data-table_list-exCard');


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

                    $.post('../../../ajaxs/admin/action/memberDetail.php', {
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
                                    window.location.href = "<?= "/admin/list/card" ?>";
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
    // LỊCH SỬ ĐỔI THẺ
    handleButtonClick('.history_card_send', 'history_card_send', "GỬI THẺ NGAY LẬP TỨC!", "history_card_code", "history-card-code"); // Gửi lệnh gửi card tới web mẹ
    handleButtonClick('.history_card_callback_user', 'history_card_callback_user', "Callback về user!", "history_card_code", "history-card-code"); // Gửi callback về user
    handleButtonClick('.history_card_duyet_refurn', 'history_card_duyet_refurn', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Duyệt thẻ & refurn
    handleButtonClick('.history_card_duyet_nonRefurn', 'history_card_duyet_nonRefurn', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Duyệt thẻ & KHÔNG refurn
    handleButtonClick('.history_card_duyet_saiMenhGia', 'history_card_duyet_saiMenhGia', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Duyệt thẻ Sai mệnh giá & refurn giá trị thấp
    handleButtonClick('.history_card_cancel_nonRefurn', 'history_card_cancel_nonRefurn', "Đổi trạng thái đơn THÀNH CÔNG!", "history_card_code", "history-card-code"); // Hủy thẻ & KHÔNG refurn
    // =====================================
</script>