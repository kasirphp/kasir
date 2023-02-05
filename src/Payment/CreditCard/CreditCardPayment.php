<?php

namespace Kasir\Kasir\Payment\CreditCard;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class CreditCardPayment extends PaymentObject implements PaymentMethod
{
    public static function make(
        CreditCard | CardToken | string | null $token_id = null,
        string | null $bank = null,
        int | null $installment_term = null,
        array | null $bins = null,
        string | null $type = 'authorize',
        bool | null $save_token_id = null
    ): static {
        $options = [];
        if (! is_string($token_id)) {
            if ($token_id instanceof CreditCard) {
                $token_id = $token_id->getToken();
            }
            $token_id = $token_id->token();
        }
        $options = get_defined_vars();

        return app(static::class)
            ->type('credit_card')
            ->optionKey('credit_card')
            ->options($options);
    }
}
