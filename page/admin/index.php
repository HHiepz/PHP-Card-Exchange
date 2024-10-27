<?php
require('../../core/database.php');
require('../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Thêm hàm mới để lấy dữ liệu lãi
function getProfitDataLast30Days()
{
    $data = [];
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-29 days'));

    for ($i = 0; $i < 30; $i++) {
        $currentDate = date('Y-m-d', strtotime($startDate . " +$i days"));
        $nextDate = date('Y-m-d', strtotime($startDate . " +" . ($i + 1) . " days"));

        $profit = pdo_query_value(
            "SELECT SUM(`card-data_profit`) FROM `card-data` WHERE `card-data_created_at` >= ? AND `card-data_created_at` < ? AND `card-data_status` = 'success'",
            [$currentDate, $nextDate]
        );

        $data[] = [
            'date' => date('d/m', strtotime($currentDate)),
            'profit' => floatval($profit) // Chuyển đổi sang số thực để xử lý các giá trị thập phân
        ];
    }
    return $data;
}

// Hàm lấy dữ liệu thẻ đổi 30 ngày gần nhất
function getCardExchangeDataLast30Days()
{
    $data = [];
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-29 days'));

    for ($i = 0; $i < 30; $i++) {
        $currentDate = date('Y-m-d', strtotime($startDate . " +$i days"));
        $nextDate = date('Y-m-d', strtotime($startDate . " +" . ($i + 1) . " days"));

        $total = pdo_query_value(
            "SELECT COUNT(*) FROM `card-data` WHERE `card-data_created_at` >= ? AND `card-data_created_at` < ?",
            [$currentDate, $nextDate]
        );

        $success = pdo_query_value(
            "SELECT COUNT(*) FROM `card-data` WHERE `card-data_created_at` >= ? AND `card-data_created_at` < ? AND `card-data_status` = 'success'",
            [$currentDate, $nextDate]
        );

        $fail = pdo_query_value(
            "SELECT COUNT(*) FROM `card-data` WHERE `card-data_created_at` >= ? AND `card-data_created_at` < ? AND `card-data_status` = 'fail'",
            [$currentDate, $nextDate]
        );

        $wrong_amount = pdo_query_value(
            "SELECT COUNT(*) FROM `card-data` WHERE `card-data_created_at` >= ? AND `card-data_created_at` < ? AND `card-data_status` = 'wrong_amount'",
            [$currentDate, $nextDate]
        );

        $data[] = [
            'date' => date('d/m', strtotime($currentDate)),
            'total' => intval($total),
            'success' => intval($success),
            'fail' => intval($fail),
            'wrong_amount' => intval($wrong_amount)
        ];
    }
    return $data;
}


// Hàm lấy dữ liệu rút tiền 30 ngày gần nhất
function getWithdrawDataLast30Days()
{
    $data = [];
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-29 days'));

    for ($i = 0; $i < 30; $i++) {
        $currentDate = date('Y-m-d', strtotime($startDate . " +$i days"));
        $nextDate = date('Y-m-d', strtotime($startDate . " +" . ($i + 1) . " days"));

        $total = pdo_query_value(
            "SELECT COUNT(*) FROM `withdraw` WHERE `created_at` >= ? AND `created_at` < ?",
            [$currentDate, $nextDate]
        );

        $success = pdo_query_value(
            "SELECT COUNT(*) FROM `withdraw` WHERE `created_at` >= ? AND `created_at` < ? AND `wd_status` = 'success'",
            [$currentDate, $nextDate]
        );

        $fail = pdo_query_value(
            "SELECT COUNT(*) FROM `withdraw` WHERE `created_at` >= ? AND `created_at` < ? AND `wd_status` = 'fail'",
            [$currentDate, $nextDate]
        );

        $data[] = [
            'date' => date('d/m', strtotime($currentDate)),
            'total' => intval($total),
            'success' => intval($success),
            'fail' => intval($fail)
        ];
    }
    return $data;
}


// Hàm lấy tổng lãi từ thẻ cào
function getTotalAmountProfitCard($weeks, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $currentDate = new DateTime();

    if ($type === 'd') {
        $startDate = $currentDate->modify("-$weeks days")->format('Y-m-d');
    } elseif ($type === 'w') {
        $startDate = $currentDate->modify('last monday')->modify("-$weeks week")->format('Y-m-d');
    } else {
        $startDate = $currentDate->format('Y-m-01'); // First day of current month
    }

    $endDate = $currentDate->format('Y-m-t'); // Last day of current month

    return pdo_query_value("SELECT SUM(`card-data_profit`) AS total_profit_today FROM `card-data` WHERE DATE(`card-data_created_at`) BETWEEN ? AND ? AND `card-data_status` = 'success'", [$startDate, $endDate]);
}

// Hàm lấy tổng tiền thẻ gửi 
function getTotalAmountCard($weeks, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    if ($type === 'd') {
        $date = date('Y-m-d', strtotime("-$weeks days"));
    } elseif ($type === 'w') {
        $startOfWeek = strtotime("last monday midnight");
        $date = date('Y-m-d', strtotime("-$weeks week", $startOfWeek));
    } else {
        $date = date('Y-m-01', strtotime("-$weeks months"));
    }
    return pdo_query_value("SELECT SUM(`card-data_amount`) AS total_amount_today FROM `card-data` WHERE DATE(`card-data_created_at`) >= ? AND `card-data_status` = 'success'", [$date]);
}

// Hàm lấy tổng thực nhận thẻ gửi
function getTotalAmountRecieveCard($weeks, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    if ($type === 'd') {
        $date = date('Y-m-d', strtotime("-$weeks days"));
    } elseif ($type === 'w') {
        $startOfWeek = strtotime("last monday midnight");
        $date = date('Y-m-d', strtotime("-$weeks week", $startOfWeek));
    } else {
        $date = date('Y-m-01', strtotime("-$weeks months"));
    }
    return pdo_query_value("SELECT SUM(`card-data_amount_recieve`) AS total_amount_recieve_today FROM `card-data` WHERE DATE(`card-data_created_at`) >= ? AND `card-data_status` = 'success'", [$date]);
}

// Hàm lấy tổng rút tiền
function getTotalAmountWithdraw($weeks, $type)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    if ($type === 'd') {
        $date = date('Y-m-d', strtotime("-$weeks days"));
    } elseif ($type === 'w') {
        $startOfWeek = strtotime("last monday midnight");
        $date = date('Y-m-d', strtotime("-$weeks week", $startOfWeek));
    } else {
        $date = date('Y-m-01', strtotime("-$weeks months"));
    }
    return pdo_query_value("SELECT SUM(`wd_cash`) AS total_amount_withdraw_today FROM `withdraw` WHERE DATE(`created_at`) >= ? AND `wd_status` = 'success'", [$date]);
}

