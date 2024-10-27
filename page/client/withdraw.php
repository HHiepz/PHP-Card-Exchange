<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken("client");

$user_id      = getIdUser();

// Thông báo hiển thị đầu tiên khi truy cập trang
$noti_withdraw = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'noti_withdraw'");

// Danh sách ngân hàng
$list_bank_user = pdo_query("SELECT * FROM `bank-user` WHERE `user_id` = ?", [$user_id]);

// Lịch sử rút tiền
$history_withdraw = pdo_query("SELECT * FROM `withdraw` WHERE `user_id` = ? ORDER BY `wd_id` DESC", [$user_id]);

// Phí rút tiền (cố định)
$fee_withdraw = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'fee_withdraw'");

// Rút tối thiểu
$min_withdraw = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'min_withdraw'");

// Header
$title_website = 'Rút tiền';
require('../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
    <div class="row row-cols-1 row-cols-md-2 g-16 mb-32">
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-center">TẠO YÊU CẦU RÚT TIỀN</h3>
                            <div class="divider"></div>
                            <div class="alert alert-success" role="alert">
                                Phí rút tiền: <?= ($fee_withdraw == 0) ? 'Miễn phí' : number_format($fee_withdraw) . 'đ' ?>
                            </div>
                        </div>
                        <div class="block-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Số dư:</td>
                                        <td class="text-danger" style="font-weight: bold; font-size: 20px">
                                            <?= number_format(getCashUser()) ?>đ
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Số tiền rút:</td>
                                        <td>
                                            <input type="number" class="form-control form-control-alt" id="withdraw_cash" placeholder="-" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ngân hàng:</td>
                                        <td>
                                            <select class="js-select2 form-control js-select2-enabled" id="withdraw_bank_key" style="width: 100%" data-placeholder="Chọn ngân hàng" tabindex="0" aria-hidden="false">

                                                <option disabled selected>-</option>
                                                <?php
                                                foreach ($list_bank_user as $bank) {
                                                    $key  = $bank['bank-user_key'];
                                                    $name = showNameBank($bank['bank-user_name']) . ' - ' . $bank['bank-user_number_account'] . ' - ' . $bank['bank-user_owner'];
                                                ?>
                                                    <option value="<?= $key ?>"><?= $name ?></option>
                                                <?php
                                                }
                                                ?>

                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="form-group">
                                <button type="submit" id="withdraw" class="btn btn-hero-primary">
                                    RÚT TIỀN NGAY
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-center">THÊM THÔNG TIN NGÂN HÀNG/MOMO</h3>
                            <div class="divider"></div>
                            <div calss="block-options"></div>
                        </div>
                        <div class="block-content">
                            <div id="panel_add">

                                <div class="text-center" style="margin-bottom: 35px">
                                    <h4 style="margin-bottom: 0">
                                        THÊM THÔNG TIN RÚT TIỀN
                                    </h4>
                                    <span class="text-muted">Bắt đầu thêm thông tin rút tiền ngay hôm nay.</span>
                                </div>

                                <div>
                                    <div class="form-group">
                                        <label for="owner">Chọn ngân hàng:</label>
                                        <select class="js-select2 form-control js-select2-enabled select2-hidden-accessible mb-16" id="addBank_bank_name" style="width: 100%" data-placeholder="Chọn ngân hàng" data-select2-id="example-select2" tabindex="-1" aria-hidden="true">

                                            <option disabled selected>-</option>
                                            <?php
                                            foreach (list_bank() as $bank) {
                                            ?>
                                                <option value="<?= $bank['codeDB'] ?>"><?= $bank['name'] ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <label for="owner">Chủ tài khoản:</label>
                                    <div class="form-group">
                                        <input class="form-control form-control-alt mb-16" id="addBank_owner" maxlength="36" placeholder="-" />
                                    </div>

                                    <label for="owner">Số tài khoản:</label>
                                    <div class="form-group">
                                        <input class="form-control form-control-alt card-rule mb-16" id="addBank_number_account" maxlength="20" placeholder="-" />
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-hero-primary btn-block" id="addBank">
                                            THÊM NGÂN HÀNG
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


    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8">Lịch sử rút tiền</span>
                        <div class="divider"></div>

                        <div class="block-content block-content-full" bis_skin_checked="1">
                            <div class="block-content" bis_skin_checked="1">
                                <div class="table-responsive" bis_skin_checked="1">

                                    <table id="data-table_history-withdraw" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap">Mã đơn</th>
                                                <th class="text-nowrap">Số tiền</th>
                                                <th class="text-nowrap">Ngân hàng</th>
                                                <th class="text-nowrap">Tên tài khoản</th>
                                                <th class="text-nowrap">Số tài khoản</th>
                                                <th class="text-nowrap">Trạng thái</th>
                                                <th class="text-nowrap">Thời gian</th>
                                                <th class="text-nowrap">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($history_withdraw as $value) {
                                                $wd_status = $value['wd_status'];
                                            ?>
                                                <tr>
                                                    <td class="text-nowrap"><?= $value['wd_code'] ?></td>
                                                    <td class="text-nowrap">
                                                        <span class="text-danger"><?= number_format($value['wd_cash']) ?></span>
                                                    </td>
                                                    <td class="text-nowrap"><?= showNameBank($value['wd_bank_name']) ?></td>
                                                    <td class="text-nowrap"><?= $value['wd_bank_owner'] ?></td>
                                                    <td class="text-nowrap"><?= $value['wd_number_account'] ?></td>
                                                    <td class="text-nowrap">
                                                        <span class="badge badge-<?= statusBank($wd_status, "color") ?>" data-bs-toggle="tooltip" title="<?= $value['wd_api_message'] ?>"><?= statusBank($wd_status) ?></span>
                                                    </td>
                                                    <td class="text-nowrap"><?= $value['created_at'] ?></td>
                                                    <td class="text-nowrap">
                                                        <!-- Xem chi tiết -->
                                                        <a href="<?= getDomain() . "/details/withdraw/" . $value['wd_code'] ?>" type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
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
        </div>
    </div>


    <?php
    if (!empty($list_bank_user)) {
    ?>
        <div class="col-12 mb-32">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="h3 d-block mb-8">Danh sách ngân hàng</span>
                            <div class="divider"></div>

                            <div class="block-content block-content-full" bis_skin_checked="1">
                                <div class="block-content" bis_skin_checked="1">
                                    <div class="table-responsive">

                                        <table id="data-table_list-bank" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap">Ngân hàng</th>
                                                    <th class="text-nowrap">Họ tên người nhận</th>
                                                    <th class="text-nowrap">Số tài khoản</th>
                                                    <th class="text-nowrap">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                foreach ($list_bank_user as $bank) {
                                                    $key  = $bank['bank-user_key'];
                                                    $name = showNameBank($bank['bank-user_name']) . ' - ' . $bank['bank-user_number_account'] . ' - ' . $bank['bank-user_owner'];
                                                ?>
                                                    <tr>
                                                        <td class="text-nowrap"><?= showNameBank($bank['bank-user_name']) ?></td>
                                                        <td class="text-nowrap"><?= $bank['bank-user_owner'] ?></td>
                                                        <td class="text-nowrap"><?= $bank['bank-user_number_account'] ?></td>
                                                        <td class="text-nowrap">
                                                            <button type="submit" class="btn btn-sm btn-danger removeBank" data-bank-key="<?= $key ?>">Xóa</button>
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
            </div>
        </div>
    <?php
    }
    ?>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Loa loa loa...</h5>
                </div>
                <div class="modal-body">
                    <?= $noti_withdraw ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    $(document).ready(function() {
        $('#myModal').modal('show');
    });
    $(document).ready(function() {
        $('.btn-secondary').click(function() {
            $('#myModal').modal('hide');
        });
    });

    // Thêm ngân hàng
    $('#addBank').click(function() {
        var data = {
            bank_name: $('#addBank_bank_name').val(),
            owner: $('#addBank_owner').val(),
            number_account: $('#addBank_number_account').val()
        };

        sendAjaxRequest('./ajaxs/main/client/action/addBank.php', {
            data: JSON.stringify(data)
        }, function(result) {
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
    });

    // Xóa ngân hàng
    $('.removeBank').click(function() {
        var bankKey = $(this).data('bank-key');

        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn xóa ngân hàng này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                sendAjaxRequest('./ajaxs/main/client/action/removeBank.php', {
                    data: JSON.stringify({
                        bank_key: bankKey
                    })
                }, function(result) {
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
    });

    // Rút tiền
    $('#withdraw').click(function() {
        var withdraw_cash = $('#withdraw_cash').val();
        var withdraw_bank_key = $('#withdraw_bank_key').val();

        if (!withdraw_cash || !withdraw_bank_key) {
            Swal.fire('', 'Vui lòng điền đầy đủ thông tin', 'error');
            return;
        }

        Swal.fire({
            title: '',
            text: "Bạn có chắc chắn muốn thực hiện giao dịch này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                sendAjaxRequest('./ajaxs/main/client/withdraw.php', {
                    data: JSON.stringify({
                        withdraw_cash: withdraw_cash,
                        withdraw_bank_key: withdraw_bank_key
                    })
                }, function(result) {
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
    });

    // Hàm gửi yêu cầu Ajax với loading
    function sendAjaxRequest(url, data, successCallback, errorCallback) {
        showLoading();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr, status, error) {
                if (errorCallback) errorCallback(xhr, status, error);
                else Swal.fire('', 'Có lỗi xảy ra khi gửi yêu cầu', 'error');
            },
            complete: function() {
                hideLoading();
            }
        });
    }


    // Bảng dữ liệu
    new DataTable('#data-table_list-bank');
    new DataTable('#data-table_history-withdraw', {
        order: [
            [6, 'desc']
        ],
        columnDefs: [{
            targets: 6,
            type: 'date',
            render: function(data, type, row) {
                return moment(data).format('YYYY-MM-DD HH:mm:ss');
            }
        }]
    });
</script>