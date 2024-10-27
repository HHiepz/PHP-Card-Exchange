<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách thành viên
$list_partner = pdo_query("SELECT * FROM `partner`");

// Header
$title_website = 'Danh sách đối tác';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">DANH SÁCH ĐỐI TÁC</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-partner" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Loại</th>
                                            <th class="text-nowrap">Thông tin API</th>
                                            <th class="text-nowrap">Phương thức</th>
                                            <th class="text-nowrap">Trạng thái</th>
                                            <th class="text-nowrap">Ngày tạo</th>
                                            <th class="text-nowrap">Lần cuối</th>
                                            <th class="text-nowrap">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_partner as $partner) {
                                            $user_email = getEmailUser($partner['user_id']);
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_email ?>" class="badge"><?= limitShow($user_email) ?></a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span>Charging</span>
                                                </td>
                                                <td class="text-nowrap">
                                                    Partner ID: <span class="badge"><?= $partner['partner_id'] ?></span><br>
                                                    Partner Key: <span class="badge"><?= $partner['partner_key'] ?></span><br>
                                                    <?php
                                                    if (!empty($partner['partner_callback'])) {
                                                    ?>
                                                        Callback: <span class="badge"><?= $partner['partner_callback'] ?></span>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= ($partner['partner_status'] == 'active') ? 'success' : 'dark' ?>"><?= $partner['partner_action'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= ($partner['partner_status'] == 'active') ? 'success' : 'dark' ?>"><?= $partner['partner_status'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= $partner['created_at'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= $partner['updated_at'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <!-- Xóa Partner -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger partner_delete" data-bs-toggle="tooltip" data-partner-id="<?= $partner['partner_id'] ?>" data-bs-placement="top" title="Xóa Partner">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                    <?php
                                                    if ($partner['partner_status'] == 'active') {
                                                    ?>
                                                        <!-- Hủy kích hoạt -->
                                                        <button type="button" class="btn btn-sm btn-outline-warning partner_cancel" data-bs-toggle="tooltip" data-partner-id="<?= $partner['partner_id'] ?>" data-bs-placement="top" title="Hủy kích hoạt">
                                                            <i class="fa-solid fa-ban"></i>
                                                        </button>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <!-- Kích hoạt -->
                                                        <button type="button" class="btn btn-sm btn-outline-success partner_active" data-bs-toggle="tooltip" data-partner-id="<?= $partner['partner_id'] ?>" data-bs-placement="top" title="Kích hoạt">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>
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
    new DataTable('#data-table_list-partner');


    function handleButtonClick(buttonClass, action, message, keyGet, valueKeyGet) {
        $(buttonClass).click(function() {
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

                    $.post('../../../ajaxs/admin/action/partner.php', {
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
                                    window.location.href = "<?= "/admin/list/partner" ?>";
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

    // =================== 
    handleButtonClick('.partner_delete', 'partner_delete', 'Bạn có chắc chắn muốn xóa đối tác này?', 'partner_id', 'partner-id');
    handleButtonClick('.partner_cancel', 'partner_cancel', 'Bạn có chắc chắn muốn hủy kích hoạt đối tác này?', 'partner_id', 'partner-id');
    handleButtonClick('.partner_active', 'partner_active', 'Bạn có chắc chắn muốn kích hoạt đối tác này?', 'partner_id', 'partner-id');
    // =================== 
</script>