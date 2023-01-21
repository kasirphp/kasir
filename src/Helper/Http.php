<?php

namespace Kasir\Kasir\Helper;

use Http as BaseHttp;
use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Str;

class Http
{
    /**
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @return mixed
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function get($url, $server_key, $data_hash): mixed
    {
        return self::call($url, $server_key, $data_hash, 'GET');
    }

    /**
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @return mixed
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function post($url, $server_key, $data_hash): mixed
    {
        return self::call($url, $server_key, $data_hash, 'POST');
    }

    /**
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @return mixed
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function patch($url, $server_key, $data_hash): mixed
    {
        return self::call($url, $server_key, $data_hash, 'PATCH');
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
     * @param $url
     * @param $server_key
     * @param $data_hash
     * @param $method
     * @return mixed
     *
     * @throws MidtransApiException
     * @throws MidtransKeyException
     */
    public static function call($url, $server_key, $data_hash, $method): mixed
    {
        $server_key = self::validateServerKey($server_key);
        $method = Str::lower($method);

        $response = BaseHttp::withBasicAuth($server_key, '')
            ->accept('application/json')
            ->withUserAgent('midtrans-php-v2.5.2')
            ->withBody(json_encode($data_hash), 'application/json')
            ->$method($url);

        // TODO: set notifications in header

        // TODO: Merge with config curl options

        self::validateResponse($response);

        return $response;
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
                $messages = [];
                foreach ($response->json() as $value) {
                    $messages[] = implode('. ', $value);
                }

                throw new MidtransApiException(
                    $response->status() . ' ' . $response->reason() . ': '
                    . implode('. ', $messages) . '.',
                    $response->status()
                );
            }
        }
    }
}
