<p align="center">
    <img src="art/svg/logo-wordmark-side.svg" alt="Kasir Logo">
</p>

<p align="center">
    <a href="https://packagist.org/packages/kasir/kasir"><img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/kasir/kasir"></a>
    <a href="https://packagist.org/packages/kasir/kasir"><img alt="Packagist PHP Version" src="https://img.shields.io/packagist/dependency-v/kasir/kasir/illuminate/contracts"></a>
    <a href="https://packagist.org/packages/kasir/kasir"><img alt="Packagist PHP Version" src="https://img.shields.io/packagist/dependency-v/kasir/kasir/php"></a>
    <a href="https://packagist.org/packages/kasir/kasir"><img src="https://img.shields.io/packagist/l/kasir/kasir" alt="License"></a>
</p>

<p align="center">
    <a href="https://github.com/kasirphp/kasir/actions/workflows/tests.yml"><img src="https://github.com/kasirphp/kasir/actions/workflows/tests.yml/badge.svg" /></a>
</p>

## Introduction

Kasir helps integrate Midtrans in Laravel way.

## Usage

In your controller or Livewire component, you can write:

```php
Snap::make(200000)
    ->customerDetails($user)
    ->billingAddress($billing_address)
    ->shippingAddress($shipping_address)
    ->itemDetails($items)
    ->redirect();
```

## Installation

Please refer to [Kasir Installation Page](https://github.com/kasirphp/kasir/wiki/Installation).

## Contributing

If you want to contribute to Kasir package, you may want to test it in a real Laravel project:

1. Fork this project to your GitHub account.
2. Create Laravel app locally.
3. Clone **your fork** in your Laravel app's root directory.
4. In the `/kasir` directory, create a new branch for your fix, e.g. `fix-something`.
5. Install the packages in your app's `composer.json`:

```json
{
    ...
    "require": {
        "kasir/kasir": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "kasir/*"
        }
    ],
    ...
}
```

6. Run `composer update`.

## Capability

### Payment API

- [x] Tokenize payment card information before being charged.
- [x] Perform a transaction with various available payment methods and features.
- [x] Capture an authorized transaction for card payment.
- [x] Approve a transaction with certain `order_id` which gets challenge status from Fraud Detection System.
- [x] Deny a transaction with a specific `order_id`, flagged as challenge by Fraud Detection System.
- [ ] Cancel a transaction with a specific `order_id`. Cancelation can only be done before settlement process.
- [ ] Expire a transaction with a specific `order_id`. Expiration can only be done before settlement process.
- [ ] Refund a transaction with a specific `order_id`. Refund can only be done after settlement process.
- [ ] Send refund to the customer's bank or the payment provider and update the transaction status to `refund`.
- [x] Get the transaction status of a specific `order_id`.
- [ ] Get the transaction status multiple B2B transactions related to certain `order_id`.
- [ ] Register customer's card information (card number and expiry) to be used for One Click and Two Click transactions.
- [ ] Get the point balance of the card in denomination amount.
- [ ] Used to link the customer's account to create payment for certain channel.
- [ ] Get customer payment account details.
- [ ] Unbind a linked customer account.
- [ ] Get Bin Metadata.

## License

Kasir is open-sourced software licensed under the [MIT license](LICENSE.md).
