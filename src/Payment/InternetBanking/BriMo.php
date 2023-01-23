<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class BriMo extends PaymentObject implements PaymentType
{
    /**
     * Create BRImo payment object.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#brimo
     */
    public static function make(): static
    {
        $type = 'bri_epay';

        return app(static::class)
            ->type($type);
    }
}
