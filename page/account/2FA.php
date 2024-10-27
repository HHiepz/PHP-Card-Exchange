<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken('verify');

$user_id     = getIdUser();
$user_info   = getInfoUser($user_id);
$verify_type = getVerifyType($user_info);

if ($verify_type == 'Email') {
    $dataVerify = [
        'name'     => 'Email, check your email',
        'nameType' => 'Email',
        'otp'      => 'otpEmail'
    ];
} else if ($verify_type == 'Google Authenticator') {
    $dataVerify = [
        'name'     => 'Google Authenticator',
        'nameType' => 'GgAuth',
        'otp'      => 'otpGgAuth'
    ];
} else {
    $dataVerify = [
        'name'     => 'Không xác định',
        'nameType' => NULL,
        'otp'      => NULL
    ];
}

// Header
$title_website = 'Xác thực cấp 2';
require('../../layout/account/header.php');
?>
<div class="row hp-authentication-page authentication-page">
    <div class="hp-bg-black-20 hp-bg-color-dark-90 col-lg-6 col-12">
        <div class="row hp-image-row hp-image-row-v1 image-row h-100 px-8 px-sm-16 px-md-0 pb-32 pb-sm-0 pt-32 pt-md-0">

            <div class="col-12 px-0">
                <div class="row h-100 w-100 mx-0 align-items-center justify-content-center">
                    <div class="hp-bg-item text-center mb-32 mb-md-0 px-0 col-12">
                        <a href="<?= getDomain() ?>">
                            <img class="hp-dark-none m-auto w-100" src="<?= getDomain() ?>/frontend/app-assets/img/pages/authentication/register/light.png" alt="Background Image" />
                            <img class="hp-dark-block m-auto w-100" src="<?= getDomain() ?>/frontend/app-assets/img/pages/authentication/register/dark.png" alt="Background Image" />
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-12 col-lg-6 py-sm-64 py-lg-0">
        <div class="row align-items-center justify-content-center h-100 mx-4 mx-sm-n32">
            <div class="col-12 col-md-9 col-xl-7 col-xxxl-5 px-8 px-sm-0 pt-24 pb-48">
                <h1 class="mb-0 mb-sm-24">Bảo mật 2 lớp </h1>
                <div class="divider"></div>

                <div class="mt-16 mt-sm-32 mb-8">
                    <div class="mb-16">
                        <label for="otpEmail" class="form-label"> Mã xác thực <span class="text-danger">(<?= $dataVerify['name'] ?>)</span>:</label>
                        <input type="text" class="form-control" id="<?= $dataVerify['otp'] ?>" />
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="verify">
                        Đăng nhập (2FA)
                    </button>
                </div>

                <div class="col-12 hp-form-info text-center">
                    <span class="text-black-80 hp-text-color-dark-40 hp-caption me-4">Có sự nhầm lẫn?</span>
                    <a class="text-primary-1 hp-text-color-dark-primary-2 hp-caption" href="<?= getDomain() ?>/account/logout">Đăng nhập tài khoản khác</a>
                </div>

                <div class="mt-48 mt-sm-96 col-12">
                    <p class="hp-p1-body text-center hp-text-color-black-60 mb-8">
                        COPYRIGHT ©2023 HHIEPZ, All rights Reserved
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require('../../layout/account/footer.php');
?>
<script>
    <?php
    if (!empty($google_reCaptcha) && $google_reCaptcha == 1) {
        echo "var google_site_key = " . json_encode($google_site_key) . ";";
    ?>
        var isSending = false;

        $('#verify').click(function() {
            if (isSending) {
                return;
            }

            // Lấy token reCAPTCHA
            grecaptcha.ready(function() {
                grecaptcha.execute(google_site_key, {
                    action: 'submit'
                }).then(function(token) {
                    var data = {
                        <?= $dataVerify['otp'] ?>: $('#<?= $dataVerify['otp'] ?>').val(),
                        token: token
                    };

                    // Tiến hành gửi dữ liệu đăng nhập cùng với token reCAPTCHA
                    $.post('../ajaxs/main/account/2FA.php', {
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
                        }
                        isSending = false;
                    }).fail(function() {
                        // Trong trường hợp thất bại, kích hoạt lại nút gửi
                        $('#verify').prop('disabled', false);
                        isSending = false;
                    });
                });
            });
        });

    <?php
    } else {
    ?>
        var isSending = false;

        $('#verify').click(function() {
            if (isSending) {
                return;
            }
            var data = {
                <?= $dataVerify['otp'] ?>: $('#<?= $dataVerify['otp'] ?>').val()
            };

            // Tạm thời vô hiệu hóa nút gửi
            $('#verify').prop('disabled', true);
            isSending = true; // Đánh dấu đang gửi

            $.post('../ajaxs/main/account/2FA.php', {
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
                    $('#verify').prop('disabled', false);
                }
                isSending = false;
            }).fail(function() {
                // Trong trường hợp thất bại, kích hoạt lại nút gửi
                $('#verify').prop('disabled', false);
                isSending = false;
            });
        });
    <?php
    }
    ?>
</script>