<?php

namespace Kasir\Kasir\Payment\BankTransfer;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class BriVA extends PaymentObject implements PaymentType
{
    /**
     * Create BRI Virtual Account payment object.
     *
     * @param  string|null  $va_number Custom VA number assigned by you.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#bri-virtual-account
     */
    public static function make(string | null $va_number = null): static
    {
        $options = array_filter(get_defined_vars(), 'strlen');
        $options['bank'] = 'bri';

        $type = 'bank_transfer';

        return app(static::class)
            ->type($type)
            ->optionKey($type)
            ->options($options);
    }
}
