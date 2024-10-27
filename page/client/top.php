<?php
require('../../core/database.php');
require('../../core/function.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');
$top10 = pdo_query("SELECT * FROM `top` WHERE `month` = ? ORDER BY `top_cash` DESC, `top_id` ASC LIMIT 10", [date('m')]);

// Header
$title_website = 'Top 10 thành viên nạp tháng 02';
require('../../layout/client/header.php');
?>


<div class="hp-main-layout-content">
    <div class="col-12 mb-32">
        <div class="row g-32">
            <div class="col-12 col-md-3"></div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <span class="h3 d-block mb-8 text-center">Top 10 thành viên nạp tháng <?= date('m') ?></span>
                        <div class="divider mt-18 mb-16"></div>

                        <div class="block-content" bis_skin_checked="1">
                            <div class="table-responsive" bis_skin_checked="1">
                                <table class="table table-striped table-hover table-borderless table-vcenter fs-sm text-center">
                                    <thead>
                                        <tr>
                                            <th>THỨ HẠNG</th>
                                            <th>NGƯỜI DÙNG</th>
                                            <th>TỔNG TIỀN</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-weight: bold">

                                        <?php
                                        $rank = 1;
                                        foreach ($top10 as $member) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if ($rank == 1) {
                                                        echo '<i class="fas fa-crown fs-2 text-danger"></i>';
                                                    } else if ($rank == 2 || $rank == 3) {
                                                        echo '<i class="fas fa-crown fs-4 text-warning"></i>';
                                                    } else {
                                                        echo $rank;
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-danger"><?= maskEmail(getEmailUser($member['user_id']), 5) ?></td>
                                                <td class=""><span class="fw-bold fs-5"><?= number_format($member['top_cash']) ?>đ</span></td>
                                            </tr>
                                        <?php
                                            $rank++;
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
// Footer
require('../../layout/client/footer.php');
?>