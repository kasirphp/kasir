<?php

namespace Kasir\Kasir\Payment\EMoney;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class ShopeePay extends PaymentObject implements PaymentMethod
{
    /**
     * Create ShopeePay payment object.
     *
     * @param  string|null  $callback_url  The URL to redirect the customer back from the ShopeePay app. Default value is the finish URL, configured on your MAP account.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/shopeepay-1
     * @see https://docs.midtrans.com/reference/shopeepay-object
     */
    public static function make(string | null $callback_url = null): static
    {
        $options = get_defined_vars();

        $key = 'shopeepay';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
