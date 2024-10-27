<?php
// NOTE DEV: Tự động trả về rank thành viên khi hết hạn
require('../../core/function.php');
require('../../core/database.php');

$listUser = pdo_query("SELECT * FROM `user` WHERE `user_rank_expire` != 0");

foreach ($listUser as $user) {
    // Nếu thời gian hiện tại lớn hơn thời gian hết hạn rank
    if (time() > $user['user_rank_expire']) {
        $oldRankTime = $user['user_backup_rank_date'];

        // Nếu rank là Đại Lý thì hạ xuống rank VIP
        if ($user['user_rank'] == 'agency') {
            if ($oldRankTime == 0) {
                userRank('vip', -1, $user['user_id'], '[AUTO] Hết hạn rank');
                continue;
            }
            if (time() < $oldRankTime) {
                $daysRemaining = ceil(($oldRankTime - time()) / 86400);
                userRank('vip', $daysRemaining, $user['user_id'], '[AUTO] Hết hạn rank');
                continue;
            }
        }

        // Thực hiện hạ rank xuống thành viên
        userRank('member', -1, $user['user_id'], '[AUTO] Hết hạn rank');
    }
}

jsonReturn(true, 'Truy thu thời hạn rank thành viên thành công');
