<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

checkToken("client");

// Kiểm tra có tồn tại mã đơn hàng không
if (!isset($_GET['transfer_user'])) {
    header("Location:" . getDomain() . "/transfer");
    die;
}

// Kiểm tra xem người dùng có tồn tại không
$transfer_user = $purifier->purify($_GET['transfer_user']);
$checkUser = pdo_query_one("SELECT * FROM `user` WHERE `user_email` = ? OR `user_phone` = ? OR `user_fullname` = ?", [$transfer_user, $transfer_user, $transfer_user]);
if (empty($checkUser)) {
    header("Location:" . getDomain() . "/transfer");
    die;
}

// Nếu là chính mình
if ($checkUser['user_id'] == getIdUser()) {
    header("Location:" . getDomain() . "/transfer");
    die;
}

$min_transfer     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'min_transfer'");
$max_transfer     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'max_transfer'");
$user_id          = getIdUser();

// Thông tin người dùng
$user_email    = empty($checkUser['user_email']) ? 'Không có' : $checkUser['user_email'];
$user_phone    = empty($checkUser['user_phone']) ? 'Không có' : $checkUser['user_phone'];

// Lịch sử giao dịch
$history_transfer = pdo_query("SELECT * FROM `transfer` WHERE `transfer_user_from` = ? OR `transfer_user_to` = ? ORDER BY `transfer_id` DESC", [$user_id, $user_id]);

// Header
$title_website = 'Chuyển tiền';
require('../../../layout/client/header.php');
?>
<div class="hp-main-layout-content">

    <div class="row mb-32 gy-32">
        <div class="col-12">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="h3 d-block mb-8 text-warning"><i class="fa-solid fa-exclamation-triangle"></i> Lưu ý:</span>
                            <div class="divider"></div>

                            <div class="block-content">
                                <div class="alert" role="alert">
                                    <h4 class="alert-heading text-warning">Không đọc mất tiền admin không chịu trách nhiệm !!!</h4>
                                    <p>Số tiền chuyển tối thiểu: <span class="text-danger fw-bold"><?= number_format($min_transfer) ?></span> VNĐ</p>
                                    <p>Số tiền chuyển tối đa: <span class="text-danger fw-bold"><?= number_format($max_transfer) ?></span> VNĐ</p>
                                    <p>Có thể chuyển tiền bằng: <span class="fw-bold">email, số điện thoại, biệt danh</span>.</p>
                                    <hr>
                                    <p class="mb-0">Chuyển tiền sẽ không thể hoàn lại, hãy kiểm tra kỹ thông tin trước khi chuyển tiền.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-32 gy-32">
        <div class="col-12 col-sm-6">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="h3 d-block mb-8">Thông tin người nhận:</span>
                            <div class="divider"></div>
                            <div class="block-content">
                                <div class="alert" role="alert">
                                    <h4 class="alert-heading text-warning"></h4>
                                    <p>Số điện thoại: <span class="text-danger fw-bold"><?= $user_phone ?></span></p>
                                    <p>Email: <span class="text-danger fw-bold"><?= $user_email ?></span> </p>
                                    <!-- Ghi chú -->
                                    <p>Nếu thông tin trên không chính xác, vui lòng kiểm tra lại thông tin người nhận.</p>
                                    <hr>
                                    <p class="mb-0">Vui lòng đọc lưu ý trước khi thực hiện giao dịch.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="h3 d-block mb-8">Chuyển tiền</span>
                            <div class="divider"></div>

                            <div class="block-content" bis_skin_checked="1">
                                <div class="form-group mb-16" bis_skin_checked="1">
                                    <label>Email hoặc số điện thoại người nhận:</label><br />
                                    <div class="input-number w-100">
                                        <div class="input-number-input-wrap">
                                            <input class="input-number-input" id="transfer_email" type="text" value="<?= $transfer_user ?>" disabled />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-16" bis_skin_checked="1">
                                    <label>Số tiền chuyển:</label><br />
                                    <div class="input-number w-100">
                                        <div class="input-number-handler-wrap">
                                            <span class="input-number-handler input-number-handler-up">
                                                <span class="input-number-handler-up-inner">
                                                    <svg viewBox="64 64 896 896" width="1em" height="1em" fill="currentColor">
                                                        <path d="M890.5 755.3L537.9 269.2c-12.8-17.6-39-17.6-51.7 0L133.5 755.3A8 8 0 00140 768h75c5.1 0 9.9-2.5 12.9-6.6L512 369.8l284.1 391.6c3 4.1 7.8 6.6 12.9 6.6h75c6.5 0 10.3-7.4 6.5-12.7z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="input-number-handler input-number-handler-down input-number-handler-down-disabled">
                                                <span class="input-number-handler-down-inner">
                                                    <svg viewBox="64 64 896 896" width="1em" height="1em" fill="currentColor">
                                                        <path d="M884 256h-75c-5.1 0-9.9 2.5-12.9 6.6L512 654.2 227.9 262.6c-3-4.1-7.8-6.6-12.9-6.6h-75c-6.5 0-10.3 7.4-6.5 12.7l352.6 486.1c12.8 17.6 39 17.6 51.7 0l352.6-486.1c3.9-5.3.1-12.7-6.4-12.7z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="input-number-input-wrap">
                                            <input class="input-number-input" id="transfer_cash" type="number" min="0" max="<?= $max_transfer ?>" value="10000" step="1000" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-16" bis_skin_checked="1">
                                    <label>Nội dung:</label>
                                    <textarea class="form-control form-control-alt" id="transfer_description" placeholder="Nội dung"></textarea>
                                </div>
                                <div class="form-group" bis_skin_checked="1">
                                    <button class="btn btn-hero-danger btn-block" id="transfer_send">
                                        XÁC NHẬN
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
<?php
// Footer
require('../../../layout/client/footer.php');
?>
<script>
    $('#transfer_send').click(function() {
        Swal.fire({
            title: '',
            text: "Sai thông tin sẽ dẫn đến mất tiền & admin không giải quyết. Kiểm tra kỹ nhé!",
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
                }, 3500);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    transfer_email: $('#transfer_email').val(),
                    transfer_cash: $('#transfer_cash').val(),
                    transfer_description: $('#transfer_description').val()
                };

                $.post('./../../ajaxs/main/client/transfer.php', {
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
                                window.location.href = "<?= getDomain() ?>/transfer";
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