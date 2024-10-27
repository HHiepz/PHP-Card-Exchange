<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken("client");

// Danh sách thẻ cào
$telco_list = pdo_query("SELECT * FROM `card-rare` WHERE `card-rare_status` = 1");


// Danh sách mệnh giá
$amount_list = list_amount_buyCard();

// Dữ liệu mệnh giá [Loại thẻ cào => [Mệnh giá => Phí giảm giá]] dùng để render mệnh giá theo nhà mạng
$telcoData = array_map(function ($telco) {
    return array_filter($telco, function ($key) {
        return is_numeric($key);
    }, ARRAY_FILTER_USE_KEY);
}, $amount_list);

// Header
$title_website = 'Mua thẻ cào tự động 24/7';
require('../../layout/client/header.php');
?>

<div class="hp-main-layout-content">

    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8">Chọn nhà mạng</span>
                        <div class="divider mt-18 mb-16"></div>
                        <div class="col-12 col-lg-12">
                            <ul class="nav nav-tabs mb-12" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="thedienthoai-tab" data-bs-toggle="tab" data-bs-target="#thedienthoai" type="button" role="tab" aria-controls="thedienthoai" aria-selected="true">
                                        Thẻ điện thoại
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="thegame-tab" data-bs-toggle="tab" data-bs-target="#thegame" type="button" role="tab" aria-controls="thegame" aria-selected="false">
                                        Thẻ game
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="contentTab">
                                <div class="tab-pane fade active show" id="thedienthoai" role="tabpanel" aria-labelledby="thedienthoai-tab">
                                    <div class="col-12">
                                        <div class="row g-16">

                                            <?php
                                            foreach ($telco_list as $telco) {
                                                if ($telco['card-rare_type'] != 'phone') {
                                                    continue;
                                                }
                                            ?>
                                                <div class="col-12 col-md-3">
                                                    <div class="hp-select-box-item">
                                                        <input type="radio" hidden="" id="<?= $telco['card-rare_code'] ?>" name="telco" />
                                                        <label for="<?= $telco['card-rare_code'] ?>" class="d-block hp-cursor-pointer">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row text-center mb-8">
                                                                        <div class="col-12 my-12">
                                                                            <img src="<?= getDomain() ?>/frontend/app-assets/img/selectbox/card/<?= $telco['card-rare_img'] ?>" width="175px" alt="<?= $telco['card-rare_name'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="thegame" role="thegame" aria-labelledby="thegame-tab">
                                    <div class="col-12">
                                        <div class="row g-16">

                                            <?php
                                            foreach ($telco_list as $telco) {
                                                if ($telco['card-rare_type'] != 'game') {
                                                    continue;
                                                }
                                            ?>
                                                <div class="col-12 col-md-3">
                                                    <div class="hp-select-box-item">
                                                        <input type="radio" hidden="" id="<?= $telco['card-rare_code'] ?>" name="telco" />
                                                        <label for="<?= $telco['card-rare_code'] ?>" class="d-block hp-cursor-pointer">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row text-center mb-8">
                                                                        <div class="col-12 my-12">
                                                                            <img src="<?= getDomain() ?>/frontend/app-assets/img/selectbox/card/<?= $telco['card-rare_img'] ?>" width="175px" alt="<?= $telco['card-rare_name'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>

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
                        <span class="h3 d-block mb-8">Chọn mệnh giá</span>
                        <div class="divider mt-18 mb-16"></div>
                        <div class="col-12 col-lg-12">
                            <div class="col-12">
                                <div class="row g-16">
                                    <div class="col-12 row g-16" id="js_list_price">
                                        <!-- Note dev: Mệnh giá sẽ tự động render ở đây ! -->
                                    </div>
                                    <div class="divider mt-18 mb-16"></div>

                                    <div class="col-12 col-lg-12 text-center">
                                        <div class="hp-select-box-item">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row justify-content-between py-8">
                                                        <div class="col-12 col-md-12">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <h4>Số lượng:</h4>
                                                                    <div class="input-number">
                                                                        <div class="input-number-handler-wrap">
                                                                            <span class="input-number-handler input-number-handler-up" id="order_quantity_up">
                                                                                <span class="input-number-handler-up-inner">
                                                                                    <svg viewBox="64 64 896 896" width="1em" height="1em" fill="currentColor">
                                                                                        <path d="M890.5 755.3L537.9 269.2c-12.8-17.6-39-17.6-51.7 0L133.5 755.3A8 8 0 00140 768h75c5.1 0 9.9-2.5 12.9-6.6L512 369.8l284.1 391.6c3 4.1 7.8 6.6 12.9 6.6h75c6.5 0 10.3-7.4 6.5-12.7z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </span>
                                                                            </span>

                                                                            <span class="input-number-handler input-number-handler-down input-number-handler-down-disabled" id="order_quantity_down">
                                                                                <span class="input-number-handler-down-inner">
                                                                                    <svg viewBox="64 64 896 896" width="1em" height="1em" fill="currentColor">
                                                                                        <path d="M884 256h-75c-5.1 0-9.9 2.5-12.9 6.6L512 654.2 227.9 262.6c-3-4.1-7.8-6.6-12.9-6.6h-75c-6.5 0-10.3 7.4-6.5 12.7l352.6 486.1c12.8 17.6 39 17.6 51.7 0l352.6-486.1c3.9-5.3.1-12.7-6.4-12.7z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </span>
                                                                            </span>
                                                                        </div>

                                                                        <div class="input-number-input-wrap">
                                                                            <input class="input-number-input" id="buyCard_quantity" type="number" min="0" max="10" value="0" step="1" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-6"></div>
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
            </div>
        </div>
    </div>

    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8">Xác nhận giao dịch</span>
                        <div class="divider mt-18 mb-16"></div>
                        <div class="row g-32">
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row g-16">
                                            <div class="col-6 hp-flex-none w-auto">
                                                <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-primary-4 hp-bg-color-dark-primary rounded-circle">
                                                    <i class="fa-solid fa-globe text-primary hp-text-color-dark-primary-2" style="font-size: 24px"></i>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <h3 class="mb-4 mt-8" id="order_telco"> - </h3>
                                                <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                                                    Nhà mạng
                                                </p>
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
                                                <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-secondary-4 hp-bg-color-dark-secondary rounded-circle">
                                                    <i class="fa-solid fa-money-bill-1 text-secondary" style="font-size: 24px"></i>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <h3 class="mb-4 mt-8" id="order_price"> - </h3>
                                                <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                                                    Mệnh giá
                                                </p>
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
                                                    <i class="fa-solid fa-percent text-warning" style="font-size: 24px"></i>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <h3 class="mb-4 mt-8" id="order_discount"> - %</h3>
                                                <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                                                    Chiết khấu
                                                </p>
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
                                                    <i class="fa-solid fa-money-check text-danger" style="font-size: 24px"></i>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <h3 class="mb-4 mt-8"><span id="order_quantity"> - </span> thẻ</h3>
                                                <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                                                    Số lượng
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-12 text-center">
                                <div class="hp-select-box-item">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row justify-content-between py-8">
                                                <div class="col-12 col-md-12">
                                                    <div class="row">
                                                        <div class="col">
                                                            Tổng thanh toán
                                                            <span class="h4 d-block mb-0" id="order_total"> - </span>
                                                            <span class="hp-caption text-black-80 hp-text-color-dark-30 d-block"></span>
                                                            <div class="divider mt-18 mb-16"></div>
                                                            <button class="btn btn-primary px-12 w-50" id="buyCard_send">
                                                                <span>THANH TOÁN</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6"></div>
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
    </div>
</div>
<?php
// Footer
require('../../layout/client/footer.php');
?>
<script>
    var buyCard_type;
    var buyCard_price;
    var buyCard_quantity = $('#buyCard_quantity').val();

    var telcoData = <?= json_encode($telcoData); ?>;

    // Lấy nhà mạng
    $(document).on('change', 'input[name="telco"]', function() {
        // Lấy giá trị của radio button được chọn
        buyCard_type = $(this).attr('id');

        // Thay đổi nội dung của id="order_telco"
        $('#order_telco').text(buyCard_type);

        // Render mệnh giá theo nhà mạng
        renderPrices(buyCard_type);
    });

    // Lấy mệnh giá và hiển thị phí chiết khấu và tính tổng sau khi trừ chiết khấu
    $(document).on('change', 'input[name="price"]', function() {
        // Lấy giá trị của radio button được chọn
        buyCard_price = parseFloat($(this).attr('id'));
        var total = buyCard_price * buyCard_quantity;

        // Thay đổi nội dung của id="order_price" và format giá tiền
        $('#order_price').text(formatMoney(buyCard_price, 0, ',', '.') + 'đ');

        var discount = telcoData[buyCard_type][buyCard_price]; // Lấy mức chiết khấu
        var discountedPrice = buyCard_price - (buyCard_price * discount / 100); // Tính giá sau khi chiết khấu

        // Thay đổi nội dung của id="order_discount" và hiển thị phí chiết khấu
        $('#order_discount').text(discount + '%');

        // Tính tổng sau khi trừ chiết khấu
        var totalDiscounted = discountedPrice * buyCard_quantity;

        // Thay đổi nội dung của id="order_total" và format giá tiền
        $('#order_total').text(formatMoney(totalDiscounted, 0, ',', '.') + 'đ');
    });

    $(document).on('change', '#buyCard_quantity', function() {
        buyCard_quantity = $(this).val();
        var total = buyCard_price * buyCard_quantity;

        // Thay đổi nội dung của id="order_quantity"
        $('#order_quantity').text(buyCard_quantity);

        // Tính tổng sau khi trừ chiết khấu
        var discount = telcoData[buyCard_type][buyCard_price];
        var discountedPrice = buyCard_price - (buyCard_price * discount / 100);
        var totalDiscounted = discountedPrice * buyCard_quantity;

        // Thay đổi nội dung của id="order_total" và format giá tiền
        $('#order_total').text(formatMoney(totalDiscounted, 0, ',', '.') + 'đ');
    });

    $(document).on('click', '#order_quantity_up', function() {
        buyCard_quantity++;
        $('#buyCard_quantity').val(buyCard_quantity);
        var total = buyCard_price * buyCard_quantity;

        // Thay đổi nội dung của id="order_quantity"
        $('#order_quantity').text(buyCard_quantity);

        // Tính tổng sau khi trừ chiết khấu
        var discount = telcoData[buyCard_type][buyCard_price];
        var discountedPrice = buyCard_price - (buyCard_price * discount / 100);
        var totalDiscounted = discountedPrice * buyCard_quantity;

        // Thay đổi nội dung của id="order_total" và format giá tiền
        $('#order_total').text(formatMoney(totalDiscounted, 0, ',', '.') + 'đ');
    });

    $(document).on('click', '#order_quantity_down', function() {
        if (buyCard_quantity > 0) {
            buyCard_quantity--;
            $('#buyCard_quantity').val(buyCard_quantity);
            var total = buyCard_price * buyCard_quantity;

            // Thay đổi nội dung của id="order_quantity"
            $('#order_quantity').text(buyCard_quantity);

            // Tính tổng sau khi trừ chiết khấu
            var discount = telcoData[buyCard_type][buyCard_price];
            var discountedPrice = buyCard_price - (buyCard_price * discount / 100);
            var totalDiscounted = discountedPrice * buyCard_quantity;

            // Thay đổi nội dung của id="order_total" và format giá tiền
            $('#order_total').text(formatMoney(totalDiscounted, 0, ',', '.') + 'đ');
        }
    });

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
                $('#buyCard_send').prop('disabled', false).text('Mua thẻ');
            }
        });
    }

    // Bấm id="buyCard_send" thực hiện mua thẻ
    $('#buyCard_send').click(function() {
        var button = $(this);
        if (typeof buyCard_type === 'undefined' || typeof buyCard_price === 'undefined') {
            Swal.fire('', 'Bạn phải chọn nhà mạng và mệnh giá', 'error');
            return;
        }

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
                }, 100);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.prop('disabled', true);
                button.text('Hệ thống đang kiểm tra,... vui lòng đợi');

                var data = {
                    buyCard_type: buyCard_type,
                    buyCard_price: buyCard_price,
                    buyCard_quantity: buyCard_quantity
                };

                sendAjaxRequest(
                    './ajaxs/main/client/buyCard.php', {
                        data: JSON.stringify(data)
                    },
                    function(result) {
                        var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                        if (result.success) {
                            Swal.fire({
                                title: '',
                                text: result.message + dataMessage,
                                icon: 'success',
                                willClose: function() {
                                    window.location.href = './historyBuyCard ';
                                }
                            });
                        } else {
                            Swal.fire('', result.message + dataMessage, 'error');
                        }
                    },
                    function() {
                        Swal.fire('', 'Có lỗi xảy ra khi gửi yêu cầu', 'error');
                    }
                );
            }
        });
    });

    // Render mệnh giá theo nhà mạng và giá sau khi trừ chiết khấu
    function renderPrices(buyCard_type, discount) {
        var prices = Object.entries(telcoData[buyCard_type]);
        var js_list_price = document.getElementById('js_list_price');

        js_list_price.innerHTML = ''; // Clear the current content

        for (var i = 0; i < prices.length; i++) {
            var [denomination, price] = prices[i];
            var giaGoc = parseFloat(denomination); // Giá góc
            var phiGiam = parseFloat(price); // Phí giảm giá thẻ cào
            var giaSauKhiChietKhau = giaGoc - (giaGoc * phiGiam / 100); // Giá sau khi chiết khấu

            var html = `
                <div class="col-12 col-lg-6">
                    <div class="hp-select-box-item">
                        <input type="radio" hidden="" id="${giaGoc}" name="price" />
                        <label for="${giaGoc}" class="d-block hp-cursor-pointer">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row justify-content-between py-8">
                                        <div class="col-12 col-md-6">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="h4 d-block mb-0">Thẻ ${formatMoney(giaGoc, 0, ',', '.')}</span>
                                                    <span class="hp-caption text-black-80 hp-text-color-danger-30 d-block">Giảm giá: ${phiGiam}%</span>
                                                    <span class="hp-caption text-black-80 hp-text-color-danger-30 d-block">Giá sau khi chiết khấu: ${formatMoney(giaSauKhiChietKhau, 0, ',', '.')}đ</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            `;
            js_list_price.innerHTML += html;
        }
    }

    // Hàm format tiền (1000 -> 1,000)
    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    };
</script>