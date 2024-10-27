<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

checkToken("client");


// Kiểm tra có tồn tại mã đơn hàng không
if (!isset($_GET['buyCard_code'])) {
  header("Location:" . getDomain() . "/historyBuyCard");
  die;
}

// Kiểm tra đơn hàng có tồn tại & thuộc về nguời dùng không
$user_id           = getIdUser();
$buyCard_code      = $purifier->purify($_GET['buyCard_code']);
$checkOrder        = pdo_query_one("SELECT * FROM `buy-card-order` WHERE `buy-card-order_code` = ? AND `user_id` = ?", [$buyCard_code, $user_id]);
if (empty($checkOrder)) {
  header("Location:" . getDomain() . "/historyBuyCard");
  die;
}

// Lấy thông tin đơn hàng
$buyCardData = pdo_query("SELECT * FROM `buy-card-data` WHERE `buy-card-order_code` = ?", [$buyCard_code]);
$total_pay       = $checkOrder['buy-card-order_total_pay'];
$buyCard_status  = $checkOrder['buy-card-order_status'];
$buyCard_created = $checkOrder['created_at'];

// Header
$title_website = 'Chi tiết đơn hàng';
require('../../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
  <div class="row mb-32 gy-32">
    <div class="col-12">
      <div class="hp-bg-black-bg py-32 py-sm-64 px-24 px-sm-48 px-md-80 position-relative overflow-hidden hp-page-content" style="border-radius: 32px">
        <svg width="358" height="336" fill="none" xmlns="http://www.w3.org/2000/svg" class="position-absolute hp-rtl-scale-x-n1" style="bottom: 0px; right: 0px">
          <path d="M730.404 135.471 369.675-6.641l88.802 164.001-243.179-98.8 246.364 263.281-329.128-126.619 114.698 166.726-241.68-62.446" stroke="url(#a)" stroke-width="40" stroke-linejoin="bevel"></path>
          <defs>
            <linearGradient id="a" x1="315.467" y1="6.875" x2="397.957" y2="337.724" gradientUnits="userSpaceOnUse">
              <stop stop-color="#fff"></stop>
              <stop offset="1" stop-color="#fff" stop-opacity="0"></stop>
            </linearGradient>
          </defs>
        </svg>

        <div class="row">
          <div class="col-sm-6 col-12">
            <div class="row">
              <div class="col-12 mb-32">
                <h1 class="mb-0 hp-text-color-black-0">
                  Thông tin đơn hàng
                </h1>
              </div>
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Mã đơn hàng:
                    <span class="badge" id="order-id"><?= $buyCard_code ?></span>
                    <button type="button" class="badge" data-copy-click-id="order-id">
                      <i class="fas fa-copy"></i>
                    </button>
                  </h5>
                  <p class="card-text">Số tiền: <span><?= number_format($total_pay) ?>đ</span></p>
                  <p class="card-text">Trạng thái: <span class="badge badge-<?= statusCard($buyCard_status, "color") ?>"><?= statusCard($buyCard_status) ?></span></p>
                  <p class="card-text">Thời gian: <?= $buyCard_created ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mb-32">
    <div class="row g-32">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <span class="h3 d-block mb-8 text-center">Đơn hàng</span>
            <div class="divider"></div>

            <div class="block-content" bis_skin_checked="1">
              <div class="table-responsive" bis_skin_checked="1">

                <table id="data-table_history-buyCard" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th class="col-2 text-nowrap">Nhà mạng</th>
                      <th class="col-2 text-nowrap">Mệnh giá</th>
                      <th class="col-3 text-nowrap">Code / Pin</th>
                      <th class="col-3 text-nowrap">Serial</th>
                      <th class="col-2 text-nowrap">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    foreach ($buyCardData as $value) {
                    ?>
                      <tr>
                        <td class="col-2 text-nowrap fw-bold"><?= $value['buy-card-data_telco'] ?></td>
                        <td class="col-2 text-nowrap fw-bold text-warning"><?= number_format($value['buy-card-data_price']) ?></td>
                        <td class="col-2 text-nowrap">
                          <span class="badge" id="code-copy"><?= $value['buy-card-data_pin'] ?></span>
                          <button type="button" class="badge" data-copy-click-id="code-copy">
                            <i class="fas fa-copy"></i>
                          </button>
                        </td>
                        <td class="col-2 text-nowrap">
                          <span class="badge" id="code-copy"><?= $value['buy-card-data_serial'] ?></span>
                          <button type="button" class="badge" data-copy-click-id="code-copy">
                            <i class="fas fa-copy"></i>
                          </button>
                        </td>
                        <td class="col-2 text-nowrap">
                          <span class="badge d-none" id="copyAll">
                            Code: <?= $value['buy-card-data_pin'] ?> Serial: <?= $value['buy-card-data_serial'] ?>
                          </span>
                          <button type="button" class="badge" data-copy-click-id="copyAll">
                            Sao chép
                          </button>

                          <span class="badge d-none" id="copyTraTruoc">
                            *100*<?= $value['buy-card-data_pin'] ?>#
                          </span>
                          <button type="button" class="badge" data-copy-click-id="copyTraTruoc">
                            Nạp DT
                          </button>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>

                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="col-2 text-nowrap">Nhà mạng</th>
                      <th class="col-2 text-nowrap">Mệnh giá</th>
                      <th class="col-2 text-nowrap">Code / Pin</th>
                      <th class="col-2 text-nowrap">Serial</th>
                      <th class="col-2 text-nowrap">Hành động</th>
                    </tr>
                  </tfoot>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
// Footer
require('../../../layout/client/footer.php');
?>
<script>
  new DataTable('#data-table_history-buyCard');
</script>