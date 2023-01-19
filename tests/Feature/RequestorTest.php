<?php

use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Helper\Requestor;

it('throws MidtransKeyException when the server/client key is not defined', function () {
    Requestor::call('http://example.com', null, [], 'POST');
})->throws(MidtransKeyException::class);
