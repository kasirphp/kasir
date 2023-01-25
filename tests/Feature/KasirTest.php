<?php

use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

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
        ->paymentType(null);

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

it('can create payment type object', function ($method, $type, $option_key, $options, $expected_options) {
    $kasir = Kasir::make(1)
        ->$method(...$options);

    expect($kasir->toArray())->toHaveKey('payment_type')
        ->and($kasir->getPaymentType())->toBe($type)
        ->and($kasir->getPaymentOptionKey())->toBe($option_key)
        ->and($kasir->getPaymentOptions())->toBe($expected_options)->toBe($kasir->toArray()[$option_key]);
})->with('payment_type');

test('get transaction status from non-static method or static method with transaction_id or order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $charge = $kasir->charge();
    $order_id = $kasir->getOrderId();
    $transaction_id = $charge->transactionId();

    $status_non_static = $kasir->status();
    $status_static = Kasir::getStatus($order_id);
    $status_static_transaction_id = Kasir::getStatus($transaction_id);

    expect($status_non_static->json())
        ->toBe($status_static->json())
        ->toBe($status_static_transaction_id->json());
});

test('capture an authorized card transaction as response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $charge = $kasir->charge();
    $capture = $charge->capture();

    expect($charge->ok())->toBeTrue()
        ->and($capture->ok())->toBeTrue()
        ->and($capture->json('channel_response_code'))->toBe('00');
});

test('capture an authorized card transaction using response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $capture = Kasir::capture($response);

    expect($response->ok())->toBeTrue()
            ->and($capture->ok())->toBeTrue()
            ->and($capture->json('channel_response_code'))->toBe('00');
});

test('capture an authorized card transaction using transaction_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $capture = Kasir::capture($response->transactionId());

    expect($response->ok())->toBeTrue()
        ->and($capture->ok())->toBeTrue()
        ->and($capture->json('channel_response_code'))->toBe('00');
});

test('capture throws MidtransApiException if the card is challenged', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $response = $kasir->charge();
    expect(fn () => $response->capture())->toThrow(MidtransApiException::class);
});
