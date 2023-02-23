<?php

use Kasir\Kasir\Kasir;

it('calculates the right amount of discounts', function () {
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
        ->itemDetails($items);

    $discountMethods = $kasir->copy()
        ->discount(20, true, 'Voucher', 'voucher')
        ->discount(1000, false, 'Promo', 'promo')
        ->discount(10, true);

    $discountClosure = $kasir->copy()
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

    $discountCombined1 = $kasir->copy()
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

    $discountCombined2 = $kasir->copy()
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

it('calculates the right amount of taxes', function () {
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
        ->itemDetails($items);

    $taxMethods = $kasir->copy()
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxClosure = $kasir->copy()
        ->taxes(fn () => [
            [
                'id' => 'snap-fee',
                'name' => 'Biaya Metode Pembayaran',
                'amount' => 1000,
                'percentage' => false,
            ], [
                'id' => 'ppn',
                'name' => 'PPN 11%',
                'amount' => 11,
                'percentage' => true,
            ],
        ]);

    $taxCombined1 = $kasir->copy()
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->taxes(fn () => [
            [
                'id' => 'ppn',
                'name' => 'PPN 11%',
                'amount' => 11,
                'percentage' => true,
            ],
        ]);

    $taxCombined2 = $kasir->copy()
        ->taxes(fn () => [
            [
                'id' => 'snap-fee',
                'name' => 'Biaya Metode Pembayaran',
                'amount' => 1000,
                'percentage' => false,
            ],
        ])
        ->tax(11, true, 'PPN 11%', 'ppn');

    expect($taxMethods->getGrossAmount())
        ->and($taxClosure->getGrossAmount())
        ->and($taxCombined1->getGrossAmount())
        ->and($taxCombined2->getGrossAmount())
        ->toBe(12210)
        ->and($taxMethods->getTaxes())
        ->toBe($taxClosure->getTaxes())
        ->toBe($taxCombined1->getTaxes())
        ->toBe($taxCombined2->getTaxes())
        ->and($taxMethods->toArray())
        ->toBe($taxClosure->toArray())
        ->toBe($taxCombined1->toArray())
        ->toBe($taxCombined2->toArray());
});

it('calculates the right gross_amount for taxes and discounts in multiple method assignment combinations', function () {
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
        ->itemDetails($items);

    expect($kasir->getGrossAmount())
        ->toBe(10000);

    $taxDiscount1 = $kasir->copy()
        ->discount(10, true, 'Voucher', 'voucher')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxDiscount2 = $kasir->copy()
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxDiscount3 = $kasir->copy()
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(10, true, 'Voucher', 'voucher')
        ->discount(2000, false, 'Promo', 'promo')
        ->tax(11, true, 'PPN 11%', 'ppn');

    $taxDiscount4 = $kasir->copy()
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(11, true, 'PPN 11%', 'ppn')
        ->discount(2000, false, 'Promo', 'promo');

    $taxDiscount5 = $kasir->copy()
        ->tax(1000, false, 'Biaya Metode Pembayaran', 'snap-fee')
        ->discount(10, true, 'Voucher', 'voucher')
        ->tax(11, true, 'PPN 11%', 'ppn')
        ->discount(2000, false, 'Promo', 'promo');

    $taxDiscount6 = $kasir->copy()
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
