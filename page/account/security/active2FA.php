<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../vendor/autoload.php');

$googleAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

checkToken("client");

// Tạo khóa bảo mật 
$user_info = getInfoUser(getIdUser());
if ($user_info['user_is_verify_2fa'] == 1 || $user_info['user_is_verify_email'] == 1 || !empty($user_info['user_2fa_code'])) {
    header('Location: /account/profile');
    exit();
}

$user_email = $user_info['user_email'];

$ggAuth_secret = $googleAuth->generateSecret();
$ggAuth_qrCode = $googleAuth->getUrl("CARD2K | $user_email", "", $ggAuth_secret);

// Header
$title_website = 'Kích hoạt 2FA';
require('../../../layout/client/header.php');
?>
<div class="hp-main-layout-content">

    <div class="row mb-32 gy-32">
        <div class="col-12">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="h3 d-block mb-8 text-warning"><i class="fa-solid fa-exclamation-triangle"></i> Lưu ý:</span>
                            <div class="divider"></div>

                            <div class="block-content">
                                <div class="alert" role="alert">
                                    <h4 class="alert-heading text-warning">Không đọc nếu có hậu quả admin không chịu trách nhiệm !!!</h4>
                                    <p>
                                        Để kích hoạt 2FA, bạn cần cài đặt ứng dụng Google Authenticator trên điện thoại của mình.
                                    </p>
                                    <p>
                                        Đối với Android: <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">Tải ứng dụng</a> <br>
                                        Đối với IOS: <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">Tải ứng dụng</a>
                                    </p>
                                    <p>
                                        Sau khi cài đặt ứng dụng, hãy <strong>quét mã QR</strong> bên dưới, và <strong>điền mã xác thực vào ô bên dưới</strong> để kích hoạt 2FA.
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        Cứ mỗi lần đăng nhập, bạn sẽ cần nhập mã xác thực từ ứng dụng Google Authenticator để hoàn tất quá trình đăng nhập. Và 30 giây sau mã sẽ thay đổi một lần.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-32 gy-32">
        <div class="col-12 col-sm-6">
            <div class="row g-32">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="h3 d-block mb-8">Kết nối với App:</span>
                            <div class="divider"></div>
                            <div class="block-content">
                                <div class="alert" role="alert">
                                    <h4 class="alert-heading text-warning"></h4>
                                    <p>Khóa bảo mật: <span class="text-danger fw-bold"><?= $ggAuth_secret ?></span></p>
                                    <!-- Ảnh mã QR -->
                                    <img src="<?= $ggAuth_qrCode ?>" alt="QR Code" class="img-fluid" />
                                    <hr>
                                    <p class="mb-0">Vui lòng quét mã QR sau đó điền mã xác thực để kích hoạt.</p>
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
                            <span class="h3 d-block mb-8">
                                Kích hoạt 2FA
                            </span>
                            <div class="divider"></div>

                            <div class="block-content" bis_skin_checked="1">
                                <div class="form-group mb-16" bis_skin_checked="1">
                                    <label>Mã xác thực :</label><br />
                                    <div class="input-number w-100">
                                        <div class="input-number-input-wrap">
                                            <input class="input-number-input" id="ggAuth_otp" type="text" placeholder="0123.." />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" bis_skin_checked="1">
                                    <button class="btn btn-hero-danger btn-block" id="ggAuth_check">
                                        KÍCH HOẠT
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

<?php
// Footer
require('../../../layout/client/footer.php');
?>

<script>
    var isSending = false;

    $('#ggAuth_check').click(function() {
        if (isSending) {
            return;
        }
        var data = {
            otp: $('#ggAuth_otp').val(),
            ggAuth_secret: '<?= $ggAuth_secret ?>'
        };

        // Tạm thời vô hiệu hóa nút gửi
        $('#ggAuth_check').prop('disabled', true);
        isSending = true; // Đánh dấu đang gửi

        $.post('../../ajaxs/main/account/security/active2FA.php', {
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
                        window.location.href = '/';
                    }
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
                $('#ggAuth_check').prop('disabled', false);
            }
            isSending = false;
        }).fail(function() {
            // Trong trường hợp thất bại, kích hoạt lại nút gửi
            $('#ggAuth_check').prop('disabled', false);
            isSending = false;
        });
    });
</script>