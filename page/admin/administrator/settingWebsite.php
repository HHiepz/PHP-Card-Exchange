<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');
// ================= GGOLE SEO ====================
$google_favicon      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_favicon'");
$google_search       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_search'");
$google_description  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_description'");
$google_analytic     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_analytic'");

$website_logo_light  = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_logo_light'");
$website_logo_dark   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'google_logo_dark'");

$facebook_message    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'facebook_message'");

$dmca_meta_verify    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'dmca_meta_verify'");
$dmca_link           = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'dmca_link'");

$header_script       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'header_script'");
$footer_script       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_script'");

$footer_support      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_support'");
$footer_facebook     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_facebook'");
$footer_discord      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_discord'");
$footer_telegram     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_telegram'");
$footer_youtube      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'footer_youtube'");

// Header
$title_website = 'Cài đặt thẻ đổi';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">

  <div class="col-12 mb-16">
    <div class="row g-36">

      <!-- Google SEO -->
      <div class="col-12 ">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">GOOGLE <span class="text-warning">SEO</span></h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <label class="form-label-2">Favicon</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="google_favicon" placeholder=" - " value="<?= $google_favicon ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Script Google Analytic</label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="google_analytic" rows="3"><?= $google_analytic ?></textarea>
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Từ khóa: (mỗi từ cách nhau bởi <strong>dấu phẩy</strong>)</label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="google_search" rows="3"><?= $google_search ?></textarea>
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Mô tả:</label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="google_description" rows="3"><?= $google_description ?></textarea>
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="google_seo">
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

      <!-- WEBSITE -->
      <div class="col-12 ">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center text-info">WEBSITE</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <label class="form-label-2">Logo giao diện SÁNG (upload ảnh và lấy link <a href="https://i.ibb.co/">tại đây</a>)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_logo_light" placeholder=" - " value="<?= $website_logo_light ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Logo giao diện TỐI (upload ảnh và lấy link <a href="https://i.ibb.co/">tại đây</a>)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_logo_dark" placeholder=" - " value="<?= $website_logo_dark ?>" />
                      </div>
                    </div>

                    <div class="mt-16 mb-16"></div>

                    <div class="col-12">
                      <label class="form-label-2">Link HỖ TRỢ CHÍNH: (Hiển thị footer)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_support" placeholder=" - " value="<?= $footer_support ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Link YOUTUBE: (Hiển thị footer)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_youtube" placeholder=" - " value="<?= $footer_youtube ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Link TELEGRAM: (Hiển thị footer)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_telegram" placeholder=" - " value="<?= $footer_telegram ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Link FACEBOOK: (Hiển thị footer)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_facebook" placeholder=" - " value="<?= $footer_facebook ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Link DISCORD: (Hiển thị footer)</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="website_discord" placeholder=" - " value="<?= $footer_discord ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="website">
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

      <!-- FACEBOOK -->
      <div class="col-12 ">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center text-info">FACEBOOK</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <label class="form-label-2">Script Message</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="facebook_message" placeholder=" - " value="<?= $facebook_message ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="facebook">
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

      <!-- DMCA -->
      <div class="col-12 ">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center text-success">DMCA</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <label class="form-label-2">Script dmca</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="dmca_meta_verify" placeholder=" - " value="<?= $dmca_meta_verify ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <label class="form-label-2">Link dmca</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="dmca_link" placeholder=" - " value="<?= $dmca_link ?>" />
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="dmca">
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

      <!-- HEADER -->
      <div class="col-12">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">EXTENSION <span class="text-warning">HEADER</span></h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <label class="form-label-2">Script mở rộng </label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="header_script" rows="3"><?= $header_script ?></textarea>
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="header">
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

      <!-- FOOTER -->
      <div class="col-12">
        <div class="card h-auto">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">EXTENSION <span class="text-warning">FOOTER</span></h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <div class="form-group w-100 row g-16">

                    <div class="col-12">
                      <label class="form-label-2">Script mở rộng </label>
                      <div class="input-group mb-3">
                        <textarea class="form-control" id="footer_script" rows="3"><?= $footer_script ?></textarea>
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="footer">
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
  $('#google_seo').click(function() {
    var data = {
      google_favicon: $('#google_favicon').val(),
      google_analytic: $('#google_analytic').val(),
      google_search: $('#google_search').val(),
      google_description: $('#google_description').val(),
      google_seo: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWebsite.php', {
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
            window.location.href = "<?= "/admin/administrator/settingWebsite" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#website').click(function() {
    var data = {
      website_logo_light: $('#website_logo_light').val(),
      website_logo_dark: $('#website_logo_dark').val(),
      website_support: $('#website_support').val(),
      website_youtube: $('#website_youtube').val(),
      website_telegram: $('#website_telegram').val(),
      website_facebook: $('#website_facebook').val(),
      website_discord: $('#website_discord').val(),
      website: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWebsite.php', {
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
            window.location.href = "<?= "/admin/administrator/settingWebsite" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#facebook').click(function() {
    var data = {
      facebook_message: $('#facebook_message').val(),
      facebook: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWebsite.php', {
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
            window.location.href = "<?= "/admin/administrator/settingWebsite" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#dmca').click(function() {
    var data = {
      dmca_meta_verify: $('#dmca_meta_verify').val(),
      dmca_link: $('#dmca_link').val(),
      dmca: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWebsite.php', {
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
            window.location.href = "<?= "/admin/administrator/settingWebsite" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#header').click(function() {
    var data = {
      header_script: $('#header_script').val(),
      header: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWebsite.php', {
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
            window.location.href = "<?= "/admin/administrator/settingWebsite" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  $('#footer').click(function() {
    var data = {
      footer_script: $('#footer_script').val(),
      footer: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingWebsite.php', {
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
            window.location.href = "<?= "/admin/administrator/settingWebsite" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });
</script>