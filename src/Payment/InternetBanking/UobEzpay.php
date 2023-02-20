<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class UobEzpay extends PaymentObject implements PaymentMethod
{
    /**
     * Create UOB EZPay payment object.
     *
     *
     * @see https://docs.midtrans.com/reference/uob-ezpay
     */
    public static function make(): static
    {
        $type = 'uob_ezpay';

        return app(static::class)
            ->type($type);
    }
}
