<?php

namespace Kasir\Kasir\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Kasir\Kasir\KasirServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            KasirServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        $app['config']->set('kasir.server_key', env('MIDTRANS_SERVER_KEY_SANDBOX'));
        $app['config']->set('kasir.client_key', env('MIDTRANS_CLIENT_KEY_SANDBOX'));
    }
}
