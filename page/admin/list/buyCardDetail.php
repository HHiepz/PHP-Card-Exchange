<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Kiểm tra quyền
checkToken('admin');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);


// Kiểm tra có tồn tại mã đơn hàng không
if (!isset($_GET['buy_card_order_code'])) {
  header("Location:" . getDomain() . "/admin/list/buyCard");
  die;
}

// Kiểm tra mã đơn hàng có tồn tại không
$buy_card_order_code = $purifier->purify($_GET['buy_card_order_code']);
$checkBuyCardOrder = pdo_query_one("SELECT * FROM `buy-card-order` WHERE `buy-card-order_code` = ?", [$buy_card_order_code]);
if (empty($checkBuyCardOrder)) {
  header("Location:" . getDomain() . "/admin/list/buyCard");
  die;
}

// Lấy thông tin chi tiết
$user_id       = $checkBuyCardOrder['user_id'];
$code          = $checkBuyCardOrder['buy-card-order_code'];
$status        = $checkBuyCardOrder['buy-card-order_status'];
$total_pay     = $checkBuyCardOrder['buy-card-order_total_pay'];
$telco         = $checkBuyCardOrder['buy-card-order_telco'];
$price         = $checkBuyCardOrder['buy-card-order_price'];
$quantity      = $checkBuyCardOrder['buy-card-order_quantity'];
$created_at    = $checkBuyCardOrder['created_at'];
$update_api    = empty($checkBuyCardOrder['updated_api']) ? '-' : $checkBuyCardOrder['updated_api'];
$order_code    = $checkBuyCardOrder['buy-card-order_order_code'];

// Header
$title_website = 'Chi tiết mua thẻ cào';
require('../../../layout/admin/header.php');
?>

