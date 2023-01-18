<?php

it('throws correct validation Exceptions', function ($kasir, $exception) {
    expect(fn () => $kasir->toArray())->toThrow($exception);
})->with([
    "'gross_amount' == null && 'item_details' == null" => [
        \Kasir\Kasir\Kasir::make(),
        \Kasir\Kasir\Exceptions\ZeroGrossAmountException::class,
    ],
    "'gross_amount' == 0 && 'item_details' == null" => [
        \Kasir\Kasir\Kasir::make(0),
        \Kasir\Kasir\Exceptions\ZeroGrossAmountException::class,
    ],
    "'gross_amount' == null && 'item_details' without 'price' and 'quantity' keys" => [
        \Kasir\Kasir\Kasir::make()
            ->itemDetails([]),
        \Kasir\Kasir\Exceptions\NoItemDetailsException::class,
    ],
    // TODO: Implements throwing NoPriceAndQuantityAttributeException
]);
