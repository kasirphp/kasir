<?php

namespace Kasir\Kasir\Payment\BankTransfer;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class BniVA extends PaymentObject implements PaymentMethod
{
    /**
     * Create BNI Virtual Account payment object.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     *
     * @see https://docs.midtrans.com/reference/bank-transfer-object
     */
    public static function make(string | null $va_number = null): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');
        $options['bank'] = 'bni';

        $type = 'bank_transfer';

        return app(static::class)
            ->type($type)
            ->optionKey($type)
            ->options($options);
    }
}
