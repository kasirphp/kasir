<?php

namespace Kasir\Kasir\Facades;

use Illuminate\Support\Facades\Facade;

class Kasir extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Kasir\Kasir\Kasir::class;
    }
}
