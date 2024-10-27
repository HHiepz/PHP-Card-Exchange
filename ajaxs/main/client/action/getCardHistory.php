<?php
require('../../../../core/database.php');
require('../../../../core/function.php');

checkToken("client");

$user_id = getIdUser();
$last_update = $_GET['last_update'] ?? '1970-01-01 00:00:00';

$query = "SELECT MAX(`card-data_updated_api`) AS latest_update FROM `card-data` WHERE `user_id` = ?";

$result = pdo_query($query, [$user_id]);

if (!empty($result)) {
    $latest_update = $result[0]['latest_update'] ?? '1970-01-01 00:00:00';
    $new_update = strtotime($latest_update) > strtotime($last_update);

    echo json_encode([
        'success' => true,
        'newUpdate' => $new_update,
        'latestUpdate' => $latest_update
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No data found'
    ]);
}
