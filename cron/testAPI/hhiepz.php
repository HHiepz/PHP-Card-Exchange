<?php
require('../../core/database.php');
require('../../core/function.php');
require('../../core/apiSend.php');

// $partner_id          = '37888662239';                       // API_ID
// $partner_key         = 'f1735cabe2b91d7b8823956d50aed359';  // API_KEY 
// $partner_server_name = 'doithecao.vn';                      // Nhà cung cấp
// // $request_id          = rand_string();                       // Random ngẫu nhiên 11 ký tự


// // // Đặt đơn hàng
// // $service_code = 'vietteltt';    // VIETTEL TRẢ TRƯỚC 
// // $amount       = 20000;          // Mệnh giá
// // $quantity     = 1;              // Số lượng
// // $account_info = '0389802966';   // Số điện thoại nạp

// // $order = orderTopup(
// //     $partner_id, 
// //     $partner_key, 
// //     $partner_server_name, 
// //     $service_code, 
// //     $amount, 
// //     $quantity, 
// //     $request_id, 
// //     $account_info
// // );

// // echo "<pre>";
// // print_r($order);
// // echo "</pre>";


// // Kiểm tra trạng thái đơn hàng
// $order_code          = 'R66DEBA077A4E4'; // Mã đơn hàng cần kiểm tra
// $request_id          = 'TPA3D756F1DCC';
// $result = checkStatusTopup($partner_id, $partner_key, $partner_server_name, $order_code, $request_id);

// echo "<pre>";
// print_r($result);
// echo "Request details:\n";
// echo "URL: http://" . $partner_server_name . "/api/rechargews\n";
// echo "Data sent: " . json_encode([
//     'partner_id' => $partner_id,
//     'command' => 'getstatus',
//     'request_id' => $request_id,
//     'order_code' => $order_code,
//     'sign' => md5($partner_key . $partner_id . 'getstatus' . $request_id)
// ]) . "\n";
// echo "</pre>";







