<?php

namespace Kasir\Kasir\Tests;

use Kasir\Kasir\KasirServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            KasirServiceProvider::class,
        ];
    }
}
