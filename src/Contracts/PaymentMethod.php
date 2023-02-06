<?php

namespace Kasir\Kasir\Contracts;

interface PaymentMethod
{
    public function __construct(...$options);

    public function optionKey(string $key): static;

    public function options(array $options): static;

    public function getType(): string | null;

    public function getOptionKey(): string | null;

    public function getOptions(): array | null;
}
