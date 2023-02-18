---
title: Core API

---

[[toc]]

## Getting Started

Payment API is intended for performing transactions and deduct funds from the customer, depending on the payment method
selected. You can charge, tokenize credit card, approve, deny, refund, or cancel a transactions.

Every method in this page will return a `MidtransResponse` instance, which inherits from Laravel `Response` class with
additional features and methods.

> Before you start using this API, you need to configure the (Payment Methods)[payment-methods]. You may also need to
> configure the [Transaction Details](transaction-details).

## Charge Transaction

To perform a transaction with various available payment methods and features, you can use `->charge()` method. This will
return a `MidtransResponse` instance.

```php
// use Kasir\Kasir\Kasir;

$kasir = Kasir::make(10000)
    ->qris(); // or any payment method you choose.

$response = $kasir->charge();
```

## Capture a Credit Card or GoPay Transaction

To capture a credit card or GoPay transaction, you can use `capture()` static method. This method receives
a `MidtransResponse` instance. Capture transaction is triggered to capture the transaction balance
when `'transaction_status' => 'authorize'`.

This method receives a `MidtransResponse` or Order ID or Transaction ID as its parameter. You can
use `Kasir::capture()` method to capture a transaction.

```php
$kasir = Kasir::make(10000)
    ->creditCard(CreditCard::make($card_number, $card_exp_month, $card_exp_year, $card_cvv));

$response = $kasir->charge();

$captured_transaction = Kasir::capture($response);
```

You can also capture a transaction using `MidtransResponse` instance.

```php
$captured_transaction = $response->capture();
```

## Approve and Deny a Credit Card Transaction

When you charge using, there is a chance for the credit card number to be challenged by Fraud Detection System (FDS).
In this case, the transaction will be put on hold, and you will need to approve or deny the transaction.

To approve a challenged credit card transaction, you can use `approve()` static method. This method receives
a `MidtransResponse` or Order ID or Transaction ID.

```php
$approved_transaction = Kasir::approve($response);
// or
$approved_transaction = $response->approve();
```

To deny a challenged credit card transaction, you can use `deny()` static method. This method receives
a `MidtransResponse` or Order ID or Transaction ID.

```php
$denied_transaction = Kasir::deny($response);
// or
$denied_transaction = $response->deny();
```

## Cancel a Transaction

Cancel transaction is triggered to void the transaction. If transaction is already settled (`'status' => 'settlement`)
you should perform [refund](#refunding-a-transaction) instead if the payment method supports it.

To cancel a transaction, you can use `cancel()` static method. This method receives a `MidtransResponse` or Order ID or
Transaction ID.

```php
$canceled_transaction = Kasir::cancel($response);
// or
$canceled_transaction = $response->cancel();
```

## Expire a Transaction

Expire transaction is triggered to update the `transaction_status` to `expire`, when the customer fails to complete the
payment. The expired `order_id` can be reused for the same or different payment methods.

To expire a transaction, you can use `expire()` static method. This method receives a `MidtransResponse` or Order ID or
Transaction ID.

```php
$expired_transaction = Kasir::expire($response);
// or
$expired_transaction = $response->expire();
```

## Refund a Transaction

Refund transaction is called to reverse the money back to customers for transactions with payment status `settlement`.
If transaction's status is still Pending Authorize or Capture please use [`cancel`](#canceling-a-transaction) instead.
The same `refund_id` cannot be reused.

Refund transaction is supported only for `creditCard()` payment method. Banks which support this method are BNI,
Mandiri, and CIMB.

With Refund, refund request is made to Midtrans where Midtrans will then forward it to payment providers. Do note that
some payment method only supports refunding via [Direct Refund method](#direct-refund-a-transaction) and vice versa.

> For more information, please refer to Midtrans Documentation
> on [Refund Transaction](https://docs.midtrans.com/reference/refund-transaction).

To refund a transaction, you can use `refund()` static method. This method receives a `MidtransResponse` or Order ID or
Transaction ID.

```php
$refunded_transaction = Kasir::refund($response);
// or
$refunded_transaction = $response->refund();
```

## Direct Refund a Transaction

Unlike the [Refund Transaction](#refund-a-transaction), Direct Refund transaction is triggered to send the refund
request directly to the bank or to the third-party payment provider for transaction with payment status Settlement. If
payment status is still in either Capture, Pending or Authorize, use the Cancel API instead. This method is faster than
the standard operation process which may take one to two days, after the initial refund request. The status of
corresponding transaction is updated immediately after receiving refund result from the third-party payment provider.
HTTP notification is sent only if the refund is successful. Do note that some payment method only supports refunding via
[Refund method](#refund-a-transaction), and vice versa.

Direct Refund transaction is only supported for GoPay, QRIS, ShopeePay, and Credit Card payment methods. Currently for
Credit Card payment method, Midtrans only supports BCA, MAYBANK, and BRI banks.

For QRIS with acquirers AirPay (Shopee) and ShopeePay, the maximum refund window is 24 hours since the transaction is
paid. Refund is not allowed from 11:55 PM to 6:00 AM GMT+7. If you send Direct Refund during this timeframe, the request
gets rejected by ShopeePay.

> For more information, please refer to Midtrans Documentation
> on [Direct Refund Transaction](https://docs.midtrans.com/reference/direct-refund-transaction).

To direct refund a transaction, you can use `directRefund()` static method. This method receives a `MidtransResponse` or
Order ID or Transaction ID.

```php
$refunded_transaction = Kasir::directRefund($response);
// or
$refunded_transaction = $response->directRefund();
```

## Get Transaction Status

To get the transaction status, you can use `getStatus()` static method. This method receives a `MidtransResponse` or
Order
ID or Transaction ID.

```php
$transaction_status = Kasir::getStatus($order_id);
```

or you can use `status()` method on Kasir instance with Order ID.

```php
$transaction = Kasir::make(10000)
    ->creditCard(CreditCard::make($card_number, $card_exp_month, $card_exp_year, $card_cvv));

$kasir->charge();

$transaction_status = $kasir->status();
```