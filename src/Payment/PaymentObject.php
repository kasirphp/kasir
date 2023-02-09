<?php

namespace Kasir\Kasir\Payment;

use Illuminate\Support\Str;
use ReflectionClass;

abstract class PaymentObject
{
    public string | null $type = null;

    public string | null $option_key = null;

    public array | null $options = null;

    public function __construct(...$options)
    {
        $this->type = Str::snake((new ReflectionClass($this))->getShortName());
        $this->options = $options;
    }

    public function type(string $name): static
    {
        $this->type = $name;

        return $this;
    }

    public function optionKey(string $key): static
    {
        $this->option_key = $key;

        return $this;
    }

    public function options($options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getType(): string | null
    {
        return $this->type;
    }

    public function getOptionKey(): string | null
    {
        return $this->option_key;
    }

    public function getOptions(): array | null
    {
        return $this->options;
    }
}
