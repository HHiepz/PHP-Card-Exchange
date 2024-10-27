<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken("client");

$user_id = getIdUser();

// Danh sách thẻ
$list_topup      = pdo_query("SELECT * FROM `topup` WHERE `topup_status` = 1");
$list_topup_rare = pdo_query("SELECT * FROM `topup-rare` WHERE `topup-rare_status` = 1");

// Process the topup data
$topup_data = processTopupData($list_topup, $list_topup_rare);

// Header
$title_website = 'Nạp tiền điện thoại';
require('../../layout/client/header.php');
?>

<div class="hp-main-layout-content">
    <div class="row mb-32 gy-32">
        <div class="col-12">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card mb-32">
                        <div class="card-body">
                            <span class="h3 d-block mb-8">Bảng chiết khấu nạp tiền điện thoại</span>
                            <div class="divider"></div>

                            <div class="block-content">
                                <ul class="nav nav-tabs" id="discountTabs" role="tablist">
                                    <?php foreach ($topup_data as $code => $data): ?>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link <?= $code === 'vietteltt' ? 'active' : '' ?>" id="<?= $code ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $code ?>" type="button" role="tab" aria-controls="<?= $code ?>" aria-selected="<?= $code === 'vietteltt' ? 'true' : 'false' ?>">
                                                <i class="fas fa-<?= $data['type'] === 'phone' ? 'mobile-alt' : 'gamepad' ?>"></i> <?= $data['name'] ?>
                                            </button>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="tab-content mt-3" id="discountTabContent">
                                    <?php foreach ($topup_data as $code => $data): ?>
                                        <div class="tab-pane fade <?= $code === 'vietteltt' ? 'show active' : '' ?>" id="<?= $code ?>" role="tabpanel" aria-labelledby="<?= $code ?>-tab">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Mệnh Giá</th>
                                                            <th>Giá bán</th>
                                                            <th>Chiết khấu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['rates'] as $rate): ?>
                                                            <tr>
                                                                <td><strong><?= $rate['name'] ?></strong></td>
                                                                <td>
                                                                    <span class="text-success"><?= number_format($rate['price']) ?>đ</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-muted"><?= $rate['discount'] ?>%</span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> Lưu ý: Giá bán đã được trừ chiết khấu. Vui lòng kiểm tra trước khi thực hiện giao dịch.
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-32">
                        <div class="card-body">
                            <h3 class="mb-4">Nạp tiền điện thoại</h3>
                            <div class="divider mb-4"></div>

                            <div class="row g-16">
                                <div class="col-md-6">
                                    <label for="telco" class="form-label">Nhà mạng</label>
                                    <select class="form-select" id="telco" name="telco" required>
                                        <option value="">Chọn nhà mạng</option>
                                        <?php foreach ($topup_data as $code => $data): ?>
                                            <?php if ($data['type'] === 'phone'): ?>
                                                <option value="<?= $code ?>"><?= $data['name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại (10 số)">
                                </div>
                                <div class="col-12">
                                    <label for="amount" class="form-label">Mệnh giá</label>
                                    <select class="form-select" id="amount" name="amount" required>
                                        <option value="">Chọn mệnh giá</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-4">
                                    <label for="finalPrice" class="form-label fs-5 fw-bold">Số tiền cần thanh toán</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg fs-4 fw-bold text-success" id="finalPrice" name="finalPrice" readonly>
                                        <span class="input-group-text fs-4">VNĐ</span>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="button" id="submitTopup" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span class="d-inline-block ps-4">Nạp tiền</span>
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
require('../../layout/client/footer.php');
?>

<script>
    // Remove the tooltip initialization code from here if it exists elsewhere

    // Function to populate amount dropdown
    function populateAmountDropdown() {
        const telco = document.getElementById('telco').value;
        const amountSelect = document.getElementById('amount');
        const topupData = <?= json_encode($topup_data) ?>;

        // Clear existing options
        amountSelect.innerHTML = '<option value="">Chọn mệnh giá</option>';

        if (telco && topupData[telco]) {
            topupData[telco].rates.forEach(function(rate) {
                const option = document.createElement('option');
                option.value = rate.value;
                option.textContent = new Intl.NumberFormat('vi-VN').format(rate.value) + 'đ';
                amountSelect.appendChild(option);
            });
        }

        // Recalculate final price
        calculateFinalPrice();
    }

    // Add event listener for telco change
    document.getElementById('telco').addEventListener('change', populateAmountDropdown);

    // Update the calculateFinalPrice function
    function calculateFinalPrice() {
        const telco = document.getElementById('telco').value;
        const amount = parseInt(document.getElementById('amount').value);
        const topupData = <?= json_encode($topup_data) ?>;

        if (telco && amount && topupData[telco]) {
            const rate = topupData[telco].rates.find(r => r.value === amount);
            if (rate) {
                const finalPrice = rate.price;
                document.getElementById('finalPrice').value = new Intl.NumberFormat('vi-VN').format(finalPrice) + 'đ';
            }
        } else {
            document.getElementById('finalPrice').value = '';
        }
    }

    // Add event listener for amount change
    document.getElementById('amount').addEventListener('change', calculateFinalPrice);

    // Form submission
    document.getElementById('submitTopup').addEventListener('click', function() {
        const telco = document.getElementById('telco').value;
        const phone = document.getElementById('phone').value;
        const amount = document.getElementById('amount').value;

        if (!telco || !phone || !amount) {
            Swal.fire('', 'Vui lòng điền đầy đủ thông tin.', 'error');
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
                sendAjaxRequest('./ajaxs/main/client/orderTopup.php', {
                    data: JSON.stringify({
                        telco: telco,
                        phone: phone,
                        amount: amount
                    })
                }, function(result) {
                    var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                    if (result.success) {
                        Swal.fire({
                            title: '',
                            text: result.message + dataMessage,
                            icon: 'success',
                            willClose: function() {
                                window.location.href = './historyOrderTopup';
                            }
                        });
                    } else {
                        Swal.fire('', result.message + dataMessage, 'error');
                    }
                }, function(xhr, status, error) {
                    Swal.fire('', 'Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.', 'error');
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
                else Swal.fire('', 'Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.', 'error');
            },
            complete: function() {
                hideLoading();
            }
        });
    }
</script>