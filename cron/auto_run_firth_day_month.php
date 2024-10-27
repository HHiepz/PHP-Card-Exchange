<?php
require('../core/database.php');
require('../core/function.php');

// Tên miền
$domain = getDomain();

// Withdraw profit
$withdrawProfit = curlGet($domain . "/cronJob/withdrawProfit");

