<?php

namespace Kasir\Kasir\Payment\CStore;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class Indomaret extends PaymentObject implements PaymentMethod
{
    /**
     * Create Indomaret payment object.
     *
     * @param  string  $message  Label displayed in Indomaret POS.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#indomaret
     */
    public static function make(string $message = ''): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');
        $key = 'cstore';
        $options['store'] = 'indomaret';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
