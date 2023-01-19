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

## License

Kasir is open-sourced software licensed under the [MIT license](LICENSE.md).
