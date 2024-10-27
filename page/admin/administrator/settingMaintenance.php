<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');
// ================= CHỨC NĂNG ====================
$doithe     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_exchange_card'");
$muathe     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_buyCard'");
$ruttien    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_withdraw'");
$chuyentien = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_transfer'");

$server = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_server'");

$verify_email = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'register_verify_email'");
$verify_ip    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'register_verify_ip'");

$ggRecaptcha_status = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'status_ggReCaptcha'");
$ggRecaptcha_siteKey = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'ggReCaptcha_site_key'");
$ggRecaptcha_secretKey = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'ggReCaptcha_secret_key'");
// Header
$title_website = 'Cài đặt bảo trì chức năng';
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
                <h3 class="block-title text-center">BẢNG 01</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Đổi thẻ cào</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="baotri_doithe" <?= ($doithe == 1) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="baotri_doithe"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Mua thẻ cào</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="baotri_muathe" <?= ($muathe == 1) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="baotri_muathe"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Rút tiền</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="baotri_ruttien" <?= ($ruttien == 1) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="baotri_ruttien"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Chuyển tiền</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="baotri_chuyentien" <?= ($chuyentien == 1) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="baotri_chuyentien"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">

                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="doithe_muathe">
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

      <div class="col-12 col-xxl-6">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">TOÀN SERVER</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Toàn server (chỉ admin đăng nhập được)</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="baotri_server" <?= ($server == 1) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="baotri_server"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="server">
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

      <div class="col-12 col-xxl-6">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">CHỨC NĂNG</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Xác thực email đăng ký</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="chucnang_registerVerifyEmail" <?= ($verify_email == 0) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="chucnang_registerVerifyEmail"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Xác thực IP đăng nhập/đăng ký</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="chucnang_registerVerifyIp" <?= ($verify_ip == 0) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="chucnang_registerVerifyIp"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="chucnang">
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


      <div class="col-12 col-xxl-6">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">GOOGLE <span class="text-warning">RECAPTCHA</span> </h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <div class="row g-3 align-items-center">
                        <div class="col-10">
                          <div class="input-group">
                            <span class="input-group-text w-100" id="basic-addon1">Xác thực Google Recaptcha</span>
                          </div>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ggRecaptcha_status" <?= ($ggRecaptcha_status == 0) ? '' : 'checked' ?>>
                            <label class="form-check-label" for="ggRecaptcha_status"></label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="pass">Site-Key:</label>
                      <div class="input-group mb-3">
                        <input type="password" class="form-control" id="ggRecaptcha_siteKey" placeholder="-" value="<?= $ggRecaptcha_siteKey ?>">
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="pass">Secret-Key:</label>
                      <div class="input-group mb-3">
                        <input type="password" class="form-control" id="ggRecaptcha_secretKey" placeholder="-" value="<?= $ggRecaptcha_secretKey ?>">
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="ggRecaptcha">
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


</div>

<?php
require('../../../layout/admin/footer.php');
?>
<script>
  $('#doithe_muathe').click(function() {
    var baotri_doithe = $('#baotri_doithe').is(':checked');
    var baotri_muathe = $('#baotri_muathe').is(':checked');
    var baotri_ruttien = $('#baotri_ruttien').is(':checked');
    var baotri_chuyentien = $('#baotri_chuyentien').is(':checked');

    var data = {
      baotri_doithe: baotri_doithe,
      baotri_muathe: baotri_muathe,
      baotri_ruttien: baotri_ruttien,
      baotri_chuyentien: baotri_chuyentien,
      doithe_muathe: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingMaintenance.php', {
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
            window.location.href = "<?= "/admin/administrator/settingMaintenance" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });


  $('#server').click(function() {
    var baotri_server = $('#baotri_server').is(':checked');

    var data = {
      baotri_server: baotri_server,
      server: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingMaintenance.php', {
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
            window.location.href = "<?= "/admin/administrator/settingMaintenance" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });


  $('#chucnang').click(function() {
    var chucnang_registerVerifyEmail = $('#chucnang_registerVerifyEmail').is(':checked');
    var chucnang_registerVerifyIp = $('#chucnang_registerVerifyIp').is(':checked');

    var data = {
      chucnang_registerVerifyEmail: chucnang_registerVerifyEmail,
      chucnang_registerVerifyIp: chucnang_registerVerifyIp,
      chucnang: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingMaintenance.php', {
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
            window.location.href = "<?= "/admin/administrator/settingMaintenance" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#ggRecaptcha').click(function() {
    var ggRecaptcha_status = $('#ggRecaptcha_status').is(':checked');

    var data = {
      ggRecaptcha_status: ggRecaptcha_status,
      ggRecaptcha_siteKey: $('#ggRecaptcha_siteKey').val(),
      ggRecaptcha_secretKey: $('#ggRecaptcha_secretKey').val()
    };

    $.post('../../../../ajaxs/admin/administrator/googleRecaptcha.php', {
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
            window.location.href = "<?= "/admin/administrator/settingMaintenance" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });
</script>