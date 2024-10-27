<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken("client");

$user_id      = getIdUser();

// Danh sách API của người dùng
$list_partner = pdo_query("SELECT * FROM `partner` WHERE `user_id` = ? ORDER BY `id` DESC", [$user_id]);

// Header
$title_website = 'API dễ dàng giao dịch';
require('../../layout/client/header.php');
?>
<div class="hp-main-layout-content">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Thông báo!</strong> Các SHOP mới/cũ đấu nối API thành công <strong>tự động lên rank VIP hoặc Đại lý</strong> (nếu ổn định), <a href="https://discord.gg/Zd8M4A2PZH" target="_blank">liên hệ hỗ trợ</a> nếu bạn cần trợ giúp nối API tới web
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Hướng dẫn!</strong> Bấm vào đây để xem <a href="https://documenter.getpostman.com/view/27137333/2sA2xnw9Wg#f1a4a027-013e-470c-aed7-a76e9f612e61" target="_blank">tài liệu</a> hướng dẫn
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <div class="col-12 mb-32">
    <div class="row g-32">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <span class="h3 d-block mb-8">Thêm API dễ dàng giao dịch</span>
            <div class="divider"></div>

            <div class="block-content" bis_skin_checked="1">
              <div class="row">
                <div class="col-12 col-md-8">
                  <div class="form-group mb-16" bis_skin_checked="1">
                    <label>Chức năng:</label>
                    <!-- <input class="form-control form-control-alt" placeholder=" - " /> -->
                    <select class="form-control" id="api_type" bis_skin_checked="1">
                      <option disabled selected> - </option>
                      <option value="Charging">Đổi Thẻ</option>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="form-group mb-16" bis_skin_checked="1">
                    <label>Phương thức:</label>
                    <!-- <input class="form-control form-control-alt" placeholder=" - " /> -->
                    <select class="form-control" id="api_action" bis_skin_checked="1">
                      <option disabled selected> - </option>
                      <option value="GET">GET</option>
                      <option value="POST">POST</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group mb-16" bis_skin_checked="1">
                <label>Đường dẫn nhận dữ liệu (Callback Url):</label>
                <input class="form-control form-control-alt" id="api_callback" placeholder="https:// " />
              </div>
              <div class="form-group mb-16" bis_skin_checked="1">
                <label>Địa chỉ IP (nếu có):</label>
                <input class="form-control form-control-alt" id="api_ip" />
              </div>
              <div class="form-group mb-16" bis_skin_checked="1">
                <button type="submit" class="btn btn-primary" id="api_send">
                  Thêm thông tin kết nối
                </button>
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
            <span class="h3 d-block mb-8">Danh sách API</span>
            <div class="divider"></div>

            <div class="block-content" bis_skin_checked="1">
              <div style="margin-bottom: 10px" bis_skin_checked="1">
                <a href="https://documenter.getpostman.com/view/27137333/2sA2xnw9Wg#f1a4a027-013e-470c-aed7-a76e9f612e61">Tài liệu hướng dẫn API đổi thẻ cào</a>
              </div>
              <div class="table-responsive" bis_skin_checked="1">

                <table id="data-table_list-partner" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-nowrap">Chức năng</th>
                      <th class="text-nowrap">Thông tin kết nối</th>
                      <th class="text-nowrap">Phương thức</th>
                      <th class="text-nowrap">Trạng thái</th>
                      <th class="text-nowrap">Địa chỉ IP</th>
                      <th class="text-nowrap">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($list_partner as $value) {
                      $partner_ip = empty($value['partner_ip']) ? ' - ' : $value['partner_ip'];
                    ?>
                      <tr>
                        <td class="text-nowrap">
                          <small>
                            <?= $value['partner_type'] ?>
                          </small>
                        </td>
                        <td class="text-nowrap">
                          <small>Partner ID: <?= $value['partner_id'] ?></small>
                          <br />
                          <small>Partner Key: <?= $value['partner_key'] ?></small>
                        </td>
                        <td class="text-nowrap">
                          <span class="badge badge-<?= formatPartnerAction($value['partner_action'], "color") ?>"><?= $value['partner_action'] ?></span>
                          <br />
                          <small><?= $value['partner_callback'] ?></small>
                        </td>
                        <td class="text-nowrap">
                          <span class="badge badge-<?= formatPartnerStatus($value['partner_status'], "color") ?>"><?= formatPartnerStatus($value['partner_status']) ?></span>
                        </td>
                        <td class="text-nowrap">
                          <small><?= $partner_ip ?></small>
                        </td>
                        <td class="text-nowrap">
                          <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" onclick="getInfoPartner(<?= $value['partner_id'] ?>)" data-bs-target="#staticBackdrop">
                            Sửa
                          </button>
                          <a class="btn btn-danger btn-sm removePartner" data-partner-id="<?= $value['partner_id'] ?>">Xóa</a>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>

                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="text-nowrap">Chức năng</th>
                      <th class="text-nowrap">Thông tin kết nối</th>
                      <th class="text-nowrap">Phương thức</th>
                      <th class="text-nowrap">Lần cuối thực thi</th>
                      <th class="text-nowrap">Địa chỉ IP</th>
                      <th class="text-nowrap">Hành động</th>
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

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header flex-column align-items-start">
        <h1 class="modal-title fs-5 mb-3" id="staticBackdropLabel">Chỉnh sửa</h1>
        <div>
          <p class="mt-2"></p>
          <p class="m-0">Partner ID:
            <span class="text-muted" id="modal_partnerID">
              <i class="fa-solid fa-spinner fa-spin"></i>
            </span>
          </p>
          <p class="m-0">Partner Key:
            <span class="text-muted" id="modal_partnerKey">
              <i class="fa-solid fa-spinner fa-spin"></i>
            </span>
          </p>
        </div>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="" class="form-label">Cập nhật đường dẫn</label>
          <input type="text" id="modal_partnerCallback" class="form-control" placeholder="https://yourdomain.com" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" id="modal_update" class="btn btn-primary">Cập nhật</button>
      </div>
    </div>
  </div>
</div>


<?php
// Footer
require('../../layout/client/footer.php');
?>
<script>
  // Thêm API
  $('#api_send').click(function() {
    var data = {
      api_type: $('#api_type').val(),
      api_callback: $('#api_callback').val(),
      api_action: $('#api_action').val(),
      api_ip: $('#api_ip').val()
    };

    $.post('./ajaxs/main/client/action/addPartner.php', {
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
            window.location.href = './partner ';
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });

  // Xóa API
  $('.removePartner').click(function() {
    var data = {
      partner_id: $(this).data('partner-id'),
    };

    Swal.fire({
      title: '',
      text: "Bạn có chắc chắn muốn xóa API này!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Có, tôi chắc chắn!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post('./ajaxs/main/client/action/removePartner.php', {
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
                window.location.href = './partner ';
              }
            });
          } else {
            Swal.fire('', result.message + dataMessage, 'error');
          }
        });
      }
    });
  });

  // Lấy dữ liệu Partner
  function getInfoPartner(partnerID) {
    const ICON_LOADER = '<i class="fa-solid fa-spinner fa-spin"></i>'; // Icon loader
    const MSG_ERROR = '<span class="text-danger">Lỗi lấy dữ liệu! vui lòng đóng và mở lại</span>';
    let modal_partnerID = document.getElementById("modal_partnerID");
    let modal_partnerKey = document.getElementById("modal_partnerKey");
    let modal_update = document.getElementById("modal_update");
    let modal_partnerCallback = document.getElementById("modal_partnerCallback");

    // Gán nút loader
    modal_partnerID.innerHTML = ICON_LOADER;
    modal_partnerKey.innerHTML = ICON_LOADER;

    // Tạo mảng data với partnerID
    var data = {
      partnerID: partnerID
    };

    // Gửi dữ liệu dạng POST đến getInfoPartner.php
    $.post('./ajaxs/main/client/action/getInfoPartner.php', {
      data: JSON.stringify(data)
    }, function(response) {
      var result = JSON.parse(response);
      // Lấy dữ liệu thành công
      if (result.success) {
        const partnerID = result.data.partner_id;
        const partnerKey = result.data.partner_key;
        const partnerCallback = result.data.partner_callback;

        modal_partnerID.innerHTML = partnerID;
        modal_partnerKey.innerHTML = partnerKey;

        // Nếu partnerCallback không rỗng, cập nhật giá trị
        if (partnerCallback) {
          modal_partnerCallback.value = partnerCallback;
        }

        modal_update.setAttribute('onclick', `updateInfoPartner(${partnerID})`);
      } else {
        modal_partnerID.innerHTML = MSG_ERROR;
        modal_partnerKey.innerHTML = MSG_ERROR;
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {
      console.error('Lỗi khi lấy thông tin đối tác:', textStatus, errorThrown);
      console.log('Chi tiết lỗi:', jqXHR.responseText);
    });
  }

  // Cập nhật dữ liệu Partner
  function updateInfoPartner(partnerID) {
    let modal_partnerCallback = document.getElementById("modal_partnerCallback").value;

    var data = {
      partnerID: partnerID,
      partnerCallback: modal_partnerCallback
    };

    $.post('./ajaxs/main/client/action/updateInfoPartner.php', {
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
            window.location.href = './partner ';
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });

  }

  new DataTable('#data-table_list-partner');
</script>