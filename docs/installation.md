---
title: Installation

---

<!-- TOC -->
  * [Requirements](#requirements)
  * [Installation](#installation)
    * [Install via Composer](#install-via-composer)
    * [Configure .env file](#configure-env-file)
    * [Publish Configuration file](#publish-configuration-file)
<!-- TOC -->

## Requirements

Kasir has a few requirements to run:

- PHP 8.0+
- Laravel 8.0+

## Installation

### Install via Composer

To get started charging your customer via Midtrans, install Kasir in your application using Composer:

```shell
composer require kasir/kasir
```

### Configure .env file

Then, add Midtrans' Client Key and Server Key to your `.env` file.

```dotenv
MIDTRANS_CLIENT_KEY= // Your PRODUCTION Client Key
MIDTRANS_SERVER_KEY= // Your PRODUCTION Server Key
MIDTRANS_CLIENT_KEY_SANDBOX= // Your SANDBOX Client Key
MIDTRANS_SERVER_KEY_SANDBOX= // Your SANDBOX Server Key

KASIR_PRODUCTION=false
```

Note that Midtrans has two separate key for Production and Sandbox mode. The key used is determined by
your `KASIR_PRODUCTION` environment. The default value is `false`, which means Kasir will use the Sandbox key.

For example, if `KASIR_PRODUCTION` is set to `false`, then Kasir will use `MIDTRANS_CLIENT_KEY_SANDBOX` as your client
key. Same applied to the server key.

Don't forget to change your `KASIR_PRODUCTION` mode to `true` when deploying to production.

### Publish Configuration file

Optionally, you can publish the configuration file:

```shell
php artisan vendor:publish --tag=kasir-config
```

This config file is located at `config/kasir.php`. You can change the configuration to your liking.

Now you're ready to use Kasir in your controller.