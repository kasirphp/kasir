<?php

dataset('payment_type', [

    'Permata VA' => [
        'method' => 'permataVA',
        'type' => 'bank_transfer',
        'option_key' => 'bank_transfer',
        'options' => [
            'va_number' => '1234567890',
        ],
        'expected_options' => [
            'va_number' => '1234567890',
            'bank' => 'permata',
        ],
    ],

    'BCA VA' => [
        'method' => 'bcaVA',
        'type' => 'bank_transfer',
        'option_key' => 'bank_transfer',
        'options' => [],
        'expected_options' => [
            'va_number' => null,
            'bank' => 'bca',
            'bca' => [
                'sub_company_code' => null,
            ],
        ],
    ],

    'BNI VA' => [
        'method' => 'bniVA',
        'type' => 'bank_transfer',
        'option_key' => 'bank_transfer',
        'options' => [
            'va_number' => '1234567890',
        ],
        'expected_options' => [
            'va_number' => '1234567890',
            'bank' => 'bni',
        ],
    ],

    'BRI VA' => [
        'method' => 'briVA',
        'type' => 'bank_transfer',
        'option_key' => 'bank_transfer',
        'options' => [
            'va_number' => '1234567890',
        ],
        'expected_options' => [
            'va_number' => '1234567890',
            'bank' => 'bri',
        ],
    ],

    'Mandiri Bill' => [
        'method' => 'mandiriBill',
        'type' => 'echannel',
        'option_key' => 'echannel',
        'options' => [
            'bill_info1' => 'Payment for:',
            'bill_info2' => 'Testing 123',
        ],
        'expected_options' => [
            'bill_info1' => 'Payment for:',
            'bill_info2' => 'Testing 123',
        ],
    ],

    'BCA KlikPay' => [
        'method' => 'bcaKlikpay',
        'type' => 'bca_klikpay',
        'option_key' => 'bca_klikpay',
        'options' => [
            'description' => 'some string',
        ],
        'expected_options' => [
            'description' => 'some string',
        ],
    ],

    // This payment channel is not currently activated for my account.
    //    'KlikBCA' => [
    //        'method' => 'klikBca',
    //        'type' => 'bca_klikbca',
    //        'option_key' => 'bca_klikbca',
    //        'options' => [
    //            'description' => 'some string',
    //            'user_id' => 'some string',
    //        ],
    //        'expected_options' => [
    //            'description' => 'some string',
    //            'user_id' => 'some string',
    //        ],
    //    ],

    'BRImo' => [
        'method' => 'briMo',
        'type' => 'bri_epay',
        'option_key' => null,
        'options' => [],
        'expected_options' => [],
    ],

    'CIMB Clicks' => [
        'method' => 'cimbClicks',
        'type' => 'cimb_clicks',
        'option_key' => 'cimb_clicks',
        'options' => [
            'description' => 'some string',
        ],
        'expected_options' => [
            'description' => 'some string',
        ],
    ],

    'Danamon Online' => [
        'method' => 'danamonOnline',
        'type' => 'danamon_online',
        'option_key' => null,
        'options' => [],
        'expected_options' => [],
    ],

    'UOB Ezpay' => [
        'method' => 'uobEzpay',
        'type' => 'uob_ezpay',
        'option_key' => null,
        'options' => [],
        'expected_options' => [],
    ],

    'QRIS Gopay' => [
        'method' => 'qris',
        'type' => 'qris',
        'option_key' => 'qris',
        'options' => [
            'acquirer' => 'gopay',
        ],
        'expected_options' => [
            'acquirer' => 'gopay',
        ],
    ],

    'QRIS Airpay Shopee' => [
        'method' => 'qris',
        'type' => 'qris',
        'option_key' => 'qris',
        'options' => [
            'acquirer' => 'airpay shopee',
        ],
        'expected_options' => [
            'acquirer' => 'airpay shopee',
        ],
    ],

    'GoPay' => [
        'method' => 'gopay',
        'type' => 'gopay',
        'option_key' => 'gopay',
        'options' => [
            'enable_callback' => true,
            'callback_url' => 'https://google.com/',
        ],
        'expected_options' => [
            'enable_callback' => true,
            'callback_url' => 'https://google.com/',
        ],
    ],

    'ShopeePay' => [
        'method' => 'shopeepay',
        'type' => 'shopeepay',
        'option_key' => 'shopeepay',
        'options' => [
            'callback_url' => 'https://example.com',
        ],
        'expected_options' => [
            'callback_url' => 'https://example.com',
        ],
    ],

    'Indomaret' => [
        'method' => 'indomaret',
        'type' => 'cstore',
        'option_key' => 'cstore',
        'options' => [
            'message' => 'Purchase for an item',
        ],
        'expected_options' => [
            'message' => 'Purchase for an item',
            'store' => 'indomaret',
        ],
    ],

    'Alfamart' => [
        'method' => 'alfamart',
        'type' => 'cstore',
        'option_key' => 'cstore',
        'options' => [
            'alfamart_free_text_1' => 'some string',
            'alfamart_free_text_2' => 'some string',
            'alfamart_free_text_3' => 'some string',
        ],
        'expected_options' => [
            'alfamart_free_text_1' => 'some string',
            'alfamart_free_text_2' => 'some string',
            'alfamart_free_text_3' => 'some string',
            'store' => 'alfamart',
        ],
    ],

    'Akulaku' => [
        'method' => 'akulaku',
        'type' => 'akulaku',
        'option_key' => null,
        'options' => [],
        'expected_options' => [],
    ],

    'Kredivo' => [
        'method' => 'kredivo',
        'type' => 'kredivo',
        'option_key' => null,
        'options' => [],
        'expected_options' => [],
    ],

]);
