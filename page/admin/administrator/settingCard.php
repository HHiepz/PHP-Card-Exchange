<?php
require('../../../core/database.php');
require('../../../core/function.php');

// Kiểm tra quyền
checkToken('admin');

// ========================= ĐỔI THẺ =========================
$rate_member = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_member'");
$rate_vip    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_vip'");
$rate_agency = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'exchange_card_rare_agency'");

$partner_server = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name'");
$partner_id     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id'");
$partner_key    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key'");
$wallet         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'wallet_exCard'");

// ========================= MUA THẺ =========================
$buyCard_viettel     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_viettel'");
$buyCard_vinaphone   = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_vina'");
$buyCard_mobifone    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_mobi'");
$buyCard_vietnamobile = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_vnmobile'");
$buyCard_garena      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_garena'");
$buyCard_gate        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_gate'");
$buyCard_vcoin       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_vcoin'");
$buyCard_zing        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_zing'");
$buyCard_gmobile     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_gmobile'");
$buyCard_appota      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_appota'");
$buyCard_carot       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_carot'");
$buyCard_funcard     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_funcard'");
$buyCard_scoin       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_scoin'");
$buyCard_gosu        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_gosu'");
$buyCard_sohacoin    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_sohacoin'");
$buyCard_oncash      = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_oncash'");
$buyCard_bit         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_bitvn'");
$buyCard_anpay       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_anpay'");
$buyCard_kul         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_kul'");
$buyCard_vega        = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_vega'");
$buyCard_kcong       = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_kcong'");
$buyCard_vga         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_vga'");
$buyCard_kis         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'buyCard_rare_kis'");

$partner_server_buyCard = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_server_name_buyCard'");
$partner_id_buyCard     = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_id_buyCard'");
$partner_key_buyCard    = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'partner_key_buyCard'");
$wallet_buyCard         = pdo_query_value("SELECT `value` FROM `setting` WHERE `name` = 'wallet_buyCard'");


