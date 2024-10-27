<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách thành viên
$list_rank = pdo_query("SELECT * FROM `rank`");

// Header
$title_website = 'Lịch sử rank';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">LỊCH SỬ GIA HẠN RANK</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-rank" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Trước</th>
                                            <th class="text-nowrap">Sau</th>
                                            <th class="text-nowrap">Nội dung</th>
                                            <th class="text-nowrap">Người thay đổi</th>
                                            <th class="text-nowrap">Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_rank as $rank) {
                                            $user_email = getEmailUser($rank['user_id']);
                                            $user_change = ($rank['rank_user_change'] == -1) ? 'Hệ thống' : getEmailUser($rank['rank_user_change']);
                                            $color_user_change = ($rank['rank_user_change'] == -1) ? '' : 'text-danger';
                                        ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $user_email ?>" class="badge"><?= $user_email ?></a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= formatRank($rank['rank_before'])['color'] ?>"><?= formatRank($rank['rank_before'])['name'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                <span class="badge badge-<?= formatRank($rank['rank_after'])['color'] ?>"><?= formatRank($rank['rank_after'])['name'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= $rank['rank_note'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge <?= $color_user_change ?>"><?= $user_change ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span><?= $rank['created_at'] ?></span>
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
    new DataTable('#data-table_list-rank');
</script>