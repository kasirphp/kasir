<?php

namespace Kasir\Kasir;

use Illuminate\Support\Str;
use Kasir\Kasir\Concerns\Configurable;
use Kasir\Kasir\Concerns\EvaluateClosures;
use Kasir\Kasir\Concerns\HasItemDetails;
use Kasir\Kasir\Concerns\HasTransactionDetails;
use Kasir\Kasir\Concerns\Validation;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;

class Kasir
{
    use Configurable;
    use EvaluateClosures;
    use HasItemDetails;
    use HasTransactionDetails;
    use Validation;

    const SANDBOX_BASE_URL = 'https://api.sandbox.midtrans.com';

    const PRODUCTION_BASE_URL = 'https://api.midtrans.com';

    const SNAP_SANDBOX_BASE_URL = 'https://app.sandbox.midtrans.com/snap/v1';

    const SNAP_PRODUCTION_BASE_URL = 'https://app.midtrans.com/snap/v1';

    public function __construct(?int $gross_amount)
    {
        $this->grossAmount($gross_amount);
        $this->orderId($order_id ?? Str::orderedUuid());
    }

    /**
     * Initialize Kasir with base Gross Amount
     *
     * @param  int|null  $gross_amount
     * @return static
     */
    public static function make(?int $gross_amount = null): static
    {
        $static = app(static::class, [
            'gross_amount' => $gross_amount ?? null,
        ]);
        $static->configure();

        return $static;
    }

    /**
     * Convert passed data to an array.
     *
     * @return array
     *
     * @throws ZeroGrossAmountException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function toArray(): array
    {
        try {
            $this->validate();
        } catch (NoItemDetailsException|ZeroGrossAmountException|NoPriceAndQuantityAttributeException $exception) {
            throw new $exception();
        }

        $array = [
            'transaction_details' => $this->transaction_details,
        ];

        if (! is_null($this->getItemDetails())) {
            $array['item_details'] = $this->getItemDetails();
        }

        return $array;
    }

    /**
     * Get Base URL for the API
     *
     * @return string
     */
    public static function getBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::PRODUCTION_BASE_URL
            : self::SANDBOX_BASE_URL;
    }

    /**
     * Get Base URL for the SNAP API
     *
     * @return string
     */
    public static function getSnapBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::SNAP_PRODUCTION_BASE_URL
            : self::SNAP_SANDBOX_BASE_URL;
    }
}
