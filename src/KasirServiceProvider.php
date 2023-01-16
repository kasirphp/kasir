<?php

namespace Kasir\Kasir;

use Illuminate\Support\ServiceProvider;

class KasirServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishConfig();
    }

    public function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/kasir.php' => config_path('kasir.php'),
        ], 'kasir-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/kasir.php', 'kasir'
        );
    }
}
