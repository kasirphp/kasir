<?php

namespace Kasir\Kasir\Payment\CreditCard;

use Illuminate\Support\Str;
use Kasir\Kasir\Helper\MidtransResponse;
use Kasir\Kasir\Helper\Request;
use Kasir\Kasir\Kasir;

class CreditCard
{
    private string $cardNumber;

    private string $cardExpMonth;

    private string $cardExpYear;

    private string $cardCvv;

    public string | null $token = null;

    public string | null$hash = null;

    public function __construct(string $cardNumber, string $cardExpMonth, string $cardExpYear, string $cardCvv)
    {
        $this->cardNumber = Str::replace(' ', '', $cardNumber);
        $this->cardExpMonth = Str::replace(' ', '', $cardExpMonth);
        $this->cardExpYear = Str::replace(' ', '', $cardExpYear);
        $this->cardCvv = Str::replace(' ', '', $cardCvv);
    }

    public static function make(string $cardNumber, string $ExpMonth, string $ExpYear, string $cvv): static
    {
        return app(static::class, [
            'cardNumber' => $cardNumber,
            'cardExpMonth' => $ExpMonth,
            'cardExpYear' => $ExpYear,
            'cardCvv' => $cvv,
        ]);
    }

    public function getToken(): CardToken | MidtransResponse
    {
        $path = '?card_number=' . $this->cardNumber
            . '&card_exp_month=' . $this->cardExpMonth
            . '&card_exp_year=' . $this->cardExpYear
            . '&card_cvv=' . $this->cardCvv
            . '&client_key=' . config('kasir.client_key');

        $response = Request::get(
            Kasir::getBaseUrl() . '/v2/token' . $path,
            config('kasir.client_key'),
            null
        );

        if ($response->successful()) {
            $this->token = $response->json('token_id');
            $this->hash = $response->json('hash');

            return new CardToken($response->json('token_id'), $response->json('hash'));
        }

        return $response;
    }

    public function token(): string
    {
        return $this->token ?: $this->getToken()->token();
    }

    public function hash(): string
    {
        return $this->hash ?: $this->getToken()->hash();
    }
}
