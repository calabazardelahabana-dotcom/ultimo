<?php
// includes/stripe_utils.php - higher level helpers: checkout & subscriptions
require_once __DIR__ . '/stripe_curl.php';
$config = require __DIR__ . '/../config.php';

function stripe_create_checkout_session($line_items, $success_url, $cancel_url, $client_reference_id = null, $connected_account = null, $application_fee_cents = null) {
    $data = ['mode'=>'payment','success_url'=>$success_url,'cancel_url'=>$cancel_url];
    if ($client_reference_id) $data['client_reference_id'] = (string)$client_reference_id;
    foreach ($line_items as $i=>$li) {
        $data["line_items[$i][price_data][currency]"] = $li['currency'] ?? 'usd';
        $data["line_items[$i][price_data][product_data][name]"] = $li['name'];
        $data["line_items[$i][price_data][unit_amount]"] = intval($li['unit_amount_cents']);
        $data["line_items[$i][quantity]"] = intval($li['quantity']);
    }
    if ($connected_account) {
        $data['payment_intent_data[transfer_data][destination]'] = $connected_account;
        if (!is_null($application_fee_cents)) $data['payment_intent_data[application_fee_amount]'] = intval($application_fee_cents);
    }
    return stripe_request('POST', 'checkout/sessions', $data);
}

function stripe_create_subscription_session($price_id, $success_url, $cancel_url, $client_reference_id = null, $connected_account = null, $application_fee_cents = null) {
    $data = ['mode'=>'subscription','success_url'=>$success_url,'cancel_url'=>$cancel_url];
    if ($client_reference_id) $data['client_reference_id'] = (string)$client_reference_id;
    $data['line_items[0][price]'] = $price_id;
    $data['line_items[0][quantity]'] = 1;
    if ($connected_account) {
        $data['subscription_data[transfer_data][destination]'] = $connected_account;
        if (!is_null($application_fee_cents)) $data['payment_intent_data[application_fee_amount]'] = intval($application_fee_cents);
    }
    return stripe_request('POST', 'checkout/sessions', $data);
}