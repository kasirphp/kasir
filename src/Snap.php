<?php

namespace Kasir\Kasir;

use Exception;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Helper\Requestor;
use Kasir\Kasir\Helper\Sanitizer;

class Snap extends Kasir
{
    /**
     * Make the request
     *
     * @throws MidtransKeyException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     * @throws ZeroGrossAmountException
     */
    public function pay()
    {
        $params = static::toArray();

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

        $payloads = array_replace_recursive($payloads, $params);

        return Requestor::post(
            static::getSnapBaseUrl().'/transactions',
            config('kasir.server_key'),
            $payloads
        );
    }

    /**
     * Redirect To Snap Page
     *
     * @return $this
     *
     * @throws NoItemDetailsException
     * @throws ZeroGrossAmountException
     * @throws NoPriceAndQuantityAttributeException
     * @throws Exception
     */
    public function redirect(): static
    {
        $url = $this->pay()->redirect_url;
        redirect($url);

        return $this;
    }

    /**
     * Get token for Snap
     *
     * @throws ZeroGrossAmountException
     * @throws MidtransKeyException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     */
    public function getToken(): string
    {
        return $this->pay()->token;
    }
}
