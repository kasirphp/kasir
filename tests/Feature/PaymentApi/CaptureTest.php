<?php

use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

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

test('capture an authorized card transaction using order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $response = $kasir->charge();
    $capture = Kasir::capture($kasir->getOrderId());

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
