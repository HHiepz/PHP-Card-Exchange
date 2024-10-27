<?php
require('../core/database.php');
require('../core/function.php');

// Tên miền
$domain = getDomain();

// Auto cập nhật giá đổi thẻ
$doithe = curlGet("$domain/cronJob/updateFeeExchangeCard");

// Auto cập nhật giá mua thẻ
$muathe = curlGet("$domain/cronJob/updateFeeBuyCard");

// Auto rút tiền
$ruttien = curlGet("$domain/cronJob/withdraw");

// Auto kiểm tra thẻ trạng thái chờ
$kiemTraTheCho = curlGet("$domain/cronJob/checkCardPending");

// Auto gữi email chờ
$emailPending = curlGet("$domain/cronJob/sendMail");

// Auto đơn nạp thẻ
$orderTopup = curlGet("$domain/cronJob/orderTopup");