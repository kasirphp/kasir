<?php

use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('expire a transaction as response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $expire = $response->expire();

    expect($response->successful())->toBeTrue()
        ->and($expire->ok())->toBeTrue();
});

test('expire a transaction using response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $expire = Kasir::expire($response);

    expect($response->successful())->toBeTrue()
        ->and($expire->ok())->toBeTrue();
});

test('expire a transaction using order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $expire = Kasir::expire($kasir->getOrderId());

    expect($response->successful())->toBeTrue()
        ->and($expire->ok())->toBeTrue();
});
