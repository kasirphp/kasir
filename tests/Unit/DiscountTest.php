<?php

use Kasir\Kasir\Kasir;

it('calculates the right amount of discounts', function () {
    $kasir = Kasir::make()
        ->itemDetails([
            [
                'id' => 'product-1',
                'name' => 'Product 1',
                'price' => 5000,
                'quantity' => 1,
            ], [
                'id' => 'product-2',
                'name' => 'Product 2',
                'price' => 2500,
                'quantity' => 2,
            ],
        ]);

    $discountMethods = (clone $kasir)
        ->discount(20, true, 'Voucher', 'voucher')
        ->discount(1000, false, 'Promo', 'promo')
        ->discount(10, true);

    $discountClosure = (clone $kasir)
        ->discounts(fn () => [
            [
                'id' => 'voucher',
                'name' => 'Voucher',
                'amount' => 20,
                'percentage' => true,
            ], [
                'id' => 'promo',
                'name' => 'Promo',
                'amount' => 1000,
                'percentage' => false,
            ], [
                'id' => 'discount#3',
                'name' => 'Discount #3',
                'amount' => 10,
                'percentage' => true,
            ],
        ]);

    $discountCombined1 = (clone $kasir)
        ->discounts(fn () => [
            [
                'id' => 'voucher',
                'name' => 'Voucher',
                'amount' => 20,
                'percentage' => true,
            ], [
                'id' => 'promo',
                'name' => 'Promo',
                'amount' => 1000,
                'percentage' => false,
            ],
        ])
        ->discount(10, true);

    $discountCombined2 = (clone $kasir)
        ->discount(20, true, 'Voucher', 'voucher')
        ->discounts(fn () => [
            [
                'id' => 'promo',
                'name' => 'Promo',
                'amount' => 1000,
                'percentage' => false,
            ], [
                'id' => 'discount#3',
                'name' => 'Discount #3',
                'amount' => 10,
                'percentage' => true,
            ],
        ]);

    expect($discountMethods->getGrossAmount())
        ->and($discountClosure->getGrossAmount())
        ->and($discountCombined1->getGrossAmount())
        ->and($discountCombined2->getGrossAmount())
        ->toBe(6300)
        ->and($discountMethods->getDiscounts())
        ->toBe($discountClosure->getDiscounts())
        ->toBe($discountCombined1->getDiscounts())
        ->toBe($discountCombined2->getDiscounts())
        ->and($discountMethods->toArray())
        ->toBe($discountClosure->toArray())
        ->toBe($discountCombined1->toArray())
        ->toBe($discountCombined2->toArray());
});

test('taxes and discounts', function () {
    $items = [
        [
            'id' => 'product-1',
            'name' => 'Product 1',
            'price' => 5000,
            'quantity' => 1,
        ], [
            'id' => 'product-2',
            'name' => 'Product 2',
            'price' => 2500,
            'quantity' => 2,
        ],
    ];

    $kasir = Kasir::make()
        ->itemDetails($items)
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(11, true, 'PPN 11%', 'ppn');

    expect($kasir->getGrossAmount())->toBe(8880);
});
