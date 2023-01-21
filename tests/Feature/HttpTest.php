<?php

use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Helper\Http;

it('throws MidtransKeyException when validating client/server key', function ($server_key, $message) {
    expect(fn () => Http::call('http://example.com', $server_key, [], 'POST'))->toThrow(MidtransKeyException::class, $message);
})->with([
    'null' => [
        null,
        'The Server/Client Key is null. Please double check the config or env file.',
    ],
    'empty string' => [
        '',
        'The Server/Client Key is empty. Please double check the config or env file.',
    ],
    'contains whitespace' => [
        'foo bar',
        'The Server/Client Key contains a whitespace character. Please double check the config or env file.',
    ],
]);
