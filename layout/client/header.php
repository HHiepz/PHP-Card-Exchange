<?php
$status_buyCard  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_buyCard'");  // Trạng thái mua thẻ
$status_withdraw = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_withdraw'"); // Trạng thái rút tiền
$status_transfer = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_transfer'"); // Trạng thái chuyển tiền
$status_top      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_top'");      // Trạng thái TOP 10 thành viên nạp tiền
$status_topup    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_topup'");    // Trạng thái nạp thẻ

$google_favicon     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_favicon'");     // Google Favicon
$google_search      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_search'");      // Google Search
$google_logo_light  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_logo_light'");  // Google Image
$google_logo_dark   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_logo_dark'");   // Google Image
$google_description = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_description'"); // Google Description
$google_analytic    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_analytic'");    // Google Analytic
$header_script      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'header_script'");      // Google Extension

$footer_support   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_support'");  // Hỗ trợ chính
$footer_facebook  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_facebook'"); // Facebook Message
$footer_discord   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_discord'");  // Discord
$footer_telegram  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_telegram'"); // Telegram
$footer_youtube   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_youtube'");  // Youtube

$dmca_link        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'dmca_link'"); // DMCA Link
$dmca_meta_verify = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'dmca_meta_verify'"); // DMCA Meta

$version          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'version'"); // Phiên bản phát hành
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
    <meta property="og:title" content="<?= isset($title_website) ? "$title_website - " . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'] ?>">
    <meta property="og:description" content="<?= $google_description ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $_SERVER['HTTP_HOST'] ?>">
    <meta property="og:image" content="https://i.ibb.co/Vvc0pVQ/login-light-theme-01.png">

    <!-- Twitter Card Tags -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:title" content="<?= isset($title_website) ? "$title_website - " . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'] ?>">
    <meta property="twitter:description" content="<?= $google_description ?>">
    <meta property="twitter:image" content="https://i.ibb.co/Vvc0pVQ/login-light-theme-01.png" />
    <meta property="twitter:image:alt" content="Logo">

    <!-- DMCA -->
    <?= $dmca_meta_verify ?>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $google_favicon ?>" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $google_favicon ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $google_favicon ?>" />
    <link rel="manifest" href="<?= getDomain() ?>/frontend/app-assets/favicon/site.webmanifest" />
    <meta name="msapplication-TileColor" content="#38b6ff" />
    <meta name="theme-color" content="#ffffff" />

    <!-- Google Analytic -->
    <?= $google_analytic ?>

    <!-- Extension -->
    <?= $header_script ?>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

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

    <!-- Flags -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/plugin/flags.min.css?v=1.0.0" />

    <!-- Charts -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/plugin/apex-charts.css?v=1.0.0" />

    <!-- Pages -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/pages/dashboard-analytics.css?v=1.0.0" />
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/pages/widgets-selectbox.css?v=1.0.0" />

    <!-- Horizontal -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/horizontal-menu.css?v=1.0.0" />

    <!-- Custom -->
    <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/assets/css/main.css?v=1.1.0" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title><?= isset($title_website) ? "$title_website - " . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'] ?></title>
</head>

