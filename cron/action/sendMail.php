<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use GuzzleHttp\Client;

require('../../vendor/autoload.php');
require('../../core/function.php');
require('../../core/database.php');

function sendMail($emailSending, $subject, $body)
{
    // Info email
    $send_email = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "email"');
    $send_password = pdo_query_value('SELECT `value` FROM `setting` WHERE `name` = "email_password"');

    // Create an email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $send_email;
        $mail->Password = $send_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->CharSet = "UTF-8";

        // Recipients
        $mail->setFrom("$send_email", "$send_email");
        $mail->addAddress($emailSending);
        $mail->addReplyTo("$send_email", "$send_email");

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send
        $mail->send();

        return ['status' => true, 'response' => 'Email sent successfully'];
    } catch (Exception $e) {
        return ['status' => false, 'message' => $mail->ErrorInfo];
    }
}

function getTemplateContent($url, $replacements = [])
{
    $client = new Client(['verify' => false]);
    try {
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            // Thực hiện thay thế ký tự
            foreach ($replacements as $search => $replace) {
                $content = str_replace($search, $replace, $content);
            }
            return $content;
        } else {
            error_log("Failed to retrieve content. HTTP Status Code: " . $response->getStatusCode());
            return false;
        }
    } catch (Exception $e) {
        error_log("Guzzle Error: " . $e->getMessage());
        return false;
    }
}


$listEmail = pdo_query("SELECT * FROM `email_queue` ORDER BY `id` ASC LIMIT 5");
foreach ($listEmail as $email) {
    $emailSending = $email['recipient_email'];
    $subject = $email['subject'];
    $type = $email['type'];
    $dataJson = json_decode($email['dataJson'], true);

    $domain = getDomain();
    $body = '';

    if ($type == "loginNewIp") {
        $replacements = [
            '{{email}}' => $emailSending,
            '{{ip_address}}' => $dataJson['ip'],
            '{{time}}' => $dataJson['time'],
            '{{year}}' => $dataJson['year'],
        ];
        $body = getTemplateContent($domain . "/lib/templatesEmail/newIpLogin.html", $replacements);
    }

    if ($type == 'otpResetPass' || $type == 'otpRegister') {
        $replacements = [
            '{{name}}' => $emailSending,
            '{{new_password}}' => $dataJson['otp'],
            '{{year}}' => $dataJson['year'],
        ];
        $body = getTemplateContent($domain . "/lib/templatesEmail/otpResetPass.html", $replacements);
    }

    if ($type == 'otpRegister') {
        $replacements = [
            '{{name}}' => $emailSending,
            '{{new_password}}' => $dataJson['otp'],
            '{{year}}' => $dataJson['year'],
        ];
        $body = getTemplateContent($domain . "/lib/templatesEmail/otpRegister.html", $replacements);
    }

    if ($type == 'newPassword') {
        $replacements = [
            '{{name}}' => $emailSending,
            '{{new_password}}' => $dataJson['new_password'],
            '{{year}}' => $dataJson['year'],
        ];
        $body = getTemplateContent($domain . "/lib/templatesEmail/newPassword.html", $replacements);
    }

    if ($type == 'otp2FAEmail' || $type == 'otpVerifyEmail') {
        $replacements = [
            '{{name}}' => $dataJson['name'],
            '{{otp}}' => $dataJson['otp'],
            '{{year}}' => $dataJson['year'],
        ];
        $body = getTemplateContent($domain . "/lib/templatesEmail/otpVerifyEmail.html", $replacements);
    }

    $sendMail = sendMail($emailSending, $subject, $body);
    if ($sendMail['status']) {
        pdo_execute("DELETE FROM `email_queue` WHERE `id` = ?", [$email['id']]);
        jsonReturn(true, "Đã gửi email thành công");
    } else {
        jsonReturn(false, $sendMail['message']);
    }
}
jsonReturn(false, "Không có mail nào đang đợi gữi");
