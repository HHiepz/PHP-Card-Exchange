<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách thành viên
$list_transfer = pdo_query("SELECT * FROM `transfer`");

// Header
$title_website = 'Lịch sử chuyển tiền nội bộ';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">DANH SÁCH CHUYỂN TIỀN NỘI BỘ</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-transfer" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Mã GD</th>
                                            <th class="text-nowrap">Người chuyển</th>
                                            <th class="text-nowrap">Số tiền</th>
                                            <th class="text-nowrap">Người thụ hưởng</th>
                                            <th class="text-nowrap">Nội dung</th>
                                            <th class="text-nowrap">Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_transfer as $transfer) {
                                            $user_from = getEmailUser($transfer['transfer_user_from']);
                                            $user_to   = getEmailUser($transfer['transfer_user_to']);
                                            $description = empty($transfer['transfer_description']) ? '-' : $transfer['transfer_description'];
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $transfer['transfer_code'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_from ?>" class="badge"><?= $user_from ?></a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= number_format($transfer['transfer_cash']) ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_to ?>" class="badge"><?= $user_to ?></a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= $description ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= $transfer['created_at'] ?></span>
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
    new DataTable('#data-table_list-transfer');
</script>