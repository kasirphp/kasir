<?php

namespace Kasir\Kasir\Exceptions;

class MidtransKeyException extends \Exception
{
    public function __construct(
        string $message = 'Midtrans Client/Sever Key is invalid. Please check your config or env file.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
