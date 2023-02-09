<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasOrderId
{
    protected string | \Closure $order_id;

    public function orderId(string | \Closure $order_id): static
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getOrderId(): string
    {
        return $this->evaluate($this->order_id);
    }
}
