<?php

namespace Kasir\Kasir\Payment\CreditCard;

class CardToken
{
    private string $token;

    private string $hash;

    public function __construct($token, $hash)
    {
        $this->token = $token;
        $this->hash = $hash;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function hash(): string
    {
        return $this->hash;
    }
}
