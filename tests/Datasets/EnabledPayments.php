<?php

dataset('enabled_payments', function () {
    $payments = [];

    $available_payments = [
        'credit_card',
        'cimb_clicks',
        'bca_klikbca',
        'bca_klikpay',
        'bri_epay',
        'echannel',
        'permata_va',
        'bca_va',
        'bni_va',
        'bri_va',
        'danamon_online',
        'uob_ezpay',
        'gopay',
        'shopeepay',
        'indomaret',
        'alfamart',
        'akulaku',
        'kredivo',
    ];

    $num_array = 2;

    for ($i = 0; $i < $num_array; $i++) {
        shuffle($available_payments);
        $num_items = rand(1, count($available_payments));
        $payments[$num_items.' items'][] = array_slice($available_payments, 0, $num_items);
    }

    return $payments;
});
