<?php
$google_favicon     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_favicon'");     // Google Favicon
$google_search      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_search'");      // Google Search
$google_logo_light  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_logo_light'");  // Google Image
$google_logo_dark   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_logo_dark'");   // Google Image
$google_description = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_description'"); // Google Description
$google_analytic    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_analytic'");    // Google Analytic
$header_script      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'header_script'");      // Google Extension

$dmca_link        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'dmca_link'"); // DMCA Link
$dmca_meta_verify = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'dmca_meta_verify'"); // DMCA Meta

$google_reCaptcha = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_ggReCaptcha'"); // Google reCapcha
if ($google_reCaptcha == 1) {
    $google_site_key = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'ggReCaptcha_site_key'"); // Google Site Key
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- SEO Tags -->
    <meta name="title" content="Đổi thẻ cào thành tiền mặt - <?= $_SERVER['HTTP_HOST'] ?>">
    <meta name="description" content="<?= $google_description ?>">
    <meta name="author" content="<?= $_SERVER['HTTP_HOST'] ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Vietnamese">
    <meta name="revisit-after" content="7 days">

    <!-- Popular Search Terms -->
    <meta name="keywords" content="<?= $google_search ?>">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?= isset($title_website) ? $title_website :  'Đăng nhập - ' . $_SERVER['HTTP_HOST'] ?>">
    <meta property="og:description" content="<?= $google_description ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $_SERVER['HTTP_HOST'] ?>">
    <meta property="og:image" content="<?= $google_favicon ?>">
    <meta property="og:image:alt" content="Logo">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="<?= $google_favicon ?>">
    <meta name="twitter:title" content="<?= isset($title_website) ? $title_website :  'Đăng nhập - ' . $_SERVER['HTTP_HOST'] ?>">
    <meta name="twitter:description" content="<?= $google_description ?>">
    <meta name="twitter:image" content="<?= $google_favicon ?>">
    <meta name="twitter:image:alt" content="Logo">

    <!-- DMCA -->
    <?= $dmca_meta_verify ?>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $google_favicon ?>" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $google_favicon ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $google_favicon ?>" />
    <link rel="manifest" href="<?= getDomain() ?>/frontend/app-assets/favicon/site.webmanifest" />
    <meta name="msapplication-TileColor" content="#38b6ff" />
    <meta name="theme-color" content="#ffffff" />

    <?php
    if (!empty($google_reCaptcha) && $google_reCaptcha == 1) {
    ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?= $google_site_key ?>"></script>
    <?php
    }
    ?>

    <!-- Google Analytic -->
    <?= $google_analytic ?>

    <!-- Extension -->
    <?= $header_script ?>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Plugin -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/plugin/swiper-bundle.min.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/icons/iconly/index.min.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/icons/remix-icon/index.min.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/bootstrap.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/colors.css?v=1.0.0" />

    <!-- Base -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/base/typography.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/base/base.css?v=1.0.0" />

    <!-- Theme -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/theme/colors-dark.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/theme/theme-dark.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/custom-rtl.css?v=1.0.0" />

    <!-- Layouts -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/sider.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/header.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/page-content.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/components.css?v=1.0.0" />

    <!-- Customizer -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/customizer.css?v=1.0.0" />

    <!-- Charts -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/plugin/apex-charts.css?v=1.0.0" />

    <!-- Pages -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/pages/dashboard-analytics.css?v=1.0.0" />

    <!-- Horizontal -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/horizontal-menu.css?v=1.0.0" />

    <!-- Custom -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/assets/css/main.css?v=1.0.0" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css?v=1.0.0" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title><?= isset($title_website) ? "$title_website - " . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'] ?></title>
</head>

<body>