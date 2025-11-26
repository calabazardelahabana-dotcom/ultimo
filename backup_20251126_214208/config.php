<?php
// config.php - agregar campos de Stripe Connect y webhook
return (object)[
    'db' => (object)[
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'massolag_negocios',
        'user' => 'massolag_negocios',
        'pass' => 'Luyano8906*',
        'charset' => 'utf8mb4'
    ],
    'site' => (object)[
        'name' => 'MassolaGroup Negocios',
        'url'  => 'https://negocios.massolagroup.com',
        'brand' => 'MassolaGroup',
        'support_email' => 'soporte@negocios.massolagroup.com',
        'sales_email' => 'comercial@negocios.massolagroup.com'
    ],
    'payments' => (object)[
        'stripe_secret' => '',                // sk_live_xxx or sk_test_xxx
        'stripe_publishable' => '',
        'stripe_connect_client_id' => '',     // client_id de Connect
        'stripe_webhook_secret' => ''         // webhook signing secret
    ],
    'platform' => (object)[
        'platform_fee_percent' => 10          // % de comisión por defecto (enteros)
    ],
    'smtp' => (object)[
        'host' => '',
        'port' => 587,
        'user' => '',
        'pass' => '',
        'from_email' => 'no-reply@negocios.massolagroup.com',
        'from_name' => 'MassolaGroup'
    ]
];