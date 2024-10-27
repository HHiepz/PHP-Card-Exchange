<?php
session_start();
// Sử dụng hàm getDomain() để lấy phần đầu của tên miền và xây dựng các đường dẫn tương đối
require($_SERVER['DOCUMENT_ROOT'] . '/core/function.php');
require($_SERVER['DOCUMENT_ROOT'] . '/core/database.php');

pdo_execute("UPDATE `user` SET `user_is_verify` = 0 WHERE `user_id` = ?", [getIdUser()]);
setcookie('token', '', time() - 3600, '/');
session_destroy();

header('Location: /');
exit();
