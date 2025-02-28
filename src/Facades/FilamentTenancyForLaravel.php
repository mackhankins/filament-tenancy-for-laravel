<?php

namespace Saasykit\FilamentTenancyForLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Saasykit\FilamentTenancyForLaravel\FilamentTenancyForLaravel
 */
class FilamentTenancyForLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Saasykit\FilamentTenancyForLaravel\FilamentTenancyForLaravel::class;
    }
}
