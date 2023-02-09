<?php

use Kasir\Kasir\Kasir;

it('calculates gross_amount', function () {
    $kasir = Kasir::make()
        ->grossAmount(100);

    expect($kasir->getGrossAmount())->toBe(100);

    $kasir = $kasir->itemDetails([
        [
            'id' => 'item-id',
            'price' => 300,
            'quantity' => 1,
            'name' => 'item-name',
        ],
    ]);

    expect($kasir->getGrossAmount())->toBe(300);
});

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
        ->enablePayments([])
        ->paymentMethod(null);

    expect($kasir->toArray())->toHaveKeys($keys);
});

it('has the correct keys', function ($key, $keys) {
    $kasir = Kasir::make()
        ->grossAmount(1)
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

it('configures transaction details from array', function ($items, $customer, $address, $payments) {
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
    $kasir_array = $kasir->toArray();
    unset($kasir_array['transaction_details']['order_id']);

    $manual = Kasir::make($payload['transaction_details']['gross_amount'])
        ->orderId($payload['transaction_details']['order_id'])
        ->itemDetails($payload['item_details'])
        ->customerDetails($payload['customer_details'])
        ->billingAddress($payload['customer_details']['billing_address'])
        ->shippingAddress($payload['customer_details']['shipping_address'])
        ->enablePayments($payload['enabled_payments']);
    $manual_array = $manual->toArray();
    unset($manual_array['transaction_details']['order_id']);

    expect($kasir_array)->toBeArray()->toBe($manual_array);
})->with('item_details')
    ->with('customer_details')
    ->with('address')
    ->with('enabled_payments');

it('can create payment type object', function ($method, $type, $option_key, $options, $expected_options) {
    $kasir = Kasir::make(1)
        ->$method(...$options);

    expect($kasir->toArray())->toHaveKey('payment_type')
        ->and($kasir->getPaymentType())->toBe($type)
        ->and($kasir->getPaymentOptionKey())->toBe($option_key)
        ->and($kasir->getPaymentOptions())->toBe($expected_options)->toBe($kasir->toArray()[$option_key]);
})->with('payment_type');
