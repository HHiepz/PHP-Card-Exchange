<?php
require('../../../core/database.php');
require('../../../core/function.php');

checkToken("client");

$user_id      = getIdUser();

$query = "
  SELECT 
    c.*, 
    COUNT(c.`card-data_id`) OVER () AS total_cards,
    SUM(CASE WHEN c.`card-data_status` = 'success' THEN 1 ELSE 0 END) OVER () AS total_card_true,
    SUM(CASE WHEN c.`card-data_status` = 'fail' THEN 1 ELSE 0 END) OVER () AS total_card_false,
    SUM(CASE WHEN c.`card-data_status` = 'success' THEN c.`card-data_amount_recieve` ELSE 0 END) OVER () AS total_card_receive
  FROM `card-data` c
  WHERE c.`user_id` = ?
  ORDER BY c.`card-data_id` DESC
";

$result = pdo_query($query, [$user_id]);

// Dữ liệu thẻ của người dùng
$cards = $result;

// Thống kê
if (count($result) > 0) {
  $total_cards        = $result[0]['total_cards'];        // Tổng số thẻ
  $total_card_true    = $result[0]['total_card_true'];    // Tổng thẻ đúng
  $total_card_false   = $result[0]['total_card_false'];   // Tổng thẻ sai
  $total_card_receive = $result[0]['total_card_receive']; // Tổng thực nhận
} else {
  $total_cards = 0;
  $total_card_true = 0;
  $total_card_false = 0;
  $total_card_receive = 0;
}

// Lấy thời gian cập nhật mới nhất
$latest_update = pdo_query_value("SELECT MAX(`card-data_updated_api`) FROM `card-data` WHERE `user_id` = ?", [getIdUser()]);

// Header
$title_website = 'Lịch sử đổi thẻ';
require('../../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
  <div class="col-12 mb-32">
    <div class="row g-32">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <span class="h3 d-block mb-8 text-center">Thống kê đổi thẻ</span>
            <p class="hp-p1-body mb-8 text-center text-black-80 hp-text-color-dark-30">
              Ghi chú: Dữ liệu chỉ được giữ tại web trong vòng 2 tháng gần nhất. Và sẽ được cập nhật mới vào đầu tháng
            </p>
            <div class="divider"></div>

            <div class="row g-32">
              <div class="col-12 col-md-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-16">
                      <div class="col-6 hp-flex-none w-auto">
                        <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-primary-4 hp-bg-color-dark-primary rounded-circle">
                          <i class="fa-solid fa-hand-holding-dollar text-primary hp-text-color-dark-primary-2" style="font-size: 24px"></i>
                        </div>
                      </div>

                      <div class="col">
                        <h3 class="mb-4 mt-8"><?= number_format($total_card_receive) ?>đ</h3>
                        <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                          Tổng thực nhận
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-16">
                      <div class="col-6 hp-flex-none w-auto">
                        <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-secondary-4 hp-bg-color-dark-secondary rounded-circle">
                          <i class="fa-solid fa-sack-dollar text-secondary" style="font-size: 24px"></i>
                        </div>
                      </div>
                      <div class="col">
                        <h3 class="mb-4 mt-8"><?= number_format($total_card_true) ?></h3>
                        <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                          Tổng thẻ đúng
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-16">
                      <div class="col-6 hp-flex-none w-auto">
                        <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-warning-4 hp-bg-color-dark-warning rounded-circle">
                          <i class="fa-solid fa-sack-xmark text-warning" style="font-size: 24px"></i>
                        </div>
                      </div>

                      <div class="col">
                        <h3 class="mb-4 mt-8"><?= number_format($total_card_false) ?></h3>
                        <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                          Tổng thẻ sai
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-16">
                      <div class="col-6 hp-flex-none w-auto">
                        <div class="avatar-item d-flex align-items-center justify-content-center avatar-lg bg-danger-4 hp-bg-color-dark-danger rounded-circle">
                          <i class="fa-solid fa-money-bill-transfer text-danger" style="font-size: 24px"></i>
                        </div>
                      </div>

                      <div class="col">
                        <h3 class="mb-4 mt-8"><?= number_format($total_cards) ?></h3>
                        <p class="hp-p1-body mb-0 text-black-80 hp-text-color-dark-30">
                          Tổng thẻ đổi
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
            <span class="h3 d-block mb-8 text-center">Lịch sử đổi thẻ</span>
            <div class="divider mt-18 mb-16"></div>

            <div class="block-content" bis_skin_checked="1">
              <div class="table-responsive">

                <table id="data-table_list-exCard" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-nowrap">Trạng thái</th>
                      <th class="text-nowrap">Mã nạp</th>
                      <th class="text-nowrap">Serial</th>
                      <th class="text-nowrap">Mạng</th>
                      <th class="text-nowrap">Tổng gửi</th>
                      <th class="text-nowrap">Tổng thực</th>
                      <th class="text-nowrap">Phí</th>
                      <th class="text-nowrap">Phạt</th>
                      <th class="text-nowrap">Nhận</th>
                      <th class="text-nowrap">Ngày tháng</th>
                      <th class="text-nowrap">Request ID</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    foreach ($cards as $value) {
                      $request_partner_id = empty($value['card-data_partner_request_id']) ? ' - ' : $value['card-data_partner_request_id'];
                    ?>
                      <tr>
                        <td class="text-nowrap">
                          <span class="badge badge-<?= statusCard($value['card-data_status'], "color") ?>">
                            <?= statusCard($value['card-data_status']) ?>
                          </span>
                        </td>
                        <td class="text-nowrap"><?= $value['card-data_code'] ?></td>
                        <td class="text-nowrap"><?= $value['card-data_seri'] ?></td>
                        <td class="text-nowrap"><?= $value['card-data_telco'] ?></td>
                        <td class="text-nowrap"><?= number_format($value['card-data_amount']) ?></td>
                        <td class="text-nowrap"><?= number_format($value['card-data_amount_real']) ?></td>
                        <td class="text-nowrap"><?= $value['card-data_fee'] ?>%</td>
                        <td class="text-nowrap"><?= number_format($value['card-data_punish']) ?></td>
                        <td class="text-nowrap"><?= number_format($value['card-data_amount_recieve']) ?></td>
                        <td class="text-nowrap"><?= $value['card-data_created_at'] ?></td>
                        <td class="text-nowrap"><?= $request_partner_id ?></td>
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
  // Biến toàn cục
  var latestUpdate = <?php echo json_encode($latest_update); ?>;

  function checkForUpdates() {
    $.ajax({
      url: './ajaxs/main/client/action/getCardHistory.php',
      type: 'GET',
      data: {
        last_update: latestUpdate
      },
      dataType: 'json',
      success: function(response) {
        if (response.newUpdate) {
          location.reload();
        }
      },
      error: function(xhr, status, error) {
        console.error('Error checking for updates:', error);
      },
      complete: function() {
        setTimeout(checkForUpdates, 5000);
      }
    });
  }


  $(document).ready(function() {
    var table = new DataTable('#data-table_list-exCard', {
      order: [
        [9, 'desc']
      ],
      columnDefs: [{
        targets: 9,
        type: 'date',
        render: function(data, type, row) {
          return moment(data).format('YYYY-MM-DD HH:mm:ss');
        }
      }]
    });

    // Bắt đầu kiểm tra cập nhật
    checkForUpdates();
  });
</script>