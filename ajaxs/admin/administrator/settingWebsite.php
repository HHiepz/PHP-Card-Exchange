<?php
require('../../../core/database.php');
require('../../../core/function.php');
require('../../../plugins/HTMLPurifier/HTMLPurifier.auto.php');

// Config HTMLPurifier
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (checkToken("request_admin")) {
        $data = json_decode($_POST['data'], true);

        // Kiểm tra dữ liệu data phải là kiểu mảng
        if (!is_array($data)) {
            response(false, 'Dữ liệu không hợp lệ');
        }

        // GOOGLE SEO
        if (isset($data['google_seo'])) {
            $google_favicon     = $purifier->purify($data['google_favicon']);
            $google_analytic    = $data['google_analytic'];
            $google_search      = $purifier->purify($data['google_search']);
            $google_description = $purifier->purify($data['google_description']);

            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'google_favicon'", [$google_favicon]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'google_analytic'", [$google_analytic]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'google_search'", [$google_search]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'google_description'", [$google_description]);

            response(true, 'Cập nhật Google SEO thành công!');
        }

        // WEBSITE
        if (isset($data['website'])) {
            $website_logo_light = $purifier->purify($data['website_logo_light']);
            $website_logo_dark  = $purifier->purify($data['website_logo_dark']);
            $website_support    = $purifier->purify($data['website_support']);
            $website_youtube    = $purifier->purify($data['website_youtube']);
            $website_facebook   = $purifier->purify($data['website_facebook']);
            $website_telegram   = $purifier->purify($data['website_telegram']);
            $website_discord    = $purifier->purify($data['website_discord']);

            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'google_logo_light'", [$website_logo_light]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'google_logo_dark'", [$website_logo_dark]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'footer_support'", [$website_support]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'footer_youtube'", [$website_youtube]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'footer_facebook'", [$website_facebook]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'footer_telegram'", [$website_telegram]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'footer_discord'", [$website_discord]);

            response(true, 'Cập nhật Website thành công!');
        }

        // FACEBOOK
        if (isset($data['facebook'])) {
            $facebook_message = $data['facebook_message'];
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'facebook_message'", [$facebook_message]);
            response(true, 'Cập nhật Facebook thành công!');
        }

        // DMCA
        if (isset($data['dmca'])) {
            $dmca_meta_verify = $data['dmca_meta_verify'];
            $dmca_link        = $data['dmca_link'];
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'dmca_meta_verify'", [$dmca_meta_verify]);
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'dmca_link'", [$dmca_link]);
            response(true, 'Cập nhật DMCA thành công!');
        }

        // HEADER
        if (isset($data['header'])) {
            $header_script = $data['header_script'];
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'header_script'", [$header_script]);
            response(true, 'Cập nhật Header thành công!');
        }

        // FOOTER
        if (isset($data['footer'])) {
            $footer_script = $data['footer_script'];
            pdo_execute("UPDATE `setting` SET `value` = ? WHERE `name` = 'footer_script'", [$footer_script]);
            response(true, 'Cập nhật Footer thành công!');
        }
    } else {
        response(false, "Bạn chưa đăng nhập xin vui lòng đăng nhập!");
    }
}
