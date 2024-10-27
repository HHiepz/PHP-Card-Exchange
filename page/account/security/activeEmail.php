<?php
require('../../../core/database.php');
require('../../../core/function.php');

checkToken("client");

// Tạo khóa bảo mật 
$user_info = getInfoUser(getIdUser());
if ($user_info['user_is_verify_2fa'] == 1 || $user_info['user_is_verify_email'] == 1) {
    header('Location: /account/profile');
    exit();
}

$user_email = $user_info['user_email'];

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
                                        Để kích hoạt 2FA, bạn cần xác minh tài khoản qua Email.
                                    </p>
                                    <p>
                                        Một mã xác thực đã được gửi đến Email của bạn: <strong><?= $user_email ?></strong>, nếu không
                                        nhận được Email, vui lòng bấm vào <a class="text-primary" id="otpSend">đây</a> để gửi lại.
                                    </p>

                                    <div class="form-group mb-16" bis_skin_checked="1">
                                        <div class="input-number w-100">
                                            <div class="input-number-input-wrap">
                                                <input class="input-number-input" id="email_otp" type="text" placeholder="0123.." />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center" bis_skin_checked="1">
                                        <button class="btn btn-hero-danger btn-block" id="email_check">
                                            KÍCH HOẠT
                                        </button>
                                    </div>
                                    <hr>
                                    <p class="mb-0">
                                        Cứ mỗi lần đăng nhập, bạn sẽ cần nhập mã xác thực từ Email để hoàn tất quá trình đăng nhập.
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

    $('#email_check').click(function() {
        if (isSending) {
            return;
        }
        var data = {
            otp: $('#email_otp').val(),
            otpCheck: true
        };

        // Tạm thời vô hiệu hóa nút gửi
        $('#email_check').prop('disabled', true);
        isSending = true; // Đánh dấu đang gửi

        $.post('../../ajaxs/main/account/security/activeEmail.php', {
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
                        window.location.href = '/account/profile';
                    }
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
                $('#email_check').prop('disabled', false);
            }
            isSending = false;
        }).fail(function() {
            // Trong trường hợp thất bại, kích hoạt lại nút gửi
            $('#email_check').prop('disabled', false);
            isSending = false;
        });
    });


    $('#otpSend').click(function() {
        if (isSending) {
            return;
        }
        var data = {
            otpSend: true
        };

        // Tạm thời vô hiệu hóa nút gửi
        $('#otpSend').prop('disabled', true);
        isSending = true; // Đánh dấu đang gửi

        $.post('../../ajaxs/main/account/security/activeEmail.php', {
            data: JSON.stringify(data)
        }, function(response) {
            var result = JSON.parse(response);
            var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
            if (result.success) {
                Swal.fire({
                    title: '',
                    text: result.message + dataMessage,
                    icon: 'success'
                });
            } else {
                Swal.fire('', result.message + dataMessage, 'error');
                $('#otpSend').prop('disabled', false);
            }
            isSending = false;
        }).fail(function() {
            // Trong trường hợp thất bại, kích hoạt lại nút gửi
            $('#otpSend').prop('disabled', false);
            isSending = false;
        });
    });
</script>