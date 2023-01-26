<?php

use Kasir\Kasir\Payment\CreditCard\CardToken;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('get credit card token from Midtrans', function ($card_number) {
    $token = CreditCard::make($card_number, '01', '2027', '123')->getToken();

    expect($token)->toBeInstanceOf(CardToken::class)
        ->and($token->token())->toBeString();
})->with('credit_card');
