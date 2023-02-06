<?php

namespace Kasir\Kasir\Payment\CreditCard;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class CreditCardPayment extends PaymentObject implements PaymentMethod
{
    /**
     * Create CreditCardPayment payment object.
     *
     * @param  CreditCard|CardToken|string  $token_id  CardToken, or token_id string. This token_id represents customer credit card information. If CreditCard is provided, it will be used to generate token_id.
     * @param  string|null  $bank  Name of the acquiring bank. Valid values are: 'mandiri', 'bni', 'cimb', 'bca', 'maybank', and 'bri'.
     * @param  int|null  $installment_term  Installment tenure in terms of months.
     * @param  array|null  $bins  List of credit card's BIN (Bank Identification Number) that is allowed for transaction.
     * @param  string|null  $type  Used as preauthorization feature. Valid value: 'authorize'.
     * @param  bool|null  $save_token_id  Used on 'One Click' or 'Two Clicks' feature. Enabling it will return a 'saved_token_id' that can be used for the next transaction.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/credit-card-object
     */
    public static function make(
        CreditCard | CardToken | string $token_id,
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
