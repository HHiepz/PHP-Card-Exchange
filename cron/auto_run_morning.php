<?php
require('../core/database.php');
require('../core/function.php');

// Tên miền
$domain = getDomain();

// Truy thu rank hết hạn
$checkDateRank = curlGet("$domain/cronJob/checkDateRankuser");

$checkDateNotiTransfer = curlGet("$domain/cronJob/checkDateNotiTransfer");

// Auto tổng kết
$tongKet = curlGet("$domain/cronJob/tongKet");