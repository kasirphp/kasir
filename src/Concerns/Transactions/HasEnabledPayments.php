<?php

namespace Kasir\Kasir\Concerns\Transactions;

trait HasEnabledPayments
{
    private array $available_payments = [
        'credit_card',
        'cimb_clicks',
        'bca_klikbca',
        'bca_klikpay',
        'bri_epay',
        'echannel',
        'permata_va',
        'bca_va',
        'bni_va',
        'bri_va',
        'danamon_online',
        'uob_ezpay',
        'gopay',
        'shopeepay',
        'indomaret',
        'alfamart',
        'akulaku',
        'kredivo',
    ];

    protected array | null $enabled_payments = null;

    protected array | null $disabled_payments = null;

    public function enablePayments(array | \Closure | null $payments): static
    {
        $this->enabled_payments = $payments;

        return $this;
    }

    public function disablePayments(array | \Closure | null $payments): static
    {
        $this->disabled_payments = $payments;

        return $this;
    }

    public function getEnabledPayments(): array | null
    {
        if (! is_null($this->disabled_payments) && is_null($this->enabled_payments)) {
            return array_diff($this->available_payments, $this->evaluate($this->disabled_payments));
        }

        return $this->evaluate($this->enabled_payments);
    }
}
