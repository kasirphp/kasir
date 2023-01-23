<?php

namespace Kasir\Kasir\Concerns\Transactions;

use Kasir\Kasir\Contracts\PaymentType;
use Kasir\Kasir\Payment\BankTransfer\BcaVA;
use Kasir\Kasir\Payment\BankTransfer\BniVA;
use Kasir\Kasir\Payment\BankTransfer\BriVA;
use Kasir\Kasir\Payment\BankTransfer\MandiriBill;
use Kasir\Kasir\Payment\BankTransfer\PermataVA;
use Kasir\Kasir\Payment\CardlessCredit\Akulaku;
use Kasir\Kasir\Payment\CardlessCredit\Kredivo;
use Kasir\Kasir\Payment\CStore\Alfamart;
use Kasir\Kasir\Payment\CStore\Indomaret;
use Kasir\Kasir\Payment\EMoney\GoPay;
use Kasir\Kasir\Payment\EMoney\Qris;
use Kasir\Kasir\Payment\EMoney\ShopeePay;
use Kasir\Kasir\Payment\InternetBanking\BcaKlikpay;
use Kasir\Kasir\Payment\InternetBanking\BriMo;
use Kasir\Kasir\Payment\InternetBanking\CimbClicks;
use Kasir\Kasir\Payment\InternetBanking\DanamonOnline;
use Kasir\Kasir\Payment\InternetBanking\KlikBca;
use Kasir\Kasir\Payment\InternetBanking\UobEzpay;

trait HasPaymentType
{
    protected string | null $payment_type = null;

    protected array | null $payment_options = null;

    protected string | null $payment_option_key = null;

