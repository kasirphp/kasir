<?php

namespace Kasir\Kasir;

use Exception;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Helper\Requestor;

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
        $payloads = static::configurePayload(static::toArray());

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
