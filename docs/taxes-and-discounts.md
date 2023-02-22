---
title: Taxes and Discounts
---

# Taxes and Discounts

[[toc]]

## Getting Started

Sometime you need to apply discounts and taxes to transactions. Kasir is built with this in mind. You can easily apply
discounts and taxes to transactions by following these guides.

## Discounts

To add a discount, call the`discount()` method and pass the parameters to create a discount, This method will also automatically calculate the `gross_amount`.

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
