<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class DanamonOnline extends PaymentObject implements PaymentType
{
    /**
     * Create Danamon Online payment object.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#danamon-online-banking
     */
    public static function make(): static
    {
        $type = 'danamon_online';

        return app(static::class)
            ->type($type);
    }
}
