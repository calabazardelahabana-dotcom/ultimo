<?php
// includes/stripe_curl.php - low-level Stripe HTTP helper
$config = require __DIR__ . '/../config.php';

function stripe_request($method, $path, $data = [], $headers = []) {
    global $config;
    if (empty($config->payments->stripe_secret)) throw new Exception("Stripe secret key not configured");
    $url = (strpos($path, 'http') === 0) ? $path : "https://api.stripe.com/v1/{$path}";
    $ch = curl_init();
    $baseHeaders = [
        'Authorization: Bearer ' . $config->payments->stripe_secret,
        'Content-Type: application/x-www-form-urlencoded'
    ];
    $allHeaders = array_merge($baseHeaders, $headers);
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $allHeaders,
        CURLOPT_TIMEOUT => 30,
    ]);
    if (!empty($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $resp = curl_exec($ch);
    if (curl_errno($ch)) { $err = curl_error($ch); curl_close($ch); throw new Exception($err); }
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status'=>$http, 'body'=>json_decode($resp, true), 'raw'=>$resp];
}