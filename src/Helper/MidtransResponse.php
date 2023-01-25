<?php

namespace Kasir\Kasir\Helper;

use GuzzleHttp\Exception\GuzzleException;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Kasir;
use Psr\Http\Message\ResponseInterface;

class MidtransResponse extends Response
{
    private string | null $transaction_id;

    private array | null $actions;

    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->transaction_id = $this->json('transaction_id');
        $this->actions = $this->json('actions');
    }

    public function transactionId(): string | null
    {
        return $this->transaction_id;
    }

    /**
     * Get response actions.
     *
     * @return mixed
     */
    public function actions(): mixed
    {
        return $this->actions;
    }

    /**
     * Get response action names.
     *
     * @return array|null
     */
    public function actionsName(): array | null
    {
        return $this->actions() ? array_column($this->actions(), 'name') : null;
    }

    /**
     * Get response action by name.
     *
     * @param  string  $name
     * @return mixed|null
     */
    public function action(string $name): mixed
    {
        if (! $this->actions()) {
            return null;
        }

        $array = array_filter($this->actions(), function ($action) use ($name) {
            return $action['name'] === $name;
        });

        $result = call_user_func_array('array_merge', $array);

        return ! empty($result) ? $result : null;
    }

    /**
     * Capture this transaction.
     *
     * @return MidtransResponse
     *
     * @throws GuzzleException
     * @throws MidtransKeyException
     */
    public function capture(): MidtransResponse
    {
        return Kasir::capture($this->transactionId());
    }
}
