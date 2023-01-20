<?php

namespace Kasir\Kasir\Concerns;

use Kasir\Kasir\Concerns\Transactions\HasGrossAmount;
use Kasir\Kasir\Concerns\Transactions\HasItemDetails;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;

trait Validation
{
    use HasGrossAmount;
    use HasItemDetails;

    /**
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     * @throws ZeroGrossAmountException
     */
    public function validate(): static
    {
        if (($this->getGrossAmount() === null || ! $this->getGrossAmount() > 0) && $this->getItemDetails() === null) {
            throw new ZeroGrossAmountException();
        }

        if (($this->getGrossAmount() === null || ! $this->getGrossAmount() > 0)) {
            $item_details_has_price_and_quantity_attr = array_map(
                fn ($a) => array_key_exists('price', $a) && array_key_exists('quantity', $a),
                $this->getItemDetails()
            );

            if (in_array(false, $item_details_has_price_and_quantity_attr)) {
                throw new NoPriceAndQuantityAttributeException();
            }

            throw new NoItemDetailsException();
        }

        return $this;
    }
}
