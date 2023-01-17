<?php

namespace Kasir\Kasir\Concerns;

trait HasBillingAddress
{
    protected array|\Closure|null $billing_address = null;

    public function billingAddress(array|\Closure|null $billing_address): static
    {
        $this->billing_address = $billing_address;

        return $this;
    }

    public function getBillingAddress(): array|null
    {
        return $this->evaluate($this->billing_address);
    }
}