// Header
$title_website = 'Cài đặt thẻ đổi';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
  <div class="row mb-32 g-32">

    <div class="col-12 mb-16">
      <div class="row g-16">
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title text-center">CÀI ĐẶT THẺ ĐỔI</h3>
                  <div class="divider"></div>
                </div>

                <div class="block-content">
                  <!-- Cập nhật chiết khấu -->
                  <div class="mb-16">
                    <label for="pass">Chiết khấu:</label>
                    <div class="form-group w-100 row g-16">
                      <!-- Member -->
                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><?= $rate_member ?>%</span>
                          </div>
                          <input type="text" class="form-control" id="updateCardRate_member_rate" placeholder="Nhập % chiết khấu mới">
                        </div>
                      </div>
                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="updateCardRate_member">
                          MEMBER
                        </button>
                      </div>

                      <!-- VIP -->
                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><?= $rate_vip ?>%</span>
                          </div>
                          <input type="text" class="form-control" id="updateCardRate_vip_rate" placeholder="Nhập % chiết khấu mới">
                        </div>
                      </div>
                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-warning border-warning hp-hover-text-color-warning-2 hp-hover-border-color-warning-2" id="updateCardRate_vip">
                          VIP
                        </button>
                      </div>

                      <!-- ĐẠI LÝ / API -->
                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><?= $rate_agency ?>%</span>
                          </div>
                          <input type="text" class="form-control" id="updateCardRate_agency_rate" placeholder="Nhập % chiết khấu mới">
                        </div>
                      </div>
                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-danger border-danger hp-hover-text-color-danger-2 hp-hover-border-color-danger-2" id="updateCardRate_agency">
                          ĐẠI LÝ
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Nhà cung cấp -->
                  <div class="mb-16">
                    <label for="pass">Nhà cung cấp:</label>
                    <div class="form-group w-100 row g-16">
                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Tên miền</span>
                          </div>
                          <input type="password" class="form-control" id="updatePartnerCard_serverName" placeholder=" Card2k.com " value="<?= $partner_server ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Partner ID</span>
                          </div>
                          <input type="password" class="form-control" id="updatePartnerCard_partnerId" placeholder=" 0123456.. " value="<?= $partner_id ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Partner Key</span>
                          </div>
                          <input type="password" class="form-control" id="updatePartnerCard_partnerKey" placeholder=" ABCXYZ123456.." value="<?= $partner_key ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-2">
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Ví</span>
                          </div>
                          <input type="password" class="form-control" id="updatePartnerCard_wallet" placeholder="Nhập ví" value="<?= $wallet ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="updatePartnerCard">
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

    <div class="col-12 mb-16">
      <div class="row g-16">
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title text-center">CÀI ĐẶT MUA THẺ</h3>
                  <div class="divider"></div>
                </div>

                <div class="block-content">
                  <!-- Cập nhật chiết khấu -->
                  <div class="mb-16">
                    <label for="pass">Chiết khấu:</label>
                    <div class="form-group w-100 row g-16">
                      <!-- BuyCard: Phone -->
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">VIETTEL</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_viettel" value="<?= $buyCard_viettel ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">VINAPHONE</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_vinaphone" value="<?= $buyCard_vinaphone ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">MOBIFONE</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_mobifone" value="<?= $buyCard_mobifone ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">VIETNAMOBILE</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_vietnamobile" value="<?= $buyCard_vietnamobile ?>" placeholder="0.0">
                        </div>
                      </div>
                      <!-- BuyCard: Game -->
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">GARENA</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_garena" value="<?= $buyCard_garena ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">GATE</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_gate" value="<?= $buyCard_gate ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">VCOIN</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_vcoin" value="<?= $buyCard_vcoin ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">ZING</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_zing" value="<?= $buyCard_zing ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">GMOBILE</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_gmobile" value="<?= $buyCard_gmobile ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">APPOTA</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_appota" value="<?= $buyCard_appota ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">CAROT</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_carot" value="<?= $buyCard_carot ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">FUNCARD</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_funcard" value="<?= $buyCard_funcard ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">SCOIN</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_scoin" value="<?= $buyCard_scoin ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">GOSU</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_gosu" value="<?= $buyCard_gosu ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">SOHACOIN</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_sohacoin" value="<?= $buyCard_sohacoin ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">ONCASH</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_oncash" value="<?= $buyCard_oncash ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">BIT</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_bit" value="<?= $buyCard_bit ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">ANPAY</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_anpay" value="<?= $buyCard_anpay ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">KUL</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_kul" value="<?= $buyCard_kul ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">VEGA</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_vega" value="<?= $buyCard_vega ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">K+</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_kcong" value="<?= $buyCard_kcong ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">AVG</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_vga" value="<?= $buyCard_vga ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12 col-sm-4 col-xxl-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">KASPERSKY</span>
                          </div>
                          <input type="text" class="form-control" id="updateBuyCardRate_kis" value="<?= $buyCard_kis ?>" placeholder="0.0">
                        </div>
                      </div>
                      <div class="col-12">
                        <button type="button" class="w-100 btn btn-dashed text-warning border-warning hp-hover-text-color-warning-2 hp-hover-border-color-warning-2" id="updateBuyCardRate">
                          Lưu
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Nhà cung cấp -->
                  <div class="mb-16">
                    <label for="pass">Nhà cung cấp:</label>
                    <div class="form-group w-100 row g-16">
                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Tên miền</span>
                          </div>
                          <input type="password" class="form-control" id="updateBuyCard_partnerServer" placeholder=" Card2k.com " value="<?= $partner_server_buyCard ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Partner ID</span>
                          </div>
                          <input type="password" class="form-control" id="updateBuyCard_partnerId" placeholder=" 0123456.. " value="<?= $partner_id_buyCard ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Partner Key</span>
                          </div>
                          <input type="password" class="form-control" id="updateBuyCard_partnerKey" placeholder=" ABCXYZ123456.." value="<?= $partner_key_buyCard ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-2">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Wallet</span>
                          </div>
                          <input type="password" class="form-control" id="updateBuyCard_wallet" placeholder=" Mã số ví.." value="<?= $wallet_buyCard ?>">
                        </div>
                      </div>

                      <div class="col-12 col-md-1">
                        <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="updateBuyCard">
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
  // Cập nhật chiết khấu MEMBER
  $('#updateCardRate_member').click(function() {
    var data = {
      updateCardRate_member_rate: $('#updateCardRate_member_rate').val(),
      updateCardRate_member: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingCard.php', {
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
            window.location.href = "<?= "/admin/administrator/settingCard" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Cập nhật chiết khấu VIP
  $('#updateCardRate_vip').click(function() {
    var data = {
      updateCardRate_vip_rate: $('#updateCardRate_vip_rate').val(),
      updateCardRate_vip: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingCard.php', {
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
            window.location.href = "<?= "/admin/administrator/settingCard" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Cập nhật chiết khấu AGENCY
  $('#updateCardRate_agency').click(function() {
    var data = {
      updateCardRate_agency_rate: $('#updateCardRate_agency_rate').val(),
      updateCardRate_agency: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingCard.php', {
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
            window.location.href = "<?= "/admin/administrator/settingCard" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Thay đổi thông tin nhà cung cấp
  $('#updatePartnerCard').click(function() {
    var data = {
      updatePartnerCard_serverName: $('#updatePartnerCard_serverName').val(),
      updatePartnerCard_partnerId: $('#updatePartnerCard_partnerId').val(),
      updatePartnerCard_partnerKey: $('#updatePartnerCard_partnerKey').val(),
      updatePartnerCard_wallet: $('#updatePartnerCard_wallet').val(),
      updatePartnerCard: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingCard.php', {
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
            window.location.href = "<?= "/admin/administrator/settingCard" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Cập nhật chiết khấu MUA THẺ
  $('#updateBuyCardRate').click(function() {
    var data = {
      updateBuyCardRate_viettel: $('#updateBuyCardRate_viettel').val(),
      updateBuyCardRate_vinaphone: $('#updateBuyCardRate_vinaphone').val(),
      updateBuyCardRate_mobifone: $('#updateBuyCardRate_mobifone').val(),
      updateBuyCardRate_vietnamobile: $('#updateBuyCardRate_vietnamobile').val(),
      updateBuyCardRate_garena: $('#updateBuyCardRate_garena').val(),
      updateBuyCardRate_gate: $('#updateBuyCardRate_gate').val(),
      updateBuyCardRate_vcoin: $('#updateBuyCardRate_vcoin').val(),
      updateBuyCardRate_zing: $('#updateBuyCardRate_zing').val(),
      updateBuyCardRate_gmobile: $('#updateBuyCardRate_gmobile').val(),
      updateBuyCardRate_appota: $('#updateBuyCardRate_appota').val(),
      updateBuyCardRate_carot: $('#updateBuyCardRate_carot').val(),
      updateBuyCardRate_funcard: $('#updateBuyCardRate_funcard').val(),
      updateBuyCardRate_scoin: $('#updateBuyCardRate_scoin').val(),
      updateBuyCardRate_gosu: $('#updateBuyCardRate_gosu').val(),
      updateBuyCardRate_sohacoin: $('#updateBuyCardRate_sohacoin').val(),
      updateBuyCardRate_oncash: $('#updateBuyCardRate_oncash').val(),
      updateBuyCardRate_bit: $('#updateBuyCardRate_bit').val(),
      updateBuyCardRate_anpay: $('#updateBuyCardRate_anpay').val(),
      updateBuyCardRate_kul: $('#updateBuyCardRate_kul').val(),
      updateBuyCardRate_vega: $('#updateBuyCardRate_vega').val(),
      updateBuyCardRate_kcong: $('#updateBuyCardRate_kcong').val(),
      updateBuyCardRate_vga: $('#updateBuyCardRate_vga').val(),
      updateBuyCardRate_kis: $('#updateBuyCardRate_kis').val(),
      updateBuyCardRate: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingCard.php', {
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
            window.location.href = "<?= "/admin/administrator/settingCard" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Thay đổi thông tin nhà cung cấp MUA THẺ
  $('#updateBuyCard').click(function() {
    var data = {
      updateBuyCard_partnerServer: $('#updateBuyCard_partnerServer').val(),
      updateBuyCard_partnerId: $('#updateBuyCard_partnerId').val(),
      updateBuyCard_partnerKey: $('#updateBuyCard_partnerKey').val(),
      updateBuyCard_wallet: $('#updateBuyCard_wallet').val(),
      updateBuyCard: true
    };

    $.post('../../../../ajaxs/admin/administrator/settingCard.php', {
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
            window.location.href = "<?= "/admin/administrator/settingCard" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });
</script>