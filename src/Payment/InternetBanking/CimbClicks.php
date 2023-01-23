<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class CimbClicks extends PaymentObject implements PaymentType
{
    /**
     * Create CIMB Clicks payment object.
     *
     * @param  string|null  $description Description of CIMB transaction. This will be displayed on the CIMB email notification.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#cimb-clicks
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
