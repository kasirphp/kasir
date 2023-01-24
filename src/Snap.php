<?php

namespace Kasir\Kasir;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Exceptions\NoItemDetailsException;
use Kasir\Kasir\Exceptions\NoPriceAndQuantityAttributeException;
use Kasir\Kasir\Exceptions\ZeroGrossAmountException;
use Kasir\Kasir\Helper\MidtransResponse;
use Kasir\Kasir\Helper\Request;

class Snap extends Kasir
{
    /**
     * Make the request
     *
     * @throws MidtransKeyException
     * @throws NoItemDetailsException
     * @throws NoPriceAndQuantityAttributeException
     * @throws ZeroGrossAmountException|GuzzleException
     */
    public function pay(): MidtransResponse
    {
        $payload = static::toArray();

        $response = Request::post(
            static::getSnapBaseUrl() . '/transactions',
            config('kasir.server_key'),
            $payload
        );

        return new MidtransResponse($response);
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
     *
     * @deprecated Use getUrl() to get URL and redirect it manually.
     */
    public function redirect(): RedirectResponse
    {
        return redirect()->away($this->getUrl());
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
        return $this->pay()->json('token');
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
    public function getUrl(): mixed
    {
        return $this->pay()->json('redirect_url');
    }
}
