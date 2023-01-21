<?php

namespace Kasir\Kasir\Helper;

use GuzzleHttp\Promise\PromiseInterface;
use Http as BaseHttp;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Exceptions\MidtransKeyException;

class Http
{
    /**
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @return Response|PromiseInterface
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function get($url, $server_key, $data_hash): Response | PromiseInterface
    {
        $response = self::request($server_key, $data_hash)->get($url);
        self::validateResponse($response);

        return $response;
    }

    /**
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @return PromiseInterface|Response
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function post($url, $server_key, $data_hash): PromiseInterface | Response
    {
        $response = self::request($server_key, $data_hash)->post($url);
        self::validateResponse($response);

        return $response;
    }

    /**
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @return PromiseInterface|Response
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function patch($url, $server_key, $data_hash): PromiseInterface | Response
    {
        $response = self::request($server_key, $data_hash)->patch($url);
        self::validateResponse($response);

        return $response;
    }

    /**
     * Validates the server and client key
     *
     * @param $server_key
     * @return string
     *
     * @throws MidtransKeyException
     */
    private static function validateServerKey($server_key): string
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

        return $server_key;
    }

    /**
     * Make the request
     *
     * @param $server_key
     * @param $data_hash
     * @return PendingRequest
     *
     * @throws MidtransKeyException
     */
    public static function request($server_key, $data_hash): PendingRequest
    {
        $server_key = self::validateServerKey($server_key);

        return BaseHttp::withBasicAuth($server_key, '')
            ->accept('application/json')
            ->withUserAgent('midtrans-php-v2.5.2')
            ->withHeaders(static::notificationHeader())
            ->withBody(json_encode($data_hash), 'application/json');
    }

    /**
     * Set Notification Header for the Request
     *
     * @return array
     */
    public static function notificationHeader(): array
    {
        $header = [];

        if (config('kasir.notification_url.append')) {
            $header['X-Append-Notification'] = implode(
                ',',
                array_slice(config('kasir.notification_url.append'), 0, 3)
            );
        }

        if (config('kasir.notification_url.override')) {
            $header['X-Override-Notification'] = implode(
                ',',
                array_slice(config('kasir.notification_url.override'), 0, 3)
            );
        }

        return $header;
    }

    /**
     * Validates Response
     *
     * @param $response
     * @return void
     *
     * @throws MidtransApiException
     */
    public static function validateResponse($response): void
    {
        if ($response->failed()) {
            if (array_key_exists('error_messages', $response->json())) {
                throw new MidtransApiException(
                    $response->status() . ' ' . $response->reason() . ': '
                    . implode('. ', $response->object()->error_messages) . '.',
                    $response->status()
                );
            } elseif (array_key_exists('status_messages', $response->json())) {
                throw new MidtransApiException(
                    $response->status() . ' ' . $response->reason() . ': '
                    . implode('. ', $response->object()->status_messages) . '.',
                    $response->status()
                );
            } else {
                throw new MidtransApiException(
                    $response->status() . ' ' . $response->reason() . ': '
                    . implode('. ', $response->json()) . '.',
                    $response->status()
                );
            }
        }
    }
}
