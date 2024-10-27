<?php
require('../core/database.php');
require('../core/function.php');

// Tên miền
$domain = getDomain();

// Auto thông báo chiết khấu thấp nhất.
$discordTelco = curlGet("$domain/cronJob/discord/minTelcoRare");

// Auto cập nhật phí nạp thẻ
$updateFeeTopup = curlGet("$domain/cronJob/updateFeeTopup");

