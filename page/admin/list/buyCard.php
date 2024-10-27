<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách thành viên
$list_buyCard = pdo_query("SELECT * FROM `buy-card-order` ORDER BY `buy-card-order_id` DESC");

// Header
$title_website = 'Danh sách mua thẻ cào';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">Lịch sử MUA THẺ</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-buyCard" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Mã đơn</th>
                                            <th class="text-nowrap">Nhà mạng</th>
                                            <th class="text-nowrap">Mệnh giá</th>
                                            <th class="text-nowrap">Số lượng</th>
                                            <th class="text-nowrap">Trạng thái API</th>
                                            <th class="text-nowrap">Message API</th>
                                            <th class="text-nowrap">Data API</th>
                                            <th class="text-nowrap">Order Code API</th>
                                            <th class="text-nowrap">Tổng thanh toán</th>
                                            <th class="text-nowrap">Thời gian</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_buyCard as $buyCard) {
                                            $user_email = getEmailUser($buyCard['user_id']);
                                            $buyCard_status_api  = empty($buyCard['buy-card-order_api_status']) ? ' - ' : $buyCard['buy-card-order_api_status'];
                                            $buyCard_message_api = empty($buyCard['buy-card-order_api_message']) ? ' - ' : $buyCard['buy-card-order_api_message'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_email ?>" class="badge"><?= limitShow($user_email) ?></a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= statusBuyCard($buyCard['buy-card-order_status'], "color") ?>"><?= statusBuyCard($buyCard['buy-card-order_status']) ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $buyCard['buy-card-order_code'] ?></span>
                                                </td>
                                                <td class="text-nowrap"><?= $buyCard['buy-card-order_telco'] ?></td>
                                                <td class="text-nowrap"><?= number_format($buyCard['buy-card-order_price']) ?>đ</td>
                                                <td class="text-nowrap"><?= $buyCard['buy-card-order_quantity'] ?></td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge"><?= $buyCard_status_api ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge"><?= $buyCard_message_api ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <!-- icon copy nội dung -->
                                                    <p class="d-none"><?= $buyCard['buy-card-order_api_data'] ?></p>
                                                    <button type="button" class="btn btn-sm btn-outline-primary copy-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy nội dung">
                                                        <i class="fa-solid fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $buyCard['buy-card-order_order_code'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <?= number_format($buyCard['buy-card-order_total_pay']) ?>
                                                </td>
                                                <td class="text-nowrap"> <?= $buyCard['created_at'] ?> </td>
                                                <td class="text-nowrap">
                                                    <!-- Xem chi tiết -->
                                                    <a href="<?= getDomain() ?>/admin/list/buyCardDetail/<?= $buyCard['buy-card-order_code'] ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <!-- Duyệt thẻ thành công -->
                                                    <button type="button" class="btn btn-sm btn-outline-success buyCard_success" data-buycard-code="<?= $buyCard['buy-card-order_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Duyệt thẻ thành công">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <!-- Hủy không refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning buyCard_cancel_refurn" data-buycard-code="<?= $buyCard['buy-card-order_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hủy Refurn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                    <!-- Hủy refurn -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger buyCard_cancel_non_refurn" data-buycard-code="<?= $buyCard['buy-card-order_code'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hủy KHÔNG Refurn">
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
    new DataTable('#data-table_list-buyCard');

    // Copy nội dung
    $(document).ready(function() {
        $('body').on('click', '.copy-data', function() {
            var copyText = $(this).prev('p').text();

            var textarea = document.createElement("textarea");
            textarea.textContent = copyText;
            textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            try {
                return document.execCommand("copy"); // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        });
    });

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

                    $.post('../../../../ajaxs/admin/action/buyCard.php', {
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
                                    window.location.href = "<?= "/admin/list/buyCard" ?>";
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
    handleButtonClick('.buyCard_success', 'buyCard_success', "Duyệt thẻ thành công!", "buycard_code", "buycard-code");
    handleButtonClick('.buyCard_cancel_refurn', 'buyCard_cancel_refurn', "HỦY THẺ & REFURN!", "buycard_code", "buycard-code");
    handleButtonClick('.buyCard_cancel_non_refurn', 'buyCard_cancel_non_refurn', "HỦY THẺ & KHÔNG REFURN!", "buycard_code", "buycard-code");
    // =====================================
</script>