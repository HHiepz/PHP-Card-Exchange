<?php
require('./core/function.php');
require('./core/database.php');

// Kiểm tra tài khoản có bật xác thực 2 bước Email || 2FA hay không? (nếu có, chuyển hướng đến trang xác thực)
if (checkToken("goto_verify")) {
    $domain = getDomain();
    header("Location: $domain/account/verify");
    exit;
}

// Thông báo hiển thị đầu tiên khi truy cập trang
$noti_index = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'noti_index'");
$noti_index_title = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'noti_index_title'");

// Header
$title_website = 'Đổi thẻ cào thành tiền mặt';
require('./layout/client/header.php');
?>
<div class="hp-main-layout-content">
    <!-- THÔNG BÁO BẢO TRÌ -->
    <?php
    if (pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'") == 0) {
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Thông báo!</strong> Hệ thống đang bảo trì, vui lòng quay lại sau! <a href="https://www.facebook.com/profile.php?id=100066408558292">xem chi tiết</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    }
    ?>

    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8 text-center">Đổi thẻ cào thành tiền mặt</span>
                        <!--<span class="d-block mb-8 text-center text-danger">MỜI 5 NGƯỜI DÙNG ĐỂ NHẬN RANK VIP - LẤY MÃ <a href="<?= getDomain() . '/account/profile' ?>">TẠI ĐÂY</a>!!</span>-->
                        <span class="d-block mb-8 text-center" style="font-family: Roboto, Arial, Helvetica, sans-serif; font-size: 14px;">
                            <!--<marquee behavior="" direction="">-->
                            <!--    Chào Mừng-->
                            <!--    <strong class="text-danger">-->
                            <!--        <img src="https://i.ibb.co/5GbyZPd/gift-box.gif" height="20px" alt="">-->
                            <!--        THÀNH VIÊN MỚI-->
                            <!--        <img src="https://i.ibb.co/5GbyZPd/gift-box.gif" height="20px" alt="">-->
                            <!--    </strong>-->
                            <!--    --->
                            <!--    Sự kiện <strong class="text-warning">TẶNG NGAY RANK VIP</strong> khi hoàn thành nhiệm vụ mời <strong>5 người dùng</strong>, lấy mã <a href="https://card2k.com/account/profile">tại đây</a>-->
                            <!--</marquee>-->
                            <marquee behavior="" direction="">
                                Hey yooo, nhận quà nè.
                                <strong class="text-danger">
                                    <img src="https://i.ibb.co/5GbyZPd/gift-box.gif" height="20px" alt="">
                                    TẶNG RANK ĐẠI LÝ
                                    <img src="https://i.ibb.co/5GbyZPd/gift-box.gif" height="20px" alt="">
                                </strong>
                                -
                                Nhân dịp <strong class="text-warning">20/10</strong>, vui lòng cung cấp thông tin tại <strong><a href="https://discord.card2k.com">Discord</a></strong>.
                            </marquee>
                        </span>
                        <div class="divider"></div>

                        <div class="row g-48">
                            <div class="col-12">
                                <?= $noti_index_title ?>
                                <div class="row g-36">
                                    <div class="col-12">
                                        <div class="row g-16">

                                            <div class="col-12 col-lg-12">
                                                <div class="hp-select-box-item mb-16">
                                                    <label for="select-box-item-1" class="d-block hp-cursor-pointer">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row justify-content-between py-8">
                                                                    <div class="col-12 col-md-12">
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <span class="h4 d-block mb-0 text-center">Tổng
                                                                                    thực nhận: <span class="alert-link" id="order_total">0đ</span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <button class="btn btn-lg btn-primary text-center w-100" id="exchangeCard">
                                                    Đổi Thẻ
                                                </button>
                                            </div>

                                            <div class="col-12 mb-24"></div>
                                            <div class="list_row" id="cardData" bis_skin_checked="1">
                                                <div class="list_row" id="cardData">
                                                    <div class="row form_card mb-16">
                                                        <div class="col-sm-2">
                                                            <select class="form-control telco" name="telco">
                                                                <!-- Nhà mạng -->
                                                                <?php
                                                                foreach (list_telco() as $telco => $value) {
                                                                    echo "<option value='$telco'> $value</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input class="form-control pin card-rule" placeholder="Mã thẻ" name="code" />
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input class="form-control serial card-rule" placeholder="Số serial" name="seri" />
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <select class="form-control amount" name="amount">
                                                                <option selected disabled value="">Mệnh giá</option>
                                                                <option value="10000">10,000 đ</option>
                                                                <option value="20000">20,000 đ</option>
                                                                <option value="30000">30,000 đ</option>
                                                                <option value="50000">50,000 đ</option>
                                                                <option value="100000">100,000 đ</option>
                                                                <option value="200000">200,000 đ</option>
                                                                <option value="300000">300,000 đ</option>
                                                                <option value="500000">500,000 đ</option>
                                                                <option value="1000000">1,000,000 đ</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <a class="btn btn-success btn-sm btn-action" onclick="addRow(this)"><i class="fas fa-plus"></i></a>
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
    </div>

    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8 text-center">Bảng phí đổi thẻ cào</span>
                        <!-- ( Rank Hiện Tại ) -->
                        <?php
                        if (checkToken("request")) {
                            $rank_user = getUserRank_Token($_COOKIE['token']);
                        ?>
                            <span class='d-block mb-8 text-center text-danger'>
                                <span class='badge badge-<?= formatRank($rank_user)['color'] ?>'><?= formatRank($rank_user)['name'] ?></span>
                            </span>
                        <?php
                        } else {
                        ?>
                            <span class='d-block mb-8 text-center text-danger'> Bạn chưa đăng nhập! </span>
                        <?php
                        }
                        ?>
                        <div class=" divider">
                        </div>

                        <div class="col-12 col-lg-12">
                            <!-- Chuyên mục thẻ -->
                            <ul class="nav nav-tabs mb-12" id="myTab" role="tablist">
                                <?php
                                $flag = true;
                                foreach (list_telco() as $telco => $value) {
                                ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php if ($flag) echo "active" ?>" id="<?= $telco ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $telco ?>" type="button" role="tab" aria-controls="<?= $telco ?>" aria-selected="<?= $flag ?>"><?= $value ?></button>
                                    </li>
                                <?php
                                    $flag = false;
                                }
                                ?>
                            </ul>

                            <!-- Bảng phí chiết khấu -->
                            <div class="tab-content" id="contentTab">
                                <?php
                                $flag = true; // "Active' tab đầu tiên

                                if (checkToken("request")) {
                                    $rank_user = getUserRank_Token($_COOKIE['token']);
                                    foreach (list_fee_exchange() as $telco => $rank) {
                                ?>
                                        <div class="tab-pane fade <?php if ($flag) echo "active show" ?>" id="<?= $telco ?>" role="<?= $telco ?>" aria-labelledby="<?= $telco ?>-tab">
                                            <p class="hp-p1-body mb-0">
                                            <div class="table-responsive" bis_skin_checked="1">
                                                <table class="table table-bordered table-striped table-vcenter">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">
                                                                Cấp bậc
                                                            </th>
                                                            <?php
                                                            foreach ($rank[$rank_user] as $key => $value) {
                                                            ?>
                                                                <th class="text-nowrap">
                                                                    Thẻ <?= number_format($key) ?>đ
                                                                </th>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="text-center">
                                                            <td style="font-weight: bold;">
                                                                <span class="badge badge-<?= formatRank($rank_user)['color'] ?>" style="font-size: 15px;"><?= formatRank($rank_user)['name'] ?></span>
                                                            </td>
                                                            <?php
                                                            foreach ($rank[$rank_user] as $key => $value) {
                                                            ?>
                                                                <td>
                                                                    <?= $value ?>%
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class='d-block mb-8 text-center'>
                                                <span> Rank hiện tại của bạn là: <strong class="text-<?= formatRank($rank_user)['color'] ?>"><?= formatRank($rank_user)['name'] ?></strong> và có thời hạn <strong><?= getTimeRank() ?> </strong></span>
                                            </span>
                                            </p>
                                        </div>
                                    <?php
                                        $flag = false;
                                    }
                                } else {
                                    foreach (list_fee_exchange() as $telco => $rank) {
                                    ?>
                                        <div class="tab-pane fade <?php if ($flag) echo "active show" ?>" id="<?= $telco ?>" role="<?= $telco ?>" aria-labelledby="<?= $telco ?>-tab">
                                            <p class="hp-p1-body mb-0">
                                            <div class="table-responsive" bis_skin_checked="1">
                                                <table class="table table-bordered table-striped table-vcenter">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">
                                                                Cấp bậc
                                                            </th>
                                                            <?php
                                                            foreach ($rank['member'] as $key => $value) {
                                                            ?>
                                                                <th>
                                                                    Thẻ <?= number_format($key) ?>đ
                                                                </th>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="text-center">
                                                            <td style="font-weight: bold;">
                                                                <span class="badge badge-danger" style="font-size: 15px;">ĐẠI LÝ/API</span>
                                                            </td>
                                                            <?php
                                                            foreach ($rank['agency'] as $key => $value) {
                                                            ?>
                                                                <td>
                                                                    <?= $value ?>%
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td style="font-weight: bold;">
                                                                <span class="badge badge-warning" style="font-size: 15px;">VIP</span>
                                                            </td>
                                                            <?php
                                                            foreach ($rank['vip'] as $key => $value) {
                                                            ?>
                                                                <td>
                                                                    <?= $value ?>%
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td style="font-weight: bold;">
                                                                <span class="badge badge-info" style="font-size: 15px;">THÀNH VIÊN</span>
                                                            </td>
                                                            <?php
                                                            foreach ($rank['member'] as $key => $value) {
                                                            ?>
                                                                <td>
                                                                    <?= $value ?>%
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            </p>
                                        </div>
                                <?php
                                        $flag = false;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Loa loa loa...</h5>
                </div>
                <div class="modal-body">
                    <?= $noti_index ?>
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
require("./layout/client/footer.php");
?>

<script>
    var telcoPrices = <?php echo json_encode(list_fee_exchange()); ?>;

    // ==================== HIỂN THỊ THÔNG BÁO ====================
    $(document).ready(function() {
        $('#myModal').modal('show');
    });
    $(document).ready(function() {
        $('.btn-secondary').click(function() {
            $('#myModal').modal('hide');
        });
    });

    // ==================== LẤY MỆNH GIÁ DỰA TRÊN LOẠI THẺ ====================
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

    function updateAmountOptions(parentRow) {
        var telco = parentRow.find(".telco").val();
        var amounts = Object.keys(telcoPrices[telco]["member"]);
        parentRow.find(".amount").empty().append('<option selected disabled value="">Mệnh giá</option>');
        amounts.forEach(function(amount) {
            parentRow.find(".amount").append('<option value="' + amount + '">' + formatMoney(amount, 0, ',', '.') + ' đ</option>');
        });
    }


    $(document).ready(function() {
        // Sự kiện khi thay đổi nhà mạng
        $(document).on("change", ".telco", function() {
            var parentRow = $(this).closest('.form_card');
            updateAmountOptions(parentRow);
        });

        // Gọi hàm cập nhật tùy chọn mệnh giá khi trang được tải
        $(".telco").each(function() {
            var parentRow = $(this).closest('.form_card');
            updateAmountOptions(parentRow);
        });
    });


    // ==================== TÍNH TỔNG THỰC NHẬN ====================
    // Hàm tính tổng thực nhận dựa trên loại thẻ, mệnh giá và rank
    function calculateTotal() {
        var total = 0;
        $(".form_card").each(function() {
            var telco = $(this).find(".telco").val();
            var amount = $(this).find(".amount").val();
            var rank = "<?php
                        if (isset($_COOKIE['token'])) {
                            echo getUserRank_Token($_COOKIE['token']);
                        } else {
                            echo 'member';
                        }
                        ?>";
            var price = telcoPrices[telco][rank][amount] || 0;
            var totalPrice = amount - (amount * price / 100);
            total += totalPrice;
        });
        return total;
    }

    // Hàm hiển thị tổng thực nhận trên giao diện
    function displayTotal(total) {
        $("#order_total").text(formatMoney(total, 0, ',', '.') + 'đ');
    }

    // Gọi hàm tính toán và hiển thị tổng thực nhận khi trang web được tải
    $(document).ready(function() {
        calculateAndDisplayTotal(); // Tính và hiển thị tổng ban đầu khi trang được tải

        // Gọi hàm tính toán và hiển thị tổng thực nhận khi người dùng thay đổi lựa chọn
        $(document).on("change", ".telco, .amount", function() {
            calculateAndDisplayTotal();
        });

        // Gọi hàm tính toán và hiển thị tổng thực nhận khi thêm hoặc xóa hàng
        $(document).on("click", ".btn-action", function() {
            calculateAndDisplayTotal();
        });
    });

    // Hàm tính toán và hiển thị tổng thực nhận
    function calculateAndDisplayTotal() {
        var total = calculateTotal();
        displayTotal(total);
    }

    // ==================== GỬI ĐỔI THẺ ====================
    function sendAjaxRequest(url, data, successCallback, errorCallback) {
        showLoading();
        $('#exchangeCard').prop('disabled', true).text('Đang xử lý...');
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
                $('#exchangeCard').prop('disabled', false).text('Đổi thẻ');
            }
        });
    }

    $('#exchangeCard').click(function() {
        Swal.fire({
            title: "",
            text: "Hãy Kiểm Tra Thẻ Kỹ Trước Khi Gửi Để Tránh Mất Tiền Bạn Nhé!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Vâng, tôi đã kiểm tra!"
        }).then((result) => {
            if (result.isConfirmed) {
                var data = [];
                $('#cardData .row').each(function() {
                    var row = {};
                    $(this).find('input, select').each(function() {
                        row[$(this).attr('name')] = $(this).val();
                    });
                    data.push(row);
                });

                sendAjaxRequest(
                    './ajaxs/main/client/exchangeCard.php', {
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
                                    window.location.href = './historyExCard';
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

    // ==================== XÓA KÝ TỰ DƯ THỪA TRONG INPUT ====================
    document.body.addEventListener('input', function(event) {
        // Kiểm tra xem sự kiện có được kích hoạt từ một phần tử input hay không
        if (event.target.tagName.toLowerCase() === 'input') {
            // Loại bỏ các ký tự không phải là số hoặc chữ cái
            event.target.value = event.target.value.replace(/[^a-zA-Z0-9]/g, '');
        }
    });
</script>