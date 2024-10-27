<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// =================== RÚT TIỀN ===================
$withdraw_max = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'max_withdraw'");
$withdraw_min = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'min_withdraw'");
$withdraw_fee = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'fee_withdraw'");
$withdraw_api = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_withdraw'");
$withdraw_momo_limit = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'momo_limit'");

// =================== CHUYỂN TIỀN ===================
$transfer_max = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'max_transfer'");
$transfer_min = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'min_transfer'");


// Header
$title_website = 'Cài đặt thẻ đổi';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">

  <div class="col-12 mb-16">
    <div class="row g-16">
      <div class="col-12 col-xxl-6">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">CÀI ĐẶT RÚT TIỀN</h3>
                <div class="divider"></div>
              </div>

              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">
                    <div class="col-12">
                      <label for="pass">Rút tối đa:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="withdraw_max" placeholder="Rút tối đa" value="<?= number_format($withdraw_max) ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass">Rút tối thiểu:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="withdraw_min" placeholder="Rút tối thiểu" value="<?= number_format($withdraw_min) ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass">Phí rút:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="withdraw_fee" placeholder="Phí rút" value="<?= number_format($withdraw_fee) ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass">Rút <span class="text-danger">MOMO</span> tối đa trong ngày:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="withdraw_momo_limit" placeholder="Rút tối đa trong ngày" value="<?= number_format($withdraw_momo_limit) ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass"><span class="text-danger">API-KEY-BANK</span> (do partner cung cấp):</label>
                      <div class="input-group mb-3">
                        <input type="password" class="form-control" id="withdraw_api" placeholder="API Key" value="<?= $withdraw_api ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-1">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="withdraw">
                        Lưu
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-xxl-6">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">CÀI ĐẶT CHUYỂN TIỀN</h3>
                <div class="divider"></div>
              </div>

              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">
                    <div class="col-12">
                      <label for="pass">Chuyển tối đa:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="transfer_max" placeholder="Chuyển tối đa" value="<?= number_format($transfer_max) ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass">Chuyển tối thiểu:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="transfer_min" placeholder="Chuyển tối thiểu" value="<?= number_format($transfer_min) ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-1">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="transfer">
                        Lưu
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
  </div>

</div>

<?php
require('../../../layout/admin/footer.php');
?>
<script>
  // Rút tiền
  $('#withdraw').click(function() {
    var data = {
      withdraw_max: $('#withdraw_max').val(),
      withdraw_min: $('#withdraw_min').val(),
      withdraw_fee: $('#withdraw_fee').val(),
      withdraw_api: $('#withdraw_api').val(),
      withdraw_momo_limit: $('#withdraw_momo_limit').val()
    };

    $.post('../../../../ajaxs/admin/administrator/settingWithdraw.php', {
      data: JSON.stringify(data)
    }, function(response) {
      var result = JSON.parse(response);
      var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
      if (result.success) {
        Swal.fire({
          title: '',
          text: result.message + dataMessage,
          icon: 'success',
          willClose: function() {
            window.location.href = "<?= "/admin/administrator/settingMoney" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Chuyển tiền
  $('#transfer').click(function() {
    var data = {
      transfer_max: $('#transfer_max').val(),
      transfer_min: $('#transfer_min').val(),
    };

    $.post('../../../../ajaxs/admin/administrator/settingTransfer.php', {
      data: JSON.stringify(data)
    }, function(response) {
      var result = JSON.parse(response);
      var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
      if (result.success) {
        Swal.fire({
          title: '',
          text: result.message + dataMessage,
          icon: 'success',
          willClose: function() {
            window.location.href = "<?= "/admin/administrator/settingMoney" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });
</script>