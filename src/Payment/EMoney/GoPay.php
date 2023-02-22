<?php

namespace Kasir\Kasir\Payment\EMoney;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class GoPay extends PaymentObject implements PaymentMethod
{
    /**
     * Create GoPay payment object.
     *
     * @param  bool  $enable_callback  Required for GoPay deeplink/QRIS. To determine appending callback URL in the deeplink. Default value: false.
     * @param  string|null  $callback_url  The HTTP or Deeplink URL to which the customer is redirected from Gojek app after successful payment. Default value: callback_url in dashboard settings.
     * @param  string|\Kasir\Kasir\GoPay|null  $account_id  Required for GoPay Tokenization. The customer account ID linked during Create Pay Account API.
     * @param  string|null  $payment_option_token  Required for GoPay Tokenization. Token to specify the payment option made by the customer from Get Pay Account API metadata.
     * @param  bool  $pre_auth  Set the value to true to reserve the specified amount from the customer balance. Once the customer balance is reserved, you can initiate a subsequent Capture API request. Default value: false.
     * @param  bool  $recurring  Set the value to true to mark as a recurring transaction, only allowed for authorised merchant. Default value: false
     *
     * @see https://docs.midtrans.com/reference/gopay-1
     * @see https://docs.midtrans.com/reference/gopay-object
     */
    public static function make(
        bool $enable_callback = false,
        string | null $callback_url = null,
        string | \Kasir\Kasir\GoPay | null $account_id = null,
        string | null $payment_option_token = null,
        bool $pre_auth = false,
        bool $recurring = false
    ): static {
        $options = array_filter(get_defined_vars(), 'strlen');

        if ($account_id instanceof \Kasir\Kasir\GoPay) {
            if (! $account_id->accountId()) {
                $account_id->status();
            }
            $options['account_id'] = $account_id->accountId();
        }

        $key = 'gopay';

        return app(static::class)
            ->type($key)
            ->optionKey($key)
            ->options($options);
    }
}
