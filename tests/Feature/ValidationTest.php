<?php

it('throws correct validation Exceptions', function ($kasir, $exception) {
    expect(fn () => $kasir->validate())->toThrow($exception);
})->with('validation_exceptions');
