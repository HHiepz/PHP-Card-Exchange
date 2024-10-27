<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken('auth');

// Header
$title_website = 'Quên mật khẩu';
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
                <h1 class="mb-0 mb-sm-24">Lấy lại mật khẩu</h1>
                <div class="divider"></div>

                <div class="mt-16 mt-sm-32 mb-8">
                    <div class="mb-16">
                        <label for="email" class="form-label">E-mail:</label>
                        <div class="row">
                            <div class="col-8">
                                <input type="email" class="form-control" id="email" />
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-warning w-100" id="sendOtpButton">
                                    Gửi mã
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label for="otpEmail" class="form-label">OTP Email (mã xác nhận):</label>
                        <input type="number" class="form-control" id="otpEmail" />
                    </div>

                    <div class="row align-items-center justify-content-between mb-16">
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="resetpass">
                        Xác nhận
                    </button>
                </div>

                <div class="col-12 hp-form-info text-center">
                    <a class="text-primary-1 hp-text-color-dark-primary-2 hp-caption" href="./register">Đăng kí tài khoản</a> |
                    <a class="text-primary-1 hp-text-color-dark-primary-2 hp-caption" href="./login">Đăng nhập</a>
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
        $('#resetpass').click(function() {
            grecaptcha.ready(function() {
                grecaptcha.execute(google_site_key, {
                    action: 'submit'
                }).then(function(token) {

                    var data = {
                        email: $('#email').val(),
                        otpEmail: $('#otpEmail').val(),
                        token: token,
                        resetpass: true
                    };

                    // Disable the button
                    $(this).prop('disabled', true);

                    $.post('../ajaxs/main/account/resetpass.php', {
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
                                    window.location.href = './login ';
                                }
                            });
                        } else {
                            Swal.fire('', result.message + dataMessage, 'error');
                        }
                        // Enable the button again
                        $('#resetpass').prop('disabled', false);
                    }).fail(function() {
                        // Enable the button again in case of failure
                        $('#resetpass').prop('disabled', false);
                    });
                });
            });
        });


        var isSending = false; // Cờ để xác định trạng thái gửi
        $('#sendOtpButton').click(function() {
            if (isSending) {
                return; // Nếu đang gửi, không cho phép gửi yêu cầu mới
            }
            grecaptcha.ready(function() {
                grecaptcha.execute(google_site_key, {
                    action: 'submit'
                }).then(function(token) {

                    var data = {
                        email: $('#email').val(),
                        token: token,
                        sendOtpButton: $('#sendOtpButton').val()
                    };

                    // Tạm thời vô hiệu hóa nút gửi
                    $('#sendOtpButton').prop('disabled', true);

                    isSending = true; // Đánh dấu đang gửi

                    $.post('../ajaxs/main/account/resetpass.php', {
                        data: JSON.stringify(data)
                    }, function(response) {
                        var result = JSON.parse(response);
                        var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                        if (result.success) {
                            Swal.fire({
                                title: '',
                                text: result.message + dataMessage,
                                icon: 'success',
                            });
                            // Nếu thành công, nút gửi vẫn bị vô hiệu hóa
                        } else {
                            Swal.fire('', result.message + dataMessage, 'error');
                            // Trong trường hợp thất bại, kích hoạt lại nút gửi
                            $('#sendOtpButton').prop('disabled', false);
                        }

                        isSending = false;
                    }).fail(function() {
                        // Trong trường hợp thất bại, kích hoạt lại nút gửi
                        $('#sendOtpButton').prop('disabled', false);
                        isSending = false;
                    });
                });
            });
        });
    <?php
    } else {
    ?>
        $('#resetpass').click(function() {
            var data = {
                email: $('#email').val(),
                otpEmail: $('#otpEmail').val(),
                resetpass: true
            };

            // Disable the button
            $(this).prop('disabled', true);

            $.post('../ajaxs/main/account/resetpass.php', {
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
                            window.location.href = './login ';
                        }
                    });
                } else {
                    Swal.fire('', result.message + dataMessage, 'error');
                }
                // Enable the button again
                $('#resetpass').prop('disabled', false);
            }).fail(function() {
                // Enable the button again in case of failure
                $('#resetpass').prop('disabled', false);
            });
        });


        var isSending = false; // Cờ để xác định trạng thái gửi
        $('#sendOtpButton').click(function() {
            if (isSending) {
                return; // Nếu đang gửi, không cho phép gửi yêu cầu mới
            }

            var data = {
                email: $('#email').val(),
                sendOtpButton: $('#sendOtpButton').val()
            };

            // Tạm thời vô hiệu hóa nút gửi
            $('#sendOtpButton').prop('disabled', true);

            isSending = true; // Đánh dấu đang gửi

            $.post('../ajaxs/main/account/resetpass.php', {
                data: JSON.stringify(data)
            }, function(response) {
                var result = JSON.parse(response);
                var dataMessage = result.data ? '\nDữ liệu: ' + JSON.stringify(result.data) : '';
                if (result.success) {
                    Swal.fire({
                        title: '',
                        text: result.message + dataMessage,
                        icon: 'success',
                    });
                    // Nếu thành công, nút gửi vẫn bị vô hiệu hóa
                } else {
                    Swal.fire('', result.message + dataMessage, 'error');
                    // Trong trường hợp thất bại, kích hoạt lại nút gửi
                    $('#sendOtpButton').prop('disabled', false);
                }

                isSending = false;
            }).fail(function() {
                // Trong trường hợp thất bại, kích hoạt lại nút gửi
                $('#sendOtpButton').prop('disabled', false);
                isSending = false;
            });
        });
    <?php
    }
    ?>
</script>