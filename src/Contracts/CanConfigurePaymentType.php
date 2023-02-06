<?php

namespace Kasir\Kasir\Contracts;

use Kasir\Kasir\Payment\CreditCard\CardToken;
use Kasir\Kasir\Payment\CreditCard\CreditCard;

interface CanConfigurePaymentType
{
    /**
     * Using Card as payment method.
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
    public function creditCard(
        CreditCard | CardToken | string $token_id,
        string | null $bank = null,
        int | null $installment_term = null,
        array | null $bins = null,
        string | null $type = 'authorize',
        bool | null $save_token_id = null
    ): static;

    /**
     * Using Permata Virtual Account as payment method.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @param  string|null  $recipient_name  Recipient name shown on the payment details.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bank-transfer-object
     * @see https://docs.midtrans.com/reference/bank-transfer-object#permata-va-object
     */
    public function permataVA(string | null $va_number = null, string | null $recipient_name = null): static;

    /**
     * Using BCA Virtual Account as payment method.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @param  string|null  $sub_company_code  BCA sub company code directed for this transactions.
     * @param  string|null  $inquiry_text_en  English Inquiry Text.
     * @param  string|null  $inquiry_text_id  Indonesian Inquiry Text.
     * @param  string|null  $payment_text_en  English Payment Text.
     * @param  string|null  $payment_text_id  Indonesian Payment Text.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bank-transfer-object
     * @see https://docs.midtrans.com/reference/bank-transfer-object#bca-va-object
     */
    public function bcaVA(
        string | null $va_number = null,
        string | null $sub_company_code = null,
        string | null $inquiry_text_en = null,
        string | null $inquiry_text_id = null,
        string | null $payment_text_en = null,
        string | null $payment_text_id = null
    ): static;

    /**
     * Using BNI Virtual Account as payment method.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bank-transfer-object
     */
    public function bniVA(string | null $va_number = null): static;

    /**
     * Using BRI Virtual Account as payment method.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bank-transfer-object
     */
    public function briVA(string | null $va_number = null): static;

    /**
     * Using Mandiri Bill as payment method.
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
    public function mandiriBill(
        string $bill_info1,
        string $bill_info2,
        string | null $bill_info3 = null,
        string | null $bill_info4 = null,
        string | null $bill_info5 = null,
        string | null $bill_info6 = null,
        string | null $bill_info7 = null,
        string | null $bill_info8 = null,
        string | null $bill_key = null,
    ): static;

    /**
     * Using BCA Klikpay as payment method.
     *
     * @param  string  $description  Description of the BCA KlikPay transaction.
     * @param  string|null  $misc_fee  Additional fee for documentation.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bca-klikpay-object
     */
    public function bcaKlikpay(string $description, string | null $misc_fee = null): static;

    /**
     * Using KlikBCA as payment method.
     *
     * @param  string  $description  Description of KlikBCA transaction.
     * @param  string  $user_id  KlikBCA User ID.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/bca-klikbca-object
     */
    public function klikBca(string $description, string $user_id): static;

    /**
     * Using Danamon Online as payment method.
     *
     * @return static
     *
     * @see https://docs.midtrans.com/reference/danamon-online-banking-dob
     */
    public function danamonOnline(): static;

    /**
     * Using BRImo as payment method.
     *
     * @return static
     *
     * @see https://docs.midtrans.com/reference/brimo-1
     */
    public function briMo(): static;

    /**
     * Using CIMB Clicks as payment method.
     *
     * @param  string|null  $description  Description of CIMB transaction. This will be displayed on the CIMB email notification.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/cimb-clicks-1
     * @see https://docs.midtrans.com/reference/cimb-clicks-object
     */
    public function cimbClicks(string $description = null): static;

    /**
     * Using UOB EZpay as payment method.
     *
     * @return static
     *
     * @see https://docs.midtrans.com/reference/uob-ezpay
     */
    public function uobEzpay(): static;

    /**
     * Using QRIS as payment method.
     *
     * @param  string  $acquirer  The acquirer for QRIS. Possible values are airpay shopee, gopay.
     * @return $this
     *
     * @see https://docs.midtrans.com/reference/qris
     */
    public function qris(string $acquirer = 'gopay'): static;

    /**
     * Using GoPay as payment object.
     *
     * @param  bool  $enable_callback  Required for GoPay deeplink/QRIS. To determine appending callback URL in the deeplink. Default value: false.
     * @param  string|null  $callback_url  The HTTP or Deeplink URL to which the customer is redirected from Gojek app after successful payment. Default value: callback_url in dashboard settings.
     * @param  string|null  $account_id  Required for GoPay Tokenization. The customer account ID linked during Create Pay Account API.
     * @param  string|null  $payment_option_token  Required for GoPay Tokenization. Token to specify the payment option made by the customer from Get Pay Account API metadata.
     * @param  bool  $pre_auth  Set the value to true to reserve the specified amount from the customer balance. Once the customer balance is reserved, you can initiate a subsequent Capture API request. Default value: false.
     * @param  bool  $recurring  Set the value to true to mark as a recurring transaction, only allowed for authorised merchant. Default value: false
     * @return static
     *
     * @see https://docs.midtrans.com/reference/gopay-1
     * @see https://docs.midtrans.com/reference/gopay-object
     */
    public function gopay(
        bool $enable_callback = false,
        string | null $callback_url = null,
        string | null $account_id = null,
        string | null $payment_option_token = null,
        bool $pre_auth = false,
        bool $recurring = false
    ): static;

    /**
     * Using ShopeePay as payment method.
     *
     * @param  string|null  $callback_url  The URL to redirect the customer back from the ShopeePay app. Default value is the finish URL, configured on your MAP account.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/shopeepay-1
     * @see https://docs.midtrans.com/reference/shopeepay-object
     */
    public function shopeepay(string | null $callback_url = null): static;

    /**
     * Using Indomaret as payment method.
     *
     * @param  string  $message  Label displayed in Indomaret POS.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/indomaret-1
     */
    public function indomaret(string $message): static;

    /**
     * Using Alfamart as payment method.
     *
     * @param  string|null  $alfamart_free_text_1  Customizable first row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_2  Customizable second row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_3  Customizable third row of text on the Alfamart printed receipt.
     * @return static
     *
     * @see https://docs.midtrans.com/reference/alfamart-1
     */
    public function alfamart(
        string | null $alfamart_free_text_1 = '',
        string | null $alfamart_free_text_2 = '',
        string | null $alfamart_free_text_3 = ''
    ): static;

    /**
     * Using Akulaku as payment method.
     *
     * @return $this
     *
     * @see https://docs.midtrans.com/reference/akulaku-1
     */
    public function akulaku(): static;

    /**
     * Using Kredivo as payment method.
     *
     * @return static
     *
     * @see https://docs.midtrans.com/reference/kredivo-1
     */
    public function kredivo();

    /**
     * Assign payment method.
     *
     * @param  PaymentMethod|null  $payment_method
     * @return $this
     */
    public function paymentMethod(PaymentMethod | null $payment_method): static;

    /**
     * Assign payment method.
     *
     * @param  PaymentMethod|null  $payment_method
     * @return $this
     *
     * @deprecated Use paymentMethod() instead.
     */
    public function paymentType(PaymentMethod | null $payment_method): static;

    public function getPaymentType(): string | null;

    public function getPaymentOptionKey(): string | null;

    public function getPaymentOptions(): array | null;
}
