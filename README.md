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
$kasir = Kasir::make()
    ->customerDetails($user)
    ->billingAddress($billing_address)
    ->shippingAddress($shipping_address)
    ->itemDetails($items);

return $kasir->charge();
```

## Installation

Please refer to [the documentation](https://kasirphp.com/installation).

## Community and Discussions

<p align="center">
    <a href="https://twitter.com/i/communities/1623376036779130881" target="_blank"><img alt="Twitter Community" src="https://img.shields.io/twitter/url?label=Twitter%20Community&style=social&url=https%3A%2F%2Ftwitter.com%2Fi%2Fcommunities%2F1623376036779130881"></a>
    <a href="https://github.com/kasirphp/kasir/discussions"><img alt="Github Discussions" src="https://img.shields.io/twitter/url?label=GitHub%20Discussions&logo=GitHub&style=social&url=https%3A%2F%2Fgithub.com%2Fkasirphp%2Fkasir%2Fdiscussions"></a>
</p>

## Contributing

If you want to contribute to Kasir package, you may want to test it in a real Laravel project:

1. Fork this project to your GitHub account.
2. Create Laravel app locally.
3. Clone **your fork** in your Laravel app's root directory.
4. In the `/kasir` directory, create a new branch for your fix, e.g. `fix-something`.
5. Install the packages in your app's `composer.json`:
    ```json
    {
        "require": {
            "kasir/kasir": "*"
        },
        "repositories": [
            {
                "type": "path",
                "url": "kasir/*"
            }
        ]
    }
    ```
6. Run `composer update`.

## License

Kasir is open-sourced software licensed under the [MIT license](LICENSE.md).
