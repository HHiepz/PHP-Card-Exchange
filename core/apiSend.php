<?php
// KIỂM TRA TRẠNG THÁI THẺ ĐÃ GỬI
function checkCardPending($telco, $code, $serial, $amount, $request_id)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác
    $partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id'");          // ID đối tác
    $partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key'");         // Key đối tác

    $dataPost = [
        'telco'      => $telco,
        'code'       => $code,
        'serial'     => $serial,
        'amount'     => $amount,
        'request_id' => $request_id,
        'partner_id' => $partner_id,
        'sign'       => md5($partner_key . $code . $serial),
        'command'    => 'check'
    ];

    $url = "http://" . $partner_server_name . "/chargingws/v2?" . http_build_query($dataPost);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


// GỬI ĐƠN RÚT TIỀN WEB MẸ
function sendWithdraw($bank_code, $number_account, $owner_account, $cash, $description)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác
    $partner_key_bank = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_withdraw'"); // Key đối tác

    $dataPost = [
        'api_key'                 => $partner_key_bank,
        'currency_code'           => 'VND',
        'bank_code'               => $bank_code,
        'receive_account_number'  => $number_account,
        'receive_account_name'    => $owner_account,
        'amount'                  => $cash,
        'description'             => $description
    ];

    $url = "https://" . $partner_server_name . "/api/client/withdraw";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


// KIỂM TRA TRẠNG THÁI RÚT TIỀN
function checkWithdraw($order_code)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác
    $partner_key_bank = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_withdraw'"); // Key đối tác

    $dataPost = [
        'api_key'    => $partner_key_bank,
        'order_code' => $order_code
    ];

    $url = "https://" . $partner_server_name . "/api/client/get_order";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


// KIỂM TRA DANH SÁCH NHÀ BANK HỖ TRỢ
function checkListBanking()
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác
    $partner_key_bank = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_withdraw'"); // Key đối tác
    
    $dataPost = [
        'api_key' => $partner_key_bank
    ];

    $url = "http://" . $partner_server_name . "/api/client/withdraw_banks";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


// GỬI THẺ LÊN WEB MẸ
// ============================
// {
//     "trans_id": 8,
//     "request_id": "323233",
//     "status": 99,
//     "message": "PENDING",
//     "telco": "VIETTEL",
//     "code": "312821445892982",
//     "serial": "10004783347874",
//     "declared_value": 50000,
//     "value": null,
//     "amount": 35000
//   }
// ============================
function sendCard($telco, $code, $serial, $amount, $request_id)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác
    $partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id'");          // ID đối tác
    $partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key'");         // Key đối tác

    $dataPost = [
        'telco'      => $telco,
        'code'       => $code,
        'serial'     => $serial,
        'amount'     => $amount,
        'request_id' => $request_id,
        'partner_id' => $partner_id,
        'sign'       => md5($partner_key . $code . $serial),
        'command'    => 'charging'
    ];

    $url = "http://" . $partner_server_name . "/chargingws/v2?" . http_build_query($dataPost);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


// KIỂM TRA TRẠNG THÁI THẺ (chưa dùng)
// ============================
// {
//     "trans_id": 8,
//     "request_id": "323233",
//     "status": 99,
//     "message": "PENDING",
//     "telco": "VIETTEL",
//     "code": "312821445892982",
//     "serial": "10004783347874",
//     "declared_value": 50000,
//     "value": null,
//     "amount": 35000
//   }
// ============================
function checkCard($telco, $code, $serial, $amount, $request_id)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'"); // Tên server đối tác
    $partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id'");          // ID đối tác
    $partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key'");         // Key đối tác

    $dataPost = [
        'telco'      => $telco,
        'code'       => $code,
        'serial'     => $serial,
        'amount'     => $amount,
        'request_id' => $request_id,
        'partner_id' => $partner_id,
        'sign'       => md5($partner_key . $code . $serial),
        'command'    => 'check'
    ];

    $url = "http://" . $partner_server_name . "/chargingws/v2?" . http_build_query($dataPost);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


// MUA THẺ CÀO
// ============================
// {
// "status": 1,
// "message": "Mua the thanh cong",
// "data": {
//     "cards": [{
//         "name": "Viettel 10.000\u0111",
//         "serial": "20000257049237",
//         "code": "421399095175232",
//         "expired": ""
//     }],
//     "time": "2024-03-02 02:18:14",
//     "request_id": "CARD2K42137562",
//     "order_code": "S65E229F34AADE"
// }
// ============================
// {
// "status": 114,
// "message": "Merchant sai IP dang ky",
// "data": false
// }
// ============================
function buyCard($telco, $amount, $quantity, $request_id)
{
    $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_buyCard'"); // Tên server đối tác
    $partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_buyCard'");          // ID đối tác
    $partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_buyCard'");         // Key đối tác
    $wallet_number       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'wallet_buyCard'");              // Số ví đối tác

    $dataPost = [
        'partner_id'    => $partner_id,
        'command'       => 'buycard',
        'request_id'    => $request_id,
        'service_code'  => $telco,
        'wallet_number' => $wallet_number,
        'value'         => $amount,
        'qty'           => $quantity,
        'sign'          => md5($partner_key . $partner_id . 'buycard' . $request_id)
    ];

    $url = "https://" . $partner_server_name . "/api/cardws";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}



