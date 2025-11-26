<?php
// includes/stripe_payouts.php - transfers and payout helpers
require_once __DIR__ . '/stripe_curl.php';

function stripe_create_transfer_to_connected($connected_account, $amount_cents, $currency='usd', $description='') {
    if (empty($connected_account)) throw new Exception("Connected account required");
    $data = ['amount'=>intval($amount_cents), 'currency'=>$currency, 'destination'=>$connected_account, 'description'=>$description];
    return stripe_request('POST', 'transfers', $data);
}

function stripe_create_payout_on_connected($connected_account, $amount_cents, $currency='usd') {
    if (empty($connected_account)) throw new Exception("Connected account required");
    $headers = ['Stripe-Account: ' . $connected_account];
    $data = ['amount'=>intval($amount_cents), 'currency'=>$currency];
    return stripe_request('POST', 'payouts', $data, $headers);
}

function stripe_get_balance($connected_account = null) {
    $headers = $connected_account ? ['Stripe-Account: ' . $connected_account] : [];
    return stripe_request('GET', 'balance', [], $headers);
}