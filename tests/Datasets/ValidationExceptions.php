<?php

use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Kasir;

dataset('validation_exceptions', [
    "'gross_amount' == null && 'item_details' == null" => [
        Kasir::make()
            ->itemDetails(null),
        ZeroGrossAmountException::class,
    ],
    "'gross_amount' == 0 && 'item_details' == null" => [
        Kasir::make(0)
            ->itemDetails(null),
        ZeroGrossAmountException::class,
    ],
    "'gross_amount' == null && 'item_details' == []" => [
        Kasir::make()
            ->itemDetails([]),
        NoItemDetailsException::class,
    ],
    "'gross_amount' == 0 && 'item_details' == []" => [
        Kasir::make(0)
            ->itemDetails([]),
        NoItemDetailsException::class,
    ],
    "'gross_amount' == null && 'item_details' without 'price' and 'quantity' keys" => [
        Kasir::make()
            ->itemDetails([
                [
                    'name' => 'foo',
                ],
            ]),
        NoPriceAndQuantityAttributeException::class,
    ],
    "'gross_amount' == null && 'item_details' without 'price' but with 'quantity' keys" => [
        Kasir::make()
            ->itemDetails([
                [
                    'price' => 1000,
                ],
            ]),
        NoPriceAndQuantityAttributeException::class,
    ],
    "'gross_amount' == null && 'item_details' with 'price' but without 'quantity' keys" => [
        Kasir::make()
            ->itemDetails([
                [
                    'quantity' => 1,
                ],
            ]),
        NoPriceAndQuantityAttributeException::class,
    ],
    "'gross_amount' == 0 && 'item_details' without 'price' and 'quantity' keys" => [
        Kasir::make(0)
            ->itemDetails([
                [
                    'name' => 'foo',
                ],
            ]),
        NoPriceAndQuantityAttributeException::class,
    ],
    "'gross_amount' == 0 && 'item_details' without 'price' but with 'quantity' keys" => [
        Kasir::make(0)
            ->itemDetails([
                [
                    'price' => 1000,
                ],
            ]),
        NoPriceAndQuantityAttributeException::class,
    ],
    "'gross_amount' == 0 && 'item_details' with 'price' but without 'quantity' keys" => [
        Kasir::make()
            ->itemDetails([
                [
                    'quantity' => 1,
                ],
            ]),
        NoPriceAndQuantityAttributeException::class,
    ],
]);
