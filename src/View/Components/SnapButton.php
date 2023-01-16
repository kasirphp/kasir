<?php

namespace Kasir\Kasir\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SnapButton extends Component
{
    public string $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function render(): View
    {
        return view('kasir::snap-button');
    }
}
