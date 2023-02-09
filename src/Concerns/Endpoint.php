<?php

namespace Kasir\Kasir\Concerns;

trait Endpoint
{
    /**
     * Get Base URL for the API
     *
     * @return string
     */
    public static function getBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::PRODUCTION_BASE_URL
            : self::SANDBOX_BASE_URL;
    }

    /**
     * Get Base URL for the SNAP API
     *
     * @return string
     */
    public static function getSnapBaseUrl(): string
    {
        return config('kasir.production_mode') === true
            ? self::SNAP_PRODUCTION_BASE_URL
            : self::SNAP_SANDBOX_BASE_URL;
    }
}
