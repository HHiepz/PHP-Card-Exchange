<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');
// ================= THÔNG BÁO DISCORD ====================
$exchange_noti_disc       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_exchange_card'");
$money_noti_disc          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_money'");
$login_noti_disc          = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_login'");
$register_noti_disc       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_register'");
$withdraw_noti_disc       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_withdraw'");
$min_telco_rare_noti_disc = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_min_telco_rare'");
$tongKet_noti_disc        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_tongKet'");
$backup_noti_disc         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'webhook_backup'");

// ================= THÔNG BÁO EMAIL ====================
$email_name = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'email'");
$email_pass = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'email_password'");

// ================= THÔNG BÁO HIỂN THỊ ====================
$index_noti    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'noti_index'");
$withdraw_noti = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'noti_withdraw'");

// Header
$title_website = 'Cài đặt thẻ đổi';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">

  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Note Admin!</strong> Bỏ trống tương đương với tắt chức năng</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>

  <div class="col-12 mb-16">
    <div class="row g-16">
      <div class="col-12 col-xxl-6">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">CÀI ĐẶT THÔNG BÁO DISCORD</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Đổi thẻ</span>
                        </div>
                        <input type="text" class="form-control" id="exchange_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $exchange_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="exchange_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Dòng tiền</span>
                        </div>
                        <input type="text" class="form-control" id="money_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $money_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="money_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Đăng nhập</span>
                        </div>
                        <input type="text" class="form-control" id="login_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $login_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="login_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Đăng ký</span>
                        </div>
                        <input type="text" class="form-control" id="register_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $register_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="register_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Rút tiền</span>
                        </div>
                        <input type="text" class="form-control" id="withdraw_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $withdraw_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="withdraw_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Chiết khấu thấp nhất</span>
                        </div>
                        <input type="text" class="form-control" id="min_telco_rare_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $min_telco_rare_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="min_telco_rare_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Tổng kết cuối ngày</span>
                        </div>
                        <input type="text" class="form-control" id="tongKet_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $tongKet_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="tongKet_noti_disc">
                        Lưu
                      </button>
                    </div>

                    <div class="col-12 col-md-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">Backup dữ liệu cũ hơn 2 tháng</span>
                        </div>
                        <input type="text" class="form-control" id="backup_noti_disc_value" placeholder=" https://example.com/xxx " value="<?= $backup_noti_disc ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="backup_noti_disc">
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
                <h3 class="block-title text-center">CÀI ĐẶT THÔNG BÁO EMAIL</h3>
                <div class="divider"></div>
              </div>

              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">
                    <div class="col-12">
                      <label for="pass">Tên email:</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="email_noti_name" placeholder="hotrocard2k.com" value="<?= $email_name ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass">Mật khẩu ứng dụng:</label>
                      <div class="input-group mb-3">
                        <input type="password" class="form-control" id="email_noti_pass" placeholder="xxxx-xxxx-xxxx-xxxx" value="<?= $email_pass ?>">
                      </div>
                    </div>
                    <div class="col-12 col-md-1">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="email_noti">
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
                <h3 class="block-title text-center">THÔNG BÁO HIỂN THỊ</h3>
                <div class="divider"></div>
              </div>

              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">
                    <div class="col-12">
                      <label for="pass">Thông báo hiển thị đầu khi truy cập <span class="fw-bold text-warning">(TRANG CHỦ)</span> :</label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="index_noti_value" rows="3">
                          <?= $index_noti ?>
                        </textarea>
                      </div>
                    </div>
                    <div class="col-12 col-md-1">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="index_noti">
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
                <h3 class="block-title text-center">THÔNG BÁO HIỂN THỊ</h3>
                <div class="divider"></div>
              </div>

              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">
                    <div class="col-12">
                      <label for="pass">Thông báo hiển thị đầu khi truy cập <span class="fw-bold text-warning">(RÚT TIỀN)</span> :</label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="withdraw_noti_value" rows="3">
                            <?= $withdraw_noti ?>
                        </textarea>
                      </div>
                    </div>
                    <div class="col-12 col-md-1">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="withdraw_noti">
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
  $('#exchange_noti_disc').click(function() {
    var data = {
      exchange_noti_disc_value: $('#exchange_noti_disc_value').val(),
      exchange_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#money_noti_disc').click(function() {
    var data = {
      money_noti_disc_value: $('#money_noti_disc_value').val(),
      money_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#login_noti_disc').click(function() {
    var data = {
      login_noti_disc_value: $('#login_noti_disc_value').val(),
      login_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#register_noti_disc').click(function() {
    var data = {
      register_noti_disc_value: $('#register_noti_disc_value').val(),
      register_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#withdraw_noti_disc').click(function() {
    var data = {
      withdraw_noti_disc_value: $('#withdraw_noti_disc_value').val(),
      withdraw_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#min_telco_rare_noti_disc').click(function() {
    var data = {
      min_telco_rare_noti_disc_value: $('#min_telco_rare_noti_disc_value').val(),
      min_telco_rare_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#tongKet_noti_disc').click(function() {
    var data = {
      tongKet_noti_disc_value: $('#tongKet_noti_disc_value').val(),
      tongKet_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#backup_noti_disc').click(function() {
    var data = {
      backup_noti_disc_value: $('#backup_noti_disc_value').val(),
      backup_noti_disc: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#email_noti').click(function() {
    var data = {
      email_name: $('#email_noti_name').val(),
      email_pass: $('#email_noti_pass').val(),
      email_noti: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#index_noti').click(function() {
    var data = {
      index_noti_value: $('#index_noti_value').val(),
      index_noti: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#withdraw_noti').click(function() {
    var data = {
      withdraw_noti_value: $('#withdraw_noti_value').val(),
      withdraw_noti: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingNotification.php', {
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
            window.location.href = "<?= "/admin/administrator/settingNotification" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });
</script>