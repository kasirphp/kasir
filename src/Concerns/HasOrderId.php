<?php

namespace Kasir\Kasir\Concerns;

trait HasOrderId
{
    protected string|int $order_id;

    public function orderId(string|int $order_id): static
    {
        $this->transaction_details['order_id'] = $order_id;

        return $this;
    }

    public function getOrderId(): string|int
    {
        return $this->evaluate($this->transaction_details['order_id']);
    }
}
