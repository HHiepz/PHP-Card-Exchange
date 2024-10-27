<?php
require('../../core/database.php');
require('../../core/function.php');
require('../../core/apiSend.php');

// Fetch settings
$settingNames = ['partner_server_name_buyCard', 'partner_id_buyCard', 'partner_key_buyCard', 'wallet_exCard', 'webhook_withdraw_profit', 'role_withdraw_profit'];
$placeholders = implode(',', array_fill(0, count($settingNames), '?'));
$dataSQL = pdo_query("SELECT `name`, `value` FROM `setting` WHERE `name` IN ($placeholders)", $settingNames);

// Convert to associative array
$settings = array_column($dataSQL, 'value', 'name');

// Check if all required settings are present
$requiredSettings = ['partner_server_name_buyCard', 'partner_id_buyCard', 'partner_key_buyCard', 'wallet_exCard', 'role_withdraw_profit'];
foreach ($requiredSettings as $setting) {
    if (empty($settings[$setting])) {
        die("Error: Missing required setting: $setting");
    }
}

// Get current balance
$balanceResponse = getBalance(
    $settings['partner_server_name_buyCard'],
    $settings['partner_id_buyCard'],
    $settings['partner_key_buyCard'],
    $settings['wallet_exCard']
);

if (!isset($balanceResponse['balance'])) {
    $message = "Error: Unable to fetch balance";
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitErrorMessage($settings['role_withdraw_profit'], $message));
    }
    die("Error: Unable to fetch balance");
}

$currentBalance = $balanceResponse['balance'];

// Get total user balance
$totalUserBalance = pdo_query_value("SELECT SUM(`user_cash`) FROM `user` WHERE `user_cash` > 0");

// Check if total user balance exceeds current balance
if ($totalUserBalance > $currentBalance) {
    $message = "Withdrawal failed: Total user balance **(" . number_format($totalUserBalance) . ")** exceeds current balance **(" . number_format($currentBalance) . ")**";

    // Call webhook with failure message
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitErrorMessage($settings['role_withdraw_profit'], $message));
    }
    die("Withdrawal failed: Total user balance **($totalUserBalance)** exceeds current balance **($currentBalance)**");
}

// Calculate profit
$profit = $currentBalance - $totalUserBalance;

if ($profit <= 0) {
    $message = "No profit to withdraw";
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitErrorMessage($settings['role_withdraw_profit'], $message));
    }
    die("No profit to withdraw");
}

if ($profit < 10000) {
    $message = "Withdrawal failed: Profit is less than 10,000 VND";
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitErrorMessage($settings['role_withdraw_profit'], $message));
    }
    die("Withdrawal failed: Profit is less than 10,000 VND");
}

$bank_code            = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'bank_code_withdraw_profit'");
$account_number       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'account_number_withdraw_profit'");
$account_owner        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'account_owner_withdraw_profit'");
$withdraw_description = 'Profit withdrawal ' . rand_string();

// Check if any of the variables are empty
$empty_fields = [];
if (empty($bank_code)) $empty_fields[] = 'bank_code';
if (empty($account_number)) $empty_fields[] = 'account_number';
if (empty($account_owner)) $empty_fields[] = 'account_owner';

if (!empty($empty_fields)) {
    $error_message = "Error: Missing required fields for withdrawal: " . implode(', ', $empty_fields);
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitErrorMessage($settings['role_withdraw_profit'], $error_message));
    }
    die($error_message);
}

$bank_code            = showCodeBank($bank_code); // Mã ngân hàng (do bên nhà cung cấp API cung cấp)

// Perform withdrawal
$withdrawResponse = sendWithdraw(
    $bank_code,
    $account_number,
    $account_owner,
    $profit,
    $withdraw_description
);

// Check withdrawal response
if (isset($withdrawResponse['status']) && $withdrawResponse['status'] == 1) {

    // Ghi log giao dịch
    $user_id = 180; // Tài khoản admin
    $note = "[AUTO] Rút lợi nhuận mã giao dịch: " . $withdraw_description;
    userCash('add', $profit, $user_id, $note); // Cộng tiền
    userCash('sub', $profit, $user_id, $note); // Trừ tiền

    $message = "Withdrawal successful. Amount: " . number_format($profit);

    // Call webhook with success message
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitSuccessMessage($settings['role_withdraw_profit'], $message));
    }
    die("Withdrawal successful. Amount: " . number_format($profit));
} else {
    $message = "Withdrawal failed. Response: " . print_r($withdrawResponse, true);

    // Call webhook with failure message
    if (!empty($settings['webhook_withdraw_profit'])) {
        sendDiscord($settings['webhook_withdraw_profit'], createWithdrawProfitErrorMessage($settings['role_withdraw_profit'], $message));
    }
    die("Withdrawal failed. Response: " . print_r($withdrawResponse, true));
}
