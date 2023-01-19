<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasTransactionDetails
{
    use HasGrossAmount;
    use HasOrderId;

    protected array | \Closure $transaction_details;

    public function transactionDetails(array | \Closure $transaction_details): static
    {
        $this->transaction_details = $transaction_details;

        return $this;
    }

    public function getTransactionDetails(): array
    {
        return $this->evaluate($this->transaction_details);
    }
}
