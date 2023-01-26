<?php

use Kasir\Kasir\Kasir;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

test('get transaction status from non-static method or static method with transaction_id or order_id', function () {
    $kasir = Kasir::make(1)
        ->creditCard(CreditCard::make('4811 1111 1111 1114', '01', '2025', '123'));

    $charge = $kasir->charge();
    $order_id = $kasir->getOrderId();
    $transaction_id = $charge->transactionId();

    $status_non_static = $kasir->status();
    $status_static = Kasir::getStatus($order_id);
    $status_static_transaction_id = Kasir::getStatus($transaction_id);

    expect($status_non_static->json())
        ->toBe($status_static->json())
        ->toBe($status_static_transaction_id->json());
});
