---
title: Why Kasir?
---

# Why Kasir

[[toc]]

## The Problems

When you try to search "Midtrans Laravel" on your favorite search engine, you'll find that Midtrans has a package called
[Midtrans Laravel](https://github.com/midtrans/midtrans-laravel5), Official Midtrans Payment API Client for Laravel.
It's an official package from Midtrans. But, the latest support for it was for Laravel 5.5. It's not maintained
anymore. It's not even compatible with the latest version of Laravel. The Readme is even suggesting you to
use [Midtrans PHP](https://github.com/midtrasn/midtrans-php) instead.

> This repo still be here for archive and compatibility purpose. But it's always recommended to use the newer version
> Midtrans PHP.

### Midtrans PHP

[Midtrans PHP](https://github.com/midtrans/midtrans-php) may look like the best choice. It's the official package from
Midtrans. When you only need to build a simple business logic, it's enough.

::: code-group

```php [PaymentController.php]
class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $user = auth()->user();

        \Midtrans\Config::$serverKey = 'your_server_key';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        \Midtrans\Config::$appendNotifUrl = 'https://yourdomain.com/payment/notification';

        $transaction_details = [
            'order_id' => rand(),
            'gross_amount' => $request('amount'),
        ];

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => $user->cart()->items(),
            'payment_type' => 'credit_card',
            'credit_card' => [
                'token_id' => $token_id,
                'authentication' => true,
            ],
        ];

        return \Midtrans\CoreApi::charge($transaction_data);
    }
}
```

:::

But, when you're building a large application, you'll find that it's not as easy as you thought. You'll need
to setup the client key, headers, and other stuff repeatedly. You'll also need to manually convert the response to
Laravel Response. It's not a big deal if you're building a small application. But, if you're building a large
application, you'll find that it's not as maintainable as you thought.

## Leaving the Problems Behind

For example, you need to change the logic of your API request to Midtrans based on the payment method your user is
using.
You might need to change the `payment_type` and the corresponding array keys based on your customers payment method. In
Midtrans PHP, you'll need to create each array repeatedly in your controller. You'll also need to manually convert the
response to Laravel Response.

Compare this when you are using Midtrans PHP and Kasir:

::: code-group

<<< @/snippets/MidtransController.php
<<< @/snippets/KasirController.php

:::

With Midtrans, you get an stdClass object that represents the response from Midtrans. You'll need to manually convert it
to Laravel Response. With Kasir, you get MidtransResponse class which extends Laravel Response. You don't need to do
anything. Because of that, you can also use the methods available from Laravel Response like `ok()` or `json()`.