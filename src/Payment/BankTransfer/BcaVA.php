<?php

namespace Kasir\Kasir\Payment\BankTransfer;

use Kasir\Kasir\Contracts\PaymentMethod;
use Kasir\Kasir\Payment\PaymentObject;

class BcaVA extends PaymentObject implements PaymentMethod
{
    /**
     * Create BCA Virtual Account payment object.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @param  string|null  $sub_company_code  BCA sub company code directed for this transactions.
     * @param  string|null  $inquiry_text_en  English Inquiry Text.
     * @param  string|null  $inquiry_text_id  Indonesian Inquiry Text.
     * @param  string|null  $payment_text_en  English Payment Text.
     * @param  string|null  $payment_text_id  Indonesian Payment Text.
     *
     * @see https://docs.midtrans.com/reference/bank-transfer-object
     * @see https://docs.midtrans.com/reference/bank-transfer-object#bca-va-object
     */
    public static function make(
        string | null $va_number = null,
        string | null $sub_company_code = null,
        string | null $inquiry_text_en = null,
        string | null $inquiry_text_id = null,
        string | null $payment_text_en = null,
        string | null $payment_text_id = null
    ): static {
        $options = compact('va_number');
        $options['bank'] = 'bca';
        $options['bca'] = compact('sub_company_code');

        if ($inquiry_text_en || $inquiry_text_id) {
            $options['free_text']['inquiry'] = [
                'en' => $inquiry_text_en,
                'id' => $inquiry_text_id,
            ];
        }

        if ($payment_text_en || $payment_text_id) {
            $options['free_text']['payment'] = [
                'en' => $payment_text_en,
                'id' => $payment_text_id,
            ];
        }

        $type = 'bank_transfer';

        return app(static::class)
            ->type($type)
            ->optionKey($type)
            ->options($options);
    }
}
