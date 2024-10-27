<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách rút tiền
$list_withdraw_wait    = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'wait' OR `wd_status` = 'pending' OR `wd_status` = 'hold' ORDER BY `created_at` DESC");
$list_withdraw_success = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'success' ORDER BY `created_at` DESC");
$list_withdraw_fail    = pdo_query("SELECT * FROM `withdraw` WHERE `wd_status` = 'fail' OR `wd_status` = 'cancel' ORDER BY `created_at` DESC");

// Header
$title_website = 'Danh sách rút tiền';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">

    <!-- Aler Note Dev -->
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Chú ý!</strong> Bấm vào <span class="badge">Mã đơn</span> <span class="badge">Số tiền</span> <span class="badge">STK</span> sẽ tự động copy nội dung.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- CHỜ - ĐANG CHUYỂN -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">RÚT TIỀN CHỜ</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-withdraw-wait" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Số tiền</th>
                                            <th class="text-nowrap">Ngân hàng</th>
                                            <th class="text-nowrap">Tên tài khoản</th>
                                            <th class="text-nowrap">Số tài khoản</th>
                                            <th class="text-nowrap">Trạng thái API</th>
                                            <th class="text-nowrap">Message API</th>
                                            <th class="text-nowrap">Thời gian</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_withdraw_wait as $withdraw) {
                                            $user_email = getEmailUser($withdraw['user_id']);
                                            $wd_message_api = empty($withdraw['wd_api_message']) ? ' - ' : $withdraw['wd_api_message'];
                                            $wd_status_api  = empty($withdraw['wd_api_status']) ? ' - ' : $withdraw['wd_api_status'];
                                            $wd_updated_api = empty($withdraw['wd_updated_api']) ? ' - ' : $withdraw['wd_updated_api'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_email ?>" class="badge"><?= limitShow($user_email) ?></a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= statusBank($withdraw['wd_status'], "color") ?>"><?= statusBank($withdraw['wd_status']) ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge" data-copy="<?= "CARD2K " . $withdraw['wd_code'] ?>"><?= $withdraw['wd_code'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge text-danger" data-copy="<?= $withdraw['wd_cash'] ?>"><?= number_format($withdraw['wd_cash']) ?></span>
                                                </td>
                                                <td class="text-nowrap"> <?= showNameBank($withdraw['wd_bank_name']) ?> </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $withdraw['wd_bank_owner'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge" data-copy="<?= $withdraw['wd_number_account'] ?>"><?= $withdraw['wd_number_account'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge"><?= $wd_status_api ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $wd_message_api ?></span>
                                                </td>
                                                <td class="text-nowrap"> 
                                                    <?= $withdraw['created_at'] ?>
                                                    <br>
                                                    <span class="text-success"><?= $wd_updated_api ?></span>
                                                </td>
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

    <!-- THÀNH CÔNG -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">RÚT TIỀN THÀNH CÔNG</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-withdraw-success" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Số tiền</th>
                                            <th class="text-nowrap">Ngân hàng</th>
                                            <th class="text-nowrap">Tên tài khoản</th>
                                            <th class="text-nowrap">Số tài khoản</th>
                                            <th class="text-nowrap">Trạng thái API</th>
                                            <th class="text-nowrap">Message API</th>
                                            <th class="text-nowrap">Thời gian</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_withdraw_success as $withdraw) {
                                            $user_email = getEmailUser($withdraw['user_id']);
                                            $wd_message_api = empty($withdraw['wd_api_message']) ? ' - ' : $withdraw['wd_api_message'];
                                            $wd_status_api  = empty($withdraw['wd_api_status']) ? ' - ' : $withdraw['wd_api_status'];
                                            $wd_updated_api = empty($withdraw['wd_updated_api']) ? ' - ' : $withdraw['wd_updated_api'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_email ?>" class="badge"><?= limitShow($user_email) ?></a>
                                                </td>
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
                                                <td class="text-nowrap"> 
                                                    <?= $withdraw['created_at'] ?>
                                                    <br>
                                                    <span class="text-success"><?= $wd_updated_api ?></span>
                                                </td>
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

    <!-- THẤT BẠI -->
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">RÚT TIỀN THẤT BẠI</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-withdraw-fail" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Số tiền</th>
                                            <th class="text-nowrap">Ngân hàng</th>
                                            <th class="text-nowrap">Tên tài khoản</th>
                                            <th class="text-nowrap">Số tài khoản</th>
                                            <th class="text-nowrap">Trạng thái API</th>
                                            <th>Message API</th>
                                            <th class="text-nowrap">Thời gian</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_withdraw_fail as $withdraw) {
                                            $user_email = getEmailUser($withdraw['user_id']);
                                            $wd_message_api = empty($withdraw['wd_api_message']) ? ' - ' : $withdraw['wd_api_message'];
                                            $wd_status_api  = empty($withdraw['wd_api_status']) ? ' - ' : $withdraw['wd_api_status'];
                                            $wd_updated_api = empty($withdraw['wd_updated_api']) ? ' - ' : $withdraw['wd_updated_api'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_email ?>" class="badge"><?= limitShow($user_email) ?></a>
                                                </td>
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
                                                <td>
                                                    <span class="badge"><?= $wd_message_api ?></span>
                                                </td>
                                                <td class="text-nowrap"> 
                                                    <?= $withdraw['created_at'] ?>
                                                    <br>
                                                    <span class="text-success"><?= $wd_updated_api ?></span>
                                                </td>
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

</div>

<?php
require('../../../layout/admin/footer.php');
?>
<script>
    new DataTable('#data-table_list-withdraw-wait');
    new DataTable('#data-table_list-withdraw-success');
    new DataTable('#data-table_list-withdraw-fail');

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
                                    window.location.href = "<?= "/admin/list/withdraw" ?>";
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

    $(document).ready(function() {
        $('.badge').click(function() {
            var copyText = $(this).attr('data-copy');

            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = copyText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
        });
    });

    // =====================================
    // LỊCH SỬ RÚT TIỀN
    handleButtonClick('.history_withdraw_duyet', 'history_withdraw_duyet', "Đổi trạng thái đơn THÀNH CÔNG!", "history_withdraw_code", "history-withdraw-code"); // Duyệt
    handleButtonClick('.history_withdraw_cancel_refurn', 'history_withdraw_cancel_Refurn', "HỦY ĐƠN & HOÀN TIỀN!", "history_withdraw_code", "history-withdraw-code"); // Hủy refurn
    handleButtonClick('.history_withdraw_cancel_nonRefurn', 'history_withdraw_cancel_nonRefurn', "HỦY ĐƠN & KHÔNG HOÀN TIỀN!", "history_withdraw_code", "history-withdraw-code"); // Hủy không refurn
    handleButtonClick('.history_withdraw_reload', 'history_withdraw_reload', "GỬI ĐƠN RÚT NGAY LẬP TỨC!", "history_withdraw_code", "history-withdraw-code"); // Gửi lệnh rút tiền
    // =====================================
</script>