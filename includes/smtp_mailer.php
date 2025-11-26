<?php
// includes/smtp_mailer.php - minimal SMTP sender using stream_socket_client
$config = require __DIR__ . '/../config.php';

function smtp_send($to, $subject, $body, $from = null) {
    global $config;
    $from = $from ?: ($config->smtp->from_email ?? $config->site->support_email);
    // Fallback to mail() if no SMTP configured
    if (empty($config->smtp->host) || empty($config->smtp->user)) {
        $hdr = "From: {$from}\r\nContent-Type: text/plain; charset=UTF-8\r\n";
        return @mail($to, $subject, $body, $hdr);
    }

    $host = $config->smtp->host;
    $port = $config->smtp->port ?: 587;
    $user = $config->smtp->user;
    $pass = $config->smtp->pass;

    $socket = stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 10);
    if (!$socket) {
        error_log("SMTP connect failed: $errno $errstr");
        return false;
    }

    $get = function() use ($socket) {
        $res = '';
        while ($line = fgets($socket, 515)) {
            $res .= $line;
            if (substr($line,3,1) == ' ') break;
        }
        return $res;
    };
    $send = function($cmd) use ($socket) {
        fwrite($socket, $cmd . "\r\n");
        return fgets($socket, 515);
    };

    $get();
    $send("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    // STARTTLS if needed
    $send("STARTTLS");
    stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
    $send("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    $send("AUTH LOGIN");
    $send(base64_encode($user));
    $send(base64_encode($pass));
    $send("MAIL FROM:<{$from}>");
    $send("RCPT TO:<{$to}>");
    $send("DATA");
    $headers = "From: {$from}\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nSubject: {$subject}\r\n";
    $send($headers . "\r\n" . $body . "\r\n.");
    $send("QUIT");
    fclose($socket);
    return true;
}