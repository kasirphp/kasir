<?php

namespace Kasir\Kasir\Payment\InternetBanking;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class KlikBca extends PaymentObject implements PaymentMethod
{
    /**
     * Create KlikBCA payment object.
     *
     * @param  string  $description  https://api-docs.midtrans.com/#klikbca
     * @param  string  $user_id  KlikBCA User ID.
     *
     * @see https://docs.midtrans.com/reference/bca-klikbca-object
     */
    public static function make(string $description, string $user_id): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');

        $key = 'bca_klikbca';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
