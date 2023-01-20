<?php

namespace Kasir\Kasir\Helper;

use Exception;
use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Exceptions\MidtransKeyException;
use Kasir\Kasir\Helper\Deprecated\Config;

class Requestor
{
    /**
     * @throws MidtransKeyException
     */
    public static function get($url, $server_key, $data_hash)
    {
        return self::call($url, $server_key, $data_hash, 'GET');
    }

    /**
     * @throws MidtransKeyException
     */
    public static function post($url, $server_key, $data_hash)
    {
        return self::call($url, $server_key, $data_hash, 'POST');
    }

    /**
     * @throws MidtransKeyException
     */
    public static function patch($url, $server_key, $data_hash)
    {
        return self::call($url, $server_key, $data_hash, 'PATCH');
    }

    /**
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
     * @throws MidtransKeyException
     * @throws Exception
     */
    public static function call($url, $server_key, $data_hash, $method)
    {
        $ch = curl_init();

        $server_key = self::validateServerKey($server_key);

        $curl_options = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: midtrans-php-v2.5.2',
                'Authorization: Basic ' . base64_encode($server_key . ':'),
            ],
            CURLOPT_RETURNTRANSFER => 1,
        ];

        // TODO: set notifications in header

        // TODO: Merge with config curl options

        if ($method != 'GET') {
            if ($data_hash) {
                $body = json_encode($data_hash);
                $curl_options[CURLOPT_POSTFIELDS] = $body;
            } else {
                $curl_options[CURLOPT_POSTFIELDS] = '';
            }
            if ($method == 'POST') {
                $curl_options[CURLOPT_POST] = 1;
            } elseif ($method == 'PATCH') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            }
        }

        curl_setopt_array($ch, $curl_options);

        $result = curl_exec($ch);

        if ($result === false) {
            throw new MidtransKeyException('Curl Error: ' . curl_error($ch));
        } else {
            try {
                $result_array = json_decode($result);
            } catch (Exception $e) {
                throw new \Exception('Invalid JSON in API response: ' . $result);
            }
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (isset($result_array->status_code) && $result_array->status_code >= 401 && $result_array->status_code != 407) {
                throw new MidtransApiException(
                    'Midtrans API is returning API error. HTTP status code: ' . $result_array->status_code . ': ' . "'{$result_array->status_message}'",
                    $result_array->status_code
                );
            } elseif ($httpCode >= 400) {
                throw new MidtransApiException(
                    'Midtrans API is returning API error. HTTP status code: ' . $httpCode . ' API response: ' . $result,
                    $httpCode
                );
            } else {
                return $result_array;
            }
        }
    }
}
