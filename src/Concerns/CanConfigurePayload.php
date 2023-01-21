<?php

namespace Kasir\Kasir\Concerns;

use Kasir\Kasir\Helper\Sanitizer;

trait CanConfigurePayload
{
    public static function configurePayload($params): array
    {
        $payloads = [
            'credit_card' => [
                'secure' => config('kasir.3ds'),
            ],
        ];

        $params = static::calculateGrossAmount($params);

        if (config('kasir.sanitize')) {
            Sanitizer::json($params);
        }

        return $params;
    }

    /**
     * Calculate transaction_details.gross_amount from item_details
     *
     * @param $params
     * @return array
     */
    public static function calculateGrossAmount($params): array
    {
        if (isset($params['item_details'])) {
            $gross_amount = 0;
            foreach ($params['item_details'] as $item) {
                $gross_amount += $item['quantity'] * $item['price'];
            }
            $params['transaction_details']['gross_amount'] = $gross_amount;
        }

        return $params;
    }
}
