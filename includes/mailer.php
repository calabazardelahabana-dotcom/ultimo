<?php
// includes/mailer.php - wrapper using smtp_send() or mail()
require_once __DIR__ . '/smtp_mailer.php';
$config = require __DIR__ . '/../config.php';

function send_mail($to, $subject, $body, $from = null) {
    global $config;
    $from = $from ?: ($config->smtp->from_email ?? $config->site->support_email);
    if (!empty($config->smtp->host) && !empty($config->smtp->user)) {
        return smtp_send($to, $subject, $body, $from);
    }
    $hdr = "From: {$from}\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    return @mail($to, $subject, $body, $hdr);
}