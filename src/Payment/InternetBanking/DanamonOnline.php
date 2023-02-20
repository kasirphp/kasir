<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class DanamonOnline extends PaymentObject implements PaymentMethod
{
    /**
     * Create Danamon Online payment object.
     *
     *
     * @see https://docs.midtrans.com/reference/danamon-online-banking-dob
     */
    public static function make(): static
    {
        $type = 'danamon_online';

        return app(static::class)
            ->type($type);
    }
}
