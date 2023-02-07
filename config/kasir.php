<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Key
    |--------------------------------------------------------------------------
    |
    | This value is your merchant's server and client key. These values are
    | used when the package needs to interact with Midtrans. You can get your
    | merchant's key at Midtrans' dashboard settings page.
    |
    */

    'client_key' => config('kasir.production_mode') === true
        ? env('MIDTRANS_CLIENT_KEY')
        : env('MIDTRANS_CLIENT_KEY_SANDBOX'),
    'server_key' => config('kasir.production_mode') === true
        ? env('MIDTRANS_SERVER_KEY')
        : env('MIDTRANS_SERVER_KEY_SANDBOX'),

    /*
    |--------------------------------------------------------------------------
    | Kasir Production Mode
    |--------------------------------------------------------------------------
    |
    | This value determines the "production" environment the Kasir package
    | currently running in. This key may determine your Kasir is in Production
    | Mode. Set this value to true for production mode. Default: false.
    |
    */

    'production_mode' => env('KASIR_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Sanitation
    |--------------------------------------------------------------------------
    |
    | This configuration takes a boolean that activates a function that will
    | sanitizes JSON input by removing any key-value pairs that do not conform
    | to a predefined set of rules. The purpose is to ensure that the input
    | data is clean and safe to use in further processing.
    |
    */

    'sanitize' => true,

    /*
    |--------------------------------------------------------------------------
    | 3-D Secure
    |--------------------------------------------------------------------------
    |
    | Enables 3D-Secure. This typically involves the customer entering a
    | One-Time-Password (OTP) or a similar form of authentication. Once the
    | customer has been authenticated, they are then redirected back to the
    | merchant's website, and the transaction is completed.
    |
    */

    '3ds' => true,

    /*
    |--------------------------------------------------------------------------
    | Notification URL
    |--------------------------------------------------------------------------
    |
    | HTTP POST notifications or Webhooks are sent to your server when the
    | customer when transaction status changes. These notifications help you
    | to update payment status or take suitable actions in real-time.
    |
    */

    'notification_url' => [
        'append' => [],
        'override' => [],
    ],
];
