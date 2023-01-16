<?php

namespace Kasir\Kasir\Exceptions;

use Throwable;

class NoPriceAndQuantityAttributeException extends \Exception
{
    public function __construct(
        string $message = "item_details must have 'price' and 'quantity' attributes",
        int $code = 403,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
