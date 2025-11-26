<?php
// includes/stripe_connect.php - OAuth helpers for Stripe Connect
$config = require __DIR__ . '/../config.php';

function stripe_connect_authorize_url($redirect_uri, $state = '') {
    global $config;
    $params = http_build_query([
        'response_type' => 'code',
        'client_id' => $config->payments->stripe_connect_client_id,
        'scope' => 'read_write',
        'redirect_uri' => $redirect_uri,
        'state' => $state
    ]);
    return "https://connect.stripe.com/oauth/authorize?$params";
}

function stripe_connect_exchange_code($code) {
    global $config;
    $data = [
        'client_secret' => $config->payments->stripe_secret,
        'code' => $code,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init('https://connect.stripe.com/oauth/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $resp = curl_exec($ch);
    if (curl_errno($ch)) { $e = curl_error($ch); curl_close($ch); throw new Exception($e); }
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $body = json_decode($resp,true);
    if ($http >=200 && $http < 300) return $body;
    throw new Exception("Stripe OAuth error: " . ($body['error_description'] ?? $resp));
}