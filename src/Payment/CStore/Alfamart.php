<?php

namespace Kasir\Kasir\Payment\CStore;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class Alfamart extends PaymentObject implements PaymentMethod
{
    /**
     * Create Alfamart payment object.
     *
     * @param  string|null  $alfamart_free_text_1  Customizable first row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_2  Customizable second row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_3  Customizable third row of text on the Alfamart printed receipt.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/alfamart-1
     */
    public static function make(
        string | null $alfamart_free_text_1 = null,
        string | null $alfamart_free_text_2 = null,
        string | null $alfamart_free_text_3 = null
    ): static {
        $options = array_filter(get_defined_vars(), 'strlen');
        $key = 'cstore';
        $options['store'] = 'alfamart';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
