<?php
require('../../../core/database.php');
require('../../../core/function.php');

checkToken("client");

$user_id      = getIdUser();

// Tổng thẻ đã mua và tổng tiền mua thẻ
$buyCard_success = pdo_query_one("SELECT COUNT(`buy-card-order_id`) AS count, SUM(`buy-card-order_total_pay`) AS total FROM `buy-card-order` WHERE `buy-card-order_status` = 'success' AND `user_id` = ?", [$user_id]);
$count_buyCard_success = formatNumber($buyCard_success['count']);
$total_buyCard_success = formatNumber($buyCard_success['total']);

// Lịch sử mua thẻ
$history_buyCard = pdo_query("SELECT * FROM `buy-card-order` WHERE `user_id` = ? ORDER BY `buy-card-order_id` DESC", [$user_id]);

// Header
$title_website = 'Lịch sử mua thẻ';
require('../../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
  <div class="col-12 mb-32">
    <div class="row g-32">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <span class="h3 d-block mb-8 text-center">Thống kê mua thẻ</span>
            <div class="divider"></div>

            <div class="row g-32">
              <div class="col-12 col-md-6 col-xl-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-16">
                      <div class="col-6 hp-flex-none w-auto">
                        <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-primary-4 hp-bg-color-dark-primary rounded-circle">
                          <i class="fa-solid fa-file-invoice-dollar text-primary hp-text-color-dark-primary-2" style="font-size: 24px"></i>
                        </div>
                      </div>

                      <div class="col">
                        <h3 class="mb-4 mt-8"><?= $count_buyCard_success ?></h3>
                        <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                          Tổng thẻ đã mua
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-16">
                      <div class="col-6 hp-flex-none w-auto">
                        <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-secondary-4 hp-bg-color-dark-secondary rounded-circle">
                          <i class="fa-solid fa-coins text-secondary" style="font-size: 24px"></i>
                        </div>
                      </div>

                      <div class="col">
                        <h3 class="mb-4 mt-8"><?= $total_buyCard_success ?></h3>
                        <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                          Tổng tiền mua thẻ
                        </p>
                      </div>
                    </div>
                  </div>
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
            <span class="h3 d-block mb-8 text-center">Lịch sử mua thẻ</span>
            <div class="divider mt-18 mb-16"></div>
            <br />

            <div class="block-content" bis_skin_checked="1">
              <div class="table-responsive" bis_skin_checked="1">


                <table id="data-table_history-buyCard" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-nowrap">Trạng thái</th>
                      <th class="text-nowrap">Mã đơn</th>
                      <th class="text-nowrap">Nhà mạng</th>
                      <th class="text-nowrap">Mệnh giá</th>
                      <th class="text-nowrap">Số lượng</th>
                      <th class="text-nowrap">Hành động</th>
                      <th class="text-nowrap">Ngày tháng</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    foreach ($history_buyCard as $value) {
                      $link_detail = getDomain() . "/details/buyCard/" . $value['buy-card-order_code'];
                    ?>
                      <tr>
                        <td class="text-nowrap">
                          <span class="badge badge-<?= statusCard($value['buy-card-order_status'], "color") ?>">
                            <?= statusCard($value['buy-card-order_status']) ?>
                          </span>
                        </td>
                        <td class="text-nowrap"><?= $value['buy-card-order_code'] ?></td>
                        <td class="text-nowrap"><?= $value['buy-card-order_telco'] ?></td>
                        <td class="text-nowrap"><?= number_format($value['buy-card-order_price']) ?></td>
                        <td class="text-nowrap"><?= number_format($value['buy-card-order_quantity']) ?></td>
                        <td class="text-nowrap">
                          <a href="<?= $link_detail ?>" class="btn btn-sm btn-seacondary">Xem</a>
                        </td>
                        <td class="text-nowrap"><?= $value['created_at'] ?></td>
                      </tr>
                    <?php
                    }
                    ?>

                  </tbody>
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
  new DataTable('#data-table_history-buyCard', {
      order: [[6, 'desc']],
      columnDefs: [
        {
          targets: 6,
          type: 'date',
          render: function ( data, type, row ) {
            return moment(data).format( 'YYYY-MM-DD HH:mm:ss' );
          }
        }
      ]
    });
</script>