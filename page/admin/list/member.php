<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Danh sách thành viên
$list_member = pdo_query("SELECT * FROM `user`");

// Header
$title_website = 'Danh sách thành viên';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="block-header block-header-default">
                            <h3 class="block-title text-left">DANH SÁCH THÀNH VIÊN</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">

                                <table id="data-table_list-member" class="table table-striped table-hover table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-nowrap">ID</th>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Thực nhận</th>
                                            <th class="text-nowrap">Số dư</th>
                                            <th class="text-nowrap">Số điện thoại</th>
                                            <th class="text-nowrap">Rank</th>
                                            <th class="text-nowrap">Khóa</th>
                                            <th class="text-nowrap">Cảnh cáo</th>
                                            <th class="text-nowrap">Lần cuối</th>
                                            <th class="text-nowrap">Mã mời</th>
                                            <th class="text-nowrap">Mời bởi</th>
                                            <th class="text-nowrap">Hành động</th>
                                            <th class="text-nowrap">Địa chỉ IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list_member as $member) {
                                        ?>
                                            <tr>
                                                <td class="text-nowrap"> <?= $member['user_id'] ?> </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $member['user_email'] ?></span>
                                                </td>
                                                <td class="text-nowrap"> 0 </td>
                                                <td class="text-nowrap"> <?= number_format($member['user_cash']) ?> </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $member['user_phone'] ?></span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge badge-<?= formatRank($member['user_rank'])['color'] ?>"><?= formatRank($member['user_rank'])['name'] ?></span>
                                                </td>
                                                <td class="text-nowrap"> <?= ($member['user_banned'] == 0) ? ' - ' : ' Có ' ?> </td>
                                                <td class="text-nowrap"> <?= ($member['user_warning'] == 0) ? ' - ' : $member['user_warning'] ?> </td>
                                                <td class="text-nowrap"> <?= $member['updated_at'] ?> </td>
                                                <td class="text-nowrap"> <span class="badge"><?= $member['user_invite_code'] ?></span> </td>
                                                <td class="text-nowrap"> <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= getEmailByInviteCode($member['user_invite_by']) ?>" class="badge"><?= $member['user_invite_by'] ?></span> </td>
                                                <td>
                                                    <a href="<?= getDomain() ?>/admin/list/memberDetail/<?= $member['user_email'] ?>" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <span class="badge"><?= $member['user_ip'] ?></span>
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
    new DataTable('#data-table_list-member');
</script>