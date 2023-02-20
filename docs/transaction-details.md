---
title: Transaction Details

---

# Transaction Details

[[toc]]

## Getting Started

Transaction details allow the merchant to reference the transaction on
the [Merchant Account Portal (MAP) page](https://dashboard.midtrans.com/transactions). These parameters include the
customer's information, billing address, shipping address, item details, etc. You can add these parameters to
the `Kasir` object using predefined methods. All the methods also accept a `Closure` as a parameter. This anonymous
function will be executed when it is used as the parameter.

After creating a transaction, you can charge your customer using [CoreAPI](core-api) or [Snap](snap-api) after setting
up the [Payment Methods](payment-methods) configuration.

## Creating Transaction

To create a transaction, use `Kasir` facade and call `make()` method. This method will return a new instance of `Kasir`
class.
The method accepts an optional parameter that represents the gross amount of the transaction.

```php
// use Kasir\Kasir\Kasir;

$kasir = Kasir::make(1000);
```

If you don't pass any parameter, you can use `grossAmount()` method to set the gross amount.

```php
$kasir = Kasir::make()
    ->grossAmount(1000);
```

This both method will return an instance of `Kasir` class with gross amount of `Rp1000`.

## Order ID

Order ID can be used to differentiate orders and payments in your app. It should be a unique string. By default, Kasir
will generate a `Str::orderedUuid()` to assign as the Order ID. However, you can override the Order ID with any string
you want by using the `orderId()` method.

```php
$order_id = 'order-'.Str::random(10)

$kasir = Kasir::make(1000)
    ->orderId($order_id);
```

You can also pass a `Closure` as a parameter. This anonymous function will be executed when it is used as the parameter.

```php
$kasir = Kasir::make(1000)
    ->orderId(function () {
        return 'order-'.Str::random(10);
    });
```

> If `order_id` is not unique, it will throw `MidtransApiException` calling the API.

## Gross Amount and Order ID in One Method

::: warning
This method is **deprecated** and will be removed in the next major release.
Please use `grossAmount()` and `orderId()` method instead.
:::

You can also set the gross amount and order ID in one method by passing an array as a parameter.

```php
$kasir = Kasir::make()
    ->transactionDetails([
        'gross_amount' => 1000, [!code --]
        'order_id' => 'order-'.Str::random(10),
    ]);
```

You can also pass a `Closure` as a parameter. This anonymous function will be executed when it is used as the parameter.

```php
$kasir = Kasir::make()
    ->transactionDetails(function () {
        return [
            'gross_amount' => 1000,
            'order_id' => 'order-'.Str::random(10),
        ];
    });
```

## Customer Details

Customer details are the details of the customer who is making the payment. These details include the customer's name,
email, phone number, and address. You can add these parameters to the `Kasir` object using predefined methods.

```php
$kasir = Kasir::make(1000)
    ->customerDetails([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'phone' => '081234567890'
    ]);
```

You can also pass a `Closure` as a parameter. This anonymous function will be executed when it is used as the parameter.

```php
$kasir = Kasir::make(1000)
    ->customerDetails(function () {
        $user = auth()->user();
        $first_name = Str::before($user->name, ' ');
        $last_name = Str::after($user->name, ' ');
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    });
```

## Billing Address

Billing address is the address of the customer who is making the payment. You can add these parameters to the `Kasir`
object using predefined methods.

```php
$kasir = Kasir::make(1000)
    ->billingAddress([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'phone' => '081234567890',
        'address' => 'Jl. Kenangan',
        'city' => 'Jakarta',
        'postal_code' => '12345',
        'country_code' => 'IDN',
    ]);
```

You can also pass a `Closure` as a parameter. This anonymous function will be executed when it is used as the parameter.

```php
$kasir = Kasir::make(1000)
    ->billingAddress(function () {
        $user = auth()->user();
        $first_name = Str::before($user->name, ' ');
        $last_name = Str::after($user->name, ' ');
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
            'country_code' => $user->country_code,
        ];
    });
```

## Shipping Address

Shipping address is the address of the customer who is receiving the product. You can add these parameters to
the `Kasir` object using predefined methods.

```php
$kasir = Kasir::make(1000)
    ->shippingAddress([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'phone' => '081234567890',
        'address' => 'Jl. Kenangan',
        'city' => 'Jakarta',
        'postal_code' => '12345',
        'country_code' => 'IDN',
    ]);
```

You can also pass a `Closure` as a parameter. This anonymous function will be executed when it is used as the parameter.

```php
$kasir = Kasir::make(1000)
    ->shippingAddress(function () {
        $user = auth()->user();
        $first_name = Str::before($user->name, ' ');
        $last_name = Str::after($user->name, ' ');
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
            'country_code' => $user->country_code,
        ];
    });
```

## Item Details

Item details are the details of the items that are being purchased. These details include the item's name, price,
quantity,
and brand. You can add these parameters to the `Kasir` object using predefined methods.

```php
$kasir = Kasir::make(1000)
    ->itemDetails([
        [
            'id' => 'item-1',
            'price' => 1000,
            'quantity' => 1,
            'name' => 'Product 1',
        ],
        [
            'id' => 'item-2',
            'price' => 2000,
            'quantity' => 2,
            'name' => 'Product 2',
        ],
    ]);
```

> By default, Kasir will calculate the gross amount of the items based on the price and quantity.

You can also pass a `Closure` as a parameter. This anonymous function will be executed when it is used as the parameter.
For example, if your application has a shopping cart, you can use the `Closure` to get the items from the shopping cart.

```php
$kasir = Kasir::make(1000)
    ->itemDetails(function () {
        $items = auth()->user()->cart->items; // Example
        return $items->map(function ($item) {
            return [
                'id' => $item->id,
                'price' => $item->price,
                'quantity' => $item->qty,
                'name' => $item->name,
            ];
        })->toArray();
    });
```

## Next Step

After you have created the `Kasir` object with those configurations, you can use it to charge your costumer
using [CoreAPI](core-api) or [Snap](snap-api) after setting up the [Payment Methods](payment-methods)
configuration.