<?php

namespace Kasir\Kasir;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Kasir\Kasir\Concerns\CanConfigurePayload;
use Kasir\Kasir\Concerns\EvaluateClosures;
use Kasir\Kasir\Concerns\Transactions\HasBillingAddress;
use Kasir\Kasir\Concerns\Transactions\HasCustomerDetails;
use Kasir\Kasir\Concerns\Transactions\HasEnabledPayments;
use Kasir\Kasir\Concerns\Transactions\HasItemDetails;
use Kasir\Kasir\Concerns\Transactions\HasPaymentType;
use Kasir\Kasir\Concerns\Transactions\HasShippingAddress;
use Kasir\Kasir\Concerns\Transactions\HasTransactionDetails;
use Kasir\Kasir\Concerns\Validation;
use Kasir\Kasir\Contracts\CanConfigurePaymentType;
use Kasir\Kasir\Contracts\ShouldConfigurePayload;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Helper\MidtransResponse;
use Kasir\Kasir\Helper\Request;

class Kasir implements Arrayable, ShouldConfigurePayload, CanConfigurePaymentType
{
    use CanConfigurePayload;
    use EvaluateClosures;
    use HasBillingAddress;
    use HasCustomerDetails;
    use HasEnabledPayments;
    use HasItemDetails;
    use HasPaymentType;
    use HasShippingAddress;
    use HasTransactionDetails;
    use Validation;

    const SANDBOX_BASE_URL = 'https://api.sandbox.midtrans.com';

    const PRODUCTION_BASE_URL = 'https://api.midtrans.com';

    const SNAP_SANDBOX_BASE_URL = 'https://app.sandbox.midtrans.com/snap/v1';

    const SNAP_PRODUCTION_BASE_URL = 'https://app.midtrans.com/snap/v1';

    public function __construct(?int $gross_amount)
    {
        $this->grossAmount($gross_amount);
        $this->orderId($order_id ?? Str::orderedUuid());
    }

    /**
     * Initialize Kasir with base Gross Amount
     *
     * @param  int|null  $gross_amount
     * @return static
     */
    public static function make(?int $gross_amount = null): static
    {
        return app(static::class, [
            'gross_amount' => $gross_amount ?? null,
        ]);
    }

    /**
     * Convert passed data to an array.
     *
     * @return array
     *
     * @throws ZeroGrossAmountException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function toArray(): array
    {
        $this->validate();

        $array = [
            'transaction_details' => $this->transaction_details,
        ];

        if (! is_null($this->getItemDetails())) {
            $array['item_details'] = array_values($this->getItemDetails());
        }

        if (! is_null($this->getCustomerDetails())) {
            $array['customer_details'] = $this->getCustomerDetails();
            if (! is_null($this->getBillingAddress())) {
                $array['customer_details']['billing_address'] = $this->getBillingAddress();
            }
            if (! is_null($this->getShippingAddress())) {
                $array['customer_details']['shipping_address'] = $this->getShippingAddress();
            }
        }

        if (! is_null($this->getEnabledPayments())) {
            $array['enabled_payments'] = array_values($this->getEnabledPayments());
        }

        if (! empty($this->getPaymentType())) {
            $array['payment_type'] = $this->getPaymentType();
            $array[$this->getPaymentOptionKey()] = $this->getPaymentOptions();
        }

        return static::configurePayload($array);
    }

    public static function fromArray(array $data): static
    {
        $static = static::make($data['transaction_details']['gross_amount'] ?? null);
        $static->orderId($data['transaction_details']['order_id'] ?? Str::orderedUuid());
        $static->itemDetails($data['item_details'] ?? null);
        $static->customerDetails($data['customer_details'] ?? null);
        $static->billingAddress($data['customer_details']['billing_address'] ?? null);
        $static->shippingAddress($data['customer_details']['shipping_address'] ?? null);
        $static->enablePayments($data['enabled_payments'] ?? null);

        if (! empty($static->getItemDetails())) {
            $gross_amount = self::calculateGrossAmount($data)['transaction_details']['gross_amount'];
            $static->grossAmount($gross_amount);
        }

        return $static;
    }

    /**
     * Get Base URL for the API
     *
     * @return string
     */
    public static function getBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::PRODUCTION_BASE_URL
            : self::SANDBOX_BASE_URL;
    }

    /**
     * Get Base URL for the SNAP API
     *
     * @return string
     */
    public static function getSnapBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::SNAP_PRODUCTION_BASE_URL
            : self::SNAP_SANDBOX_BASE_URL;
    }

    public function charge()
    {
        return Request::post(
            self::getBaseUrl() . '/v2/charge',
            config('kasir.server_key'),
            static::toArray()
        );
    }

    public function status(): MidtransResponse
    {
        $id = $this->transaction_details['order_id'];

        return static::getStatus($id);
    }

    public static function getStatus($id): MidtransResponse
    {
        return Request::get(
            static::getBaseUrl() . '/v2/' . $id . '/status',
            config('kasir.server_key'),
        );
    }

    /**
     * Capture the transaction of a given ID or Response.
     *
     * @param  MidtransResponse|string  $transaction_id
     * @return MidtransResponse
     *
     * @throws MidtransKeyException
     * @throws GuzzleException
     */
    public static function capture(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        $payload = get_defined_vars();

        return Request::post(
            static::getBaseUrl() . '/v2/capture',
            config('kasir.server_key'),
            $payload
        );
    }
}
