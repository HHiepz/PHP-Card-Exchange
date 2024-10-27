<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="author" content="<?= $_SERVER['HTTP_HOST'] ?>" />
  <meta name="description" content="Đổi card giá rẻ" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= getDomain() ?>/frontend/app-assets/favicon/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= getDomain() ?>/frontend/app-assets/favicon/favicon.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="<?= getDomain() ?>/frontend/app-assets/favicon/favicon.png" />
  <link rel="manifest" href="<?= getDomain() ?>/frontend/app-assets/favicon/site.webmanifest" />
  <meta name="msapplication-TileColor" content="#38b6ff" />
  <meta name="theme-color" content="#ffffff" />

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Plugin -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/plugin/swiper-bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/icons/iconly/index.min.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/icons/remix-icon/index.min.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/colors.css" />

  <!-- Base -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/base/typography.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/base/base.css" />

  <!-- Theme -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/theme/colors-dark.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/theme/theme-dark.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/custom-rtl.css" />

  <!-- Layouts -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/sider.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/header.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/page-content.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/components.css" />
  <!-- Customizer -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/layouts/customizer.css" />

  <!-- Charts -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/plugin/apex-charts.css" />

  <!-- Pages -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/app-assets/css/pages/dashboard-analytics.css" />

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="<?= getDomain() ?>/frontend/assets/css/style-admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">


  <title><?= isset($title_website) ? "[AD] $title_website - " . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'] ?></title>
</head>

