<?php

it('throws MidtransKeyException when the server/client key is not defined', function () {
    $snap = \Kasir\Kasir\Snap::make(1000);

    $snap->pay();
})->throws(\Kasir\Kasir\Exceptions\MidtransKeyException::class);
