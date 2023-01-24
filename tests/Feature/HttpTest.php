<?php

use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Helper\Request;

it('throws MidtransKeyException when validating client/server key', function ($server_key, $message) {
    expect(fn () => Request::post('https://example.com', $server_key, []))
        ->toThrow(MidtransKeyException::class, $message);
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

it('creates notification header if exists', function ($urls) {
    Config::set('kasir.notification_url.append', $urls);
    Config::set('kasir.notification_url.override', $urls);

    $headers = Request::configureHeader('foo');
    $header_expectation = implode(',', array_slice($urls, 0, 3));

    if (! $urls) {
        expect($headers)->not->toHaveKeys([
            'X-Append-Notification',
            'X-Override-Notification',
        ]);
    } else {
        expect($headers['X-Override-Notification'])->toBeString()->toBe($header_expectation)
            ->and($headers['X-Append-Notification'])->toBeString()->toBe($header_expectation);
    }
})->with([
    'empty notification' => [[]],
    'one url' => [['https://google.com']],
    'three urls' => [['https://google.com', 'https://twitter.com', 'https://github.com']],
    'four urls' => [['https://google.com', 'https://twitter.com', 'https://github.com', 'https://example.com']],
]);
