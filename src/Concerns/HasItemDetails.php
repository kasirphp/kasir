<?php

namespace Kasir\Kasir\Concerns;

trait HasItemDetails
{
    protected array|\Closure|null $item_details = null;

    public function itemDetails(array|\Closure|null $item_details): static
    {
        $this->item_details = $item_details;

        return $this;
    }

    public function getItemDetails(): array|null
    {
        return array_values($this->evaluate($this->item_details));
    }
}