<body>
  <main class="hp-bg-color-dark-90 d-flex min-vh-100">
    <div class="hp-sidebar hp-bg-color-black-20 hp-bg-color-dark-90 border-end border-black-40 hp-border-color-dark-80">
      <div class="hp-sidebar-container">
        <div class="hp-sidebar-header-menu">
          <div class="row justify-content-between align-items-end mx-0">
            <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-visible">
              <div class="hp-cursor-pointer">
                <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/20/svg">
                  <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                  <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                </svg>
              </div>
            </div>

            <div class="w-auto px-0">
              <div class="hp-header-logo d-flex align-items-center">
                <a href="<?= getDomain() ?>/admin" class="position-relative">
                  <img class="hp-logo hp-sidebar-visible hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                  <img class="hp-logo hp-sidebar-visible hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo-dark.png" alt="logo" />
                  <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                  <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo-dark.png" alt="logo" />
                  <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                  <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo-dark.png" alt="logo" />
                </a>
              </div>
            </div>

            <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden">
              <div class="hp-cursor-pointer mb-4">
                <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/20/svg">
                  <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                  <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                </svg>
              </div>
            </div>
          </div>

          <ul>
            <li>
              <div class="menu-title">HHIEPZ</div>
              <div class="divider"></div>

              <ul>
                <li>
                  <a href="<?= getDomain() ?>/admin">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Bảng điều khiển" aria-label="Bảng điều khiển"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <svg xmlns="http://www.w3.org/20/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                          <path d="M8.97 22h6c5 0 7-2 7-7V9c0-5-2-7-7-7h-6c-5 0-7 2-7 7v6c0 5 2 7 7 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                          </path>
                          <path d="m1.97 12.7 6-.02c.75 0 1.59.57 1.87 1.27l1.14 2.88c.26.65.67.65.93 0l2.29-5.81c.22-.56.63-.58.91-.05l1.04 1.97c.31.59 1.11 1.07 1.77 1.07h4.06" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                          </path>
                        </svg>
                      </span>

                      <span>Bảng điều khiển</span>
                    </span>
                  </a>
                </li>
              </ul>
            </li>

            <li>
              <div class="menu-title">LIST</div>
              <ul>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/member">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Thành viên" aria-label="Thành viên"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-user"></i>
                      </span>

                      <span>Thành viên</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/card">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Đổi thẻ cào" aria-label="Đổi thẻ cào"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                      </span>

                      <span>Đổi thẻ cào</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/buyCard">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Mua thẻ cào" aria-label="Mua thẻ cào"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-shop"></i>
                      </span>

                      <span>Mua thẻ cào</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/withdraw">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Rút tiền" aria-label="Rút tiền"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-money-check-dollar"></i>
                      </span>

                      <span>Rút tiền</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/orderTopup">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Đơn nạp thẻ" aria-label="Đơn nạp thẻ"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-money-check-dollar"></i>
                      </span>

                      <span>Đơn nạp thẻ</span>
                    </span>
                  </a>
                </li>
              </ul>
            </li>

            <li>
              <div class="menu-title">HISTORY</div>
              <ul>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/money">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Dòng tiền" aria-label="Dòng tiền"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-file-invoice"></i>
                      </span>

                      <span>Dòng tiền</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/rank">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Vai trò" aria-label="Vai trò"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-file-invoice"></i>
                      </span>

                      <span>Vai trò</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/transfer">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Chuyển tiền" aria-label="Chuyển tiền"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                      </span>

                      <span>Chuyển tiền</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/list/partner">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Đối tác API" aria-label="Đối tác API"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-code-compare"></i>
                      </span>

                      <span>Đối tác API</span>
                    </span>
                  </a>
                </li>
              </ul>
            </li>

            <li>
              <div class="menu-title">Admin</div>

              <ul>
                <li>
                  <a href="<?= getDomain() ?>/admin/administrator/settingCard">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt đổi thẻ" aria-label="Cài đặt đổi thẻ"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-circle-dollar-to-slot"></i>
                      </span>

                      <span>Cài đặt đổi thẻ</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/administrator/settingMoney">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt rút / chuyển" aria-label="Cài đặt rút / chuyển"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                      </span>

                      <span>Cài đặt rút / chuyển</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/administrator/settingNotification">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt thông báo" aria-label="Cài đặt thông báo"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-bell"></i>
                      </span>

                      <span>Cài đặt thông báo</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/administrator/settingMaintenance">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Bảo trì chức năng" aria-label="Bảo trì chức năng"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-bell"></i>
                      </span>

                      <span>Bảo trì chức năng</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/administrator/settingWebsite">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Thiết lập website" aria-label="Thiết lập website"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-gear"></i>
                      </span>

                      <span>Header & Footer</span>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="<?= getDomain() ?>/admin/administrator/settingWithdrawProfit">
                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt rút tiền cuối tháng" aria-label="Cài đặt rút tiền cuối tháng"></div>

                    <span>
                      <span class="submenu-item-icon">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                      </span>

                      <span>Cài đặt rút tiền cuối tháng</span>
                    </span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="hp-main-layout">
      <header>
        <div class="row w-100 m-0">
          <div class="col px-0">
            <div class="row w-100 align-items-center justify-content-between position-relative">
              <div class="col w-auto hp-flex-none hp-mobile-sidebar-button me-24 px-0" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <button type="button" class="btn btn-text btn-icon-only">
                  <i class="ri-menu-fill hp-text-color-black-80 hp-text-color-dark-30 lh-1" style="font-size: 24px"></i>
                </button>
              </div>

              <div class="hp-horizontal-logo-menu d-flex align-items-center w-auto">
                <div class="col hp-flex-none w-auto hp-horizontal-block">
                  <div class="hp-header-logo d-flex align-items-center">
                    <a href="index.html" class="position-relative">
                      <div class="hp-header-logo-icon position-absolute bg-black-20 hp-bg-dark-90 rounded-circle border border-black-0 hp-border-color-dark-90 d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; top: -5px">
                        <svg class="hp-fill-color-dark-0" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/20/svg">
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
              </div>

              <div class="hp-header-search d-none col">
                <input type="text" class="form-control" placeholder="Search..." id="header-search" autocomplete="off" />
              </div>

              <div class="col hp-flex-none w-auto pe-0">
                <div class="row align-items-center justify-content-end">
                  <div class="w-auto px-0">
                    <div class="d-flex align-items-center me-4 hp-header-search-button">
                      <button type="button" class="btn btn-icon-only bg-transparent border-0 hp-hover-bg-black-10 hp-hover-bg-dark-100 hp-transition d-flex align-items-center justify-content-center" style="height: 40px">
                        <svg class="hp-header-search-button-icon-1 hp-text-color-black-80 hp-text-color-dark-30" set="curved" xmlns="http://www.w3.org/20/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M11.5 21a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19ZM22 22l-2-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <i class="d-none hp-header-search-button-icon-2 ri-close-line hp-text-color-black-60" style="font-size: 24px"></i>
                      </button>
                    </div>
                  </div>

                  <div class="hover-dropdown-fade w-auto px-0 d-flex align-items-center position-relative">
                    <button type="button" class="btn btn-icon-only bg-transparent border-0 hp-hover-bg-black-10 hp-hover-bg-dark-100 hp-transition d-flex align-items-center justify-content-center" style="height: 40px">
                      <svg xmlns="http://www.w3.org/20/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" class="hp-text-color-black-80 hp-text-color-dark-30">
                        <path d="M12 6.44v3.33M12.02 2C8.34 2 5.36 4.98 5.36 8.66v2.1c0 .68-.28 1.7-.63 2.28l-1.27 2.12c-.78 1.31-.24 2.77 1.2 3.25a23.34 23.34 0 0 0 14.73 0 2.22 2.22 0 0 0 1.2-3.25l-1.27-2.12c-.35-.58-.63-1.61-.63-2.28v-2.1C18.68 5 15.68 2 12.02 2Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"></path>
                        <path d="M15.33 18.82c0 1.83-1.5 3.33-3.33 3.33-.91 0-1.75-.38-2.35-.98-.6-.6-.98-1.44-.98-2.35" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"></path>
                      </svg>
                    </button>

                    <div class="hp-notification-menu dropdown-fade position-absolute pt-18" style="width: 380px; top: 100%">
                      <div class="p-24 rounded hp-bg-black-0 hp-bg-dark-100">
                        <div class="row justify-content-between align-items-center mb-16">
                          <div class="col hp-flex-none w-auto h5 hp-text-color-black-100 hp-text-color-dark-10 hp-text-color-dark-0 me-64 mb-0">
                            Thông báo
                          </div>
                        </div>

                        <div class="divider my-4"></div>

                        <div class="hp-overflow-y-auto px-10" style="
                              max-height: 400px;
                              margin-right: -10px;
                              margin-left: -10px;
                            ">
                          <div class="row hp-cursor-pointer rounded hp-transition hp-hover-bg-primary-4 hp-hover-bg-dark-80 py-12 px-10" style="margin-left: -10px; margin-right: -10px">
                            <div class="w-auto px-0 me-12">
                              <div class="avatar-item hp-bg-dark-success bg-success-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px">
                                <svg xmlns="http://www.w3.org/20/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="hp-text-color-success-1">
                                  <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2Zm4.78 7.7-5.67 5.67a.75.75 0 0 1-1.06 0l-2.83-2.83a.754.754 0 0 1 0-1.06c.29-.29.77-.29 1.06 0l2.3 2.3 5.14-5.14c.29-.29.77-.29 1.06 0 .29.29.29.76 0 1.06Z" fill="currentColor"></path>
                                </svg>
                              </div>
                            </div>

                            <div class="w-auto px-0 col">
                              <p class="d-block fw-medium hp-p1-body hp-text-color-black-100 hp-text-color-dark-0 mb-4">
                                <span class="hp-text-color-black-60">NHT đã chuyển
                                </span>
                                10,000đ
                                <span class="hp-text-color-black-60">cho</span>
                                NHT2
                              </p>

                              <span class="d-block hp-badge-text fw-medium hp-text-color-black-60 hp-text-color-dark-40">17h
                                ago</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="hover-dropdown-fade w-auto px-0 d-flex align-items-center position-relative">
                    <button type="button" class="btn btn-icon-only bg-transparent border-0 hp-hover-bg-black-10 hp-hover-bg-dark-100 hp-transition d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                      <svg xmlns="http://www.w3.org/20/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M9 22h6c5 0 7-2 7-7V9c0-5-2-7-7-7H9C4 2 2 4 2 9v6c0 5 2 7 7 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17 2.44v9.98c0 1.97-1.41 2.74-3.14 1.7l-1.32-.79c-.3-.18-.78-.18-1.08 0l-1.32.79C8.41 15.15 7 14.39 7 12.42V2.44" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path d="M9 22h6c5 0 7-2 7-7V9c0-5-2-7-7-7H9C4 2 2 4 2 9v6c0 5 2 7 7 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17 2.44v9.98c0 1.97-1.41 2.74-3.14 1.7l-1.32-.79c-.3-.18-.78-.18-1.08 0l-1.32.79C8.41 15.15 7 14.39 7 12.42V2.44" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                      </svg>
                    </button>

                    <div class="hp-notification-menu dropdown-fade position-absolute pt-18" style="width: 200px; top: 100%">
                      <div class="p-24 rounded hp-bg-black-0 hp-bg-dark-100">
                        <div class="hp-overflow-y-auto px-10" style="
                              max-height: 400px;
                              margin-right: -10px;
                              margin-left: -10px;
                            ">
                          <a href="<?= getDomain() ?>" class="btn btn-dashed text-info border-info hp-hover-text-color-info-2 hp-hover-border-color-info-2 w-100 mb-10">
                            Trang chủ website
                          </a>
                          <button class="btn btn-dashed text-black-100 border-black-100 hp-hover-text-color-black-80 hp-hover-border-color-black-80 w-100">
                            Liên hệ hỗ trợ
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
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
              <a href="index.html" class="position-relative">
                <img class="hp-logo hp-sidebar-visible hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                <img class="hp-logo hp-sidebar-visible hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="<?= getDomain() ?>/frontend/app-assets/img/logo/logo.png" alt="logo" />
              </a>
            </div>
          </div>

          <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden" data-bs-dismiss="offcanvas" aria-label="Close">
            <button type="button" class="btn btn-text btn-icon-only bg-transparent">
              <i class="ri-close-fill lh-1 hp-text-color-black-80" style="font-size: 24px"></i>
            </button>
          </div>
        </div>

        <div class="hp-sidebar hp-bg-color-black-20 hp-bg-color-dark-90 border-end border-black-40 hp-border-color-dark-80">
          <div class="hp-sidebar-container">
            <div class="hp-sidebar-header-menu">
              <div class="row justify-content-between align-items-end mx-0">
                <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-visible">
                  <div class="hp-cursor-pointer">
                    <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/20/svg">
                      <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                      <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                    </svg>
                  </div>
                </div>

                <div class="w-auto px-0">
                  <div class="hp-header-logo d-flex align-items-center">
                    <a href="index.html" class="position-relative">
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
                    <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/20/svg">
                      <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                      <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                    </svg>
                  </div>
                </div>
              </div>

              <ul>
                <li>
                  <div class="menu-title">HHIEPZ</div>
                  <div class="divider"></div>

                  <ul>
                    <li>
                      <a href="<?= getDomain() ?>/admin">
                        <div class="tooltip-item active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Bảng điều khiển" aria-label="Bảng điều khiển"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <svg xmlns="http://www.w3.org/20/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                              <path d="M8.97 22h6c5 0 7-2 7-7V9c0-5-2-7-7-7h-6c-5 0-7 2-7 7v6c0 5 2 7 7 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                              </path>
                              <path d="m1.97 12.7 6-.02c.75 0 1.59.57 1.87 1.27l1.14 2.88c.26.65.67.65.93 0l2.29-5.81c.22-.56.63-.58.91-.05l1.04 1.97c.31.59 1.11 1.07 1.77 1.07h4.06" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                              </path>
                            </svg>
                          </span>

                          <span>Bảng điều khiển</span>
                        </span>
                      </a>
                    </li>
                  </ul>
                </li>

                <li>
                  <div class="menu-title">LIST</div>
                  <ul>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/member">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Thành viên" aria-label="Thành viên"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-user"></i>
                          </span>

                          <span>Thành viên</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/card">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Đổi thẻ cào" aria-label="Đổi thẻ cào"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                          </span>

                          <span>Đổi thẻ cào</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/buyCard">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Mua thẻ cào" aria-label="Mua thẻ cào"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-shop"></i>
                          </span>

                          <span>Mua thẻ cào</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/withdraw">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Rút tiền" aria-label="Rút tiền"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-money-check-dollar"></i>
                          </span>

                          <span>Rút tiền</span>
                        </span>
                      </a>
                    </li>
                  </ul>
                </li>

                <li>
                  <div class="menu-title">HISTORY</div>
                  <ul>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/money">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Dòng tiền" aria-label="Dòng tiền"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-file-invoice"></i>
                          </span>

                          <span>Dòng tiền</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/rank">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Vai trò" aria-label="Vai trò"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-file-invoice"></i>
                          </span>

                          <span>Vai trò</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/transfer">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Chuyển tiền" aria-label="Chuyển tiền"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                          </span>

                          <span>Chuyển tiền</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/list/partner">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Đối tác API" aria-label="Đối tác API"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-code-compare"></i>
                          </span>

                          <span>Đối tác API</span>
                        </span>
                      </a>
                    </li>
                  </ul>
                </li>

                <li>
                  <div class="menu-title">Admin</div>

                  <ul>
                    <li>
                      <a href="<?= getDomain() ?>/admin/administrator/settingCard">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt đổi thẻ" aria-label="Cài đặt đổi thẻ"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-circle-dollar-to-slot"></i>
                          </span>

                          <span>Cài đặt đổi thẻ</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/administrator/settingMoney">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt rút / chuyển" aria-label="Cài đặt rút / chuyển"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                          </span>

                          <span>Cài đặt rút / chuyển</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/administrator/settingNotification">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt thông báo" aria-label="Cài đặt thông báo"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-bell"></i>
                          </span>

                          <span>Cài đặt thông báo</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/administrator/settingMaintenance">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Bảo trì chức năng" aria-label="Bảo trì chức năng"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-bell"></i>
                          </span>

                          <span>Bảo trì chức năng</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/administrator/settingWebsite">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-gear"></i>
                          </span>

                          <span>Header & Footer</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?= getDomain() ?>/admin/administrator/settingWithdrawProfit">
                        <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Cài đặt rút tiền cuối tháng" aria-label="Cài đặt rút tiền cuối tháng"></div>

                        <span>
                          <span class="submenu-item-icon">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                          </span>

                          <span>Cài đặt rút tiền cuối tháng</span>
                        </span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>