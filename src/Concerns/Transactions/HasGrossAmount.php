<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasGrossAmount
{
    protected int | null $gross_amount;

    public function grossAmount(int | null $gross_amount): static
    {
        $this->transaction_details['gross_amount'] = $this->evaluate($gross_amount);

        return $this;
    }

    public function getGrossAmount(): int | null
    {
        return $this->evaluate($this->transaction_details)['gross_amount'];
    }
}
