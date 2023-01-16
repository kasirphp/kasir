<?php

namespace Kasir\Kasir;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class KasirServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishConfig();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'kasir');
        Blade::componentNamespace('Kasir\\Views\\Components', 'kasir');
        $this->publishViews();
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

    public function publishViews()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/kasir'),
        ], 'kasir-snap-button');
    }
}
