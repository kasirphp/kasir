<?php

namespace Kasir\Kasir;

use Illuminate\Http\RedirectResponse;
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
            static::getSnapBaseUrl() . '/transactions',
            config('kasir.server_key'),
            $payloads
        );
    }

    /**
     * Redirect To Snap Page
     *
     * @return RedirectResponse
     *
     * @throws MidtransKeyException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     * @throws ZeroGrossAmountException
     */
    public function redirect(): RedirectResponse
    {
        $url = $this->getUrl();

        return redirect()->away($url);
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

    /**
     * Get Redirect Url for Snap
     *
     * @return mixed
     *
     * @throws MidtransKeyException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     * @throws ZeroGrossAmountException
     */
    public function getUrl()
    {
        return $this->pay()->redirect_url;
    }
}
