<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasCustomerDetails
{
    protected array|\Closure|null $customer_details = null;

    public function customerDetails(array|\Closure|null $customer_details): static
    {
        $this->customer_details = $customer_details;

        return $this;
    }

    public function getCustomerDetails(): array|null
    {
        return $this->evaluate($this->customer_details);
    }
}
