<?php

use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('cancel a transaction as response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $cancel = $response->cancel();

    expect($response->ok())->toBeTrue()
        ->and($cancel->ok())->toBeTrue()
        ->and($cancel->json('channel_response_code'))->toBe('00');
});

test('cancel a challenged card transaction using response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4411 1111 1111 1118', '01', '2025', '123'));

    $response = $kasir->charge();
    $cancel = Kasir::cancel($response);

    expect($response->successful())->toBeTrue()
        ->and($cancel->ok())->toBeTrue();
});

test('cancel a challenged card transaction using order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4411 1111 1111 1118', '01', '2025', '123'));

    $response = $kasir->charge();
    $cancel = Kasir::cancel($kasir->getOrderId());

    expect($response->successful())->toBeTrue()
        ->and($cancel->ok())->toBeTrue();
});
