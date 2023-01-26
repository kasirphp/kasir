<?php

use Kasir\Kasir\Helper\MidtransResponse;
use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('perform transaction with credit card as payment method', function ($card_number) {
    $kasir = Kasir::make(1)
        ->creditCard(
            CreditCard::make($card_number, '01', '2027', '123')->token()
        );

    $response = $kasir->charge();

    expect($response->successful())->toBeTrue();
})->with('credit_card');

test(
    'perform transaction with various available payment methods',
    function ($method, $type, $option_key, $options, $expected_options) {
        $kasir = Kasir::make(1)
            ->$method(...$options);

        $array = $kasir->toArray();
        $response = $kasir->charge();

        expect($array['payment_type'])->toBe($type)
            ->and($array[$option_key])->toBe($expected_options)
            ->and($response)->toBeInstanceOf(MidtransResponse::class)
            ->and($response->successful())->toBeTrue();
    }
)->with('payment_type');
