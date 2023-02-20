<?php

use Kasir\Kasir\GoPay;

test('gopay', function () {
    $bind = GoPay::bind('08123456789', 'https://google.com');
    dump($bind->actions());

    $account_id = $bind->accountId();
    $status = GoPay::make($account_id)->status();
    dump($status);

    $gopay = GoPay::make($account_id);
    $paylater = $gopay->payLater();
    $wallet = $gopay->wallet();
    dump($paylater, $wallet);
})->only();
