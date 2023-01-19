<?php

namespace Kasir\Kasir\Contracts;

interface ShouldConfigurePayload
{
    public static function configurePayload($params): array;
}