<div class="hp-main-layout-content">
  <input type="hidden" id="card_data_order_code" value="<?= $code ?>">
  <div class="row mb-32 gy-32">
    <div class="col-12">
      <h1 class="display-4 fs-2">Chi tiết thẻ: <span class="text-danger"><?= $code ?></span></h1>
    </div>
  </div>

  <!-- Thông tin chi tiết -->
  <div class="col-12 mb-16">
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-between">
          <div class="col-12">
            <div class="row">
              <div class="col-12 col-md-12">
                <ul class="list-group">

                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Lời lãi:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"> Chưa cập nhật </span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Chủ:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0 fw-bold fs-4"> <?= getEmailUser($user_id) ?> </span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Mã đơn hàng:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="badge"><?= $code ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Tổng tiền:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= number_format($total_pay) ?>đ</span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Trạng thái:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="badge badge-<?= statusCard($status, "color") ?>"><?= statusCard($status) ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Số lượng thẻ:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= number_format($quantity) ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Mệnh giá:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= number_format($price) ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Mã web mẹ:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="badge"><?= $order_code ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Thời gian tạo:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $created_at ?></span>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-12 col-md-2">
                        <span class="hp-p1-body">Thời gian duyệt:</span>
                      </div>
                      <div class="col-12 col-md-10">
                        <span class="mt-0 mt-sm-4 hp-p1-body text-black-100 hp-text-color-dark-0"><?= $update_api ?></span>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cập nhật chỉnh sửa -->
  <div class="col-12 mb-16">
    <div class="row g-16">
      <div class="col">
        <div class="card h-100">
          <div class="card-body">
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title text-center">Cập nhật - chỉnh sửa</h3>
                <div class="divider"></div>
              </div>
              <div class="block-content">

                <div class="mb-16">
                  <label for="pass">Thêm thẻ:</label>
                  <div class="form-group w-100 row">

                    <div class="col-12 col-md-2">
                      <select class="form-control mb-16" id="addCardData_telco">
                        <option selected disabled>Nhà mạng</option>
                        <?php
                        foreach (list_telco_buyCard() as $key => $telco) {
                        ?>
                          <option value="<?= $key ?>"><?= $telco ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-12 col-md-2">
                      <select class="form-control mb-16" id="addCardData_price">
                        <option selected disabled>Mệnh giá</option>
                        <option value="10000">10.000</option>
                        <option value="20000">20.000</option>
                        <option value="30000">30.000</option>
                        <option value="50000">50.000</option>
                        <option value="100000">100.000</option>
                        <option value="200000">200.000</option>
                        <option value="300000">300.000</option>
                        <option value="500000">500.000</option>
                        <option value="1000000">1.000.000</option>
                        <option value="1000000">2.000.000</option>
                        <option value="1000000">3.000.000</option>
                        <option value="1000000">5.000.000</option>
                      </select>
                    </div>
                    <div class="col-12 col-md-3">
                      <input class="form-control form-control-alt mb-16" id="addCardData_code" placeholder="Code / mã" min="0" type="text" />
                    </div>
                    <div class="col-12 col-md-3">
                      <input class="form-control form-control-alt mb-16" id="addCardData_seri" placeholder="Serial" type="text" />
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="w-100 btn btn-dashed text-success border-success hp-hover-text-color-success-2 hp-hover-border-color-success-2" id="addCardData">
                        Thêm
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

  <!-- Danh sách thẻ -->
  <div class="col-12 mb-16">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Danh sách thẻ</h3>
        <div class="table-responsive">
          <table class="table table-hover" id="data-table_list-card">
            <thead>
              <tr>
                <th scope="col">STT</th>
                <th scope="col">Nhà mạng</th>
                <th scope="col">Mã thẻ</th>
                <th scope="col">Serial</th>
                <th scope="col">Mệnh giá</th>
                <th scope="col">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stt = 0;
              $checkBuyCardData = pdo_query("SELECT * FROM `buy-card-data` WHERE `buy-card-order_code` = ?", [$code]);
              foreach ($checkBuyCardData as $buyCardData) {
                $stt++;
                $telco = $buyCardData['buy-card-data_telco'];
                $pin    = $buyCardData['buy-card-data_pin'];
                $serial = $buyCardData['buy-card-data_serial'];
                $price  = $buyCardData['buy-card-data_price'];
              ?>
                <tr>
                  <th scope="row"><?= $stt ?></th>
                  <th><?= $telco ?></th>
                  <td><?= $serial ?></td>
                  <td><?= $pin ?></td>
                  <td><?= number_format($price) ?>đ</td>
                  <td>
                    <!-- Xóa -->
                    <button type="button" class="btn btn-sm btn-outline-danger buyCardData_delete" data-buycarddata-id="<?= $buyCardData['buy-card-data_id'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa thẻ">
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
// Footer
require('../../../layout/admin/footer.php');
?>
<script>
  new DataTable('#data-table_list-card');

  function handleButtonClick(buttonClass, action, message, keyGet, valueKeyGet) {
    $(buttonClass).click(function() {
      Swal.fire({
        title: '',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tôi đã chắc chắn!',
        onOpen: () => {
          Swal.showLoading();
          const confirmButton = Swal.getConfirmButton();
          confirmButton.disabled = true;
          setTimeout(() => {
            confirmButton.disabled = false;
            Swal.hideLoading();
          }, 1500);
        }
      }).then((result) => {
        if (result.isConfirmed) {
          var data = {};
          data[keyGet] = $(this).data(valueKeyGet);
          data[action] = true;

          $.post('../../../../ajaxs/admin/action/buyCard.php', {
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
                  window.location.href = "<?= "/admin/list/buyCardDetail/$code" ?>";
                }
              });
            } else {
              Swal.fire('', result.message + dataMessage, 'error');
            }
          });
        }
      })
    });
  }

  // =================== 
  handleButtonClick('.buyCardData_delete', 'buyCardData_delete', "Bạn có chắc chắn muốn XÓA THẺ!", "buyCardData_id", "buycarddata-id");
  // =================== 

  // Thêm thẻ
  $('#addCardData').click(function() {
    var data = {
      card_data_order_code: $('#card_data_order_code').val(),
      buyCardData_telco: $('#addCardData_telco').val(),
      buyCardData_price: $('#addCardData_price').val(),
      buyCardData_code: $('#addCardData_code').val(),
      buyCardData_seri: $('#addCardData_seri').val(),
      buyCardData_add: true
    };

    $.post('../../../../ajaxs/admin/action/buyCard.php', {
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
            window.location.href = "<?= "/admin/list/buyCardDetail/$code" ?>";
          }
        });
      } else {
        Swal.fire('', result.message + dataMessage, 'error');
      }
    });
  });
</script>