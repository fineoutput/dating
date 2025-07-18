<?php

return [
    'app_id' => env('CASHFREE_APP_ID'),
    'secret_key' => env('CASHFREE_SECRET_KEY'),
    'env' => env('CASHFREE_ENV', 'TEST'), // TEST or PROD
    'base_url' => env('CASHFREE_ENV', 'TEST') === 'PROD'
        ? 'https://api.cashfree.com/pg'
        : 'https://sandbox.cashfree.com/pg',
];