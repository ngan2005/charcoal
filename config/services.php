<?php

return [
    // VNPay Payment Configuration
    'vnpay' => [
        'vnp_Url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'vnp_TmnCode' => env('VNPAY_TMN_CODE', ''),
        'vnp_HashSecret' => env('VNPAY_HASH_SECRET', ''),
        'vnp_ReturnUrl' => env('VNPAY_RETURN_URL', 'http://charcoal.test/checkout/vnpay/return'),
        'vnp_Api' => env('VNPAY_API', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    ],
];
