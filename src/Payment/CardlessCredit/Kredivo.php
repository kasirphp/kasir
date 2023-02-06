<?php

namespace Kasir\Kasir\Payment\CardlessCredit;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class Kredivo extends PaymentObject implements PaymentMethod
{
    /**
     * Create Kredivo payment object.
     *
     * @return static
     *
     * @see https://docs.midtrans.com/reference/kredivo-1
     */
    public static function make(): static
    {
        $type = 'kredivo';

        return app(static::class)
            ->type($type);
    }
}
