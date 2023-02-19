---
title: Payment Methods

---

# Payment Methods

[[toc]]

## Getting Started

Payment API is intended for performing transactions and deduct funds from the customer, depending on the payment method
selected. You can tokenize, approve, deny, refund, or cancel a transactions.

Every method in this page will return a `MidtransResponse` instance, which inherits from Laravel `Response` class with
additional features and methods.

## Card

Using Card payment method, customers can make payments using a credit card or any online-transaction-capable debit card
within Visa, MasterCard, JCB, or Amex network. Midtrans sends real-time notification when the customer completes the
payment.

You can use `->creditCard()` method to perform a card payment. This method receives a `CreditCard` instance as its
parameter. You can use `CreditCard::make()` method to create a new instance.

The `CreditCard` class has 4 required parameters; `card_number`, `card_exp_month`, `card_exp_year`, and `card_cvv`.

Under the hood, when you charge this payment method, Kasir will tokenize the card using Midtrans API and can be used to
perform a charge using the tokenized card.

```php
// use Kasir\Kasir\Kasir;
// use Kasir\Kasir\Payment\CreditCard\CreditCard;

$card_number = '4811 1111 1111 1114';
$card_exp_month = '01';
$card_exp_year = '2025';
$card_cvv = '123';

$kasir = Kasir::make(10000)
    ->creditCard(CreditCard::make($card_number, $card_exp_month, $card_exp_year, $card_cvv));
```

## Bank Transfer

Bank Transfer is one of the payment methods offered by Midtrans. By using this method, your customers can make a payment
via bank transfer and Midtrans will send real time notification when the payment is completed.

A list of bank transfer payment methods supported by Midtrans is given below.

- Permata Virtual Account
- BCA Virtual Account
- BNI Virtual Account
- BRI Virtual Account
- Mandiri Bill

### Permata Virtual Account

Use `->permataVA()` method to perform a Permata Virtual Account payment. This method receives these parameters:

- `$va_number` - Custom VA number assigned by you.
- `$recipient_name` - Recipient name shown on the payment details.

```php
$kasir = Kasir::make(10000)
    ->permataVA('1234567890', 'John Doe');
```

### BCA Virtual Account

Use `->bcaVA()` method to perform a BCA Virtual Account payment. This method receives these parameters:

- `$va_number` - Custom VA number assigned by you.
- `$sub_company_code` - BCA sub company code directed for this transactions.
- `$inquiry_text_en` - English Inquiry Text.
- `$inquiry_text_id` - Indonesian Inquiry Text.
- `$payment_text_en` - English Payment Text.
- `$payment_text_id` - Indonesian Payment Text.

```php
$kasir = Kasir::make(10000)
    ->bcaVA('1234567890', '12345', 'Inquiry Text', 'Inquiry Text', 'Payment Text', 'Payment Text');
```

### BNI Virtual Account

Use `->bniVA()` method to perform a Mandiri Bill Payment. This method receives a `$va_number` parameter.

```php
$kasir = Kasir::make(10000)
    ->bniVA('1234567890');
```

### BRI Virtual Account

Use `->briVA()` method to perform a Mandiri Bill Payment. This method receives a `$va_number` parameter.

```php
$kasir = Kasir::make(10000)
    ->briVA('1234567890');
```

### Mandiri Bill

Use `->mandiriBill()` method to perform a Mandiri Bill Payment. This method receives these parameters:

- `$bill_info1` - Label 1. `Required`
- `$bill_info2` - Value for Label 1. `Required`
- `$bill_info3` - Label 2.
- `$bill_info4` - Value for Label 2.
- `$bill_info5` - Label 3.
- `$bill_info6` - Value for Label 3.
- `$bill_info7` - Label 4.
- `$bill_info8` - Value for Label 4.
- `$bill_key` - Custom bill key assigned by you.

```php
$kasir = Kasir::make(10000)
    ->mandiriBill('Payment for', 'Order #123');
```

## Direct Debit

### BCA Klikpay

Use `->bcaKlikpay()` method to perform a BCA Klikpay payment. This method receives these parameters:

