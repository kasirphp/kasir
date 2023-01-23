<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class KlikBca extends PaymentObject implements PaymentType
{
    /**
     * Create KlikBCA payment object.
     *
     * @param  string|null  $description  https://api-docs.midtrans.com/#klikbca
     * @param  string|null  $user_id  KlikBCA User ID.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#klikbca
     */
    public static function make(string $description = null, string $user_id = null): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');

        $key = 'bca_klikbca';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
