<?php

namespace Kasir\Kasir\Concerns;

trait HasOrderId
{
    protected string $order_id;

    public function orderId(string $order_id): static
    {
        $this->transaction_details['order_id'] = $order_id;

        return $this;
    }

    public function getOrderId(): string
    {
        return $this->evaluate($this->transaction_details['order_id']);
    }
}
