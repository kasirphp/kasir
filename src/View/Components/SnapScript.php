<?php

namespace Kasir\Kasir\View\Components;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SnapScript extends Component
{
    public string $id;

    public string $token;

    public function __construct(string $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }

    public function render(): View|Factory|Htmlable|Closure|string|Application
    {
        return view('kasir::components.snap-script');
    }
}
