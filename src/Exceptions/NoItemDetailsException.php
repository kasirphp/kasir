<?php

namespace Kasir\Kasir\Exceptions;

class NoItemDetailsException extends \Exception
{
    public function __construct(
        string $message = 'if gross_amount is not set, item_details must not null',
        int $code = 403,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
