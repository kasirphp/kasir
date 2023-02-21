<?php

namespace Kasir\Kasir\Concerns;

trait Endpoint
{
    protected static string $SANDBOX_BASE_URL = 'https://api.sandbox.midtrans.com';

    protected static string $PRODUCTION_BASE_URL = 'https://api.midtrans.com';

    protected static string $SNAP_SANDBOX_BASE_URL = 'https://app.sandbox.midtrans.com/snap/v1';

    protected static string $SNAP_PRODUCTION_BASE_URL = 'https://app.midtrans.com/snap/v1';

    /**
     * Get Base URL for the API
     */
    public static function getBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? static::$PRODUCTION_BASE_URL
            : static::$SANDBOX_BASE_URL;
    }

    /**
     * Get Base URL for the SNAP API
     */
    public static function getSnapBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? static::$SNAP_PRODUCTION_BASE_URL
            : static::$SNAP_SANDBOX_BASE_URL;
    }
}
