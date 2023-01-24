<?php

namespace Kasir\Kasir\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as BaseRequest;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Psr\Http\Message\ResponseInterface;

class Request extends BaseRequest
{
    /**
     * Send a POST request.
     *
     * @param  string  $uri
     * @param  string|null  $server_key
     * @param  array|null  $data_hash
     * @return ResponseInterface
     *
     * @throws GuzzleException
     * @throws MidtransKeyException
     */
    public static function post(string $uri, string | null $server_key, array | null $data_hash = null): ResponseInterface
    {
        $client = new Client();
        $headers = static::configureHeader($server_key);
        $body = json_encode($data_hash);

        return $client->post($uri, compact('headers', 'body'));
    }

    /**
     * Configure the request header.
     *
     * @param  string|null  $server_key
     * @return array
     *
     * @throws MidtransKeyException
     */
    public static function configureHeader(string | null $server_key): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'midtrans-php-v2.5.2',
            'Authorization' => 'Basic ' . static::encodeServerKey($server_key),
        ];

        return array_merge($headers, static::configureNotificationHeader());
    }

    /**
     * Configure the notification header.
     *
     * @return array
     */
    protected static function configureNotificationHeader(): array
    {
        $headers = [];

        if (config('kasir.notification_url.append')) {
            $headers['X-Append-Notification'] = implode(
                ',',
                array_slice(config('kasir.notification_url.append'), 0, 3)
            );
        }

        if (config('kasir.notification_url.override')) {
            $headers['X-Override-Notification'] = implode(
                ',',
                array_slice(config('kasir.notification_url.override'), 0, 3)
            );
        }

        return $headers;
    }

    /**
     * Encode the Authorization Key
     *
     * @param  string|null  $server_key
     * @return string
     *
     * @throws MidtransKeyException
     */
    private static function encodeServerKey(string | null $server_key): string
    {
        if (is_null($server_key)) {
            throw new MidtransKeyException('The Server/Client Key is null. Please double check the config or env file.');
        } else {
            if ($server_key == '') {
                throw new MidtransKeyException('The Server/Client Key is empty. Please double check the config or env file.');
            } elseif (preg_match('/\s/', $server_key)) {
                throw new MidtransKeyException('The Server/Client Key contains a whitespace character. Please double check the config or env file.');
            }
        }

        return base64_encode($server_key . ':');
    }
}
