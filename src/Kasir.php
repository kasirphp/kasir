<?php

namespace Kasir\Kasir;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Kasir\Kasir\Concerns\CanConfigurePayload;
use Kasir\Kasir\Concerns\EvaluateClosures;
use Kasir\Kasir\Concerns\Transactions\HasBillingAddress;
use Kasir\Kasir\Concerns\Transactions\HasCustomerDetails;
use Kasir\Kasir\Concerns\Transactions\HasEnabledPayments;
use Kasir\Kasir\Concerns\Transactions\HasItemDetails;
use Kasir\Kasir\Concerns\Transactions\HasPaymentMethods;
use Kasir\Kasir\Concerns\Transactions\HasShippingAddress;
use Kasir\Kasir\Concerns\Transactions\HasTransactionDetails;
use Kasir\Kasir\Concerns\Validation;
use Kasir\Kasir\Contracts\CanConfigurePaymentType;
use Kasir\Kasir\Contracts\ShouldConfigurePayload;
use Kasir\Kasir\Exceptions\MidtransApiException;
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
    use HasPaymentMethods;
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
     */
    public static function getBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::PRODUCTION_BASE_URL
            : self::SANDBOX_BASE_URL;
    }

    /**
     * Get Base URL for the SNAP API
     */
    public static function getSnapBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::SNAP_PRODUCTION_BASE_URL
            : self::SNAP_SANDBOX_BASE_URL;
    }

    /**
     * Convert this class to Snap object.
     *
     * @throws ZeroGrossAmountException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function snap(): Snap
    {
        return Snap::fromArray($this->toArray());
    }

    /**
     * Charge the transaction.
     *
     * @throws ZeroGrossAmountException
     * @throws MidtransKeyException
     * @throws NoItemDetailsException
     * @throws GuzzleException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function charge(): MidtransResponse
    {
        return Request::post(
            self::getBaseUrl() . '/v2/charge',
            config('kasir.server_key'),
            static::toArray()
        );
    }

    /**
     * Get status of current transaction.
     *
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function status(): MidtransResponse
    {
        $transaction_id = $this->transaction_details['order_id'];

        return static::getStatus($transaction_id);
    }

    /**
     * Get status of given transaction ID.
     *
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function getStatus(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        try {
            return Request::get(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/status',
                config('kasir.server_key'),
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();
            $validation_messages = json_decode($response->getBody()->getContents())->validation_messages;
            $messages = implode(', ', $validation_messages);

            throw new MidtransApiException($messages, $response->getStatusCode());
        } catch (MidtransKeyException $e) {
            throw new $e;
        }
    }

    /**
     * Capture the transaction of a given ID or Response.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse
     *
     * @throws GuzzleException
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function capture(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        $payload = get_defined_vars();

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/capture',
                config('kasir.server_key'),
                $payload
            );
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $validation_messages = json_decode($response->getBody()->getContents())->validation_messages;
            $messages = implode(', ', $validation_messages);

            throw new MidtransApiException($messages, $response->getStatusCode());
        }
    }

    /**
     * Approve a challenged transaction with Transaction ID or Order ID.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function approve(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/approve',
                config('kasir.server_key'),
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }

    /**
     * Deny a challenged transaction with Transaction ID or Order ID.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function deny(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/deny',
                config('kasir.server_key'),
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }

    /**
     * Cancel a pending transaction with Transaction ID or Order ID.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function cancel(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/cancel',
                config('kasir.server_key'),
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }

    /**
     * Expire a pending transaction with Transaction ID or Order ID.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function expire(MidtransResponse | string $transaction_id): MidtransResponse
    {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/expire',
                config('kasir.server_key'),
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }

    /**
     * Refund a transaction with Transaction ID or Order ID.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse.
     * @param  int|null  $amount  Amount to be refunded. By default whole transaction amount is refunded.
     * @param  string|null  $reason  Reason justifying the refund.
     * @param  string|null  $refund_key  Merchant refund ID. If not passed then Midtrans creates a new one. It is recommended to use this parameter to avoid double refund attempt.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function refund(
        MidtransResponse | string $transaction_id,
        int | null $amount = null,
        string | null $reason = null,
        string | null $refund_key = null
    ): MidtransResponse {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        $payload = compact('amount', 'reason', 'refund_key');

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/refund',
                config('kasir.server_key'),
                $payload ?: null
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }

    /**
     * Direct refund a transaction with Transaction ID or Order ID.
     *
     * @param  MidtransResponse|string  $transaction_id  Transaction ID or Order ID or MidtransResponse.
     * @param  int|null  $amount  Amount to be refunded. By default whole transaction amount is refunded.
     * @param  string|null  $reason  Reason justifying the refund.
     * @param  string|null  $refund_key  Merchant refund ID. If not passed then Midtrans creates a new one. It is recommended to use this parameter to avoid double refund attempt.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function directRefund(
        MidtransResponse | string $transaction_id,
        int | null $amount = null,
        string | null $reason = null,
        string | null $refund_key = null
    ): MidtransResponse {
        if ($transaction_id instanceof MidtransResponse) {
            $transaction_id = $transaction_id->transactionId();
        }

        $payload = compact('amount', 'reason', 'refund_key');

        try {
            return Request::post(
                static::getBaseUrl() . '/v2/' . $transaction_id . '/refund/online/direct',
                config('kasir.server_key'),
                $payload ?: null
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }
}
