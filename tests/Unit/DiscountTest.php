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

    $kasir = Kasir::make(10000);
//        ->itemDetails($items);

    expect($kasir->getGrossAmount())
        ->toBe(10000);

    $taxDiscount1 = (clone $kasir)
        ->discount(10, true, 'Voucher', 'voucher')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxDiscount2 = (clone $kasir)
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxDiscount3 = (clone $kasir)
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(10, true, 'Voucher', 'voucher')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxDiscount4 = (clone $kasir)
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(11, true, 'PPN 11%', 'ppn')
        ->discount(2000, false, 'Promo', 'promo');

    $taxDiscount5 = (clone $kasir)
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(11, true, 'PPN 11%', 'ppn')
        ->discount(2000, false, 'Promo', 'promo');

    $taxDiscount6 = (clone $kasir)
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->tax(11, true, 'PPN 11%', 'ppn')
        ->discount(10, true, 'Voucher', 'voucher')
        ->discount(2000, false, 'Promo', 'promo');

    expect($taxDiscount1->toArray())
        ->toBe($taxDiscount2->toArray())
        ->toBe($taxDiscount3->toArray())
        ->toBe($taxDiscount4->toArray())
        ->toBe($taxDiscount5->toArray())
        ->toBe($taxDiscount6->toArray())
        ->and($taxDiscount1->getGrossAmount())
        ->and($taxDiscount2->getGrossAmount())
        ->and($taxDiscount3->getGrossAmount())
        ->and($taxDiscount4->getGrossAmount())
        ->and($taxDiscount5->getGrossAmount())
        ->and($taxDiscount6->getGrossAmount())
        ->toBe(8880);
});
