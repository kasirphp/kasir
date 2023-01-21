<?php

use Kasir\Kasir\Kasir;

it('has the correct array keys', function () {
    $keys = [
        'transaction_details',
        'item_details',
        'customer_details',
        'enabled_payments',
    ];

    $kasir = Kasir::make()
        ->grossAmount(100)
        ->orderId('order-id')
        ->itemDetails([])
        ->customerDetails([])
        ->enablePayments([]);

    expect($kasir->toArray())->toHaveKeys($keys);
});

it('has the correct keys', function ($key, $keys) {
    $kasir = Kasir::make()
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

it('can create transactions from array', function ($items, $customer, $address, $payments) {
    $payload = [
        'transaction_details' => [
            'gross_amount' => rand(1, 100) * 1000,
            'order_id' => Str::orderedUuid()->toString(),
        ],
    ];
    $payload['item_details'] = $items;
    $payload['customer_details'] = $customer;
    $payload['customer_details']['billing_address'] = array_merge($customer, $address);
    $payload['customer_details']['shipping_address'] = array_merge($customer, $address);
    $payload['enabled_payments'] = $payments;

    $kasir = Kasir::fromArray($payload);

    $configured_payload = Kasir::configurePayload($payload);

    expect($kasir->toArray())->toBeArray()->toBe($configured_payload);
})->with('item_details')
    ->with('customer_details')
    ->with('address')
    ->with('enabled_payments');
