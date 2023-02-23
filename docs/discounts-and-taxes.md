---
title: Discounts and Taxes
---

# Discounts and Taxes

[[toc]]

## Getting Started

Sometime you need to apply discounts and taxes to transactions. Kasir is built with this in mind. You can easily apply
discounts and taxes to transactions by following these guides.

## Discounts

To add a discount, call the`discount()` method and pass the parameters, This method will also automatically calculate the `gross_amount`.

The available parameters for discount are:
- `amount`: `int` **`required`** The amount of the discount, in fixed amount or percentage of the `gross_amount` (discount rate).
- `percentage`: `bool` Determines if the amount is in percentage.
- `name`: `string` The name of the discount. This will be displayed in the Midtrans Merchant Account Portal.
- `id`: `string` The ID of the discount. This will be displayed in the Midtrans Merchant Account Portal.

### Adding a Discount

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->discount(10, true, 'Voucher GoPay', 'voucher-gopay'); // [!code focus]
```

::: info
The example above will assign an array of discount to the `discounts` property and will automatically calculate the `gross_amount`.
:::

### Adding Multiple Discounts

To add multiple discounts, chain the `discount()` method:

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->discount(10, true, 'Voucher GoPay', 'voucher-gopay') // [!code focus:2]
    ->discount(2, true, 'Promo Pengguna Baru', 'new-user');
```

::: info
The example above will create two discounts; first is a 10% discount, and second is a 2% discount calculated **after** the first discount is applied.
:::

::: warning
The `gross_amount` will be calculated in the order in which the discount is called.
:::

You can also add multiple discounts by using `discounts()` method and passing an array of discounts with each discount parameters as the keys.

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->discounts([ // [!code focus:13]
        [
            'id' => 'voucher-gopay',
            'name' => 'Voucher GoPay',
            'amount' => 10,
            'percentage' => true,
        ], [
            'id' => 'new-user',
            'name' => 'Promo Pengguna Baru',
            'amount' => 2,
            'percentage' => true,
        ]
    ]);
```

or pass an anonymous function:

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->discounts(function() { // [!code focus:15]
        return [
            [
                'id' => 'voucher-gopay',
                'name' => 'Voucher GoPay',
                'amount' => 10,
                'percentage' => true,
            ], [
                'id' => 'new-user',
                'name' => 'Promo Pengguna Baru',
                'amount' => 2,
                'percentage' => true,
            ]
        ];
    });
```

## Taxes

To add a tax, call the`tax()` method and pass the parameters, This method will also automatically calculate the `gross_amount`.

The available parameters for tax are:
- `amount`: `int` **`required`** The amount of the tax, in fixed amount or percentage of the `gross_amount` (tax rate).
- `percentage`: `bool` Determines if the amount is in percentage.
- `name`: `string` The name of the tax. This will be displayed in the Midtrans Merchant Account Portal.
- `id`: `string` The ID of the tax. This will be displayed in the Midtrans Merchant Account Portal.

### Adding a Tax

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->tax(2, true, 'Biaya Layanan', 'service-fee'); // [!code focus]
```

::: info
The example above will assign an array of tax to the `taxes` property and will automatically calculate the `gross_amount`.
:::

### Adding Multiple Taxes

To add multiple taxes, chain the `tax()` method:

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->tax(2, true, 'Biaya Layanan', 'service-fee') // [!code focus:2]
    ->tax(11, true, 'PPN 11%', 'ppn');
```

::: info
The example above will create two taxes; first is a 2% tax, and second is a 11% tax calculated **after** the first tax is applied.
:::

::: warning
The `gross_amount` will be calculated in the order in which the tax is called.
:::

You can also add multiple taxes by using `taxes()` method and passing an array of taxes with each tax parameters as the keys.

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->taxes([ // [!code focus:13]
        [
            'id' => 'service-fee',
            'name' => 'Biaya Layanan',
            'amount' => 10,
            'percentage' => true,
        ], [
            'id' => 'ppn',
            'name' => 'PPN 11%',
            'amount' => 11,
            'percentage' => true,
        ]
    ]);
```

or pass an anonymous function:

```php
$kasir = Kasir::make()
    ->itemDetails($items)
    ->taxes(function() { // [!code focus:15]
        return [
            [
                'id' => 'service-fee',
                'name' => 'Biaya Layanan',
                'amount' => 10,
                'percentage' => true,
            ], [
                'id' => 'ppn',
                'name' => 'PPN 11%',
                'amount' => 11,
                'percentage' => true,
            ]
        ];
    });
```

## Discount and Tax Priority

Because discounts are generally offered directly by the retailer and reduce the amount of the sales price and the cash received by the retailer, the sales tax applies to the price after the discount is applied. For example, if you have a 11% tax and a 10% discount, the discount will be applied first, then the tax will be applied.

By that rule, these code will produce the same result:

::: code-group
```php [Calling Discounts First]
$kasir = Kasir::make()
    ->itemDetails($items)
    ->discount(10, true, 'Voucher GoPay', 'voucher-gopay') // [!code focus:4]
    ->discount(2, true, 'Promo Pengguna Baru', 'new-user')
    ->tax(2, true, 'Biaya Layanan', 'service-fee')
    ->tax(11, true, 'PPN 11%', 'ppn');
```

```php [Calling Taxes First]
$kasir = Kasir::make()
    ->itemDetails($items)
    ->tax(2, true, 'Biaya Layanan', 'service-fee') // [!code focus:4]
    ->tax(11, true, 'PPN 11%', 'ppn')
    ->discount(10, true, 'Voucher GoPay', 'voucher-gopay')
    ->discount(2, true, 'Promo Pengguna Baru', 'new-user');
```

```php [No Specific Order]
$kasir = Kasir::make()
    ->itemDetails($items)
    ->tax(2, true, 'Biaya Layanan', 'service-fee') // [!code focus:4]
    ->discount(10, true, 'Voucher GoPay', 'voucher-gopay')
    ->tax(11, true, 'PPN 11%', 'ppn')
    ->discount(2, true, 'Promo Pengguna Baru', 'new-user');
```
:::

::: info
There are currently no method for overriding this behavior.
:::