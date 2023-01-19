<?php

it('has the correct array keys', function () {
    $keys = [
        'transaction_details',
        'item_details',
        'customer_details',
        'enabled_payments',
    ];

    $kasir = \Kasir\Kasir\Kasir::make()
        ->grossAmount(100)
        ->orderId('order-id')
        ->itemDetails([])
        ->customerDetails([])
        ->enablePayments([]);

    expect($kasir->toArray())->toHaveKeys($keys);
});

it('has the correct keys', function ($key, $keys) {
    $kasir = \Kasir\Kasir\Kasir::make()
        ->grossAmount(1)
        ->orderId('order-id')
        ->itemDetails([])
        ->customerDetails([])
        ->billingAddress([])
        ->shippingAddress([])
        ->enablePayments([]);

    expect($kasir->toArray()[$key])->toHaveKeys($keys);
})->with([
    'transaction_details' => [
        'transaction_details',
        [
            'gross_amount',
            'order_id',
        ],
    ],
    'customer_details' => [
        'customer_details',
        [
            'billing_address',
            'shipping_address',
        ],
    ],
]);
