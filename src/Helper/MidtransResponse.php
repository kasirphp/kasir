<?php

namespace Kasir\Kasir\Helper;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Kasir;
use Psr\Http\Message\MessageInterface;

class MidtransResponse extends Response
{
    private string | null $transaction_id;

    private string | null $transaction_status;

    private array | null $actions;

    public function __construct(MessageInterface $response)
    {
        parent::__construct($response);

        $this->response = $response->withStatus(
            $this->json('status_code') ?? 200,
            $this->json('status_message') ?? 'OK'
        );
        $this->transaction_id = $this->json('transaction_id');
        $this->transaction_status = $this->json('transaction_status');
        $this->actions = Arr::keyBy($this->json('actions'), 'name');
    }

    /**
     * Get the Transaction ID.
     */
    public function transactionId(): string | null
    {
        return $this->transaction_id;
    }

    /**
     * Get the Transaction Status.
     */
    public function transactionStatus(): string | null
    {
        return $this->transaction_status;
    }

    /**
     * Get response actions.
     */
    public function actions(): mixed
    {
        return $this->actions;
    }

    /**
     * Get response action names.
     */
    public function actionsName(): array | null
    {
        return $this->actions() ? array_column($this->actions(), 'name') : null;
    }

    /**
     * Get Credit Card fraud status.
     */
    public function fraudStatus(): string | null
    {
        return $this->json('fraud_status');
    }

    /**
     * Get response action by name.
     *
     * @return array|null
     */
    public function action(string $name): mixed
    {
        if (! $this->actions()) {
            return null;
        }

        return $this->actions()[$name] ?? null;
    }

    /**
     * Capture this transaction.
     *
     *
     * @throws GuzzleException
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function capture(): MidtransResponse
    {
        return Kasir::capture($this->transactionId());
    }

    /**
     * Approve this challenged transaction.
     *
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function approve(): MidtransResponse
    {
        return Kasir::approve($this->transactionId());
    }

    /**
     * Deny this challenged transaction.
     *
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function deny(): MidtransResponse
    {
        return Kasir::deny($this->transactionId());
    }

    /**
     * Cancel this transaction.
     *
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function cancel(): MidtransResponse
    {
        return Kasir::cancel($this->transactionId());
    }

    /**
     * Expire this transaction.
     *
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function expire(): MidtransResponse
    {
        return Kasir::expire($this->transactionId());
    }

    /**
     * Refund this transaction.
     *
     * @param  int|null  $amount  Amount to be refunded. By default whole transaction amount is refunded.
     * @param  string|null  $reason  Reason justifying the refund.
     * @param  string|null  $refund_key  Merchant refund ID. If not passed then Midtrans creates a new one. It is recommended to use this parameter to avoid double refund attempt.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function refund(
        int | null $amount = null,
        string | null $reason = null,
        string | null $refund_key = null
    ): MidtransResponse {
        return Kasir::refund($this->transactionId(), $amount, $reason, $refund_key);
    }

    /**
     * Direct refund this transaction.
     *
     * @param  int|null  $amount  Amount to be refunded. By default whole transaction amount is refunded.
     * @param  string|null  $reason  Reason justifying the refund.
     * @param  string|null  $refund_key  Merchant refund ID. If not passed then Midtrans creates a new one. It is recommended to use this parameter to avoid double refund attempt.
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public function directRefund(
        int | null $amount = null,
        string | null $reason = null,
        string | null $refund_key = null
    ): MidtransResponse {
        return Kasir::directRefund($this->transactionId(), $amount, $reason, $refund_key);
    }
}
