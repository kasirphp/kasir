<?php

namespace Kasir\Kasir\Concerns;

trait HasShippingAddress
{
    protected array|\Closure|null $shipping_address = null;

    public function shippingAddress(array|\Closure|null $shipping_address): static
    {
        $this->shipping_address = $shipping_address;

        return $this;
    }

    public function getShippingAddress(): array|null
    {
        return $this->evaluate($this->shipping_address);
    }
}
