<?php

namespace Kasir\Kasir\Concerns;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Helper\MidtransResponse;
use Kasir\Kasir\Helper\Request;
use Kasir\Kasir\Kasir;

trait InteractsWithPaymentApi
{
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
            Kasir::getBaseUrl() . '/v2/charge',
            config('kasir.server_key'),
            $this->toArray()
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
        $transaction_id = $this->getOrderId();

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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/status',
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
                Kasir::getBaseUrl() . '/v2/capture',
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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/approve',
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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/deny',
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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/cancel',
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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/expire',
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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/refund',
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
                Kasir::getBaseUrl() . '/v2/' . $transaction_id . '/refund/online/direct',
                config('kasir.server_key'),
                $payload ?: null
            );
        } catch (GuzzleException | RequestException $e) {
            $response = $e->getResponse();

            throw new MidtransApiException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }
}
