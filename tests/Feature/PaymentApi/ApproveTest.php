<?php

use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('approve a challenged card transaction as response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $charge = $kasir->charge();
    $approve = $charge->approve();

    expect($charge->successful())->toBeTrue()
        ->and($approve->ok())->toBeTrue();
});

test('approve a challenged card transaction using response', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $response = $kasir->charge();
    $approve = Kasir::approve($response);

    expect($response->successful())->toBeTrue()
        ->and($approve->ok())->toBeTrue();
});

test('approve a challenged card transaction using order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4511 1111 1111 1117', '01', '2025', '123'));

    $response = $kasir->charge();
    $approve = Kasir::approve($kasir->getOrderId());

    expect($response->successful())->toBeTrue()
        ->and($approve->ok())->toBeTrue();
});
