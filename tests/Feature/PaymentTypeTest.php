<?php

use Kasir\Kasir\Kasir;

it('can charge using the given payment_method', function ($method, $type, $option_key, $options, $expected_options) {
    $kasir = Kasir::make(1)
        ->$method(...$options);

    $array = $kasir->toArray();
    $response = $kasir->charge();

    expect($array['payment_type'])->toBe($type)
        ->and($array[$option_key])->toBe($expected_options)
        ->and($response)->toBeInstanceOf(\Kasir\Kasir\Helper\MidtransResponse::class)
        ->and($response->status())->toBeGreaterThanOrEqual(200)->toBeLessThan(400);
})->with('payment_type');
