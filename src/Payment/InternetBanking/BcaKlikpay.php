<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class BcaKlikpay extends PaymentObject implements PaymentType
{
    /**
     * Create BCA Klikpay payment object.
     *
     * @param  string|null  $description  Description of the BCA KlickPay transaction.
     * @param  string|null  $misc_fee  Additional fee for documentation.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#bca-klikpay
     */
    public static function make(string $description = null, string $misc_fee = null): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');
        $key = 'bca_klikpay';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
