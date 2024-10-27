<?php
require('../../../core/database.php');
require('../../../core/function.php');

checkToken("client");

// Tạo khóa bảo mật 
$user_info = getInfoUser(getIdUser());
if ($user_info['user_is_verify'] == 0 || ($user_info['user_is_verify_2fa'] == 0 && $user_info['user_is_verify_email'] == 0) || empty($user_info['user_2fa_code'])) {
    header('Location: /account/profile');
    exit();
}

// Header
$title_website = 'Hủy kích hoạt 2FA';
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
                                    <h4 class="alert-heading text-warning text-center">Không đọc nếu có hậu quả admin không chịu trách nhiệm !!!</h4>
                                    <p class="text-center">
                                        Bạn có chắc chắn muốn hủy kích hoạt 2FA <strong>Google Authenticator</strong>?
                                    </p>
                                    <p class="text-center">
                                        <strong>Điều này sẽ làm giảm bảo mật tài khoản của bạn đáng kể đấy.</strong>
                                    </p>
                                    <p class="text-center">
                                        Vui lòng nhập mã xác thực từ ứng dụng Google Authenticator để hoàn tất quá trình hủy kích hoạt.
                                    </p>

                                    <div class="form-group mb-16" bis_skin_checked="1">
                                        <div class="input-number w-100">
                                            <div class="input-number-input-wrap">
                                                <input class="input-number-input" id="ggAuth_otp" type="text" placeholder="0123.." />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center" bis_skin_checked="1">
                                        <button class="btn btn-hero-danger btn-block" id="ggAuth_check">
                                            HỦY KÍCH HOẠT
                                        </button>
                                    </div>

                                    <hr>
                                    <p class="mb-0">
                                        Chúng tôi khuyến cáo bạn không nên hủy kích hoạt 2FA nếu không cần thiết.
                                    </p>
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
            otp: $('#ggAuth_otp').val()
        };

        // Tạm thời vô hiệu hóa nút gửi
        $('#ggAuth_check').prop('disabled', true);
        isSending = true; // Đánh dấu đang gửi

        $.post('../../ajaxs/main/account/security/remove2FA.php', {
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