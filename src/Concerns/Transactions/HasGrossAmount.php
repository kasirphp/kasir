<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasGrossAmount
{
    protected ?int $gross_amount;

    public function grossAmount(?int $gross_amount): static
    {
        $this->transaction_details['gross_amount'] = $this->evaluate($gross_amount);

        return $this;
    }

    public function getGrossAmount(): ?int
    {
        return $this->evaluate($this->transaction_details)['gross_amount'];
    }
}
