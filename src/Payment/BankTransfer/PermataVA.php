<?php

namespace Kasir\Kasir\Payment\BankTransfer;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\PaymentObject;

class PermataVA extends PaymentObject implements PaymentType
{
    /**
     * Create Permata Virtual Account payment object.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @param  string|null  $recipient_name  Recipient name shown on the payment details.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#permata-virtual-account
     */
    public static function make(string | null $va_number = null, string | null $recipient_name = null): static
    {
        $options = compact('va_number');
        $options['bank'] = 'permata';

        if ($recipient_name) {
            $options['recipient_name'] = $recipient_name;
        }

        $type = 'bank_transfer';

        return app(static::class)
            ->type($type)
            ->optionKey($type)
            ->options($options);
    }
}