<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken("client");

$min_transfer     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'min_transfer'");
$max_transfer     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'max_transfer'");
$user_id          = getIdUser();

// Lịch sử giao dịch
$history_transfer = pdo_query("SELECT * FROM `transfer` WHERE `transfer_user_from` = ? OR `transfer_user_to` = ? ORDER BY `transfer_id` DESC", [$user_id, $user_id]);

// Header
$title_website = 'Chuyển tiền';
require('../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
  <div class="row mb-32 gy-32">
    <div class="col-12">
      <iframe width="100%" height="500" src="https://www.youtube.com/embed/WX8xDpHy07E?si=ti0L4xOBUeJJBzx5" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
  </div>

  <div class="row mb-32 gy-32">
    <div class="col-12 col-sm-6">
      <div class="row g-32">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <span class="h3 d-block mb-8 text-warning"><i class="fa-solid fa-exclamation-triangle"></i> Lưu ý:</span>
              <div class="divider"></div>

              <div class="block-content">
                <div class="alert" role="alert">
                  <h4 class="alert-heading text-warning">Không đọc mất tiền admin không chịu trách nhiệm !!!</h4>
                  <p>Số tiền chuyển tối thiểu: <span class="text-danger fw-bold"><?= number_format($min_transfer) ?></span> VNĐ</p>
                  <p>Số tiền chuyển tối đa: <span class="text-danger fw-bold"><?= number_format($max_transfer) ?></span> VNĐ</p>
                  <p>Có thể chuyển tiền bằng: <span class="fw-bold">email, số điện thoại, biệt danh</span>.</p>
                  <hr>
                  <p class="mb-0">Chuyển tiền sẽ không thể hoàn lại, hãy kiểm tra kỹ thông tin trước khi chuyển tiền.</p>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6">
      <div class="row g-32">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <span class="h3 d-block mb-8">Chuyển tiền</span>
              <div class="divider"></div>

              <div class="block-content" bis_skin_checked="1">
                <div class="form-group mb-16" bis_skin_checked="1">
                  <label>Email hoặc số điện thoại người nhận:</label><br />
                  <div class="input-number w-100">
                    <div class="input-number-input-wrap">
                      <input class="input-number-input" id="transfer_email" type="text" placeholder="Email hoặc số điện thoại người nhận" />
                    </div>
                  </div>
                </div>

                <div class="form-group" bis_skin_checked="1">
                  <button class="btn btn-hero-danger btn-block" id="transfer_send">
                    TIẾP TỤC
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php
  if (!empty($history_transfer)) {
  ?>
    <div class="row mb-32 gy-32">
      <div class="col-12">
        <div class="row g-32">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <span class="h3 d-block mb-8">Lịch sử giao dịch</span>
                <div class="divider"></div>

                <div class="block-content" bis_skin_checked="1">
                  <div class="table-responsive" bis_skin_checked="1">

                    <table id="data-table_history-transfer" class="table table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th class="text-nowrap">Mã đơn</th>
                          <th class="text-nowrap">Số tiền</th>
                          <th class="text-nowrap">Người nhận</th>
                          <th class="text-nowrap">Nội dung</th>
                          <th class="text-nowrap">Thời gian</th>
                          <th class="text-nowrap">Hành động</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($history_transfer as $value) {
                          $transfer_description = empty($value['transfer_description']) ? ' - ' : $value['transfer_description'];
                        ?>
                          <tr>
                            <td class="text-nowrap"><?= $value['transfer_code'] ?></td>
                            <td class="text-nowrap">
                              <span class="text-danger"><?= number_format($value['transfer_cash']) ?></span>
                            </td>
                            <td class="text-nowrap"><?= getEmailUser($value['transfer_user_to']) ?></td>
                            <td class="text-nowrap"><?= $transfer_description ?></td>
                            <td class="text-nowrap"><?= $value['created_at'] ?></td>
                            <td class="text-nowrap">
                              <!-- Xem chi tiết -->
                              <a href="<?= getDomain() . "/details/transfer/" . $value['transfer_code'] ?>" type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem chi tiết">
                                <i class="fa-solid fa-eye"></i>
                              </a>
                            </td>
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
  }
  ?>
</div>
<?php
// Footer
require('../../layout/client/footer.php');
?>
<script>
  $('#transfer_send').click(function() {
    Swal.fire({
      title: '',
      text: "Đã xem kỹ thông tin chưa? tiếp tục nhé!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Tôi đã kiểm tra thông tin!',
      onOpen: () => {
        Swal.showLoading();
        const confirmButton = Swal.getConfirmButton();
        confirmButton.disabled = true;
        setTimeout(() => {
          confirmButton.disabled = false;
          Swal.hideLoading();
        }, 500);
      }
    }).then((result) => {
      if (result.isConfirmed) {
        var data = {
          transfer_email: $('#transfer_email').val()
        };

        $.post('./ajaxs/main/client/transferCheck.php', {
          data: JSON.stringify(data)
        }, function(response) {
          var result = JSON.parse(response);
          var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
          if (result.success) {
            window.location.href = "<?= getDomain() ?>/details/transferUser/" + $('#transfer_email').val();
          } else {
            Swal.fire('', result.message + dataMessage, 'error');
          }
        });
      }
    })
  });


  new DataTable('#data-table_history-transfer', {
    order: [
      [4, 'desc']
    ],
    columnDefs: [{
      targets: 4,
      type: 'date',
      render: function(data, type, row) {
        return moment(data).format('YYYY-MM-DD HH:mm:ss');
      }
    }]
  });
</script>