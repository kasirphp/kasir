<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasGrossAmount
{
    protected int | null $gross_amount;

    public function grossAmount(int | null $gross_amount): static
    {
        $this->gross_amount = $gross_amount;
        $this->gross_amount = $this->calculateGrossAmount();

        return $this;
    }

    public function getGrossAmount(): int | null
    {
        return $this->evaluate($this->gross_amount);
    }
}
