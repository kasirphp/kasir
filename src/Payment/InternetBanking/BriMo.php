<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class BriMo extends PaymentObject implements PaymentMethod
{
    /**
     * Create BRImo payment object.
     *
     *
     * @see https://docs.midtrans.com/reference/brimo-1
     */
    public static function make(): static
    {
        $type = 'bri_epay';

        return app(static::class)
            ->type($type);
    }
}