// ===============================
$profit_today = formatNumber(getTotalAmountProfitCard(0, 'd')); // Lãi hôm nay
$profit_month = formatNumber(getTotalAmountProfitCard(1, 'm')); // Lãi tháng này

$cardExchangeData = getCardExchangeDataLast30Days(); // Lấy dữ liệu thẻ đổi 30 ngày gần nhất
$cardExchangeDataJSON = json_encode($cardExchangeData); // Chuyển dữ liệu thành JSON

$withdrawData = getWithdrawDataLast30Days(); // Lấy dữ liệu rút tiền 30 ngày gần nhất
$withdrawDataJSON = json_encode($withdrawData); // Chuyển dữ liệu thành JSON

$profitData = getProfitDataLast30Days(); // Lấy dữ liệu lãi 30 ngày gần nhất
$profitDataJSON = json_encode($profitData); // Chuyển dữ liệu thành JSON


// Tổng số thành viên và tổng số dư thành viên
$user_query = pdo_query("SELECT COUNT(`user_id`) AS total_member, SUM(`user_cash`) AS total_cash_member FROM `user`");
$total_member = formatNumber($user_query[0]['total_member']);
$total_cash_member = formatNumber($user_query[0]['total_cash_member']);

// Tổng đối tác API
$total_partner = formatNumber(pdo_query_value("SELECT COUNT(`partner_id`) AS total_partner FROM `partner`"));

