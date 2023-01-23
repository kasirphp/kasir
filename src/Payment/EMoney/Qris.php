<?php

namespace Kasir\Kasir\Payment\EMoney;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class Qris extends PaymentObject implements PaymentType
{
    /**
     * Create QRIS payment object.
     *
     * @param  string  $acquirer The acquirer for QRIS. Possible values are airpay shopee, gopay.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#qris-object
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
