<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class BcaKlikpay extends PaymentObject implements PaymentMethod
{
    /**
     * Create BCA Klikpay payment object.
     *
     * @param  string  $description  Description of the BCA KlickPay transaction.
     * @param  string|null  $misc_fee  Additional fee for documentation.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bca-klikpay-object
     */
    public static function make(string $description, string | null $misc_fee = null): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');
        $key = 'bca_klikpay';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
