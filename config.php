<?php
// config.php - Configuración principal MassolaCommerce
return (object)[
    'db' => (object)[
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'massolag_commerce_nat',
        'user' => 'massolag_amassola',
        'pass' => 'Luyano8906*',
        'charset' => 'utf8mb4'
    ],
    'site' => (object)[
        'name' => 'MassolaCommerce',
        'url'  => 'https://negocios.massolagroup.com',
        'brand' => 'MassolaGroup',
        'support_email' => 'soporte@massolagroup.com',
        'sales_email' => 'comercial@massolagroup.com'
    ],
    'payments' => (object)[
        'stripe_secret' => '',
        'stripe_publishable' => '',
        'stripe_connect_client_id' => '',
        'stripe_webhook_secret' => ''
    ],
    'platform' => (object)[
        'platform_fee_percent' => 10
    ],
    'smtp' => (object)[
        'host' => '',
        'port' => 587,
        'user' => '',
        'pass' => '',
        'from_email' => 'no-reply@massolagroup.com',
        'from_name' => 'MassolaGroup'
    ],
    'paths' => (object)[
        'root' => __DIR__,
        'includes' => __DIR__ . '/includes',
        'public' => __DIR__ . '/public',
        'storage' => __DIR__ . '/storage',
        'uploads' => __DIR__ . '/storage/uploads',
        'logs' => __DIR__ . '/storage/logs'
    ]
];
