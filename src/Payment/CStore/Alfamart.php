<?php

namespace Kasir\Kasir\Payment\CStore;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class Alfamart extends PaymentObject implements PaymentType
{
    /**
     * Create Alfamart payment object.
     *
     * @param  string|null  $alfamart_free_text_1  Customizable first row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_2  Customizable second row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_3  Customizable third row of text on the Alfamart printed receipt.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#alfamart
     */
    public static function make(
        string | null $alfamart_free_text_1 = '',
        string | null $alfamart_free_text_2 = '',
        string | null $alfamart_free_text_3 = ''
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
