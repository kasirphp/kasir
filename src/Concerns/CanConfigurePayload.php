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

        if (isset($params['item_details'])) {
            $gross_amount = 0;
            foreach ($params['item_details'] as $item) {
                $gross_amount += $item['quantity'] * $item['price'];
            }
            $params['transaction_details']['gross_amount'] = $gross_amount;
        }

        if (config('kasir.sanitize')) {
            Sanitizer::json($params);
        }

        return array_replace_recursive($payloads, $params);
    }
}
