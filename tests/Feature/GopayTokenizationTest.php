<?php

use Kasir\Kasir\GoPay;

test('gopay tokenization', function () {
    $bind = GoPay::bind('08123456789', 'https://google.com');

    expect($bind->accountId())->toBeString();

    if ($bind->actions()) {
        expect($bind->action('activation-link-url')['url'])->toStartWith('https://api.sandbox.midtrans.com/v2/pay/account/');
    }
});
