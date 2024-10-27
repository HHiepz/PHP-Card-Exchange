<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// Lấy dữ liệu từ cơ sở dữ liệu
$webhook_withdraw_profit = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_withdraw_profit'");
$bank_code_withdraw_profit = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'bank_code_withdraw_profit'");
$account_number_withdraw_profit = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'account_number_withdraw_profit'");
$account_owner_withdraw_profit = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'account_owner_withdraw_profit'");
$role_withdraw_profit = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'role_withdraw_profit'");

// Header
$title_website = 'Cài đặt rút tiền cuối tháng';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
  <div class="row mb-32 gy-32">
    <div class="col-12">
      <div class="row justify-content-between gy-32">
        <div class="col hp-flex-none w-auto">
          <h1 class="hp-mb-0 text-4xl font-bold"><?= $title_website ?></h1>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row g-24">
            <div class="col-12 col-md-6">
              <label for="webhook_withdraw_profit" class="form-label">Webhook Discord <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="webhook_withdraw_profit" placeholder="Nhập webhook Discord để gửi thông báo rút tiền" value="<?= $webhook_withdraw_profit ?>">
              <small class="text-muted">Webhook này sẽ được sử dụng để gửi thông báo về Discord khi có yêu cầu rút tiền</small>
            </div>
            <div class="col-12 col-md-6">
              <label for="bank_code_withdraw_profit" class="form-label">Mã ngân hàng <span class="text-danger">*</span></label>
              <select class="form-select" id="bank_code_withdraw_profit">
                <?php
                $banks = list_bank();
                foreach ($banks as $bank) {
                  $selected = ($bank['codeDB'] == $bank_code_withdraw_profit) ? 'selected' : '';
                  echo "<option value='{$bank['codeDB']}' {$selected}>{$bank['name']}</option>";
                }
                ?>
              </select>
              <small class="text-muted">Chọn ngân hàng mà bạn muốn nhận tiền rút</small>
            </div>
            <div class="col-12 col-md-6">
              <label for="account_number_withdraw_profit" class="form-label">Số tài khoản <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="account_number_withdraw_profit" placeholder="Nhập số tài khoản ngân hàng của bạn" value="<?= $account_number_withdraw_profit ?>">
              <small class="text-muted">Đây là số tài khoản mà tiền sẽ được chuyển đến khi rút</small>
            </div>
            <div class="col-12 col-md-6">
              <label for="account_owner_withdraw_profit" class="form-label">Chủ tài khoản <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="account_owner_withdraw_profit" placeholder="Nhập tên chủ tài khoản ngân hàng" value="<?= $account_owner_withdraw_profit ?>">
              <small class="text-muted">Tên chủ tài khoản phải khớp với thông tin ngân hàng của bạn</small>
            </div>
            <div class="col-12">
              <label for="role_withdraw_profit" class="form-label">Vai trò được phép rút</label>
              <input type="text" class="form-control" id="role_withdraw_profit" placeholder="Nhập vai trò được phép rút (cách nhau bằng dấu phẩy, ví dụ: admin,moderator)" value="<?= $role_withdraw_profit ?>">
              <small class="text-muted">role_id của discord, ví dụ: 123456789012345678</small>
            </div>
            <div class="col-12">
              <button type="button" class="btn btn-primary" id="save_settings">Lưu cài đặt</button>
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
  $('#save_settings').click(function() {
    var data = {
      webhook_withdraw_profit: $('#webhook_withdraw_profit').val(),
      bank_code_withdraw_profit: $('#bank_code_withdraw_profit').val(),
      account_number_withdraw_profit: $('#account_number_withdraw_profit').val(),
      account_owner_withdraw_profit: $('#account_owner_withdraw_profit').val(),
      role_withdraw_profit: $('#role_withdraw_profit').val(),
      save_settings: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWithdrawProfit.php', {
      data: JSON.stringify(data)
    }, function(response) {
      var result = JSON.parse(response);
      if (result.success) {
        Swal.fire({
          title: '',
          text: result.message,
          icon: 'success',
          confirmButtonText: 'OK'
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      } else {
        Swal.fire('', result.message, 'error');
      }
    });
  });
</script>