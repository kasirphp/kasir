<?php

namespace Kasir\Kasir\Concerns;

trait HasOrderId
{
    protected string $order_id;

    public function orderId(string|\Closure $order_id): static
    {
        $this->transaction_details['order_id'] = $this->evaluate($order_id);

        return $this;
    }

    public function getOrderId(): string
    {
        return $this->evaluate($this->transaction_details['order_id']);
    }
}
