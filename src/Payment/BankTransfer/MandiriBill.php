<?php

namespace Kasir\Kasir\Payment\BankTransfer;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class MandiriBill extends PaymentObject implements PaymentMethod
{
    /**
     * Create Mandiri Bill payment object.
     *
     * @param  string  $bill_info1  Label 1.
     * @param  string  $bill_info2  Value for Label 1.
     * @param  string|null  $bill_info3  Label 2.
     * @param  string|null  $bill_info4  Value for Label 2.
     * @param  string|null  $bill_info5  Label 3.
     * @param  string|null  $bill_info6  Value for Label 3.
     * @param  string|null  $bill_info7  Label 4.
     * @param  string|null  $bill_info8  Value for Label 4.
     * @param  string|null  $bill_key  Custom bill key assigned by you.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/e-channel-object
     */
    public static function make(
        string $bill_info1,
        string $bill_info2,
        string | null $bill_info3 = null,
        string | null $bill_info4 = null,
        string | null $bill_info5 = null,
        string | null $bill_info6 = null,
        string | null $bill_info7 = null,
        string | null $bill_info8 = null,
        string | null $bill_key = null,
    ): static {
        $options = array_filter(get_defined_vars(), 'strlen');
        $type = 'echannel';

        return app(static::class)
            ->type($type)
            ->optionKey($type)
            ->options($options);
    }
}
