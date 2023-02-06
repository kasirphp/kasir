<?php

namespace Kasir\Kasir\Payment\EMoney;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class Qris extends PaymentObject implements PaymentMethod
{
    /**
     * Create QRIS payment object.
     *
     * @param  string  $acquirer The acquirer for QRIS. Possible values are airpay shopee, gopay.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/qris
     */
    public static function make(string $acquirer = 'gopay'): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');

        $key = 'qris';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
