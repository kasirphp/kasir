<?php

namespace Kasir\Kasir\Payment\EMoney;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class ShopeePay extends PaymentObject implements PaymentMethod
{
    /**
     * Create ShopeePay payment object.
     *
     * @param  string  $callback_url  The URL to redirect the customer back from the ShopeePay app. Default value is the finish URL, configured on your MAP account.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#shopeepay
     */
    public static function make(string $callback_url = ''): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');

        $key = 'shopeepay';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
