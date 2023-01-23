<?php

namespace Kasir\Kasir\Payment\CardlessCredit;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class Akulaku extends PaymentObject implements PaymentType
{
    /**
     * Create Akulaku payment object.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#akulaku-paylater
     */
    public static function make(): static
    {
        $type = 'akulaku';

        return app(static::class)
            ->type($type);
    }
}
