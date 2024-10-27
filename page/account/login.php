<?php
require('../../core/database.php');
require('../../core/function.php');

checkToken('auth');

// Header
$title_website = 'Đăng nhập';
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
                <h1 class="mb-0 mb-sm-24">Đăng nhập</h1>
                <div class="divider"></div>

                <div class="mt-16 mt-sm-32 mb-8">
                    <div class="mb-16">
                        <label for="email" class="form-label"> Email:</label>
                        <input type="text" class="form-control" id="email" />
                    </div>

                    <div class="mb-16">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="password" class="form-control" id="password" />
                    </div>

                    <div class="row align-items-center justify-content-between mb-16">


                        <div class="col hp-flex-none w-auto">
                            <a class="hp-button text-black-80 hp-text-color-dark-40" href="./resetpass">Quên mật khẩu?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="loginButton">
                        Đăng nhập
                    </button>
                </div>

                <div class="col-12 hp-form-info text-center">
                    <span class="text-black-80 hp-text-color-dark-40 hp-caption me-4">Chưa có tài khoản?</span>
                    <a class="text-primary-1 hp-text-color-dark-primary-2 hp-caption" href="<?= getDomain() ?>/account/register">Tạo tài khoản tại đây</a>
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

        $('#loginButton').click(function() {
            if (isSending) {
                return;
            }

            // Lấy token reCAPTCHA
            grecaptcha.ready(function() {
                grecaptcha.execute(google_site_key, {
                    action: 'submit'
                }).then(function(token) {
                    var data = {
                        email: $('#email').val(),
                        password: $('#password').val(),
                        token: token
                    };

                    // Tiến hành gửi dữ liệu đăng nhập cùng với token reCAPTCHA
                    $.post('../ajaxs/main/account/login.php', {
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
                        $('#loginButton').prop('disabled', false);
                        isSending = false;
                    });
                });
            });
        });

    <?php
    } else {
    ?>
        var isSending = false;

        $('#loginButton').click(function() {
            if (isSending) {
                return;
            }
            var data = {
                email: $('#email').val(),
                password: $('#password').val(),
            };

            // Tạm thời vô hiệu hóa nút gửi
            $('#loginButton').prop('disabled', true);
            isSending = true; // Đánh dấu đang gửi

            $.post('../ajaxs/main/account/login.php', {
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
                    $('#loginButton').prop('disabled', false);
                }
                isSending = false;
            }).fail(function() {
                // Trong trường hợp thất bại, kích hoạt lại nút gửi
                $('#loginButton').prop('disabled', false);
                isSending = false;
            });
        });
    <?php
    }
    ?>
</script>