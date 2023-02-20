<?php

namespace Kasir\Kasir\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as BaseRequest;
use Kasir\Kasir\Exceptions\MidtransKeyException;

class Request extends BaseRequest
{
    /**
     * Send a GET request.
     *
     * @param  array|null  $data_hash
     *
     * @throws GuzzleException
     * @throws MidtransKeyException
     */
    public static function get(string $uri, string | null $server_key, array | null $data_hash = null): MidtransResponse
    {
        $client = new Client();
        $headers = static::configureHeader($server_key);
        $body = json_encode($data_hash);

        $response = $client->get($uri, compact('headers', 'body'));

        return new MidtransResponse($response);
    }

    /**
     * Send a POST request.
     *
     * @param  array|null  $data_hash
     *
     * @throws GuzzleException
     * @throws MidtransKeyException
     */
    public static function post(string $uri, string | null $server_key, array | null $data_hash = null): MidtransResponse
    {
        $client = new Client();
        $headers = static::configureHeader($server_key);
        $body = json_encode($data_hash);

        $response = $client->post($uri, compact('headers', 'body'));

        return new MidtransResponse($response);
    }

    /**
     * Send a PATCH request.
     *
     * @param  array|null  $data_hash
     *
     * @throws GuzzleException
     * @throws MidtransKeyException
     */
    public static function patch(string $uri, string | null $server_key, array | null $data_hash = null): MidtransResponse
    {
        $client = new Client();
        $headers = static::configureHeader($server_key);
        $body = json_encode($data_hash);

        $response = $client->patch($uri, compact('headers', 'body'));

        return new MidtransResponse($response);
    }

    /**
     * Configure the request header.
     *
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
