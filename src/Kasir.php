<?php

namespace Kasir\Kasir;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Kasir\Kasir\Concerns\CanCalculateGrossAmount;
use Kasir\Kasir\Concerns\Endpoint;
use Kasir\Kasir\Concerns\EvaluateClosures;
use Kasir\Kasir\Concerns\InteractsWithPaymentApi;
use Kasir\Kasir\Concerns\Transactions\HasBillingAddress;
use Kasir\Kasir\Concerns\Transactions\HasCustomerDetails;
use Kasir\Kasir\Concerns\Transactions\HasDiscounts;
use Kasir\Kasir\Concerns\Transactions\HasEnabledPayments;
use Kasir\Kasir\Concerns\Transactions\HasGrossAmount;
use Kasir\Kasir\Concerns\Transactions\HasItemDetails;
use Kasir\Kasir\Concerns\Transactions\HasOrderId;
use Kasir\Kasir\Concerns\Transactions\HasPaymentMethods;
use Kasir\Kasir\Concerns\Transactions\HasShippingAddress;
use Kasir\Kasir\Concerns\Transactions\HasTaxes;
use Kasir\Kasir\Concerns\Validation;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Helper\Sanitizer;

class Kasir implements Arrayable
{
    use InteractsWithPaymentApi;
    use CanCalculateGrossAmount;
    use Endpoint;
    use EvaluateClosures;
    use HasBillingAddress;
    use HasCustomerDetails;
    use HasDiscounts;
    use HasEnabledPayments;
    use HasGrossAmount;
    use HasItemDetails;
    use HasOrderId;
    use HasPaymentMethods;
    use HasShippingAddress;
    use HasTaxes;
    use Validation;

    public function __construct(int | null $gross_amount)
    {
        $this->grossAmount($gross_amount);
        $this->orderId($order_id ?? Str::orderedUuid());
    }

    /**
     * Initialize Kasir with base Gross Amount
     *
     * @param  int|null  $gross_amount
     */
    public static function make(int | null $gross_amount = null): static
    {
        return app(static::class, [
            'gross_amount' => $gross_amount ?? null,
        ]);
    }

    /**
     * Convert passed data to an array.
     *
     *
     * @throws ZeroGrossAmountException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function toArray(): array
    {
        $this->validate();

        $array = [
            'transaction_details' => [
                'gross_amount' => $this->getGrossAmount(),
                'order_id' => $this->getOrderId(),
            ],
        ];

        if (! is_null($this->getItemDetails())) {
            $array['item_details'] = array_values($this->getItemDetails());
            $array['item_details'] = array_merge($array['item_details'], array_values($this->getDiscountDetails() ?: []));
            $array['item_details'] = array_merge($array['item_details'], array_values($this->getTaxDetails() ?: []));
        }

        if (! is_null($this->getCustomerDetails())) {
            $array['customer_details'] = $this->getCustomerDetails();
            if (! is_null($this->getBillingAddress())) {
                $array['customer_details']['billing_address'] = $this->getBillingAddress();
            }
            if (! is_null($this->getShippingAddress())) {
                $array['customer_details']['shipping_address'] = $this->getShippingAddress();
            }
        }

        if (! is_null($this->getEnabledPayments())) {
            $array['enabled_payments'] = array_values($this->getEnabledPayments());
        }

        if (! empty($this->getPaymentType())) {
            $array['payment_type'] = $this->getPaymentType();
            $array[$this->getPaymentOptionKey()] = $this->getPaymentOptions();
        }

        if (config('kasir.sanitize')) {
            Sanitizer::json($array);
        }

        return $array;
    }

    public static function fromArray(array $data): static
    {
        $static = static::make($data['transaction_details']['gross_amount'] ?? null);
        $static->orderId($data['transaction_details']['order_id'] ?? Str::orderedUuid());
        $static->itemDetails($data['item_details'] ?? null);
        $static->customerDetails($data['customer_details'] ?? null);
        $static->billingAddress($data['customer_details']['billing_address'] ?? null);
        $static->shippingAddress($data['customer_details']['shipping_address'] ?? null);
        $static->enablePayments($data['enabled_payments'] ?? null);

        return $static;
    }

    /**
     * Convert this class to Snap object.
     *
     * @throws ZeroGrossAmountException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function snap(): Snap
    {
        return Snap::fromArray($this->toArray());
    }

    /**
     * Clone this class.
     */
    public function clone(): Kasir
    {
        return clone $this;
    }

    /**
     * Clone this class.
     */
    public function copy(): Kasir
    {
        return $this->clone();
    }
}
