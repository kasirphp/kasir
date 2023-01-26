<?php

use Kasir\Kasir\Helper\MidtransResponse;
use Kasir\Kasir\Snap;

it('can request snap token and redirect url', function () {
    $snap = Snap::make(1);

    expect($snap->pay())->tobeInstanceOf(MidtransResponse::class)
        ->and($snap->getToken())->toBeString()->not->toBeNull()
        ->and($snap->getUrl())->toBeString()->not->toBeNull();
});
