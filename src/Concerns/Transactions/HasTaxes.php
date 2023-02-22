<?php

namespace Kasir\Kasir\Concerns\Transactions;

use Closure;

trait HasTaxes
{
    private int $tax_id = 0;

    protected array | null $taxes = null;

    protected array | null $tax_details = null;

    /**
     * Add a tax to the transaction.
     *
     * @param  int  $amount  Amount of tax.
     * @param  bool  $percentage  Whether the tax is a percentage or fixed value.
     * @param  string|null  $name  Name of the tax.
     * @param  string|null  $id  ID of the tax.
     * @return $this
     */
    public function tax(
        int $amount,
        bool $percentage = false,
        string | null $name = null,
        string | null $id = null
    ): static {
        $this->tax_id++;

        $tax = [
            'id' => $id ?? 'tax#' . $this->tax_id,
            'name' => $name ?? 'Tax #' . $this->tax_id,
            'amount' => $amount,
            'percentage' => $percentage,
        ];

        $this->taxes[] = $tax;

        $this->calculateTaxedPrice($amount, $percentage, $name, $id);

        return $this;
    }

    /**
     * Add multiple taxes to the transaction.
     *
     * @param  array|Closure|null  $taxes  Taxes to add.
     * @return $this
     */
    public function taxes(array | Closure | null $taxes): static
    {
        $taxes = $this->evaluate($taxes);
        $this->taxes = array_merge($this->getTaxes() ?: [], $taxes);

        foreach ($taxes as $tax) {
            $this->tax_id++;
            $this->calculateTaxedPrice(
                $tax['amount'],
                $tax['percentage'],
                $tax['name'],
                $tax['id']
            );
        }

        return $this;
    }

    /**
     * Get the taxes.
     */
    public function getTaxes(): array | null
    {
        return $this->evaluate($this->taxes);
    }

    /**
     * Get the taxes that will be appended to the item details.
     */
    public function getTaxDetails(): array | null
    {
        return $this->evaluate($this->tax_details);
    }

    /**
     * Calculate the taxed gross_amount and append the tax to tax_details. Do not call this method directly.
     *
     * @param  int  $amount  Amount of tax.
     * @param  bool  $percentage  Whether the tax is a percentage or fixed value.
     * @param  string|null  $name  Name of the tax.
     * @param  string|null  $id  ID of the tax.
     */
    public function calculateTaxedPrice(
        int $amount,
        bool $percentage = false,
        string | null $name = null,
        string | null $id = null
    ): void {
        if ($percentage) {
            $amount = (int) ($this->gross_amount * ($amount / 100));
        }
        $amount = abs($amount);
        $this->gross_amount += $amount;

        $this->tax_details[] = [
            'id' => $id ?? 'tax#' . $this->tax_id,
            'name' => $name ?? 'Tax #' . $this->tax_id,
            'price' => $amount,
            'quantity' => 1,
        ];
    }

    public function recalculateTaxedPrice(): void
    {
        $this->tax_details = null;
        if (! is_null($this->getTaxes())) {
            foreach ($this->getTaxes() as $tax) {
                $this->calculateTaxedPrice(
                    $tax['amount'],
                    $tax['percentage'],
                    $tax['name'],
                    $tax['id']
                );
            }
        }
    }

    public function reverseCalculateTaxedPrice(): void
    {
        if (! is_null($this->getTaxDetails())) {
            foreach ($this->getTaxDetails() as $tax) {
                $amount = $tax['price'];
                $amount = abs($amount);
                $this->gross_amount -= $amount;
            }
        }
    }
}