// TẢI LẠI LỆNH MUA THẺ CÀO, KHÔNG PHÁT SINH GIAO DỊCH [KHÔNG SỬ DỤNG ĐƯỢC]
// function redownloadBuyCard($request_id, $order_code)
// {
//     $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_buyCard'"); // Tên server đối tác
//     $partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_buyCard'");          // ID đối tác
//     $partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_buyCard'");         // Key đối tác

//     $dataPost = [
//         'partner_id'    => $partner_id,
//         'command'       => 'redownload',
//         'request_id'    => $request_id,
//         'order_code'    => $order_code,
//         'sign'          => md5($partner_key . $partner_id . 'redownload' . $request_id)
//     ];

//     $url = "http://" . $partner_server_name . "/api/cardws";

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_ENCODING, '');
//     curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 0); 
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  // Disable SSL verification
//     curl_setopt($ch, CURLOPT_HTTPHEADER, [
//         'Content-Type: application/json'
//     ]);

//     $response = curl_exec($ch);
//     curl_close($ch);

//     return json_decode($response, true);
// }


// KIỂM TRA TỒN KHO [KHÔNG SỬ DỤNG ĐƯỢC]
// function checkBuyCard($telco, $amount, $quantity)
// {
//     $partner_server_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_buyCard'"); // Tên server đối tác
//     $partner_id          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_buyCard'");          // ID đối tác
//     $partner_key         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_buyCard'");         // Key đối tác

//     $dataPost = [
//         'partner_id'   => $partner_id,
//         'command'      => 'checkavailable',
//         'service_code' => $telco,
//         'value'        => $amount,
//         'qty'          => $quantity,
//         'sign'         => md5($partner_key . $partner_id . 'checkavailable')
//     ];

//     $url = "http://" . $partner_server_name . "/api/cardws";

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_ENCODING, '');
//     curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 0);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Disable SSL verification
//     curl_setopt($ch, CURLOPT_HTTPHEADER, [
//         'Content-Type: application/json'
//     ]);

//     $response = curl_exec($ch);
//     curl_close($ch);

//     return json_decode($response, true);
// }


// KIỂM TRA DANH SÁCH SẢN PHẨM
function checkProducts($partner_id, $partner_key, $partner_server_name)
{
    $dataPost = [
        'partner_id' => $partner_id,
        'sign'       => md5($partner_key . $partner_id . 'productlist'),
        'command'    => 'productlist'
    ];

    $url = "https://" . $partner_server_name . "/api/rechargews";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// KIỂM TRA SỐ DƯ TÀI KHOẢN
function getBalance($partner_server_name, $partner_id, $partner_key, $wallet_number)
{
    $dataPost = [
        'partner_id'    => $partner_id,
        'command'       => 'getbalance',
        'wallet_number' => $wallet_number,
        'sign'          => md5($partner_key . $partner_id . 'getbalance')
    ];

    $url = "https://" . $partner_server_name . "/api/cardws";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// ĐẶT ĐƠN HÀNG TOPUP
function orderTopup($partner_id, $partner_key, $partner_server_name, $service_code, $amount, $quantity, $request_id, $account_info)
{
    $dataPost = [
        'partner_id' => $partner_id,
        'command' => 'topup',
        'request_id' => $request_id,
        'service_code' => $service_code,
        'amount' => $amount,
        'qty' => $quantity,
        'account_info' => [
            'phone' => $account_info
        ],
        'sign' => md5($partner_key . $partner_id . 'topup' . $request_id)
    ];

    $url = "https://" . $partner_server_name . "/api/rechargews";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// KIỂM TRA TRẠNG THÁI ĐƠN HÀNG
function checkStatusTopup($partner_id, $partner_key, $partner_server_name, $order_code, $request_id)
{
    $dataPost = [
        'partner_id' => $partner_id,
        'command' => 'getstatus',
        'request_id' => $request_id,
        'order_code' => $order_code,
        'sign' => md5($partner_key . $partner_id . 'getstatus' . $request_id)
    ];

    $url = "https://" . $partner_server_name . "/api/rechargews";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}