    /**
     * Using Permata Virtual Account as payment type.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @param  string|null  $recipient_name  Recipient name shown on the payment details.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#permata-virtual-account
     */
    public function permataVA(string | null $va_number = null, string | null $recipient_name = null): static
    {
        $payment = PermataVA::make($va_number, $recipient_name);
        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using BCA Virtual Account as payment type.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @param  string|null  $sub_company_code  BCA sub company code directed for this transactions.
     * @param  string|null  $inquiry_text_en  English Inquiry Text.
     * @param  string|null  $inquiry_text_id  Indonesian Inquiry Text.
     * @param  string|null  $payment_text_en  English Payment Text.
     * @param  string|null  $payment_text_id  Indonesian Payment Text.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#bca-virtual-account
     */
    public function bcaVA(
        string | null $va_number = null,
        string | null $sub_company_code = null,
        string | null $inquiry_text_en = null,
        string | null $inquiry_text_id = null,
        string | null $payment_text_en = null,
        string | null $payment_text_id = null
    ): static {
        $payment = BcaVA::make(
            $va_number,
            $sub_company_code,
            $inquiry_text_en,
            $inquiry_text_id,
            $payment_text_en,
            $payment_text_id
        );

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using BNI Virtual Account as payment method.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#bni-virtual-account
     */
    public function bniVA(string | null $va_number = null): static
    {
        $payment = BniVA::make($va_number);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using BRI Virtual Account as payment method.
     *
     * @param  string|null  $va_number  Custom VA number assigned by you.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#bri-virtual-account
     */
    public function briVA(string | null $va_number = null): static
    {
        $payment = BriVA::make($va_number);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using Mandiri Bill as payment method.
     *
     * @param  string|null  $bill_info1  Label 1.
     * @param  string|null  $bill_info2  Value for Label 1.
     * @param  string|null  $bill_info3  Label 2.
     * @param  string|null  $bill_info4  Value for Label 2.
     * @param  string|null  $bill_info5  Label 3.
     * @param  string|null  $bill_info6  Value for Label 3.
     * @param  string|null  $bill_info7  Label 4.
     * @param  string|null  $bill_info8  Value for Label 4.
     * @param  string|null  $bill_key  Custom bill key assigned by you.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#mandiri-bill-payment
     */
    public function mandiriBill(
        string $bill_info1 = null,
        string $bill_info2 = null,
        string | null $bill_info3 = null,
        string | null $bill_info4 = null,
        string | null $bill_info5 = null,
        string | null $bill_info6 = null,
        string | null $bill_info7 = null,
        string | null $bill_info8 = null,
        string | null $bill_key = null,
    ): static {
        $payment = MandiriBill::make(
            $bill_info1,
            $bill_info2,
            $bill_info3,
            $bill_info4,
            $bill_info5,
            $bill_info6,
            $bill_info7,
            $bill_info8,
            $bill_key
        );

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using BCA Klikpay as payment method.
     *
     * @param  string|null  $description  Description of the BCA KlickPay transaction.
     * @param  string|null  $misc_fee  Additional fee for documentation.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#bca-klikpay
     */
    public function bcaKlikpay(string $description = null, string $misc_fee = null): static
    {
        $payment = BcaKlikpay::make($description, $misc_fee);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using KlikBCA as payment method.
     *
     * @param  string|null  $description  https://api-docs.midtrans.com/#klikbca
     * @param  string|null  $user_id  KlikBCA User ID.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#klikbca
     */
    public function klikBca(string $description = null, string $user_id = null): static
    {
        $payment = KlikBca::make($description, $user_id);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using Danamon Online as payment method.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#danamon-online-banking
     */
    public function danamonOnline(): static
    {
        $payment = DanamonOnline::make();

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using BRImo as payment method.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#brimo
     */
    public function briMo(): static
    {
        $payment = BriMo::make();

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using CIMB Clicks as payment method.
     *
     * @param  string|null  $description  Description of CIMB transaction. This will be displayed on the CIMB email notification.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#cimb-clicks
     */
    public function cimbClicks(string $description = null): static
    {
        $payment = CimbClicks::make($description);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using UOB EZpay as payment type.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#uob-ezpay
     */
    public function uobEzpay(): static
    {
        $payment = UobEzpay::make();

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using QRIS as payment type.
     *
     * @param  string  $acquirer  The acquirer for QRIS. Possible values are airpay shopee, gopay.
     * @return $this
     *
     * @see https://api-docs.midtrans.com/#qris
     */
    public function qris(string $acquirer = 'gopay'): static
    {
        $payment = Qris::make($acquirer);

        $this->paymentType($payment);

        return $this;
    }

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
     * @see https://api-docs.midtrans.com/#gopay
     */
    public function gopay(
        bool $enable_callback = false,
        string $callback_url = null,
        string $account_id = null,
        string $payment_option_token = null,
        bool $pre_auth = false,
        bool $recurring = false
    ): static {
        $payment = GoPay::make(
            $enable_callback,
            $callback_url,
            $account_id,
            $payment_option_token,
            $pre_auth,
            $recurring
        );

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using ShopeePay as payment type.
     *
     * @param  string  $callback_url  The URL to redirect the customer back from the ShopeePay app. Default value is the finish URL, configured on your MAP account.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#shopeepay
     */
    public function shopeepay(string $callback_url = ''): static
    {
        $payment = ShopeePay::make($callback_url);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using Indomaret as payment type.
     *
     * @param  string  $message  Label displayed in Indomaret POS.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#indomaret
     */
    public function indomaret(string $message = ''): static
    {
        $payment = Indomaret::make($message);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using Alfamart as payment type.
     *
     * @param  string|null  $alfamart_free_text_1  Customizable first row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_2  Customizable second row of text on the Alfamart printed receipt.
     * @param  string|null  $alfamart_free_text_3  Customizable third row of text on the Alfamart printed receipt.
     * @return static
     *
     * @see https://api-docs.midtrans.com/#alfamart
     */
    public function alfamart(
        string | null $alfamart_free_text_1 = '',
        string | null $alfamart_free_text_2 = '',
        string | null $alfamart_free_text_3 = ''
    ): static {
        $payment = Alfamart::make($alfamart_free_text_1, $alfamart_free_text_2, $alfamart_free_text_3);

        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using Akulaku as payment type.
     *
     * @return $this
     *
     * @see https://api-docs.midtrans.com/#akulaku-paylater
     */
    public function akulaku(): static
    {
        $payment = Akulaku::make();
        $this->paymentType($payment);

        return $this;
    }

    /**
     * Using Kredivo as payment method.
     *
     * @return static
     *
     * @see https://api-docs.midtrans.com/#kredivo
     */
    public function kredivo()
    {
        $payment = Kredivo::make();
        $this->paymentType($payment);

        return $this;
    }

    /**
     * Assign payment type.
     *
     * @param  PaymentType|null  $payment_type
     * @return $this
     */
    public function paymentType(PaymentType | null $payment_type): static
    {
        if ($payment_type) {
            $this->payment_type = $payment_type->getType();
            $this->payment_options = $payment_type->getOptions();
            $this->payment_option_key = $payment_type->getOptionKey();
        }

        return $this;
    }

    public function getPaymentType(): string | null
    {
        return $this->payment_type;
    }

    public function getPaymentOptionKey(): string | null
    {
        return $this->payment_option_key;
    }

    public function getPaymentOptions(): array | null
    {
        return $this->payment_options;
    }
}
