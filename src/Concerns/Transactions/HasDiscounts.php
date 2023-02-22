<?php

namespace Kasir\Kasir\Concerns\Transactions;

use Closure;

trait HasDiscounts
{
    private int $discount_id = 0;

    protected array | null $discounts = null;

    protected array | null $discount_details = null;

    /**
     * Add a discount to the transaction.
     *
     * @param  int  $amount  Amount of discount.
     * @param  bool  $percentage  Whether the discount is a percentage or fixed value.
     * @param  string|null  $name  Name of the discount.
     * @param  string|null  $id  ID of the discount.
     * @return $this
     */
    public function discount(
        int $amount,
        bool $percentage = false,
        string | null $name = null,
        string | null $id = null
    ): static {
        $this->discount_id++;

        $discount = [
            'id' => $id ?? 'discount#' . $this->discount_id,
            'name' => $name ?? 'Discount #' . $this->discount_id,
            'amount' => $amount,
            'percentage' => $percentage,
        ];

        $this->discounts[] = $discount;

        if ($this->getTaxes()) {
            $this->reverseCalculateTaxedPrice();
            $this->calculateDiscountedPrice($amount, $percentage, $name, $id);
            $this->recalculateTaxedPrice();
        } else {
            $this->calculateDiscountedPrice($amount, $percentage, $name, $id);
        }

        return $this;
    }

    /**
     * Add multiple discounts to the transaction.
     *
     * @param  array|Closure|null  $discounts  Discounts to add.
     * @return $this
     */
    public function discounts(array | Closure | null $discounts): static
    {
        $discounts = $this->evaluate($discounts);
        $this->discounts = array_merge($this->getDiscounts() ?: [], $discounts);

        $this->reverseCalculateTaxedPrice();
        foreach ($discounts as $discount) {
            $this->discount_id++;
            $this->calculateDiscountedPrice(
                $discount['amount'],
                $discount['percentage'],
                $discount['name'],
                $discount['id']
            );
        }
        $this->recalculateTaxedPrice();

        return $this;
    }

    /**
     * Get the discounts.
     */
    public function getDiscounts(): array | null
    {
        return $this->evaluate($this->discounts);
    }

    /**
     * Get the discounts that will be appended to the item details.
     */
    public function getDiscountDetails(): array | null
    {
        return $this->evaluate($this->discount_details);
    }

    /**
     * Calculate the discounted gross_amount and append the discount to discount_details. Do not call this method directly.
     *
     * @param  int  $amount  Amount of discount.
     * @param  bool  $percentage  Whether the discount is a percentage or fixed value.
     * @param  string|null  $name  Name of the discount.
     * @param  string|null  $id  ID of the discount.
     */
    public function calculateDiscountedPrice(
        int $amount,
        bool $percentage = false,
        string | null $name = null,
        string | null $id = null
    ): void {
        if ($percentage) {
            $amount = (int) ($this->gross_amount * ($amount / 100));
        }
        $amount = -1 * abs($amount);
        $this->gross_amount += $amount;

        $this->discount_details[] = [
            'id' => $id ?? 'discount#' . $this->discount_id,
            'name' => $name ?? 'Discount #' . $this->discount_id,
            'price' => $amount,
            'quantity' => 1,
        ];
    }
}
