<?php
require('../core/database.php');
require('../core/function.php');

// Tên miền
$domain = getDomain();

// Xóa lịch sử quá 2 tháng
$delteLog = curlGet("$domain/cronJob/deleteLog");

