<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class CimbClicks extends PaymentObject implements PaymentMethod
{
    /**
     * Create CIMB Clicks payment object.
     *
     * @param  string|null  $description Description of CIMB transaction. This will be displayed on the CIMB email notification.
     *
     * @see https://docs.midtrans.com/reference/cimb-clicks-1
     * @see https://docs.midtrans.com/reference/cimb-clicks-object
     */
    public static function make(string $description = null): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');

        $key = 'cimb_clicks';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
