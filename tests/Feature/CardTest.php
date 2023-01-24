<?php

use Kasir\Kasir\Payment\CreditCard\CardToken;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('get Token', function () {
    $cardNumber = '4911 1111 1111 1113';
    $cardExpMonth = '01';
    $cardExpYear = '2025';
    $cardCvv = '123';

    $token = CreditCard::make($cardNumber, $cardExpMonth, $cardExpYear, $cardCvv)->getToken();

    expect($token)->toBeInstanceOf(CardToken::class)
        ->and($token->token())->toBeString();
});
