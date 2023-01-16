<?php

namespace Kasir\Kasir\Exceptions;

use Throwable;

class ZeroGrossAmountException extends \Exception
{
    public function __construct(
        string $message = 'if item_details is not set, gross_amount must be equal to or greater than 0.01',
        int $code = 403,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
