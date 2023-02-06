<?php

namespace Kasir\Kasir\Payment\CardlessCredit;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class Akulaku extends PaymentObject implements PaymentMethod
{
    /**
     * Create Akulaku payment object.
     *
     * @return static
     *
     * @see https://docs.midtrans.com/reference/akulaku-1
     */
    public static function make(): static
    {
        $type = 'akulaku';

        return app(static::class)
            ->type($type);
    }
}
