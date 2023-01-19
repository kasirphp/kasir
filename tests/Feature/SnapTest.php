<?php

use Kasir\Kasir\Snap;

it('can request snap token and redirect url', function () {
    $snap = Snap::make(1);

    expect($snap->pay())->toHaveKeys(['token', 'redirect_url'])
        ->and($snap->getToken())->toBeString()
        ->and($snap->redirect())->toBeValidUrl();
});