- `$description` - Description of the payment. `Required`
- `$misc_fee` - Additional fee for documentation.

```php
$kasir = Kasir::make(10000)
    ->bcaKlikpay('Payment for Order #123');
```

### KlikBCA

Use `->klikBca()` method to perform a KlikBCA payment. This method receives these parameters:

- `$description` - Description of the payment. `Required`
- `$user_id` - KlikBCA User ID. `Required`

```php
$kasir = Kasir::make(10000)
    ->klikBca('Payment for Order #123', '1');
```

### Danamon Online

Use `->danamonOnline()` method to perform a Danamon Online payment.

```php
$kasir = Kasir::make(10000)
    ->danamonOnline();
```

### BRImo

Use `->briMo()` method to perform a BRImo payment.

```php
$kasir = Kasir::make(10000)
    ->briMo();
```

### CIMB Clicks

Use `->cimbClicks()` method to perform a CIMB Clicks payment. This method receives this parameter:

- `$description` - Description of the payment. `Required`

```php
$kasir = Kasir::make(10000)
    ->cimbClicks('Payment for Order #123');
```

### UOB EZpay

Use `->uobEzpay()` method to perform a UOB EZpay payment.

```php
$kasir = Kasir::make(10000)
    ->uobEzpay();
```

## E-Money

### QRIS

Use `->qris()` method to perform a QRIS payment. This method receives 'acquirer' as a parameter. The possible value
for `acquirer` is `'gopay'` or `'airpay shopee'`.

```php
$kasir = Kasir::make(10000)
    ->qris('gopay');

//or

$kasir = Kasir::make(10000)
    ->qris('airpay shopee');
```

### GoPay

Use `->gopay()` method to perform a GoPay payment. This method receives these parameters:

- `$enable_callback` - Required for GoPay deeplink/QRIS. To determine appending callback URL in the deeplink. Default
  value: `false`.
- `$callback_url` - The HTTP or Deeplink URL to which the customer is redirected from Gojek app after successful
  payment. Default value: callback_url in dashboard settings.
- `$account_id` - Required for GoPay Tokenization. The customer account ID linked during Create Pay Account API.
- `$payment_option_token` - Required for GoPay Tokenization. Token to specify the payment option made by the customer
  from Get Pay Account API metadata.
- `$pre_auth` - Set the value to true to reserve the specified amount from the customer balance. Once the customer
  balance is reserved, you can initiate a subsequent Capture API request. Default value: `false`.
- `$recurring` - Set the value to true to mark as a recurring payment. Default value: `false`.

```php
$kasir = Kasir::make(10000)
    ->gopay(true, 'https://yourdomain.com/payment/success');
```

### ShopeePay

Use `->shopeepay()` method to perform a ShopeePay payment. This method receives `callback_url` as a parameter, which is
the HTTP or Deeplink URL to which the customer is redirected from Shopee app after successful payment.

```php
$kasir = Kasir::make(10000)
    ->shopeepay('https://yourdomain.com/payment/success');
```

## Over the Counter

### Indomaret

Use `->indomaret()` method to perform a Indomaret payment. This method receives `message` as a parameter.

```php
$kasir = Kasir::make(10000)
    ->indomaret('Payment for Order #123');
```

### Alfamart

Use `->alfamart()` method to perform a Alfamart payment. This method receives these parameters:

- `$alfamart_free_text_1` - Customizable first row of text on the Alfamart printed receipt.
- `$alfamart_free_text_2` - Customizable second row of text on the Alfamart printed receipt.
- `$alfamart_free_text_3` - Customizable third row of text on the Alfamart printed receipt.

```php
$kasir = Kasir::make(10000)
    ->alfamart('Payment for', 'Order #123');
```

## Cardless Credit

### Akulaku

Use `->akulaku()` method to perform a Akulaku payment.

```php
$kasir = Kasir::make(10000)
    ->akulaku();
```

### Kredivo

Use `->kredivo()` method to perform a Kredivo payment.

```php
$kasir = Kasir::make(10000)
    ->kredivo();
```