// Thống kê thẻ đổi
$card_data_query = pdo_query("SELECT 
    COUNT(*) AS total_cardEx_change,
    SUM(CASE WHEN `card-data_status` = 'success' THEN 1 ELSE 0 END) AS total_cardEx_success,
    SUM(CASE WHEN `card-data_status` = 'fail' THEN 1 ELSE 0 END) AS total_cardEx_fail,
    SUM(CASE WHEN `card-data_status` = 'wrong_amount' THEN 1 ELSE 0 END) AS total_cardEx_wrong,
    SUM(CASE WHEN `card-data_status` = 'wait' THEN 1 ELSE 0 END) AS total_cardEx_wait,
    SUM(CASE WHEN `card-data_status` = 'wait' THEN `card-data_amount` ELSE 0 END) AS total_cardEx_wait_cash,
    SUM(CASE WHEN `card-data_status` != 'fail' THEN `card-data_amount_recieve` ELSE 0 END) AS total_cardEx_amount_recieve,
    SUM(CASE WHEN `card-data_status` != 'fail' THEN `card-data_amount_real` ELSE 0 END) AS total_cardEx_amount_real
FROM `card-data`");

$total_cardEx_change = formatNumber($card_data_query[0]['total_cardEx_change']);
$total_cardEx_success = formatNumber($card_data_query[0]['total_cardEx_success']);
$total_cardEx_fail = formatNumber($card_data_query[0]['total_cardEx_fail']);
$total_cardEx_wrong = formatNumber($card_data_query[0]['total_cardEx_wrong']);
$total_cardEx_wait = formatNumber($card_data_query[0]['total_cardEx_wait']);
$total_cardEx_wait_cash = formatNumber($card_data_query[0]['total_cardEx_wait_cash']);
$total_cardEx_amount_recieve = formatNumber($card_data_query[0]['total_cardEx_amount_recieve']);
$total_cardEx_amount_real = formatNumber($card_data_query[0]['total_cardEx_amount_real']);

// Thống kê thẻ mua chờ
$buy_card_query = pdo_query("SELECT 
    COUNT(*) AS total_buyCard_wait,
    SUM(`buy-card-order_total_pay`) AS total_buyCard_wait_cash
FROM `buy-card-order` 
WHERE `buy-card-order_status` = 'wait'");

$total_buyCard_wait = formatNumber($buy_card_query[0]['total_buyCard_wait']);
$total_buyCard_wait_cash = formatNumber($buy_card_query[0]['total_buyCard_wait_cash']);

// Thống kê rút thẻ chờ
$withdraw_query = pdo_query("SELECT 
    COUNT(*) AS total_withdraw_wait,
    SUM(`wd_cash`) AS total_withdraw_wait_cash
FROM `withdraw` 
WHERE `wd_status` IN ('wait', 'pending', 'hold')");

$total_withdraw_wait = formatNumber($withdraw_query[0]['total_withdraw_wait']);
$total_withdraw_wait_cash = formatNumber($withdraw_query[0]['total_withdraw_wait_cash']);

$total_cardEx_amount_today          = formatNumber(getTotalAmountCard(0, 'd'));        // Tổng tiền thẻ gửi hôm nay
$total_cardEx_amount_recieve_today  = formatNumber(getTotalAmountRecieveCard(0, 'd')); // Tổng thực nhận hôm nay
$total_cardEx_amount_withdraw_today = formatNumber(getTotalAmountWithdraw(0, 'd'));    // Tổng rút tiền hôm nay

// Header
$title_website = 'Bảng điều khiển';
require("../../layout/admin/header.php");
?>
<div class="hp-main-layout-content">
    <div class="row mb-32 g-32">
        <div class="col flex-grow-1 overflow-hidden">
            <div class="row g-32">
                <div class="col-12">
                    <h1 class="hp-mb-0">Bảng điều khiển</h1>
                </div>

                <div class="col-12">
                    <div class="row g-32">
                        <div class="col-md-6 col-12">
                            <div class="card hp-dashboard-feature-card hp-border-color-black-0 hp-border-color-dark-80 hp-cursor-pointer">
                                <div class="card-body">
                                    <div class="d-flex mt-12">
                                        <span class="h6 mb-0 d-block hp-text-color-black-bg hp-text-color-dark-0 fw-medium me-4">
                                            Lãi hôm này
                                        </span>
                                    </div>
                                    <span class="d-block mt-12 mb-8 h3"> <?= $profit_today ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="card hp-dashboard-feature-card hp-border-color-black-0 hp-border-color-dark-80 hp-cursor-pointer">
                                <div class="card-body">
                                    <div class="d-flex mt-12">
                                        <span class="h6 mb-0 d-block hp-text-color-black-bg hp-text-color-dark-0 fw-medium me-4">
                                            Lãi tháng này
                                        </span>
                                    </div>
                                    <span class="d-block mt-12 mb-8 h3"> <?= $profit_month ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thẻ đã đổi trong 30 ngày qua -->
                <div class="col-12">
                    <div class="row">
                        <div class="mb-18 col-12">
                            <div class="row align-items-center justify-content-between">
                                <div class="hp-flex-none w-auto col">
                                    <span class="d-block hp-p1-body">Thẻ đã đổi trong 30 ngày qua</span>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden col-12 mb-n24">
                            <div id="total-card-exchange-chart-30-days" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Thẻ đã rút trong 30 ngày qua -->
                <div class="col-12">
                    <div class="row">
                        <div class="mb-18 col-12">
                            <div class="row align-items-center justify-content-between">
                                <div class="hp-flex-none w-auto col">
                                    <span class="d-block hp-p1-body">Thẻ đã rút trong 30 ngày qua</span>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden col-12 mb-n24">
                            <div id="total-card-withdraw-chart-30-days" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê lãi -->
                <div class="col-12">
                    <div class="row">
                        <div class="mb-18 col-12">
                            <div class="row align-items-center justify-content-between">
                                <div class="hp-flex-none w-auto col">
                                    <span class="d-block hp-p1-body">Thống kê lãi</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-hidden col-12 mb-n24">
                            <div id="total-profit-chart-30-days" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="hp-flex-none w-auto hp-dashboard-line px-0 col">
            <div class="hp-bg-black-40 hp-bg-dark-80 h-100 mx-24" style="width: 1px"></div>
        </div>

        <div class="col hp-analytics-col-2">
            <div class="row g-32">
                <div class="col-12">
                    <span class="h3 d-block fw-semibold hp-text-color-black-bg hp-text-color-dark-0 mb-0">
                        Thống kê nhanh
                    </span>
                    <div class="divider"></div>
                    <div class="row mt-24">

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-info" style="color: #1f3251"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Thẻ hôm nay
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_amount_today ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-info" style="color: #1f3251"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Thực nhận hôm nay
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_amount_recieve_today ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-info" style="color: #1f3251"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Rút hôm nay
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_amount_withdraw_today ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #dcad04"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Rút tiền chờ
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_withdraw_wait ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #dcad04"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số tiền
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_withdraw_wait_cash ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-user" style="color: #74c0fc"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Tổng số thành viên
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_member ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-dollar-sign" style="color: #74c0fc"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Tổng số dư thành viên
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cash_member ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-code-compare" style="color: #74c0fc"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Tổng đối tác API
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_partner ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-info" style="color: #1f3251"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số thẻ đổi
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_change ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #4ba086"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số thẻ thành công
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_success ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-x" style="color: #ce0909"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số thẻ sai
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_fail ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-x" style="color: #ce0909"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số thẻ sai mệnh giá
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_wrong ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-info" style="color: #1f3251"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Tổng thực nhận
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_amount_recieve ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-info" style="color: #1f3251"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Tổng tiền thẻ gửi
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_amount_real ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #dcad04"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Gạch thẻ chờ
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_wait ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #dcad04"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số tiền
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_cardEx_wait_cash ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #dcad04"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Mua thẻ chờ
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_buyCard_wait ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="hp-cursor-pointer hp-transition hp-hover-bg-dark-100 hp-hover-bg-black-10 rounded py-8 mb-16 col-12">
                            <div class="row align-items-end justify-content-between">
                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="hp-flex-none w-auto pe-0 col">
                                            <div class="me-16 border hp-border-color-black-10 hp-bg-black-0 rounded-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px">
                                                <i class="fa-solid fa-check" style="color: #dcad04"></i>
                                            </div>
                                        </div>

                                        <div class="hp-flex-none w-auto ps-0 col">
                                            <span class="d-block hp-p1-body fw-medium hp-text-color-black-bg hp-text-color-dark-0">
                                                Số tiền
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-flex-none w-auto col">
                                    <span class="h5 hp-text-color-black-bg hp-text-color-dark-0">
                                        <?= $total_buyCard_wait_cash ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require("../../layout/admin/footer.php");
?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                    name: 'Tổng số thẻ',
                    data: <?php echo json_encode(array_column($cardExchangeData, 'total')); ?>
                },
                {
                    name: 'Thẻ đúng',
                    data: <?php echo json_encode(array_column($cardExchangeData, 'success')); ?>
                },
                {
                    name: 'Thẻ sai',
                    data: <?php echo json_encode(array_column($cardExchangeData, 'fail')); ?>
                },
                {
                    name: 'Thẻ sai mệnh giá',
                    data: <?php echo json_encode(array_column($cardExchangeData, 'wrong_amount')); ?>
                }
            ],
            chart: {
                height: 350,
                type: 'area',
                stacked: false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: <?php echo json_encode(array_column($cardExchangeData, 'date')); ?>
            },
            tooltip: {
                x: {
                    format: 'dd/MM'
                },
            },
            colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560'],
            legend: {
                position: 'top'
            }
        };

        var chart = new ApexCharts(document.querySelector("#total-card-exchange-chart-30-days"), options);
        chart.render();

        var withdrawOptions = {
            series: [{
                    name: 'Tổng số lệnh rút',
                    data: <?php echo json_encode(array_column($withdrawData, 'total')); ?>
                },
                {
                    name: 'Rút thành công',
                    data: <?php echo json_encode(array_column($withdrawData, 'success')); ?>
                },
                {
                    name: 'Rút thất bại',
                    data: <?php echo json_encode(array_column($withdrawData, 'fail')); ?>
                }
            ],
            chart: {
                height: 350,
                type: 'area',
                stacked: false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: <?php echo json_encode(array_column($withdrawData, 'date')); ?>
            },
            tooltip: {
                x: {
                    format: 'dd/MM'
                },
            },
            colors: ['#008FFB', '#00E396', '#FEB019'],
            legend: {
                position: 'top'
            }
        };

        var withdrawChart = new ApexCharts(document.querySelector("#total-card-withdraw-chart-30-days"), withdrawOptions);
        withdrawChart.render();


        var profitOptions = {
            series: [{
                name: 'Lãi',
                data: <?php echo json_encode(array_column($profitData, 'profit')); ?>
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: <?php echo json_encode(array_column($profitData, 'date')); ?>
            },
            tooltip: {
                x: {
                    format: 'dd/MM'
                },
                y: {
                    formatter: function(val) {
                        return val.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " VND";
                    }
                }
            },
            colors: ['#00E396'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            }
        };

        var profitChart = new ApexCharts(document.querySelector("#total-profit-chart-30-days"), profitOptions);
        profitChart.render();

    });
</script>