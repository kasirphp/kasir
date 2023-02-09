<?php

namespace Kasir\Kasir\Concerns;

trait CanCalculateGrossAmount
{
    public function calculateGrossAmount(): int | null
    {
        if (! $this->getItemDetails()) {
            return $this->getGrossAmount();
        } else {
            $grossAmount = 0;

            foreach ($this->getItemDetails() as $item) {
                $grossAmount += $item['price'] * $item['quantity'];
            }

            return $grossAmount;
        }
    }
}
