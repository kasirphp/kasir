<?php

use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('deny a challenged card transaction as response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $charge = $kasir->charge();
    $deny = $charge->deny();

    expect($charge->successful())->toBeTrue()
        ->and($deny->ok())->toBeTrue();
});

test('deny a challenged card transaction using response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $response = $kasir->charge();
    $deny = Kasir::deny($response);

    expect($response->successful())->toBeTrue()
        ->and($deny->ok())->toBeTrue();
});

test('deny a challenged card transaction using order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $response = $kasir->charge();
    $deny = Kasir::deny($kasir->getOrderId());

    expect($response->successful())->toBeTrue()
        ->and($deny->ok())->toBeTrue();
});