<body class="horizontal-active">
    <!-- EFFECT SNOW -->
    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❆
        </div>
        <div class="snowflake">
            ❄
        </div>
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❆
        </div>
        <div class="snowflake">
            ❄
        </div>
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❆
        </div>
        <div class="snowflake">
            ❄
        </div>
    </div>

    <main class="hp-bg-color-dark-90 d-flex min-vh-100">
        <div class="hp-main-layout hp-main-layout-horizontal">
            <header>
                <div class="row w-100 m-0">
                    <div class="col px-0">
                        <div class="row w-100 align-items-center justify-content-between position-relative">
                            <div class="col w-auto hp-flex-none hp-mobile-sidebar-button me-24 px-0" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                                <button type="button" class="btn btn-text btn-icon-only">
                                    <i class="fa-solid fa-bars"></i>
                                </button>
                            </div>

                            <div class="hp-horizontal-logo-menu d-flex align-items-center w-auto">
                                <div class="col hp-flex-none w-auto hp-horizontal-block">
                                    <div class="hp-header-logo d-flex align-items-center">
                                        <a href="<?= getDomain() ?>" class="position-relative">
                                            <img class="hp-logo hp-sidebar-visible hp-dark-none" src="<?= $google_logo_light ?>" alt="logo" />
                                            <img class="hp-logo hp-sidebar-visible hp-dark-block" src="<?= $google_logo_dark ?>" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="<?= $google_logo_light ?>" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="<?= $google_logo_dark ?>" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="<?= $google_logo_light ?>" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="<?= $google_logo_dark ?>" alt="logo" />
                                        </a>
                                    </div>
                                </div>

                                <div class="col hp-flex-none w-auto hp-horizontal-block hp-horizontal-menu ps-24">
                                    <ul class="d-flex flex-wrap align-items-center">
                                        <li class="px-6">
                                            <a href="<?= getDomain() ?>" class="px-12 py-4">
                                                <span>Trang chủ</span>
                                            </a>
                                        </li>

                                        <li class="px-6">
                                            <a href="<?= getDomain() ?>" class="px-12 py-4">
                                                <span>Đổi thẻ</span>
                                            </a>
                                        </li>

                                        <?php
                                        if ($status_buyCard == 1) {
                                        ?>
                                            <li class="px-6">
                                                <a href="<?= getDomain() ?>/buyCard" class="px-12 py-4">
                                                    <span>Mua mã thẻ</span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>


                                        <?php
                                        if ($status_withdraw == 1) {
                                        ?>
                                            <li class="px-6">
                                                <a href="<?= getDomain() ?>/withdraw" class="px-12 py-4">
                                                    <span>Rút tiền</span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if ($status_transfer == 1) {
                                        ?>
                                            <li class="px-6">
                                                <a href="<?= getDomain() ?>/transfer" class="px-12 py-4">
                                                    <span>Chuyển tiền</span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <li class="px-6">
                                            <a href="<?= getDomain() ?>/partner" class="px-12 py-4">
                                                <span>API</span>
                                            </a>
                                        </li>

                                        <?php
                                        if ($status_top == 1) {
                                        ?>
                                            <li class="px-6">
                                                <a href="<?= getDomain() ?>/top" class="px-12 py-4">
                                                    <span class="text-danger">Top nạp</span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if ($status_topup == 1) {
                                        ?>
                                            <li class="px-6">
                                                <a href="<?= getDomain() ?>/orderTopup" class="px-12 py-4">
                                                    <span class="text-danger">Nạp điện thoại (Giá siêu rẻ)</span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="hp-header-search d-none col">
                                <input type="text" class="form-control" placeholder="Search..." id="header-search" autocomplete="off" />
                            </div>

                            <div class="col hp-flex-none w-auto pe-0">
                                <div class="row align-items-center justify-content-end">
                                    <?php
                                    if (checkToken('request')) {
                                        $user_id   = getIdUser();              // ID người dùng 
                                        $user_info = getInfoUser($user_id);    // Thông tin người dùng
                                    ?>
                                        <div class="hover-dropdown-fade w-auto px-0 ms-6 position-relative">
                                            <div class="hp-cursor-pointer rounded-4 border hp-border-color-dark-80">
                                                <div class="rounded-3 overflow-hidden m-4 d-flex">
                                                    <div class="avatar-item hp-bg-info-4 d-flex" style="width: 32px; height: 32px">
                                                        <img src="<?= getDomain() ?>/frontend/app-assets/img/memoji/user-avatar-9.png" />
                                                    </div>

                                                    <!-- Khung Hiển thị tiền -->
                                                    <div class="hp-header-profile-money d-flex align-items-center hp-text-color-black-100 hp-text-color-dark-0 hp-p1-body ms-8">
                                                        Ví: <?= number_format($user_info['user_cash']) ?>đ
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="hp-header-profile-menu dropdown-fade position-absolute pt-18" style="top: 100%; width: 260px">
                                                <div class="rounded hp-bg-black-0 hp-bg-dark-100 px-18 py-24">
                                                    <span class="d-block h5 hp-text-color-black-100 hp-text-color-dark-0 mb-16">
                                                        <?= limitShow($user_info['user_fullname']) ?>
                                                        <span class="badge bg-primary-4 hp-bg-dark-primary border-primary text-primary ms-16"><?= formatRank($user_info['user_rank'])['name'] ?></span>
                                                    </span>
                                                    <?php
                                                    if ($user_info['user_admin'] == 1) {
                                                    ?>
                                                        <div class="col-12">
                                                            <a href="<?= getDomain() ?>/admin" class="d-flex align-items-center fw-medium hp-p1-body my-4 py-8 px-10 hp-transition hp-hover-bg-danger-4 hp-hover-bg-dark-danger hp-hover-bg-danger-80 rounded" target="_self" style="margin-left: -10px; margin-right: -10px">
                                                                <span class="text-danger"><i class="fa-solid fa-crown"></i> Bảng điều khiển</span>
                                                            </a>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="col-12">
                                                        <a href="<?= getDomain() ?>/account/profile" class="d-flex align-items-center fw-medium hp-p1-body my-4 py-8 px-10 hp-transition hp-hover-bg-primary-4 hp-hover-bg-dark-primary hp-hover-bg-dark-80 rounded" target="_self" style="margin-left: -10px; margin-right: -10px">
                                                            <span><i class="fa-solid fa-circle-user"></i> Thông tin tài khoản</span>
                                                        </a>
                                                    </div>

                                                    <div class="divider mt-18 mb-16"></div>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="<?= getDomain() ?>/historyExCard" class="d-flex align-items-center fw-medium hp-p1-body my-4 py-8 px-10 hp-transition hp-hover-bg-primary-4 hp-hover-bg-dark-primary hp-hover-bg-dark-80 rounded" target="_self" style="margin-left: -10px; margin-right: -10px">
                                                                <span><i class="fa-solid fa-money-bill-transfer"></i> Lịch sử đổi thẻ</span>
                                                            </a>
                                                        </div>
                                                        <div class="col-12">
                                                            <a href="<?= getDomain() ?>/historyBuyCard" class="d-flex align-items-center fw-medium hp-p1-body my-4 py-8 px-10 hp-transition hp-hover-bg-primary-4 hp-hover-bg-dark-primary hp-hover-bg-dark-80 rounded" target="_self" style="margin-left: -10px; margin-right: -10px">
                                                                <span><i class="fa-solid fa-receipt"></i> Lịch sử mua thẻ</span>
                                                            </a>
                                                        </div>
                                                        <div class="col-12">
                                                            <a href="<?= getDomain() ?>/historyOrderTopup" class="d-flex align-items-center fw-medium hp-p1-body my-4 py-8 px-10 hp-transition hp-hover-bg-primary-4 hp-hover-bg-dark-primary hp-hover-bg-dark-80 rounded" target="_self" style="margin-left: -10px; margin-right: -10px">
                                                                <span><i class="fa-solid fa-phone"></i> Lịch sử nạp điện thoại</span>
                                                            </a>
                                                        </div>

                                                    </div>

                                                    <div class="divider my-12"></div>

                                                    <div class="row">
                                                        <div class="col-12 mt-24">
                                                            <i class="fa-solid fa-right-from-bracket"></i>
                                                            <a href="<?= getDomain() ?>/account/logout" class="hp-p1-body fw-medium">Đăng xuất</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="w-auto px-0">
                                            <a href="<?= getDomain() ?>/account/login" class="btn btn-primary w-100">Đăng nhập</a>
                                        </div>
                                        <div class="w-auto px-0">
                                            <a href="<?= getDomain() ?>/account/register" class="btn btn-warning ms-6 w-100">Đăng ký</a>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="offcanvas offcanvas-start hp-mobile-sidebar bg-black-20 hp-bg-dark-90" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel" style="width: 256px">
                <div class="offcanvas-header justify-content-between align-items-center ms-16 me-8 mt-16 p-0">
                    <div class="w-auto px-0">
                        <div class="hp-header-logo d-flex align-items-center">
                            <a href="<?= getDomain() ?>" class="position-relative">
                                <img class="hp-logo hp-sidebar-visible hp-dark-none" src="<?= $google_logo_light ?>" alt="logo" />
                                <img class="hp-logo hp-sidebar-visible hp-dark-block" src="<?= $google_logo_dark ?>" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="<?= $google_logo_light ?>" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="<?= $google_logo_dark ?>" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="<?= $google_logo_light ?>" alt="logo" />
                                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="<?= $google_logo_dark ?>" alt="logo" />
                            </a>
                        </div>
                    </div>

                    <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden" data-bs-dismiss="offcanvas" aria-label="Close">
                        <button type="button" class="btn btn-text btn-icon-only bg-transparent">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="hp-sidebar hp-bg-color-black-20 hp-bg-color-dark-90 border-end border-black-40 hp-border-color-dark-80">
                    <div class="hp-sidebar-container">
                        <div class="hp-sidebar-header-menu">
                            <div class="row justify-content-between align-items-end mx-0">
                                <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-visible">
                                    <div class="hp-cursor-pointer">
                                        <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                                            <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div class="w-auto px-0">
                                    <div class="hp-header-logo d-flex align-items-center">
                                        <a href="<?= getDomain() ?>" class="position-relative">
                                            <div class="hp-header-logo-icon position-absolute bg-black-20 hp-bg-dark-90 rounded-circle border border-black-0 hp-border-color-dark-90 d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; top: -5px">
                                                <svg class="hp-fill-color-dark-0" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.709473 0L1.67247 10.8L5.99397 12L10.3267 10.7985L11.2912 0H0.710223H0.709473ZM9.19497 3.5325H4.12647L4.24722 4.88925H9.07497L8.71122 8.95575L5.99397 9.70875L3.28047 8.95575L3.09522 6.87525H4.42497L4.51947 7.93275L5.99472 8.33025L5.99772 8.3295L7.47372 7.93125L7.62672 6.21375H3.03597L2.67897 2.208H9.31422L9.19572 3.5325H9.19497Z" fill="#2D3436" />
                                                </svg>
                                            </div>

                                            <img class="hp-logo hp-sidebar-visible hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                            <img class="hp-logo hp-sidebar-visible hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                            <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                                        </a>
                                    </div>
                                </div>

                                <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden">
                                    <div class="hp-cursor-pointer mb-4">
                                        <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                                            <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <ul>
                                <li>
                                    <div class="menu-title">Card2k xin chào!</div>

                                    <ul>
                                        <li>
                                            <a href="<?= getDomain() ?>">
                                                <span>
                                                    <span class="submenu-item-icon">
                                                        <i class="fa-solid fa-right-left"></i>
                                                    </span>
                                                    <span>Đổi thẻ</span>
                                                </span>
                                            </a>
                                        </li>

                                        <?php
                                        if ($status_buyCard == 1) {
                                        ?>
                                            <li>
                                                <a href="<?= getDomain() ?>/buyCard">
                                                    <span>
                                                        <span class="submenu-item-icon">
                                                            <i class="fa-solid fa-cart-shopping"></i>
                                                        </span>
                                                        <span>Mua mã thẻ</span>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if ($status_withdraw == 1) {
                                        ?>
                                            <li>
                                                <a href="<?= getDomain() ?>/withdraw">
                                                    <span>
                                                        <span class="submenu-item-icon">
                                                            <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                                        </span>
                                                        <span>Rút tiền</span>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if ($status_transfer == 1) {
                                        ?>
                                            <li>
                                                <a href="<?= getDomain() ?>/transfer">
                                                    <span>
                                                        <span class="submenu-item-icon">
                                                            <i class="fa-solid fa-money-bill-transfer"></i>
                                                        </span>
                                                        <span>Chuyển tiền</span>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <li>
                                            <a href="<?= getDomain() ?>/partner">
                                                <span>
                                                    <span class="submenu-item-icon">
                                                        <i class="fa-solid fa-code-compare"></i>
                                                    </span>
                                                    <span>API</span>
                                                </span>
                                            </a>
                                        </li>

                                        <?php
                                        if ($status_top == 1) {
                                        ?>
                                            <li>
                                                <a href="<?= getDomain() ?>/top">
                                                    <span>
                                                        <span class="submenu-item-icon">
                                                            <i class="fa-solid fa-money-bill-transfer"></i>
                                                        </span>
                                                        <span class="text-danger">Top nạp</span>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if ($status_topup == 1) {
                                        ?>
                                            <li>
                                                <a href="<?= getDomain() ?>/orderTopup">
                                                    <span>
                                                        <span class="submenu-item-icon">
                                                            <i class="fa-solid fa-money-bill-transfer"></i>
                                                        </span>
                                                        <span class="text-danger">Nạp điện thoại (Giá siêu rẻ)</span>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <?php
                        if (checkToken('request')) {
                            $user_id   = getIdUser();              // ID người dùng 
                            $user_info = getInfoUser($user_id);    // Thông tin người dùng
                        ?>
                            <div class="row justify-content-between align-items-center hp-sidebar-footer mx-0 hp-bg-color-dark-90">
                                <div class="divider border-black-40 hp-border-color-dark-70 hp-sidebar-hidden mt-0 px-0">
                                </div>

                                <div class="col">
                                    <div class="row align-items-center">
                                        <div class="w-auto px-0">
                                            <div class="avatar-item bg-primary-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px">
                                                <img src="<?= getDomain() ?>/frontend/app-assets/img/memoji/user-avatar-9.png" />
                                            </div>
                                        </div>

                                        <div class="w-auto ms-8 px-0 hp-sidebar-hidden mt-4">
                                            <span class="d-block hp-text-color-black-100 hp-text-color-dark-0 hp-p1-body lh-1"><?= limitShow($user_info['user_fullname']) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col hp-flex-none w-auto px-0 hp-sidebar-hidden">
                                    <a href="<?= getDomain() ?>/account/profile">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="remix-icon hp-text-color-black-100 hp-text-color-dark-0" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path fill="none" d="M0 0h24v24H0z"></path>
                                                <path d="M3.34 17a10.018 10.018 0 0 1-.978-2.326 3 3 0 0 0 .002-5.347A9.99 9.99 0 0 1 4.865 4.99a3 3 0 0 0 4.631-2.674 9.99 9.99 0 0 1 5.007.002 3 3 0 0 0 4.632 2.672c.579.59 1.093 1.261 1.525 2.01.433.749.757 1.53.978 2.326a3 3 0 0 0-.002 5.347 9.99 9.99 0 0 1-2.501 4.337 3 3 0 0 0-4.631 2.674 9.99 9.99 0 0 1-5.007-.002 3 3 0 0 0-4.632-2.672A10.018 10.018 0 0 1 3.34 17zm5.66.196a4.993 4.993 0 0 1 2.25 2.77c.499.047 1 .048 1.499.001A4.993 4.993 0 0 1 15 17.197a4.993 4.993 0 0 1 3.525-.565c.29-.408.54-.843.748-1.298A4.993 4.993 0 0 1 18 12c0-1.26.47-2.437 1.273-3.334a8.126 8.126 0 0 0-.75-1.298A4.993 4.993 0 0 1 15 6.804a4.993 4.993 0 0 1-2.25-2.77c-.499-.047-1-.048-1.499-.001A4.993 4.993 0 0 1 9 6.803a4.993 4.993 0 0 1-3.525.565 7.99 7.99 0 0 0-.748 1.298A4.993 4.993 0 0 1 6 12c0 1.26-.47 2.437-1.273 3.334a8.126 8.126 0 0 0 .75 1.298A4.993 4.993 0 0 1 9 17.196zM12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2z">
                                                </path>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>